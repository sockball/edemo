<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class RedirectAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/redirect.css',
    ];
    public $js = [
        'js/redirect/base.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
