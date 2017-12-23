<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use backend\widgets\SideNavWidget;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use common\models\School;
use yii\caching\DbDependency;
AppAsset::register($this);

// 本学校信息的缓存
$schoolModel = Yii::$app->cache->get('schoolModel');
if($schoolModel === false)
{
    $school      = YII::$app->session->get('school');
    $dependency  = new DbDependency(['sql' => 'SELECT * FROM school WHERE id = ' . $school]); 
    $schoolModel = School::findOne($school);
    Yii::$app->cache->set('schoolModel', $schoolModel, 3600, $dependency);
    // sleep(3);
}

$currentController = Yii::$app->controller->id;

//是否为基础设置 是则启用右上边栏
if(in_array($currentController, ['grade', 'classinfo', 'sundry']))
{
    array_unshift($this->params['breadcrumbs'], ['label' => '基础设置', 'url' => 'grade/index']);

    //杂项的类型
    $type = isset($_GET['type']) ? $_GET['type'] : false;
    $flag = true;
}
else
    $flag = false;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class='wrap'>
    <?php
        NavBar::begin([
            'brandLabel' => "<img class='pull-left img-margin-right logo-small' src='{$schoolModel->logo}'/>{$schoolModel->name}",
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        $menuItems = [
            ['label' => $schoolModel->name, 'url' => ['/site/index']],
        ];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => '登录', 'url' => ['/site/login']];
        } else {
            $menuItems[] = '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>';
        }
    ?>

    <?=
        Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
    ?>

    <?php NavBar::end();?>

    <div class='container '>
        <?= Alert::widget() ?>
    </div>
    <div class='container container-reset'>
            <?= 
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]); 
            ?>
            <div class='row'>
                <div class='col-md-2 bg-info center-block'>
                    <div class='list-group text-center'>
                        <img class='logo-middle' src='<?= $schoolModel->logo ?>'>
                        <br>
                        <span class='text-info'><?= $schoolModel->name ?></span>
                        <br>
                        <span>用户名</span>
                    </div>
                    <?= 
                        SideNavWidget::widget([
                          'items' => [
                              [
                                  'label' => '前台首页',
                                  'url' => ['site/index'],
                                  'linkOptions' => [],
                              ],
                              [
                                  'label' => '快捷操作',
                                  'items' => [
                                       ['label' => '班级管理', 'url' => '#'],
                                       // '<li class="dropdown-header">Dropdown Header</li>',
                                       ['label' => '学生管理', 'url' => '#'],
                                       ['label' => '考试管理', 'url' => '#'],
                                       ['label' => '错题本管理', 'url' => '#'],
                                  ],
                              ],
                              [
                                  'label' => '基础设置',
                                  'items' => [
                                       ['label' => '校园设置', 'url' => ['school/update'], 'active' => ($currentController == 'school')],
                                       // '<li class="dropdown-header">Dropdown Header</li>',
                                       ['label' => '基础设置', 'url' => ['grade/index'], 'active' => (in_array($currentController, ['grade', 'classinfo', 'sundry']))
                                       ],
                                       ['label' => '积分设置', 'url' => '#'],
                                       ['label' => '幻灯片管理', 'url' => '#'],
                                  ],
                              ],
                              [
                                  'label' => '教务管理',
                                  'items' => [
                                       ['label' => '教师管理', 'url' => ['teacher/index'], 'active' => ($currentController == 'teacher')],
                                       // '<li class="dropdown-header">Dropdown Header</li>',
                                       ['label' => '学生管理', 'url' => ['student/index'], 'active' => ($currentController == 'student')],
                                       ['label' => '成绩管理', 'url' => '#'],
                                       ['label' => '课程管理', 'url' => '#'],
                                       ['label' => '课时管理', 'url' => '#'],
                                       ['label' => '考试管理', 'url' => '#'],
                                  ],
                              ],
                          ],
                      ]);
                    ?>
                </div>
                <div class='col-md-10'>
                    <?php if ($flag):?>
                    <?= 
                Nav::widget([
                 'items' => [
                     [
                         'label' => '年级管理',
                         'url' => ['grade/index'],
                         'active' => ($currentController == 'grade'),
                         // 'linkOptions' => ['class'=> ($currentController == 'grade') ? 'bg-primary' : ''],
                     ],
                     [
                         'label' => '班级管理',
                         'url' => ['classinfo/index'],
                         'active' => ($currentController == 'classinfo'),
                         'linkOptions' => [],
                     ],
                     [
                         'label' => '科目管理',
                         'url' => ['sundry/index', 'type' => 'subject'],
                         'active' => ($type == 'subject'),
                         'linkOptions' => [],
                     ],
                     [
                         'label' => '星期管理',
                         'url' => ['sundry/index', 'type' => 'week'],
                         'active' => ($type == 'week'),
                         'linkOptions' => [],
                     ],
                     [
                         'label' => '时段管理',
                         'url' => ['sundry/index', 'type' => 'period'],
                         'active' => ($type == 'period'),
                         'linkOptions' => [],
                     ],
                 ],
                 'options' => ['class' =>'nav nav-tabs img-margin-bottom'],
                ]);
                    ?>
                    <?php endif;?>
                    <?= $content ?>
                </div>                
            </div>
    </div>
</div>

<footer class='footer'>
    <div class='container'>
        <p class='pull-left'>&copy; <?= Html::encode($schoolModel->name) ?> <?= date('Y') ?></p>

        <p class='pull-right'><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
