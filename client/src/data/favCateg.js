import GuitarIcon from "../assets/imgs/iconsCateg/guitar.svg";
import PianoIcon from "../assets/imgs/iconsCateg/piano.svg";
import DrumIcon from "../assets/imgs/iconsCateg/bateria.svg";
import MicIcon from "../assets/imgs/iconsCateg/micros.svg";
import AmpIcon from "../assets/imgs/iconsCateg/parlante.svg";

export const categories = [
  {
    title: "Guitarras",
    icon: GuitarIcon,
    color: "linear-gradient(135deg, #f43f5e, #ec4899)",
    ruta: "guitarras",
  },
  {
    title: "Pianos",
    icon: PianoIcon,
    color: "linear-gradient(135deg, #3b82f6, #06b6d4)",
    ruta: "teclados-pianos",
  },
  {
    title: "Baterías",
    icon: DrumIcon,
    color: "linear-gradient(135deg, #10b981, #22c55e)",
    ruta: "baterias-percusion",
  },
  {
    title: "Micrófonos",
    icon: MicIcon,
    color: "linear-gradient(135deg, #8b5cf6, #a855f7)",
    ruta: "microfonos",
  },
  {
    title: "Amplificadores",
    icon: AmpIcon,
    color: "linear-gradient(135deg, #6366f1, #3b82f6)",
    ruta: "amplificadores",
  },
];
