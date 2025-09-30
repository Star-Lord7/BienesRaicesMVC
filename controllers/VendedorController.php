<?php

namespace Controllers;

use MVC\Router;
use Model\Vendedor;

class VendedorController{
    public static function crear(Router $router){

        $errores = Vendedor::getErrores(); //Obtenemos los errores de la clase Vendedor

        $vendedor = new Vendedor; //Creamos una instancia de la clase Vendedor

        //Ejecutar el código despues de que el usuario envía el formulario
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Crear una nueva instancia
            $vendedor = new Vendedor($_POST['vendedor']); //Pasamos los datos del formulario al constructor de la clase Vendedor

            //Validar
            $errores = $vendedor->validar(); //Llamamos al método validar de la clase Vendedor

            //Revisar que el arreglo de errores esté vacío
            if(empty($errores)){
                $vendedor->guardar(); //Llamamos al método guardar de la clase Vendedor
            }
        }

        $router->render('vendedores/crear',[
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
    }

    public static function actualizar(Router $router){

        $errores = Vendedor::getErrores(); //Obtenemos los errores de la clase Vendedor
        $id = validarORedireccionar('/admin'); //Validamos el id de la URL o redireccionamos al admin

        //Obtener datos del vendedor a actualizar
        $vendedor = Vendedor::find($id); //Llamamos al método estático find de la clase Vendedor

        //Ejecutar el código despues de que el usuario envía el formulario
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Asignar los atributos
            $args = $_POST['vendedor']; //Obtenemos los datos del formulario

            //Sincronizar objeto en memoria con lo que el usuario escribió
            $vendedor->sincronizar($args); //Llamamos al método sincronizar de la clase Vendedor

            //Validar
            $errores = $vendedor->validar(); //Llamamos al método validar de la clase Vendedor

            //Revisar que el arreglo de errores esté vacío
            if(empty($errores)){
                $vendedor->guardar(); //Llamamos al método guardar de la clase Vendedor
            }
        }

        $router->render('vendedores/actualizar',[
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Validar id
            $id = $_POST['id']; //Obtenemos el id del vendedor a eliminar
            $id = filter_var($id, FILTER_VALIDATE_INT); //Validamos que el id sea un entero

            if($id){
                $tipo = $_POST['tipo']; //Obtenemos el tipo de vendedor a eliminar

                if(validarTipoContenido($tipo)){ //Validamos que el tipo de contenido sea válido
                    $vendedor = Vendedor::find($id); //Llamamos al método estático find de la clase Vendedor
                    $vendedor->eliminar(); //Llamamos al método eliminar de la clase Vendedor
                }
            }
        }
    }
}