<?php 
    require_once '../DIVISION.php';
    $employee = new Employee($_GET['user_id']);
    $fullname = "{$employee -> get('firstname')} {$employee -> get('lastname')}";
    $userpath = <<<HTML
        <li><a href="employees.php">Employés</a></li>
        <li><a class="capitalize" href="employee.php?user_id=$_GET[user_id]">$fullname</a></li>
    HTML;
?>
<?php 
    if (isset($_POST['update_employee_profile'])) {
        $employee           = new Employee($_GET['user_id']);
        $firstname          = $_POST['firstname'];
        $lastname           = $_POST['lastname'];
        $birth_date         = $_POST['birth_date'];
        $gender             = $_POST['gender'];
        $email              = $_POST['email'];
        $phone_number       = $_POST['phone_number'];
        $password           = $_POST['password'];
        $photo_name         = $_FILES['photo']['name'] ?? '';
        if (!empty($photo_name)) {
            $old_photo = "{$CONSTANT('EMPLOYEE_PICTURE_UPLOAD_STORAGE_LINK')}/{$employee->get('photo_name')}";
            $new_photo = "{$CONSTANT('EMPLOYEE_PICTURE_UPLOAD_STORAGE_LINK')}/$photo_name";
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $new_photo) && unlink($old_photo)) {
                $secure_base_name = str_replace(
                    search : pathinfo($photo_name, PATHINFO_BASENAME), 
                    replace: "employee-".trim($_POST['firstname'])."-".trim($_POST['lastname']), 
                    subject: $photo_name
                );
                $extension = pathinfo($photo_name, PATHINFO_EXTENSION);
                $secure_name = "$secure_base_name.$extension";
                $secure_link = "{$CONSTANT('EMPLOYEE_PICTURE_UPLOAD_STORAGE_LINK')}/$secure_name";
                rename($new_photo, $secure_link);
                $photo_name = $secure_name;
            } else {
                die("Erreur: L'Ancien Courrier Éléctronique N'Existe Pas !!");
            }
        } else {
            $photo_name = $employee -> get('photo_name');
            $old_photo = "{$CONSTANT('EMPLOYEE_PICTURE_UPLOAD_STORAGE_LINK')}/{$employee->get('photo_name')}";
            $secure_base_name = str_replace(
                search : pathinfo($employee->get('photo_name'), PATHINFO_BASENAME), 
                replace: "employee-".trim($_POST['firstname'])."-".trim($_POST['lastname']), 
                subject: $employee->get('photo_name')
            );
            $extension = pathinfo($employee->get('photo_name'), PATHINFO_EXTENSION);
            $secure_name = "$secure_base_name.$extension";
            $secure_link = "{$CONSTANT('EMPLOYEE_PICTURE_UPLOAD_STORAGE_LINK')}/$secure_name";
            rename($old_photo, $secure_link);
            $photo_name = $secure_name;
        }
        $division -> update_employee(
            employee    :$employee,
            firstname   :$firstname,
            lastname    :$lastname,
            birth_date  :$birth_date,
            gender      :$gender,
            photo_name  :$photo_name,
            email       :$email,
            password    :$password,
            phone_number:$phone_number
        );
        header("Location: employee.php?user_id={$_GET['user_id']}");
    }
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php echo Page::get_head() ?>
    <link rel="stylesheet" href="../asset/style/read/employee.css">
    <title>Mon Profile | Bureau d'Ordre</title>
</head>
<body>
    <div id="container" class="container">
        <header id="header"><?php echo Page::get_header($userpath) ?></header>
        <aside id="aside"><?php echo Page::get_aside() ?></aside>
        <main id="main">
            <div id="main-form-employee">
                <div id="picture-and-options">
                    <div id="user-picture">
                        <img src="<?= "{$CONSTANT('EMPLOYEE_PICTURE_READ_STORAGE_LINK')}/{$employee -> get('photo_name')}" ?>" 
                        alt="La Photo du Profile" title="La Photo de Profile">
                        <input type="file" name="photo"  id="user-picture-file" hidden>
                        <div id="quick-user-desc">
                            <span class="capitalize"><?php echo "{$employee->get('firstname')} {$employee->get('lastname')}"?></span>
                            <span><?php echo $employee->get('email') ?></span>
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
                                    <input type="text" name="firstname" id="firstname" 
                                    value="<?php echo $employee->get('firstname') ?>">
                                </div>
                                <div class="field">
                                    <label for="lastname">Nom</label>
                                    <input type="text" name="lastname" id="lastname" 
                                    value="<?php echo $employee->get('lastname') ?>">
                                </div>
                            </div>
                            <div class="field-double">
                                <div class="field">
                                    <label for="birth_date">Date de Naissance</label>
                                    <input type="date" name="birth_date" id="birth_date" 
                                    value="<?php echo date("Y-m-d", strtotime($employee -> get("birth_date"))) ?>">
                                </div>
                                <div class="field">
                                    <label for="gender">Genre</label>
                                    <input value="<?= $employee->get('gender') ?>" name="gender" id="gender"/>
                                </div>
                            </div>
                            <div class="field">
                                <label for="division_name">Division</label>
                                <?php $division = Division::get_with_self_id(division_id: $employee -> get("division_id"));?>
                                <input type="text" id="division_name" value="<?php echo $division->get('name') ?>">
                            </div>
                            <div class="field">
                                <label for="phone_number">Numéro de Téléphone</label>
                                <input type="text" name="phone_number" id="phone_number" 
                                value="<?php echo $employee->get('phone_number') ?>">
                            </div>
                        </div>
                        <div id="user-security">
                            <span>Sécurité</span>
                            <div class="fields">
                                <div class="field">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" value="<?php echo $employee->get('email') ?>">
                                </div>
                                <div class="field">
                                    <label for="password">Mot de Passe</label>
                                    <input type="text" name="password" id="password" value="<?php echo $employee->get('password') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
    <script src="../asset/javascript/update/employee.js"></script>
</body>
</html>
