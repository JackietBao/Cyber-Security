<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>文件上传</title>
</head>
<body>




<script type="text/javascript" charset="utf-8" src="../blog/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="../blog/ueditor/ueditor.all.js"></script>
<h1>编辑器上传</h1>
<div>
	<script id="editor" type="text/palin" name="bianji"></script>
</div>

<script type="text/javascript">
	var ue=UE.getEditor('editor');
</script>
<h1>文件上传</h1>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <p><input type="file" name="upload"></p>
    <p><input type="submit" value="上传"></p>
</form>
</body>
</html>
<?php
/**
 * Created by PhpStorm.
 * User: xiaodi
 * Date: 2021/12/25
 * Time: 12:20
 */

//获取上传文件名
@$file_name=$_FILES['upload']['name'];
//获取上传文件类型
@$file_type=$_FILES['upload']['type'];
//获取上传文件大小
@$file_size=$_FILES['upload']['size'];
//获取上传文件临时文件名
@$file_tmpname=$_FILES['upload']['tmp_name'];
//获取上传文件是否错误
@$file_error=$_FILES['upload']['error'];

echo $file_name."<hr>";
echo $file_type."<hr>";
echo $file_size."<hr>";
echo $file_tmpname."<hr>";
echo $file_error."<hr>";

if (@$file_error>0){
    echo '上传出错！';
}
else{
    move_uploaded_file(@$_FILES["upload"]["tmp_name"], "upload/" . @$_FILES["upload"]["name"]);
    echo "文件存储在: " . "upload/" . @$_FILES["upload"]["name"];
}


?>


