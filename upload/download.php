<?php 
    function download($filepath, $filename) {
        if (file_exists($filepath)) {
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachement; filename=$filename");
            header("Content-Type: application/zip");
            header("Content-Transfer-Encoding: binary");
            readfile($filepath);
            exit;
        } else {
            die('Aucun Courrier Éléctronique Associé avec Ce Courrier');
        } 
    }

    if (isset($_GET['incoming_mail_name'])) {
        download(
            "../upload/electronic-mail/incoming-mail/{$_GET['incoming_mail_name']}", $_GET['incoming_mail_name']
        );
    } elseif (isset($_GET['outgoing_mail_name'])) {
        download(
            "../upload/electronic-mail/outgoing-mail/{$_GET['outgoing_mail_name']}", $_GET['outgoing_mail_name']
        );
    } else {
        die(
            "Vous N'avez Pas Le Droit d'accéder à cette Page !!"
        );
    }
?>
