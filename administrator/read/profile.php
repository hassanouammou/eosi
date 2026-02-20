<?php 
    require_once '../ADMINISTRATOR.php';
    $userpath = <<<HTML
        <li><a href="profile.php">Mon Profile</a></li>
    HTML;
    if (isset($_POST['update_profile'])) {
        $administrator -> set('firstname'   , trim(   $_POST['firstname']));
        $administrator -> set('lastname'    , trim(    $_POST['lastname']));
        $administrator -> set('birth_date'  , trim(  $_POST['birth_date']));
        $administrator -> set('gender'      , trim(      $_POST['gender']));
        $administrator -> set('email'       , trim(       $_POST['email']));
        $administrator -> set('phone_number', trim($_POST['phone_number']));
        $administrator -> set('password'    , trim(    $_POST['password']));
        $photo_name = $_FILES['photo']['name'];
        if (!(empty($photo_name))) {
            $old_photo_link = "{$CONSTANT('PROFILE_PICTURE_UPLOAD_STORAGE_LINK')}/{$administrator->get('photo_name')}";
            $new_photo_link = "{$CONSTANT('PROFILE_PICTURE_UPLOAD_STORAGE_LINK')}/$photo_name";
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $new_photo_link) && unlink($old_photo_link)) {
                $new_photo_extension = pathinfo($new_photo_link, PATHINFO_EXTENSION);
                $secure_photo_name = "{$administrator->get('firstname')}-{$administrator->get('lastname')}.$new_photo_extension";
                rename($new_photo_link, "{$CONSTANT('PROFILE_PICTURE_UPLOAD_STORAGE_LINK')}/$secure_photo_name");
                $administrator -> set('photo_name', $secure_photo_name);
            } else {
                die("Erreur Ancien Photo N'Existe Pas !!");
            }
        } else {
            $extension         = pathinfo($administrator -> get('photo_name'), PATHINFO_EXTENSION);
            $secure_photo_name = "{$administrator->get('firstname')}-{$administrator->get('lastname')}.$extension";
            $photo_link        = "{$CONSTANT('PROFILE_PICTURE_UPLOAD_STORAGE_LINK')}/{$administrator->get('photo_name')}";
            rename($photo_link, "{$CONSTANT('PROFILE_PICTURE_UPLOAD_STORAGE_LINK')}/$secure_photo_name");
            $administrator -> set('photo_name', $secure_photo_name);
        }
        header("Location: profile.php");
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?= Page::get_head() ?>
    <link rel="stylesheet" href="../asset/style/read/profile.css">
    <title>Mon Profile | Bureau d'Ordre</title>
</head>
<body>
    <div id="container" class="container">
        <header id="header"><?= Page::get_header($userpath) ?></header>
        <aside id="aside"><?= Page::get_aside() ?></aside>
        <main id="main">
            <form method="post" id="main-form-profile" enctype="multipart/form-data">
                <div id="picture-and-options">
                    <div id="user-picture">
                        <img src="<?= "{$CONSTANT('PROFILE_PICTURE_READ_STORAGE_LINK')}/{$administrator->get('photo_name')}" ?>"
                        alt="La Photo du Profile" title="Clicker ICI Si Vous Voulez Changer Votre Photo de Profile">
                        <input type="file" name="photo" id="user-picture-file" hidden>
                        <div id="quick-user-desc">
                            <span class="capitalize">
                                <?= "{$administrator->get('firstname')} {$administrator->get('lastname')}" ?>
                            </span>
                            <span><?= $administrator->get("email") ?></span>
                        </div>
                    </div>
                </div>
                <div id="options-content">
                    <div id="form-profile">
                        <div id="user-data">
                            <span>Données Personnelles</span>
                            <div class="field-double">
                                <div class="field">
                                    <label for="firstname">Prénom</label>
                                    <input type="text" name="firstname" id="firstname" value="<?= $administrator->get("firstname") ?>">
                                </div>
                                <div class="field">
                                    <label for="lastname">Nom</label>
                                    <input type="text" name="lastname" id="lastname" value="<?= $administrator->get("lastname") ?>">
                                </div>
                            </div>
                            <div class="field-double">
                                <div class="field">
                                    <label for="birth_date">Date de Naissance</label>
                                    <input type="date" name="birth_date" id="birth_date" 
                                    value="<?= date("Y-m-d", strtotime($administrator -> get("birth_date"))) ?>">
                                </div>
                                <div class="field">
                                    <label for="gender">Genre</label>
                                    <select name="gender" id="gender">
                                        <?php
                                            echo 
                                            "<option value='{$administrator->get("gender")}' selected>
                                                {$administrator->get("gender")}
                                            </option>";
                                            echo ($administrator->get("gender")) === "homme" 
                                            ? "<option value='femme'>femme</option>" 
                                            : "<option value='homme'>homme</option>";
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="field">
                                <label for="phone_number">Numéro de Téléphone</label>
                                <input type="tel" name="phone_number" id="phone_number" 
                                value="<?= $administrator->get("phone_number") ?>">
                            </div>
                        </div>
                        <div id="user-security">
                            <span>Sécurité</span>
                            <div class="fields">
                                <div class="field">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" value="<?= $administrator->get("email") ?>">
                                </div>
                                <div class="field">
                                    <label for="password">Mot de Passe</label>
                                    <input type="text" name="password" id="password" value="<?= $administrator->get("password") ?>">
                                </div>
                            </div>
                            <button id="update-profile-btn" type="submit" name="update_profile">Modifier</button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
    <script src="../asset/javascript/update/profile.js"></script>
</body>
</html>
