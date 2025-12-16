import styles from "./styles/Checkout.module.css";
import CheckoutSummary from "../components/CheckoutSummary";
import { useState } from "react";
import { useCart } from "../context/CartContext";
import PaymentForm from "../components/PaymentForm";
import Processing from "../components/Processing";
import Success from "../components/Success";
import ScrollToTop from "../components/specials/ScrollToTop";
import { useEffect } from "react";

export default function Checkout() {
  const { cartItems, clearCart } = useCart();
  const [step, setStep] = useState(1);
  const [address, setAddress] = useState("");

  useEffect(() => {
    if (step === 2) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "auto";
    }
  }, [step]);

  const handlePayment = async () => {
    if (step === 1 && !address.trim()) {
      return alert("Por favor, completa la dirección de entrega.");
    }
    setStep(2);
    setTimeout(() => {
      setStep(3);
      clearCart();
    }, 3000);
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
