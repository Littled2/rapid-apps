<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("POST");

validate_request_data($_POST, "id|string");


$database = new DemandDB();

$document = $database->get_document('portuguese_words', $_POST["id"]);

must_be_document_owner($document);

$database->delete_document('portuguese_words', $_POST["id"]);

?>