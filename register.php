<?php
require 'lib/PHPMailer.php';
require 'lib/SMTP.php';
require 'lib/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'validate.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = array();
    //creat user
    if (empty($_POST['username'])) {
        $error['username'] = 'bạn cần nhập username';
    } else {
        if(!isUserName($_POST['username'])) {
            $error['username'] = 'Username không đúng định dạng!'; 
        }else {
            $username = $_POST['username'];
        }
    }

    //creat password
    if (empty($_POST['password'])) {
        $error['password'] = 'bạn cần nhập password ';
    } else {
        if(!isPassWord($_POST['password'])) {
            $error['password'] = 'PassWord phải gôm chữ + số và không có ký tự trống!'; 
        }else {
            $password = $_POST['password'];
        }
    }

    if (empty($_POST['password_confirmation'])) {
        $error['password_confirmation'] = 'bạn cần nhập lại password ';
    } else {
        if ($_POST['password_confirmation'] != $_POST['password']) {
            $error['password_confirmation'] = 'Mật khẩu nhập lại không chính xác! Vui lòng nhập lại!';
        } else {
            $password_confirmation = $_POST['password_confirmation'];
        }
    }

    //send mail
    if (empty($_POST['email'])) {
        $error['email'] = 'bạn cần nhập email ';
    } else {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $error['email'] = "Email không đúng định dạng!";
        }else {
            $email = $_POST['email'];
            $subject = "Successful registration confirmation!";
            if (isset($username) && isset($password)) {
                $msg = "User: ".$username."\nPassowrd: ".$password."\nThank you very much!";
            }
            // send email          
            $mail = new PHPMailer();
            $mail->isSMTP();  
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "tls"; 
            $mail->Port       = 587;  
            $mail->Username   = 'kiritodnvn@gmail.com';               
            $mail->Password   = 'giabao204';  
            $mail->Subject = $subject;
            $mail->setFrom($email);
            if (isset($msg)){
                $mail->Body    = $msg;
            }
            $mail->addAddress($email); 
        }
    }


    // set avatar
    if(isset($_FILES['avatar'])) {
        $errors= array();
        $file_name = $_FILES['avatar']['name'];
        $file_size =$_FILES['avatar']['size'];
        $file_tmp =$_FILES['avatar']['tmp_name'];
        $file_type=$_FILES['avatar']['type'];
        $file_subName = explode('.', $_FILES['avatar']['name']);
        $file_ext=strtolower(end($file_subName));
        
        $expensions= array("jpeg","jpg","png");
        
        if(in_array($file_ext, $expensions)=== false) {
            $errors="Không chấp nhận định dạng ảnh có đuôi này, mời bạn chọn JPEG hoặc PNG.";
        }
        
        if($file_size > 2097152) {
            $error='Kích cỡ file quá lớn!';
        }
        
        if(empty($errors)==true) {
            move_uploaded_file($file_tmp, "images/".$file_name);
        }
        else{
            $error['avatar'] = $errors;
        }
    } else {
            $error['avatar'] = "Vui lòng chọn avatar!";
    }

    if(empty($error)) {
        // write txt
        $myfile = fopen("data.txt", "a") or die("Unable to open file!");
        $txt = "{$username} {$password} {$file_name} {$email}\n";
        fputs($myfile, $txt);
        fclose($myfile);
        // send mail
        $mail->send();
        $mail->smtpClose();


        header('Location: login.php');
        // echo $file_avt;
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
    
        <form action="" method="POST" class="form" id="form-1" enctype="multipart/form-data">
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
            <!-- avatar -->
            <div class="form-group">
                <label for="avatar" class="form-label">Avatar</label>
                <input id="avatar" name="avatar" type="file" class="form-control">
                
                <?php
                if(isset($error['avatar'])) {
                    ?>
                <span class="form-message" style="color:red"><?php echo $error['avatar']; ?></span>
                <?php } ?>
                 
            </div>

            <button class="form-submit" name="register">Đăng ký</button>
        </form>

    </div>
</body>

</html>