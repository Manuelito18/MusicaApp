import styles from "../components/styles/HeroSlider.module.css";
import ChevronLeft from "../../src/assets/imgs/ChevronLeft.svg";
import ChevronRight from "../../src/assets/imgs/ChevronRight.svg";
import { useState, useEffect, useCallback } from "react";
import { useNavigate } from "react-router-dom";

const HeroSlider = ({ slides = [] }) => {
  const navigate = useNavigate();
  const [currentSlide, setCurrentSlide] = useState(0);
  const slidesCount = slides.length;

  const goToSlide = useCallback(
    (index) => setCurrentSlide((index + slidesCount) % slidesCount),
    [slidesCount]
  );

  const nextSlide = useCallback(
    () => goToSlide(currentSlide + 1),
    [currentSlide, goToSlide]
  );
  const prevSlide = useCallback(
    () => goToSlide(currentSlide - 1),
    [currentSlide, goToSlide]
  );

  useEffect(() => {
    if (!slidesCount) return;
    const interval = setInterval(nextSlide, 5000);
    return () => clearInterval(interval);
  }, [nextSlide, slidesCount]);

  function irACatalogo() {
    var ir;
    if (currentSlide === 0) ir = "guitarras";
    if (currentSlide === 1) ir = "teclados-pianos";
    if (currentSlide === 2) ir = "baterias-percusion";
    if (currentSlide === 3) ir = "vientos";
    if (currentSlide === 4) ir = "dj-iluminacion";
    navigate(`/productos/${ir}`);
  }

  if (!slidesCount) return null;

  return (
    <section className={styles.hero}>
      <div className={styles.slider}>
        {slides.map((slide, index) => (
          <div
            key={index}
            className={`${styles.slide} ${
              index === currentSlide ? styles.active : ""
            }`}
            style={{
              backgroundImage: `linear-gradient(to right, rgba(0,0,0,0.6), rgba(0,0,0,0.2)), url(${slide.image})`,
              backgroundBlendMode: "overlay",
            }}
            aria-hidden={index !== currentSlide}
          >
            <div className={styles.slideContent}>
              <div className="container">
                <div className={styles.heroText}>
                  <h2 className={styles.heroTitle}>{slide.title}</h2>
                  <p className={styles.heroSubtitle}>{slide.subtitle}</p>
                  <p className={styles.heroDescription}>{slide.description}</p>
                  <div className={styles.heroButtons}>
                    <button
                      className={styles.btn + " " + styles.btnPrimary}
                      onClick={() => irACatalogo()}
                    >
                      Ver Ofertas
                    </button>
                    <button
                      onClick={() => irACatalogo()}
                      className={styles.btn + " " + styles.btnOutline}
                    >
                      Catálogo
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>

      <div className={styles.sliderControls}>
        <button
          className={`btn-ghost ${styles.sliderBtn} ${styles.prev}`}
          onClick={prevSlide}
          aria-label="Anterior"
        >
          <img src={ChevronLeft} alt="Anterior" width={24} height={24} />
        </button>
        <button
          className={`btn-ghost ${styles.sliderBtn} ${styles.next}`}
          onClick={nextSlide}
          aria-label="Siguiente"
        >
          <img src={ChevronRight} alt="Siguiente" width={24} height={24} />
        </button>
      </div>

      <div className={styles.sliderDots}>
        {slides.map((_, index) => (
          <button
            key={index}
            className={`${styles.dot} ${
              index === currentSlide ? styles.active : ""
            }`}
            onClick={() => goToSlide(index)}
            aria-label={`Ir al slide ${index + 1}`}
            aria-current={index === currentSlide}
          />
        ))}
      </div>
    </section>
  );
};

export default HeroSlider;
