<?php 
    require_once '../DIVISION.php';
    $userpath = <<<HTML
        <li><a href="employees.php">Employés</a></li>
    HTML;

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
        $db_connection          = Database::connect();
        $table_heading          = "Les Employés Recherchés";


        $real_user_id           = $_GET['user_id']    ?? null;
        $real_firstname         = $_GET['firstname']  ?? null;
        $real_lastname          = $_GET['lastname']   ?? null;
        $real_gender            = $_GET['gender']     ?? null;
        $real_birth_date        = $_GET['birth_date'] ?? null;
        $real_email             = $_GET['email']      ?? null;

        $user_id           = isset(   $_GET['user_id']) ? $db_connection -> quote(trim(          $_GET['user_id'])) :null;
        $firstname         = isset( $_GET['firstname']) ? $db_connection -> quote('%'.trim($_GET['firstname']).'%') :null;
        $lastname          = isset(  $_GET['lastname']) ? $db_connection -> quote('%'.trim( $_GET['lastname']).'%') :null;
        $gender            = isset(    $_GET['gender']) ? $db_connection -> quote(trim(           $_GET['gender'])) :null;
        $birth_date        = isset($_GET['birth_date']) ? $db_connection -> quote(trim(       $_GET['birth_date'])) :null;
        $email             = isset(     $_GET['email']) ? $db_connection -> quote('%'.trim(    $_GET['email']).'%') :null;

        $user_id_cond      = isset(   $user_id) ? "user_id           =  $user_id            " :null;
        $firstname_cond    = isset( $firstname) ? "LOWER(firstname)  LIKE LOWER($firstname) " :null;
        $lastname_cond     = isset(  $lastname) ? "LOWER(lastname)   LIKE LOWER($lastname)  " :null;
        $gender_cond       = isset(    $gender) ? "gender            = $gender              " :null;
        $birth_date_cond   = isset($birth_date) ? "birth_date        = $birth_date          " :null;
        $email_cond        = isset(     $email) ? "LOWER(email)      LIKE LOWER($email)     " :null;

        $conditions = array_kill_null_values(
            [$user_id_cond, $firstname_cond, $lastname_cond, $gender_cond, 
            $birth_date_cond, $email_cond, "division_id = {$division -> get('id')}"]
        );
        $stmt = put_conditions_inside_sql_select("\"Employee\" INNER JOIN \"User\" ON \"Employee\".user_id = \"User\".id", $conditions);
        $employees = array();
        $stmt = $db_connection -> query($stmt);
        while ($employee = $stmt -> fetch(PDO::FETCH_OBJ)) {
            array_push($employees, new Employee(user_id: $employee -> user_id));
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete_employee'])) {
            $location =  put_values_in_url_for_get_request(
                baseurl: 'DELETE.php?target=employee&', argument_name: "user_id", values: $_POST['checked_employees']
            );
            header("Location: $location");
        } elseif (isset($_POST['update_employee'])) {
            $location = "../update/employee.php?user_id={$_POST['checked_employees'][0]}";
            header("Location: $location");
        } else {
            $location = from_post_make_its_arguments_in_url(baseurl: "employees.php");
            header("Location: $location");
        }
    } else {
        $employees = $division -> get_employees();
        $table_heading = "Les Employés";
    }
?> 
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php echo Page::get_head() ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <link rel="stylesheet" href="../asset/style/read/employees.css">
    <title>Employés  | Bureau d'Ordre</title>
</head>
<body>
    <div id="container" class="container">
        <header id="header"><?php echo Page::get_header($userpath) ?></header>
        <aside id="aside"><?php echo Page::get_aside() ?></aside>

        <main id="main">
            <form method="post" class="form" id="emp-search-form">
                <div class="smta">
                    <?php 
                        $visibility = 'visible';
                        if (isset($_GET['user_id'])) {
                            $visibility    = 'hidden';  
                            $table_heading = "Le Nouveau Employé";
                        }
                        
                    ?>
                    <button id="search-button" type="button" title="Rechercher" style="visibility: <?= $visibility ?>">
                        Rechercher
                    </button>
                    <?php 
                        if(isset($_GET['user_id'])) {
                            echo "<a title='Retourner' href='dashboard.php'>Retourner</a>";
                        }
                    ?>
                </div>
                <div class="table">
                    <span class="heading"><?= $table_heading ?></span>
                    <div id="om-search-form">
                        <div id="om-inputs">
                            <input placeholder="Prénom" type="text" name="firstname" value="<?= $real_firstname ?? '' ?>">
                            <input placeholder="Nom" type="text" name="lastname" value="<?= $real_lastname ?? '' ?>">
                            <input placeholder="Genre" type="text" name="gender" value="<?= $real_gender ?? '' ?>" list="gender-list-options" autocomplete="off">
                            <datalist id="gender-list-options"><option value="homme"/><option value="femme"/></datalist>
                            <input placeholder="Date de Naissance" type="text"  name="birth_date" value="<?= $real_birth_date ?? '' ?>">
                            <input placeholder="Email" type="text" name="email" value="<?= $real_email ?? '' ?>">
                        </div>
                        <div id="om-options">
                            <button type="reset" id="emp-search-reset">Réinitialiser</button>
                            <button type="submit" name="search_employees">Rechecher</button>
                        </div>
                    </div>
                    <table id="myTable" class="cell-border">
                        <thead>
                            <tr>
                                <th class="dt-head-center">Prénom</th>
                                <th class="dt-head-center">Nom</th>
                                <th class="dt-head-center">Genre</th>
                                <th class="dt-head-center">Date de Naissance</th>
                                <th>Email</th>
                                <th class="dt-head-center">Détailles et Modification</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if (!(count($employees) === 0)) {
                                    foreach ($employees as $employee) {
                                        echo "
                                            <tr>
                                                <td class='firstname'>{$employee->get('firstname')}</td>
                                                <td class='lastname'>{$employee->get('lastname')}</td>
                                                <td class='gender'>{$employee->get('gender')}</td>
                                                <td class='birth_date'>{$employee->get('birth_date')}</td>
                                                <td class='division'>{$employee->get('email')}</td>
                                                <td class='détailles'>
                                                    <a href='employee.php?user_id={$employee -> get('user_id')}'>Voir Plus</a>
                                                </td>
                                            </tr>
                                        ";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>     
            </form>
        </main>
    </div>
    <script src="../asset/javascript/read/employees.js"></script>
</body>
</html>
