<?php
session_start();

require 'validate.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = array();
    

    // get data
    $read = @fopen('data.txt','r');
    $u = array();
    $p = array();
    if($read)
    {
        while(!feof($read))
        {   
            $subStr = explode(" ",fgets($read));
            if (isset($subStr[0])){
                array_push($u,$subStr[0]);
            }
            if (isset($subStr[1])){
                array_push($p,$subStr[1]);
            }
        }

    } else {
        $error['data'] = 'have not data!!';
        echo $error['data'];
    }


    // login!!
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error['password'] = 'bạn không được bỏ trống Tài khoản hoặc Mật khẩu.';
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];
        for ($i = 0; $i < (count($u) - 1); $i++)
        {   
            // echo count($u);
            // echo ($p[$i]);
            if(($username == $u[$i]) && ($password == '123456')) {
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['password'] = $_POST['password'];

                setcookie('username',$_POST['username'],time()+3000);
                setcookie('password',$_POST['password'],time()+3000);
                header('Location: home.php');
            } else {
                $error['password'] = "Tài khoản hoặc mật khẩu không chính xác";
            }

        }
    }
}  
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="main">

        <form action="" method="POST" class="form" id="form-1">
            <h3 class="heading">Đăng nhập</h3>

            <div class="spacer"></div>

            <div class="form-group">
                <label for="username" class="form-label">Tài khoản</label>
                <input id="username" name="username" type="text" placeholder="abc123" class="form-control" value="<?php setValue('username'); ?>">
                <?php
                if(isset($error['username'])){
                ?>
                <span class="form-message" style="color:red"><?php echo $error['username']; ?></span>
                <?php } ?>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mật khẩu</label>
                <input id="password" name="password" type="password" placeholder="Nhập mật khẩu" class="form-control">
                <?php
                if(isset($error['password'])){
                ?>
                <span class="form-message" style="color:red"><?php echo $error['password']; ?></span>
                <?php } ?>
            </div>
            <button class="form-submit">Đăng nhập</button>
            <p id="registerBtn">
                Not yet registered?
                <a href="register.php">Click here to register</a>
            </p>
        </form>
        
    </div>
</body>

</html>