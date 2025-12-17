import { useState, useEffect } from "react";
import styles from "./styles/LoginModal.module.css";
import { useUser } from "../context/UserContext";

const API_URL = import.meta.env.VITE_API_URL || "http://localhost:8000";

export default function LoginModal({ isOpen, onClose }) {
  const { login } = useUser();
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [dbStatus, setDbStatus] = useState(null); // null, 'checking', 'connected', 'disconnected'
  const [loading, setLoading] = useState(false);

  // Verificar conexión a la base de datos cuando se abre el modal
  useEffect(() => {
    if (isOpen) {
      checkDatabaseConnection();
    }
  }, [isOpen]);

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

  const checkDatabaseConnection = async () => {
    setDbStatus("checking");
    setError(""); // Limpiar errores previos
    try {
      const response = await fetch(`${API_URL}/app/api/health.php`);
      const data = await response.json();
      
      if (data.status === "ok" && data.database === "connected") {
        setDbStatus("connected");
        setError(""); // Asegurar que no haya errores cuando está conectado
      } else {
        setDbStatus("disconnected");
        setError("No hay conexión a la base de datos. Por favor, verifica la configuración del servidor.");
      }
    } catch (error) {
      setDbStatus("disconnected");
      setError("No se pudo conectar al servidor. Verifica que el backend esté corriendo en " + API_URL);
    }
  };

  if (!isOpen) return null;

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    
    // Verificar conexión antes de intentar login
    if (dbStatus !== "connected") {
      setError("No hay conexión a la base de datos. Por favor, verifica la configuración.");
      await checkDatabaseConnection();
      return;
    }

    setLoading(true);
    try {
      const res = await login({ username, password });
      if (res.success) {
        setError("");
        onClose();
        // Limpiar campos
        setUsername("");
        setPassword("");
      } else {
        setError(res.message || "Error al iniciar sesión");
      }
    } catch (err) {
      setError("Error de conexión. Verifica que el servidor esté corriendo.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className={styles.overlay} onClick={onClose}>
      <div className={styles.modal} onClick={(e) => e.stopPropagation()}>
        <button className={styles.closeBtn} onClick={onClose}>
          ×
        </button>

        <h3 className={styles.title}>Acceso al sistema</h3>

        {/* Indicador de estado de la base de datos */}
        {dbStatus === "checking" && (
          <div className={styles.dbStatus}>
            <span className={styles.statusChecking}>🔄 Verificando conexión a la base de datos...</span>
          </div>
        )}
        {dbStatus === "connected" && (
          <div className={styles.dbStatus}>
            <span className={styles.statusConnected}>✅ Conexión a la base de datos establecida</span>
          </div>
        )}
        {dbStatus === "disconnected" && (
          <div className={styles.dbStatus}>
            <span className={styles.statusDisconnected}>❌ Sin conexión a la base de datos</span>
            <button
              type="button"
              className={styles.btnRetry}
              onClick={checkDatabaseConnection}
            >
              Reintentar conexión
            </button>
          </div>
        )}

        <form onSubmit={handleSubmit} className={styles.form}>
          <label className={styles.label}>
            Usuario
            <input
              className={styles.input}
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              type="text"
              autoComplete="username"
              disabled={dbStatus !== "connected" || loading}
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
              disabled={dbStatus !== "connected" || loading}
            />
          </label>

          {error && <div className={styles.error}>{error}</div>}

          <div className={styles.actions}>
            <button
              type="submit"
              className={styles.btnPrimary}
              disabled={dbStatus !== "connected" || loading}
            >
              {loading ? "Iniciando sesión..." : "Entrar"}
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
