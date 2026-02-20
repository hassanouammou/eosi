<?php
    function build_sentence(array $words, string $words_separator) : string {
        if (count($words) > 1) {
            $sentence = 
            "Vous ne pouvez pas supprimer les Divisions: " 
            . implode($words_separator, array_splice($words, 0, count($words) - 1)) ." et ". end($words) . 
            " Car Elles Sont Assignées à des Employés. Veuillez Décocher Ces Divisions Ou Déplacer Leur Employés.";
        } else {
            $sentence = 
            "Vous ne pouvez pas supprimer <strong> la {$words[0]}</strong> Car Elle est Assignée à des Employés. 
            Veuillez Décocher Cette Division Ou Déplacer Leur Employés.";
        }
        return $sentence;
    }
    require_once '../../ADMINISTRATOR.php';
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db_connection = Database::connect();
        $stmt          = $db_connection -> query("SELECT id FROM \"User\" WHERE role = 'division'");
        $possible_user_ids = array();
        while ($row = $stmt -> fetch(PDO::FETCH_OBJ)) {
            array_push($possible_user_ids, $row -> id);
        }
        $stmt = $db_connection -> query(
            "SELECT \"Division\".user_id FROM \"Employee\" INNER JOIN \"Division\" ON \"Employee\".division_id = \"Division\".id"
        );
        $occupied_user_ids = array();
        while ($row = $stmt -> fetch(PDO::FETCH_OBJ)) {
            array_push($occupied_user_ids, $row -> user_id);
        }
        $cannot_be_deleted_divisions_name = array();
        foreach ($_GET as $user_id) {     
            $division                         = new Division(user_id: $user_id);
            if (in_array($user_id, $possible_user_ids)) {
                if (in_array($user_id, $occupied_user_ids)) {
                    array_push($cannot_be_deleted_divisions_name, "<strong>{$division -> get("name")}</strong>");
                }
            } else {
                echo "division n'existe pas !!";
            }
        }
        
        if (count($cannot_be_deleted_divisions_name) > 0) {
            echo build_sentence(
                words             : $cannot_be_deleted_divisions_name,
                words_separator   : ", ",
            );
        } else {
            echo "so far so good";
        }
    }
?>