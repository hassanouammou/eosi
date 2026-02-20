<?php 
    require_once '../ADMINISTRATOR.php';
    $userpath = <<<HTML
        <li><a href="../read/divisions.php">Divisions</a></li>
        <li><a href="division.php">Ajoutation</a></li>
    HTML;
    if (isset($_POST['create_division'])) {
        $administrator -> create_division(
            name         : trim($_POST['division_name']),
            email        : trim(        $_POST['email']),
            password     : trim(     $_POST['password']),
            phone_number : trim( $_POST['phone_number']),
        );
        header('Location: ../read/divisions.php');
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?= Page::get_head() ?>
    <link rel="stylesheet" href="../asset/style/create/division.css">
    <title>Ajouter Une Division | Bureau d'Ordre</title>
</head>
<body>
    <div id="container" class="container">
        <header id="header"><?= Page::get_header($userpath) ?></header>
        <aside id="aside"><?= Page::get_aside() ?></aside>
        <main id="main" class="main">
            <form  class="form" method="post">
                <div class="smta"><a href="../read/divisions.php">Retourner</a></div>
                <div class="main-form">
                    <span>Ajouter Une Nouvelle Division</span>
                    <div class="input">
                        <label for="division_name">Nom de La Division</label>
                        <input type="text" name="division_name" id="division_name" autocomplete="off">

                    </div>
                    <div class="input">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" autocomplete="off">
                    </div>
                    <div class="input">
                        <label for="phone_number">Numéro de Téléphone</label>
                        <input type="text" name="phone_number" id="phone_number" autocomplete="off">
                    </div>
                    <div class="input">
                        <label for="password">Mot de Passe</label>
                        <input type="text" name="password" id="password" autocomplete="off">
                    </div>
                    <div class="buttons">
                        <button type="reset">Réinitialiser</button>
                        <button type="button" id="add-button">Ajouter</button>
                    </div>
                </div>
                <div id="add-pop-up" class="attention--pop-up">
                    <strong><i class="fa-solid fa-circle-exclamation"></i>Attention</strong>
                    <p class="capitalize">Voulez vous vraiment ajouter Cette Division ?!</p>
                    <div class="dialog-decisions">
                        <button type="submit" type="submit" name="create_division">Oui</button>
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
    <script src="../asset/javascript/create/division.js"></script>
</body>
</html>