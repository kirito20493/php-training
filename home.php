<?php
session_start();    

    if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
        $username = $_SESSION['username'];
        $password = $_SESSION['password'];
        $email = $_SESSION['email'];
    } else {
        header('Location: login.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    
    <body>
    <div class="main">
        <div class="container">
            <h3 class="container_infor">USER'S INFORMATION</h3>
            <img class="avatar" src="./images/<?php echo $_SESSION['avatar']; ?>" alt="">  
            <p class='container_username'>
                Hello <?php echo $username; ?> !!!
            </p>
            <p class='container_username'>
                Your Email:  <?php echo $email; ?> !!!
            </p>
        </div>
        
        <div class="button">
            <a href="logout.php">LOG OUT</a>
            <a href="changePassword.php">Đổi mật khẩu!!</a>
            <a href="changeEmail.php">Đổi Email!!</a>
        </div>
    </div>
    
    
   </body>
</body>
</html>