<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("POST");

validate_request_data($_POST, "words");

must_be_authenticated();

safely_start_session();


$database = new DemandDB();

$documents = [];

foreach ((array) json_decode($_POST["words"]) as $word) {

    $word = (array) $word;

    $newWord = array(
        "word" => $word["word"],
        'definition' => $word["definition"]
    );
    
    $newWord = set_document_owner($newWord, $_SESSION["user_id"]);

    $documents[] = $newWord;
}

$database->batch_create_documents('portuguese_words', $documents);

?>