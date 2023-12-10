<?php
    session_start();
    if(!isset($_SESSION['username'])) {
        echo '{"error":"Unauthorized"}';
        die();
    }
    require_once('../classes.php');
    $pl=new PropertyList();
	$pl->importFromFile('../../data/properties.csv');
    echo $pl->convertToJSON();
    if(isset($_POST['name'])) {
        $pl->add($_POST['name'],$_POST['units']);
        $pl->exportToFile('../../data/properties.csv');
    }
?>