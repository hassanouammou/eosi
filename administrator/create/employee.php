<?php 
    require_once '../ADMINISTRATOR.php';
    $userpath = <<<HTML
        <li><a href="../read/employees.php">Employés</a></li>
        <li><a href="employee.php">Ajoutation</a></li>
    HTML;
    if (isset($_POST['create_employee'])) {
        $photo            = $_FILES['photo'];
        $photo_name       = $photo['name'];
        $photo_link       = "{$CONSTANT('EMPLOYEE_PICTURE_UPLOAD_STORAGE_LINK')}/$photo_name";
        $secure_base_name = str_replace(
            search : pathinfo($photo_name, PATHINFO_BASENAME), 
            replace: "employee-" . trim($_POST['firstname']) . "-". trim($_POST['lastname']),
            subject: $photo_name
        );
        $extension   = pathinfo($photo_name, PATHINFO_EXTENSION);
        $secure_name = "$secure_base_name.$extension";
        $secure_link = "{$CONSTANT('EMPLOYEE_PICTURE_UPLOAD_STORAGE_LINK')}/$secure_name";
        if (move_uploaded_file($photo['tmp_name'], $photo_link)) {
            $administrator -> create_employee(
                firstname   : trim(   $_POST['firstname']),
                lastname    : trim(    $_POST['lastname']),
                birth_date  : trim(  $_POST['birth_date']),
                gender      : trim(      $_POST['gender']),
                division_id : trim( $_POST['division_id']),
                email       : trim(       $_POST['email']),
                password    : trim(    $_POST['password']),
                phone_number: trim($_POST['phone_number']),
                photo_name  : $secure_name
            );
            rename($photo_link, $secure_link);
            header("Location: ../read/employees.php");
        } else {
            die("Erreur En Ajoutation d'Employé !");
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?= Page::get_head() ?>
    <link rel="stylesheet" href="../asset/style/create/employee.css">
    <title>Mon Profile | Bureau d'Ordre</title>
</head>
<body>
    <div id="container" class="container">
        <header id="header"><?= Page::get_header($userpath) ?></header>
        <aside id="aside"><?= Page::get_aside() ?></aside>
        <main id="main">
            <form method="post" id="main-form" enctype="multipart/form-data">
                <div id="picture-and-options">
                    <div id="user-picture">
                        <img src="../../upload/photo/employee/user.svg" alt="La Photo du Profile" 
                        title="La Photo de Profile">
                        <input type="file" id="user-picture-file" name="photo" hidden>
                        <button type="button" id="btn-photo">Choisir Une Photo</button>
                    </div>
                </div>
                <div id="options-content">
                    <div method="post" id="form-profile">
                        <div id="user-data">
                            <span>Données Personnelles</span>
                            <div class="field-double">
                                <div class="field">
                                    <label for="firstname">Prénom</label>
                                    <input type="text" name="firstname" id="firstname">
                                </div>
                                <div class="field">
                                    <label for="lastname">Nom</label>
                                    <input type="text" name="lastname" id="lastname">
                                </div>
                            </div>
                            <div class="field-double">
                                <div class="field">
                                    <label for="birth_date">Date de Naissance</label>
                                    <input type="date" name="birth_date" id="birth_date">
                                </div>
                                <div class="field">
                                    <label for="gender">Genre</label>
                                    <select name="gender" id="gender">
                                        <option value="" selected>...</option>
                                        <option value="homme">Homme</option>
                                        <option value="femme">Femme</option>
                                    </select>
                                </div>
                            </div>
                            <div class="field">
                                <label for="division_name">Division</label>
                                <select name="division_id" id="division_name">
                                    <option value="" selected>...</option>
                                    <?php 
                                        foreach ($administrator -> get_divisions() as $division) {
                                            echo "<option value='{$division->get("id")}'>{$division->get("name")}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="field">
                                <label for="phone_number">Numéro de Téléphone</label>
                                <input type="text" name="phone_number" id="phone_number" >
                            </div>
                        </div>
                        <div id="user-security">
                            <span>Sécurité</span>
                            <div class="fields">
                                <div class="field">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" >
                                </div>
                                <div class="field">
                                    <label for="password">Mot de Passe</label>
                                    <input type="text" name="password" id="password" >
                                </div>
                            </div>
                            <button type="button" id="add-button">Ajouter</button>
                        </div>
                    </div>
                </div>
                <div id="add-pop-up" class="attention--pop-up">
                    <strong><i class="fa-solid fa-circle-exclamation"></i>Attention</strong>
                    <p class="capitalize">Voulez vous vraiment ajouter Cet Employé ?!</p>
                    <div class="dialog-decisions">
                        <button type="submit" type="submit" name="create_employee">Oui</button>
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
    <script src="../asset/javascript/create/employee.js"></script>
</body>
</html>
