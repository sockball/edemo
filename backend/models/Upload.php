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

	//图片后缀名
	const picExtensions = ['gif', 'jpg', 'jpeg', 'png', 'gif'];

    public static function uploadPic($inputName)
    {
            $images = UploadedFile::getInstancesByName($inputName);
            if (count($images) > 0)
            {
                foreach ($images as $key => $image)
                {
                    if ($image->size > self::size)
                        return $res = ['error' => 1, 'msg' => '图片最大不可超过3M'];

                    if (!in_array(strtolower($image->extension), self::picExtensions))
                        return $res = ['error' => 1, 'msg' => '请上传标准图片文件']; 

                    $filepath = '../../common/uploads/temp/';
                    $filename =  $inputName . time() .  '.' . $image->extension;//getExtension();  

                    //如果文件夹不存在 
                    if (!file_exists($filepath))
                        FileHelper::createDirectory($filepath, 0755); 

                    $file = $filepath . $filename;  
  					if(file_exists($file))
  						unlink($file);

                    if ($image->saveAs($file))
                    {
                    	$url = IMG_PRE . 'temp/' . $filename;
                        $res = ['error' => 0, 'msg' => '上传成功', 'url' => $url, 'column' => $inputName];
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

    public static function uploadExcel($type)
    {
        $excel = UploadedFile::getInstanceByName('excel');

        if (!in_array(strtolower($excel->extension), ['xls', 'xlsx']))
            return $res = ['error' => 1, 'msg' => '请上传excel文件！'];

        $filepath = '../../common/uploads/excel/';
        $filename =  $filepath . $type . time() .  '.' . $excel->extension;

        if ($excel->saveAs($filename))
        {
            $method = 'create' . ucfirst($type) . 'sFromExcel';
            $res    = self::$method($filename);
            unlink($filename);
        }
        else
            $res = ['error' => 1, 'msg' => '上传失败'];

        return $res;
    }

    public static function createTeachersFromExcel($file)
    {
        //Command对象查看sql语句在execute前 ->getRawSql();
        $res     = myHelpers::readExcel($file);
        $columns = [
            'name', 'sex', 'mobile', 'birthdate', 'hiredate', 'main', 'experience', 'result', 'special',
            'school', 'avatar', 'bindcode',
        ];
        $data = [];

        if(!isset($data['error']))
        {
            $school = Yii::$app->session->get('school');
            $avatar = (new \yii\db\Query())->select(['teacher'])->from('school')->where(['id' => $school])->scalar();
            foreach ($res as $key => $v)
            {
                $data[$key] = [
                    'name'      => $v[0],
                    'sex'       => ($v[1] == '男') ? 0 : 1,
                    'mobile'    => intval($v[2]),
                    'birthdate' => strtotime($v[3]),
                    'hiredate'  => strtotime($v[4]),
                    'main'      => $v[5],
                    'experience'=> $v[6],
                    'result'    => $v[7],
                    'special'   => $v[8],
                    'school'    => $school,
                    'avatar'    => $avatar,
                    'bindcode'  => myHelpers::createBindCode(),
                ];
            }

            Yii::$app->db->createCommand()->batchInsert('teacher', $columns, $data)->execute();

            $res = ['error' => 0, 'msg' => '批量导入教师成功'];
        }

        return $res;
    }

    public static function createStudentsFromExcel($file)
    {
        $res     = myHelpers::readExcel($file);
        $columns = [
            'name', 'sex', 'mobile', 'birthdate', 'hiredate', 'main', 'experience', 'result', 'special',
            'school', 'avatar', 'bindcode',
        ];
        $data = [];

        if(!isset($data['error']))
        {
            $school = Yii::$app->session->get('school');
            $avatar = (new \yii\db\Query())->select(['teacher'])->from('school')->where(['id' => $school])->scalar();
            foreach ($res as $key => $v)
            {
                $data[$key] = [
                    'name'      => $v[0],
                    'sex'       => ($v[1] == '男') ? 0 : 1,
                    'mobile'    => intval($v[2]),
                    'birthdate' => strtotime($v[3]),
                    'hiredate'  => strtotime($v[4]),
                    'main'      => $v[5],
                    'experience'=> $v[6],
                    'result'    => $v[7],
                    'special'   => $v[8],
                    'school'    => $school,
                    'avatar'    => $avatar,
                    'bindcode'  => myHelpers::createBindCode(),
                ];
            }

            Yii::$app->db->createCommand()->batchInsert('teacher', $columns, $data)->execute();

            $res = ['error' => 0, 'msg' => '批量导入教师成功'];
        }

        return $res;
    }
}