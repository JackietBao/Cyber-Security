<?php
// 开始会话
session_start();

// 清除 SESSION 变量，并销毁会话
session_unset();
session_destroy();

// 重定向到登录页面
header('Location: logins.php');
exit;
?>
