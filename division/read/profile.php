<?php 
    require_once '../DIVISION.php';
    $userpath = <<<HTML
        <li><a href="profile.php">Profile</a></li>
    HTML;
?>  

<?php
    if (isset($_POST['update'])) {
        $email          = $_POST['email'];
        $password       = $_POST['password'];
        $phone_number   = $_POST['phone_number'];
        $division -> set("email",               $email);
        $division -> set("password",         $password);
        $division -> set("phone_number", $phone_number);
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php echo Page::get_head() ?>
    <link rel="stylesheet" href="../asset/style/read/profile.css">
    <title>Ajouter Une Division | Bureau d'Ordre</title>
</head>
<body>
    <div id="container" class="container">
        <header id="header"><?php echo Page::get_header($userpath) ?></header>
        <aside id="aside"><?php echo Page::get_aside() ?></aside>
        
        <main id="main" class="main">
            <form action="" id="division-profile" class="form" method="post">
                <div class="smta"><a href="dashboard.php">Retourner</a></div>
                <div class="main-form">
                    <span>Le Profile</span>
                    <div class="input">
                        <label for="division_name">Nom de La Division</label>
                        <input type="text" disabled id="division_name" autocomplete="off"
                        value="<?= $division -> get('name')   ?>">

                    </div>
                    <div class="input">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" autocomplete="off"
                        value="<?= $division -> get('email')   ?>">

                    </div>
                    <div class="input">
                        <label for="phone_number">Numéro de Téléphone</label>
                        <input type="text" name="phone_number" id="phone_number" autocomplete="off"
                        value="<?= $division -> get('phone_number')   ?>">
                    </div>
                    <div class="input">
                        <label for="password">Mot de Passe</label>
                        <input type="text" name="password" id="password" autocomplete="off"
                        value="<?= $division -> get('password')   ?>">
                    </div>
                    <div class="buttons">
                        <button type="submit" id="update-profile-btn" name="update">Modifier</button>
                    </div>
                </div>
            </form>
        </main>
    </div>
    <script src="../asset/javascript/read/profile.js"></script>
</body>
</html>