import styles from "./styles/Layout.module.css";
import Header from "./Header";
import Footer from "./Footer";

import { Outlet } from "react-router-dom";

export default function Layout() {
  return (
    <div className={styles.wrapper}>
      <Header />
      <main className={styles.main}>
        <Outlet />
      </main>
      <Footer />
    </div>
  );
}
