import styles from "./styles/Checkout.module.css";
import CheckoutSummary from "../components/CheckoutSummary";
import { useState } from "react";
import { useCart } from "../context/CartContext";
import PaymentForm from "../components/PaymentForm";
import Processing from "../components/Processing";
import Success from "../components/Success";
import ScrollToTop from "../components/specials/ScrollToTop";
import { useEffect } from "react";
import { useUser } from "../context/UserContext";

const API_URL = import.meta.env.VITE_API_URL || "http://localhost:8000";

export default function Checkout() {
  const { cartItems, clearCart } = useCart();
  const { user, token, getAuthHeaders } = useUser();
  const [step, setStep] = useState(1);
  const [address, setAddress] = useState("");
  const [error, setError] = useState("");

  useEffect(() => {
    if (step === 2) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "auto";
    }
  }, [step]);

  const handlePayment = async ({ method, data }) => {
    if (step === 1 && !address.trim()) {
      return alert("Por favor, completa la dirección de entrega.");
    }
    if (!user || !token) {
      return alert("Debes iniciar sesión para finalizar la compra.");
    }
    if (!cartItems.length) {
      return alert("Tu carrito está vacío.");
    }

    setStep(2);
    setError("");
    try {
      const response = await fetch(`${API_URL}/app/api/checkout.php`, {
        method: "POST",
        headers: getAuthHeaders(),
        body: JSON.stringify({
          address,
          payment: { method, data },
          items: cartItems,
        }),
      });

      const res = await response.json().catch(() => ({}));
      if (!response.ok) {
        setError(res.error || "No se pudo crear el pedido.");
        setStep(1);
        return;
      }

      // OK: pedido creado en BD -> ahora aparecerá como venta
      setStep(3);
      clearCart();
    } catch (e) {
      setError("Error de conexión con el backend.");
      setStep(1);
    }
  };

  return (
    <>
      <ScrollToTop />
      <div className={styles.checkoutContainer}>
        {step === 1 && (
          <div className={styles.grid}>
            <div className={styles.summaryWrap}>
              <CheckoutSummary items={cartItems} />
            </div>

            <div className={styles.formWrap}>
              <div className={styles.addressWrap}>
                <h3>📍 Dirección de entrega</h3>
                <textarea
                  className={styles.addressInput}
                  placeholder="Calle, número, distrito, referencia..."
                  value={address}
                  onChange={(e) => setAddress(e.target.value)}
                />
              </div>
              {error && <div className={styles.error}>{error}</div>}
              <PaymentForm onPay={handlePayment} />
            </div>
          </div>
        )}

        {step === 2 && <Processing />}
        {step === 3 && (
          <>
            <ScrollToTop />
            <Success />
          </>
        )}
      </div>
    </>
  );
}
