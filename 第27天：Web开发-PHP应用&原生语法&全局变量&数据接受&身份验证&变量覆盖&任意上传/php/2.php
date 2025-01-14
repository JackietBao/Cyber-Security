<?php
//$_REQUEST：$_REQUEST 用于收集 HTML 表单提交的数据。
//$_POST：广泛用于收集提交method="post" 的HTML表单后的表单数据。
//$_GET：收集URL中的发送的数据。也可用于提交表单数据(method="get")
include('1.php');
echo $x;

$r=@$_REQUEST['x'];
$g=@$_GET['y'];
$p=@$_POST['z'];

$c=@$_COOKIE['n'];


echo $r."<hr>";
echo $g."<hr>";
echo $p."<hr>";
echo $c."<hr>";