<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>文件内容操作</title>
</head>
<body>




<script type="text/javascript" charset="utf-8" src="../blog/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="../blog/ueditor/ueditor.all.js"></script>
<h1>文件列表</h1>
<?php getfilename(); ?>

<h1>读取操作</h1>
<form action="" method="post">
    <input type="text" name="r">
    <input type="submit" value="读取">
</form>
<?php @$name=$_POST['r'];fileread($name);?>
<h1>写入操作</h1>
<form action="" method="post">
    文件：<input type="text" name="w">
    内容：<input type="text" name="txt">
    <input type="submit" value="写入">
</form>
<?php @$name=$_POST['w'];@$txt=$_POST['txt'];filewrite($name,$txt);?>

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

//自定义文件读取函数
function fileread($name){
    $f=fopen($name,"r");
    $code=fread($f,filesize($name));
    echo $code;
    fclose($f);

}
//自定义文件写入函数
function filewrite($name,$txt){
    $f=fopen($name,"a+");
    fwrite($f,$txt);
    fclose($f);

}


?>