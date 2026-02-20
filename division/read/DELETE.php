<?php 
    require_once '../page/Page.php';
    require_once '../../model/Autoloader.php';
    $autoloader = new Autoloader(for: "division");
    $autoloader -> run();
    session_start(); 
    Page::to_login_if_no_session_was_created($_SESSION['user_id'], $_SESSION['user_role']);
    $division = new Division($_SESSION['user_id']);

    if (!empty($_GET)) {
        $target = $_GET['target'];
        array_shift($_GET);
        if ($target == "outgoing_mail") {
            foreach ($_GET as $outgoing_mail_id) {
                $division -> delete_outgoing_mail(outgoing_mail_id: $outgoing_mail_id);
            }
            $next_location = "outgoing-mails.php";
        } else if ($target == "incoming_mail") {
            foreach ($_GET as $incoming_mail_id) {
                $division -> delete_incoming_mail(incoming_mail_id: $incoming_mail_id);
            }
            $next_location = "incoming-mails.php";
        } elseif ($target == "division") {
            foreach ($_GET as $user_id) {
                $division -> delete_division(user_id: $user_id);
            }
            $next_location = "divisions.php";
        } elseif ($target == "employee") {
            foreach ($_GET as $user_id) {
                $division -> delete_employee(user_id: $user_id);
            }
            $next_location = "employees.php";
        } else {
            die(
                "Unkown Target"
            );
        }
        header(
            "Location: $next_location"
        );
    } else {
        die("Vous N'avez Pas Le Droit d'accéder à cette Page !!");
    }
?>
