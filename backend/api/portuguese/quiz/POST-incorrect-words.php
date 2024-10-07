<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("POST");

echo var_dump($_POST);

validate_request_data($_POST, "incorrectWords");

must_be_authenticated();

safely_start_session();


var_dump(json_decode($_POST["incorrectWords"]));

// What should I do with the results?

$database = new DemandDB();

$result = json_decode($_POST["incorrectWords"]);

set_document_owner($result, $_SESSION["user_id"]);

$id = $database->create_document('quiz_results', $result);

echo $id;



?>