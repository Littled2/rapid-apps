<?php

require_once __DIR__ . "/../../database/demand-db.php";
require_once __DIR__ . "/../../helpers/helpers.php";

http_method_must_be("GET");

must_be_authenticated();

safely_start_session();

$database = new DemandDB();

$urls = $database->get_documents('urls')->withOwner($_SESSION["user_id"])->sort_by("created_at", "asc")->documents;

echo json_encode($urls, JSON_PRETTY_PRINT);
?>