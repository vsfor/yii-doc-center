<?php

namespace app\controllers;

use app\components\Jeen;
use app\components\PdfLib;
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
     * @return string|mixed
     * @throws NotFoundHttpException
     */
    public function actionGetpdf($catalog_id, $project_id)
    {
        $this->layout = false;
        $cacheKey = "Project:Pdf:$project_id:$catalog_id";
        $cache = \Yii::$app->getCache()->get($cacheKey);
        if ($cache) {
//            return $cache;
        }
        $project = $this->findModel($project_id);
        if (!$project) {
            return $this->goHome();
        }

        $catalog = $this->findModel($catalog_id);
        if (!$catalog) {
            return $this->goHome();
        }
        $lib = ProjectLib::getInstance();
        $itemList = [];
        $itemList = $lib->getCatPdfList($project_id, $catalog_id, $itemList);

        $pdfLib = PdfLib::getInstance();
        $pdfLib->makeMpdf($catalog->name);
        $pdfLib->setPdfHeader($catalog->name);

        $pdfLib->WriteHTML('<bookmark content="'.$catalog->name.'" level="0" />');

        foreach ($itemList as $_i => $item) {
            switch ($item['type']) {
                case 'page': {
                    $pdfLib->WriteHTML('<bookmark content="'.$item['data']['title'].'" level="'.$item['level'].'" />');
                    $pageHtml = $this->render('/page/pdf', ['model' => $item['data']]);
                    $pdfLib->WriteHTML($pageHtml);
                } break;
                case 'catalog': {
                    if ($_i == 0) {
                        $pdfLib->writeHtml('<h1 style="text-align:center;padding-top: 260px;">'.$catalog->name.'</h1>');
                    }
                    if ($item['level'] == 1) {
                        $pdfLib->setPdfHeader($item['data']['name']);
                        $pdfLib->addPage();
                    } else {
                        $pdfLib->setPdfHeader(null, $item['data']['name']);
                    }
                    $pdfLib->WriteHTML('<bookmark content="'.$item['data']['name'].'" level="'.$item['level'].'" />');
                } break;
            }
        }

        $cache = $pdfLib->output($catalog->name);
        \Yii::$app->getCache()->set($cacheKey, $cache);
        return $cache;
    }

}
