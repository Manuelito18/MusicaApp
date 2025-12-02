# Music Shop 🎶

![React](https://img.shields.io/badge/React-✓-61DAFB?style=for-the-badge&logo=react&logoColor=white) ![Vite](https://img.shields.io/badge/Vite-✓-646CFF?style=for-the-badge&logo=vite&logoColor=white) ![PHP](https://img.shields.io/badge/PHP-✓-8892BF?style=for-the-badge&logo=php&logoColor=white) ![MIT](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

_Una experiencia de compra musical moderna: **frontend React + Vite** se une a un **backend PHP** sencillo pero potente._

---

## 🎯 Visión General

Music Shop es un proyecto **educativo y de prototipado** diseñado para ilustrar la integración de un **frontend SPA (Single Page Application)** moderno con un **backend PHP** minimalista. Es tu punto de partida ideal para:

- **Practicar la separación de responsabilidades** (frontend/backend).
- **Experimentar con React Context API** para la gestión de un carrito de compras.
- **Aprender a construir APIs REST** simples con PHP.
- **Servir como base** para proyectos más complejos con persistencia de datos.

---

## 🚀 ¡Arranca Rápido! (Desarrollo)

Prepárate para rockear en solo unos pasos. Necesitarás dos terminales: una para el frontend y otra para el backend.

```bash
# Terminal 1: Frontend (Client)
cd client
npm install
npm run dev

# Terminal 2: Backend (Server)
# Asegúrate de estar en la raíz del proyecto `musicaApp/`
php -S localhost:8000
```

- **Frontend:** Accesible en `http://localhost:5173` (o el puerto que Vite asigne).
- **Backend:** Corriendo en `http://localhost:8000`.

> **Nota:** El backend incluido es intencionalmente simple, ¡perfecto para empezar!

---

## ✨ Características Destacadas

- **Frontend Modular:** Componentes React reutilizables y un sistema de rutas intuitivo.
- **Gestión de Estado:** Context API para un carrito de compras dinámico y notificaciones de usuario.
- **Backend Minimalista:** Endpoints PHP sencillos para simular una API REST.
- **Diseño Extensible:** Estructura pensada para una fácil expansión hacia una API real y persistencia de datos.

---

## 🛠 Stack Tecnológico

| Frontend                                                                                                                                                                                            | Backend                                                                                      | Herramientas                                                                                      |
| :-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | :------------------------------------------------------------------------------------------- | :------------------------------------------------------------------------------------------------ |
| ![React](https://img.shields.io/badge/React-18-61DAFB?style=flat-square&logo=react&logoColor=white) ![Vite](https://img.shields.io/badge/Vite-3-646CFF?style=flat-square&logo=vite&logoColor=white) | ![PHP](https://img.shields.io/badge/PHP-8-8892BF?style=flat-square&logo=php&logoColor=white) | ![Node](https://img.shields.io/badge/Node-n/a-339933?style=flat-square&logo=node&logoColor=white) |

---

## 📁 Estructura del Proyecto

Una mirada rápida a la organización del código:

```
musicaApp/
├── app/                  # 📦 Lógica del backend y scripts PHP
│   ├── api/              # ⚡ Endpoints RESTful (ej. products.php, orders.php)
│   ├── config/           # ⚙️ Archivos de configuración (ej. database.php)
│   ├── core/             # 🧠 Clases y funciones base del backend
│   ├── models/           # 🧩 Modelos de datos (si aplica, ej. User.php, Product.php)
│   └── utils/            # 🛠 Funciones de utilidad para el backend
├── client/               # ⚛️ Código fuente del frontend (React + Vite)
│   ├── public/           # 🌐 Archivos públicos (favicon, etc.)
│   └── src/              # 🚀 Código fuente principal de React
│       ├── assets/       # 🖼️ Imágenes, iconos, fuentes
│       ├── components/   # 🧱 Componentes reutilizables (Button, Modal, Card)
│       ├── context/      # 🛒 Contextos de React (ej. CartContext, AuthContext)
│       ├── hooks/        # 🎣 Hooks personalizados de React
│       ├── pages/        # 📄 Páginas principales de la aplicación (Home, Products, Checkout)
│       ├── services/     # 📡 Lógica para llamadas a la API
│       ├── styles/       # 🎨 Estilos globales o variables CSS
│       └── utils/        # 💡 Funciones de utilidad para el frontend
├── .env                  # 🔑 Variables de entorno
├── .gitignore            # 🚫 Archivos y carpetas a ignorar por Git
├── package.json          # 📦 Definiciones de scripts y dependencias de Node.js (frontend)
├── README.md             # 📄 Este archivo
└── LICENSE               # 📜 Información de la licencia
```

### Archivos y Carpetas Clave:

- `client/src/components/`: Componentes de UI (Header, Footer, CardProduct, CartSidebar, etc.).
- `client/src/context/`: Contextos de React para el carrito y notificaciones.
- `client/src/pages/`: Vistas principales (Home, Productos, Checkout, Contacto, Nosotros).
- `app/`: La zona de desarrollo para endpoints y scripts PHP del backend.

---

## 🌐 API Endpoints

El backend PHP expone endpoints de ejemplo. Aquí tienes uno para empezar:

- `GET /app/api/devs.php` — Un endpoint de prueba para desarrolladores.

**Ejemplo con `curl`:**

```bash
curl http://localhost:8000/app/api/devs.php
```

**Ejemplo de `fetch` desde el frontend (Vite):**

```js
const base = import.meta.env.VITE_API_URL || "http://localhost:8000";
fetch(`${base}/app/api/devs.php`)
  .then((res) => res.json())
  .then((data) => console.log(data));
```

> **Consejo Pro:** Crea un archivo `.env` o `.env.local` en tu carpeta `client/` para definir la URL de la API:
>
> ```text
> VITE_API_URL=http://localhost:8000
> ```
>
> ¡Recuerda reiniciar Vite después de crear o modificar tu `.env`!

---

## 💡 Contexto Técnico Avanzado

- **Arquitectura:** Una **SPA React** consume datos de un **servidor PHP** a través de endpoints REST simples.
- **Gestión de Estado:** El carrito se maneja con la **Context API de React**. Por defecto, no hay persistencia (¡pero podrías añadir `localStorage` o una base de datos!).
- **Próximos Pasos:** Este proyecto es una base excelente para añadir validación, autenticación y una base de datos (MySQL/SQLite) en el `app/`.

---

## 📊 Estado del Proyecto

| Ítem          | Estado                       | Notas                               |
| :------------ | :--------------------------- | :---------------------------------- |
| Mantenimiento | Experimental / En desarrollo | ¡Tu contribución es bienvenida!     |
| Tests         | No integrados                | Pruebas manuales recomendadas       |
| Persistencia  | No implementada              | Requiere DB externa para producción |

---

## 🤝 Cómo Contribuir

¡Tu ayuda es invaluable! Si quieres mejorar Music Shop, sigue estos pasos:

1.  Haz un "fork" de este repositorio.
2.  Crea una rama para tu nueva característica o arreglo: `git checkout -b feature/mi-increible-funcionalidad`.
3.  Realiza tus cambios, haciendo commits pequeños y descriptivos.
4.  Abre un **Pull Request** detallado hacia la rama `main`, explicando tus cambios y por qué son necesarios.

> **Sugerencia:** Si te interesa, puedo añadir plantillas para PRs/Issues y un archivo `CONTRIBUTING.md` para guiar mejor las contribuciones.

---

## 📝 Licencia

Este proyecto está liberado bajo la **Licencia MIT**. Puedes encontrar los detalles completos en el archivo `LICENSE`.

---

## 📬 Contacto

¿Tienes preguntas, ideas o quieres reportar un bug?

- Abre un [Issue](https://github.com/your-username/music-shop/issues) en este repositorio.
- Contacta al mantenedor a través de su perfil de GitHub (¡siempre abierto a una buena conversación!).
