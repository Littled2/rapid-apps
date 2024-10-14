<?php

require_once __DIR__ . "/../../database/demand-db.php";
require_once __DIR__ . "/../../helpers/helpers.php";

http_method_must_be("POST");

validate_request_data($_POST, "token|string", "username|string");


$db = new DemandDB();

// Check the user exists
$user = $db->get_documents("users")->where("username", "=", $_POST["username"])->first();

if($user === null) {
    send_response(400, "User does not exist");
}

// Check there is a token
if(!isset($user["token"])) {
    send_response(403, "User does not have a token");
}

// Check the token
if($_POST["token"] !== $user["token"]) {
    send_response(403, "Incorrect token");
}

// Create a new token and log the user in
$token = bin2hex(random_bytes(16));

$db->update_document("users", $user["id"], array(
    "token" => $token
));

$_SESSION["username"] = $user["username"];
$_SESSION["user_id"] = $user["id"];

echo json_encode(
    array(
        "username" => $user["username"],
        "token" => $token
    ),
    JSON_PRETTY_PRINT
)

?>