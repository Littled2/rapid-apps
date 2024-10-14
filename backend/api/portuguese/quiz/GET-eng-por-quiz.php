<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("GET");

// if order < or = to 0, then go English -> Portuguese
validate_request_data($_GET, "questions|number");

must_be_authenticated();

safely_start_session();







// Thanks Chat GPT

function generateQuiz($data, $maxQuestions = 15) { 
    shuffle($data);

    $quiz = [];
    $totalQuestions = min($maxQuestions, count($data)); // Determine the number of questions to use

    // Randomly select up to $maxQuestions items from the dataset
    $randomKeys = array_rand($data, $totalQuestions); 
    $randomKeys = (array) $randomKeys; // Ensure $randomKeys is an array in case of a single element

    $allDefinitions = array_column($data, 'definition'); // Extract all definitions from the quiz data

    foreach ($randomKeys as $key) {
        $item = $data[$key];
        $correctAnswer = $item['definition']; // The correct answer is now the Portuguese word
        $incorrectAnswers = generateIncorrectAnswers($correctAnswer, $allDefinitions);
        $options = array_merge([$correctAnswer], $incorrectAnswers);
        shuffle($options); // Shuffle options

        $quiz[] = [
            "id" => $item["id"],
            "question" => $item['word'], // The word in English
            "options" => $options,
            "correctAnswer" => $correctAnswer
        ];
    }

    return $quiz;
}

// Function to generate incorrect answers from the same definition list using array_rand()
function generateIncorrectAnswers($correctAnswer, $allDefinitions) {
    $filteredDefinitions = array_diff($allDefinitions, [$correctAnswer]); // Remove the correct answer
    $randomKeys = array_rand($filteredDefinitions, 3); // Pick 3 random keys from the filtered definitions

    $incorrectAnswers = [];
    foreach ($randomKeys as $key) {
        $incorrectAnswers[] = $filteredDefinitions[$key]; // Get the random words
    }

    return $incorrectAnswers;
}



$database = new DemandDB();

$words = $database->get_documents('portuguese_words')->withOwner($_SESSION["user_id"])->documents;

$quiz = generateQuiz($words, $_GET["questions"]);

echo json_encode($quiz, JSON_PRETTY_PRINT);



?>