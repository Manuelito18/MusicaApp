import { createContext, useContext, useState, useEffect } from "react";

const API_URL = import.meta.env.VITE_API_URL || "http://localhost:8000";

const UserContext = createContext(null);

export const UserProvider = ({ children }) => {
  const [user, setUser] = useState(() => {
    try {
      const stored = localStorage.getItem("user");
      const token = localStorage.getItem("token");
      if (stored && token) {
        return JSON.parse(stored);
      }
      return null;
    } catch (e) {
      return null;
    }
  });

  const [token, setToken] = useState(() => {
    return localStorage.getItem("token") || null;
  });

  // Auth modal global (para abrir desde cualquier componente)
  const [isAuthModalOpen, setIsAuthModalOpen] = useState(false);
  const [authModalMode, setAuthModalMode] = useState("login"); // 'login' | 'register'

  useEffect(() => {
    if (user && token) {
      localStorage.setItem("user", JSON.stringify(user));
      localStorage.setItem("token", token);
    } else {
      localStorage.removeItem("user");
      localStorage.removeItem("token");
    }
  }, [user, token]);

  const openAuthModal = (mode = "login") => {
    setAuthModalMode(mode);
    setIsAuthModalOpen(true);
  };

  const closeAuthModal = () => {
    setIsAuthModalOpen(false);
  };

  const login = async ({ username, password }) => {
    try {
      const response = await fetch(`${API_URL}/app/api/auth.php`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ username, password }),
      });

      // Si la respuesta no es JSON válido, puede ser un error de conexión
      let data;
      try {
        data = await response.json();
      } catch (jsonError) {
        return {
          success: false,
          message: `Error de conexión con el servidor (${response.status}). Verifica que el backend esté corriendo en ${API_URL}`,
        };
      }

      if (!response.ok) {
        return { success: false, message: data.error || "Error al iniciar sesión" };
      }

      setToken(data.token);
      setUser(data.user);
      return { success: true };
    } catch (error) {
      // Error de red o conexión
      if (error.name === "TypeError" && error.message.includes("fetch")) {
        return {
          success: false,
          message: `No se pudo conectar al servidor. Verifica que el backend PHP esté corriendo en ${API_URL}`,
        };
      }
      return {
        success: false,
        message: `Error de conexión: ${error.message}`,
      };
    }
  };

  const register = async ({ username, password, email, nombres, apellidos, telefono, numeroDocumento }) => {
    try {
      const response = await fetch(`${API_URL}/app/api/auth.php?path=register`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          username,
          password,
          email,
          nombres,
          apellidos,
          telefono,
          numeroDocumento,
          idRol: 3, // Cliente
        }),
      });

      const data = await response.json().catch(() => ({}));
      if (!response.ok) {
        return { success: false, message: data.error || "No se pudo registrar" };
      }

      // Auto-login luego del registro
      return await login({ username, password });
    } catch (error) {
      return { success: false, message: `Error de conexión: ${error.message}` };
    }
  };

  const logout = () => {
    setUser(null);
    setToken(null);
    closeAuthModal();
  };

  const isAdmin = () => {
    return user?.rol === "Administrador" || user?.idRol === 1;
  };

  const getAuthHeaders = () => {
    if (!token) return {};
    return {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    };
  };

  return (
    <UserContext.Provider
      value={{
        user,
        token,
        login,
        register,
        logout,
        isAdmin,
        getAuthHeaders,
        isAuthModalOpen,
        authModalMode,
        openAuthModal,
        closeAuthModal,
        setAuthModalMode,
      }}
    >
      {children}
    </UserContext.Provider>
  );
};

export const useUser = () => useContext(UserContext);
