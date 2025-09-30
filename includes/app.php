<?php

//"App" funcionara como el archivo principal que mandara a llamar otros archivos
require 'funciones.php';
require 'config/database.php';
require __DIR__ . '/../vendor/autoload.php';

//Conexion a la base de datos
$db = conectarDB();

use Model\ActiveRecord; //Importamos la clase ActiveRecord

ActiveRecord::setDB($db); //Llamamos al método estático setDB de la clase ActiveRecord y le pasamos la conexión a la base de datos