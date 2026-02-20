<?php
    require_once '../../DIVISION.php';
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (in_array($_GET['query'], ['fetch-notifications', 'fetch-notifications-length', 'clear'])) {
            if ($_GET['query'] === "fetch-notifications-length") {
                echo count($division -> get_notifications());
            } else if ($_GET['query'] === "clear") {
                $division -> clear_notifications();
            } else {
                foreach ($division -> get_notifications() as $notification) {
                    if (in_array($notification -> topic, ["outgoing mail", "incoming mail", "employee"])) {
                        if ($notification -> topic === "outgoing mail") {
                            if (in_array($notification -> subject, ["create", "delete"])) {
                                $outgoing_mail = new OutgoingMail(outgoing_mail_id: $notification -> topic_id);
                                if ($notification -> subject === "create") {
                                    echo "
                                        <li>
                                            <i class='fa-regular fa-envelope'></i>
                                            <span class='capitalize'>Votre Courrier De Départ Est Bien Envoyé.</span>
                                        </li>
                                    ";
                                }
                            }
                        } elseif ($notification -> topic === "incoming mail") {
                            if (in_array($notification -> subject, ["create", "delete"])) {
                                if ($notification -> subject === "create") {
                                    $incoming_mail = new IncomingMail(incoming_mail_id: $notification -> topic_id);
                                    $notification_link = 
                                    "/eosi/division/read/incoming-mails.php?id={$incoming_mail -> get('id')}";
                                    echo "
                                        <li>
                                            <i class='fa-regular fa-envelope'></i>
                                            <span class='capitalize'>
                                                Votre Avez Reçu Un Nouveau Courrier À Arrivée,
                                                <a href='{$notification_link}'>Voir Les Détailles</a>
                                            </span>
                                        </li>
                                    ";
                                }
                            }
                        } else {
                            if (in_array($notification -> subject, ["create", "delete"])) {
                                if ($notification -> subject === "create") {
                                    $employee = new Employee(user_id: $notification -> topic_id);
                                    $notification_link = 
                                    "/eosi/division/read/employees.php?user_id={$employee -> get('user_id')}";
                                    echo "
                                        <li>
                                            <i class='fa-regular fa-envelope'></i>
                                            <span class='capitalize'>
                                                Un Nouveau Employé Rejoint Cette Division, 
                                                <a href='{$notification_link}'>Voir Les Détailles</a>
                                            </span>
                                        </li>
                                    ";
                                }
                            }
                        }
                    }
                }
            }
        } else {
            echo "unknown request";
        }
    } else {
        die("Vous n'avez Pas le droit d'accéder à cette Page !!");
    }
?>