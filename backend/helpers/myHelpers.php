<?php

namespace backend\helpers;


/**
 * 自定义辅助方法类
 */
class myHelpers
{
    /**
      * 为数字补齐一定位数的内容
      * @int    $number 需补齐的数字
      * @int    $num    补齐后的总位数
      * @string $str    用于填充的内容
      * return string
    */

    public static function fillZeroInNumber($number, $num, $str = 0)
    {
        return sprintf("%'{$str}{$num}d", $number);
    }

    /**
      * 生成随机8位教师绑定码
      * return string
    */

    public static function createBindCode()
    {
        $str = str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

        return substr($str, 0, 8); 
    }
}
