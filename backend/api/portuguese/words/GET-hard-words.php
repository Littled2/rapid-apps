<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("GET");

must_be_authenticated();

safely_start_session();







// Thank you Chat GPT
function getWordsSortedByDifficulty($quizData, $allWords) {
    $difficultWords = [];

    foreach ($quizData as $quiz) {
        foreach ($quiz['results'] as $wordID => $attempts) {
            if ($attempts > 0) {
                if(isset($difficultWords[$wordID])) {
                    $difficultWords[$wordID] += $attempts;
                } else {
                    $difficultWords[$wordID] = $attempts;
                }
            }
        }
    }

    $words = [];

    foreach ($difficultWords as $difficultWords) {
        $words[] = array(
            "word" => $difficultWords
        );
    }


    // Sort the words by the number of attempts in descending order
    arsort($difficultWords);

    return $difficultWords;
}





$database = new DemandDB();

$results = $database->get_documents('quiz_results')->withOwner($_SESSION["user_id"])->documents;

$words = $database->get_collection_data('quiz_results');



echo var_dump(getWordsSortedByDifficulty($results, $words));

?>