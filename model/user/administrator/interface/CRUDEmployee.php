<?php
    interface CRUDEmployee {
        public function get_employee   (int                           $user_id):   Employee   ;
        public function delete_employee(int                           $user_id):    void      ;
        public function get_employees  (                                      ):    array     ;
        
        public function create_employee(
            string   $firstname,  string   $lastname,    string   $birth_date, 
            string   $gender,     string   $photo_name,  string   $division_id,
            string   $email,      string   $password,    string   $phone_number
        ) : void ;
        public function update_employee(
            Employee $employee,    string   $firstname,  string   $lastname, 
            string   $birth_date,  string   $gender,     string   $photo_name, 
            string   $division_id, string   $email,      string   $password,   
            string   $phone_number
        ) : void ;
    } 
?>