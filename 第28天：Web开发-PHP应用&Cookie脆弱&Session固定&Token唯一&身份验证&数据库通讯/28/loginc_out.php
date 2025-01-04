<?php
setcookie('username', '', time() - 3600, '/');
setcookie('password', '', time() - 3600, '/');
// 跳转到登录页面
header('Location: loginc.php');
exit;
?>