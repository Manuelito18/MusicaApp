import { useState, useEffect } from "react";
import styles from "./styles/LoginModal.module.css";
import { useUser } from "../context/UserContext";

export default function LoginModal({ isOpen, onClose }) {
  const { login } = useUser();
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");

  // 🔒 Bloquear scroll del body cuando el modal está abierto
  useEffect(() => {
    if (isOpen) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }

    return () => {
      document.body.style.overflow = "";
    };
  }, [isOpen]);

  if (!isOpen) return null;

  const handleSubmit = (e) => {
    e.preventDefault();
    const res = login({ username, password });
    if (res.success) {
      setError("");
      onClose();
    } else {
      setError(res.message || "Error al iniciar sesión");
    }
  };

  return (
    <div className={styles.overlay} onClick={onClose}>
      <div className={styles.modal} onClick={(e) => e.stopPropagation()}>
        <button className={styles.closeBtn} onClick={onClose}>
          ×
        </button>

        <h3 className={styles.title}>Acceso al sistema</h3>

        <form onSubmit={handleSubmit} className={styles.form}>
          <label className={styles.label}>
            Usuario
            <input
              className={styles.input}
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              type="text"
              autoComplete="username"
            />
          </label>

          <label className={styles.label}>
            Contraseña
            <input
              className={styles.input}
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              type="password"
              autoComplete="current-password"
            />
          </label>

          {error && <div className={styles.error}>{error}</div>}

          <div className={styles.actions}>
            <button type="submit" className={styles.btnPrimary}>
              Entrar
            </button>
            <button type="button" className={styles.btnGhost} onClick={onClose}>
              Cancelar
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
