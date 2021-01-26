<?php
session_start();
require 'validate.php';
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
        // change password
        if (empty($_POST['passwordOld']) || empty($_POST['password']) || empty($_POST['password-confirmation'])) {
            $error['password-confirmation'] = "Vui lòng nhập đầy đủ các trường trên!";
        } elseif (!isPassWord($_POST['password'])){
            $error['password'] = 'PassWord phải gôm chữ + số và không có ký tự trống!'; 
        } else {
            if (!($password == $_POST['passwordOld'])){
                $error['passwordOld'] = "Mật khẩu không chính xác!";
            } else {
                if (!($_POST['password-confirmation'] == $_POST['password'])) {
                    $error['password-confirmation'] = "Mật khẩu nhập lại không chính xác!";
                } else {

                    $myfile = fopen("dataNew.txt", "a") or die("Unable to open file!");
                    for ($i = 0; $i < (count($u) - 1); $i++) {

                        if ($username != $u[$i]) {

                            if (isset($u[$i]) && isset($p[$i]) && isset($a[$i]) && isset($e[$i])) {
                                $txt = "{$u[$i]} {$p[$i]} {$a[$i]} {$e[$i]}\n";
                                fputs($myfile, $txt);
                                
                            }
                        
                        } else {
                            if (isset($u[$i]) && isset($_POST['password']) && isset($a[$i]) && isset($e[$i])) {
                                $txt = "{$u[$i]} {$_POST['password']} {$a[$i]} {$e[$i]}\n";
                                fputs($myfile, $txt);
                            }
                        }
                    }
                    fclose($myfile);
                    copy("dataNew.txt","data.txt");
                    file_put_contents("dataNew.txt","");
                    $_SESSION['password'] = $_POST['password'];
                    header('Location: home.php');
                }
            }
        }

    }else{}

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
            <h3 class="heading">Thay đổi mật khẩu</h3>
            <div class="spacer"></div>
            <p></p>
            <div class="form-group">
                <label for="passwordOld" class="form-label">Mật khẩu hiện tại: </label>
                <input id="passwordOld" name="passwordOld" type="password" class="form-control" >
                <?php
                if(isset($error['passwordOld'])){
                ?>
                <span class="form-message" style="color:red"><?php echo $error['passwordOld']; ?></span>
                <?php } ?>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Mật khẩu mới: </label>
                <input id="password" name="password" type="password" class="form-control">
                <?php
                if(isset($error['password'])){
                ?>
                <span class="form-message" style="color:red"><?php echo $error['password']; ?></span>
                <?php } ?>
            </div>
            <div class="form-group">
                <label for="password-confirmation" class="form-label">Nhập lại mật khẩu: </label>
                <input id="password-confirmation" name="password-confirmation" type="password" class="form-control">
                <?php
                if(isset($error['password-confirmation'])){
                ?>
                <span class="form-message" style="color:red"><?php echo $error['password-confirmation']; ?></span>
                <?php } ?>
            </div>

            <button class="form-submit">Xác nhận</button>
        </form>
    </div>
</body>
</html>