<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>文件删除</title>
</head>
<body>




<script type="text/javascript" charset="utf-8" src="../blog/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="../blog/ueditor/ueditor.all.js"></script>
<h1>文件删除</h1>
<?php getfilename(); ?>
<form action="" method="post">
    <input type="text" name="filename">
    <input type="submit" value="删除">
</form>
<?php @$name=$_POST['filename'];filedel($name);?>
<h1>文件夹删除</h1>
<?php getfilename()?>
<form action="" method="post">
    <input type="text" name="filedir">
    <input type="submit" value="删除">
</form>
<?php @$dir=$_POST['filedir'];filedeldir($dir);?>
</body>
</html>
<?php
//自定义文件文件夹读取函数
function getfilename(){
    $dir=getcwd();
    $file=scandir($dir);
    foreach ($file as $value){
        if($value != '.' && $value != '..') {
            $arr[] = $value;
            echo $value.'<br>';
        }
    }
}
//自定义文件删除函数
function filedel($name){
    @unlink($name);
}
//自定义文件夹删除函数
function filedeldir($dir){
    @rmdir($dir);
}
?>


