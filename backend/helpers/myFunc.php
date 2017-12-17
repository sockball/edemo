<?php

namespace backend\helpers;


/**
 * 自定义辅助方法类
 */
class myFunc Controller
{
    /**
      * 为数字补齐一定位数的内容
      * @int    $number 需补齐的数字
      * @int    $num    补齐后的总位数
      * @string $str    用于填充的内容
    */

    public static function fillZeroInNumber($number, $num, $str = 0)
    {
        return sprintf("%'{$str}{$num}d", $number);
    }
}
