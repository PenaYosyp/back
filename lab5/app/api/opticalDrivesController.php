<?php
    session_start();
    if(!isset($_SESSION['username'])) {
        echo '{"error":"Unauthorized"}';
        die();
    }
    require_once('../classes.php');
    $dl=new OpticalDriveList();
	$dl->importFromFile('../../data/opticalDrives.csv');
    echo $dl->convertToJSON();
    if(isset($_POST['name'])) {
        eval('$propsArray='.$_POST['properties'].';');
        $dl->add($_POST['name'], $_POST['vendor'], 
        $_POST['category'], $_POST['price'], $propsArray);
        $dl->exportToFile('../../data/opticalDrives.csv');
    }
?>