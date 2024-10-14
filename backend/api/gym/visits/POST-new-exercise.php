<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("POST");

validate_request_data($_POST, "exerciseID|string", "weight|number");

must_be_authenticated();

safely_start_session();


$database = new DemandDB();

$visit = $database->get_document("gym_visits", $_POST["id"]);

if(!$visit) {
    send_response(404, "There is no visit with that ID");
}

array_push($visit["exercises"], array(
    "exerciseID" => $_POST["exerciseID"],
    "weight" => $_POST["weight"],
));

$database->set_document("gym_visits", $_POST["id"], $visit);

?>