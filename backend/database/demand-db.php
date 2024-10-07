<?php

require_once __DIR__ . "/../environment.php";
require_once __DIR__ . "/../helpers/helpers.php";


class DocumentList {

    public $documents;

    function __construct($docs) {
        $this->documents = $docs;
    }

    function withOwner($userID) {
        return $this->where("user_id", "=", $userID);
    }

    function where($property, $operator, $value) {

        $filteredDocuments = array_filter($this->documents, function($doc) use ($property, $operator, $value) {

            if(!array_key_exists($property, $doc)) return false;

            $docValue = $doc[$property];

            switch ($operator) {
                case "=":
                    return $docValue == $value;
                case "!=":
                    return $docValue != $value;
                case ">":
                    return $docValue > $value;
                case "<":
                    return $docValue < $value;
                case ">=":
                    return $docValue >= $value;
                case "<=":
                    return $docValue <= $value;
                default:
                    return false; // Unknown operator
            }
        });

        return new DocumentList($filteredDocuments);
    }

    function sort_by($property, $order) {

        if($order !== "asc" && $order !== "desc") {
            echo "Second argument of sort_by() must be either 'asc' or 'desc'";
            exit;
        }

        usort($this->documents, function($a, $b) use ($property, $order) {
            if ($order === "asc") {
                return ($a[$property] <=> $b[$property]);
            } else {
                return ($b[$property] <=> $a[$property]);
            }
        });

        return new DocumentList($this->documents);
    }

    function first() {

        if(count($this->documents) === 0) {
            return null;
        }
        
        return $this->documents[0];
    }
}


class DemandDB {

    public $rootPath;
    public $collections = [];
  
    function __construct() {

        $this->rootPath = get_data_base_path();

        $jsonFiles = glob($this->rootPath . "/*.json");

        foreach ($jsonFiles as $file) {
            $this->collections[] = basename($file, ".json");
        }
    }

    private function create_collection_if_not_exists($collection) {
        if(!in_array($collection, $this->collections)) {
            // Collection does not exist, create the collection
            file_put_contents($this->rootPath . "/" . $collection . ".json", "{}");
        }
    }

    function get_collection_data($collection) {

        $this->create_collection_if_not_exists($collection);

        $jsonData = file_get_contents($this->rootPath . "/" . $collection . ".json");
        $parsedData = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(500);
            echo "Error parsing collection " . $collection;
            exit;
        }

        return $parsedData;
    }

    function set_collection_data($collection, $collectionData) {
        $parsedData = json_encode($collectionData, JSON_PRETTY_PRINT);
        file_put_contents($this->rootPath . "/" . $collection . ".json", $parsedData);
    }

    function set_document($collection, $id, $document) {
        $collectionData = $this->get_collection_data($collection);
        $collectionData[$id] = $document;
        $this->set_collection_data($collection, $collectionData);
    }

    function update_document($collection, $id, $updated) {
        $collectionData = $this->get_collection_data($collection); 

        // Does the document exist
        if(!isset($collectionData[$id])) {
            send_response(400, "Document does not exist");
        }
        
        $collectionData[$id] = array_merge($collectionData[$id], $updated);
        $this->set_collection_data($collection, $collectionData);
    }

    function create_document($collection, $newDocument) {

        // Add created_at
        $date = new DateTime();
        $newDocument["created_at"] = $date->getTimestamp();

        $id = uniqid();
        $collectionData = $this->get_collection_data($collection);        
        $collectionData[$id] = $newDocument;
        $this->set_collection_data($collection, $collectionData);
        return $id;
    }

    function batch_create_documents($collection, $newDocuments) {

        $collectionData = $this->get_collection_data($collection);      
        
        $date = new DateTime();
        $timestamp = $date->getTimestamp();

        foreach ($newDocuments as $document) {
            $document["created_at"] = $timestamp;
            $id = uniqid();
            $collectionData[$id] = $document;
        }

        $this->set_collection_data($collection, $collectionData);

        return $id;
    }

    function delete_document($collection, $id) {
        $collectionData = $this->get_collection_data($collection);
        unset($collectionData[$id]);
        $this->set_collection_data($collection, $collectionData);
    }

    function get_documents($collection) {
        $collectionDataRaw = $this->get_collection_data($collection);
        $collectionData = [];

        foreach ($collectionDataRaw as $key => $value) {
            $newElement = ['id' => $key] + $value;
            $collectionData[] = $newElement;
        }
        
        return new DocumentList($collectionData);
    }

    function get_document($collection, $id) {
        $collectionData = $this->get_collection_data($collection);
        if(!array_key_exists($id, $collectionData)) return null;
        $collectionData[$id]["id"] = $id;
        return $collectionData[$id];
    }
}
  
?>