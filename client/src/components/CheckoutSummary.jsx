import styles from "./styles/CheckoutSummary.module.css";

export default function CheckoutSummary({ items }) {
  const total = items.reduce((a, i) => a + i.precio * i.quantity, 0);
  return (
    <div className={styles.summary}>
      <h2>Resumen de tu pedido</h2>
      <ul>
        {items.map((i) => (
          <li key={i.id}>
            {i.nombre} x {i.quantity} — S/ {i.precio * i.quantity}
          </li>
        ))}
      </ul>
      <p>
        <strong>Total:</strong> S/ {total}
      </p>
    </div>
  );
}
