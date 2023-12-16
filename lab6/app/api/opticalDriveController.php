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
    if($_SERVER['REQUEST_METHOD']=="GET"&&!isset($_GET['id'])){
        $dl->getAllFromDatabase();
        echo $dl->convertToJSON();
    }
    if($_SERVER['REQUEST_METHOD']=="GET"&&isset($_GET['id'])){
        $record=$dl->getFromDatabaseById($_GET['id']);
        $record['properties']=$dl->getopticalDrivePropertiesById($_GET['id']);
        echo json_encode($record,JSON_UNESCAPED_UNICODE);
    }
    
    if(isset($_POST['name'])){
        $opticalDriveId=$dl->insertIntoDatabase($_POST['name'],$_POST['vendor'],$_POST['price'],$_POST['category']);       
        $propsArray=$pl->getDataAsArray();
        echo json_encode($propsArray);
        for($i=0;$i<count($propsArray);$i++){
            $dl->addopticalDriveProperty($opticalDriveId,$propsArray[$i]['id'],$_POST['prop_'.$propsArray[$i]['id']]);
        }
    }
    if($_SERVER['REQUEST_METHOD']=="DELETE"){
        $dl->deleteFromDatabase($_REQUEST['id']);
        echo '{"status":"success"}';
    }
    if($_SERVER['REQUEST_METHOD']=="PUT"){
        $data = json_decode( file_get_contents('php://input') );
        $dl->updateDatabaseById($data->id,$data->name,$data->vendor,$data->price,$data->category);
        $propsArray=$pl->getDataAsArray();
        for ($i=0;$i<count($propsArray);$i++){
            $dl->refreshopticalDriveProperty($data->id,$propsArray[$i]['id'],$data->{'prop_'.$propsArray[$i]['id']});
        }
        echo json_encode($propsArray);
        //echo '{"status":"success"}';
    }
    
?>