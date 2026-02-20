<?php session_start(); ?>
<?php
    if (isset($_GET['user_id']) && $_GET['user_id']) {
        require_once '../../model/Autoloader.php';
        $autoloader   = new Autoloader(for: "authentification");
        $autoloader   -> run();
        $user_id      = $_GET['user_id'];
        $user         = new User(user_id: $user_id);
        $userauth     = new UserAuth(email: $user -> get("email"), password: $user -> get("password"));
        $datetime     = new DateTime("Africa/Casablanca");
        $now_datetime = $datetime -> format("Y-m-d H:i:s");
        if ($userauth -> get("begin_reset_password")) {
            if (!($userauth -> get("reset_begin_time") <= $now_datetime && $userauth -> get("reset_end_time") >= $now_datetime)) {
                die(
                    "Demmande Expirée. Vous pouvez envoyer une nouvelle demmande en cliquant 
                    <a href='/eosi/authentification/reset-password/start.php'>ICI</a>"
                );
            }
        } else {
            die("
                Vous devez Soumettre d'abord une Demmande de Réinitialisation, vous pouvez demmander une par le lien au-dessous: <br> 
                <a href='/eosi/authentification/reset-password/start.php'>Demmander</a>    
            ");
        }
        if (isset($_POST['validate'])) {
            $new_password = $_POST['new_password'];
            $user -> set("password", trim($new_password));
            $_SESSION['password_changed'] = true;
        }
    } else {
        die("Veuillez N'avez Pas Le Droit d'acceder à Cette Page!!");
    }
?>
<?php
    if (isset($_SESSION['password_changed']) && $_SESSION['password_changed']) {
        echo <<<JS
            <script>
                window.addEventListener("DOMContentLoaded", () => {
                    setTimeout(() => {
                        var requestMessage = document.querySelector("#request-message");
                        requestMessage.style.display = "block";
                        var endResetFields = document.querySelectorAll("#end-reset-form input");
                        endResetFields.forEach(field => {
                            field.onclick = () => {
                                requestMessage.style.display = "none";
                            }
                            field.onkeyup = () => {
                                requestMessage.style.display = "none";
                            };
                        });
                        setTimeout(() => {
                            requestMessage.style.display = "none";
                        }, 5000);
                    }, 200);
                });
            </script>
        JS;
    }
    session_destroy();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@500&display=swap" rel="stylesheet">
    <link rel='stylesheet'    href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css' 
    integrity='sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==' *
    crossorigin='anonymous'/>
    <link rel="shortcut icon" href="/eosi/website-icon.svg" type="image/svg+xml">
    <link rel="stylesheet"    href="asset/style/end.css">
    <title>Bureau d'Ordre | Province Guelmim</title>
</head>
<body>
    <div id="container">
        <header id="header">
            <a href="/eosi/#home-section" id="brand-name" title="Province de Guelmim">Province de Guelmim</a>
            <nav id="navigation-bar">
                <a class="link" href="/eosi/#login-section">Connexion</a>
            </nav>
        </header>
        <main id="main">
            <section id="end-reset-section" class="section">
                <span class="heading">Veuillez Saisir Votre Nouveau Mot de Passe</span>
                <form id="end-reset-form"  method="post">
                    <div class="field">
                        <label class="label" for="new-password">Nouveau Mot de Passe</label>
                        <input class="input" type="password" name="new_password" id="new-password" 
                        title="Veuillez Saisir Votre Nouveau Mot de Passe" required/>
                    </div>
                    <div class="field">
                        <label class="label" for="confirm-password">Confirmation</label>
                        <input class="input" type="password" name="confirm_password" id="confirm-password" 
                        title="Veuillez Confirmer Ce Mot de Passe" required>
                    </div>
                    <div id="request-message">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <strong class="capitalize">Votre Nouveau Mot de Passe A été Réinitialisé</strong>
                    </div>
                    <button id="end-reset-button" type="submit" name="validate">Réinitialiser</button>
                </form>
            </section>
        </main>
    </div>
    <script src="asset/javascript/end.js"></script>
    <script src="asset/javascript/main.js"></script>
</body>
</html>