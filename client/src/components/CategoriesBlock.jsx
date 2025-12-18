import styles from "./styles/CategoriesBlock.module.css";
import { useNavigate } from "react-router-dom";
import { useEffect, useState } from "react";

const API_URL =
  import.meta.env.VITE_API_URL || "http://localhost/WEBS/MusicaApp/";

export default function CategoriesBlock() {
  const navigate = useNavigate();
  const [categories, setCategories] = useState([]);

  const irACategoria = (ruta) => {
    navigate(`/productos/${ruta}`);
  };

  useEffect(() => {
    fetchCategorias();
  }, []);

  const fetchCategorias = async () => {
    try {
      const res = await fetch(`${API_URL}/app/api/categorias.php`);
      const data = await res.json();
      if (!res.ok) return;

      // Mostrar hasta 5 categorías
      const normalized = (data || []).slice(0, 5).map((c, idx) => ({
        title: c.Nombre,
        ruta: String(c.IdCategoria),
        // colores/placeholder de icono simple
        color: [
          "linear-gradient(135deg, #f43f5e, #ec4899)",
          "linear-gradient(135deg, #3b82f6, #06b6d4)",
          "linear-gradient(135deg, #10b981, #22c55e)",
          "linear-gradient(135deg, #8b5cf6, #a855f7)",
          "linear-gradient(135deg, #6366f1, #3b82f6)",
        ][idx % 5],
        icon: "/imgs/icons/search.svg",
      }));
      setCategories(normalized);
    } catch (e) {
      // noop
    }
  };

  return (
    <section className={styles.section}>
      <h2 className={styles.title}>Categorías Preferidas</h2>
      <div className={styles.grid}>
        {categories.map((cat, i) => (
          <div
            key={i}
            className={styles.card}
            onClick={() => irACategoria(cat.ruta)}
          >
            <div
              className={styles.iconWrapper}
              style={{ background: cat.color }}
            >
              <img src={cat.icon} alt={cat.title} className={styles.icon} />
            </div>
            <p className={styles.label}>{cat.title}</p>
          </div>
        ))}
      </div>
    </section>
  );
}
