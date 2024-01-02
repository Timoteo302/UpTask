<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\LoginController;
use Controllers\DashboardController;
use Controllers\TareaController;
$router = new Router();

// Login
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

// Crear Cuenta
$router->get('/crear', [LoginController::class, 'crear']);
$router->post('/crear', [LoginController::class, 'crear']);

// Fomulario de olvide mi password
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);

// Colocar el nuevo password
$router->get('/restablecer', [LoginController::class, 'restablecer']);
$router->post('/restablecer', [LoginController::class, 'restablecer']);


// Confirmacion de Cuenta
/* estas son 2 rutas
una a la cual enviaremos al usuario una vez haya completado el formulario
otra que va ser el token que vamos a enviar por email
*/
$router->get('/mensaje', [LoginController::class, 'mensaje']);
$router->get('/confirmar', [LoginController::class, 'confirmar']);


// ZONA DE PROYECTOS
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/crear-proyecto', [DashboardController::class, 'crear_proyecto']);
$router->post('/crear-proyecto', [DashboardController::class, 'crear_proyecto']);
$router->get('/proyecto', [DashboardController::class, 'proyecto']);
$router->post('/proyecto', [DashboardController::class, 'proyecto']);
$router->get('/perfil', [DashboardController::class, 'perfil']);
$router->post('/perfil', [DashboardController::class, 'perfil']);
$router->get('/restablecer-contraseña', [DashboardController::class, 'restablecer_contraseña']);
$router->post('/restablecer-contraseña', [DashboardController::class, 'restablecer_contraseña']);
// api eliminar un proyecto
$router->post('/eliminar_proyecto', [DashboardController::class, 'eliminar_proyecto']);

// API PARA LAS TAREAS
$router->get('/api/tareas', [TareaController::class, 'index']); // consultara por todas las tarea de un proyecto
$router->post('/api/tarea', [TareaController::class,'crear']);
$router->post('/api/tarea/actualizar', [TareaController::class,'actualizar']);
$router->post('/api/tarea/eliminar', [TareaController::class,'eliminar']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();