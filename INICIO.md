# 🚀 Guía de Inicio Rápido

## Pasos para ejecutar la aplicación

### 1. Preparar el Frontend (React)

Abre una **Terminal 1** y ejecuta:

```bash
# Navegar a la carpeta del cliente
cd client

# Instalar dependencias (solo la primera vez)
npm install

# Iniciar el servidor de desarrollo
npm run dev
```

El frontend estará disponible en: **http://localhost:5173**

### 2. Preparar el Backend (PHP)

Abre una **Terminal 2** (nueva terminal) y ejecuta:

```bash
# Asegúrate de estar en la raíz del proyecto (MusicaApp/)
# Si estás en client/, vuelve atrás:
cd ..

# Iniciar el servidor PHP
php -S localhost:8000
```

El backend estará disponible en: **http://localhost:8000**

### 3. Configurar la URL de la API (Opcional pero recomendado)

Crea un archivo `.env` en la carpeta `client/` con el siguiente contenido:

```env
VITE_API_URL=http://localhost:8000
```

**Nota:** Si creas o modificas el archivo `.env`, necesitas reiniciar el servidor de Vite (Ctrl+C y luego `npm run dev` nuevamente).

## 📋 Resumen de Comandos

### Terminal 1 - Frontend:
```bash
cd client
npm install  # Solo la primera vez
npm run dev
```

### Terminal 2 - Backend:
```bash
# Desde la raíz del proyecto
php -S localhost:8000
```

## ✅ Verificar que todo funciona

1. **Frontend:** Abre http://localhost:5173 en tu navegador
2. **Backend:** Abre http://localhost:8000 en tu navegador (deberías ver algo o un error 404, pero el servidor está corriendo)

## 🔐 Credenciales de Administrador

Para acceder al panel de administración, usa estas credenciales (según la base de datos):

- **Username:** `admin`
- **Password:** `admin123`

## 🐛 Solución de Problemas

### Error: "php: command not found"
- Instala PHP en tu sistema
- En Linux: `sudo apt install php` o `sudo pacman -S php`
- Verifica con: `php -v`

### Error: "npm: command not found"
- Instala Node.js y npm
- Descarga desde: https://nodejs.org/
- Verifica con: `npm -v`

### El frontend no puede conectar con el backend
- Verifica que ambos servidores estén corriendo
- Verifica que la URL en `.env` sea correcta: `VITE_API_URL=http://localhost:8000`
- Reinicia ambos servidores

### Puerto 8000 o 5173 ya está en uso
- Cierra otros programas que usen esos puertos
- O cambia el puerto:
  - PHP: `php -S localhost:8001` (y actualiza `.env`)
  - Vite: `npm run dev -- --port 5174`

