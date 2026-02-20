<?php 
    require_once '../ADMINISTRATOR.php';
    $userpath = <<<HTML
        <li><a href="divisions.php">Divisions</a></li>
    HTML;
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
        $table_heading = "Les Divisions Recherchés";
        $db_connection = Database::connect();

        $real_id            = $_GET['id']           ?? null;
        $real_name          = $_GET['name']         ?? null;
        $real_email         = $_GET['email']        ?? null;
        $real_phone_number  = $_GET['phone_number'] ?? null;

        $id             = isset(          $_GET['id']) ? $db_connection -> quote(trim(                  $_GET['id'])) :null;
        $name           = isset(        $_GET['name']) ? $db_connection -> quote('%'.trim(        $_GET['name']).'%') :null;
        $email          = isset(       $_GET['email']) ? $db_connection -> quote('%'.trim(       $_GET['email']).'%') :null;
        $phone_number   = isset($_GET['phone_number']) ? $db_connection -> quote('%'.trim($_GET['phone_number']).'%') :null;
       
        $id_cond           = isset(          $id) ? "id                  =  $id                    " :null;
        $email_cond        = isset(       $email) ? "LOWER(email)        LIKE LOWER(       $email) " :null;
        $name_cond         = isset(        $name) ? "LOWER(name)         LIKE LOWER(        $name) " :null;
        $phone_number_cond = isset($phone_number) ? "LOWER(phone_number) LIKE LOWER($phone_number) " :null;

        $conditions = array_kill_null_values([$id_cond, $name_cond, $email_cond, $phone_number_cond]);
        $stmt = put_conditions_inside_sql_select(
            target    : "\"Division\" INNER JOIN \"User\" ON \"Division\".user_id = \"User\".id", 
            conditions: $conditions
        );
        $divisions = [];
        $stmt = $db_connection -> query($stmt);
        while ($division = $stmt -> fetch(PDO::FETCH_OBJ)) {
            array_push($divisions, new Division(user_id: $division -> user_id));
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete_division'])) {
            $location =  put_values_in_url_for_get_request(
                baseurl: 'DELETE.php?target=division&', argument_name: "user_id", values: $_POST['checked_divisions']
            );
            header("Location: $location");
        } elseif (isset($_POST['update_division'])) {
            $location = "../update/division.php?user_id={$_POST['checked_divisions'][0]}";
            header("Location: $location");
        } else {
            $location = from_post_make_its_arguments_in_url(baseurl: "divisions.php");
            header("Location: $location");
        }
    } else {
        $divisions = $administrator -> get_divisions();
        $table_heading = "Les Divisions";
    }
?> 
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php echo Page::get_head() ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <link rel="stylesheet" href="../asset/style/read/divisions.css">
    <title>Divisions | Bureau d'Ordre</title>
</head>
<body>
    <div id="container" class="container">
        <header id="header"><?php echo Page::get_header($userpath) ?></header>
        <aside id="aside"><?php echo Page::get_aside() ?></aside>
        <main id="main">
            <form method="post" class="form">
                <div class="smta">
                    <button id="search-button" type="button" title="Rechercher">Rechercher</button>
                    <a href="../create/division.php" title="Ajouter Une Nouvelle Division">Ajouter</a>
                    <button id="update-button" type="submit" name="update_division">Modifier</button>
                    <button id="delete-button" type="button">Supprimer</button>
                </div>
                <div class="table">
                    <span class="heading"><?php echo $table_heading ?></span>
                    <div id="om-search-form">
                        <div id="om-inputs">
                            <input placeholder="Nom"   type="text" name="name"  value="<?= $real_name ?? '' ?>">
                            <input placeholder="Email" type="text" name="email" value="<?= $real_email ?? '' ?>">
                            <input placeholder="Numéro de Téléphone" type="text" name="phone_number" value="<?= $real_phone_number ?? '' ?>">
                        </div>
                        <div id="om-options">
                            <button id="search-reset-di" type="reset">Réinitialiser</button>
                            <button type="submit" name="search_divisions">Rechecher</button>
                        </div>
                    </div>
                    <table id="myTable" class="cell-border">
                        <thead>
                            <tr>
                                <th class="dt-head-center">#</th>
                                <th>Nom de La Division</th>
                                <th>Email</th>
                                <th>Numéro de Téléphone</th>
                                <th>Mot de Passe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if (!(count($divisions) === 0)) {
                                    foreach ($divisions as $division) {
                                        echo "
                                            <tr>
                                                <td class='identifiant'>
                                                    <input class='checked_divisions' type='checkbox' name='checked_divisions[]' 
                                                    value='{$division -> get('user_id')}'>
                                                </td>
                                                <td class='nom-de-la-division'>{$division->get('name')}</td>
                                                <td class='email-de-la-division'>{$division->get('email')}</td>
                                                <td class='numéro-de_téléphone-de-la-division'>{$division->get('phone_number')}</td>
                                                <td class='mot-de-passe-de-la-division'>{$division->get('password')}</td>
                                            </tr>
                                        ";
                                    }   
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div div id="delete-pop-up" class="attention--pop-up">
                    <strong><i class="fa-solid fa-circle-exclamation"></i>Attention</strong>
                    <p class="capitalize">Voulez Vous Vraiment Supprimer Ces Divisions ?!</p>
                    <div class="dialog-decisions">
                        <button type="submit" name="delete_division">Oui</button>
                        <button type="button" class="closePopUp">Non</button>
                    </div>
                </div>
                <div div id="exception-pop-up" class="attention--pop-up">
                    <strong id="exception-header"><i class="fa-solid fa-circle-exclamation"></i>UNKNOW</strong>
                    <p id="exception-body" class="capitalize">UKNNOWN</p>
                    <div class="dialog-decisions">
                        <button type="button" class="closePopUp">OK!</button>
                    </div>
                </div>
            </form>
        </main>
    </div>
    <script src="../asset/javascript/read/divisions.js"></script>
</body>
</html>
