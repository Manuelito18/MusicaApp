import { CartProvider } from "./context/CartContext";
import { NotificationProvider } from "./context/NotificationContext";
import { UserProvider } from "./context/UserContext";

export const AppProviders = ({ children }) => (
  <NotificationProvider>
    <UserProvider>
      <CartProvider>{children}</CartProvider>
    </UserProvider>
  </NotificationProvider>
);
