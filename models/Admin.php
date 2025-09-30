<?php

namespace Model;

class Admin extends ActiveRecord{
    //Base de Datos
    protected static $tabla = 'usuarios'; //Nombre de la tabla en la base de datos
    protected static $columnasDB = ['id', 'email', 'password']; //Columnas de la tabla en la base de datos

    public $id;
    public $email;
    public $password;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
    }

    // Validamos que los campos no estén vacíos
    public function validar(){
        if(!$this->email){
            self::$errores[] = "El Email es Obligatorio";
        }
        if(!$this->password){
            self::$errores[] = "El Password es Obligatorio";
        }

        return self::$errores;
    }

    public function existeUsuario(){
        //Revisar si un usuario existe o no
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query);

        if(!$resultado->num_rows){
            self::$errores[] = 'El usuario no existe';
            return;
        }
        return $resultado;
    }

    public function comprobarPassword($resultado){
        $usuario = $resultado->fetch_object();

        $autenticado = password_verify($this->password, $usuario->password);

        if(!$autenticado){
            self::$errores[] = 'El Password es incorrecto';
        }

        return $autenticado;
    }

    public function autenticar(){
        session_start();

        //Llenar el arreglo de la sesión
        $_SESSION['usuario'] = $this->email;
        $_SESSION['login'] = true;

        header('Location: /admin');
    }
}