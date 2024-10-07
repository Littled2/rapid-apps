<?php

require_once __DIR__ . "/../../database/demand-db.php";
require_once __DIR__ . "/../../helpers/helpers.php";

http_method_must_be("GET");

must_be_authenticated();

safely_start_session();


$database = new DemandDB();

$user = $database->get_document("users", $_SESSION["user_id"]);

unset($user["password"]);

echo json_encode($user, JSON_PRETTY_PRINT);


?>