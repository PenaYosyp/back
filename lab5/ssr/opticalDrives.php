<?php
    session_start();
    if(!isset($_SESSION['username']))
        header('Location: login.php');
    require_once('../app/classes.php');
    $dl=new opticalDriveList();
	$dl->importFromFile('../data/opticalDrives.csv');
    $cl=new CategoryList();
	$cl->importFromFile('../data/categories.csv');
    if(isset($_POST['name'])) {
        eval('$propsArray='.$_POST['properties'].';');
        $dl->add($_POST['name'], $_POST['vendor'], 
        $_POST['category'], $_POST['price'], $propsArray);
        
        $dl->exportToFile('../data/opticalDrives.csv');
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
            <div class='table-content'>
                    <h1>Оптичні приводи</h1>
                    <table>
                        <thead>
                            <th>ID</th>
                            <th>Модель</th>
                            <th>Виробник</th>
                            <th>Категорія</th>
                            <th>Ціна</th>
                            <th>Характеристики</th>
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
                    <p><input type="text" placeholder="Ціна" name="price" required/></p>
                    <p><input type="text" placeholder="Характеристики" name="properties" required/></p>
                    <p><button type="submit">Зберегти</button></p>
                </form>
            </div>
        </div>
    </body>
</html>