<?php 
    session_start(); 
    require_once 'C:/XAMPP/htdocs/eosi/division/page/Page.php';
    Page::to_login_if_no_session_was_created($_SESSION['user_id'], $_SESSION['user_role']);
    require_once 'C:/XAMPP/htdocs/eosi/model/Autoloader.php';
    require_once 'C:/XAMPP/htdocs/eosi/functions.php';
    $autoloader = new Autoloader(for: "division");
    $autoloader -> run(); 
    $division = new Division($_SESSION['user_id']);
    $CONSTANT = function($name) {
        return constant(name: $name);
    };
    // READ CONSTANTS
    define('OUTGOING_MAILS_READ_STORAGE_LINK'  , '/eosi/upload/electronic-mail/outgoing-mail');
    define('INCOMING_MAILS_READ_STORAGE_LINK'  , '/eosi/upload/electronic-mail/incoming-mail');
    define('PROFILE_PICTURE_READ_STORAGE_LINK' , '/eosi/upload/photo/administrator');
    define('EMPLOYEE_PICTURE_READ_STORAGE_LINK', '/eosi/upload/photo/employee');

    // UPLOAD CONSTANTS
    define('PROFILE_PICTURE_UPLOAD_STORAGE_LINK' , 'C:/XAMPP/htdocs/eosi/upload/photo/administrator');
    define('OUTGOING_MAILS_UPLOAD_STORAGE_LINK'  , 'C:/XAMPP/htdocs/eosi/upload/electronic-mail/outgoing-mail');
    define('INCOMING_MAILS_UPLOAD_STORAGE_LINK'  , 'C:/XAMPP/htdocs/eosi/upload/electronic-mail/incoming-mail');
    define('EMPLOYEE_PICTURE_UPLOAD_STORAGE_LINK', 'C:/XAMPP/htdocs/eosi/upload/photo/employee');
?>