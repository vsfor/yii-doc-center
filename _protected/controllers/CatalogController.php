<?php

namespace app\controllers;

use app\components\Jeen;
use app\components\ProjectLib;
use app\models\ProjectMember;
use kartik\mpdf\Pdf;
use Yii;
use app\models\Catalog;
use app\models\CatalogSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CatalogController implements the CRUD actions for Catalog model.
 */
class CatalogController extends ControllerBase
{
    /**
     * 添加项目目录
     * @param $project_id
     * @return string|\yii\web\Response
     */
    public function actionCreate($project_id)
    {
        $project_id = intval($project_id);
        $model = new Catalog();
        $model->project_id = $project_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //cache reset logic
            Yii::$app->getCache()->delete("Project:Menu:$project_id");
            Yii::$app->getCache()->delete("Project:Catalog:$project_id");
            Yii::$app->getCache()->delete("Project:DocList:$project_id");
            Yii::$app->getCache()->delete("Project:PageList:$project_id");
            Yii::$app->getCache()->delete("Project:Pdf:$project_id");

            Yii::$app->getSession()->addFlash('success',Yii::t('app', 'Create Success'));

            return $this->goBack();
        }
        
        $projectLib = ProjectLib::getInstance();
        $leftMenu = $projectLib->getMenu($project_id);
        $parents = $projectLib->getCatOptions($project_id,0,2,1);
        return $this->render('create', [
            'model' => $model,
            'leftMenu' => $leftMenu,
            'parents' => $parents,
        ]);
    }

    /**
     * 更新项目目录信息
     * @param $catalog_id
     * @param $project_id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($catalog_id, $project_id)
    {
        $project_id = intval($project_id);
        $catalog_id = intval($catalog_id);
        $model = $this->findModel($catalog_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //cache reset logic
            Yii::$app->getCache()->delete("Project:Menu:$project_id");
            Yii::$app->getCache()->delete("Project:Catalog:$project_id");
            Yii::$app->getCache()->delete("Project:DocList:$project_id");
            Yii::$app->getCache()->delete("Project:PageList:$project_id");
            Yii::$app->getCache()->delete("Project:Pdf:$project_id");

            Yii::$app->getSession()->addFlash('success',Yii::t('app', 'Update Success'));

            return $this->goBack();
        }

        $projectLib = ProjectLib::getInstance();
        $leftMenu = $projectLib->getMenu($project_id);
        $parents = $projectLib->getSubCatOptions($project_id);
        return $this->render('update', [
            'model' => $model,
            'leftMenu' => $leftMenu,
            'parents' => $parents,
        ]);
    }

    /**
     * 移除项目目录
     * @param $catalog_id
     * @param $project_id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($catalog_id, $project_id)
    {
        $project_id = intval($project_id);
        $catalog_id = intval($catalog_id);
        $model = $this->findModel($catalog_id);
        if ($model) {
            $model->status = Catalog::STATUS_DELETED;
            $model->save();
        }
        //cache reset logic
        Yii::$app->getCache()->delete("Project:Menu:$project_id");
        Yii::$app->getCache()->delete("Project:Catalog:$project_id");
        Yii::$app->getCache()->delete("Project:DocList:$project_id");
        Yii::$app->getCache()->delete("Project:PageList:$project_id");
        Yii::$app->getCache()->delete("Project:Pdf:$project_id");

        return $this->goBack();
    }

    /**
     * @param $id
     * @return Catalog
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Catalog::find()->where('`id`=:id and `status`=:status',[
                ':id' => intval($id),
                ':status' => Catalog::STATUS_NORMAL,
        ])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 导出目录为PDF文件
     * @param $catalog_id
     * @param $project_id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionGetpdf($catalog_id, $project_id)
    {
        $this->layout = false;
        $cacheKey = "Project:Pdf:$project_id:$catalog_id";
        $cache = \Yii::$app->getCache()->get($cacheKey);
        if ($cache) {
            return $cache;
        }
        $project = $this->findModel($project_id);
        if (!$project) {
            return $this->goHome();
        }
        $model = $this->findModel($catalog_id);
        if (!$model) {
            return $this->goHome();
        }
        $lib = ProjectLib::getInstance();
        $itemList = [];
        $itemList = $lib->getCatPdfList($project_id, $catalog_id, $itemList);

        //Jeen::echoln($itemList);exit();

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'filename' => 'YDCProject_'.$model->name.'_'.date("Ymd").'.pdf',
//            'content' => $content,
            'cssFile' => '@webroot/static/css/pdf.css',
        ]);

        //设置权限
        $pdf->getApi()->SetProtection(['copy', 'print'], '', 'ydc.jeen.wang');

        //设置一些文档标头信息
        $pdf->getApi()->SetTitle($model->name);
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
        $pdf->getApi()->showWatermarkText = false;
        $pdf->getApi()->watermark_font = 'Sun-ExtA';//支持中文

        $pdf->getApi()->WriteHTML($pdf->getCss(), 1);

        $pageHeader = [
            'L' => [
                'content' => $model->name,
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' =>  'serif',
                'color' => '#435b67',
            ],
            'C' => [
                'content' => '',
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' =>  'serif',
                'color' => '#666666',
            ],
            'R' => [
                'content' => '',
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' =>  'serif',
                'color' => '#333333',
            ],
            'line' => 1,
        ];
        $pdf->getApi()->DefHeaderByName('diyHeader', $pageHeader);
        $pdf->getApi()->mirrorMargins = 1; //Odd基数  Even偶数
        $pdf->getApi()->SetHeaderByName('diyHeader', 'O');
        $pdf->getApi()->SetHeaderByName('diyHeader', 'E');

        $pageFooter = [
            'L' => [
                'content' => '{DATE Y-m-d}/{nb}/'.$project_id.'-'.$catalog_id,
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' =>  'serif',
                'color' => '#888888',
            ],
            'C' => [
                'content' => '{PAGENO}/{nbpg}',
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' =>  'serif',
                'color' => '#111111',
            ],
            'R' => [
                'content' => 'Document Project From <a href="http://ydc.jeen.wang/">YDC</a>',
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' =>  'serif',
                'color' => '#93aebb',
            ],
            'line' => 1,
        ];
        $pdf->getApi()->DefFooterByName('diyFooter', $pageFooter);
        $pdf->getApi()->mirrorMargins = 1;
        $pdf->getApi()->SetHTMLFooterByName('diyFooter', 'O');
        $pdf->getApi()->SetHTMLFooterByName('diyFooter', 'E');
        $tempBookmarks = [];
        foreach ($itemList as $k=>$item) {

            if ($item['type'] == 'page') {
                $pageHeader['R']['content'] = $item['data']['title'];
                $pdf->getApi()->DefHeaderByName('diyHeader', $pageHeader);
                $pdf->getApi()->mirrorMargins = 1; //Odd基数  Even偶数
                $pdf->getApi()->SetHeaderByName('diyHeader', 'O');
                $pdf->getApi()->SetHeaderByName('diyHeader', 'E');
                $pdf->getApi()->AddPage();
                while ($tempBookmarks) {
                    $tempBookmark = array_shift($tempBookmarks);
                    $pdf->getApi()->Bookmark($tempBookmark['txt'], $tempBookmark['level']);
                }
                $pdf->getApi()->Bookmark($item['data']['title'], $item['level']);

                $pageHtml = $this->render('/page/pdf', ['model' => $item['data']]);
                $pdf->getApi()->WriteHTML($pageHtml);

            } elseif ($item['type'] == 'catalog') {
                $pageHeader['C']['content'] = $item['data']['name'];
                array_push($tempBookmarks, [
                    'txt' => $item['data']['name'],
                    'level' => $item['level']
                ]);
            }
        }

        while ($tempBookmarks) {
            $tempBookmark = array_shift($tempBookmarks);
            $pdf->getApi()->Bookmark($tempBookmark['txt'], $tempBookmark['level'], 1);
        }

        $cache = $pdf->getApi()->Output($pdf->filename, $pdf::DEST_BROWSER);

        \Yii::$app->getCache()->set($cacheKey, $cache);
        return $cache;
    }

}
