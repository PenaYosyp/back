<?php
    session_start();
    if(!isset($_SESSION['username']))
        header('Location: login.php');
    require_once('../app/db_connect.php');
    require_once('../app/models/CategoryList.php');
    $cl = new CategoryList($conn);
    if(!isset($_GET['search']))
        $cl->getAllFromDatabase();
    else $cl->getAllFromDatabaseBySearchCriteria($_GET['search']);
    if(isset($_POST['action']) && $_POST['action']=='delete') {
        $cl->deleteFromDatabase($_POST['id']);
        $cl=new CategoryList($conn);
	    $cl->getAllFromDatabase();
    }
    if(isset($_POST['name'])) {
        $cl->insertIntoDatabase($_POST['name']);
        $cl = new CategoryList($conn);
	    $cl->getAllFromDatabase();
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
                    <h1>Категорії</h1>
                    <table>
                        <thead>
                            <th>ID</th>
                            <th>Назва</th>
                            <th>Дії</th>
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