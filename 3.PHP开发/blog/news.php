<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <title>小迪博客</title>

    <style type="text/css">

        .nav li{

            background-color: red;

            padding: 8px 15px;

            float: left;

            list-style: none;

            color:#fff;

        }

    </style>

</head>

<body>

<ul class="nav">

    <li><a href="index.php">首页</a></li>

    <li><a href="news.php">新闻</a></li>

    <li>公司产品</li>

    <li>关于我们</li>

    <li>公司介绍</li>

    <li><?php echo 123;?></li>

</ul>

</body>

</html>


<?php
//操作数据库讲数据取出进行展示
//$conn=mysql_connect('localhost','root','root');
//mysql_select_db('beescms',$conn);
include("./config/conn.php");

$i=$_GET['id'];//GET请求接受id参数名值给变量i

$sql="select * from bees_article where id=$i";
$result=mysql_query($sql,$conn);
while($row=mysql_fetch_array($result)){
    echo '<br><br><hr>';
    echo $row['id'];
    echo $row['content'];
}
?>