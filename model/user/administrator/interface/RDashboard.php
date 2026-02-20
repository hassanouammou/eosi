<?php 
    interface RDashboard {
        public function get_years(                            ) : array ;
        public function get_number_of_outgoing_mails_per_year() : array ;
        public function get_number_of_incoming_mails_per_year() : array ;
        public function get_number_of_divisions(              ) :  int  ;
        public function get_number_of_employees(              ) :  int  ;
    }
?>