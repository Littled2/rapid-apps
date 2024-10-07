<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("POST");

validate_request_data($_POST, "name|string");

must_be_authenticated();

safely_start_session();


$database = new DemandDB();

$newExercise = array(
    'name' => $_POST["name"]
);

$newExercise = set_document_owner($newExercise, $_SESSION["user_id"]);


$database->create_document('exercises', $newExercise);

?>