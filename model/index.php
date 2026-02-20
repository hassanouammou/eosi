<?php
    require_once 'Autoloader.php';
    $autoloader = new Autoloader(for: 'administrator');
    $autoloader -> run();

    $admin = new Administrator(1);
    $incoming_mails = $admin -> get_incoming_mails();
    




    
   

?>  