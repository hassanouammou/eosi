<?php
    require_once '../../ADMINISTRATOR.php';
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (in_array($_GET['query'], ['fetch-notifications', 'fetch-notifications-length', 'clear'])) {
            if ($_GET['query'] === "fetch-notifications-length") {
                echo count($administrator -> get_notifications());
            } else if ($_GET['query'] === "clear") {
                $administrator -> clear_notifications();
            } else {
                foreach ($administrator -> get_notifications() as $notification) {
                    if (in_array($notification -> topic, ["outgoing mail", "incoming mail"])) {
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
                        } else {
                            if (in_array($notification -> subject, ["create", "delete"])) {
                                if ($notification -> subject === "create") {
                                    $incoming_mail = new IncomingMail(incoming_mail_id: $notification -> topic_id);
                                    echo "
                                        <li>
                                            <i class='fa-regular fa-envelope'></i>
                                            <span class='capitalize'>
                                                Votre Courrier À Arrivée Est Bien Envoyé À La {$incoming_mail -> get('receiver')}.
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