import { CartProvider } from "./context/CartContext";
import { NotificationProvider } from "./context/NotificationContext";

export const AppProviders = ({ children }) => (
  <NotificationProvider>
    <CartProvider>{children}</CartProvider>
  </NotificationProvider>
);
