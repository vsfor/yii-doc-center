<?php
namespace app\components;

use app\models\Catalog;
use app\models\Page;
use app\models\PageHistory;
use app\models\Project;
use app\models\ProjectMember;
use app\models\User;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

class ProjectLib
{
    private static $instance;

    /**
     * @return ProjectLib
     */
    public static function getInstance(){
        if(empty(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    private function __construct() { }
    public function __clone() { throw new \Exception('Clone is not allowed !'); }

    /**
     * 通过ID获取项目
     * @param int $id
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public function findModel($id)
    {
        if (($model = Project::find()->where('`id`=:id and `status`=:status', [
                ':id' => $id,
                ':status' => Project::STATUS_NORMAL,
            ])->one()) !== null) {
            return $model;
        }
        return false;
    }

    /**
     * 获取项目左侧导航菜单列表
     * @param $projectId
     * @return array|mixed
     */
    public function getMenu($projectId)
    {
        $projectId = intval($projectId);
        $cacheKey = "Project:Menu:$projectId";
        $cache = \Yii::$app->getCache()->get($cacheKey);
        if ($cache) {
            $menu = $cache;
        } else {
            $menu = [['label' => '>  '.Yii::t('app', 'Document List'), 'options' => ['class' => 'header']]];
            //项目根目录页面
            $pages = Page::find()
                ->where('`status`='.Page::STATUS_NORMAL)
                ->andWhere('`project_id`=:project_id and `catalog_id`=:catalog_id', [
                    ':project_id' => $projectId,
                    ':catalog_id' => 0
                ])
                ->orderBy('`sort_number` asc,`id` asc')
                ->all();
            /** @var Page $page */
            foreach ($pages as $page) {
                $menu[] = [
                    'label' => $page->title,
                    'icon' => 'file-text-o',
                    'url' => ['/page/view', 'page_id'=>$page->id, 'project_id'=>$projectId],
                ];
            }
            unset($pages);

            $cats = Catalog::find()
                ->where('`status`='.Catalog::STATUS_NORMAL)
                ->andWhere('`project_id`=:project_id and `parent_id`=:parent_id',[
                    ':project_id' => $projectId,
                    ':parent_id' => 0
                ])
                ->orderBy('`sort_number` asc,`id` asc')
                ->all();
            /** @var Catalog $cat */
            foreach ($cats as $cat) {
                $menu[] = [
                    'label' => $cat->name,
                    'icon' => 'folder-o',
                    'url' => 'javascript:;',
                    'items' => $this->getCatSubMenu($projectId, $cat->id),
                ];
            }
            unset($cats);

            \Yii::$app->getCache()->set($cacheKey, $menu);
        }
        $menu[] = [
            'label' => '==== ====',
            'options' => [
                'class' => 'header',
                'style'=>'padding:0 10px;letter-spacing:20px;'
            ]
        ];

        $auth = Yii::$app->getAuthManager();
        if ($auth->allow('/page/create', ['project_id' => $projectId])) {
            $menu[] = [
                'label' => Yii::t('app', 'Create Page'),
                'icon' => 'file-o',
                'url' => ['/page/create','project_id'=>$projectId]
            ];
        }
        if ($auth->allow('/catalog/create', ['project_id' => $projectId])) {
            $menu[] = [
                'label' => Yii::t('app', 'Create Catalog'),
                'icon' => 'plus-square-o',
                'url' => ['/catalog/create','project_id'=>$projectId]
            ];
        }
        if ($auth->allow('/project/manage', ['project_id'=>$projectId])) {
            $menu[] = [
                'label' => Yii::t('app', 'Manage Actions'),
                'icon' => 'gear',
                'url' => 'javascript:;',
                'items' => [
                    [
                        'label' => Yii::t('app', 'Manage Project Document'),
                        'icon' => 'folder-open-o',
                        'url' => ['/project/manage','project_id'=>$projectId]
                    ],
                    [
                        'label' => Yii::t('app', 'Manage Project Member'),
                        'icon' => 'users',
                        'url' => ['/project/member','project_id'=>$projectId]
                    ],
                ],
            ];
        }

        return $menu;
    }

    /**
     * 获取项目某个目录子菜单
     * @param $projectId
     * @param $catId
     * @return array
     */
    public function getCatSubMenu($projectId, $catId)
    {
        $projectId = intval($projectId);
        $catId = intval($catId);
        $menu = [];
        $pages = Page::find()
            ->where('`status`='.Page::STATUS_NORMAL)
            ->andWhere('`project_id`=:project_id and `catalog_id`=:catalog_id', [
                ':project_id' => $projectId,
                ':catalog_id' => $catId
            ])
            ->orderBy('`sort_number` asc,`id` asc')
            ->all();
        /** @var Page $page */
        foreach ($pages as $page) {
            $menu[] = [
                'label' => $page->title,
                'icon' => 'file-text-o',
                'url' => ['/page/view', 'page_id'=>$page->id, 'project_id'=>$projectId],
            ];
        }
        unset($pages);

        $cats = Catalog::find()
            ->where('`status`='.Catalog::STATUS_NORMAL)
            ->andWhere('`parent_id`=:paid',[
                ':paid' => $catId
            ])
            ->orderBy('`sort_number` asc,`id` asc')
            ->all();
        /** @var Catalog $cat */
        foreach ($cats as $cat) {
            $menu[] = [
                'label' => $cat->name,
                'icon' => 'folder-o',
                'url' => 'javascript:;',
                'items' => $this->getCatSubMenu($projectId, $cat->id),
            ];
        }
        unset($cats);

        return $menu;
    }

    /**
     * 获取项目目录层级结构 - 用于下拉选择
     * @param $projectId
     * @param int $parentId
     * @param int $depth
     * @param int $level
     * @return array|mixed
     */
    public function getCatOptions($projectId,$parentId=0,$depth = 3,$level=1)
    {
        if ($level > $depth) {
            return [];
        }
        $cacheKey = "Project:Catalog:$projectId";
        $cache = \Yii::$app->getCache()->get($cacheKey);
        if ($cache) {
            return $cache;
        }
        $cats = Catalog::find()
            ->where('`status`='.Catalog::STATUS_NORMAL)
            ->andWhere('`project_id`=:project_id and `parent_id`=:parent_id', [
                ':project_id' => $projectId,
                ':parent_id' => $parentId
            ])
            ->orderBy('`sort_number` asc,`id` asc')
            ->all();
        $list = [];
        $subPrefix = '';
        for($i = 1; $i<$level; $i++) {
            $subPrefix .= '&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        /** @var Catalog $cat */
        foreach ($cats as $cat) {
            $list[] = [
                'id' => $cat->id,
                'label' => $subPrefix.$cat->name,
            ];
            $subItems = $this->getCatOptions($projectId,$cat->id,$depth,$level+1);
            foreach ($subItems as $subItem) {
                $list[] = $subItem;
            }
        }
        if ($parentId==0 && $level==1) { 
            \Yii::$app->getCache()->set($cacheKey, $list);
        }
        return $list;
    }

    /**
     * 获取项目目录子层级
     * @param $projectId
     * @param int $parentId
     * @return array
     */
    public function getSubCatOptions($projectId, $parentId=0)
    {
        $rows = Catalog::find()
            ->where('`status`='.Catalog::STATUS_NORMAL)
            ->andWhere('`project_id`=:project_id and `parent_id`=:parent_id', [
                ':project_id' => $projectId,
                ':parent_id' => $parentId
            ])
            ->orderBy('`sort_number` asc,`id` asc')
            ->all();
        $list = [];
        /** @var Catalog $row */
        foreach ($rows as $row) {
            $list[] = [
                'id' => $row->id,
                'label' => $row->name,
            ];
        }
        return $list;
    }

    /**
     * 获取文档页面历史修改记录
     * @param $pageId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getPageHistoryList($pageId)
    {
        return PageHistory::find()
            ->where('`page_id`=:page_id', [
                ':page_id' => intval($pageId)
            ])
            ->orderBy('`id` desc')
            ->asArray()
            ->all();
    }

    /**
     * 获取项目文档结构
     * @param $projectId
     * @return array|mixed
     */
    public function getDocList($projectId)
    {
        $projectId = intval($projectId);
        $cacheKey = "Project:DocList:$projectId";
        $cache = \Yii::$app->getCache()->get($cacheKey);
        if ($cache) {
            return $cache;
        }
        $list = [];

        //项目根目录页面
        $pages = Page::find()
            ->where('`status`='.Page::STATUS_NORMAL)
            ->andWhere('`project_id`=:project_id and `catalog_id`=:catalog_id', [
                ':project_id' => $projectId,
                ':catalog_id' => 0
            ])
            ->orderBy('`sort_number` asc,`id` asc')
            ->all();
        /** @var Page $page */
        foreach ($pages as $page) {
            $list[] = [
                'type' => 'page',
                'data' => $page->toArray(),
            ];
        }
        unset($pages);

        $cats = Catalog::find()
            ->where('`status`='.Catalog::STATUS_NORMAL)
            ->andWhere('`project_id`=:project_id and `parent_id`=:parent_id',[
                ':project_id' => $projectId,
                ':parent_id' => 0
            ])
            ->orderBy('`sort_number` asc,`id` asc')
            ->all();
        /** @var Catalog $cat */
        foreach ($cats as $cat) {
            $list[] = [
                'type' => 'catalog',
                'data' => $cat->toArray(),
                'items' => $this->getCatDocList($projectId, $cat->id),
            ];
        }
        unset($cats);

        \Yii::$app->getCache()->set($cacheKey, $list);
        return $list;

    }

    /**
     * 获取目录文档结构
     * @param $projectId
     * @param $catId
     * @return array
     */
    public function getCatDocList($projectId, $catId)
    {
        $projectId = intval($projectId);
        $catId = intval($catId);
        $list = [];
        $pages = Page::find()
            ->where('`status`='.Page::STATUS_NORMAL)
            ->andWhere('`project_id`=:project_id and `catalog_id`=:catalog_id', [
                ':project_id' => $projectId,
                ':catalog_id' => $catId
            ])
            ->orderBy('`sort_number` asc,`id` asc')
            ->all();
        /** @var Page $page */
        foreach ($pages as $page) {
            $list[] = [
                'type' => 'page',
                'data' => $page->toArray(),
            ];
        }
        unset($pages);

        $cats = Catalog::find()
            ->where('`status`='.Catalog::STATUS_NORMAL)
            ->andWhere('`parent_id`=:paid',[
                ':paid' => $catId
            ])
            ->orderBy('`sort_number` asc,`id` asc')
            ->all();
        /** @var Catalog $cat */
        foreach ($cats as $cat) {
            $list[] = [
                'type' => 'catalog',
                'data' => $cat->toArray(),
                'items' => $this->getCatDocList($projectId, $cat->id),
            ];
        }
        unset($cats);

        return $list;
    }

    /**
     * 获取项目成员ID列表
     * @param $projectId
     * @return array|mixed
     */
    public function getMemberIds($projectId)
    {
        $cacheKey = "Project:MemberIds:$projectId";
        $cache = \Yii::$app->getCache()->get($cacheKey);
        if ($cache) {
            return $cache;
        }
        $rows = ProjectMember::find()
            ->select(['user_id'])
            ->where('`project_id` = :project_id', [
                ':project_id' => $projectId
            ])
            ->asArray()
            ->all();
        $userIds = ArrayHelper::getColumn($rows,'user_id');

        //cache clear logics
        \Yii::$app->getCache()->set($cacheKey, $userIds);
        return $userIds;
    }

    /**
     * 获取项目成员信息列表
     * @param $projectId
     * @return array|mixed
     */
    public function getMemberList($projectId)
    {
        $cacheKey = "Project:MemberList:$projectId";
        $cache = \Yii::$app->getCache()->get($cacheKey);
        if ($cache) {
            return $cache;
        }
        $projectId = intval($projectId);
        $list = [];
        $members = ProjectMember::find()->select(['user_id','level'])->where('`project_id`=:project_id', [
            ':project_id' => $projectId
        ])->orderBy('`level` desc')->all();
        /** @var ProjectMember $member */
        foreach ($members as $member) {
            $user = User::findOne($member->user_id);
            if (!$user) {
                continue;
            }
            $list[] = [
                'project_id' => $projectId,
                'user_id' => $member->user_id,
                'user_level' => $member->level,
                'username' => $user->username,
                'email' => $user->email,
            ];
        }

        Yii::$app->getCache()->set($cacheKey,$list);
        return $list;
    }

    /**
     * 获取项目成员等级信息
     * @param $projectId
     * @param $userId
     * @return array|mixed|null|\yii\db\ActiveRecord
     */
    public function getMemberLevel($projectId, $userId)
    {
        $cacheKey = "Project:MemberLevel:$projectId:$userId";
        $cache = Yii::$app->getCache()->get($cacheKey);
        if ($cache) {
            return $cache;
        }
        $pm = ProjectMember::find()->where('`project_id`=:project_id and `user_id`=:user_id',[
            ':project_id' => $projectId,
            ':user_id' => $userId,
        ])->asArray()->one();

        Yii::$app->getCache()->set($cacheKey, $pm);
        return $pm;
    }

    /**
     * 获取项目成员级别设置页面元素
     * @param $model
     * @return string
     */
    public function getMemberLevelSetHtml($model)
    {
        if (Yii::$app->getUser()->getId() == $model['user_id']) {
            return '';
        }
        $pm = $this->getMemberLevel($model['project_id'], Yii::$app->getUser()->getId());
        if (!$pm || $pm['level']<ProjectMember::LEVEL_ADMIN || $pm['level']<$model['user_level']) {
            return '';
        }
        $html = '';
        foreach (ProjectMember::$levelList as $k=>$level) {
            if ($k > $pm['level']) {
                continue;
            }
            if ($k == $model['user_level']) {
                $html .= Html::tag(
                    'li',
                    Html::a(Yii::t('app',$level),'javascript:;'),
                    ['class'=>'active']
                );
            } else {
                $html .= Html::tag(
                    'li',
                    Html::a(Yii::t('app',$level),[
                        '/project/set-member-level',
                        'project_id' => $model['project_id'],
                        'user_id' => $model['user_id'],
                        'level' => $k,
                        ],['data-method'=>'post']),
                    []
                );
            }
        }
        return $html;
    }

    /**
     * 设置用户级别 - 用于项目权限分级
     * @param int $userId 用户ID
     * @param int $level 级别
     * @return bool
     */
    public function setMemberLevelRole($userId, $level)
    {
        try {
            $auth = Yii::$app->getAuthManager();
            $roles = $auth->getRolesByUser($userId);
            switch ($level) {
                case ProjectMember::LEVEL_READER : {
                    if (!isset($roles['projectReader'])) {
                        $auth->assign($auth->getRole('projectReader'), $userId);
                    }
                } break;
                case ProjectMember::LEVEL_EDITOR : {
                    if (!isset($roles['projectEditor'])) {
                        $auth->assign($auth->getRole('projectEditor'), $userId);
                    }
                } break;
                case ProjectMember::LEVEL_ADMIN : {
                    if (!isset($roles['projectAdmin'])) {
                        $auth->assign($auth->getRole('projectAdmin'), $userId);
                    }
                } break;
                case ProjectMember::LEVEL_OWNER : {
                    if (!isset($roles['projectOwner'])) {
                        $auth->assign($auth->getRole('projectOwner'), $userId);
                    }
                } break;
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 获取项目所有文档页面
     * @param int $projectId 项目ID
     * @return array|mixed
     */
    public function getPageList($projectId) 
    {

        $projectId = intval($projectId);
        $cacheKey = "Project:PageList:$projectId";
        $cache = \Yii::$app->getCache()->get($cacheKey);
        if ($cache) {
            return $cache;
        }
        $list = [];
        //项目根目录页面
        $pages = Page::find()
            ->select(['id','project_id','title'])
            ->where('`status`='.Page::STATUS_NORMAL)
            ->andWhere('`project_id`=:project_id and `catalog_id`=:catalog_id', [
                ':project_id' => $projectId,
                ':catalog_id' => 0
            ])
            ->orderBy('`sort_number` asc,`id` asc')
            ->all();
        /** @var Page $page */
        foreach ($pages as $page) {
            $list[] = $page->toArray(['id','project_id','title']);
        }
        unset($pages);

        $cats = Catalog::find()
            ->select(['id'])
            ->where('`status`='.Catalog::STATUS_NORMAL)
            ->andWhere('`project_id`=:project_id and `parent_id`=:parent_id',[
                ':project_id' => $projectId,
                ':parent_id' => 0
            ])
            ->orderBy('`sort_number` asc,`id` asc')
            ->all();
        /** @var Catalog $cat */
        foreach ($cats as $cat) {
            $items = $this->getCatPageList($projectId, $cat->id);
            foreach ($items as $item) {
                $list[] = $item;
            }
        }
        unset($cats);

        \Yii::$app->getCache()->set($cacheKey, $list);
        return $list;
    }

    /**
     * 获取目录文档页面
     * @param int $projectId 项目ID
     * @param int $catId 目录ID
     * @return array
     */
    public function getCatPageList($projectId, $catId)
    {

        $projectId = intval($projectId);
        $catId = intval($catId);
        $list = [];
        $pages = Page::find()
            ->select(['id','project_id','title'])
            ->where('`status`='.Page::STATUS_NORMAL)
            ->andWhere('`project_id`=:project_id and `catalog_id`=:catalog_id', [
                ':project_id' => $projectId,
                ':catalog_id' => $catId
            ])
            ->orderBy('`sort_number` asc,`id` asc')
            ->all();
        /** @var Page $page */
        foreach ($pages as $page) {
            $list[] = $page->toArray(['id','project_id','title']);
        }
        unset($pages);

        $cats = Catalog::find()
            ->select(['id'])
            ->where('`status`='.Catalog::STATUS_NORMAL)
            ->andWhere('`parent_id`=:paid',[
                ':paid' => $catId
            ])
            ->orderBy('`sort_number` asc,`id` asc')
            ->all();
        /** @var Catalog $cat */
        foreach ($cats as $cat) {
            $items = $this->getCatPageList($projectId, $cat->id);
            foreach ($items as $item) {
                $list[] = $item;
            }
        }
        unset($cats);

        return $list;
    }

    /**
     * 获取文档上一页下一页信息
     * @param int $projectId
     * @param int $pageId
     * @return array
     */
    public function getPagePreNext($projectId, $pageId)
    {
        $pageList = $this->getPageList($projectId);
        $pre = [];
        $next = [];
        foreach ($pageList as $k=>$page) {
            if ($page['id'] == $pageId) {
                if (isset($pageList[$k-1])) {
                    $pre = $pageList[$k-1];
                }
                if (isset($pageList[$k+1])) {
                    $next = $pageList[$k+1];
                }
                break;
            }
        }
        
        return ['pre'=>$pre,'next'=>$next];
    }

    /**
     * 获取某个项目用于PDF导出的文档列表
     * @param int $projectId 项目ID
     * @return array
     */
    public function getPdfList($projectId)
    {
        $projectId = intval($projectId);
        $list = [];

        //项目根目录页面
        $pages = Page::find()
            ->where('`status`='.Page::STATUS_NORMAL)
            ->andWhere('`project_id`=:project_id and `catalog_id`=:catalog_id', [
                ':project_id' => $projectId,
                ':catalog_id' => 0
            ])
            ->orderBy('`sort_number` asc,`id` asc')
            ->all();
        /** @var Page $page */
        foreach ($pages as $page) {
            $list[] = [
                'type' => 'page',
                'data' => $page->toArray(),
                'level' => 1,
            ];
        }
        unset($pages);

        $cats = Catalog::find()
            ->where('`status`='.Catalog::STATUS_NORMAL)
            ->andWhere('`project_id`=:project_id and `parent_id`=:parent_id',[
                ':project_id' => $projectId,
                ':parent_id' => 0
            ])
            ->orderBy('`sort_number` asc,`id` asc')
            ->all();
        /** @var Catalog $cat */
        foreach ($cats as $cat) {
            $list[] = [
                'type' => 'catalog',
                'data' => $cat->toArray(),
                'level' => 1,
            ];
            $this->getCatPdfList($projectId, $cat->id, $list, 2);
        }
        unset($cats);

        return $list;
    }

    /**
     * 获取项目某个目录用于PDF导出的文档列表
     * @param int $projectId  项目ID
     * @param int $catId 目录ID
     * @param array $list 列表数组
     * @param int $level pdf书签层级(目录)
     * @return array
     */
    public function getCatPdfList($projectId, $catId, &$list, $level=1)
    {
        $projectId = intval($projectId);
        $catId = intval($catId);
        $pages = Page::find()
            ->where('`status`='.Page::STATUS_NORMAL)
            ->andWhere('`project_id`=:project_id and `catalog_id`=:catalog_id', [
                ':project_id' => $projectId,
                ':catalog_id' => $catId
            ])
            ->orderBy('`sort_number` asc,`id` asc')
            ->all();
        /** @var Page $page */
        foreach ($pages as $page) {
            $list[] = [
                'type' => 'page',
                'data' => $page->toArray(),
                'level' => $level
            ];
        }
        unset($pages);

        $cats = Catalog::find()
            ->where('`status`='.Catalog::STATUS_NORMAL)
            ->andWhere('`parent_id`=:paid',[
                ':paid' => $catId
            ])
            ->orderBy('`sort_number` asc,`id` asc')
            ->all();
        /** @var Catalog $cat */
        foreach ($cats as $cat) {
            $list[] = [
                'type' => 'catalog',
                'data' => $cat->toArray(),
                'level' => $level
            ];
            $this->getCatPdfList($projectId, $cat->id, $list, ($level+1));
        }
        unset($cats);

        return $list;
    }


    public function getCatPath($projectId, $catId)
    {
        $path = [];
        $cats = Catalog::find()->where([
            'project_id' => $projectId,
            'status' => Catalog::STATUS_NORMAL
        ])->select(['id','name','parent_id'])->asArray()->all();
        $catParent = ArrayHelper::map($cats, 'id', 'parent_id');
        $catName = ArrayHelper::map($cats, 'id', 'name');
        while ($catId) {
            if(isset($catName[$catId])) {
                array_unshift($path, $catName[$catId]);
            }
            $catId = isset($catParent[$catId]) ? intval($catParent[$catId]) : 0;
        }
        return implode('-',$path);
    }

}