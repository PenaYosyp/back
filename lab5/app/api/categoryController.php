<?php
    session_start();
    if(!isset($_SESSION['username'])) {
        echo '{"error":"Unauthorized"}';
        die();
    }
    require_once('../classes.php');
    $cl=new CategoryList();
	$cl->importFromFile('../../data/categories.csv');
    echo $cl->convertToJSON();
    if(isset($_POST['name'])) {
        $cl->add($_POST['name']);
        $cl->exportToFile('../../data/categories.csv');
    }
?>