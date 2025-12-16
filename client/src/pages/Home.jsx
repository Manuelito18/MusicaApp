import styles from "./styles/Home.module.css";
import HeroSlider from "../components/HeroSlider";
import { promos } from "../data/promos";
import CategoriesBlock from "../components/CategoriesBlock";
import ProductBlock from "../components/ProductBlock";

export default function Home() {
  return (
    <div className={styles.home}>
      <HeroSlider slides={promos} />
      <CategoriesBlock />
      <ProductBlock />
    </div>
  );
}
