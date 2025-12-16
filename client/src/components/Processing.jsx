import styles from "./styles/Processing.module.css";
export default function Processing() {
  return (
    <div className={styles.processing}>
      <div className={styles.spinner} />
      <p>Procesando tu pago...</p>
    </div>
  );
}
