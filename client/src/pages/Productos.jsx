import styles from "./styles/Productos.module.css";
import { useNavigate } from "react-router-dom";
import { useEffect, useMemo, useState } from "react";
import CardProduct from "../components/CardProduct";

const API_URL =
  import.meta.env.VITE_API_URL || "http://localhost/WEBS/MusicaApp/";

export default function Productos() {
  const [busqueda, setBusqueda] = useState("");
  const [ordenPrecio, setOrdenPrecio] = useState("asc");
  const [categoriaSeleccionada, setCategoriaSeleccionada] = useState(null); // IdCategoria
  const [categorias, setCategorias] = useState([]);
  const [productos, setProductos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const navigate = useNavigate();
  const manejarBusqueda = (e) => {
    e.preventDefault();
  };

  useEffect(() => {
    fetchCategorias();
    fetchProductos();
  }, []);

  const fetchCategorias = async () => {
    try {
      const res = await fetch(`${API_URL}/app/api/categorias.php`);
      const data = await res.json();
      if (res.ok) setCategorias(data);
    } catch (e) {
      // noop
    }
  };

  const fetchProductos = async () => {
    try {
      setLoading(true);
      setError("");
      const res = await fetch(`${API_URL}/app/api/productos.php`);
      const data = await res.json();
      if (!res.ok) {
        setError(data.error || "No se pudieron cargar los productos.");
        return;
      }

      // Normalizar al shape del frontend
      const normalized = (data || []).map((p) => ({
        id: p.IdProducto,
        nombre: p.Nombre,
        precio: Number(p.Precio),
        imagen: p.ImagenURL || "/imgs/productos/Guitarra-Yamaha-F310.webp",
        descripcion: p.Descripcion || "",
        rating: 0,
        stado: null,
        idCategoria: p.IdCategoria,
        categoriaNombre: p.Categoria,
      }));
      setProductos(normalized);
    } catch (e) {
      setError("Error de conexión con el backend.");
    } finally {
      setLoading(false);
    }
  };

  const productosFiltrados = useMemo(() => {
    let lista = productos;

    if (categoriaSeleccionada) {
      lista = lista.filter((p) => p.idCategoria === categoriaSeleccionada);
    }

    if (busqueda) {
      lista = lista.filter((p) =>
        p.nombre.toLowerCase().includes(busqueda.toLowerCase())
      );
    }

    return [...lista].sort((a, b) =>
      ordenPrecio === "asc" ? a.precio - b.precio : b.precio - a.precio
    );
  }, [productos, categoriaSeleccionada, busqueda, ordenPrecio]);

  return (
    <div className={styles.container}>
      <h1 className={styles.titulo}>Nuestros Productos</h1>
      <div className={styles.categoriasNav}>
        <button
          className={`${styles.catBtn} ${
            !categoriaSeleccionada ? styles.active : ""
          }`}
          onClick={() => {
            setCategoriaSeleccionada(null);
            navigate(`/productos`);
          }}
        >
          Todas
        </button>
        {categorias.map((cat) => (
          <button
            key={cat.IdCategoria}
            className={`${styles.catBtn} ${
              categoriaSeleccionada === cat.IdCategoria ? styles.active : ""
            }`}
            onClick={() => {
              setCategoriaSeleccionada(cat.IdCategoria);
              navigate(`/productos/${cat.IdCategoria}`);
            }}
          >
            {cat.Nombre}
          </button>
        ))}
      </div>
      <form onSubmit={manejarBusqueda} className={styles.buscador}>
        <input
          type="text"
          placeholder="Buscar producto..."
          value={busqueda}
          onChange={(e) => setBusqueda(e.target.value)}
        />
        <button type="submit" className={styles.btnBuscar}>
          Buscar
        </button>
      </form>
      <div className={styles.filtros}>
        <label>Ordenar:</label>
        <select
          value={ordenPrecio}
          onChange={(e) => setOrdenPrecio(e.target.value)}
        >
          <option value="asc">Menor → Mayor</option>
          <option value="desc">Mayor → Menor</option>
        </select>
      </div>
      {error && <div className={styles.empty}>{error}</div>}
      {loading ? (
        <div className={styles.empty}>Cargando productos...</div>
      ) : (
        <div className={styles.gridFlat}>
          {productosFiltrados.map((p) => (
            <CardProduct key={p.id} producto={p} />
          ))}
        </div>
      )}
    </div>
  );
}
