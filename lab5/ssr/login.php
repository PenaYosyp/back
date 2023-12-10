<?php
    session_start();
    if(isset($_SESSION['username'])) {
        header('Location: opticalDrives.php');
    }
    $loginError='';
    if(isset($_POST['login'])) {
        if($_POST['login']=='admin' && $_POST['password']=='111111') {
            $_SESSION['username']='admin';
            header('Location: opticalDrives.php');
        } else {
            $loginError='Неправильний логін або пароль';
        }
    }
?>
<html>
    <head>
        <title>Login</title>
        <link href="../assets/style.css" rel="stylesheet" />
    </head>
    <body>
        <div class='container login-container'>
            <div class='form-content'>
                <h1>Вхід</h1>
                <form method="POST">
                    <p><input type="text" placeholder="Логін" name="login" required/></p>
                    <p><input type="password" placeholder="Пароль" name="password" required/></p>
                    <p><button type="submit">Увійти</button></p>
                    <p><?php echo $loginError; ?></p>
                </form>
            </div>
        </div>
    </body>
</html>