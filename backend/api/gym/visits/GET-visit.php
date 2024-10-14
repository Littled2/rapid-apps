<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("GET");

validate_request_data($_GET, "id|string");

must_be_authenticated();

safely_start_session();




$database = new DemandDB();

$visit = $database->get_document('gym_visits', $_GET["id"]);

must_be_document_owner($visit);

echo json_encode($visit, JSON_PRETTY_PRINT);



?>