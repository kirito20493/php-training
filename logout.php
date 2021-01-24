<?php
    session_start();
    session_destroy();

    setcookie('username',$_POST['username'],time()-3000);
    setcookie('password',$_POST['password'],time()-3000);
    header('Location: login.php');
?>