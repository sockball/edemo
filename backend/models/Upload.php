<?php
namespace backend\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use backend\helpers\myHelpers;
use yii\db\Query;
use common\models\Student;
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

        if(!isset($res['error']))
        {
            $school = Yii::$app->session->get('school');
            $avatar = (new Query())->select(['teacher'])->from('school')->where(['id' => $school])->scalar();
            foreach ($res as $key => $v)
            {
                $data[$key] = [
                    'name'      => $v[0],
                    'sex'       => ($v[1] == '男') ? 0 : 1,
                    'mobile'    => floatval($v[2]),
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
            'name', 'sex', 'mobile', 'birthdate', 'cid', 'createtime', 'avatar',
        ];
        $data = [];

        if(!isset($res['error']))
        {
            $school = Yii::$app->session->get('school');
            $query  = new Query();
            $avatar = $query->select(['student'])->from('school')->where(['id' => $school])->scalar();

            $now = time();
            $error = false;

            foreach ($res as $key => $v)
            {
                $query     = new Query();
                $gradeName = $v[4];
                $className = $v[5];
                $cid       = $query->select(['classinfo.id'])
                                   ->from('classinfo')
                                   ->where(['like', 'classinfo.name', $className])
                                   ->innerJoin('grade', 'grade.id = classinfo.parent')
                                   ->andWhere(['like', 'grade.name', $gradeName])
                                   ->andWhere(['school' => $school])
                                   ->scalar();

                if($cid === false)
                {
                    $error = true;
                    $msg   = 'Excel表第' . ($key + 2) . '行年级或班级不存在!';
                    $res   = ['error' => 1, 'msg' => $msg];

                    break;
                }

                $data[$key] = [
                    'name'      => $v[0],
                    'sex'       => ($v[1] == '男') ? 0 : 1,
                    'mobile'    => floatval($v[2]),
                    'birthdate' => strtotime($v[3]),
                    'cid'       => $cid,
                    'createtime'=> $now,
                    'avatar'    => $avatar,
                ];
            }

            if(!$error)
            {
                Yii::$app->db->createCommand()->batchInsert('student', $columns, $data)->execute();
                self::updateStudentsCode();
                $res = ['error' => 0, 'msg' => '批量导入学生成功'];
            }
        }

        return $res;
    }

    public static function createCoursesFromExcel($file)
    {
        $res     = myHelpers::readExcel($file);
        $columns = [
            'cid', 'name', 'tid', 'assistant', 'subject', 'info', 'price', 'starttime', 'endtime',
        ];
        $data = [];

        if(!isset($res['error']))
        {
            $school = Yii::$app->session->get('school');

            $now = time();
            $error = false;

            foreach ($res as $key => $v)
            {
                //查询年级、班级是否存在
                $query     = new Query();
                $gradeName = $v[0];
                $className = $v[1];
                $cid       = $query->select(['classinfo.id'])
                                   ->from('classinfo')
                                   ->where(['like', 'classinfo.name', $className])
                                   ->innerJoin('grade', 'grade.id = classinfo.parent')
                                   ->andWhere(['like', 'grade.name', $gradeName])
                                   ->andWhere(['school' => $school])
                                   ->scalar();

                if($cid === false)
                {
                    $error = true;
                    $msg   = 'Excel表第' . ($key + 2) . '行年级或班级不存在!';
                    $res   = ['error' => 1, 'msg' => $msg];

                    break;
                }

                //查询教师是否存在
                $query       = new Query();
                $teacherName = $v[3];
                $teacher     = $query->select(['id'])
                                     ->from('teacher')
                                     ->where(['like', 'name', $teacherName])
                                     ->andWhere(['school' => $school])
                                     ->scalar();

                if($teacher === false)
                {
                    $error = true;
                    $msg   = 'Excel表第' . ($key + 2) . '行授课教师不存在!';
                    $res   = ['error' => 1, 'msg' => $msg];

                    break;
                }

                $assistantName = trim($v[4]);
                if(!empty($assistantName))
                {
                    //查询助教是否存在
                    $query       = new Query();
                    $assistant   = $query->select(['id'])
                                         ->from('teacher')
                                         ->where(['like', 'name', $assistantName])
                                         ->andWhere(['school' => $school])
                                         ->scalar();

                    if($assistant === false)
                    {
                        $error = true;
                        $msg   = 'Excel表第' . ($key + 2) . '行助教不存在!';
                        $res   = ['error' => 1, 'msg' => $msg];

                        break;
                    }     
                }
                else
                    $assistant = 0;

                //查询科目是否存在
                $query       = new Query();
                $subjectName = $v[5];
                $subject     = $query->select(['id'])
                                    ->from('sundry')
                                    ->where(['school' => $school])
                                    ->andWhere(['like', 'name', $subjectName])
                                    ->scalar();

                if($subject === false)
                {
                    $error = true;
                    $msg   = 'Excel表第' . ($key + 2) . '行科目不存在!';
                    $res   = ['error' => 1, 'msg' => $msg];

                    break;
                }

                $data[$key] = [
                    'cid'       => $cid,
                    'name'      => $v[2],
                    'tid'       => $teacher,
                    'assistant' => $assistant,
                    'subject'   => $subject,
                    'info'      => $v[6],
                    'price'     => $v[7],
                    'starttime' => strtotime($v[8]),
                    'endtime'   => strtotime($v[9]),
                ];
            }

            if(!$error)
            {
                Yii::$app->db->createCommand()->batchInsert('course', $columns, $data)->execute();
                self::updateStudentsCode();
                $res = ['error' => 0, 'msg' => '批量导入课程成功'];
            }
        }

        return $res;
    }

    public static function createSchedulesFromExcel($file)
    {
        $res     = myHelpers::readExcel($file);
        $columns = [
            'relate', 'date', 'period', 'num', 'info',
        ];
        $data = [];

        if(!isset($res['error']))
        {
            $school = Yii::$app->session->get('school');

            $now = time();
            $error = false;

            foreach ($res as $key => $v)
            {
                //查询年级、班级是否存在
                $query     = new Query();
                $gradeName = $v[0];
                $className = $v[1];
                $cid       = $query->select(['classinfo.id'])
                                   ->from('classinfo')
                                   ->where(['like', 'classinfo.name', $className])
                                   ->innerJoin('grade', 'grade.id = classinfo.parent')
                                   ->andWhere(['like', 'grade.name', $gradeName])
                                   ->andWhere(['school' => $school])
                                   ->scalar();

                if($cid === false)
                {
                    $error = true;
                    $msg   = 'Excel表第' . ($key + 2) . '行年级或班级不存在!';
                    $res   = ['error' => 1, 'msg' => $msg];

                    break;
                }

                //查询课程是否存在
                $query      = new Query();
                $courseName = $v[2];
                $course     = $query->select(['course.id'])
                                    ->from('course')
                                    ->where(['like', 'course.name', $courseName])
                                    ->innerJoin('classinfo', 'course.cid = classinfo.id')
                                    ->andWhere(['classinfo.id' => $cid])
                                    ->scalar();

                if($course === false)
                {
                    $error = true;
                    $msg   = 'Excel表第' . ($key + 2) . '行课程不存在!';
                    $res   = ['error' => 1, 'msg' => $msg];

                    break;
                }

                //查询时段是否存在
                $query      = new Query();
                $periodName = $v[4];
                $period     = $query->select(['id'])
                                    ->from('sundry')
                                    ->where(['school' => $school])
                                    ->andWhere(['like', 'name', $periodName])
                                    ->scalar();

                if($period === false)
                {
                    $error = true;
                    $msg   = 'Excel表第' . ($key + 2) . '行时段不存在!';
                    $res   = ['error' => 1, 'msg' => $msg];

                    break;
                }

                $data[$key] = [
                    'relate'    => $course,
                    'date'      => strtotime($v[3]),
                    'period'    => $period,
                    'num'       => intval($v[5]),
                    'info'      => $v[6],
                ];
            }

            if(!$error)
            {
                Yii::$app->db->createCommand()->batchInsert('schedule', $columns, $data)->execute();
                self::updateStudentsCode();
                $res = ['error' => 0, 'msg' => '批量导入课表成功'];
            }
        }

        return $res;
    }

    public static function updateStudentsCode()
    {
        //没加上学校搜索条件.. 全更新也可以?
        $students = Student::find()->select(['id', 'cid'])->where(['code' => 0])->all();
        foreach ($students as $key => $student)
        {
            $code = myHelpers::createStudentCode($student->cid, $student->id);
            //用save(false)的话会走beforeSave等等;
            $student->updateAll(['code' => $code], ['id' => $student->id]);
        }
    }
}