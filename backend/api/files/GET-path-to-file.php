<?php

    require_once __DIR__ . "/../../database/demand-db.php";
    require_once __DIR__ . "/../../database/demand-files.php";
    require_once __DIR__ . "/../../helpers/helpers.php";

    http_method_must_be("GET");

    validate_request_data($_GET, "id|string");

    must_be_authenticated();

    safely_start_session();

    $database = new DemandDB();

    $all_files = $database->get_collection_data("file_nodes");

    // To avoid someone being able to probe if a given ID is valid, even if not the document owner
    if(!isset($all_files[$_GET["id"]]) || $all_files[$_GET["id"]]["user_id"] !== $_SESSION["user_id"]) {
        send_response(400, "No");
    }

    $activeFile = $all_files[$_GET["id"]];

    // Because why not anyways
    must_be_document_owner($activeFile);

    $path = [];

    $path[] = array(
        "name" => $activeFile["name"],
        "id" => $_GET["id"]
    );

    while ($activeFile["parent"] !== null) {

        $parentID = $activeFile["parent"];

        if(!isset($all_files[$parentID])) {
            send_response(500, "This file is missing an ancestor");
        }

        $activeFile = $all_files[$parentID];

        $path[] = array(
            "name" => $activeFile["name"],
            "id" => $parentID
        );
    }

    echo json_encode(array_reverse($path), JSON_PRETTY_PRINT);

?>