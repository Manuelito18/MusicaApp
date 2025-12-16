import styles from "./styles/CardPer.module.css";
import { FaGithub } from "react-icons/fa";

export default function CardPer({
  FotoPerfil = null,
  Nombre,
  Rol,
  Correo = null,
  Github = null,
}) {
  return (
    <div className={styles.card}>
      <img className={styles.photo} src={FotoPerfil} alt="image" />
      <h3 className={styles.name}>{Nombre}</h3>
      <h4 className={styles.role}>{Rol}</h4>
      <p className={styles.description}>{Correo}</p>

      {Github && (
        <a
          href={Github}
          className={styles.githubIcon}
          target="_blank"
          rel="noopener noreferrer"
          aria-label={`${Nombre}'s GitHub`}
        >
          <FaGithub />
        </a>
      )}
    </div>
  );
}
