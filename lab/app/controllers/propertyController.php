<?php
  session_start();
    if(!isset($_SESSION['username'])) {
        echo '{"error":"Unauthorized"}';
        die();
    }
    require_once('../db_connect.php');
    require_once('../models/PropertyList.php');
    $pl = new PropertyList($conn);
    if($_SERVER['REQUEST_METHOD']=="GET") {
        if(isset($_GET['id'])) {
            $property = $pl->getFromDatabaseById($_GET['id']);
            echo json_encode($property, JSON_UNESCAPED_UNICODE);
        } else {
            $pl->getAllFromDatabase();
            echo $pl->convertToJSON();
         }
    }
	
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody);
        $pl->insertIntoDatabase($data->name, $data->units);
        echo '{"status":"success"}';
    }
    if($_SERVER['REQUEST_METHOD'] == "PUT") {
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody);
        $pl->updateDatabaseById($data->id, $data->name, $data->units);
        echo '{"status":"success"}';
    }
    if($_SERVER['REQUEST_METHOD'] == "DELETE") {
        $pl->deleteFromDatabase($_REQUEST['id']);
        echo '{"status":"success"}';
    }
?>