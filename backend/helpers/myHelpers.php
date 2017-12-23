<?php

namespace backend\helpers;

use Yii;
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

    public static function fillInNumber($number, $num, $str = 0)
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

    /**
      * 生成后台提示用HTML
      * @string $hint 提示内容
      * return string
    */

    public static function giveHint($hint = 'What do you want to hint')
    {
        $hintHTML = <<<start
    <div class="alert alert-warning alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      
      <h4>{$hint}</h4>
    </div>
start;

        return $hintHTML;
    }

    /**
      * 获取图片上传地址(除去图片url前缀)
      * @string $path 原完整图片url
      * return string 系统可读相对路径
    */

    public static function getImgPath($path)
    {
        $length = strlen(IMG_PRE);

        return '../../common/uploads/' . substr($path, $length);
    }

    public static function readExcel($file)
    {
        $data = [];
        $PHPReader = new \PHPExcel_Reader_Excel2007(); 
        if(!$PHPReader->canRead($file))
        {
            $PHPReader = new \PHPExcel_Reader_Excel5(); 
            if(!$PHPReader->canRead($file))
            { 
              return ['error' => 1, 'msg' => '读取excel文件错误'];
            }
        }

        $PHPExcel     = $PHPReader->load($file);
        $currentSheet = $PHPExcel->getSheet(0);
        $allColumn    = $currentSheet->getHighestColumn(); //最大行号 ABCDEFG
        $allRow       = $currentSheet->getHighestRow();

        for($row = 2; $row <= $allRow; $row++)
        {
            for($column = 'A'; $column <= $allColumn; $column++)
            {
                $cell = $currentSheet->getCell($column . $row)->getValue();
                if($cell instanceof \PHPExcel_RichText)     //富文本转换字符串  
                    $cell = $cell->__toString();
                $data[$row - 2][] = $cell;
            }
        }

        return $data;
    }

    public static function getHint()
    {
        $session = Yii::$app->session;
        $hint    = $session->get('hint', false);
        $session->remove('hint');

        return $hint;
    }
}
