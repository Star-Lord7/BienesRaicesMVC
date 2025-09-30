<?php

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager as Image;

class PropiedadController{
    public static function index(Router $router){

        $propiedades = Propiedad::all(); //Usamos el método estático all de la clase Propiedad para obtener todas las propiedades

        $vendedores = Vendedor::all(); //Llamamos al método estático all de la clase Vendedor

        //Muestra mensaje condicional
        $resultado = $_GET['resultado'] ?? null;

        $router->render('propiedades/admin',[
            'propiedades' => $propiedades,
            'resultado' => $resultado,
            'vendedores' => $vendedores
        ]);
    }

    public static function crear(Router $router){
        $propiedad = new Propiedad; //Creamos una nueva instancia de la clase Propiedad
        $vendedores = Vendedor::all(); //Llamamos al método estático all de la clase Vendedor
        //Arreglo con mensaje de errores
        $errores = Propiedad::getErrores(); //Llamamos al método estático getErrores de la clase Propiedad

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $propiedad = new Propiedad($_POST['propiedad']); //Creamos una nueva instancia de la clase Propiedad

            //Generar nombre unico
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
            
            if($_FILES['propiedad']['tmp_name']['imagen']) {
                //Configuraciones para tratar la imagen
                $manager = new Image(Driver::class); //Creamos una nueva instancia de la clase Image
                $imagen = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(800, 600); //Leemos la imagen temporal y la redimensionamos a 800x600
                $propiedad->setImagen($nombreImagen); //Seteamos la imagen en la propiedad
            }

            $errores = $propiedad->validar(); //Llamamos al método validar de la clase Propiedad y almacenamos los errores en el arreglo $errores

            //Revisar que el arreglo de errores este vacio
            if(empty($errores)) {
                //Subir Archivos
                //Verificamos is existe la carpeta 'imagenes'
                if(!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }

                //Guardar la imagen en el servidor
                $imagen->save(CARPETA_IMAGENES . $nombreImagen); //Guardamos la imagen en la carpeta 'imagenes' con el nombre unico generado

                $propiedad->guardar(); //Llamamos al método guardar de la clase Propiedad
            }
        }

        $router->render('propiedades/crear',[
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router){
        $id = validarORedireccionar('/admin'); //Llamamos a la función validarORedireccionar y le pasamos la URL a redireccionar si no es válido

        $propiedad = Propiedad::find($id); //Llamamos al método estático find de la clase Propiedad

        $vendedores = Vendedor::all(); //Obtenemos todos los vendedores

        $errores = Propiedad::getErrores(); //Llamamos al método estático getErrores de la clase Propiedad

        //Metodo POST para actualizar
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            //Asigar los valores
            $args = $_POST['propiedad'];

            $propiedad->sincronizar($args); //Llamamos al método sincronizar de la clase Propiedad

            //Validación
            $errores = $propiedad->validar(); //Llamamos al método validar de la clase Propiedad y almacenamos los errores en el arreglo $errores

            //Subida de archivos
            //Generar nombre unico
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            if($_FILES['propiedad']['tmp_name']['imagen']) {
                //Configuraciones para tratar la imagen
                $manager = new Image(Driver::class); //Creamos una nueva instancia de la clase Image
                $image = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(800, 600); //Leemos la imagen temporal y la redimensionamos a 800x600
                $propiedad->setImagen($nombreImagen); //Seteamos la imagen en la propiedad
            }

            //Revisar que el arreglo de errores este vacio
            if(empty($errores)) {
                if($_FILES['propiedad']['tmp_name']['imagen']){
                    //Almacenar la imagen
                    $image->save(CARPETA_IMAGENES . $nombreImagen); //Guardamos la imagen en la carpeta de imagenes
                }

                $propiedad->guardar(); //Llamamos al método guardar de la clase Propiedad
            }
        }

        $router->render('propiedades/actualizar',[
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores,
        ]);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if($id) {
                $tipo = $_POST['tipo']; // Obtenemos el tipo de contenido
                //Compara lo que vamos a eliminar
                if(validarTipoContenido($tipo)){
                    $propiedad = Propiedad::find($id); //Obtenemos la propiedad a eliminar
                    $propiedad->eliminar(); //Llamamos al método eliminar de la clase Propiedad
                }
            }
        }
    }
}