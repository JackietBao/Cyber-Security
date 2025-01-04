<?php

if(@$_COOKIE['username']=='xiaodi' and @$_COOKIE['password']=='xiaodisec'){
    echo '恭喜进入后台管理页面!';
    echo '<a href="loginc_out.php">退出</a>';
}else{
    echo "<script>alert('请登录后尝试!')</script>";
}
