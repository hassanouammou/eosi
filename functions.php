<?php 
    function pre_printer(mixed $var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    function array_kill_null_values(array $array) : array {
        foreach ($array as $key => $value) {
            if (is_null($value)) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    function put_conditions_inside_sql_select(string $target, array $conditions) : string {
        $basestmt = "SELECT * FROM $target WHERE";
        $conditions = implode(" AND ", $conditions);
        $stmt = "$basestmt $conditions";
        return $stmt;
    }

    function put_values_in_url_for_get_request(string $baseurl, string $argument_name, array $values) : string {
        $arguments = array();
        foreach ($values as $index => $value) {
            array_push($arguments, "{$argument_name}{$index}={$value}");
        }
        $arguments = implode("&", $arguments);
        return "$baseurl?$arguments";
    }

    function from_post_make_its_arguments_in_url(string $baseurl) : string {
        array_pop($_POST);
        $url = "$baseurl?";
        foreach ($_POST as $key => $value) {
            if (!empty($value)) {
                if ($key === array_key_last($_POST)) {
                    $url .= "$key=$value";
                } else {
                    $url .= "$key=$value&";
                }
            } else {
                unset($_POST[$key]);
            }
        }
        return $url;
    }
?>


