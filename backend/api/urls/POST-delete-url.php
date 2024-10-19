<?php

require_once __DIR__ . "/../../database/demand-db.php";
require_once __DIR__ . "/../../helpers/helpers.php";

http_method_must_be("POST");

must_be_authenticated();

safely_start_session();

validate_request_data($_POST, "id|string");

$database = new DemandDB();

$link = $database->get_document("urls", $_POST["id"]);

if(!$link) {
    send_response(404, "ID is invalid");
}

$database->delete_document("urls", $_POST["id"]);

?>