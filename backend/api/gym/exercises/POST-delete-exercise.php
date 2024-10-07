<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("POST");

validate_request_data($_POST, "id|string");


$database = new DemandDB();

$exercise = $database->get_document("exercises", $_POST["id"]);

must_be_document_owner($exercise);

$database->delete_document("exercises", $_POST["id"]);

echo json_encode($exercise, JSON_PRETTY_PRINT);

?>