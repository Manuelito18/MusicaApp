import { createContext, useContext, useState, useEffect } from "react";

const UserContext = createContext(null);

export const UserProvider = ({ children }) => {
  const [user, setUser] = useState(() => {
    try {
      return JSON.parse(localStorage.getItem("user")) || null;
    } catch (e) {
      return null;
    }
  });

  useEffect(() => {
    if (user) localStorage.setItem("user", JSON.stringify(user));
    else localStorage.removeItem("user");
  }, [user]);

  const login = ({ username, password }) => {
    // Implementación simple: acepta cualquier usuario/contraseña no vacíos.
    // Reemplaza esta lógica por llamada real a API cuando esté disponible.
    if (username && password) {
      const u = { username };
      setUser(u);
      return { success: true };
    }
    return { success: false, message: "Usuario y contraseña requeridos" };
  };

  const logout = () => {
    setUser(null);
  };

  return (
    <UserContext.Provider value={{ user, login, logout }}>
      {children}
    </UserContext.Provider>
  );
};

export const useUser = () => useContext(UserContext);
