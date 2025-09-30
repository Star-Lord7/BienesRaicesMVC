<?php

namespace MVC;

class Router{
    public $rutasGET = [];
    public $rutasPOST = [];

    //Método para registrar una ruta GET donde le pasamos la URL y la función a ejecutar
    public function get($url, $fn){
        $this->rutasGET[$url] = $fn; //Asignamos la función a la URL en el array de rutas GET
    }

    //Método para registrar una ruta POST donde le pasamos la URL y la función a ejecutar
    public function post($url, $fn){
        $this->rutasPOST[$url] = $fn; //Asignamos la función a la URL en el array de rutas POST
    }

    public function comprobarRutas(){

        // session_start(); //Iniciamos la sesión para poder usar variables de sesión
        $auth = $_SESSION['login'] ?? null; //Obtenemos el valor de la variable de sesión 'login', si no existe, asignamos null

        //Arreglo de rutas protegidas
        $rutas_protegidas = ['/admin', '/propiedades/crear', '/propiedades/actualizar', '/propiedades/eliminar', 
                            '/vendedores/crear', '/vendedores/actualizar', '/vendedores/eliminar'];

        $urlActual = $_SERVER['PATH_INFO'] ?? '/'; //Obtenemos la URL actual, si no existe, asignamos '/'
        $metodo = $_SERVER['REQUEST_METHOD']; //Obtenemos el método de la petición (GET o POST)

        if($metodo === 'GET'){
            $fn = $this->rutasGET[$urlActual] ?? null; //Si la URL existe en el array de rutas GET, asignamos la función a $fn, si no, asignamos null
        }else{
            $fn = $this->rutasPOST[$urlActual] ?? null; //Si la URL existe en el array de rutas POST, asignamos la función a $fn, si no, asignamos null
        }

        //Verificamos si la ruta actual está protegida
        if(in_array($urlActual, $rutas_protegidas) && !$auth){
            header('Location: /'); //Si la ruta está protegida y el usuario no está autenticado, redirigimos a la página de inicio
        }

        if($fn){
            // "call_user_func" se usa porque no sabemos el nombre de la función que se va a ejecutar
            call_user_func($fn, $this); //Si $fn es una función, la ejecutamos y le pasamos la instancia de Router
        }else{
            echo "Página no encontrada"; //Si no existe la URL, mostramos un mensaje de error
        }
    }

    //Muestra una Vista
    public function render($view, $datos = []){
        foreach($datos as $key => $value){ //Iteramos sobre el array de datos
            $$key = $value; //Creamos una variable con el nombre de la clave usando doble signo ($$) y le asignamos el valor
        }

        ob_start(); //Inicia el almacenamiento en búfer de la salida
        include __DIR__ . "/views/$view.php"; //Incluye la vista pasada como parámetro que será dinámica
        $contenido = ob_get_clean(); //Obtiene el contenido del búfer y lo limpia
        include __DIR__ . "/views/layout.php"; // Incluye el layout
    }
}