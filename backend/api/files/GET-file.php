<?php

    require_once __DIR__ . "/../../database/demand-db.php";
    require_once __DIR__ . "/../../database/demand-files.php";
    require_once __DIR__ . "/../../helpers/helpers.php";

    http_method_must_be("GET");

    validate_request_data($_GET, "id|string");

    must_be_authenticated();

    safely_start_session();

    $database = new DemandDB();

    $fileNodeDocument = $database->get_document("file_nodes", $_GET["id"]);

    if(!$fileNodeDocument) {
        send_response(404, "Invalid actual file ID");
    }

    $fileDocument = $database->get_document("files", $fileNodeDocument["file_id"]);

    if(!$fileDocument) {
        send_response(404, "Invalid actual file ID");
    }

    must_be_document_owner($fileDocument);

    $fileController = new DemandFiles();

    $fileController->download_file($fileDocument);

?>