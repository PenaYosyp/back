<?php
    session_start();
    if(!isset($_SESSION['username'])){
        echo '{"error":"Unauthorized"}';
        die();
    }
    require_once('../db_connect.php');
    require_once('../models/OpticalDriveList.php');
    require_once('../models/PropertyList.php');
    $pl = new PropertyList($conn);
    $pl->getAllFromDatabase();
    $dl = new OpticalDriveList($conn);
    if($_SERVER['REQUEST_METHOD']=="GET"){
        if (isset($_GET['id'])){
            $opticalDrive=$bl->getFromDatabaseById($_GET['id']);
            $opticalDrive['properties']=$bl->getopticalDrivePropertiesById($_GET['id']);
            echo json_encode($opticalDrive,JSON_UNESCAPED_UNICODE);
        } else{
            $bl->getAllFromDatabase();
            echo $bl->convertToJSON();
         }
    }
    require_once('../models/PropertyList.php');
    $pl=new PropertyList($conn);
	$pl->getAllFromDatabase();
    //echo $bl->convertToJSON();
    if($_SERVER['REQUEST_METHOD']=="POST"){
        $requestBody= file_get_contents('php://input');
        $data=json_decode($requestBody);
        $opticalDriveId=$bl->insertIntoDatabase($data->name,$data->vendor,$data->price,$data->category);       
        $propsArray=$pl->getDataAsArray();
        for($i=0;$i<count($propsArray);$i++){
            $bl->addopticalDriveProperty($opticalDriveId,$propsArray[$i]['id'],$data->{'prop_'.$propsArray[$i]['id']});
        }
        echo '{"status":"success"}';
    }

    if($_SERVER['REQUEST_METHOD']=="PUT"){
        $requestBody= file_get_contents('php://input');
        $data=json_decode($requestBody);
        $bl->updateDatabaseById($data->id,$data->name,$data->vendor,$data->price,$data->category);
        $propsArray=$pl->getDataAsArray();
        for ($i=0;$i<count($propsArray);$i++){
            $bl->refreshopticalDriveProperty($data->id,$propsArray[$i]['id'],$data->{'prop_'.$propsArray[$i]['id']});
        }
        echo '{"status":"success"}';
    }
    if($_SERVER['REQUEST_METHOD']=="DELETE"){
        $bl->deleteFromDatabase($_REQUEST['id']);
        echo '{"status":"success"}';
    }
?>