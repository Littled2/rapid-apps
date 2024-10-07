<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("GET");

validate_request_data($_GET, "id|string");

must_be_authenticated();




$database = new DemandDB();

$document = $database->get_document('people', $_GET["id"]);

must_be_document_owner($document);

echo json_encode($document, JSON_PRETTY_PRINT);


?>