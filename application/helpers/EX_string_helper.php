<?php

/** 
 * 把一个汉字转为unicode的通用函数，不依赖任何库，和别的自定义函数，但有条件 
 * 条件：本文件以及函数的输入参数应该用utf-8编码，不然要加函数转换 
 * 其实亦可轻易编写反向转换的函数，甚至不局限于汉字，奇怪为什么PHP没有现成函数 
 * @author xieye 
 * 
 * @param {string} $word 必须是一个汉字，或代表汉字的一个数组(用str_split切割过) 
 * @return {string} 一个十进制unicode码，如4f60，代表汉字 “你” 
 * 
 * @example 
    echo "你 ".getUnicodeFromOneUTF8("你"); 
    echo "<br />"; 
    echo "好 ".getUnicodeFromOneUTF8("好"); 
    echo "<br />"; 
    echo "你好 ".getUnicodeFromOneUTF8("你好"); 
    echo "<br />"; 
    echo "你好吗 ".getUnicodeFromOneUTF8("你好吗"); 
    你 20320 
    好 22909 
    你好 251503099357000 
    你好吗 4.21952182258E+21 
 */  
function getUnicodeFromOneUTF8($word)
{  
    //获取其字符的内部数组表示，所以本文件应用utf-8编码!  
    if (is_array( $word))  
        $arr = $word;  
    else  
        $arr = str_split($word);  
    //此时，$arr应类似array(228, 189, 160)  
    //定义一个空字符串存储  
    $bin_str = '';  
    //转成数字再转成二进制字符串，最后联合起来。  
    foreach ($arr as $value)  
        $bin_str .= decbin(ord($value));  
    //此时，$bin_str应类似111001001011110110100000,如果是汉字"你"  
    //正则截取  
    $bin_str = preg_replace('/^.{4}(.{4}).{2}(.{6}).{2}(.{6})$/','$1$2$3', $bin_str);  
      
    //此时， $bin_str应类似0100111101100000,如果是汉字"你"  
    return dechex(bindec($bin_str));  
    //返回类似20320， 汉字"你"  
    //return dechex(bindec($bin_str));  
    //如想返回十六进制4f60，用这句  
} 