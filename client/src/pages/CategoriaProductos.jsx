import styles from "./styles/CategoriaProductos.module.css";
import { useParams } from "react-router-dom";
import { productos } from "../data/productos";
import { categorias } from "../data/categProducs";
import CardProduct from "../components/CardProduct";
import { useMemo, useState } from "react";

export default function CategoriaProductos() {
  const { categoriaId } = useParams();
  const [busqueda, setBusqueda] = useState("");
  const [ordenPrecio, setOrdenPrecio] = useState("asc");

  const nombreCategoria =
    categorias.find((cat) => cat.ruta === categoriaId)?.nombre || categoriaId;

  const productosFiltrados = useMemo(() => {
    const productosCategoria = productos[categoriaId] || [];
    let lista = productosCategoria;

    if (busqueda) {
      lista = lista.filter((p) =>
        p.nombre.toLowerCase().includes(busqueda.toLowerCase())
      );
    }

    return [...lista].sort((a, b) =>
      ordenPrecio === "asc" ? a.precio - b.precio : b.precio - a.precio
    );
  }, [categoriaId, busqueda, ordenPrecio]);

  const handleAddToCart = (producto) => {
    console.log("Agregar al carrito:", producto);
  };

  return (
    <div className={styles.categoria}>
      <h1 className={styles.titulo}>Colección de {nombreCategoria}</h1>

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

      {productosFiltrados.length === 0 ? (
        <p className={styles.vacio}>
          No hay productos en esta categoría todavía...
        </p>
      ) : (
        <div className={styles.grid}>
          {productosFiltrados.map((prod) => (
            <CardProduct
              key={prod.id}
              producto={{ ...prod, categoria: categoriaId }}
              onAddToCart={handleAddToCart}
            />
          ))}
        </div>
      )}
    </div>
  );
}
