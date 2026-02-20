<?php 
    require_once '../ADMINISTRATOR.php';
    $userpath = <<<HTML
        <li><a>Courriers</a></li>
        <li><a href="../read/incoming-mails.php">Courriers à Arrivée</a></li>
        <li><a href="incoming-mail.php">Ajoutation</a></li>
    HTML;
    if (isset($_POST['create_incoming_mail'])) {
        $electronic_mail      = $_FILES['electronic_mail'];
        $electronic_mail_name = $electronic_mail['name'];
        $secure_base_name = str_replace(
            search : pathinfo($electronic_mail_name, PATHINFO_BASENAME), 
            replace: "incoming-mail-".trim($_POST['number']), 
            subject: $electronic_mail_name
        );
        $extension = pathinfo($electronic_mail_name, PATHINFO_EXTENSION);
        $secure_name = "$secure_base_name.$extension";
        $secure_link = "{$CONSTANT('INCOMING_MAILS_UPLOAD_STORAGE_LINK')}/$secure_name";
        $incoming_mails_link  = "{$CONSTANT('INCOMING_MAILS_UPLOAD_STORAGE_LINK')}/$electronic_mail_name";
        if (move_uploaded_file($electronic_mail['tmp_name'], $incoming_mails_link)) {
            $administrator -> create_incoming_mail(
                transmitter         : trim(      $_POST['transmitter']),
                receiver            : trim(         $_POST['receiver']),
                number              : trim(           $_POST['number']),
                subject             : trim(          $_POST['subject']),
                transmission_date   : trim($_POST['transmission_date']),
                electronic_mail_name: $secure_name
            );
            rename($incoming_mails_link, $secure_link);
            header('Location: ../read/incoming-mails.php');
        } else {
            die("Erreur en Création du Courrier.");
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?= Page::get_head() ?>
    <link rel="stylesheet" href="../asset/style/create/incoming-mail.css">
    <title>Ajouter Un Courrier à Arrivée | Bureau d'Ordre</title>
</head>
<body>
    <div id="container" class="container">
        <header id="header"><?= Page::get_header($userpath) ?></header>
        <aside id="aside"><?= Page::get_aside() ?></aside>
        <main id="main" class="main">
            <form  class="form" method="post" enctype="multipart/form-data">
                <div class="smta">
                    <a href="../read/incoming-mails.php">Retourner</a>
                </div>
                <div class="main-form">
                    <span>Ajouter Un Courrier à Arrivée</span>
                    <div class="input">
                        <label for="transmitter">Émetteur</label>
                        <select name="transmitter" id="transmitter">
                            <option value="" selected disabled>...</option>
                            <?php 
                                foreach ($administrator -> get_services() as $service) {
                                    echo <<<HTML
                                        <option value="{$service -> get('name')}">{$service -> get("name")}</option>
                                    HTML;
                                }
                            ?>
                        </select>
                    </div>
                    <div class="input">
                        <label for="receiver">Destinataire</label>
                        <select name="receiver" id="receiver">
                            <option value="" selected disabled>...</option>
                            <?php 
                                foreach ($administrator -> get_divisions() as $division) {
                                    echo <<<HTML
                                        <option value="{$division -> get('name')}">{$division -> get("name")}</option>
                                    HTML;
                                }
                            ?>
                        </select>
                    </div>
                    <div class="input">
                        <label for="number">Numéro de Courrier</label>
                        <input type="text" name="number" id="number" autocomplete="off">
                    </div>
                    <div class="input">
                        <label for="subject">Objet</label>
                        <textarea name="subject" id="subject"  autocomplete="off"></textarea>
                    </div>
                    <div class="input">
                        <label for="date">Date</label>
                        <input type="date" name="transmission_date" id="date" autocomplete="off">
                    </div>
                    <div class="input">
                        <label id="file-label" for="electronic-mail">Courrier Éléctronique</label>
                        <input type="file" name="electronic_mail" id="electronic-mail" autocomplete="off" hidden>
                        <div class="fake-file">
                            <button type="button">Choisir</button>
                            <span>Aucun Courrier Éléctronique Séléctionné</span>
                        </div>
                    </div>
                    <div class="buttons">
                        <button type="reset">Réinitialiser</button>
                        <button type="button" id="add-button">Ajouter</button>
                    </div>
                </div>
                <div id="add-pop-up" class="attention--pop-up">
                    <strong><i class="fa-solid fa-circle-exclamation"></i>Attention</strong>
                    <p class="capitalize">Voulez vous vraiment ajouter Ce Courrier ?!</p>
                    <div class="dialog-decisions">
                        <button type="submit" type="submit" name="create_incoming_mail">Oui</button>
                        <button type="button" class="closePopUp">Non</button>
                    </div>
                </div>
                <div id="fill-pop-up" class="attention--pop-up">
                    <strong><i class="fa-solid fa-circle-exclamation"></i>Attention</strong>
                    <p class="capitalize">Vous devez remplir tous le formulaire!</p>
                    <div class="dialog-decisions">
                        <button type="button" class="closePopUp">OK!</button>
                    </div>
                </div>
            </form>
        </main>
    </div>
    <script src="../asset/javascript/create/mail.js"></script>
</body>
</html>