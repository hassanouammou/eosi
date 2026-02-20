<?php
    session_start();
    if (isset($_SESSION['user_not_found']) && $_SESSION['user_not_found']) {
        echo <<<JS
            <script>
                window.addEventListener("DOMContentLoaded", () => {
                    setTimeout(() => {
                        var loginSection = document.querySelector("#login-section");
                        var loginMessage = document.querySelector("#login-message");
                        var loginFormFields = document.querySelectorAll("#login-form input");
                        loginSection.scrollIntoView();
                        loginMessage.style.display = "block";
                        loginFormFields.forEach(field => {
                            field.onclick = () => {
                                loginMessage.style.display = "none";
                            }
                            field.onkeyup = () => {
                                loginMessage.style.display = "none";
                            };
                        });
                        setTimeout(() => {
                            loginMessage.style.display = "none";
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
    <link rel="stylesheet"    href="/eosi/index/style.css">
    <title>Bureau d'Ordre | Province Guelmim</title>
</head>
<body>
    <div id="container">
        <header id="header">
            <a href="#home-section" id="brand-name" title="Province de Guelmim">Province de Guelmim</a>
            <nav id="navigation-bar">
                <a class="link" href="#home-section">Acceuil</a>
                <a class="link" href="#login-section">Connexion</a>
            </nav>
        </header>
        <main id="main">
            <section id="home-section" class="section">
                <img id="picture" src="index/willaya.jpg" alt="Image du Province de Guelmim"/>
                <div id="paragraph">
                    <span class="heading"><small>Application de Gestion des Courriers (Bureau d'Ordre)</small></span>
                    <p class="description">Système informatisé de Gestion des Courriers Pour Province de Guelmim</p>
                </div>
            </section>
            <section id="login-section" class="section">
                <span class="heading">Connecter À Votre Compte ICI</span>
                <form id="login-form" action="authentification/login.php" method="post">
                    <div class="field">
                        <label class="label" for="email">Email</label>
                        <input class="input" type="email" name="email" id="email" title="Veuillez Saisir Votre Email" required>
                    </div>
                    <div class="field">
                        <label class="label" for="password">Mot de Passe</label>
                        <input class="input" type="password" name="password" id="password" 
                        title="Veuillez Saisir Votre Mot de Passe" required/>
                    </div>
                    <div id="login-message">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <strong class="capitalize">Veuillez Vérifer Votre Email ou Votre mot de passe</strong></div>
                    <div id="login-options">
                        <a id="reset-password-link" class="link" href="authentification/reset-password/start.php">Mot de Passe Oubliè?</a>
                        <button id="login-button" type="submit" name="login">Connecter</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
    <script src="index/main.js"></script>
</body>
</html>