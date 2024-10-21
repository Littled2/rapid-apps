<?php

    require_once __DIR__ . "/../../database/demand-db.php";
    require_once __DIR__ . "/../../database/demand-files.php";
    require_once __DIR__ . "/../../helpers/helpers.php";

    http_method_must_be("POST");

    validate_request_data($_POST, "name|string", "parent");

    must_be_authenticated();

    safely_start_session();

    $database = new DemandDB();

    $database->create_document("file_nodes", array(
        "name" => $_POST["name"],
        "directory" => true,
        "user_id" => $_SESSION["user_id"],
        "parent" => $_POST["parent"] !== "null" ? $_POST["parent"] : null
    ));

?>