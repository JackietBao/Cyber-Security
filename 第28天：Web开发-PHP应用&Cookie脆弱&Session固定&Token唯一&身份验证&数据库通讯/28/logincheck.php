<?php
session_start();
include 'config.php';
$conn=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
$user=@$_POST['username'];
$pass=@$_POST['password'];
$token = @$_POST['token'];

$sql="select * from admin where username='$user' and password='$pass';";
$data=mysqli_query($conn,$sql);

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (empty($token) || $token!== $_SESSION['token']) {
        echo '<script>alert("Token 无效！")</script>';
        exit();
    }
    //判断用户登录成功
    if(mysqli_num_rows($data) > 0){
        session_start();
        $_SESSION['username']=$user;
        $_SESSION['password']=$pass;
        $_SESSION['token'] = bin2hex(random_bytes(32));
        header('Location: indexst.php');
        exit();
    }else{
        //判断用户登录失败
        echo '<script>alert("登录失败!")</script>';
    }
}


?>