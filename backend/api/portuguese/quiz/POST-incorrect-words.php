<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("POST");

validate_request_data($_POST, "incorrectWords");

must_be_authenticated();

safely_start_session();


// What should I do with the results?

$database = new DemandDB();

$words = $database->get_documents('portuguese_words')->withOwner($_SESSION["user_id"])->documents;

echo json_encode($words, JSON_PRETTY_PRINT);



?>