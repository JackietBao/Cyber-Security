<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>文件下载</title>
</head>
<body>




<script type="text/javascript" charset="utf-8" src="../blog/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="../blog/ueditor/ueditor.all.js"></script>
<h1>文件列表</h1>
<?php getfilename(); ?>

<h1>直连下载</h1>
<form action="" method="post">
    <input type="text" name="filename">
    <input type="submit" value="下载">
</form>
<?php @$name=$_POST['filename'];filenameurl($name);?>
<h1>传参下载</h1>
<form action="" method="post">
    <input type="text" name="downname">
    <input type="submit" value="下载">
</form>
<?php @$name=$_POST['downname'];filenameget($name);?>

</body>
</html>
<?php
//自定义文件文件夹读取函数
function getfilename(){
    $dir=getcwd();
    $file=scandir($dir.'/soft');
    foreach ($file as $value){
        if($value != '.' && $value != '..') {
            $arr[] = $value;
            echo $value.'<br>';
        }
    }
}
//自定义文件直连下载
function filenameurl($name){
    $url='http://'.$_SERVER['HTTP_HOST'].'/blog/soft/'.$name;
    #header("location:$url");
}
//自动以文件传参下载
function filenameget($name){
    $filename = $name;
    $download_path = "soft/";
    if(eregi("\.\.", $filename)) die("抱歉，你不能下载该文件！");
    $file = str_replace("..", "", $filename);
    if(eregi("\.ht.+", $filename)) die("抱歉，你不能下载该文件！");

// 创建文件下载路径
    $file = "$download_path$file";

// 判断文件是否存在
    if(!file_exists($file)) die("抱歉，文件不存在！");

//  文件类型，作为头部发送给浏览器
    $type = filetype($file);

// 获取时间和日期
    $today = date("F j, Y, g:i a");
    $time = time();

// 发送文件头部
    /*
    header("Content-type: $type");
    header("Content-Disposition: attachment;filename=$filename");
    header("Content-Transfer-Encoding: binary");
    header('Pragma: no-cache');
    header('Expires: 0');
    */
// 发送文件内容
    set_time_limit(0);
    readfile($file);
}

?>