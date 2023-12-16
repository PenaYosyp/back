<?php
    $routeArray = explode('/',$_SERVER['REQUEST_URI']);
    $route = end($routeArray);
    $routeEndpoint = explode('?',$route)[0];
    if($routeEndpoint == "categories")
        require_once('../controllers/categoryController.php');
    else if($routeEndpoint == "properties")
        require_once('../controllers/propertyController.php');
    else if($routeEndpoint == "opticalDrives")
        require_once('../controllers/opticalDriveController.php');
    else if($routeEndpoint == "login")
        require_once('../controllers/loginController.php');
?> 