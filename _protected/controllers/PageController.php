<?php

namespace app\controllers;

use app\components\ProjectLib;
use app\models\PageHistory;
use app\models\ProjectMember;
use kartik\mpdf\Pdf;
use Yii;
use app\models\Page;
use yii\web\NotFoundHttpException;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends ControllerBase
{
    /**
     * 查看项目文档
     * @param int $page_id
     * @param int $project_id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionView($page_id, $project_id)
    {
        $project_id = intval($project_id);
        $page_id = intval($page_id);
        $this->initGoBackUrl();

        $projectLib = ProjectLib::getInstance();
        $leftMenu = $projectLib->getMenu($project_id);
        $historyList = $projectLib->getPageHistoryList($page_id);
        $preNext = $projectLib->getPagePreNext($project_id, $page_id);
        return $this->render('view', [
            'model' => $this->findModel($page_id),
            'leftMenu' => $leftMenu,
            'historyList' => $historyList,
            'preNext' => $preNext,
        ]);
    }

    /**
     * 添加项目文档
     * @param int $project_id
     * @return string|\yii\web\Response
     */
    public function actionCreate($project_id)
    {
        $project_id = intval($project_id);
        $userId = \Yii::$app->getUser()->getId();
        $model = new Page();
        $model->project_id = $project_id;
        $model->author_id = $userId;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //cache reset logic
            Yii::$app->getCache()->delete("Project:Menu:$project_id");
            Yii::$app->getCache()->delete("Project:Catalog:$project_id");
            Yii::$app->getCache()->delete("Project:DocList:$project_id");
            Yii::$app->getCache()->delete("Project:PageList:$project_id");

            Yii::$app->getSession()->addFlash('success',Yii::t('app', 'Create Success'));
            return $this->redirect(['view', 'page_id' => $model->id, 'project_id' => $project_id]);
        }
        $projectLib = ProjectLib::getInstance();
        $leftMenu = $projectLib->getMenu($project_id);
        $catalogs = $projectLib->getCatOptions($project_id);
        return $this->render('create', [
            'model' => $model,
            'leftMenu' => $leftMenu,
            'catalogs' => $catalogs,
        ]);
    }

    /**
     * 更新项目文档
     * @param int $page_id
     * @param int $project_id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($page_id, $project_id)
    {
        $project_id = intval($project_id);
        $page_id = intval($page_id);
        $model = $this->findModel($page_id);

        if ($model->load(Yii::$app->request->post())) {
            $historyAttrs = $model->getOldAttributes();
            $model->author_id = Yii::$app->getUser()->getId();
            if ($model->save()) {
                //仅当文档内容有变更时,保存历史版本
                if ($model->content != $historyAttrs['content']) {
                    $history = new PageHistory();
                    $history->page_id = $historyAttrs['id'];
                    unset($historyAttrs['id']);
                    $history->setAttributes($historyAttrs);
                    $history->save();
                }
                //cache reset logic
                Yii::$app->getCache()->delete("Page:Pdf:$page_id");
                Yii::$app->getCache()->delete("Project:Menu:$project_id");
                Yii::$app->getCache()->delete("Project:Catalog:$project_id");
                Yii::$app->getCache()->delete("Project:DocList:$project_id");
                Yii::$app->getCache()->delete("Project:PageList:$project_id");

                Yii::$app->getSession()->addFlash('success',Yii::t('app', 'Update Success'));
                return $this->redirect(['view', 'page_id' => $model->id, 'project_id' => $project_id]);
            }
        }
        $projectLib = ProjectLib::getInstance();
        $leftMenu = $projectLib->getMenu($project_id);
        $catalogs = $projectLib->getCatOptions($project_id);
        return $this->render('update', [
            'model' => $model,
            'leftMenu' => $leftMenu,
            'catalogs' => $catalogs,
        ]);
    }

    /**
     * 删除项目文档
     * @param $page_id
     * @param $project_id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($page_id, $project_id)
    {
        $project_id = intval($project_id);
        $page_id = intval($page_id);
        $model = $this->findModel($page_id);
        if ($model && $model->project_id == $project_id) {
            $model->status = Page::STATUS_DELETED;
            $model->save();
        }
        //cache reset logic
        Yii::$app->getCache()->delete("Page:Pdf:$page_id");
        Yii::$app->getCache()->delete("Project:Menu:$project_id");
        Yii::$app->getCache()->delete("Project:Catalog:$project_id");
        Yii::$app->getCache()->delete("Project:DocList:$project_id");
        Yii::$app->getCache()->delete("Project:PageList:$project_id");

        return $this->redirect(['/project/view', 'project_id' => $project_id]);
    }

    /**
     * useful
     * @param int $id
     * @return Page
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Page::find()->where('`id`=:id and `status`=:status', [
                ':id' => $id,
                ':status' => Page::STATUS_NORMAL
            ])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 查看文档历史版本
     * @param $id
     * @return mixed
     */
    public function actionGethistory($id)
    {
        $ret = [
            'time' => '',
            'title' => 'Not Found!',
            'description' => '',
            'content' => '',
        ];
        $history = PageHistory::findOne($id);
        if ($history) {
            $ret['time'] = date("Y-m-d H:i:s", $history->updated_at);
            $ret['title'] = $history->title;
            $ret['description'] = $history->description;
            $ret['content'] = $history->content;
        }
        return json_encode($ret);
    }

    /**  */
    public function actionGetpdf($page_id)
    {
        $this->layout = false;
        $cacheKey = "Page:Pdf:$page_id";
        $cache = \Yii::$app->getCache()->get($cacheKey);
        if ($cache) {
            return $cache;
        }
        $page = $this->findModel($page_id);

        $content =  $this->render('pdf', [
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
        $pdf->getApi()->SetProtection(['copy', 'print'], 'ydc.jeen.wang');

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
        $cache = $pdf->render();
        \Yii::$app->getCache()->set($cacheKey, $cache);
        return $cache;
    }

}
