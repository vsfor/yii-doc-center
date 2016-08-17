<?php
namespace jext\jrbac;

use yii\web\AssetBundle;

class JrbacAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@themes';

    public $sourcePath = '@jext/jrbac/assets';
    public $css = [
        'css/bootstrap.min.css',
        '/static/css/jrbac.css',
        '/static/toastmessage/resources/css/jquery.toastmessage.css',
    ];
    public $js = [
        '/static/toastmessage/javascript/jquery.toastmessage.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
