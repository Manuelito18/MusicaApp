import styles from "../components/styles/CartSidebar.module.css";
import { Link } from "react-router-dom";
import { useCart } from "../context/CartContext";
import { useUser } from "../context/UserContext";

export default function CartSidebar({ isOpen, toggleCart }) {
  const { cartItems, increaseQuantity, decreaseQuantity, removeFromCart } =
    useCart();
  const { user, openAuthModal } = useUser();

  if (!isOpen) return null;

  if (!user) {
    return (
      <div className={styles.cartSidebar}>
        <button className={styles.closeCartIcon} onClick={toggleCart}>
          &times;
        </button>
        <h3>Tu carrito</h3>
        <p>Debes iniciar sesión para ver el carrito.</p>
        <button
          className={styles.checkoutBtn}
          onClick={() => {
            toggleCart();
            openAuthModal("login");
          }}
        >
          Iniciar sesión / Crear cuenta
        </button>
      </div>
    );
  }

  const total = cartItems.reduce(
    (acc, item) => acc + item.precio * item.quantity,
    0
  );

  return (
    <div className={styles.cartSidebar}>
      <button className={styles.closeCartIcon} onClick={toggleCart}>
        &times;
      </button>
      <h3>Tu carrito</h3>

      <div className={styles.cartContent}>
        {cartItems.length === 0 ? (
          <p>Carrito vacío</p>
        ) : (
          <ul className={styles.cartList}>
            {cartItems.map((item) => (
              <li key={item.id} className={styles.cartItem}>
                <img
                  src={item.imagen}
                  alt={item.nombre}
                  className={styles.cartItemImg}
                />
                <div className={styles.cartItemInfo}>
                  <h4>{item.nombre}</h4>
                  <p>
                    S/ {item.precio} x {item.quantity}
                  </p>
                  <p>Total: S/ {item.precio * item.quantity}</p>
                  <div className={styles.cartItemActions}>
                    <button onClick={() => decreaseQuantity(item.id)}>-</button>
                    <span>{item.quantity}</span>
                    <button onClick={() => increaseQuantity(item.id)}>+</button>
                    <button onClick={() => removeFromCart(item.id)}>
                      Eliminar
                    </button>
                  </div>
                </div>
              </li>
            ))}
          </ul>
        )}
      </div>

      {cartItems.length > 0 && (
        <div className={styles.cartFooter}>
          <p>
            <strong>Total:</strong> S/ {total}
          </p>
          <Link
            to="checkout"
            className={styles.checkoutBtn}
            onClick={toggleCart}
          >
            Ir a pagar
          </Link>
        </div>
      )}
    </div>
  );
}
