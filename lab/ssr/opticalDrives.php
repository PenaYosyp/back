<?php
    session_start();
    if(!isset($_SESSION['username']))
        header('Location: login.php');
    require_once('../app/db_connect.php');
    require_once('../app/models/OpticalDriveList.php');
    require_once('../app/models/CategoryList.php');
    require_once('../app/models/PropertyList.php');
    $dl = new OpticalDriveList($conn);
    if(!isset($_GET['search']))
        $dl->getAllFromDatabase();
    else $dl->getAllFromDatabaseBySearchCriteria($_GET['search']);
    $pl = new PropertyList($conn);
	$pl->getAllFromDatabase();
    $cl = new CategoryList($conn);
	$cl->getAllFromDatabase();
    if(isset($_POST['action']) && $_POST['action'] == 'delete') {
        $dl->deleteFromDatabase($_POST['id']);
        $dl = new OpticalDriveList($conn);
	    $dl->getAllFromDatabase();
    }
    if(isset($_POST['name'])) {
        $opticalDriveId = $dl->insertIntoDatabase($_POST['name'], $_POST['vendor'], $_POST['price'], $_POST['category']);       
        $propsArray = $pl->getDataAsArray();
        for($i=0; $i<count($propsArray); $i++)
            $dl->addOpticalDriveProperty($opticalDriveId, $propsArray[$i]['id'], $_POST['prop_'.$propsArray[$i]['id']]);
        $dl = new OpticalDriveList($conn);
	    $dl->getAllFromDatabase();
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
                <form>
                    <input type="text" name="search" id="searchInput" required/>
                    <button type="submit">Пошук</button>
                </form>
                <ul>
                    <li><a href="opticalDrives.php">Оптичні приводи</a></li>
                    <li><a href="categories.php">Категорії</a></li>
                    <li><a href="properties.php">Властивості</a></li>
                    <li><a href="logout.php">Вийти</a></li>
                </ul>
            </div>
            <div class='table-content'>
                    <h1>Оптичні приводи</h1>
                    <table id="dataTable">
                        <thead>
                            <th>ID</th>
                            <th>Модель</th>
                            <th>Виробник</th>
                            <th>Ціна</th>
                            <th>Категорія</th>
                            <th>Характеристики</th>
                            <th>Дії</th>
                        </thead>
                        <tbody>
                            <?php echo $dl->getTable();?>
                        </tbody>
                    </table>
            </div>
            <div class='form-content'>
                <form method="POST">
                    <p><input type="text" placeholder="Модель" name="name" required/></p>
                    <p><input type="text" placeholder="Виробник" name="vendor" required/></p>
                    <p><?php echo $cl->getDataAsSelect(); ?></p>
                    <p><input type="number" placeholder="Ціна" name="price" required/></p>
                    <?php echo $pl->getDataAsInputBlock(); ?>
                    <p><button type="submit">Зберегти</button></p>
                </form>
            </div>
        </div>
       
    </body>
</html>