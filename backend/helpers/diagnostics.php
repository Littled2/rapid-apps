<?php

require_once __DIR__ . "/../database/demand-db.php";

function log_operation($name, $time) {

        $database = new DemandDB();

        $database->create_document("diagnostics", array(
            "name" => $name,
            "time" => $time,
            "file" => basename($_SERVER['SCRIPT_NAME']),
            "user_id" => "admin"
        ));
}

?>