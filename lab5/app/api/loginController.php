<?php
    session_start();
    if(isset($_POST['login'])) {
        if($_POST['login'] == 'admin' && $_POST['password'] == '111111') {
            $_SESSION['username']='admin';
            echo '{"userlogin":"admin","error":""}';
        } else echo '{"error":"Неправильний логін або пароль"}';
    } else if(isset($_GET['action']) && $_GET['action'] == 'logout'){
        session_destroy();
        echo '{"userlogin":"'.(isset($_SESSION['username'])?$_SESSION['username']:"").'"}';
    } else echo '{"userlogin":"'.(isset($_SESSION['username'])?$_SESSION['username']:"").'"}';
?>