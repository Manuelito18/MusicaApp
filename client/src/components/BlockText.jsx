import styles from "./styles/BlockText.module.css";

const BlockText = ({ icon = null, title, text, background }) => {
  const blockStyle = {
    background:
      background || "linear-gradient(135deg,rgb(30, 63, 53),rgb(25, 144, 99))",
  };
  return (
    <div className={styles.block} style={blockStyle}>
      {icon && <div className={styles.icon}>{icon}</div>}
      <h2 className={styles.title}>{title}</h2>
      <p className={styles.text}>{text}</p>
    </div>
  );
};

export default BlockText;
