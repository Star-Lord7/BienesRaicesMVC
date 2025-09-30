<?php

namespace Model; //Definimos el namespace

class Propiedad extends ActiveRecord{
    protected static $tabla = 'propiedades'; //Definimos la tabla como estática para que pertenezca a la clase y no a la instancia
    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedores_id']; //Mapeamos las columnas de la tabla propiedades

    //Definimos las propiedades de la clase
    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedores_id;

    //Constructor
    public function __construct($args = []){
        $this->id= $args['id'] ?? null; //Si no existe el valor, se asigna null
        $this->titulo= $args['titulo'] ?? ''; //Si no existe el valor, se asigna una cadena vacía
        $this->precio= $args['precio'] ?? '';
        $this->imagen= $args['imagen'] ?? ''; //Si no existe el valor, se asigna una cadena vacía
        $this->descripcion= $args['descripcion'] ?? '';
        $this->habitaciones= $args['habitaciones'] ?? '';
        $this->wc= $args['wc'] ?? '';
        $this->estacionamiento= $args['estacionamiento'] ?? '';
        $this->creado= date('Y/m/d'); //Asignamos la fecha actual
        $this->vendedores_id= $args['vendedorId'] ?? '';
    }

    public function validar(){
        if(!$this->titulo){
            self::$errores[] = "El título es obligatorio";
        }

        if(!$this->precio){
            self::$errores[] = "El precio es obligatorio";
        }

        if(strlen($this->descripcion) < 50){
            self::$errores[] = "La descripción es obligatoria y debe tener al menos 50 caracteres";
        }

        if(!$this->habitaciones){
            self::$errores[] = "El número de habitaciones es obligatorio";
        }

        if(!$this->wc){
            self::$errores[] = "El número de baños es obligatorio";
        }

        if(!$this->estacionamiento){
            self::$errores[] = "El número de plazas de estacionamiento es obligatorio";
        }

        if(!$this->vendedores_id){
            self::$errores[] = "Elige un vendedor";
        }

        if(!$this->imagen) {
            self::$errores[] = "La imagen es obligatoria";
        }

        return self::$errores; // Devuelve el arreglo de errores
    }
}