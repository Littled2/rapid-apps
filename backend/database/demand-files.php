<?php

require_once __DIR__ . "/demand-db.php";
require_once __DIR__ . "/../helpers/helpers.php";


class DemandFiles {

    private $rootPath;
    private $database;
  
    function __construct() {

        $this->rootPath = get_files_base_path();

        $this->database = new DemandDB();

    }

    function upload_file($file) {

        must_be_authenticated();
        safely_start_session();

        // Create a new document
        $newFile = array(
            "name" => basename($file["name"]),
            "type" => strtolower(pathinfo($file["name"], PATHINFO_EXTENSION)),
            "mime_type" => mime_type(strtolower(pathinfo($file["name"], PATHINFO_EXTENSION)))
        );

        $newFile = set_document_owner($newFile, $_SESSION["user_id"]);

        $newFileID = $this->database->create_document("files", $newFile);

        if(!move_uploaded_file($file["tmp_name"], $this->rootPath . "/" . $newFileID)) {
            send_response(500, "Error uploading file");
        }

        return $newFileID;
    }

    function download_file($fileDocument) {

        $pathname = $this->rootPath . "/" . $fileDocument["id"];

        if (!file_exists($pathname)) {
            send_response(500, "File does not exist in filesystem");
        }

        // Set headers
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$fileDocument["name"].'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($pathname));
        
        // Clean output buffer
        ob_clean();
        flush();
        
        // Read the file and send it to the output buffer
        readfile($pathname);

        exit;
    }
}



function mime_type($type) {

    // Define a list of common file extensions and their corresponding MIME types
    $mime_types = array(
        "txt" => "text/plain",
        "htm" => "text/html",
        "html" => "text/html",
        "php" => "text/php",
        "css" => "text/css",
        "js" => "application/javascript",
        "json" => "application/json",
        "xml" => "application/xml",
        "swf" => "application/x-shockwave-flash",
        "flv" => "video/x-flv",

        // Images
        "png" => "image/png",
        "jpe" => "image/jpeg",
        "jpeg" => "image/jpeg",
        "jpg" => "image/jpeg",
        "gif" => "image/gif",
        "bmp" => "image/bmp",
        "ico" => "image/vnd.microsoft.icon",
        "tiff" => "image/tiff",
        "tif" => "image/tiff",
        "svg" => "image/svg+xml",
        "webp" => "image/webp",

        // Archives
        "zip" => "application/zip",
        "rar" => "application/x-rar-compressed",
        "exe" => "application/x-msdownload",
        "msi" => "application/x-msdownload",
        "cab" => "application/vnd.ms-cab-compressed",

        // Audio/video
        "mp3" => "audio/mpeg",
        "qt" => "video/quicktime",
        "mov" => "video/quicktime",
        "mp4" => "video/mp4",
        "mkv" => "video/x-matroska",

        // Adobe
        "pdf" => "application/pdf",
        "psd" => "image/vnd.adobe.photoshop",
        "ai" => "application/postscript",
        "eps" => "application/postscript",
        "ps" => "application/postscript",

        // MS Office
        "doc" => "application/msword",
        "rtf" => "application/rtf",
        "xls" => "application/vnd.ms-excel",
        "ppt" => "application/vnd.ms-powerpoint",

        // OpenOffice
        "odt" => "application/vnd.oasis.opendocument.text",
        "ods" => "application/vnd.oasis.opendocument.spreadsheet"
    );

    // Return the MIME type or a default if the extension isn't found
    return isset($mime_types[$type]) ? $mime_types[$type] : 'application/octet-stream';
}



// Gets files in an array of objects from $_FILES
function parse_files($name="files") {

    $files = [];

    foreach ($_FILES[$name]['name'] as $key => $filename) {
        $files[] = array(
            "name" => $_FILES[$name]['name'][$key],
            "tmp_name" => $_FILES[$name]['tmp_name'][$key],
            "type" => $_FILES[$name]['type'][$key],
            "size" => $_FILES[$name]['size'][$key]
        );
    }

    return $files;
}
  
?>