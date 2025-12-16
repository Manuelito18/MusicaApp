import styles from "./styles/Nosotros.module.css";
import BlockText from "../components/BlockText";
import CardPer from "../components/CardPer";

import { useState, useEffect } from "react";

export default function Nosotros() {
  /* implementado la carga de los datos de los programadores */
  const [devs, setDevs] = useState([]);

  useEffect(() => {
    fetch("http://localhost/appsWeb/Music-Shop-WebSite/backend/api/devs.php")
      .then((response) => response.json())
      .then((data) => setDevs(data))
      .catch((error) => console.error("Error cargando devs:", error));
  }, []);

  /* --------------------------------------------------------------- */

  return (
    <div className={styles.container}>
      <div className={styles.section}>
        <div className={styles.blockTextWrapper}>
          <BlockText
            title="Sobre Nosotros"
            text="Más de 25 años compartiendo la pasión por la música"
            background="linear-gradient(135deg, #591C86, #821844)"
          />
        </div>
      </div>
      <div className={styles.history}>
        <div className={styles.historyText}>
          <h2>Nuestra Historia</h2>
          <p>
            Nuestra tienda instrumentos Music Shop nació en 2010 con un sueño
            simple pero poderoso: hacer que la música sea accesible para todos.
            Comenzamos como una pequeña tienda familiar en el corazón de la
            ciudad, con la visión de ofrecer instrumentos de calidad a precios
            justos. A lo largo de los años, hemos crecido junto con nuestra
            comunidad musical, expandiendo nuestro catálogo y servicios, pero
            manteniendo siempre nuestros valores fundamentales: calidad,
            servicio personalizado y pasión por la músic
          </p>
        </div>
        <div className={styles.historyImage}>
          <img src="/imgs/etc/historia.webp" alt="hombre tocando guitarra" />
        </div>
      </div>
      <div className={styles.team}>
        <h2>Nuestro Equipo</h2>
        <div className={styles.teamUser}>
          {devs.map((dev, index) => (
            <CardPer key={index} {...dev} />
          ))}
        </div>
      </div>
      <div className={styles.numbers}>
        <h2>Nuestros Numeros</h2>
        <div className={styles.dats}>
          <BlockText
            title="15+"
            text="Años de experiencia"
            background="linear-gradient(135deg, #F59B0C, #F97416)"
          />
          <BlockText
            title="2000+"
            text="Clientes satisfechos"
            background="linear-gradient(135deg, #F59B0C, #F97416)"
          />
          <BlockText
            title="500+"
            text="Instrumentos en Stock"
            background="linear-gradient(135deg, #F59B0C, #F97416)"
          />
          <BlockText
            title="50+"
            text="Marcas representadas"
            background="linear-gradient(135deg, #F59B0C, #F97416)"
          />
        </div>
      </div>
    </div>
  );
}
