-- =============================================================
-- MIGRACIÓN A POSTGRESQL - MUSICSHOP
-- SCRIPT UNIFICADO Y CORREGIDO
-- =============================================================


-- -------------------------------------------------------------
-- 1. TABLAS MAESTRAS (CATÁLOGOS y ESTADOS)
-- -------------------------------------------------------------

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

CREATE TABLE EstadoPedido (
    IdEstadoPedido SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL
);

CREATE TABLE MetodoPago (
    IdMetodoPago SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL
);

CREATE TABLE EstadoPago (
    IdEstadoPago SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL
);

CREATE TABLE EstadoEnvio (
    IdEstadoEnvio SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL
);


-- -------------------------------------------------------------
-- 2. TABLAS DE UBICACIÓN
-- -------------------------------------------------------------

CREATE TABLE Departamento (
    IdDepartamento SERIAL PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL
);

CREATE TABLE Provincia (
    IdProvincia SERIAL PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    IdDepartamento INT NOT NULL,
    FOREIGN KEY (IdDepartamento) REFERENCES Departamento(IdDepartamento)
);

CREATE TABLE Distrito (
    IdDistrito SERIAL PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    IdProvincia INT NOT NULL,
    FOREIGN KEY (IdProvincia) REFERENCES Provincia(IdProvincia)
);


-- -------------------------------------------------------------
-- 3. TABLAS TRANSACCIONALES PRINCIPALES (Usuario y PRODUCTO)
-- -------------------------------------------------------------


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


-- -------------------------------------------------------------
-- 4. TABLAS DE SEGURIDAD (ROLES y USUARIOS)
-- -------------------------------------------------------------

CREATE TABLE Rol (
    IdRol SERIAL PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE UserData (
    IdUserData SERIAL PRIMARY KEY,
    Nombres VARCHAR(100) NOT NULL,
    Apellidos VARCHAR(100) NOT NULL,
    IdTipoDocumento INT NOT NULL,
    NumeroDocumento VARCHAR(20) NOT NULL UNIQUE,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Telefono VARCHAR(20),
    FOREIGN KEY (IdTipoDocumento) REFERENCES TipoDocumento(IdTipoDocumento)
);

CREATE TABLE Usuario (
    IdUsuario SERIAL PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    PasswordHash VARCHAR(200) NOT NULL,
    IdRol INT NOT NULL,
    IdUserData INT NULL,
    FechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IdRol) REFERENCES Rol(IdRol),
    FOREIGN KEY (IdUserData) REFERENCES UserData(IdUserData)
);
-- -------------------------------------------------------------
-- 5. TABLAS DE PROCESO (CARRITO, PEDIDO y DETALLES)
-- -------------------------------------------------------------

CREATE TABLE Carrito (
    IdCarrito SERIAL PRIMARY KEY,
    IdUsuario INT NOT NULL,
    FechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    IdEstadoCarrito INT NOT NULL,
    FOREIGN KEY (IdUsuario) REFERENCES Usuario(IdUsuario),
    FOREIGN KEY (IdEstadoCarrito) REFERENCES EstadoCarrito(IdEstadoCarrito)
);

CREATE TABLE Pedido (
    IdPedido SERIAL PRIMARY KEY,
    IdUsuario INT NOT NULL,
    IdCarrito INT NULL, -- Se mantiene como NULLable, asumiendo que un pedido puede venir de otra fuente o directo.
    Fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Total DECIMAL(10,2),
    IdEstadoPedido INT NOT NULL,
    IdEmpresa INT NOT NULL,
    FOREIGN KEY (IdUsuario) REFERENCES Usuario(IdUsuario),
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

-- Se utiliza la versión mejorada de Envio que usa IdDistrito
CREATE TABLE Envio (
    IdEnvio SERIAL PRIMARY KEY,
    IdPedido INT NOT NULL,
    DireccionEntrega VARCHAR(200) NOT NULL,
    IdDistrito INT NOT NULL,
    FechaEnvio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FechaEntrega TIMESTAMP NULL,
    IdEstadoEnvio INT NOT NULL,
    FOREIGN KEY (IdPedido) REFERENCES Pedido(IdPedido) ON DELETE CASCADE,
    FOREIGN KEY (IdEstadoEnvio) REFERENCES EstadoEnvio(IdEstadoEnvio),
    FOREIGN KEY (IdDistrito) REFERENCES Distrito(IdDistrito)
);
