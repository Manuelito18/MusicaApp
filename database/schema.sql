-- =============================================================
-- MIGRACIÓN A POSTGRESQL - MUSICSHOP
-- =============================================================

-- TABLAS MAESTRAS (Dependencias)

CREATE TABLE TipoDocumento (
    IdTipoDocumento SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL
);

CREATE TABLE Categoria (
    IdCategoria SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL,
    Descripcion VARCHAR(200)
);

CREATE TABLE Marca (
    IdMarca SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL,
    Descripcion VARCHAR(200),
    PaisOrigen VARCHAR(50)
);

CREATE TABLE EstadoProducto (
    IdEstadoProducto SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL
);

CREATE TABLE EstadoCarrito (
    IdEstadoCarrito SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL
);

CREATE TABLE DatosEmpresa (
    IdEmpresa SERIAL PRIMARY KEY,
    NombreComercial VARCHAR(100) NOT NULL,
    RUC VARCHAR(20) NOT NULL,
    Direccion VARCHAR(200)
);

CREATE TABLE Cliente (
    IdCliente SERIAL PRIMARY KEY,
    Nombres VARCHAR(100) NOT NULL,
    Apellidos VARCHAR(100) NOT NULL,
    IdTipoDocumento INT NOT NULL,
    NumeroDocumento VARCHAR(20) NOT NULL UNIQUE,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Telefono VARCHAR(20),
    FOREIGN KEY (IdTipoDocumento) REFERENCES TipoDocumento(IdTipoDocumento)
);

CREATE TABLE Producto (
    IdProducto SERIAL PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Descripcion TEXT,
    Precio DECIMAL(10,2) NOT NULL,
    Stock INT NOT NULL DEFAULT 0,
    ImagenURL VARCHAR(255),
    IdCategoria INT NOT NULL,
    IdMarca INT NOT NULL,
    IdEstadoProducto INT NOT NULL,
    FechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IdCategoria) REFERENCES Categoria(IdCategoria),
    FOREIGN KEY (IdMarca) REFERENCES Marca(IdMarca),
    FOREIGN KEY (IdEstadoProducto) REFERENCES EstadoProducto(IdEstadoProducto)
);

CREATE TABLE Carrito (
    IdCarrito SERIAL PRIMARY KEY,
    IdCliente INT NOT NULL,
    FechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    IdEstadoCarrito INT NOT NULL,
    FOREIGN KEY (IdCliente) REFERENCES Cliente(IdCliente),
    FOREIGN KEY (IdEstadoCarrito) REFERENCES EstadoCarrito(IdEstadoCarrito)
);

-- =============================================================
-- TABLAS ORIGINALES DEL SCRIPT (MIGRADAS)
-- =============================================================

-- TABLAS: EstadoPedido / Pedido / DetallePedido

CREATE TABLE EstadoPedido (
    IdEstadoPedido SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL
);

CREATE TABLE Pedido (
    IdPedido SERIAL PRIMARY KEY,
    IdCliente INT NOT NULL,
    IdCarrito INT NULL,
    Fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Total DECIMAL(10,2),
    IdEstadoPedido INT NOT NULL,
    IdEmpresa INT NOT NULL,
    FOREIGN KEY (IdCliente) REFERENCES Cliente(IdCliente),
    FOREIGN KEY (IdCarrito) REFERENCES Carrito(IdCarrito),
    FOREIGN KEY (IdEstadoPedido) REFERENCES EstadoPedido(IdEstadoPedido),
    FOREIGN KEY (IdEmpresa) REFERENCES DatosEmpresa(IdEmpresa)
);

CREATE TABLE DetallePedido (
    IdPedido INT NOT NULL,
    IdProducto INT NOT NULL,
    Cantidad INT NOT NULL,
    PrecioUnitario DECIMAL(10,2) NOT NULL,
    Subtotal DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (IdPedido, IdProducto),
    FOREIGN KEY (IdPedido) REFERENCES Pedido(IdPedido) ON DELETE CASCADE,
    FOREIGN KEY (IdProducto) REFERENCES Producto(IdProducto)
);

