<?php 
    class Employee extends User {
        public function __construct(int $user_id) {
            parent::__construct($user_id);
        }
        
        public function get(string $field) : string {
            if (in_array($field, $this -> user_columns_name)) {
                return parent::get($field);
            } else {
                $stmt = ($this->db_connection) -> prepare("SELECT $field FROM \"Employee\" WHERE user_id = ?"); 
                $stmt -> execute([parent::get("id")]);
                return $stmt -> fetchColumn();
            }
        }
        
        public function set(string $field, string $value) : void {
            if (in_array($field, $this -> user_columns_name)) {
                parent::set($field, $value);
            } else {
                $stmt = ($this -> db_connection) -> prepare("UPDATE \"Employee\" SET $field = ? WHERE user_id = ?");
                $stmt -> execute([$value, parent::get("id")]);
            }
        }
    }
?>