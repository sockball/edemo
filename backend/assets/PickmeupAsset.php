<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class PickmeupAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/pickmeup.css',
    ];
    public $js = [
        'js/pickmeup.js',
    ];
    public $depends = [
    
    ];
}
