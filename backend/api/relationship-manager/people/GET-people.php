<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("GET");

must_be_authenticated();

safely_start_session();

$database = new DemandDB();

echo json_encode(
    $database->get_documents('people')
    ->withOwner($_SESSION["user_id"])
    ->sort_by("name", "asc")->documents,    
    JSON_PRETTY_PRINT
);


?>