# Music Shop

![React](https://img.shields.io/badge/React-✓-61DAFB?style=for-the-badge&logo=react&logoColor=white) ![Vite](https://img.shields.io/badge/Vite-✓-646CFF?style=for-the-badge&logo=vite&logoColor=white) ![PHP](https://img.shields.io/badge/PHP-✓-8892BF?style=for-the-badge&logo=php&logoColor=white) ![MIT](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

Una tienda online de música — frontend en **React + Vite** y backend en **PHP**. Este README está organizado para que encuentres rápidamente cómo ejecutar, entender y contribuir al proyecto.

---

> **Descripción corta:**  
> Proyecto ejemplo que une un frontend moderno (React + Vite) con un backend PHP sencillo. Pensado para aprendizaje, prototipado rápido y demostraciones.

---

## ✨ Características principales

- Frontend con componentes reutilizables y rutas.
- Contexto para carrito y notificaciones.
- Backend PHP con endpoints simples para simular API.
- Estructura pensada para extender hacia una API real y persistencia.

---

## 🛠 Tecnologías

| Frontend                                                                                                                                                                                            | Backend                                                                                      | Herramientas                                                                                      |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------- |
| ![React](https://img.shields.io/badge/React-18-61DAFB?style=flat-square&logo=react&logoColor=white) ![Vite](https://img.shields.io/badge/Vite-3-646CFF?style=flat-square&logo=vite&logoColor=white) | ![PHP](https://img.shields.io/badge/PHP-8-8892BF?style=flat-square&logo=php&logoColor=white) | ![Node](https://img.shields.io/badge/Node-n/a-339933?style=flat-square&logo=node&logoColor=white) |

---

## 🚀 Quick start (desarrollo)

Abre dos terminales: uno para el frontend y otro para el backend.

```bash
# Terminal A (Frontend)
cd frontend
npm install
npm run dev

# Terminal B (Backend)
cd backend
composer install || true
php -S localhost:8000
```

- Frontend accesible en `http://localhost:5173` (o puerto que Vite asigne).
- Backend accesible en `http://localhost:8000`.

> Nota: el backend incluido es intencionalmente simple. Para producción añade base de datos, validación y autenticación.

---

## 📁 Estructura del proyecto (resumen)

```text
music-shop-PHP/
├── backend/
│   ├── index.php
│   └── api/
│       ├── conexion.php
│       └── devs.php
├── frontend/
│   ├── public/
│   ├── src/
│   │   ├── components/
│   │   ├── context/
│   │   └── pages/
│   └── package.json
├── README.md
└── LICENSE
```

### Archivos y carpetas clave

- `frontend/src/components/` — componentes UI (Header, Footer, CardProduct, CartSidebar...).
- `frontend/src/context/` — contextos para carrito y notificaciones.
- `frontend/src/pages/` — páginas principales: Home, Productos, Checkout, Contacto, Nosotros.
- `backend/` — endpoints y scripts PHP.

---

## 📦 Estado del proyecto

| Ítem          | Estado                           |
| ------------- | -------------------------------- |
| Mantenimiento | Experimental / desarrollo        |
| Tests         | No integrados (manuales)         |
| Persistencia  | No (requiere DB para producción) |

---

## 🤝 Cómo contribuir

1. Haz fork del repositorio.
2. Crea una rama clara: `git checkout -b feature/mi-cambio`.
3. Haz commits pequeños y descriptivos.
4. Abre un Pull Request hacia `main` explicando tu cambio.

Si quieres, puedo añadir plantillas de PR/Issue y un `CONTRIBUTING.md` con convenciones de commits.

---

## 📝 Licencia

Este proyecto está bajo la licencia MIT — ver `LICENSE`.

---

## 📬 Contacto

Abre un issue para preguntas, o contacta al mantenedor en el perfil del repositorio.

---

_README reorganizado y limpio. Si quieres un estilo aún más visual (capturas, GIFs o badges adicionales), dime qué assets quieres añadir y los incorporo._

---

## Tabla de contenido

- [Descripción](#descripción)
- [Características](#-características-principales)
- [Tecnologías](#-tecnologías)
- [Quick start](#-quick-start-desarrollo)
- [API / Endpoints](#-api--endpoints)
- [Estructura del proyecto](#-estructura-del-proyecto-resumen)
- [Estado](#-estado-del-proyecto)
- [Contribuir](#-cómo-contribuir)
- [Licencia & Contacto](#-licencia)

---

## Descripción

Music Shop es un proyecto educativo / prototipo que muestra cómo integrar un frontend SPA moderno (React + Vite) con un backend sencillo en PHP. Está pensado para:

- Practicar la separación frontend/backend.
- Experimentar con context API para un carrito de compras.
- Probar despliegues simples o integrar una API real más adelante.

Este repositorio incluye ejemplos de componentes, rutas, contexto de carrito y endpoints PHP mínimos en `backend/`.

---

## 🔍 API / Endpoints

Los archivos en `backend/api/` contienen endpoints de ejemplo. Un endpoint disponible es:

- `GET /api/devs.php` — endpoint de ejemplo (ver `backend/api/devs.php`).

Ejemplo rápido con curl:

```bash
curl http://localhost:8000/api/devs.php
```

Ejemplo de fetch desde el frontend (Vite):

```js
const base = import.meta.env.VITE_API_URL || "http://localhost:8000";
fetch(`${base}/api/devs.php`)
  .then((res) => res.json())
  .then((data) => console.log(data));
```

Sugerencia: crea un archivo `.env` o `.env.local` en `frontend/` con:

```text
VITE_API_URL=http://localhost:8000
```

Luego reinicia Vite para que coja la variable.

---

## ✨ Mejor contexto técnico

- Arquitectura: SPA React (cliente) ↔ PHP (servidor). El cliente consume endpoints REST simples.
- Estado: el carrito se maneja con Context API; no hay persistencia por defecto (podrías añadir localStorage o DB).
- Extensiones recomendadas: añadir validación, autenticación y una base de datos (MySQL / SQLite) en `backend/`.

---

## 🎨 Cómo hacerlo más "cool" (ideas rápidas)

- Añadir capturas de pantalla en `frontend/public/imgs/` y enlazarlas desde este README.
- Incluir GIFs cortos mostrando el flujo del carrito.
- Añadir badges dinámicos: Issues abiertas, última versión npm, etc.

Si quieres, sube una imagen a `frontend/public/imgs/` y yo la enlazo aquí.

---

## 🧭 Flujo de desarrollo (rápido)

1. Clona el repo y crea tu rama.
2. Ejecuta frontend y backend en paralelo (ver Quick start).
3. Modifica componentes en `frontend/src/` y endpoints en `backend/api/`.
4. Abre PR con descripciones y screenshots.

---

## Checklist antes de hacer un PR (recomendado)

- [ ] Ejecuta `npm run dev` y revisa que la UI funcione.
- [ ] Prueba los endpoints que toques con curl o Postman.
- [ ] Añade capturas si el cambio afecta la UI.
- [ ] Describe el cambio y el motivo en el PR.

---

## Estado del README

Este README fue reorganizado para mejorar lectura y contexto. Puedo aplicar más mejoras visuales (badges extra, capturas y plantillas). Dime qué prefieres.
