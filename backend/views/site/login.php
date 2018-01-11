<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
$this->registerCssFile('css/login.css', ['depends' => 'backend\assets\AppAsset']);
$this->title = '易掌分系统后台登录';
?>

    <div class='login-logo'>
        <a href='index.html'><img src='images/logo.png'></a>
    </div>
    <h2 class='form-heading'>易掌分教务系统</h2>
    <div class='app-cam'>
        <?php
            $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [  
                    'template' => '{input}{hint}{error}',  
                    'inputOptions' => ['class' => 'text'],  
                ],
            ]); 
        ?>

        <?= $form->field($model, 'username')->textInput(['placeholder' => '请输入用户名']) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder' => '请输入密码']) ?>

        <?= 
            $form->field($model, 'verifyCode')->widget(Captcha::className(),[
                'captchaAction'=>'site/captcha', //默认指向site/captcha
                'template' => '{input}{image}',
                'options' => ['class' => 'text', 'placeholder' => '请输入验证码'],
                'imageOptions' => ['alt' => '换一张', 'title' => '换一张'],
            ]);
        ?>

        <?= ''//$form->field($model, 'rememberMe')->checkbox() ?>

        <div class='submit'>
            <?= Html::submitButton('Login', ['class' => 'login', 'name' => 'login-button']) ?>
        </div>

<!--         <div class='login-social-link'>
            <a href='index.html' class='facebook'>
                Facebook
            </a>
            <a href='index.html' class='twitter'>
                Twitter
            </a>
        </div> -->
        <ul class='new'>
            <li class='new_left'><p><a href='#'>忘记密码 ?</a></p></li>
            <div class='clearfix'></div>
        </ul>

        <?php ActiveForm::end(); ?>
    </div>
    <div class='copy_layout Copyright'>
        <p>Copyright &copy; 2018.易掌分</p>
    </div>
