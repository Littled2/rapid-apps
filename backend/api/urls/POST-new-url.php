<?php

require_once __DIR__ . "/../../database/demand-db.php";
require_once __DIR__ . "/../../helpers/helpers.php";

http_method_must_be("POST");

must_be_authenticated();

safely_start_session();

validate_request_data($_POST, "url|string", "name|string");

$database = new DemandDB();

$newLink = array(
    "name" => $_POST["name"],
    "url" => $_POST["url"],
    "uses" => 0
);

$newLink = set_document_owner($newLink, $_SESSION["user_id"]);

echo $database->create_document("urls", $newLink);


?>