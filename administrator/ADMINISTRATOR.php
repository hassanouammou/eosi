<?php 
    session_start(); 
    require_once __DIR__ . '/page/Page.php';
    Page::to_login_if_no_session_was_created($_SESSION['user_id'], $_SESSION['user_role']);
    require_once dirname(__DIR__) . '/model/Autoloader.php';
    require_once dirname(__DIR__) . '/functions.php';
    $autoloader = new Autoloader(for: "administrator");
    $autoloader -> run(); 
    $administrator = new Administrator($_SESSION['user_id']);
    $CONSTANT = function($name) {
        return constant(name: $name);
    };
    // READ CONSTANTS
    define('OUTGOING_MAILS_READ_STORAGE_LINK'  , '/eosi/upload/electronic-mail/outgoing-mail');
    define('INCOMING_MAILS_READ_STORAGE_LINK'  , '/eosi/upload/electronic-mail/incoming-mail');
    define('PROFILE_PICTURE_READ_STORAGE_LINK' , '/eosi/upload/photo/administrator');
    define('EMPLOYEE_PICTURE_READ_STORAGE_LINK', '/eosi/upload/photo/employee');

    // UPLOAD CONSTANTS
    define('PROFILE_PICTURE_UPLOAD_STORAGE_LINK' , dirname(__DIR__) . '/upload/photo/administrator');
    define('OUTGOING_MAILS_UPLOAD_STORAGE_LINK'  , dirname(__DIR__) . '/upload/electronic-mail/outgoing-mail');
    define('INCOMING_MAILS_UPLOAD_STORAGE_LINK'  , dirname(__DIR__) . '/upload/electronic-mail/incoming-mail');
    define('EMPLOYEE_PICTURE_UPLOAD_STORAGE_LINK', dirname(__DIR__) . '/upload/photo/employee');
    
?>