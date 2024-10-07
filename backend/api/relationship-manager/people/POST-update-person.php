<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("POST");

validate_request_data($_POST, "name|string", "info|string", "lastMeaningfulDate|string", "id|string");


$database = new DemandDB();

$document = $database->get_document('people', $_POST["id"]);

must_be_document_owner($document);

$database->update_document('people', $_POST['id'], array(
    "name" => $_POST["name"],
    "info" => $_POST["info"],
    "lastMeaningfulDate" => $_POST["lastMeaningfulDate"],
));

?>