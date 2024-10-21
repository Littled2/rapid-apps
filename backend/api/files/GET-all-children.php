<?php

    require_once __DIR__ . "/../../database/demand-db.php";
    require_once __DIR__ . "/../../database/demand-files.php";
    require_once __DIR__ . "/../../helpers/helpers.php";

    http_method_must_be("GET");

    validate_request_data($_GET, "parent|string");

    must_be_authenticated();

    safely_start_session();

    if($_GET["parent"] === "null") {
        $_GET["parent"] = null;
    }

    $database = new DemandDB();

    $files = $database
        ->get_documents("file_nodes")
        ->withOwner($_SESSION["user_id"])
        ->where("parent", "=", $_GET["parent"])
        ->sort_by("name", "asc")->documents;

    echo json_encode($files, JSON_PRETTY_PRINT);

?>