<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header('Location: login.php');
    }
    require_once('../app/db_connect.php');
    require_once('../app/models/CategoryList.php');
    $cl=new CategoryList($conn);
	$category=$cl->getFromDatabaseById($_GET['id']);
    
    if(isset($_POST['name'])){
        $cl->updateDatabaseById($_POST['id'],$_POST['name']);
        header('Location:categories.php');
    }
?>
<html>
    <head>
        <title>Categories List</title>
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
                    <p><input value='<?php echo $category['name'];?>' type="text" placeholder="Назва" name="name" required/></p>
                    <p><input value='<?php echo $category['id'];?>' type="hidden" name="id" required/></p>
                    <p><button type="submit">Зберегти</button></p>
                </form>
            </div>
            <div></div>
        </div>
    </body>
</html>