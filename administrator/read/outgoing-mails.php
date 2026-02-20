<?php 
    require_once '../ADMINISTRATOR.php';
    $userpath = <<<HTML
        <li><a>Courriers</a></li>
        <li><a href="outgoing-mails.php">Courriers de Départ</a></li>
    HTML;
?>
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
        $db_connection = Database::connect();
        $table_heading = "Les Courriers de Départ Trouvés";

        $real_id                = $_GET['id']                ?? null;
        $real_year              = $_GET['year']              ?? null;
        $real_receiver          = $_GET['receiver']          ?? null;
        $real_transmitter       = $_GET['transmitter']          ?? null;
        $real_number            = $_GET['number']            ?? null;
        $real_subject           = $_GET['subject']           ?? null;
        $real_transmission_date = $_GET['transmission_date'] ?? null;

        $id                     = isset($_GET['id'])                ? $db_connection -> quote(trim(                     $_GET['id'])) :null;
        $year                   = isset($_GET['year'])              ? $db_connection -> quote(trim(                   $_GET['year'])) :null;
        $receiver               = isset($_GET['receiver'])          ? $db_connection -> quote('%'.trim(       $_GET['receiver']).'%') :null;
        $transmitter            = isset($_GET['transmitter'])       ? $db_connection -> quote('%'.trim(    $_GET['transmitter']).'%') :null;
        $number                 = isset($_GET['number'])            ? $db_connection -> quote('%'.trim(         $_GET['number']).'%') :null;
        $subject                = isset($_GET['subject'])           ? $db_connection -> quote('%'.trim(        $_GET['subject']).'%') :null;
        $transmission_date      = isset($_GET['transmission_date']) ? $db_connection -> quote(trim(      $_GET['transmission_date'])) :null;
        
        $id_cond                = isset(               $id) ? "id                                   = $id                    " :null;
        $transmission_date_cond = isset($transmission_date) ? "transmission_date                    = $transmission_date     " :null;
        $year_cond              = isset(             $year) ? "EXTRACT(YEAR FROM transmission_date) = $year                  " :null;
        $number_cond            = isset(           $number) ? "TRIM(LOWER(number))                  LIKE LOWER($number)      " :null;
        $receiver_cond          = isset(         $receiver) ? "TRIM(LOWER(receiver))                LIKE LOWER($receiver)    " :null;
        $transmitter_cond       = isset(      $transmitter) ? "TRIM(LOWER(transmitter))             LIKE LOWER($transmitter) " :null;
        $subject_cond           = isset(          $subject) ? "TRIM(LOWER(subject))                 LIKE LOWER($subject)     " :null;
        
        $conditions = array_kill_null_values(
            [$id_cond, $transmitter_cond, $receiver_cond, $number_cond, $subject_cond, $transmission_date_cond, $year_cond]
        );
        $stmt = put_conditions_inside_sql_select("\"OutgoingMail\"", $conditions);
        $outgoing_mails = array();
        $stmt = $db_connection -> query($stmt);
        while ($outgoing_mail = $stmt -> fetch(PDO::FETCH_OBJ)) {
            array_push($outgoing_mails, new OutgoingMail(outgoing_mail_id: $outgoing_mail -> id));
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete_outgoing_mail'])) {
            $location =  put_values_in_url_for_get_request(
                baseurl: 'DELETE.php?target=outgoing_mail&', argument_name: "outgoing_mail_id", values: $_POST['checked_outgoing_mails']
            );
            header("Location: $location");
        } elseif (isset($_POST['update_outgoing_mail'])) {
            $location = "../update/outgoing-mail.php?outgoing_mail_id={$_POST['checked_outgoing_mails'][0]}";
            header("Location: $location");
        } else {
            $location = from_post_make_its_arguments_in_url(baseurl: "outgoing-mails.php");
            header("Location: $location");
        }
    } else {
        $outgoing_mails = $administrator -> get_outgoing_mails();
        $table_heading = "Les Courriers de Départ";
    }
?> 

<!DOCTYPE html>
<html lang="fr">
<head>
    <?php echo Page::get_head() ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <link rel="stylesheet" href="../asset/style/read/outgoing-mails.css">
    <title>Courriers de Départ | Bureau d'Ordre</title>
