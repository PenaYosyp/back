<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    session_start();
    require_once('../db_connect.php');
    require_once('../models/CategoryList.php');
    $cl=new CategoryList($conn);
	
    if($_SERVER['REQUEST_METHOD']=="GET"){
        if (isset($_GET['id'])){
            $category=$cl->getFromDatabaseById($_GET['id']);
            echo json_encode($category,JSON_UNESCAPED_UNICODE);
        } else{
            $cl->getAllFromDatabase();
            echo $cl->convertToJSON();
         }
    }
    if($_SERVER['REQUEST_METHOD']=="POST"){
        $requestBody= file_get_contents('php://input');
        $data=json_decode($requestBody);
        $cl->insertIntoDatabase($data->name);
        echo '{"status":"success"}';
    }
    if($_SERVER['REQUEST_METHOD']=="PUT"){
        $requestBody= file_get_contents('php://input');
        $data=json_decode($requestBody);
        $cl->updateDatabaseById($data->id,$data->name);
        echo '{"status":"success"}';
    }
    if($_SERVER['REQUEST_METHOD']=="DELETE"){
        $cl->deleteFromDatabase($_REQUEST['id']);
        echo '{"status":"success"}';
    }
    
?>