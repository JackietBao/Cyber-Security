<?php
$username=$_COOKIE['username'];
setcookie("username", $username.'gay', time() + (86400 * 30), "/");


session_start();
$_SESSION['username'] = 'xd';
echo "Welcome, " . $_SESSION['username'] . "!";