<?php

require_once __DIR__ . "/../../database/demand-db.php";
require_once __DIR__ . "/../../helpers/helpers.php";

http_method_must_be("POST");

validate_request_data($_POST, "username|string", "password|string");


// $db = new DemandDB();

// // Check the user exists
// $user = $db->get_documents("users")->where("username", "=", $_POST["username"])->first();

// if($user !== null) {
//     send_response(400, "User already exists");
// }


// // Register the user
// $user = array(
//     "username" => $_POST["username"],
//     "password" => password_hash($_POST["password"], PASSWORD_DEFAULT),
//     "created" => date("Y-m-d H:i:s")
// );

// $uid = $db->create_document("users", $user);

// echo $uid;

?>