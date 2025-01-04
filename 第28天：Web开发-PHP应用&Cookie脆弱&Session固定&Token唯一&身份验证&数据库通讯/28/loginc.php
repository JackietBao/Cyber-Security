<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台登录</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="login-container">
    <div class="login-box">
        <h2>后台登录</h2>
        <form action="" method="POST">
            <div class="input-group">
                <label for="username">用户名</label>
                <input type="text" id="username" name="username" placeholder="请输入用户名" required>
            </div>
            <div class="input-group">
                <label for="password">密码</label>
                <input type="password" id="password" name="password" placeholder="请输入密码" required>
            </div>
            <button type="submit" class="btn">登录</button>
            <div class="footer">
                <p>忘记密码?</p>
                <p>还没有账号? <a href="#">注册</a></p>
            </div>
        </form>
    </div>
</div>
</body>
</html>

<?php
/*
 * 1、连接数据库
 * 2、选择数据库中的表
 * 3、接受提交的用户和密码
 * 4、执行sql语句判断用户密码是否正确
 * */
include 'config.php';

$user=@$_POST['username'];
$pass=@$_POST['password'];
$conn=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
$sql="select * from admin where username='$user' and password='$pass';";



$data=mysqli_query($conn,$sql);
if($_SERVER["REQUEST_METHOD"] == "POST") {
    //登录成功的逻辑代码
    if (mysqli_num_rows($data) > 0) {
        $expire = time() + 60 * 60 * 24 * 30; // 一个月后过期
        setcookie('username', $user, $expire, '/');
        setcookie('password', $pass, $expire, '/');
        header('Location: indexc.php');
        exit();
    } else {
        echo "<script>alert('用户或密码错误!')</script>";
    }
}