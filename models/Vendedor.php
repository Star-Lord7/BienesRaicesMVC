<?php

namespace Model; //Definimos el namespace

class Vendedor extends ActiveRecord{
    protected static $tabla = 'vendedores'; //Definimos la tabla como estática para que pertenezca a la clase y no a la instancia
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'telefono']; //Mapeamos las columnas de la tabla vendedores

    //Definimos las propiedades de la clase
    public $id;
    public $nombre;
    public $apellido;
    public $telefono;

    //Constructor
    public function __construct($args = []){
        $this->id= $args['id'] ?? null; //Si no existe el valor, se asigna null
        $this->nombre= $args['nombre'] ?? ''; //Si no existe el valor, se asigna una cadena vacía
        $this->apellido= $args['apellido'] ?? '';
        $this->telefono= $args['telefono'] ?? ''; //Si no existe el valor, se asigna una cadena vacía
    }

    //Validar
    public function validar(){
        if(!$this->nombre){
            self::$errores[] = "El nombre es obligatorio";
        }

        if(!$this->apellido){
            self::$errores[] = "El apellido es obligatorio";
        }

        if(!$this->telefono){
            self::$errores[] = "El teléfono es obligatorio";
        }

        if(!preg_match('/[0-9]{10}/', $this->telefono)){
            self::$errores[] = "El formato del teléfono no es válido, debe contener 10 números";
        }

        return self::$errores;
    }
}