<?php 
    class Database {
        private static  PDO      $db_connection; 
        private const   DSN      = "pgsql:host=localhost;dbname=ProvinceDB;";
        private const   USER     = "postgres";
        private const   PASSWORD = "postgres";
        
        public static function connect() : PDO {
            try {
                self::$db_connection = new PDO(self::DSN, self::USER, self::PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            } catch (PDOException $e) {
                die($e -> getMessage());
            }
            return self::$db_connection;
        }

        public static function close(PDO &$db_connection) : void {
            $db_connection = null;
        }
    }
?>