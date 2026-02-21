<?php
    class UserAuth extends Model {
        protected string   $email;
        protected string   $password;
        public function __construct (string $email, string $password) {
            parent::__construct();
            $this -> email     = $email;      
            $this -> password  = $password;    
        }  

        public function get(string $field) : mixed {
            try {
                $stmt = ($this -> db_connection) -> prepare(
                    "SELECT $field FROM \"UserAuth\" WHERE email = ? AND hashed_password = MD5(?)"
                ); 
                $stmt -> execute([$this -> email, $this -> password]);
                return $stmt -> fetchColumn();
            } catch (PDOException $e) {
                die($e -> getMessage());
            }
        }
        
        public function set(string $field, string | bool $value) : void {
            try {
                $stmt = ($this -> db_connection) -> prepare(
                    "UPDATE \"UserAuth\" SET $field = ? WHERE email = ? AND hashed_password = MD5(?)"
                );
                $stmt -> execute([$value, $this -> email, $this -> password]);
            } catch (PDOException $e) {
                die($e -> getMessage());
            }
        }

        public static function has_an_account(string $email, string $password) : bool {
            $db_connection = Database::connect();
            $stmt = $db_connection -> prepare("SELECT COUNT(*) FROM \"UserAuth\" WHERE email = ? AND hashed_password = MD5(?)"); 
            $stmt -> execute([$email, $password]);
            return $stmt -> fetchColumn() != 0 ? true : false;
        }

        public static function can_reset_password(string $email) : bool {
            $db_connection = Database::connect();
            $stmt = $db_connection -> prepare("SELECT COUNT(*) FROM \"UserAuth\" WHERE email = ?"); 
            $stmt -> execute([$email]);
            return $stmt -> fetchColumn() != 0 ? true : false;
        }

        public static function get_reset_password_link(string $email) : string {
            $datetime = new DateTime("Africa/Casablanca");
            $db_connection = Database::connect();
            $stmt = $db_connection -> prepare(
                "UPDATE \"UserAuth\" SET begin_reset_password = ?, reset_begin_time = ?, reset_end_time = ?
                WHERE email = ?"
            ); 
            $start_datetime = $datetime -> format("Y-m-d H:i:s");
            $end_datetime   = $datetime -> add(new DateInterval('PT30M')) -> format("Y-m-d H:i:s");
            $stmt -> execute(
                [TRUE, $start_datetime, $end_datetime , $email]
            );  
            $stmt = $db_connection -> prepare("SELECT user_id FROM \"UserAuth\" WHERE email = ?"); 
            $stmt -> execute([$email]);
            $user_id = $stmt -> fetchColumn();
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $base_path = getenv('APP_BASE_PATH') ?: '/eosi';
            return "{$scheme}://{$host}{$base_path}/authentification/reset-password/end.php?user_id=$user_id";
        }



    }
?>