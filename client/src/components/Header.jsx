import { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import styles from "./styles/Header.module.css";
import { useCart } from "../context/CartContext";
import CartSidebar from "../components/CartSidebar";

export default function Navbar() {
  const [scrolled, setScrolled] = useState(false);
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isSearchOpen, setIsSearchOpen] = useState(false);
  const [isCartOpen, setIsCartOpen] = useState(false);
  const [searchQuery, setSearchQuery] = useState("");

  const { cartItems } = useCart();
  const totalQuantity = cartItems.reduce((sum, item) => sum + item.quantity, 0);

  useEffect(() => {
    const manejarScroll = () => {
      setScrolled(window.scrollY > 10);
    };
    window.addEventListener("scroll", manejarScroll);
    return () => window.removeEventListener("scroll", manejarScroll);
  }, []);
  useEffect(() => {
    if (isCartOpen) {
      document.body.classList.add("no-scroll");
    } else {
      document.body.classList.remove("no-scroll");
    }
  }, [isCartOpen]);

  const toggleMenu = () => setIsMenuOpen(!isMenuOpen);
  const toggleSearch = () => setIsSearchOpen(!isSearchOpen);
  const toggleCart = () => setIsCartOpen(!isCartOpen);

  return (
    <>
      <header className={`${styles.header} ${scrolled ? styles.scrolled : ""}`}>
        <div className={styles.logo}>
          <div className={styles.logoIcon}>
            <Link to="/">
              <img src="/imgs/icons/logonar.png" alt="Logo music-shop" />
            </Link>
          </div>
        </div>

        <nav className={`${styles.navbar} ${isMenuOpen ? styles.navOpen : ""}`}>
          <li>
            <Link to="/" onClick={() => setIsMenuOpen(false)}>
              Inicio
            </Link>
          </li>
          <li>
            <Link to="/productos" onClick={() => setIsMenuOpen(false)}>
              Productos
            </Link>
          </li>
          <li>
            <Link to="/nosotros" onClick={() => setIsMenuOpen(false)}>
              Nosotros
            </Link>
          </li>
          <li>
            <Link to="/contacto" onClick={() => setIsMenuOpen(false)}>
              Contacto
            </Link>
          </li>
        </nav>

        <div className={styles.headerActions}>
          <button className={styles.btnGhost} onClick={toggleSearch}>
            <img
              className={styles.imgSearch}
              src="/imgs/icons/search.svg"
              alt="Buscar"
            />
          </button>
          <button className={styles.btnGhostCartBtn} onClick={toggleCart}>
            <img
              className={styles.imgCart}
              src="/imgs/icons/cart.svg"
              alt="Carrito"
            />
            <span className={styles.cartBadge}>{totalQuantity}</span>
          </button>
          <button className={styles.btnGhostMenuBtn} onClick={toggleMenu}>
            <img
              className={styles.imgMenu}
              src="/imgs/icons/menu.svg"
              alt="Menú"
            />
          </button>
        </div>
      </header>

      {isSearchOpen && (
        <div className={styles.searchContainer}>
          <input
            type="text"
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            placeholder="Buscar productos..."
            className={styles.searchInput}
          />
        </div>
      )}

      <CartSidebar isOpen={isCartOpen} toggleCart={toggleCart} />
    </>
  );
}
