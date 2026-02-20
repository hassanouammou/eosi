<?php
    interface CRUDIncomingMail {
        public function get_incoming_mail   (int                                              $incoming_mail_id): IncomingMail  ;
        public function delete_incoming_mail(int                                              $incoming_mail_id):     void      ;
        public function get_incoming_mails  (                                                                  ):     array     ;
        
        public function create_incoming_mail
        (string $transmitter, string $receiver, string $number, string $subject, string $transmission_date, 
        string $electronic_mail_name):     void      ;
        
        public function update_incoming_mail
        (IncomingMail $incoming_mail, string $transmitter, string $receiver, string $number, string $subject, string $transmission_date, string $electronic_mail_name):     void      ;
    }
?>