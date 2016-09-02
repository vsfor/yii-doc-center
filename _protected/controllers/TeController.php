<?php
namespace app\controllers;

use app\components\ProjectLib;
use app\models\Page;
use kartik\mpdf\Pdf;
use yii\helpers\Markdown;
use yii\web\Controller;
use Yii;

/**
 * test controller.
 * It is responsible for displaying static pages, logging users in and out,
 * sign up and account activation, and password reset.
 */
class TeController extends Controller
{
    public function actionIndex()
    {
        $this->layout = false;
        $page_id = 6;
        $page = Page::findOne($page_id);

        $content =  $this->render('/diy/pdf', [
            'model' => $page,
        ]);

//        return $content;
//        echo htmlentities($content);exit();

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'filename' => 'YDCPage_'.$page->title.'_'.date("Ymd").'.pdf',
            'content' => $content,
            'cssFile' => '@webroot/static/css/pdf.css',
            // set mPDF properties on the fly
            'options' => [
                'title' => $page->title,
                'autoLangToFont' => true,    //这几个配置加上可以显示中文 y
                'autoScriptToLang' => true,  //这几个配置加上可以显示中文 y
                'autoVietnamese' => true,    //这几个配置加上可以显示中文 n
                'autoArabic' => true,        //这几个配置加上可以显示中文 n
            ],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>['Document Page From <a href="http://ydc.jeen.wang/">YDC</a>'],
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }
}
