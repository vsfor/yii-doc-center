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
        $page_id = 2;
        $page = Page::findOne($page_id);

        $content =  $this->render('/diy/pdf', [
            'model' => $page,
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'filename' => 'YDCPage_'.$page->title.'_'.date("Ymd").'.pdf',
            'content' => $content,
            'cssFile' => '@webroot/static/css/pdf.css',
        ]);

        //设置权限
        $pdf->getApi()->SetProtection(['copy', 'print'],'ydc.jeen.wang','blog.jeen.wang');

        //设置一些文档标头信息
        $pdf->getApi()->SetTitle($page->title);
        $pdf->getApi()->SetAuthor('YDC.Jeen.Wang');
        $pdf->getApi()->SetCreator('Jeen.Wang');
        $pdf->getApi()->SetSubject('PHP mPdf Document');
        $pdf->getApi()->SetKeywords('php,yii2,pdf,mpdf,html,css');
        // For CJK render
        $pdf->getApi()->autoScriptToLang = true;
        $pdf->getApi()->autoVietnamese = true;
        $pdf->getApi()->autoArabic = true;
        $pdf->getApi()->autoLangToFont = true;
        // Add watermark text
        $pdf->getApi()->SetWatermarkText('ydc.jeen.wang',0.05);
        $pdf->getApi()->showWatermarkText = true;
        $pdf->getApi()->watermark_font = 'Sun-ExtA';//支持中文

        $pageHeader = [
            'L' => [
                'content' => $page->title,
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' =>  'serif',
                'color' => '#ff0000',
            ],
            'C' => [
                'content' => '',
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' =>  'serif',
                'color' => '#666666',
            ],
            'R' => [
                'content' => 'Document Page From <a href="http://ydc.jeen.wang/">YDC</a>',
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' =>  'serif',
                'color' => '#888888',
            ],
            'line' => 1,
        ];
        $pdf->getApi()->DefHeaderByName('diyHeader', $pageHeader);
        $pdf->getApi()->mirrorMargins = 1; //Odd基数  Even偶数
        $pdf->getApi()->SetHeaderByName('diyHeader', 'O');
        $pdf->getApi()->SetHeaderByName('diyHeader', 'E');

        $pageFooter = [
            'L' => [
                'content' => '{DATE Y-m-d}({nb})',
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' =>  'serif',
                'color' => '#888888',
            ],
            'C' => [
                'content' => '',
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' =>  'serif',
                'color' => '#ff0000',
            ],
            'R' => [
                'content' => '{PAGENO}/{nbpg}',
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' =>  'serif',
                'color' => '#222222',
            ],
            'line' => 1,
        ];
        $pdf->getApi()->DefFooterByName('diyFooter', $pageFooter);
        $pdf->getApi()->mirrorMargins = 1;
        $pdf->getApi()->SetHTMLFooterByName('diyFooter', 'O');
        $pdf->getApi()->SetHTMLFooterByName('diyFooter', 'E');


        // return the pdf output as per the destination setting
        return $pdf->render();
    }
}
