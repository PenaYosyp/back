<?php
    session_start();
    if(!isset($_SESSION['username']))
        header('Location: login.php');
    require_once('../app/db_connect.php');
    require_once('../app/models/PropertyList.php');
    $pl = new PropertyList($conn);
    if(!isset($_GET['search']))
        $pl->getAllFromDatabase();
    else $pl->getAllFromDatabaseBySearchCriteria($_GET['search']);
    if(isset($_POST['action']) && $_POST['action'] == 'delete') {
        $pl->deleteFromDatabase($_POST['id']);
        $pl = new PropertyList($conn);
	    $pl->getAllFromDatabase();
    }
    if(isset($_POST['name'])) {
        $pl->insertIntoDatabase($_POST['name'],$_POST['units']);
        $pl = new PropertyList($conn);
	    $pl->getAllFromDatabase();
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
                    <h1>Властивості</h1>
                    <table id="dataTable">
                        <thead>
                            <th>ID</th>
                            <th>Назва</th>
                            <th>Одиниці вимірювання</th>
                            <th>Дії</th>
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