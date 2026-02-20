<?php
    class User extends Model {
        protected int   $user_id;
        protected array $user_columns_name = array();

        public function __construct (int $user_id) {
            parent::__construct();
            $this -> user_id = $user_id;      
            $columns = ($this -> db_connection) -> query (
                "SELECT attname AS name FROM pg_attribute WHERE attrelid = 'public.\"User\"'::regclass AND attnum > 0"
            );
            while ($column = $columns -> fetch(PDO::FETCH_OBJ)) {
                if ($column -> name !== "id") {
                    array_push($this -> user_columns_name, $column -> name);
                }
            }
        }  

        public function get(string $field) : string {
            try {
                $stmt = ($this -> db_connection) -> prepare("SELECT $field FROM \"User\" WHERE id = ?"); 
                $stmt -> execute([$this -> user_id]);
                return $stmt -> fetchColumn();
            } catch (PDOException $e) {
                die($e -> getMessage());
            }
        }
        
        public function set(string $field, string $value) : void {
            try {
                $stmt = ($this -> db_connection) -> prepare("UPDATE \"User\" SET $field = ? WHERE id = ?");
                $stmt -> execute([$value, $this -> user_id]);
            } catch (PDOException $e) {
                die($e -> getMessage());
            }
        }
    }
?>