</head>
<body>
    <div id="container" class="container">
        <header id="header"><?php echo Page::get_header($userpath) ?></header>
        <aside id="aside"><?php echo Page::get_aside() ?></aside>

        <main id="main">
            <form method="post" class="form" id="outgoing-mail-form">
                <div class="smta">
                    <select name="year" id="year-option">
                        <?php 
                            if (isset($_GET['year']) && $_GET['year']) {
                                $years = array_reverse(range(2020, 2024));
                                echo "<option value=''>Tous Les Années</option>";
                                for ($i = 0; $i < count($years); $i++) { 
                                    echo  ($_GET['year'] == $years[$i]) 
                                    ? "<option value='{$years[$i]}' selected>{$years[$i]}</option>" 
                                    : "<option value='{$years[$i]}'>{$years[$i]}</option>";
                                }
                            } else {
                                $years = array_reverse(range(2020, 2024));
                                echo "<option value='' selected disabled>Tous Les Années</option>";
                                for ($i = 0; $i < count($years); $i++) { 
                                    echo  "<option value='{$years[$i]}'>{$years[$i]}</option>";
                                }
                            }
                        ?>
                    </select>
                    <div id="non-year-options">
                        <button id="search-button" type="button" title="Rechercher">Rechercher</button>
                        <a title="Ajouter Un Nouveau Courrier" href="../create/outgoing-mail.php">Ajouter</a>
                        <button id="update-button" type="submit" name="update_outgoing_mail">Modifier</button>
                        <button id="delete-button" type="button">Supprimer</button>
                    </div>
                </div>
                <div class="table">
                    <span class="heading"><?php echo $table_heading ?></span>
                    <div id="om-search-form">
                        <input placeholder="Émetteur" type="text" name="transmitter" value="<?= $real_receiver ?? '' ?>">
                        <input placeholder="Destinataire" type="text" name="receiver" value="<?= $real_transmitter ?? '' ?>">
                        <input placeholder="Numéro de Courrier" type="text" name="number" value="<?= $real_number ?? '' ?>">
                        <input placeholder="Objet" type="text" name="subject" value="<?= $real_subject ?? '' ?>">
                        <input placeholder="Date" type="text" name="transmission_date" value="<?= $real_transmission_date ?? '' ?>">
                        <button id="search-reset-om" type="reset">Réinitialiser</button>
                        <button type="submit" name="search_outgoing_mails">Rechecher</button>
                    </div>
                    <table id="myTable" class="cell-border">
                        <thead>
                            <tr>
                                <th id="identifiant" class="dt-head-center">#</th>
                                <th id="émetteur" class="dt-head-center">Émetteur</th>
                                <th id="destinataire" class="dt-head-center">Destinataire</th>
                                <th id="numéro-de-courrier" class="dt-head-center">Numéro de Courrier</th>
                                <th id="objet" class="dt-head-center">Objet</th>
                                <th id="date" class="dt-head-center">Date</th>
                                <th id="courrier-éléctronique" class="dt-head-center">Courrier Éléctronique</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if (!(count($outgoing_mails) === 0)) {
                                    foreach ($outgoing_mails as $outgoing_mail) {
                                        $electronic_mail_name = $outgoing_mail -> get('electronic_mail_name');
                                        echo "
                                            <tr>
                                                <td>
                                                    <input class='checked_outgoing_mails' type='checkbox' 
                                                    name='checked_outgoing_mails[]' value='{$outgoing_mail -> get('id')}'/>
                                                </td>
                                                <td class='transmitter'>{$outgoing_mail->get('transmitter')}</td>
                                                <td class='receiver'>{$outgoing_mail->get('receiver')}</td>
                                                <td class='number'>{$outgoing_mail->get('number')}</td>
                                                <td class='subject'>{$outgoing_mail->get('subject')}</td>
                                                <td class='transmission_date'>{$outgoing_mail->get('transmission_date')}</td>
                                                <td>
                                                    <div class='options'>
                                                        <a href='../../upload/electronic-mail/outgoing-mail/{$electronic_mail_name}' target='_blank'>Consulter</a><hr>
                                                        <a href='../../upload/download.php?outgoing_mail_name={$electronic_mail_name}'>Télécharger</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        ";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div id="delete-pop-up" class="attention--pop-up">
                    <strong><i class="fa-solid fa-circle-exclamation"></i>Attention</strong>
                    <p>Voulez Vous Vraiment Supprimer Ces courriers ?!</p>
                    <div class="dialog-decisions">
                        <button type="submit" name="delete_outgoing_mail">Oui</button>
                        <button type="button" class="closePopUp">Non</button>
                    </div>
                </div>      
            </form>
        </main>
    </div>
    <script src="../asset/javascript/read/outgoing-mails.js"></script>
</body>
</html>
