

<?php


session_start();

if(@$_SESSION['username']=='xiaodi' && @$_SESSION['password']=='xiaodisec'){
    echo "恭喜进入后台管理页面!";
    echo '<a href="loginst_out.php">退出</a>';
}else{
    echo '<script>alert("请登录!")</script>';
    //header('Location: loginc.php');
}




?>


