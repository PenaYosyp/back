<?php
    session_start();
    if(!isset($_SESSION['username']))
        header('Location: login.php');
    require_once('../app/classes.php');
    $pl=new PropertyList();
	$pl->importFromFile('../data/properties.csv');
    if(isset($_POST['name'])) {
        $pl->add($_POST['name'], $_POST['units']);
        $pl->exportToFile('../data/properties.csv');
    }
?>
<html>
    <head>
        <title>Properties List</title>
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
                    <h1>Властивості</h1>
                    <table>
                        <thead>
                            <th>ID</th>
                            <th>Назва</th>
                            <th>Одиниці вимірювання</th>
                        </thead>
                        <tbody>
                            <?php echo $pl->getTable();?>
                        </tbody>
                    </table>
            </div>
            <div class='form-content'>
                <form method="POST">
                    <p><input type="text" placeholder="Назва" name="name" required/></p>
                    <p><input type="text" placeholder="Одиниці вимірювання" name="units" required/></p>
                    <p><button type="submit">Зберегти</button></p>
                </form>
            </div>
        </div>
    </body>
</html>