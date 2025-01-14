<?php
$x=2;
$y=3;
$z=4;

$x='aaaaaa';
$a='bbb';
$$x='cccc';//$$x=$a
echo $a;

function add(){
    //全局变量z=全局变量x+全局变量y
    $GLOBALS['z']=$GLOBALS['x']+$GLOBALS['y'];
}

//function add1(){
//    //局部
//    $z=2+3;
//    return $z;
//}

add();
echo $z;