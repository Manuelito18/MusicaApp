import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { useUser } from "../context/UserContext";
import styles from "./styles/Admin.module.css";

const API_URL =
  import.meta.env.VITE_API_URL || "http://localhost/WEBS/MusicaApp/";

export default function Admin() {
  const { user, isAdmin, getAuthHeaders, logout } = useUser();
  const navigate = useNavigate();
  const [activeTab, setActiveTab] = useState("dashboard");
  const [error, setError] = useState("");

  // Verificar si es admin
  useEffect(() => {
    if (!user) {
      navigate("/");
      return;
    }
    if (!isAdmin()) {
      setError("No tienes permisos para acceder a esta sección");
      setTimeout(() => navigate("/"), 2000);
    }
  }, [user, isAdmin, navigate]);

  if (!user || !isAdmin()) {
    return (
      <div className={styles.container}>
        <div className={styles.error}>{error || "Cargando..."}</div>
      </div>
    );
  }

  return (
    <div className={styles.container}>
      <div className={styles.header}>
        <h1>Panel de Administración</h1>
        <div className={styles.userInfo}>
          <span>@{user.username}</span>
          <button onClick={logout} className={styles.logoutBtn}>
            Cerrar sesión
          </button>
        </div>
      </div>

      <div className={styles.tabs}>
        <button
          className={`${styles.tab} ${
            activeTab === "dashboard" ? styles.active : ""
          }`}
          onClick={() => setActiveTab("dashboard")}
        >
          Dashboard
        </button>
        <button
          className={`${styles.tab} ${
            activeTab === "ventas" ? styles.active : ""
          }`}
          onClick={() => setActiveTab("ventas")}
        >
          Ventas
        </button>
        <button
          className={`${styles.tab} ${
            activeTab === "productos" ? styles.active : ""
          }`}
          onClick={() => setActiveTab("productos")}
        >
          Productos
        </button>
        <button
          className={`${styles.tab} ${
            activeTab === "usuarios" ? styles.active : ""
          }`}
          onClick={() => setActiveTab("usuarios")}
        >
          Usuarios
        </button>
        <button
          className={`${styles.tab} ${
            activeTab === "trabajadores" ? styles.active : ""
          }`}
          onClick={() => setActiveTab("trabajadores")}
        >
          Trabajadores
        </button>
      </div>

      <div className={styles.content}>
        {activeTab === "dashboard" && <DashboardTab />}
        {activeTab === "ventas" && <VentasTab />}
        {activeTab === "productos" && <ProductosTab />}
        {activeTab === "usuarios" && <UsuariosTab />}
        {activeTab === "trabajadores" && <TrabajadoresTab />}
      </div>
    </div>
  );
}

function DashboardTab() {
  const { getAuthHeaders } = useUser();
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchStats();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const fetchStats = async () => {
    try {
      setLoading(true);
      const response = await fetch(
        `${API_URL}/app/api/admin.php?path=estadisticas`,
        {
          headers: getAuthHeaders(),
        }
      );
      const data = await response.json();
      if (response.ok) setStats(data);
    } finally {
      setLoading(false);
    }
  };

  if (loading)
    return <div className={styles.loading}>Cargando dashboard...</div>;

  return (
    <div className={styles.tabContent}>
      <h2>Métricas</h2>
      <div className={styles.metricsGrid}>
        <div className={styles.metricCard}>
          <div className={styles.metricLabel}>Total Ventas</div>
          <div className={styles.metricValue}>
            S/ {stats ? Number(stats.TotalVentas || 0).toFixed(2) : "0.00"}
          </div>
        </div>
        <div className={styles.metricCard}>
          <div className={styles.metricLabel}>Total Pedidos</div>
          <div className={styles.metricValue}>
            {stats ? stats.TotalPedidos || 0 : 0}
          </div>
        </div>
        <div className={styles.metricCard}>
          <div className={styles.metricLabel}>Productos</div>
          <div className={styles.metricValue}>
            {stats ? stats.TotalProductos || 0 : 0}
          </div>
        </div>
        <div className={styles.metricCard}>
          <div className={styles.metricLabel}>Trabajadores</div>
          <div className={styles.metricValue}>
            {stats ? stats.TotalTrabajadores || 0 : 0}
          </div>
        </div>
      </div>
    </div>
  );
}

