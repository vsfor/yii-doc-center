<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use Yii;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Nenad Zivkovic <nenad@freetuts.org>
 * 
 * @since 2.0
 */
class AppAssetFullPage extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@themes';

    public $css = [
        'css/bootstrap.min.css',
        'css/style.css',
        '/static/editor.md/css/editormd.css',
        '/static/editor.md/css/editormd.preview.css',
        '/static/editor.md/lib/codemirror/codemirror.min.css',
        '/static/editor.md/lib/codemirror/addon/dialog/dialog.css',
        '/static/editor.md/lib/codemirror/addon/search/matchesonscrollbar.css',
        '/static/editor.md/lib/codemirror/addon/fold/foldgutter.css',
//        '/static/editor.md/lib/katex/katex.min.css',//有动态加载
        '/static/css/jquery.fullpage.min.css', //for full page
        '/static/uptop/uptop.css', //返回顶部 ---
        '/static/toastmessage/resources/css/jquery.toastmessage.css',// for jrbac
        '/static/css/app.css',
    ];


    public $js = [
//        '/static/editor.md/lib/marked.js',//  #   ##  解析有问题
        '/static/editor.md/lib/marked.min.js', //有修改 image 解析方法
        '/static/editor.md/lib/prettify.min.js',
        '/static/editor.md/lib/raphael.min.js',
        '/static/editor.md/lib/underscore.min.js',
        '/static/editor.md/lib/sequence-diagram.min.js',
        '/static/editor.md/lib/flowchart.min.js',
        '/static/editor.md/lib/jquery.flowchart.min.js',
//        '/static/editor.md/lib/katex/katex.min.js',//有动态加载
        '/static/editor.md/editormd.js',
        '/static/uptop/uptop.js', //返回顶部 ---
        '/static/toastmessage/javascript/jquery.toastmessage.js', // for jrbac
        '/static/js/scrolloverflow.min.js', // for full page
        '/static/js/jquery.fullpage.min.js', // for full page
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
