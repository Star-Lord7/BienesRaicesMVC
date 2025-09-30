<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/app.php'; //Requerimos el archivo app.php que contiene las funciones y la conexión a la base de datos

use Controllers\LoginController;
use MVC\Router; //Importamos la clase Router
use Controllers\PropiedadController; //Importamos el controlador PropiedadController
use Controllers\VendedorController; //Importamos el controlador VendedorController
use Controllers\PaginasController; //Importamos el controlador PaginasController

$router = new Router(); //Creamos una instancia de la clase Router

/* ZONA PRIVADA */
//Definimos las rutas para las propiedades
$router->get('/admin', [PropiedadController::class, 'index']); //Usamos la sintaxis de array para llamar al método estático "index" de la clase PropiedadController
$router->get('/propiedades/crear', [PropiedadController::class, 'crear']);
$router->post('/propiedades/crear', [PropiedadController::class, 'crear']); 
$router->get('/propiedades/actualizar', [PropiedadController::class, 'actualizar']);
$router->post('/propiedades/actualizar', [PropiedadController::class, 'actualizar']);
$router->post('/propiedades/eliminar', [PropiedadController::class, 'eliminar']);

//Definimos las rutas para los vendedores
$router->get('/vendedores/crear', [VendedorController::class, 'crear']);
$router->post('/vendedores/crear', [VendedorController::class, 'crear']);
$router->get('/vendedores/actualizar', [VendedorController::class, 'actualizar']);
$router->post('/vendedores/actualizar', [VendedorController::class, 'actualizar']);
$router->post('/vendedores/eliminar', [VendedorController::class, 'eliminar']);

/* ZONA PUBLICA */
$router->get('/', [PaginasController::class, 'index']);
$router->get('/nosotros', [PaginasController::class, 'nosotros']);
$router->get('/propiedades', [PaginasController::class, 'propiedades']);
$router->get('/propiedad', [PaginasController::class, 'propiedad']);
$router->get('/blog', [PaginasController::class, 'blog']);
$router->get('/entrada', [PaginasController::class, 'entrada']);
$router->get('/contacto', [PaginasController::class, 'contacto']);
$router->post('/contacto', [PaginasController::class, 'contacto']);

/* LOGIN Y AUTENTICACION */
$router->get('/login', [LoginController::class, 'login']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

$router->comprobarRutas();