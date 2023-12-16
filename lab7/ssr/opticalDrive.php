<?php

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
session_start();
    if(!isset($_SESSION['username'])){
        header('Location: login.php');
    }
    require_once('../app/db_connect.php');
    require_once('../app/models/opticalDriveList.php');
    require_once('../app/models/CategoryList.php');
    require_once('../app/models/PropertyList.php');
    $dl = new OpticalDriveList($conn);
	$opticalDrive=$dl->getFromDatabaseById($_GET['id']);
    $pl=new PropertyList($conn);
	$pl->getAllFromDatabase();
    $cl=new CategoryList($conn);
	$cl->getAllFromDatabase();
    $opticalDriveProps=$dl->getOpticalDrivePropertiesById($_GET['id']);
    //print_r($opticalDriveProps);
    if(isset($_POST['name'])){
        $dl->updateDatabaseById($_POST['id'],$_POST['name'],$_POST['vendor'],$_POST['price'],$_POST['category']);
        $propsArray=$pl->getDataAsArray();
        for ($i=0;$i<count($propsArray);$i++){
            $dl->refreshOpticalDriveProperty($_POST['id'],$propsArray[$i]['id'],$_POST['prop_'.$propsArray[$i]['id']]);
        }
        header('Location:opticalDrives.php');
    }
?>
<html>
    <head>
        <title>Optical Drives List</title>
        <link href="../assets/style.css" rel="stylesheet" />
    </head>
    <body>
        <div class='container'>
            <div class='navigation'>
                <ul>
                    <li><a href="opticalDrives.php">Оптичні приводи</a></li>
                    <li><a href="categories.php">Категорії</a></li>
                    <li><a href="properties.php">Властивості</a></li>
                    <li><a href="logout.php">Вийти</a></li>
                </ul>
            </div>
            
            <div class='form-content'>
                <form method="POST">
                    <p><input value="<?php echo $opticalDrive['name'];?>" type="text" placeholder="Модель" name="name" required/></p>
                    <p><input value="<?php echo $opticalDrive['vendor'];?>" type="text" placeholder="Виробник" name="vendor" required/></p>
                    <p><?php echo $cl->getDataAsSelectWithSelectedOption($opticalDrive['category_id']); ?></p>
                    <p><input value="<?php echo $opticalDrive['price'];?>" type="number" placeholder="Ціна" name="price" required/></p>
                    <?php echo $pl->getDataAsInputBlockWithValues($opticalDriveProps); ?>
                    <p><input value="<?php echo $opticalDrive['id'];?>" type="hidden" name="id" required/></p>
                    <p><button type="submit">Зберегти</button></p>
                </form>
            </div>
            <div></div>
        </div>
    </body>
</html>