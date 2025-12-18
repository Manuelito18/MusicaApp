<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../config/database.php';

function sanitizeString($value) {
    if ($value === null) return '';
    return trim(strip_tags((string)$value));
}

try {
    // Requiere sesión (JWT)
    AuthMiddleware::handle();
    $userId = (int)($_SERVER['USER_ID'] ?? 0);
    if ($userId <= 0) {
        http_response_code(401);
        echo json_encode(["error" => "No autorizado"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data || !isset($data['items']) || !is_array($data['items']) || count($data['items']) === 0) {
        http_response_code(400);
        echo json_encode(["error" => "Items requeridos"]);
        exit;
    }

    $db = Database::connect();
    $db->beginTransaction();

    // Empresa por defecto (la primera)
    $empresaRow = $db->query("SELECT MIN(idempresa) AS id FROM datosempresa")->fetch(PDO::FETCH_ASSOC);
    $empresaId = (int)($empresaRow['id'] ?? 1);
    if ($empresaId <= 0) $empresaId = 1;

    // Estado Pedido: 2 = Pagado (según data.sql)
    $estadoPedidoId = 2;

    $preparedItems = [];
    $total = 0.0;

    $stmtGetProd = $db->prepare("SELECT idproducto, precio, stock FROM producto WHERE idproducto = ?");
    $stmtInsProd = $db->prepare("
        INSERT INTO producto
        (idproducto, nombre, descripcion, precio, stock, imagenurl, idcategoria, idmarca, idestadoproducto)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($data['items'] as $item) {
        $productId = (int)($item['id'] ?? 0);
        $qty = (int)($item['quantity'] ?? 0);

        if ($productId <= 0 || $qty <= 0) {
            throw new Exception("Item inválido (id/cantidad)");
        }

        // Si no existe el producto en BD, lo creamos como placeholder para poder registrar la venta
        $stmtGetProd->execute([$productId]);
        $prod = $stmtGetProd->fetch(PDO::FETCH_ASSOC);

        $name = sanitizeString($item['nombre'] ?? ("Producto #" . $productId));
        $desc = sanitizeString($item['descripcion'] ?? '');
        $img = sanitizeString($item['imagen'] ?? '');
        $priceFromClient = (float)($item['precio'] ?? 0);

        if (!$prod) {
            $initialStock = max(100, $qty);
            $categoriaId = 1;
            $marcaId = 1;
            $estadoProductoId = 1; // Disponible

            if ($priceFromClient <= 0) {
                $priceFromClient = 1;
            }

            $stmtInsProd->execute([
                $productId,
                $name,
                $desc,
                $priceFromClient,
                $initialStock,
                $img,
                $categoriaId,
                $marcaId,
                $estadoProductoId
            ]);

            $prod = [
                'idproducto' => $productId,
                'precio' => $priceFromClient,
                'stock' => $initialStock
            ];
        }

        $precioUnit = (float)$prod['precio'];
        $stock = (int)$prod['stock'];

        if ($stock < $qty) {
            throw new Exception("Stock insuficiente para el producto {$productId}");
        }

        $subtotal = $precioUnit * $qty;
        $total += $subtotal;

        $preparedItems[] = [
            'idproducto' => $productId,
            'cantidad' => $qty,
            'preciounitario' => $precioUnit,
            'subtotal' => $subtotal,
        ];
    }

    // Crear pedido
    $stmtPedido = $db->prepare("
        INSERT INTO pedido (idusuario, idcarrito, total, idestadopedido, idempresa)
        VALUES (?, ?, ?, ?, ?)
        RETURNING idpedido
    ");
    $stmtPedido->execute([$userId, null, $total, $estadoPedidoId, $empresaId]);
    $pedidoRow = $stmtPedido->fetch(PDO::FETCH_ASSOC);
    $idPedido = (int)($pedidoRow['idpedido'] ?? 0);

    if ($idPedido <= 0) {
        throw new Exception("No se pudo crear el pedido");
    }

    // Insertar detalle y actualizar stock
    $stmtDetalle = $db->prepare("
        INSERT INTO detallepedido (idpedido, idproducto, cantidad, preciounitario, subtotal)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmtStock = $db->prepare("UPDATE producto SET stock = stock - ? WHERE idproducto = ?");

    foreach ($preparedItems as $pi) {
        $stmtDetalle->execute([$idPedido, $pi['idproducto'], $pi['cantidad'], $pi['preciounitario'], $pi['subtotal']]);
        $stmtStock->execute([$pi['cantidad'], $pi['idproducto']]);
    }

    // Sincronizar secuencia de producto por si insertamos IDs manuales
    $db->query("SELECT setval(pg_get_serial_sequence('producto','idproducto'), (SELECT COALESCE(MAX(idproducto), 1) FROM producto))");

    $db->commit();

    http_response_code(201);
    echo json_encode([
        "message" => "Pedido creado correctamente",
        "idPedido" => $idPedido,
        "total" => $total
    ]);
} catch (Exception $e) {
    if (isset($db) && $db instanceof PDO && $db->inTransaction()) {
        $db->rollBack();
    }
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}


