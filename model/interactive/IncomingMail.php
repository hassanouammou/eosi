<?php 
    class IncomingMail extends Model {
        private int $incoming_mail_id;
        public function __construct (int $incoming_mail_id) {
            parent::__construct();
            $this -> incoming_mail_id = $incoming_mail_id;      
        }

        public function get(string $field) : string {
            try {
                $stmt = ($this -> db_connection) -> prepare("SELECT $field FROM \"IncomingMail\" WHERE id = ?"); 
                $stmt -> execute([$this -> incoming_mail_id]);
                return $stmt -> fetchColumn();
            } catch (PDOException $e) {
                die($e -> getMessage());
            }
        }
        
        public function set(string $field, string $value) : void {
            try {
                $stmt = ($this -> db_connection) -> prepare("UPDATE \"IncomingMail\" SET $field = ? WHERE id = ?");
                $stmt -> execute([$value, $this -> incoming_mail_id]);
            } catch (PDOException $e) {
                die($e -> getMessage());
            }
        }
    }
?>