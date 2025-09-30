<?php

namespace Controllers;
use MVC\Router;
use Model\Admin;

class LoginController{
    public static function login(Router $router){

        $errores = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $auth = new Admin($_POST); //Creamos una instancia de la clase Admin y le pasamos los datos del formulario
            $errores = $auth->validar(); // Llamamos al método validar para validar los datos del formulario

            if(empty($errores)){
                //verificar si el usuario existe
                $resultado = $auth->existeUsuario();

                if(!$resultado){
                    //Verificar si el usuario existe o no (mensajde de error)
                    $errores = Admin::getErrores();
                }else{
                    //verificar si el password es correcto
                    $autenticado = $auth->comprobarPassword($resultado);

                    if($autenticado){
                        //Autenticar el usuario
                        $auth->autenticar();
                    }else{
                        //Password incorrecto (mensaje de error)
                        $errores = Admin::getErrores();
                    }

                    //Autenticar el usuario
                }
            }
        }

        $router->render('auth/login', [
            'errores' => $errores
        ]);
    }

    public static function logout(){
        session_start();

        $_SESSION = []; //Vaciamos el arreglo de la sesión

        header('Location: /'); //Redireccionamos al usuario a la página de inicio
    }
}