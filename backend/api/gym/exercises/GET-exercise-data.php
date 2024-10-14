<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("GET");

// validate_request_data($_GET, "id|string");

must_be_authenticated();

safely_start_session();


$database = new DemandDB();

$visits = $database->get_documents("gym_visits")->withOwner($_SESSION["user_id"])->sort_by("created_at", "asc")->documents;


// Chat GPT for the win


// Find all unique exercise IDs
$allExerciseIDs = [];

// Loop through each gym visit to collect all exercise IDs
foreach ($visits as $visit) {
    foreach ($visit['exercises'] as $exercise) {
        $exerciseID = $exercise['exerciseID'];
        if (!in_array($exerciseID, $allExerciseIDs)) {
            $allExerciseIDs[] = $exerciseID;
        }
    }
}

// Initialize an empty array to store exercise weights over time
$exerciseWeights = [];

// Loop through each gym visit
foreach ($visits as $visit) {
    $date = $visit['date'];
    
    // Create a map of exercises for this visit
    $visitExercises = [];
    foreach ($visit['exercises'] as $exercise) {
        $visitExercises[$exercise['exerciseID']] = $exercise['weight'];
    }
    
    // Ensure every exercise ID has an entry, even if it's missing
    foreach ($allExerciseIDs as $exerciseID) {
        $weight = isset($visitExercises[$exerciseID]) ? $visitExercises[$exerciseID] : null; // Use null or 0 if no weight recorded
        $exerciseWeights[$exerciseID][] = [
            'date' => $date,
            'weight' => $weight
        ];
    }
}

echo json_encode($exerciseWeights, JSON_PRETTY_PRINT);



?>