-- TABLAS: MetodoPago / EstadoPago / Pago

CREATE TABLE MetodoPago (
    IdMetodoPago SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL
);

CREATE TABLE EstadoPago (
    IdEstadoPago SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL
);

CREATE TABLE Pago (
    IdPago SERIAL PRIMARY KEY,
    IdPedido INT NOT NULL,
    FechaPago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    IdMetodoPago INT NOT NULL,
    Monto DECIMAL(10,2) NOT NULL,
    IdEstadoPago INT NOT NULL,
    FOREIGN KEY (IdPedido) REFERENCES Pedido(IdPedido) ON DELETE CASCADE,
    FOREIGN KEY (IdMetodoPago) REFERENCES MetodoPago(IdMetodoPago),
    FOREIGN KEY (IdEstadoPago) REFERENCES EstadoPago(IdEstadoPago)
);

-- TABLAS: EstadoEnvio / Envio

CREATE TABLE EstadoEnvio (
    IdEstadoEnvio SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL
);

CREATE TABLE Envio (
    IdEnvio SERIAL PRIMARY KEY,
    IdPedido INT NOT NULL,
    DireccionEntrega VARCHAR(200) NOT NULL,
    Ciudad VARCHAR(100),
    Provincia VARCHAR(100),
    Pais VARCHAR(100) DEFAULT 'Perú',
    CodigoPostal VARCHAR(20),
    FechaEnvio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FechaEntrega TIMESTAMP NULL,
    IdEstadoEnvio INT NOT NULL,
    FOREIGN KEY (IdPedido) REFERENCES Pedido(IdPedido) ON DELETE CASCADE,
    FOREIGN KEY (IdEstadoEnvio) REFERENCES EstadoEnvio(IdEstadoEnvio)
);

-- =============================================================
-- DATOS INICIALES
-- =============================================================

INSERT INTO TipoDocumento (Nombre) VALUES
('DNI'),
('RUC');

INSERT INTO Categoria (Nombre, Descripcion) VALUES
('Cuerdas', 'Instrumentos de cuerda como guitarras, bajos, violines'),
('Percusión', 'Baterías, cajones, tambores'),
('Viento', 'Saxofones, flautas, trompetas');

INSERT INTO Marca (Nombre, Descripcion, PaisOrigen) VALUES
('Fender', 'Fabricante de guitarras y bajos eléctricos', 'EE.UU.'),
('Yamaha', 'Fabricante japonés de instrumentos musicales', 'Japón'),
('Gibson', 'Reconocida marca de guitarras', 'EE.UU.');

INSERT INTO EstadoProducto (Nombre) VALUES
('Disponible'),
('Agotado'),
('Descontinuado');

INSERT INTO EstadoCarrito (Nombre) VALUES
('Activo'),
('Comprado'),
('Cancelado'),
('Abandonado');

INSERT INTO EstadoPedido (Nombre) VALUES
('Pendiente'),
('Pagado'),
('Enviado'),
('Entregado'),
('Cancelado');

INSERT INTO MetodoPago (Nombre) VALUES
('Tarjeta'),
('Transferencia'),
('Contra Entrega');

INSERT INTO EstadoPago (Nombre) VALUES
('Pendiente'),
('Confirmado'),
('Rechazado');

INSERT INTO EstadoEnvio (Nombre) VALUES
('Pendiente'),
('En Camino'),
('Entregado'),
('Cancelado');

-- Datos de prueba para Empresa
INSERT INTO DatosEmpresa (NombreComercial, RUC, Direccion) VALUES
('MusicShop S.A.C.', '20123456789', 'Av. La Música 123, Lima');

-- Datos de prueba para Productos
INSERT INTO Producto (Nombre, Descripcion, Precio, Stock, IdCategoria, IdMarca, IdEstadoProducto) VALUES
('Guitarra Stratocaster', 'Guitarra eléctrica clásica', 1200.00, 10, 1, 1, 1),
('Batería Stage Custom', 'Batería acústica completa', 3500.00, 5, 2, 2, 1),
('Saxofón Alto', 'Saxofón para estudiantes', 1800.00, 8, 3, 2, 1);
