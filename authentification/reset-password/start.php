<?php session_start(); ?>
<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    if (isset($_POST['complete'])) {
        require '../../plugins/PHPMailer/src/Exception.php';
        require '../../plugins/PHPMailer/src/PHPMailer.php';
        require '../../plugins/PHPMailer/src/SMTP.php';
        require_once '../../model/Autoloader.php';
        $autoloader = new Autoloader(for: "authentification");
        $autoloader -> run();
        $email = trim($_POST['email']);
        if (UserAuth::can_reset_password(email: $email)) {
            $mail = new PHPMailer(true);
            $mail -> isSMTP();
            $mail -> SMTPAuth = true;
            $mail -> Host = "";
            $mail -> SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail -> Port = 587;
            $mail -> Username = "";
            $mail -> Password = "";
            $mail -> setFrom('', '');
            $mail -> IsHTML (true);
            $mail -> CharSet = "UTF-8";
            $mail -> Encoding = 'base64';

            $mail -> addAddress($email);
            $mail -> Subject = 'Réinitialisation du Mot de Passe';

            $reset_password_link = UserAuth::get_reset_password_link(email: $email);
            $mail -> Body = 
            "Veuillez Clicker <a href='$reset_password_link'>ICI</a> Pour Pouvoir Définir Votre Nouveau Mot de Passe. <br>Attention Ce lien sera disponible Pour 30 minutes ";
            if ($mail -> Send()) {
                $_SESSION['user_not_found'] = false;
                header('Location ./');
            } else {
                die("Error: $mail -> ErrorInfo");
            }
        } else {
            $_SESSION['user_not_found'] = true;
            header('Location ./');
        }
    }
?>
<?php
    if (isset($_SESSION['user_not_found'])) {
        if ($_SESSION['user_not_found'] === false) {
            echo <<<JS
            <script>
                window.addEventListener("DOMContentLoaded", () => {
                    setTimeout(() => {
                        var requestMessage = document.querySelector("#request-message");
                        requestMessage.style.cssText = `
                            background-color: #5EB727;
                            color: #ffffff;
                        `;
                        requestMessage.innerHTML = `
                            <i class="fa-solid fa-circle-check"></i>
                            <strong class="capitalize">
                                Demmande Réussie, Veuillez Consulter Votre Gmail
                            </strong>
                        `;
                        requestMessage.style.display = "block";
                        email.onclick = () => {
                            requestMessage.style.display = "none";
                        }
                        email.onkeyup = () => {
                            requestMessage.style.display = "none";
                        };
                        setTimeout(() => {
                            requestMessage.style.display = "none";
                        }, 10000);
                    }, 200);
                });
            </script>
            JS;
        } else {
            echo <<<JS
            <script>
                window.addEventListener("DOMContentLoaded", () => {
                    setTimeout(() => {
                        var requestMessage = document.querySelector("#request-message");
                        requestMessage.style.display = "block";
                        email.onclick = () => {
                            requestMessage.style.display = "none";
                        }
                        email.onkeyup = () => {
                            requestMessage.style.display = "none";
                        };
                        setTimeout(() => {
                            requestMessage.style.display = "none";
                        }, 5000);
                    }, 200);
                });
            </script>
            JS;
        }
        session_destroy();
    }
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
    <link rel="stylesheet"    href="asset/style/start.css">
    <title>Bureau d'Ordre | Province Guelmim</title>
</head>
<body>
    <div id="container">
        <header id="header">
            <a href="../../#home-section" id="brand-name" title="Province de Guelmim">Province de Guelmim</a>
            <nav id="navigation-bar">
                <a class="link" href="../../#login-section">Connexion</a>
            </nav>
        </header>
        <main id="main">
            <section id="start-reset-section" class="section">
                <span class="heading">Veuillez Saisir Votre Email Pour Continuer</span>
                <form id="start-reset-form" method="post">
                    <div class="field">
                        <label class="label" for="email">Email</label>
                        <input class="input" type="email" name="email" id="email" title="Veuillez Saisir Votre Email" required>
                    </div>
                    <div id="request-message">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <strong class="capitalize">Cet Email N'appartient à aucun compte, Veuillez le Vérifier !</strong>
                    </div>
                    <button id="start-reset-button" type="submit" name="complete">Continuer</button>
                </form>
            </section>
        </main>
    </div>
    <script src="asset/javascript/main.js"></script>
</body>
</html>