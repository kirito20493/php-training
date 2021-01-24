<?php
require 'validate.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = array();

    if (empty($_POST['username'])) {
        $error['username'] = 'bạn cần nhập username';
    } else {
        if(!isUserName($_POST['username'])) {
            $error['username'] = 'Username không đúng định dạng!'; 
        }else {
            $username = $_POST['username'];
        }
    }

    if (empty($_POST['email'])) {
        $error['email'] = 'bạn cần nhập email ';
    } else {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $error['email'] = "Email không đúng định dạng!";
        }else {
            $email = $_POST['email'];
            $subject = "Test send email";
            $msg = "Test send email.\nThank you very much!";
            $headers = "From: giabao@NTA.com";
            // send email
            mail($email, $subject, $msg, $headers);            
        }
    }


    if (empty($_POST['password'])) {
        $error['password'] = 'bạn cần nhập password ';
    } else {
        $password = $_POST['password'];
    }

    if (empty($_POST['password_confirmation'])) {
        $error['password_confirmation'] = 'bạn cần nhập lại password ';
    } else {
        if ($_POST['password_confirmation'] != $password) {
            $error['password_confirmation'] = 'Mật khẩu nhập lại không chính xác! Vui lòng nhập lại!';
        } else {
            $password_confirmation = $_POST['password_confirmation'];
        }
    }

    if(empty($error)) {
        // write txt
        $myfile = fopen("data.txt", "a") or die("Unable to open file!");
        $txt = "{$username} {$password}\n";
        fputs($myfile, $txt);
        fclose($myfile);
        header('Location: login.php');
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="main">
    
        <form action="" method="POST" class="form" id="form-1">
            <h3 class="heading">Đăng ký thành viên</h3>
            <div class="spacer"></div>

            <div class="form-group">
                <label for="username" class="form-label">Tài khoản</label>
                <input id="username" name="username" type="text" placeholder="abc123" class="form-control" value="<?php setValue('username'); ?>">

                <?php
                if(isset($error['username'])) {
                    ?>
                <span class="form-message" style="color:red"><?php echo $error['username']; ?></span>
                <?php } ?>

            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="text" placeholder="VD: email@domain.com" class="form-control" value="<?php setValue('email'); ?>">

                <?php
                if(isset($error['email'])) {
                    ?>
                <span class="form-message" style="color:red"><?php echo $error['email']; ?></span>
                <?php } ?>

            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mật khẩu</label>
                <input id="password" name="password" type="password" placeholder="Nhập mật khẩu" class="form-control">

                <?php
                if(isset($error['password'])) {
                    ?>
                <span class="form-message" style="color:red"><?php echo $error['password']; ?></span>
                <?php } ?>

            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Nhập lại mật khẩu</label>
                <input id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu"
                    type="password" class="form-control">
                
                <?php
                if(isset($error['password_confirmation'])) {
                    ?>
                <span class="form-message" style="color:red"><?php echo $error['password_confirmation']; ?></span>
                <?php } ?>
                 
            </div>

            <button class="form-submit" name="register">Đăng ký</button>
        </form>

    </div>
</body>

</html>