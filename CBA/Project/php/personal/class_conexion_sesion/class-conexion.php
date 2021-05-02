<?php
define("BASE_URL", "http://localhost/CBA/project/personal/");
 class conexion {
     public function get_conexion(){
         $user = "root";
         $pass = "PHPADMINkevin128*";
         $host = "localhost";
         $db = "cba";
         $conexion = new PDO("mysql:host=$host;dbname=$db;", $user, $pass);
         return $conexion;

     }
 }
?>