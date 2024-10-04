<?php


function get_data_base_path() {
    
    // I recommend that the data is moved OUT of the web-accessible directories
    $DATA_BASE_PATH = __DIR__ . "/database/data";

    return $DATA_BASE_PATH;
}

?>