// Componente de pestaña de Ventas
function VentasTab() {
  const { getAuthHeaders } = useUser();
  const [ventas, setVentas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedVenta, setSelectedVenta] = useState(null);

  useEffect(() => {
    fetchVentas();
  }, []);

  const fetchVentas = async () => {
    try {
      setLoading(true);
      const response = await fetch(`${API_URL}/app/api/admin.php?path=ventas`, {
        headers: getAuthHeaders(),
      });
      const data = await response.json();
      if (response.ok) {
        setVentas(data);
      }
    } catch (error) {
      console.error("Error al cargar ventas:", error);
    } finally {
      setLoading(false);
    }
  };

  const verDetalle = async (id) => {
    try {
      const response = await fetch(
        `${API_URL}/app/api/admin.php?path=ventas/${id}`,
        {
          headers: getAuthHeaders(),
        }
      );
      const data = await response.json();
      if (response.ok) {
        setSelectedVenta(data);
      }
    } catch (error) {
      console.error("Error al cargar detalle:", error);
    }
  };

  if (loading) return <div className={styles.loading}>Cargando ventas...</div>;

  return (
    <div className={styles.tabContent}>
      <h2>Ventas</h2>
      <div className={styles.tableContainer}>
        <table className={styles.table}>
          <thead>
            <tr>
              <th>ID</th>
              <th>Fecha</th>
              <th>Cliente</th>
              <th>Total</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {ventas.map((venta) => (
              <tr key={venta.IdPedido}>
                <td>{venta.IdPedido}</td>
                <td>{new Date(venta.Fecha).toLocaleDateString()}</td>
                <td>
                  {venta.Nombres} {venta.Apellidos} ({venta.Username})
                </td>
                <td>S/ {parseFloat(venta.Total).toFixed(2)}</td>
                <td>
                  <span className={styles.badge}>{venta.EstadoPedido}</span>
                </td>
                <td>
                  <button
                    className={styles.btnSmall}
                    onClick={() => verDetalle(venta.IdPedido)}
                  >
                    Ver Detalle
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {selectedVenta && (
        <div className={styles.modal} onClick={() => setSelectedVenta(null)}>
          <div
            className={styles.modalContent}
            onClick={(e) => e.stopPropagation()}
          >
            <h3>Detalle de Venta #{selectedVenta.IdPedido}</h3>
            <div className={styles.detailInfo}>
              <p>
                <strong>Cliente:</strong> {selectedVenta.Nombres}{" "}
                {selectedVenta.Apellidos}
              </p>
              <p>
                <strong>Email:</strong> {selectedVenta.Email}
              </p>
              <p>
                <strong>Fecha:</strong>{" "}
                {new Date(selectedVenta.Fecha).toLocaleString()}
              </p>
              <p>
                <strong>Estado:</strong> {selectedVenta.EstadoPedido}
              </p>
              <p>
                <strong>Total:</strong> S/{" "}
                {parseFloat(selectedVenta.Total).toFixed(2)}
              </p>
            </div>
            <h4>Productos:</h4>
            <table className={styles.table}>
              <thead>
                <tr>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>Precio Unit.</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                {selectedVenta.detalles?.map((detalle, idx) => (
                  <tr key={idx}>
                    <td>{detalle.ProductoNombre}</td>
                    <td>{detalle.Cantidad}</td>
                    <td>S/ {parseFloat(detalle.PrecioUnitario).toFixed(2)}</td>
                    <td>S/ {parseFloat(detalle.Subtotal).toFixed(2)}</td>
                  </tr>
                ))}
              </tbody>
            </table>
            <button
              className={styles.btnClose}
              onClick={() => setSelectedVenta(null)}
            >
              Cerrar
            </button>
          </div>
        </div>
      )}
    </div>
  );
}

// Componente de pestaña de Productos
function ProductosTab() {
  const { getAuthHeaders } = useUser();
  const [productos, setProductos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [editingProducto, setEditingProducto] = useState(null);
  const [categorias, setCategorias] = useState([]);
  const [marcas, setMarcas] = useState([]);
  const [formData, setFormData] = useState({
    nombre: "",
    descripcion: "",
    precio: "",
    stock: "",
    imagen: "",
    idCategoria: "",
    idMarca: "",
    idEstadoProducto: 1,
  });

  useEffect(() => {
    fetchProductos();
    fetchCategorias();
    fetchMarcas();
  }, []);

  const fetchProductos = async () => {
    try {
      setLoading(true);
      const response = await fetch(
        `${API_URL}/app/api/admin.php?path=productos`,
        {
          headers: getAuthHeaders(),
        }
      );
      const data = await response.json();
      if (response.ok) {
        setProductos(data);
      }
    } catch (error) {
      console.error("Error al cargar productos:", error);
    } finally {
      setLoading(false);
    }
  };

  const fetchCategorias = async () => {
    try {
      const response = await fetch(
        `${API_URL}/app/api/admin.php?path=categorias`,
        {
          headers: getAuthHeaders(),
        }
      );
      const data = await response.json();
      if (response.ok) {
        setCategorias(data);
      }
    } catch (error) {
      console.error("Error al cargar categorías:", error);
    }
  };

  const fetchMarcas = async () => {
    try {
      const response = await fetch(`${API_URL}/app/api/admin.php?path=marcas`, {
        headers: getAuthHeaders(),
      });
      const data = await response.json();
      if (response.ok) {
        setMarcas(data);
      }
    } catch (error) {
      console.error("Error al cargar marcas:", error);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const url = editingProducto
        ? `${API_URL}/app/api/admin.php?path=productos/${editingProducto.IdProducto}`
        : `${API_URL}/app/api/admin.php?path=productos`;

      const response = await fetch(url, {
        method: editingProducto ? "PUT" : "POST",
        headers: getAuthHeaders(),
        body: JSON.stringify(formData),
      });

      if (response.ok) {
        fetchProductos();
        setShowForm(false);
        setEditingProducto(null);
        setFormData({
          nombre: "",
          descripcion: "",
          precio: "",
          stock: "",
          imagen: "",
          idCategoria: "",
          idMarca: "",
          idEstadoProducto: 1,
        });
      }
    } catch (error) {
      console.error("Error al guardar producto:", error);
    }
  };

  const handleEdit = (producto) => {
    setEditingProducto(producto);
    setFormData({
      nombre: producto.Nombre,
      descripcion: producto.Descripcion || "",
      precio: producto.Precio,
      stock: producto.Stock,
      imagen: producto.ImagenURL || "",
      idCategoria: producto.IdCategoria,
      idMarca: producto.IdMarca,
      idEstadoProducto: producto.IdEstadoProducto || 1,
    });
    setShowForm(true);
  };

  const handleDelete = async (id) => {
    if (!confirm("¿Estás seguro de eliminar este producto?")) return;
    try {
      const response = await fetch(
        `${API_URL}/app/api/admin.php?path=productos/${id}`,
        {
          method: "DELETE",
          headers: getAuthHeaders(),
        }
      );
      if (response.ok) {
        fetchProductos();
      }
    } catch (error) {
      console.error("Error al eliminar producto:", error);
    }
  };

  const updateStock = async (id, nuevoStock) => {
    const stock = prompt("Ingrese el nuevo stock:", nuevoStock);
    if (stock === null || isNaN(stock)) return;
    try {
      const response = await fetch(
        `${API_URL}/app/api/admin.php?path=productos/${id}/stock`,
        {
          method: "PUT",
          headers: getAuthHeaders(),
          body: JSON.stringify({ stock: parseInt(stock) }),
        }
      );
      if (response.ok) {
        fetchProductos();
      }
    } catch (error) {
      console.error("Error al actualizar stock:", error);
    }
  };

  if (loading)
    return <div className={styles.loading}>Cargando productos...</div>;

  return (
    <div className={styles.tabContent}>
      <div className={styles.tabHeader}>
        <h2>Productos</h2>
        <button className={styles.btnPrimary} onClick={() => setShowForm(true)}>
          + Nuevo Producto
        </button>
      </div>

      {showForm && (
        <div
          className={styles.modal}
          onClick={() => {
            setShowForm(false);
            setEditingProducto(null);
          }}
        >
          <div
            className={styles.modalContent}
            onClick={(e) => e.stopPropagation()}
          >
            <h3>{editingProducto ? "Editar Producto" : "Nuevo Producto"}</h3>
            <form onSubmit={handleSubmit} className={styles.form}>
              <label>
                Nombre:
                <input
                  type="text"
                  value={formData.nombre}
                  onChange={(e) =>
                    setFormData({ ...formData, nombre: e.target.value })
                  }
                  required
                />
              </label>
              <label>
                Descripción:
                <textarea
                  value={formData.descripcion}
                  onChange={(e) =>
                    setFormData({ ...formData, descripcion: e.target.value })
                  }
                />
              </label>
              <label>
                Precio:
                <input
                  type="number"
                  step="0.01"
                  value={formData.precio}
                  onChange={(e) =>
                    setFormData({ ...formData, precio: e.target.value })
                  }
                  required
                />
              </label>
              <label>
                Stock:
                <input
                  type="number"
                  value={formData.stock}
                  onChange={(e) =>
                    setFormData({ ...formData, stock: e.target.value })
                  }
                  required
                />
              </label>
              <label>
                URL Imagen:
                <input
                  type="text"
                  value={formData.imagen}
                  onChange={(e) =>
                    setFormData({ ...formData, imagen: e.target.value })
                  }
                />
              </label>
              <label>
                Categoría:
                <select
                  value={formData.idCategoria}
                  onChange={(e) =>
                    setFormData({ ...formData, idCategoria: e.target.value })
                  }
                  required
                >
                  <option value="">Seleccione...</option>
                  {categorias.map((cat) => (
                    <option key={cat.IdCategoria} value={cat.IdCategoria}>
                      {cat.Nombre}
                    </option>
                  ))}
                </select>
              </label>
              <label>
                Marca:
                <select
                  value={formData.idMarca}
                  onChange={(e) =>
                    setFormData({ ...formData, idMarca: e.target.value })
                  }
                  required
                >
                  <option value="">Seleccione...</option>
                  {marcas.map((marca) => (
                    <option key={marca.IdMarca} value={marca.IdMarca}>
                      {marca.Nombre}
                    </option>
                  ))}
                </select>
              </label>
              <div className={styles.formActions}>
                <button type="submit" className={styles.btnPrimary}>
                  {editingProducto ? "Actualizar" : "Crear"}
                </button>
                <button
                  type="button"
                  className={styles.btnSecondary}
                  onClick={() => {
                    setShowForm(false);
                    setEditingProducto(null);
                  }}
                >
                  Cancelar
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      <div className={styles.tableContainer}>
        <table className={styles.table}>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Categoría</th>
              <th>Marca</th>
              <th>Precio</th>
              <th>Stock</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {productos.map((producto) => (
              <tr key={producto.IdProducto}>
                <td>{producto.IdProducto}</td>
                <td>{producto.Nombre}</td>
                <td>{producto.Categoria}</td>
                <td>{producto.Marca}</td>
                <td>S/ {parseFloat(producto.Precio).toFixed(2)}</td>
                <td>
                  <span className={styles.stock}>{producto.Stock}</span>
                  <button
                    className={styles.btnStock}
                    onClick={() =>
                      updateStock(producto.IdProducto, producto.Stock)
                    }
                  >
                    📦
                  </button>
                </td>
                <td>
                  <span className={styles.badge}>{producto.Estado}</span>
                </td>
                <td>
                  <button
                    className={styles.btnSmall}
                    onClick={() => handleEdit(producto)}
                  >
                    Editar
                  </button>
                  <button
                    className={styles.btnDanger}
                    onClick={() => handleDelete(producto.IdProducto)}
                  >
                    Eliminar
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}

// Componente de pestaña de Usuarios
function UsuariosTab() {
  const { getAuthHeaders } = useUser();
  const [usuarios, setUsuarios] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchUsuarios();
  }, []);

  const fetchUsuarios = async () => {
    try {
      setLoading(true);
      const response = await fetch(
        `${API_URL}/app/api/admin.php?path=usuarios`,
        {
          headers: getAuthHeaders(),
        }
      );
      const data = await response.json();
      if (response.ok) {
        setUsuarios(data);
      }
    } catch (error) {
      console.error("Error al cargar usuarios:", error);
    } finally {
      setLoading(false);
    }
  };

  if (loading)
    return <div className={styles.loading}>Cargando usuarios...</div>;

  return (
    <div className={styles.tabContent}>
      <h2>Usuarios</h2>
      <div className={styles.tableContainer}>
        <table className={styles.table}>
          <thead>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Nombres</th>
              <th>Apellidos</th>
              <th>Email</th>
              <th>Rol</th>
              <th>Fecha Registro</th>
            </tr>
          </thead>
          <tbody>
            {usuarios.map((usuario) => (
              <tr key={usuario.IdUsuario}>
                <td>{usuario.IdUsuario}</td>
                <td>{usuario.Username}</td>
                <td>{usuario.Nombres || "-"}</td>
                <td>{usuario.Apellidos || "-"}</td>
                <td>{usuario.Email || "-"}</td>
                <td>
                  <span className={styles.badge}>
                    {usuario.Rol || "Sin rol"}
                  </span>
                </td>
                <td>{new Date(usuario.FechaRegistro).toLocaleDateString()}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}

// Componente de pestaña de Trabajadores
function TrabajadoresTab() {
  const { getAuthHeaders } = useUser();
  const [trabajadores, setTrabajadores] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [showEditForm, setShowEditForm] = useState(false);
  const [editing, setEditing] = useState(null);
  const [roles, setRoles] = useState([]);
  const [formData, setFormData] = useState({
    username: "",
    password: "",
    nombres: "",
    apellidos: "",
    email: "",
    telefono: "",
    numeroDocumento: "",
    idRol: 2, // Trabajador por defecto
  });

  useEffect(() => {
    fetchTrabajadores();
    fetchRoles();
  }, []);

  const fetchTrabajadores = async () => {
    try {
      setLoading(true);
      const response = await fetch(
        `${API_URL}/app/api/admin.php?path=trabajadores`,
        {
          headers: getAuthHeaders(),
        }
      );
      const data = await response.json();
      if (response.ok) {
        setTrabajadores(data);
      }
    } catch (error) {
      console.error("Error al cargar trabajadores:", error);
    } finally {
      setLoading(false);
    }
  };

  const fetchRoles = async () => {
    try {
      const response = await fetch(`${API_URL}/app/api/admin.php?path=roles`, {
        headers: getAuthHeaders(),
      });
      const data = await response.json();
      if (response.ok) {
        setRoles(data);
      }
    } catch (error) {
      console.error("Error al cargar roles:", error);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch(
        `${API_URL}/app/api/admin.php?path=trabajadores`,
        {
          method: "POST",
          headers: getAuthHeaders(),
          body: JSON.stringify(formData),
        }
      );

      if (response.ok) {
        fetchTrabajadores();
        setShowForm(false);
        setFormData({
          username: "",
          password: "",
          nombres: "",
          apellidos: "",
          email: "",
          telefono: "",
          numeroDocumento: "",
          idRol: 2,
        });
      }
    } catch (error) {
      console.error("Error al crear trabajador:", error);
    }
  };

  const openEdit = (t) => {
    setEditing({ ...t, password: "" });
    setShowEditForm(true);
  };

  const handleEditSubmit = async (e) => {
    e.preventDefault();
    if (!editing) return;
    try {
      const payload = {
        username: editing.Username,
        password: editing.password || "",
        userData: {
          nombres: editing.Nombres || "",
          apellidos: editing.Apellidos || "",
          email: editing.Email || "",
          telefono: editing.Telefono || "",
          numeroDocumento: editing.NumeroDocumento || "",
          idTipoDocumento: 1,
        },
      };

      const response = await fetch(
        `${API_URL}/app/api/admin.php?path=trabajadores/${editing.IdUsuario}`,
        {
          method: "PUT",
          headers: getAuthHeaders(),
          body: JSON.stringify(payload),
        }
      );

      if (response.ok) {
        setShowEditForm(false);
        setEditing(null);
        fetchTrabajadores();
      }
    } catch (error) {
      console.error("Error al actualizar trabajador:", error);
    }
  };

  const handleDelete = async (id) => {
    if (!confirm("¿Eliminar este trabajador?")) return;
    try {
      const response = await fetch(
        `${API_URL}/app/api/admin.php?path=trabajadores/${id}`,
        {
          method: "DELETE",
          headers: getAuthHeaders(),
        }
      );
      if (response.ok) {
        fetchTrabajadores();
      }
    } catch (error) {
      console.error("Error al eliminar trabajador:", error);
    }
  };

  if (loading)
    return <div className={styles.loading}>Cargando trabajadores...</div>;

  return (
    <div className={styles.tabContent}>
      <div className={styles.tabHeader}>
        <h2>Trabajadores</h2>
        <button className={styles.btnPrimary} onClick={() => setShowForm(true)}>
          + Nuevo Trabajador
        </button>
      </div>

      {showForm && (
        <div className={styles.modal} onClick={() => setShowForm(false)}>
          <div
            className={styles.modalContent}
            onClick={(e) => e.stopPropagation()}
          >
            <h3>Nuevo Trabajador</h3>
            <form onSubmit={handleSubmit} className={styles.form}>
              <label>
                Username:
                <input
                  type="text"
                  value={formData.username}
                  onChange={(e) =>
                    setFormData({ ...formData, username: e.target.value })
                  }
                  required
                />
              </label>
              <label>
                Contraseña:
                <input
                  type="password"
                  value={formData.password}
                  onChange={(e) =>
                    setFormData({ ...formData, password: e.target.value })
                  }
                  required
                />
              </label>
              <label>
                Nombres:
                <input
                  type="text"
                  value={formData.nombres}
                  onChange={(e) =>
                    setFormData({ ...formData, nombres: e.target.value })
                  }
                  required
                />
              </label>
              <label>
                Apellidos:
                <input
                  type="text"
                  value={formData.apellidos}
                  onChange={(e) =>
                    setFormData({ ...formData, apellidos: e.target.value })
                  }
                  required
                />
              </label>
              <label>
                Email:
                <input
                  type="email"
                  value={formData.email}
                  onChange={(e) =>
                    setFormData({ ...formData, email: e.target.value })
                  }
                  required
                />
              </label>
              <label>
                Teléfono:
                <input
                  type="text"
                  value={formData.telefono}
                  onChange={(e) =>
                    setFormData({ ...formData, telefono: e.target.value })
                  }
                />
              </label>
              <label>
                Número de Documento:
                <input
                  type="text"
                  value={formData.numeroDocumento}
                  onChange={(e) =>
                    setFormData({
                      ...formData,
                      numeroDocumento: e.target.value,
                    })
                  }
                />
              </label>
              <label>
                Rol:
                <select
                  value={formData.idRol}
                  onChange={(e) =>
                    setFormData({
                      ...formData,
                      idRol: parseInt(e.target.value),
                    })
                  }
                >
                  {roles.map((rol) => (
                    <option key={rol.IdRol} value={rol.IdRol}>
                      {rol.Nombre}
                    </option>
                  ))}
                </select>
              </label>
              <div className={styles.formActions}>
                <button type="submit" className={styles.btnPrimary}>
                  Crear
                </button>
                <button
                  type="button"
                  className={styles.btnSecondary}
                  onClick={() => setShowForm(false)}
                >
                  Cancelar
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {showEditForm && editing && (
        <div
          className={styles.modal}
          onClick={() => {
            setShowEditForm(false);
            setEditing(null);
          }}
        >
          <div
            className={styles.modalContent}
            onClick={(e) => e.stopPropagation()}
          >
            <h3>Editar Trabajador</h3>
            <form onSubmit={handleEditSubmit} className={styles.form}>
              <label>
                Username:
                <input
                  type="text"
                  value={editing.Username}
                  onChange={(e) =>
                    setEditing({ ...editing, Username: e.target.value })
                  }
                  required
                />
              </label>
              <label>
                Nueva contraseña (opcional):
                <input
                  type="password"
                  value={editing.password || ""}
                  onChange={(e) =>
                    setEditing({ ...editing, password: e.target.value })
                  }
                />
              </label>
              <label>
                Nombres:
                <input
                  type="text"
                  value={editing.Nombres || ""}
                  onChange={(e) =>
                    setEditing({ ...editing, Nombres: e.target.value })
                  }
                />
              </label>
              <label>
                Apellidos:
                <input
                  type="text"
                  value={editing.Apellidos || ""}
                  onChange={(e) =>
                    setEditing({ ...editing, Apellidos: e.target.value })
                  }
                />
              </label>
              <label>
                Email:
                <input
                  type="email"
                  value={editing.Email || ""}
                  onChange={(e) =>
                    setEditing({ ...editing, Email: e.target.value })
                  }
                />
              </label>
              <label>
                Teléfono:
                <input
                  type="text"
                  value={editing.Telefono || ""}
                  onChange={(e) =>
                    setEditing({ ...editing, Telefono: e.target.value })
                  }
                />
              </label>
              <div className={styles.formActions}>
                <button type="submit" className={styles.btnPrimary}>
                  Guardar
                </button>
                <button
                  type="button"
                  className={styles.btnSecondary}
                  onClick={() => {
                    setShowEditForm(false);
                    setEditing(null);
                  }}
                >
                  Cancelar
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      <div className={styles.tableContainer}>
        <table className={styles.table}>
          <thead>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Nombres</th>
              <th>Apellidos</th>
              <th>Email</th>
              <th>Teléfono</th>
              <th>Rol</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {trabajadores.map((trabajador) => (
              <tr key={trabajador.IdUsuario}>
                <td>{trabajador.IdUsuario}</td>
                <td>{trabajador.Username}</td>
                <td>{trabajador.Nombres || "-"}</td>
                <td>{trabajador.Apellidos || "-"}</td>
                <td>{trabajador.Email || "-"}</td>
                <td>{trabajador.Telefono || "-"}</td>
                <td>
                  <span className={styles.badge}>{trabajador.Rol}</span>
                </td>
                <td>
                  <button
                    className={styles.btnSmall}
                    onClick={() => openEdit(trabajador)}
                  >
                    Editar
                  </button>
                  <button
                    className={styles.btnDanger}
                    onClick={() => handleDelete(trabajador.IdUsuario)}
                  >
                    Eliminar
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
