<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("GET");

validate_request_data($_GET, "id|string");


$database = new DemandDB();

$exercise = $database->get_document("exercises", $_GET["id"]);

must_be_document_owner($exercise);

echo json_encode($exercise, JSON_PRETTY_PRINT);



?>