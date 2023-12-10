<?php
    session_start();
    if(!isset($_SESSION['username']))
        header('Location: login.php');
    require_once('../app/classes.php');
    $cl=new CategoryList();
	$cl->importFromFile('../data/categories.csv');
    if(isset($_POST['name'])) {
        $cl->add($_POST['name']);
        $cl->exportToFile('../data/categories.csv');
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
            <div class='table-content'>
                    <h1>Категорії</h1>
                    <table>
                        <thead>
                            <th>ID</th>
                            <th>Назва</th>
                        </thead>
                        <tbody>
                            <?php echo $cl->getTable();?>
                        </tbody>
                    </table>
            </div>
            <div class='form-content'>
                <form method="POST">
                    <p><input type="text" placeholder="Назва" name="name" required/></p>
                    <p><button type="submit">Зберегти</button></p>
                </form>
            </div>
        </div>
    </body>
</html>