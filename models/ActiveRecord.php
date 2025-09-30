<?php

namespace Model;

class ActiveRecord{
    //Base de Datos
    protected static $db; //DEfinimos "static" para que la propiedad pertenezca a la clase y no a la instancia
    protected static $columnasDB = []; //Mapeamos las columnas de la tabla que herederán las clases que extiendan de ActiveRecord
    protected static $tabla = ''; //Definimos la tabla como estática para que pertenezca a la clase y no a la instancia

    //Arreglo para almacenar los errores de validación y lo definimos como estático para que pertenezca a la clase y como protegido para que solo se pueda acceder desde la clase o sus subclases
    protected static $errores = []; 

    //Definimos el método estático para asignar la conexión a la base de datos
    public static function setDB($database){
        self::$db = $database; //Asignamos la conexión a la propiedad estática $db usando "self"
    }

    public function guardar(){
        if(!is_null($this->id)) {
            //Actualizar
            $this->actualizar();
        } else {
            //Creando un nuevo registro
            $this->crear();
        }
    }

    //Método para guardar la propiedad en la base de datos
    public function crear(){

        $atributos = $this->sanitizarDatos(); //Llamamos al método sanitizarDatos para sanitizar los datos

        // Insertar en la Base de Datos
        $query = " INSERT INTO " . static::$tabla . " ( "; //Usamos "static" para acceder a la propiedad estática $tabla de la clase que llama al método
        $query.= join(', ', array_keys($atributos)); //Usamos "join" para crear una cadena con los nombres de las columnas
        $query .= ") VALUES (' ";
        $query .= join("', '", array_values($atributos)); //Usamos "join" para crear una cadena con los valores de las columnas y posicionamos comillas simples para envolver cada valor
        $query .= " ') ";

        $resultado = self::$db->query($query); //Usamos "self" para acceder a la propiedad estática $db

        if($resultado) {
            //Redireccionar al usuario
            header('Location: /admin?resultado=1');
            exit;
        }
    }

    public function actualizar(){
        $atributos = $this->sanitizarDatos(); //Llamamos al método sanitizarDatos para sanitizar los datos

        $valores = []; //Array para almacenar los valores formateados para la consulta SQL
        foreach($atributos as $key => $value){ //Recorremos el arreglo de atributos
            $valores[] = "{$key}='{$value}'"; //Creamos un nuevo arreglo con los valores formateados para la consulta SQL
        }

        $query = " UPDATE " . static::$tabla . " SET "; //Usamos "static" para acceder a la propiedad estática $tabla de la clase que llama al método
        $query .= join(', ', $valores); //Usamos "join" para crear una cadena con los valores formateados
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' "; //Usamos "self" para acceder a la propiedad estática $db y escapamos el valor de id para evitar inyecciones SQL
        $query .= " LIMIT 1 "; //Limitamos la consulta a 1 registro

        $resultado = self::$db->query($query); //Usamos "self" para acceder a la propiedad estática $db

        if($resultado) {
            //Redireccionar al usuario
            header('Location: /admin?resultado=2');
        }
        return $resultado; //Retornamos el resultado de la consulta
    }

    public function eliminar(){
        //Eliminar la propiedad
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1"; //Usamos "self" para acceder a la propiedad estática $db y escapamos el valor de id para evitar inyecciones SQL y usamos "static" para acceder a la propiedad estática $tabla de la clase que llama al método
        $resultado = self::$db->query($query); //Usamos "self" para acceder a la propiedad estática $db

        if($resultado) {
            $this->borrarImagen();
            //Redireccionar al usuario
            header('Location: /admin?resultado=3');
        }

        //return $resultado; //Retornamos el resultado de la consulta
    }

    //Setear la imagen para la propiedad
    public function setImagen($imagen){
        //Elimina la imagen previa
        if(!is_null($this->id)) {
            $this->borrarImagen(); //Llamamos al método borrarImagen para eliminar la imagen previa
        }

        if($imagen) {
            $this->imagen = $imagen;
        }
    }

    //Eliminar el archivo de imagen
    public function borrarImagen(){
        //Comprobar si existe el archivo
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen); //Usamos la constante CARPETA_IMAGENES definida en app.php

