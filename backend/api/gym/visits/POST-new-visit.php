<?php

require_once __DIR__ . "/../../../database/demand-db.php";
require_once __DIR__ . "/../../../helpers/helpers.php";

http_method_must_be("POST");

must_be_authenticated();

safely_start_session();


$database = new DemandDB();

$date = date("d-m-Y");

// Is there already a visit for this date?
$visit = $database->get_documents("gym_visits")->where("date", "=", $date)->first();

if($visit) {
    echo $visit["id"];
    exit;
}

$newVisit = array(
    'date' => $date,
    'exercises' => array()
);

$newVisit = set_document_owner($newVisit, $_SESSION["user_id"]);


echo $database->create_document('gym_visits', $newVisit);

?>