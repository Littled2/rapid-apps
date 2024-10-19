<?php

require_once __DIR__ . "/../../database/demand-db.php";
require_once __DIR__ . "/../../helpers/helpers.php";

http_method_must_be("POST");

must_be_authenticated();

safely_start_session();

validate_request_data($_POST, "url|string", "name|string", "id|string");

$database = new DemandDB();

$updated = array(
    "name" => $_POST["name"],
    "url" => $_POST["url"]
);

echo $database->update_document("urls", $_POST["id"], $updated);


?>