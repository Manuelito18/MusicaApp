import styles from "./styles/ProductBlock.module.css";
import { productos } from "../data/productos";
import CardProduct from "./CardProduct";
import { useNavigate } from "react-router-dom";

export default function ProductBlock() {
  const navigate = useNavigate();
  const filtrarProductosPorEstado = (data) => {
    const productosFiltrados = {};
    var temp = 0;
    for (const categoria in data) {
      if (Object.hasOwnProperty.call(data, categoria)) {
        productosFiltrados[categoria] = data[categoria].filter(
          (producto) => producto.stado === true
        );
      }
      temp++;
      if (temp >= 3) break;
    }
    return Object.values(productosFiltrados).flat();
  };

  const productosEnOferta = filtrarProductosPorEstado(productos);

  const irA = () => {
    navigate("/productos");
  };

  return (
    <>
      <section className={styles.section}>
        <h2 className={styles.title}>Productos Destacados</h2>
        <div className={styles.grid}>
          {productosEnOferta.map((producto) => (
            <CardProduct
              key={producto.id}
              producto={producto}
              onAddToCart={(pro) => console.log(pro)}
            />
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
