<?php
session_start();    

    if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
        echo "Hello ".$_SESSION['username']."!<br>";
        echo "Password is ".$_SESSION['password']."!<br>";
    } else {
        echo ("Haven't SESSION <br>");
    }

    if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
        echo "Hello ".$_COOKIE['username']."!<br>";
        echo "Password is ".$_COOKIE['password']."!<br>";
    } else {
        echo ("Haven't COOKIE <br>");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="logout.php">LOG OUT</a>
</body>
</html>