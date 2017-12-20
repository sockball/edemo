<?php
namespace backend\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use backend\helpers\myHelpers;
// use yii\web\Controller;

class Upload
{
	//图片大小
	const size 	 	= 1024 * 1024 * 3;

	//后缀名
	const extensions = ['gif', 'jpg', 'jpeg', 'png', 'gif'];

    public static function uploadPic($column)
    {
            $images = UploadedFile::getInstancesByName($column);
            if (count($images) > 0)
            {
                foreach ($images as $key => $image)
                {
                    if ($image->size > self::size)
                        return $res = ['error' => 1, 'msg' => '图片最大不可超过3M'];

                    if (!in_array(strtolower($image->extension), self::extensions))
                        return $res = ['error' => 1, 'msg' => '请上传标准图片文件']; 

                    $filepath = '../../common/uploads/temp/';
                    $filename =  $column . time() .  '.' . $image->extension;//getExtension();  

                    //如果文件夹不存在 
                    if (!file_exists($filepath))
                        FileHelper::createDirectory($filepath, 0755); 

                    $file = $filepath . $filename;  
  					if(file_exists($file))
  						unlink($file);

                    if ($image->saveAs($file))
                    {
                    	$url = IMG_PRE . 'temp/' . $filename;
                        $res = ['error' => 0, 'msg' => '上传成功', 'url' => $url, 'column' => $column];
                    }
                    else
                    	$res = ['error' => 1, 'msg' => '上传失败'];
                }
            }
            else
            	$res = ['error' => 1, 'msg' => '没有上传文件'];

            return $res;
    }

    //将临时logo保存为正式并删除临时文件
    public static function updateTemp($tmp, $oldPath, $foldername)
    {
        $array = explode('temp/', $tmp);
        if(count($array) < 2)
            return $tmp;
        else
        {
            $filename = $array[1];
            $foldername = $foldername . '/';
            $root = '../../common/uploads/';
            $tmp  = $root . 'temp/' . $filename;
            $newPath = $root . $foldername;

            if(!file_exists($newPath))
                FileHelper::createDirectory($newPath, 0755);

            $new  = $newPath . $filename;
            copy($tmp, $new);
            unlink($tmp);

            $oldFile = myHelpers::getImgPath($oldPath);

            if(file_exists($oldFile) && strpos($oldFile, $foldername)){
                unlink($oldFile);
            }

            return IMG_PRE . $foldername . $filename;
        }
    }

}