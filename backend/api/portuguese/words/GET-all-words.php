<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("GET");

must_be_authenticated();

safely_start_session();

$database = new DemandDB();

$words = $database->get_documents('portuguese_words')->withOwner($_SESSION["user_id"])->documents;

echo json_encode($words, JSON_PRETTY_PRINT);
?>