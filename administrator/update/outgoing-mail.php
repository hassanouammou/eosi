<?php 
    require_once '../ADMINISTRATOR.php';
    $userpath = <<<HTML
        <li><a>Courriers</a></li>
        <li><a href="../read/outgoing-mails.php">Courriers de Départ</a></li>
        <li><a href="outgoing-mail.php?outgoing_mail_id={$_GET['outgoing_mail_id']}">Modification</a></li>
    HTML;
    $outgoing_mail = new OutgoingMail(outgoing_mail_id: $_GET['outgoing_mail_id']); 
    if (isset($_POST['update_outgoing_mail'])) {
        $electronic_mail_name = $_FILES['electronic_mail']['name'];
        if (!(empty($electronic_mail_name))) {
            $oldlink = "{$CONSTANT('OUTGOING_MAILS_UPLOAD_STORAGE_LINK')}/{$outgoing_mail->get('electronic_mail_name')}";
            $newlink = "{$CONSTANT('OUTGOING_MAILS_UPLOAD_STORAGE_LINK')}/$electronic_mail_name";
            if (move_uploaded_file($_FILES['electronic_mail']['tmp_name'], $newlink) && unlink($oldlink)) {
                $secure_base_name = str_replace(
                    search : pathinfo($electronic_mail_name, PATHINFO_BASENAME), 
                    replace: "outgoing-mail-".trim($_POST['number']), 
                    subject: $electronic_mail_name
                );
                $extension = pathinfo($electronic_mail_name, PATHINFO_EXTENSION);
                $secure_name = "$secure_base_name.$extension";
                $secure_link = "{$CONSTANT('OUTGOING_MAILS_UPLOAD_STORAGE_LINK')}/$secure_name";
                rename($newlink, $secure_link);
                $electronic_mail_name = $secure_name;
            } else {
                die("Erreur: L'Ancien Courrier Éléctronique N'Existe Pas !!");
            }
        } else {
            $oldlink = "{$CONSTANT('OUTGOING_MAILS_UPLOAD_STORAGE_LINK')}/{$outgoing_mail->get('electronic_mail_name')}";
            $secure_base_name = str_replace(
                search : pathinfo($outgoing_mail->get('electronic_mail_name'), PATHINFO_BASENAME), 
                replace: "outgoing-mail-".trim($_POST['number']), 
                subject: $outgoing_mail->get('electronic_mail_name')
            );
            $extension = pathinfo($outgoing_mail->get('electronic_mail_name'), PATHINFO_EXTENSION);
            $secure_name = "$secure_base_name.$extension";
            $secure_link = "{$CONSTANT('OUTGOING_MAILS_UPLOAD_STORAGE_LINK')}/$secure_name";
            rename($oldlink, $secure_link);
            $electronic_mail_name = $secure_name;
        }   
        $administrator -> update_outgoing_mail(
            outgoing_mail       : $outgoing_mail, 
            transmitter         : trim(      $_POST['transmitter']), 
            receiver            : trim(         $_POST['receiver']), 
            number              : trim(           $_POST['number']), 
            subject             : trim(          $_POST['subject']), 
            transmission_date   : trim($_POST['transmission_date']), 
            electronic_mail_name: $electronic_mail_name
        );
        header("Location: ../read/outgoing-mails.php");
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?= Page::get_head() ?>
    <link rel="stylesheet" href="../asset/style/update/outgoing-mail.css">
    <title>Bureau d'Ordre | Province Guelmim</title>
</head>
<body>
    <div id="container" class="container">
        <header id="header"><?= Page::get_header($userpath) ?></header>
        <aside id="aside"><?= Page::get_aside() ?></aside>
        <main id="main" class="main">
            <form  class="form" method="post" enctype="multipart/form-data">
                <div class="smta">
                    <a href="../read/outgoing-mails.php">Retourner</a>
                </div>
                <div class="main-form">
                    <span>Modification</span>
                    <input type="hidden" id="hidden_outgoing_mail_id" name="outgoing_mail_id" value="<?= $outgoing_mail->get('id') ?>">
                    <div class="input">
                        <label for="transmitter">Émetteur</label>
                        <select name="transmitter" id="transmitter">
                            <?php 
                                foreach ($administrator -> get_divisions() as $division) {
                                    echo ($division -> get('name') === $outgoing_mail -> get('transmitter')) 
                                    ? "<option value='{$division->get('name')}' selected>{$division->get('name')}</option>"
                                    : "<option value='{$division->get('name')}'>{$division->get('name')}</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="input">
                        <label for="receiver">Destinataire</label>
                        <select name="receiver" id="receiver">
                            <?php 
                                foreach ($administrator -> get_divisions() as $division) {
                                    echo ($division -> get('name') === $outgoing_mail -> get('receiver')) 
                                    ? "<option value='{$division->get('name')}' selected>{$division->get('name')}</option>"
                                    : "<option value='{$division->get('name')}'>{$division->get('name')}</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="input">
                        <label for="number">Numéro de Courrier</label>
                        <input type="text" id="number" name="number" value="<?= $outgoing_mail -> get("number") ?>" autocomplete="off"/>
                    </div>
                    <div class="input">
                        <label for="subject">Objet</label>
                        <textarea name="subject" id="subject" autocomplete="off"><?= $outgoing_mail -> get("subject") ?></textarea>
                    </div>
                    <div class="input">
                        <label for="transmission_date">Date</label>
                        <input type="date" name="transmission_date" id="transmission_date"
                        value="<?= $outgoing_mail -> get("transmission_date") ?>"autocomplete="off"/>
                    </div>
                    <div class="input electronic_mail">
                        <label id="file-label" for="electronic-mail">Courrier Éléctronique</label>
                        <input type="file" name="electronic_mail" id="electronic-mail" autocomplete="off" 
                        value="<?= $outgoing_mail->get('electronic_mail_name') ?>" hidden/>
                        <div class="fake-file">
                            <button type="button">
                                <?php $link = 
                                "{$CONSTANT('OUTGOING_MAILS_READ_STORAGE_LINK')}/{$outgoing_mail->get('electronic_mail_name')}"; ?>
                                <a href="<?= $link ?>" target="_blank">Consulter L'Ancien</a>
                            </button>
                            <button type="button" id="fake-file-button">Modifier</button>
                            <span></span>
                        </div>
                    </div>
                    <div class="buttons">
                        <button type="button" id="update-button" >Modifier</button>
                    </div>
                </div>
                <div id="update-pop-up" class="attention--pop-up">
                    <strong><i class="fa-solid fa-circle-exclamation"></i>Attention</strong>
                    <p>Voulez vous vraiment modifier ce Courrier ?!</p>
                    <div class="dialog-decisions">
                        <button type="submit" name="update_outgoing_mail">Oui</button>
                        <button type="button" class="closePopUp">Non</button>
                    </div>
                </div>
                <div id="fill-pop-up" class="attention--pop-up">
                    <strong><i class="fa-solid fa-circle-exclamation"></i>Attention</strong>
                    <p>Vous devez remplir tous le formulaire!</p>
                    <div class="dialog-decisions">
                        <button type="button" class="closePopUp">OK!</button>
                    </div>
                </div>
            </form>
        </main>
    </div>
    <script src="../asset/javascript/update/mail.js"></script>
</body>
</html>


