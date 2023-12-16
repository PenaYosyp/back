<?php
    session_start();
    if(!isset($_SESSION['username'])) {
        echo '{"error":"Unauthorized"}';
        die();
    }
    require_once('../db_connect.php');
    require_once('../models/OpticalDriveList.php');
    require_once('../models/PropertyList.php');
    $pl = new PropertyList($conn);
    $pl->getAllFromDatabase();
    $dl = new OpticalDriveList($conn);
    if($_SERVER['REQUEST_METHOD'] == "GET") {
        if(isset($_GET['id'])) {
            $opticalDrive = $dl->getFromDatabaseById($_GET['id']);
            $opticalDrive['properties'] = $dl->getopticalDrivePropertiesById($_GET['id']);
            echo json_encode($opticalDrive, JSON_UNESCAPED_UNICODE);
        } else {
            $dl->getAllFromDatabase();
            echo $dl->convertToJSON();
         }
    }
    require_once('../models/PropertyList.php');
    $pl = new PropertyList($conn);
	$pl->getAllFromDatabase();
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody);
        $opticalDriveId = $dl->insertIntoDatabase($data->name, $data->vendor, $data->price, $data->category);       
        $propsArray = $pl->getDataAsArray();
        for($i=0; $i<count($propsArray); $i++)
            $dl->addopticalDriveProperty($opticalDriveId, $propsArray[$i]['id'], $data->{'prop_'.$propsArray[$i]['id']});
        echo '{"status":"success"}';
    }

    if($_SERVER['REQUEST_METHOD'] == "PUT") {
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody);
        $dl->updateDatabaseById($data->id, $data->name, $data->vendor, $data->price, $data->category);
        $propsArray = $pl->getDataAsArray();
        for($i=0; $i<count($propsArray); $i++)
            $dl->refreshopticalDriveProperty($data->id, $propsArray[$i]['id'], $data->{'prop_'.$propsArray[$i]['id']});
        echo '{"status":"success"}';
    }
    if($_SERVER['REQUEST_METHOD'] == "DELETE") {
        $dl->deleteFromDatabase($_REQUEST['id']);
        echo '{"status":"success"}';
    }
?>