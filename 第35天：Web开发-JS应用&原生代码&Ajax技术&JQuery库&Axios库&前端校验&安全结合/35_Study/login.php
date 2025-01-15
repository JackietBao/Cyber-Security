<?php

$user=@$_POST['username'];
$pass=@$_POST['password'];
//真实情况需要在数据库获取
$success=array('msg'=>'ok');
if($user=='xiaodi' && $pass=='xiaodisec'){
    $success['infoCode']=1;
}else{
    $success['infoCode']=0;
}
echo json_encode($success);