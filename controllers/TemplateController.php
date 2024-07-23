<?php

namespace app\controllers;

use app\components\TemplateLib;
use Yii;
use app\models\Template;
use app\models\TemplateSearch;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TemplateController implements the CRUD actions for Template model.
 */
class TemplateController extends ControllerBase
{

    /**
     * 我的模板列表
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(false);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 查看模板
     *
     * @param  integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * 编辑模板
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws MethodNotAllowedHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->user->can('updateTemplate', ['model' => $model]))
        {
            if ($model->load(Yii::$app->request->post()) && $model->save())
            {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else
            {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        else
        {
            throw new MethodNotAllowedHttpException(Yii::t('app', 'You are not allowed to access this page.'));
        }
    }

    /**
     * 删除模板
     *
     * @param  integer $id
     * @return mixed
     *
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect('index');
    }


    /**
     * 获取模板内容
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionGetContent($id)
    {
        if (in_array($id, ['api','table'])) {
            return (new TemplateLib())->getSystemTemplateContent($id);
        }
        $model = $this->findModel($id);
        if ($model && $model->content) {
            return $model->content;
        } else {
            return '';
        }
    }

    /**
     * 添加页面内容为模板
     * @return string
     */
    public function actionAddPage()
    {
        $title = Yii::$app->getRequest()->post('title', '');
        $content = Yii::$app->getRequest()->post('content', '');
        if ($title && $content) {
            $userId = Yii::$app->getUser()->getId();
            $existCnt = (int) Template::find()->where([
                'author_id' => $userId,
            ])->count();
            if ($existCnt >= 5) {
                return 'error';
            }
            $template = new Template();
            $template->author_id = $userId;
            $template->title = strval($title);
            $template->content = strval($content);
            if ($template->save()) {
                return 'success';
            }
        }
        return 'error';
    }
    
    /**
     * Finds the Template model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Template the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $userId = \Yii::$app->getUser()->getId();
        /** @var Template $model */
        $model = Template::find()
            ->where([
                'id' => $id,
                'author_id' => $userId,
            ])->one();
        if (!empty($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
