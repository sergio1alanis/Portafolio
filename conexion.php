<?php
class conexion{
    private $servidor="localhost";
    private $database= "portafolio";
    private $usuario= "root";
    private $contrasenia= "12345";
    private $port="3307";



    private $conexion;

    public function __construct(){
        try { 
        $this->conexion = new PDO("mysql:host=".$this->servidor.";port=".$this->port.";dbname=".$this->database , $this->usuario, $this->contrasenia);
        $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e){
            return "Falla de conexión".$e;
        }
    }

    public function ejecutar($sql){  //Inser , borrar, actulizar
        $this->conexion->exec($sql);
        return $this->conexion->lastInsertId();
    }

    public function consultar($sql){
        $sentencia = $this->conexion->prepare($sql);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }
}

?>