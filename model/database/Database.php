<?php 
    class Database {
        private static  PDO      $db_connection; 
        
        public static function connect() : PDO {
            $dbHost    = getenv('DB_HOST') ?: getenv('PGHOST') ?: 'localhost';
            $dbPort    = getenv('DB_PORT') ?: getenv('PGPORT') ?: '5432';
            $dbName    = getenv('DB_NAME') ?: getenv('PGDATABASE') ?: 'ProvinceDB';
            $dbUser    = getenv('DB_USER') ?: getenv('PGUSER') ?: 'postgres';
            $dbPass    = getenv('DB_PASSWORD') ?: getenv('PGPASSWORD') ?: 'postgres';
            $dbSslMode = getenv('DB_SSLMODE') ?: getenv('PGSSLMODE') ?: '';

            $dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName}";
            if (!empty($dbSslMode)) {
                $dsn .= ";sslmode={$dbSslMode}";
            }

            try {
                self::$db_connection = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
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