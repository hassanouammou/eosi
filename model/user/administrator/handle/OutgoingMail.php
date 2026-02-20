<?php 
    class OutgoingMail extends Model {
        private int $outgoing_mail_id;
        
        public function __construct (int $outgoing_mail_id) {
            parent::__construct();
            $this -> outgoing_mail_id = $outgoing_mail_id;      
        }

        public function get(string $field) : string {
            $stmt = ($this -> db_connection) -> prepare("SELECT $field FROM \"OutgoingMail\" WHERE id = ?"); 
            $stmt -> execute([$this -> outgoing_mail_id]);
            return $stmt -> fetchColumn();
        }
        
        public function set(string $field, string $value) : void {
            $stmt = ($this -> db_connection) -> prepare("UPDATE \"OutgoingMail\" SET $field = ? WHERE id = ?");
            $stmt -> execute([$value, $this -> outgoing_mail_id]);
        }
    }
?>