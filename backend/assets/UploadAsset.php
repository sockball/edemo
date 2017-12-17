<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class UploadAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/fileupload/jquery.fileupload.css',
        // 'css/fileupload/jquery.fileupload-ui.css',
    ];
    public $js = [
        'js/fileupload/jquery.ui.widget.js',
        'js/fileupload/jquery.fileupload.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
