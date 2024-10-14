<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("POST");

validate_request_data($_POST, "visitID|string", "index|number");

must_be_authenticated();

safely_start_session();


$database = new DemandDB();

$visit = $database->get_document("gym_visits", $_POST["visitID"]);

if(!$visit) {
    send_response(404, "There is no visit with that ID");
}

if($_POST["index"] < 0 || $_POST["index"] >= count($visit["exercises"])) {
    send_response(400, "Index is out of range");
}

unset($visit["exercises"][$_POST["index"]]);

$visit["exercises"] = array_values($visit["exercises"]);

var_dump($visit["exercises"]);

$database->set_document("gym_visits", $_POST["visitID"], $visit);

?>