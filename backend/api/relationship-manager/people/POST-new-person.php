<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("POST");

validate_request_data($_POST, "name|string", "info", "lastMeaningfulDate");

safely_start_session();

$database = new DemandDB();

$newPerson = array(
    "name" => $_POST["name"],
    "info" => $_POST["info"],
    'lastMeaningfulDate' => $_POST['lastMeaningfulDate']
);

$newPerson = set_document_owner($newPerson, $_SESSION["user_id"]);

echo $database->create_document('people', $newPerson);

?>