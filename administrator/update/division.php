<?php 
    if (isset($_GET['user_id'])) {
        require_once '../ADMINISTRATOR.php';
        $division = new Division(user_id: $_GET['user_id']);
        $userpath = <<<HTML
            <li><a href="../read/divisions.php">Divisions</a></li>
            <li><a href="division.php?user_id={$_GET['user_id']}">{$division->get('name')}</a></li>
        HTML;
        if (isset($_POST['update_division'])) {
            $administrator -> update_division(
                division    : $division,
                name        : trim($_POST['division_name']),
                email       : trim(        $_POST['email']),
                password    : trim(     $_POST['password']),
                phone_number: trim(  $_POST['phone_number'])
            );
            header('Location: ../read/divisions.php');
        }
    } else {
        die("Vous n'avez pas le droit d'acceder à cette Page!!");
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?= Page::get_head() ?>
    <link rel="stylesheet" href="../asset/style/update/division.css">
    <title>Ajouter Une Division | Bureau d'Ordre</title>
</head>
<body>
    <div id="container" class="container">
        <header id="header"><?= Page::get_header($userpath) ?></header>
        <aside id="aside"><?= Page::get_aside() ?></aside>
        <main id="main" class="main">
            <form class="form" method="post">
                <div class="smta"><a href="../read/divisions.php">Retourner</a></div>
                <div class="main-form">
                    <span>Modification</span>
                    <div class="input">
                        <label for="division_name">Nom de La Division</label>
                        <input type="text" name="division_name" id="division_name" autocomplete="off"
                        value="<?= $division -> get('name') ?>">
                    </div>
                    <div class="input">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" autocomplete="off"
                        value="<?= $division -> get('email') ?>">

                    </div>
                    <div class="input">
                        <label for="phone_number">Numéro de Téléphone</label>
                        <input type="text" name="phone_number" id="phone_number" autocomplete="off"
                        value="<?= $division -> get('phone_number') ?>">
                    </div>
                    <div class="input">
                        <label for="password">Mot de Passe</label>
                        <input type="text" name="password" id="password" autocomplete="off"
                        value="<?= $division -> get('password') ?>">
                    </div>
                    <div class="buttons">
                        <button type="reset">Réinitialiser</button>
                        <button type="button" id="add-button">Modifier</button>
                    </div>
                </div>
                <div id="add-pop-up" class="attention--pop-up">
                    <strong><i class="fa-solid fa-circle-exclamation"></i>Attention</strong>
                    <p class="capitalize">Voulez vous vraiment Modifier Cette Division ?!</p>
                    <div class="dialog-decisions">
                        <button type="submit" type="submit" name="update_division">Oui</button>
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
    <script src="../asset/javascript/update/division.js"></script>
</body>
</html>