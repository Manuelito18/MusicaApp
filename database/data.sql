-- -------------------------------------------------------------
-- INSERT DE DATOS INICIALES
-- -------------------------------------------------------------

INSERT INTO TipoDocumento (Nombre) VALUES
('DNI'),
('PA');

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

INSERT INTO DatosEmpresa (NombreComercial, RUC, Direccion) VALUES
('MusicShop S.A.C.', '20123456789', 'Av. La Música 123, Lima');

-- Roles
INSERT INTO Rol (Nombre) VALUES
('Administrador'),
('Trabajador'),
('Cliente'),
('Devs');

-- Ubicación
INSERT INTO Departamento (Nombre) VALUES ('Lima');
INSERT INTO Provincia (Nombre, IdDepartamento) VALUES ('Lima', 1);
INSERT INTO Distrito (Nombre, IdProvincia) VALUES
('Miraflores', 1),
('San Isidro', 1),
('Surco', 1);

-- Productos de prueba
INSERT INTO Producto (Nombre, Descripcion, Precio, Stock, IdCategoria, IdMarca, IdEstadoProducto) VALUES
('Guitarra Stratocaster', 'Guitarra eléctrica clásica', 1200.00, 10, 1, 1, 1),
('Batería Stage Custom', 'Batería acústica completa', 3500.00, 5, 2, 2, 1),
('Saxofón Alto', 'Saxofón para estudiantes', 1800.00, 8, 3, 2, 1);

-- Usuario Administrador
-- Primero crear los datos personales (UserData)
INSERT INTO UserData (Nombres, Apellidos, IdTipoDocumento, NumeroDocumento, Email, Telefono) VALUES
('Admin', 'Sistema', 1, '00000000', 'admin@musicshop.com', '999999999');

-- Crear el usuario con rol de Administrador (IdRol = 1)
-- Password: admin123 (hash bcrypt generado con password_hash de PHP)
-- NOTA: Este hash es válido para la contraseña "admin123"
-- Si necesitas generar uno nuevo: php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"
INSERT INTO usuario (username, passwordhash, idrol, iduserdata) VALUES
('admin', '$2y$12$JhEvLWZxwapVE4uD9Vol6u2YPksLf7BFEsaT4zHdGWBVDmV7owLuy', 1, 1);

