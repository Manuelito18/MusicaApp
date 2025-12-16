import { useState } from "react";
import styles from "./styles/PaymentForm.module.css";

export default function PaymentForm({ onPay }) {
  const [method, setMethod] = useState("card");
  const [card, setCard] = useState({ num: "", name: "", exp: "", cvv: "" });
  const [yape, setYape] = useState("");

  const handleSubmit = (e) => {
    e.preventDefault();
    onPay({ method, data: method === "card" ? card : yape });
  };

  return (
    <form onSubmit={handleSubmit} className={styles.paymentForm}>
      <h2>Método de pago</h2>
      <select onChange={(e) => setMethod(e.target.value)} value={method}>
        <option value="card">Tarjeta de crédito</option>
        <option value="mobile">Yape / Plin</option>
      </select>

      {method === "card" ? (
        <div className="card-inputs">
          <input
            type="text"
            placeholder="Número de tarjeta"
            value={card.num}
            onChange={(e) => setCard({ ...card, num: e.target.value })}
            required
          />
          <input
            type="text"
            placeholder="Nombre impreso"
            value={card.name}
            onChange={(e) => setCard({ ...card, name: e.target.value })}
            required
          />
          <div className="row">
            <input
              type="text"
              placeholder="MM/AA"
              value={card.exp}
              onChange={(e) => setCard({ ...card, exp: e.target.value })}
              required
            />
            <input
              type="text"
              placeholder="CVV"
              value={card.cvv}
              onChange={(e) => setCard({ ...card, cvv: e.target.value })}
              required
            />
          </div>
        </div>
      ) : (
        <div className="mobile-input">
          <input
            type="text"
            placeholder="Número Yape/Plin"
            value={yape}
            onChange={(e) => setYape(e.target.value)}
            required
          />
        </div>
      )}
      <button type="submit" className={styles.btnPago}>
        Pagar ahora
      </button>
    </form>
  );
}
