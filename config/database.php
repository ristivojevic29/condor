<?php

    class Database{
        private $host = 'localhost';
        private $dbname = 'api_db';
        private $username = 'root';
        private $password = '';
        private $conn;

        public function connect()
        {
            $this->conn = null;

            try{
                $this->conn = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname,$this->username,$this->password);
//                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $exception)
            {
                echo $exception->getMessage();
            }

            return $this->conn;

        }

    }
