<?php

require_once __DIR__ . "/../../database/demand-db.php";
require_once __DIR__ . "/../../helpers/helpers.php";

http_method_must_be("GET");

validate_request_data($_GET, "id|string");


$database = new DemandDB();

$link = $database->get_document("urls", $_GET["id"]);

if(!$link) {
    send_response(404, "URL ID is invalid");
}

// Record the link
$database->update_document("urls", $_GET["id"], array(
    "uses" => $link["uses"] + 1
));

// Redirect the user
header('Location: ' . $link["url"]);

exit;

?>