<?php 
    class Model {
        protected PDO $db_connection;
        public function __construct() {
            $this -> db_connection = Database::connect();
        }
    }
?>