import styles from "./styles/ProductBlock.module.css";
import CardProduct from "./CardProduct";
import { useNavigate } from "react-router-dom";
import { useEffect, useState } from "react";

const API_URL = import.meta.env.VITE_API_URL || "http://localhost:8000";

export default function ProductBlock() {
  const navigate = useNavigate();
  const [productosDestacados, setProductosDestacados] = useState([]);

  useEffect(() => {
    fetchDestacados();
  }, []);

  const fetchDestacados = async () => {
    try {
      const res = await fetch(`${API_URL}/app/api/productos.php`);
      const data = await res.json();
      if (!res.ok) return;

      const normalized = (data || []).map((p) => ({
        id: p.IdProducto,
        nombre: p.Nombre,
        precio: Number(p.Precio),
        imagen: p.ImagenURL || "/imgs/productos/Guitarra-Yamaha-F310.webp",
        descripcion: p.Descripcion || "",
        rating: 0,
        stado: null,
      }));

      // “Destacados”: primeros 6 por ahora (puedes cambiar por criterio real)
      setProductosDestacados(normalized.slice(0, 6));
    } catch (e) {
      // noop
    }
  };

  const irA = () => {
    navigate("/productos");
  };

  return (
    <>
      <section className={styles.section}>
        <h2 className={styles.title}>Productos Destacados</h2>
        <div className={styles.grid}>
          {productosDestacados.map((producto) => (
            <CardProduct key={producto.id} producto={producto} />
          ))}
        </div>
      </section>
      <section className={styles.section}>
        <button className={styles.btn} onClick={() => irA()}>
          Ver todos los Productos
        </button>
      </section>
    </>
  );
}
