<?php
    class Database{
        private $hostname = 'localhost';
        private $database ="tienda_online";
        private $username = 'root';
        private $password = "";
        private $charset = "utf8";

        // Función para conectar a la base de datos
        function conectar(){
            try{
                $conexion = "mysql:host=" .$this->hostname . "; dbname=" . $this->database . "; charset=" . $this->charset;
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES=> false
                ];
    
                $pdo = new PDO($conexion, $this->username, $this->password, $options);
                return $pdo;

            //Mensaje para conocer el error en la conexión
            }catch(PDOException $e){
                echo "Error conexión: " .$e->getMessage();
                exit;
            }
        }
   }
?>