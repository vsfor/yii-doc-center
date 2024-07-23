<?php

namespace app\controllers;

use app\components\PdfLib;
use app\components\ProjectLib;
use app\models\PageHistory;
use app\models\PageSearch;
use Yii;
use app\models\Page;
use yii\web\NotFoundHttpException;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends ControllerBase
{
    /**
     * 搜索文档
     * @param $doc_text
     * @param $project_id
     * @return string
     */
    public function actionSearch($doc_text, $project_id)
    {
        $projectLib = ProjectLib::getInstance();
        $leftMenu = $projectLib->getMenu($project_id);
        
        $searchModel = new PageSearch();
        $searchModel->content = trim($doc_text);
        $searchModel->project_id = intval($project_id);
        $dataProvider = $searchModel->search([]);
        
        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'leftMenu' => $leftMenu,
        ]);
    }
    
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
            Yii::$app->getCache()->delete("Project:Pdf:$project_id");

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
                Yii::$app->getCache()->delete("Project:Pdf:$project_id");

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
        Yii::$app->getCache()->delete("Project:Pdf:$project_id");

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

    /**
     * 获取文档PDF可下载格式
     * @param $page_id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionGetpdf($page_id)
    {
        $this->layout = false;
        $page = $this->findModel($page_id);
        $content =  $this->render('pdf', [
            'model' => $page,
        ]);

//        return PdfLib::getInstance()
//            ->makeMpdf($page->title)
//            ->setPdfHeader(null, $page->title)
//            ->writeHtml($content)
//            ->output($page->title);

        $lib = PdfLib::getInstance();
        $lib->makeMpdf($page->title);
        $lib->setPdfHeader(null, $page->title);
        $lib->writeHtml($content);

        return $lib->output($page->title);
    }

}
