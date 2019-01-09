<?php

namespace app\controllers;

use app\components\Jeen;
use app\components\ProjectLib;
use app\models\ProjectMember;
use app\models\User;
use kartik\mpdf\Pdf;
use Yii;
use app\models\Project;
use app\models\ProjectSearch;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends ControllerBase
{
    /**
     * 我的项目列表
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'oneColumn.php';

        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 项目详情页
     * @param int $project_id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionView($project_id)
    {
        $project_id = intval($project_id);
        $model = $this->findModel($project_id);
        if (!$model) {
            return $this->redirect(['index']);
        }

        $this->initGoBackUrl();

        $projectLib = ProjectLib::getInstance();
        $leftMenu = $projectLib->getMenu($model->id);
        return $this->render('view', [
            'model' => $model,
            'leftMenu' => $leftMenu,
        ]);
    }

    /**
     * 创建项目
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $this->layout = 'fullPage.php';

        $model = new Project();
        $model->user_id = \Yii::$app->getUser()->getId();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $pm = new ProjectMember();
            $pm->project_id = $model->id;
            $pm->user_id = $model->user_id;
            $pm->level = ProjectMember::LEVEL_OWNER;
            if (!$pm->save()) {
                Yii::$app->getSession()->addFlash('warning',Yii::t('app', 'Create Failed,please contact us.'));
                return $this->redirect(['/site/contact']);
            } else {
                ProjectLib::getInstance()->setMemberLevelRole($pm->user_id, $pm->level);
                User::updateAllCounters(['project_limit' => -1], '`id`=:id', [':id'=>$model->user_id]);

                Yii::$app->getSession()->addFlash('success',Yii::t('app', 'Create Success'));
                return $this->redirect(['view', 'project_id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 更新项目
     * @param int $project_id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($project_id)
    {
        $project_id = intval($project_id);
        $model = $this->findModel($project_id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->addFlash('success',Yii::t('app', 'Update Success'));
            return $this->redirect(['view', 'project_id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 删除项目
     * @param int $project_id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($project_id)
    {
        $project_id = intval($project_id);
        
        $model = $this->findModel($project_id);
        if ($model) {
            $model->status = Project::STATUS_DELETED;
            if ($model->save()) {
                User::updateAllCounters(['project_limit' => 1], '`id`=:id', [':id'=>$model->user_id]);
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return Project
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Project::find()->where('`id`=:id and `status`=:status', [
                ':id' => $id,
                ':status' => Project::STATUS_NORMAL,
            ])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 项目文档管理
     * @param int $project_id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionManage($project_id)
    {
        $project_id = intval($project_id);
        $model = $this->findModel($project_id);
        if (!$model) {
            return $this->redirect(['index']);
        }

        $this->initGoBackUrl();

        $projectLib = ProjectLib::getInstance();
        $leftMenu = $projectLib->getMenu($model->id);
        $docList = $projectLib->getDocList($model->id);
        return $this->render('manage', [
            'model' => $model,
            'leftMenu' => $leftMenu,
            'docList' => $docList,
        ]);
    }

    /**
     * 项目成员管理
     * @param int $project_id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionMember($project_id)
    { 
        $model = $this->findModel($project_id);
        if (!$model) {
            return $this->redirect(['index']);
        }

        $this->initGoBackUrl();

        $projectLib = ProjectLib::getInstance();
        $leftMenu = $projectLib->getMenu($project_id);
        $memberList = $projectLib->getMemberList($project_id);
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setModels($memberList);
        return $this->render('member', [
            'model' => $model,
            'leftMenu' => $leftMenu,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 添加项目成员
     * @return \yii\web\Response
     */
    public function actionAddMember()
    {
        $projectId = intval(\Yii::$app->getRequest()->post('project_id',0));
        $username = trim(\Yii::$app->getRequest()->post('username',''));
        $member = User::findByUsername($username);
        if (!$member) {
            Yii::$app->getSession()->addFlash('error',Yii::t('app','user not found by username:'.$username));
            return $this->goBack();
        }

        $cnt = ProjectMember::find()->where('`project_id`=:project_id and `user_id`=:user_id',[
            ':project_id' => $projectId,
            ':user_id' => $member->id
        ])->count();
        if ($cnt) {
            Yii::$app->getSession()->addFlash('error',Yii::t('app','project member already exist with username:'.$username));
            return $this->goBack();
        }

        $pm = new ProjectMember();
        $pm->project_id = $projectId;
        $pm->user_id = $member->id;
        $pm->level = intval(Yii::$app->getRequest()->post('level',ProjectMember::LEVEL_READER));
        if ($pm->save()) {
            //cache reset logic
            Yii::$app->getCache()->delete("Project:MemberIds:$projectId");
            Yii::$app->getCache()->delete("Project:MemberList:$projectId");
            Yii::$app->getCache()->delete("Project:MemberLevel:$projectId:{$pm->user_id}");

            ProjectLib::getInstance()->setMemberLevelRole($pm->user_id, $pm->level);

            Yii::$app->getSession()->addFlash('success',Yii::t('app','Member add success.'));
        } else {
            Yii::$app->getSession()->addFlash('error',Yii::t('app','Member add failed,please contact us.'));
        }

        return $this->goBack();
    }

    /**
     * 设置项目成员级别
     * @param $project_id
     * @param $user_id
     * @param $level
     * @return \yii\web\Response
     */
    public function actionSetMemberLevel($project_id,$user_id,$level)
    {
        $project_id = intval($project_id);
        if (Yii::$app->getRequest()->getIsPost()) {
            if (isset(ProjectMember::$levelList[$level])) {
                if(ProjectMember::updateAll(['level' => intval($level)],[
                    'project_id' => $project_id,
                    'user_id' => $user_id,
                ])) {
                    //cache reset logic
                    Yii::$app->getCache()->delete("Project:MemberList:$project_id");
                    Yii::$app->getCache()->delete("Project:MemberLevel:$project_id:$user_id");

                    ProjectLib::getInstance()->setMemberLevelRole($user_id, $level);

                    Yii::$app->getSession()->addFlash('success',Yii::t('app','Update Success'));
                    return $this->goBack();
                }
            }
        } 
        return $this->goHome();
    }

    /**
     * 移除项目成员
     * @param $project_id
     * @param $user_id
     * @return \yii\web\Response
     */
    public function actionDelMember($project_id,$user_id)
    {
        $project_id = intval($project_id);
        if (Yii::$app->getRequest()->getIsPost()) {
            if(ProjectMember::deleteAll([
                'project_id' => $project_id,
                'user_id' => $user_id,
            ])) {
                //cache reset logic
                Yii::$app->getCache()->delete("Project:MemberIds:$project_id");
                Yii::$app->getCache()->delete("Project:MemberList:$project_id");
                Yii::$app->getCache()->delete("Project:MemberLevel:$project_id:$user_id");
                
                Yii::$app->getSession()->addFlash('success',Yii::t('app','Delete Success'));
                
                return $this->goBack();
            }
        }
        return $this->goHome();
    }

    /**
     * 导出项目为PDF文件
     * @param $project_id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionGetpdf($project_id)
    {
        $this->layout = false;
        $cacheKey = "Project:Pdf:$project_id";
        $cache = \Yii::$app->getCache()->get($cacheKey);
        if ($cache) {
            return $cache;
        }
        $project = $this->findModel($project_id);
        if (!$project) {
            return $this->goHome();
        }
        $lib = ProjectLib::getInstance();
        $itemList = $lib->getPdfList($project_id);

//        Jeen::echoln($itemList);exit();

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'filename' => 'YDCProject_'.$project->name.'_'.date("Ymd").'.pdf',
//            'content' => $content,
            'cssFile' => '@webroot/static/css/pdf.css',
        ]);

        //设置权限
        $pdf->getApi()->SetProtection(['copy', 'print'], '', 'ydc.jeen.wang');

        //设置一些文档标头信息
        $pdf->getApi()->SetTitle($project->name);
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

        $pdf->getApi()->WriteHTML($pdf->getCss(), 1);

        $pageHeader = [
            'L' => [
                'content' => $project->name,
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
                'content' => '{DATE Y-m-d}/{nb}/'.$project_id,
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
        foreach ($itemList as $item) {
            if ($item['type'] == 'page') {
                $pageHeader['R']['content'] = $item['data']['title'];
                $pdf->getApi()->DefHeaderByName('diyHeader', $pageHeader);
                $pdf->getApi()->mirrorMargins = 1; //Odd基数  Even偶数
                $pdf->getApi()->SetHeaderByName('diyHeader', 'O');
                $pdf->getApi()->SetHeaderByName('diyHeader', 'E');

                $pdf->getApi()->AddPage();

                while ($tempBookmarks) {
                    $tempBookmark = array_shift($tempBookmarks);
                    $pdf->getApi()->Bookmark($tempBookmark['txt'], $tempBookmark['level'], 1);
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
