<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";
require_once __DIR__ . "/../../../helpers/diagnostics.php";

http_method_must_be("GET");

must_be_authenticated();

validate_request_data($_GET, "collection_id|string");

safely_start_session();


$database = new DemandDB();

if(!in_array($_GET["collection_id"], $database->collections)) {
    send_response(404, "Invalid collection name");
}

$q_start = microtime(true);

$documents = $database->get_documents($_GET["collection_id"])->withOwner($_SESSION["user_id"])->documents;

$q_end = microtime(true);

log_operation("Get all words query", $q_end - $q_start);

echo json_encode($documents, JSON_PRETTY_PRINT);



?>