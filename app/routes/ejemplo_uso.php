<?php
/**
 * EJEMPLO DE USO DEL SISTEMA DE AUTENTICACIÓN
 * 
 * Este archivo muestra cómo usar los controladores y middlewares
 * en tu archivo de rutas (api.php o similar)
 */

require_once __DIR__ . '/../controllers/users/AuthController.php';
require_once __DIR__ . '/../controllers/users/UsuarioController.php';
require_once __DIR__ . '/../controllers/users/AdminUsuarioController.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/RoleMiddleware.php';

// ============================================
// RUTAS PÚBLICAS (Sin autenticación)
// ============================================

// POST /auth/login - Login de usuario
// Body: { "username": "usuario", "password": "contraseña" }
// AuthController::login();

// POST /auth/register - Registro de nuevo usuario
// Body: { "username": "usuario", "password": "pass", "email": "email@example.com", "nombres": "Juan", "apellidos": "Pérez" }
// AuthController::register();

// ============================================
// RUTAS PROTEGIDAS (Requieren autenticación)
// ============================================

// GET /usuario/profile - Ver datos del usuario autenticado
// Headers: Authorization: Bearer {token}
/*
AuthMiddleware::handle();
UsuarioController::show();
*/

// GET /usuario/rol - Obtener rol del usuario autenticado
// Headers: Authorization: Bearer {token}
/*
AuthMiddleware::handle();
UsuarioController::getRole();
*/

// PUT /usuario/userdata - Actualizar datos personales
// Headers: Authorization: Bearer {token}
// Body: { "nombres": "Juan", "apellidos": "Pérez", "email": "nuevo@email.com", ... }
/*
AuthMiddleware::handle();
UsuarioController::updateUserData();
*/

// ============================================
// RUTAS DE ADMINISTRADOR (Requieren rol Admin)
// ============================================

// GET /admin/usuarios - Ver todos los usuarios
// Headers: Authorization: Bearer {token}
/*
AuthMiddleware::handle();
RoleMiddleware::handle(['Administrador']);
AdminUsuarioController::index();
*/

// GET /admin/usuarios/{id} - Ver un usuario específico
// Headers: Authorization: Bearer {token}
/*
AuthMiddleware::handle();
RoleMiddleware::handle(['Administrador']);
AdminUsuarioController::show($id);
*/

// PUT /admin/usuarios/{id}/rol - Asignar rol a usuario
// Headers: Authorization: Bearer {token}
// Body: { "idRol": 2 }
/*
AuthMiddleware::handle();
RoleMiddleware::handle(['Administrador']);
AdminUsuarioController::assignRole($id);
*/

// PUT /admin/usuarios/{id}/bloquear - Bloquear/desbloquear usuario
// Headers: Authorization: Bearer {token}
// Body: { "blocked": true }
/*
AuthMiddleware::handle();
RoleMiddleware::handle(['Administrador']);
AdminUsuarioController::blockUser($id);
*/

// ============================================
// EJEMPLO DE FLUJO COMPLETO EN REACT
// ============================================

/*
// 1. Login
const login = async (username, password) => {
  const response = await fetch('/api/auth/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ username, password })
  });
  const data = await response.json();
  if (data.token) {
    localStorage.setItem('token', data.token);
  }
  return data;
};

// 2. Obtener perfil (con token)
const getProfile = async () => {
  const token = localStorage.getItem('token');
  const response = await fetch('/api/usuario/profile', {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });
  return await response.json();
};

// 3. Actualizar datos personales
const updateProfile = async (userData) => {
  const token = localStorage.getItem('token');
  const response = await fetch('/api/usuario/userdata', {
    method: 'PUT',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(userData)
  });
  return await response.json();
};

// 4. Acción de administrador
const getAllUsers = async () => {
  const token = localStorage.getItem('token');
  const response = await fetch('/api/admin/usuarios', {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });
  return await response.json();
};
*/

