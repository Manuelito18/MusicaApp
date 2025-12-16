import { createContext, useContext, useState, useEffect } from "react";

const NotificationContext = createContext();

export const useNotification = () => useContext(NotificationContext);

export const NotificationProvider = ({ children }) => {
  const [message, setMessage] = useState("");

  useEffect(() => {
    if (message) {
      const timer = setTimeout(() => setMessage(""), 3000);
      return () => clearTimeout(timer);
    }
  }, [message]);

  return (
    <NotificationContext.Provider value={{ setMessage }}>
      {children}
      {message && <div className="notification">{message}</div>}
    </NotificationContext.Provider>
  );
};
