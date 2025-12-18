import styles from "./styles/CategoriaProductos.module.css";
import { useParams } from "react-router-dom";
import CardProduct from "../components/CardProduct";
import { useEffect, useMemo, useState } from "react";

const API_URL =
  import.meta.env.VITE_API_URL || "http://localhost/WEBS/MusicaApp/";

export default function CategoriaProductos() {
  const { categoriaId } = useParams();
  const [busqueda, setBusqueda] = useState("");
  const [ordenPrecio, setOrdenPrecio] = useState("asc");
  const [categoriaNombre, setCategoriaNombre] = useState(categoriaId);
  const [productos, setProductos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    fetchCategoriaYProductos();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [categoriaId]);

  const fetchCategoriaYProductos = async () => {
    try {
      setLoading(true);
      setError("");

      // Categorías para obtener nombre
      const resCat = await fetch(`${API_URL}/app/api/categorias.php`);
      const cats = await resCat.json();
      if (resCat.ok) {
        const found = (cats || []).find(
          (c) => String(c.IdCategoria) === String(categoriaId)
        );
        setCategoriaNombre(found?.Nombre || categoriaId);
      }

      // Productos por categoría
      const res = await fetch(
        `${API_URL}/app/api/productos.php?categoriaId=${categoriaId}`
      );
      const data = await res.json();
      if (!res.ok) {
        setError(data.error || "No se pudieron cargar productos.");
        return;
      }
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

    if (busqueda) {
      lista = lista.filter((p) =>
        p.nombre.toLowerCase().includes(busqueda.toLowerCase())
      );
    }

    return [...lista].sort((a, b) =>
      ordenPrecio === "asc" ? a.precio - b.precio : b.precio - a.precio
    );
  }, [productos, busqueda, ordenPrecio]);

  return (
    <div className={styles.categoria}>
      <h1 className={styles.titulo}>Colección de {categoriaNombre}</h1>

      <div className={styles.controles}>
        <input
          type="text"
          placeholder="Buscar producto..."
          value={busqueda}
          onChange={(e) => setBusqueda(e.target.value)}
        />
        <select
          value={ordenPrecio}
          onChange={(e) => setOrdenPrecio(e.target.value)}
        >
          <option value="asc">Precio: menor a mayor</option>
          <option value="desc">Precio: mayor a menor</option>
        </select>
      </div>

      {error && <p className={styles.vacio}>{error}</p>}
      {loading ? (
        <p className={styles.vacio}>Cargando productos...</p>
      ) : productosFiltrados.length === 0 ? (
        <p className={styles.vacio}>
          No hay productos en esta categoría todavía...
        </p>
      ) : (
        <div className={styles.grid}>
          {productosFiltrados.map((prod) => (
            <CardProduct key={prod.id} producto={prod} />
          ))}
        </div>
      )}
    </div>
  );
}