        if($existeArchivo) {
            unlink(CARPETA_IMAGENES . $this->imagen); //Eliminamos el archivo
        }
    }

    //Identifica y une los atributos de la BD
    public function atributos(){
        $atributos = [];
        foreach(static::$columnasDB as $columna){ //Usamos "static" para acceder a la propiedad estática $columnasDB
            if($columna === 'id') continue; //Saltamos la columna id

            // Evitar actualizar la fecha de creación si ya existe un id
            if($columna === 'creado' && $this->id) continue;

            $atributos[$columna] = $this->$columna; //Asignamos el valor de la propiedad a la clave del arreglo
        }
        return $atributos; //Retornamos el arreglo de atributos
    }

    public function sanitizarDatos(){
        $atributos = $this->atributos(); //Obtenemos los atributos de la función atributos
        $sanitizado = [];
        // Recorremos el arreglo de atributos y escapamos los valores para evitar inyecciones SQL
        foreach($atributos as $key => $value){
            $sanitizado[$key] = self::$db->escape_string($value ?? ''); //Usamos "self" para acceder a la propiedad estática $db y escapamos los valores con escape_string
        }

        return $sanitizado; //Retornamos el arreglo sanitizado
    }

    //Validación
    public static function getErrores(){
        return static::$errores; //Retornamos el arreglo de errores usando "static"
    }

    public function validar(){
        static::$errores = []; // Reiniciamos el arreglo de errores usando "static"

        return static::$errores; // Devuelve el arreglo de errores usando "static"
    }

    //Lista todas las propiedades
    public static function all(){
        $query = "SELECT * FROM " . static::$tabla; // Consulta para obtener todas las propiedades usando "static" para acceder a la propiedad estática $tabla de la clase que llama al método
        $resultado =self::consultarSQL($query); //Llamamos al método estático consultarSQL para convertir el resultado en un arreglo de objetos
        return $resultado;
    }

    //Obtiene determinado número de registros
    public static function get($cantidad){
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad; // Consulta para obtener un número determinado de propiedades y usamos "static" para acceder a la propiedad estática $tabla de la clase que llama al método
        $resultado = self::consultarSQL($query); //Llamamos al método estático consultarSQL para convertir el resultado en un arreglo de objetos
        return $resultado;
    }

    //Busca una propiedad por su ID
    public static function find($id){
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = $id"; // Consulta para obtener una propiedad por su ID y usamos "static" para acceder a la propiedad estática $tabla de la clase que llama al método
        $resultado = self::consultarSQL($query); //Llamamos al método estático consultarSQL para convertir el resultado en un arreglo de objetos
        return array_shift($resultado); //Usamos array_shift para obtener el primer elemento del arreglo
    }

    public static function consultarSQL($query){
        //Consultar la base de datos
        $resultado = self::$db->query($query); //Usamos "self" para acceder a la propiedad estática $db

        //Iterar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()){ //Usamos fetch_assoc para obtener un arreglo asociativo
            $array[] = static::crearObjeto($registro); //Llamamos al método crearObjeto y almacenamos el resultado en el arreglo
        }

        //Liberar la memoria del servidor
        $resultado->free();

        //Retornar los resultados
        return $array; //Retornamos el arreglo de objetos
    }

    protected static function crearObjeto($registro){
        $objeto = new static; //Creamos una nueva instancia de la clase usando "static"

        foreach($registro as $key => $value){ //Recorremos el arreglo asociativo
            if(property_exists($objeto, $key)){ //Verificamos si la propiedad existe en el objeto usando "property_exists" que toma el objeto y el nombre de la propiedad
                $objeto->$key = $value; //Asignamos el valor a la propiedad del objeto
            }
        }

        return $objeto; //Retornamos el objeto
    }

    //Sincroniza el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar($args = []){
        foreach($args as $key => $value){ //Recorremos el arreglo asociativo
            if(property_exists($this, $key) && !is_null($value)){ //Verificamos si la propiedad existe en el objeto y si el valor no es nulo
                $this->$key = $value; //Asignamos el valor a la propiedad del objeto
            }
        }
    }
}