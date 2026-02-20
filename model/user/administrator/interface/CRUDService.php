<?php
    interface CRUDService {
        public function get_service    (int $service_id):  Service ;
        public function get_services   (               ):   array  ;
    }
?>