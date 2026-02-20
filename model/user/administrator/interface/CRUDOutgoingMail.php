<?php
    interface CRUDOutgoingMail {
        public function get_outgoing_mail   (int                                              $outgoing_mail_id): OutgoingMail  ;
        public function delete_outgoing_mail(int                                              $outgoing_mail_id):     void      ;
        public function get_outgoing_mails  (                                                                  ):     array     ;
        
        public function create_outgoing_mail
        (string $transmitter, string $receiver, string $number, string $subject, string $transmission_date, 
        string $electronic_mail_name):     void      ;
        
        public function update_outgoing_mail
        (OutgoingMail $outgoing_mail, string $transmitter, string $receiver, string $number, string $subject, string $transmission_date, string $electronic_mail_name):     void      ;
    }
?>