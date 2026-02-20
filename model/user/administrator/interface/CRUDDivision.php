<?php
    interface CRUDDivision {
        public function delete_division(int $user_id):   void   ;
        public function get_division   (int $user_id): Division ;
        public function get_divisions  (            ):   array  ;
        public function create_division(
            string $name,     string $email, 
            string $password, string $phone_number
        ) : void ;
        public function update_division(
            Division $division, string $name, 
            string   $email,    string $password, 
            string $phone_number
        ) : void ;
    }
?>