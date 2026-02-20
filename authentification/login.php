<?php
    $location = "../";
    if (isset($_POST['login'])) {
        require_once '../model/Autoloader.php';
        $autoloader = new Autoloader(for: "authentification");
        $autoloader -> run();
        $email    = $_POST['email'];
        $password = $_POST['password'];
        session_start();
        if (UserAuth::has_an_account(email: $email, password: $password)) {
            $userauth = new UserAuth(email: $email, password: $password);
            $user     = new User(user_id: $userauth -> get("user_id"));
            switch ($user -> get("role")) {
                case    'administrator' : $location = "../administrator/read/dashboard.php"; break;
                case    'division'      : $location = "../division/read/dashboard.php"     ; break;
                default                 : $_SESSION['user_not_found'] = true               ;
            } 
            list($_SESSION['user_id'], $_SESSION['user_role']) = array($user -> get("id"), $user -> get("role"));
        } else {
            $_SESSION['user_not_found'] = true;
        }
    }
    header("Location: $location");
?>