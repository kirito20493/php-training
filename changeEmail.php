<?php
require 'lib/PHPMailer.php';
require 'lib/SMTP.php';
require 'lib/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $error = array();
        
        // get data
        $read = @fopen('data.txt','r');
        $u = array();
        $p = array();
        $a = array();
        $e = array();

        $line = array();
        $newData = array();
        if($read)
        {
            while(!feof($read))
            {   
                $subStr = explode(" ",fgets($read));
                if (isset($subStr[0])){
                    array_push($u,$subStr[0]);
                }
                if (isset($subStr[1])){
                    array_push($p,rtrim($subStr[1]));
                }
                if (isset($subStr[2])){
                    array_push($a,rtrim($subStr[2]));
                }
                if (isset($subStr[3])){
                    array_push($e,rtrim($subStr[3]));
                }
            }
        } else {
            $error['data'] = 'have not data!!';
            echo $error['data'];
        }
        // change email
        //send mail
        if (empty($_POST['email'])) {
            $error['email'] = 'bạn cần nhập email ';
        } else {
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $error['email'] = "Email không đúng định dạng!";
            }else {
                $email = $_POST['email'];
                $subject = "Successful change your Email!";
                $msg = "Your new Email: ".$email."\nThank you very much!";
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
                $mail->Body    = $msg;
                $mail->addAddress($email); 
            }
        }

        // check password
        if (!($password == $_POST['password'])) {
            $error['password'] = "Mật khẩu không chính xác!";
        } else {
            $myfile = fopen("dataNew.txt", "a") or die("Unable to open file!");
            for ($i = 0; $i < (count($u) - 1); $i++) {

                if ($username != $u[$i]) {

                    if (isset($u[$i]) && isset($p[$i]) && isset($a[$i]) && isset($e[$i])) {
                        $txt = "{$u[$i]} {$p[$i]} {$a[$i]} {$e[$i]}\n";
                        fputs($myfile, $txt);
                                
                    }
                        
                } else {
                    if (isset($u[$i]) && isset($p[$i]) && isset($a[$i]) && isset($email)) {
                        $txt = "{$u[$i]} {$p[$i]} {$a[$i]} {$email}\n";
                        fputs($myfile, $txt);
                    }
                }
            }
            
        }

        if(empty($error)) {
            fclose($myfile);
            copy("dataNew.txt","data.txt");
            file_put_contents("dataNew.txt","");
            $mail->send();
            $mail->smtpClose();

            $_SESSION['email'] = $_POST['email'];
            header('Location: home.php');
        }
    }

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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="main">
        <form action="" method="POST" class="form" id="form-1">
            <h3 class="heading">Thay đổi Email</h3>
            <div class="spacer"></div>
            <p></p>
            <div class="form-group">
                <label for="email" class="form-label">Email mới </label>
                <input id="email" name="email" type="text" class="form-control" >
                <?php
                if(isset($error['email'])){
                ?>
                <span class="form-message" style="color:red"><?php echo $error['email']; ?></span>
                <?php } ?>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Mật khẩu: </label>
                <input id="password" name="password" type="password" class="form-control">
                <?php
                if(isset($error['password'])){
                ?>
                <span class="form-message" style="color:red"><?php echo $error['password']; ?></span>
                <?php } ?>
            </div>

            <button class="form-submit">Xác nhận</button>
        </form>
    </div>
</body>
</html>