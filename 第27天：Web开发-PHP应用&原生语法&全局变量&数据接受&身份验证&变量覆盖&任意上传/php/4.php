<form action="" method="post" enctype="multipart/form-data">
    选择文件：<input type="file" name="file_upload">
    <input type="submit" value="上传">
</form>

<?php
//获取表单名为file_upload提交的文件名
$filename=@$_FILES['file_upload']['name'];
$filetype=@$_FILES['file_upload']['type'];
$filesize=@$_FILES['file_upload']['size'];
$filetmp_name=@$_FILES['file_upload']['tmp_name'];

echo "文件名:".$filename."<hr>";
echo "文件格式:".$filetype."<hr>";
echo "文件大小:".$filesize."<hr>";
echo "文件临时名:".$filetmp_name."<hr>";
