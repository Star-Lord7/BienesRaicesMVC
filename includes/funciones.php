<?php

define('TEMPLATES_URL', __DIR__ . '/templates'); //Superglobal para la ruta de las plantillas
define('FUNCIONES_URL', __DIR__ . '/funciones.php'); //Superglobal para la ruta de las funciones
define('CARPETA_IMAGENES', $_SERVER['DOCUMENT_ROOT'] . '/imagenes/'); //Superglobal para la ruta de las imágenes

// Función para incluir templates
function incluirTemplate($nombre, $inicio = false) {
    include TEMPLATES_URL . '/' . $nombre . '.php';
}

// Función para autenticar usuarios y proteger rutas
function estaAutenticado() {
    session_start();

    if(!$_SESSION['login']){ //Si el usuario no está autenticado lo redirigimos a la página principal   
        header('Location: /');
    }
}

//Función para probar 
function debuguear($variable) {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

//Escapa (sanitizar) el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//Validar tipo de contenido
function validarTipoContenido($tipo) {
    $tipos = ['vendedor', 'propiedad'];

    return in_array($tipo, $tipos); //Devuelve true si el tipo está en el arreglo, false si no
}

//Muestra los mensajes
function mostrarNotificacion($codigo) {
    $mensaje = '';

    switch($codigo) {
        case 1:
            $mensaje = 'Creado Correctamente';
            break;
        case 2:
            $mensaje = 'Actualizado Correctamente';
            break;
        case 3:
            $mensaje = 'Eliminado Correctamente';
            break;
        default:
            $mensaje = false;
            break;
    }

    return $mensaje;
}

function validarORedireccionar(string $url) {
    //Validar que el ID sea un número entero
    $id=$_GET['id']; // Obtenemos el ID de la propiedad a actualizar
    $id = filter_var($id, FILTER_VALIDATE_INT); // Validamos el ID

    // Redirigir si el ID no es válido
    if(!$id) {
        header("Location: $url");
    }

    return $id;
}