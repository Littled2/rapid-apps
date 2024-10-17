<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("POST");

validate_request_data($_POST, "results", "quizName|string");

must_be_authenticated();

safely_start_session();


$database = new DemandDB();

$results = (array) json_decode($_POST["results"]);

$document = array(
    "quizName" => $_POST["quizName"],
    "results" => $results
);

$document = set_document_owner($document, $_SESSION["user_id"]);

$id = $database->create_document('quiz_results', $document);

echo $id;



?>