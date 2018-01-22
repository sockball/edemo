<?php

use backend\widgets\JsBlock;
use backend\assets\RedirectAsset;
use yii\helpers\Html;
use yii\helpers\Url;

RedirectAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml'>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <title><?= $title ?></title>
        <?php $this->head() ?>
    </head>
    <body>
<?php $this->beginBody() ?>

    <div class='mianBox'>
        <img src='images/yun0.png' class='yun yun0' />
        <img src='images/yun1.png' class='yun yun1' />
        <img src='images/yun2.png' class='yun yun2' />
        <img src='images/bird.png' class='bird' />
        <img src='images/san.png' class='san' />
        <div class='tipInfo'>
            <div class='in'>
                <div class='textThis'>
                    <h2><?= $msg ?></h2>
                    <p>
                        <span>页面自动<?= Html::a('跳转', $url, ['id' => 'href']) ?></span>
                        <span>等待<b id='wait'><?= $wait ?></b>秒</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

<?php $this->endBody() ?>
    </body>
</html>

<?php JsBlock::begin(); ?>
<script type='text/javascript'>
(function() {
        var wait = <?= $wait ?>, 
            href = '<?= $url ?>';
        var interval = setInterval(function() {
            $('#wait').html(--wait);

            if (wait < 1)
            {
                clearInterval(interval);
                if(href == '')
                    history.go(-1);
                else
                    location.href = href;
            }
        }, 1000);
    })();
</script>
<?php JsBlock::end(); ?>

<?php $this->endPage() ?>
