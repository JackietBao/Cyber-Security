<?php
// 初始化三个变量
$x=2;
$y=3;
$z=4;

// 重新赋值变量$x
$x='aaaaaa';  // $x现在是字符串'aaaaaa'
$a='bbb';     // 定义变量$a为'bbb'
$$x='cccc';   // 可变变量，相当于$aaaaaa='cccc'，因为$x的值是'aaaaaa'
              // 注释//$$x=$a 是错误的解释，因为这里是定义新变量而不是赋值

echo $a;      // 输出变量$a的值：'bbb'

function add(){
    // 使用$GLOBALS数组访问全局变量
    // $GLOBALS是PHP的超全局数组，可以在函数内部访问全局变量
    $GLOBALS['z']=$GLOBALS['x']+$GLOBALS['y'];  // z = x + y
}

// 被注释掉的函数add1
//function add1(){
//    //局部变量示例
//    $z=2+3;    // 这是局部变量$z，与全局变量$z不同
//    return $z;  // 返回局部计算结果
//}

add();        // 调用add()函数
echo $z;      // 输出全局变量$z的值