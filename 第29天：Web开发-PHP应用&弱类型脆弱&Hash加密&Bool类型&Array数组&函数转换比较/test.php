<?php
//$type = @$_GET['pay'];
//if(0 === '0abc'){// 这里使用双等号进行判断
//    echo "ok";
//}else{
//    echo "no";
//}

//var_dump(0 == 'pay');//true
//var_dump('0e123456789'==0);// bool(true)
//var_dump('0e123456789'=='0');// bool(true)
//var_dump('0e1234abcde'=='0');// bool(false)
/*
应用场景：后台登录用户密码比对，密码经常采用MD5加密
*/
//pwd=240610708 QNKCDZO s1885207154a
//$a = $_GET['pwd'];
//$password = "0e50936721341820084200876514";  //注意:这里管理员密码md5的值是以0e开头的,如果没有看到0e而直接去解md5九成是解不出来的
//if(md5($a) == $password){  //注意:这里是两个等号"=="进行判断,若是"==="则不存在弱类型hash比较缺陷
//    echo 'you are logined!';
//}else{
//    echo 'fuck';
//}

//$password="***************";
//if(isset($_GET['password'])) {
//    if (strcmp($_GET['password'], $password) == 0) {
//        echo "Right!!!login success";
//        exit();
//    } else {
//        echo "Wrong password..";
//    }
//}

//在使用 json_decode() 函数或 unserialize() 函数时，部分结构被解释成 bool 类型，也会造成缺陷，运行结果超出研发人员的预期
//$str = '{"user":true,"pass":true}';

//$str=$_GET['s'];
//$data = json_decode($str,true);
//if ($data['user'] == 'xiaodi' && $data['pass']=='xiaodisec')
//{
//    print_r(' 登录成功！ '."\n");
//}else{
//    print_r(' 登录失败！ '."\n");
//}

//预期：a:2:{s:4:"user";s:4:"root";s:4:"pass";s:6:"xiaodi";}
//绕过：$str = 'a:2:{s:4:"user";b:1;s:4:"pass";b:1;}';
//$str=$_GET['s'];
//$data = unserialize($str);
//if ($data['user'] == 'root' && $data['pass']=='xiaodi')
//{
//    print_r(' 登录成功！ '."\n");
//} else{
//    print_r(' 登录失败！ '."\n");
//}

//当在 switch 中使用 case 判断数字时，switch 会将其中的参数转换为 int 类型进行计算
//$num =$_GET['n'];
//switch ($num) {
//    case 0:
//        echo "say none hacker ！ ";
//        break;
//    case 1:
//        echo "say one hacker ！ ";
//        break;
//    case 2:
//        echo "say two hacker ！ ";
//        break;
//        default;
//        echo "I don't know ！ ";
//}

//$array=[0,1,2,'3'];
//var_dump(in_array('abc', $array));//true
//var_dump(array_search('abc', $array));//0: 下标
//var_dump(in_array('1dsdsdsbc', $array));//true
//var_dump(array_search('1bc', $array));//1: 下标
error_reporting(0);
$flag = 'flag{test}';
if (isset($_GET['username']) and isset($_GET['password'])) {
    if ($_GET['username'] == $_GET['password'])
        print 'Your password can not be your username.';
    else if (md5($_GET['username']) === md5($_GET['password']))
        die('Flag: '.$flag);
    else
        print 'Invalid password';
}


?>



