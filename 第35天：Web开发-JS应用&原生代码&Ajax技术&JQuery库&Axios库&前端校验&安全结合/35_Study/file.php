<?php

$name=$_FILES['file_upload']['name'];
$type=$_FILES['file_upload']['type'];
$size=$_FILES['file_upload']['size'];
$tmp_name=$_FILES['file_upload']['tmp_name'];
$error=$_FILES['file_upload']['error'];

echo $name."<br>";
echo $type."<br>";
echo $size."<br>";
echo $tmp_name."<br>";
echo $error."<br>";