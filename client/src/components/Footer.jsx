import {
  FaGithub,
  FaEnvelope,
  FaYoutube,
  FaBook,
  FaFacebook,
  FaInstagram,
  FaWhatsapp,
} from "react-icons/fa";
import styles from "./styles/Footer.module.css";

export default function Footer() {
  return (
    <footer className={styles.footer}>
      <div className={styles.content}>
        <p>© 2025 music-shop – Música ficticia, código real 🎧</p>
        <nav className={styles.social}>
          <a
            href="https://github.com/Manuelito18/Music-Shop-WebSite"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="GitHub"
          >
            <FaGithub />
          </a>
          <a
            href="https://www.facebook.com/colmemusic/?locale=es_LA"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Facebook"
          >
            <FaFacebook />
          </a>
          <a
            href="https://www.instagram.com/reel/DKwa0D4gFPO/?utm_source=ig_web_copy_link"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Instagram"
          >
            <FaInstagram />
          </a>
          <a
            href="https://wa.me/+51979224000"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="WhatsApp"
          >
            <FaWhatsapp />
          </a>
          <a href="mailto:mancol@hotmail.com" aria-label="Email">
            <FaEnvelope />
          </a>
          <a
            href="https://www.youtube.com/channel/UCBYloQzyO20TZfttyNlC_Lg"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="YouTube"
          >
            <FaYoutube />
          </a>
          <a
            href="https://music-shop.com/libro-de-reclamaciones"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Libro de Reclamaciones"
          >
            <FaBook />
          </a>
        </nav>
      </div>
    </footer>
  );
}
