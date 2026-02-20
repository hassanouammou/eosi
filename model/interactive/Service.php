<?php 
    class Service extends Model {
        public function __construct(int $service_id) {
            parent::__construct();
            $this -> service_id = $service_id;      
        }

        public function get(string $field) : string {
            try {
                $stmt = ($this -> db_connection) -> prepare("SELECT $field FROM \"Service\" WHERE id = ?"); 
                $stmt -> execute([$this -> service_id]);
                return $stmt -> fetchColumn();
            } catch (PDOException $e) {
                die($e -> getMessage());
            }
        }
        
        public function set(string $field, string $value) : void {
            try {
                $stmt = ($this -> db_connection) -> prepare("UPDATE \"Service\" SET $field = ? WHERE id = ?");
                $stmt -> execute([$value, $this -> service_id]);
            } catch (PDOException $e) {
                die($e -> getMessage());
            }
        }
    }
?>