<?php

    require_once __DIR__ . "/../../database/demand-db.php";
    require_once __DIR__ . "/../../database/demand-files.php";
    require_once __DIR__ . "/../../helpers/helpers.php";

    http_method_must_be("POST");

    validate_request_data($_POST, "parent");

    must_be_authenticated();

    safely_start_session();

    $database = new DemandDB();

    $fileController = new DemandFiles();

    if (empty($_FILES['files']['name'][0])) {
        send_response(400, "No files submitted");
    }

    $files = parse_files("files");

    foreach($files as $file) {

        $fileID = $fileController->upload_file($file);

        $database->create_document("file_nodes", array(
            "name" => $file["name"],
            "file_id" => $fileID,
            "directory" => false,
            "mime_type" => mime_type(strtolower(pathinfo($file["name"], PATHINFO_EXTENSION))),
            "user_id" => $_SESSION["user_id"],
            "parent" => $_POST["parent"] !== "null" ? $_POST["parent"] : null
        ));
    }

?>