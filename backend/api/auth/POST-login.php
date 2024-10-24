<?php

require_once __DIR__ . "/../../database/demand-db.php";
require_once __DIR__ . "/../../helpers/helpers.php";

http_method_must_be("POST");

validate_request_data($_POST, "username|string", "password|string");


$db = new DemandDB();

// Check the user exists
$user = $db->get_documents("users")->where("username", "=", $_POST["username"])->first();

if($user === null) {
    send_response(400, "User does not exist");
}

// Check the password
if(!password_verify($_POST["password"], $user["password"])) {
    send_response(403, "Incorrect password");

}

// Log the user in and create the SSO session

session_start();

$token = bin2hex(random_bytes(16));

$db->update_document("users", $user["id"], array(
    "token" => $token
));

$_SESSION["username"] = $user["username"];
$_SESSION["user_id"] = $user["id"];

echo json_encode(
    array(
        "token" => $token
    ),
    JSON_PRETTY_PRINT
)

?>