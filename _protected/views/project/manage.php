<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$this->title = Yii::t('app','Project').':'.$model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Project List'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$auth = Yii::$app->getAuthManager();

if ($auth->allow('/project/update', ['project_id'=>$model->id])) {
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('app', 'Update'),
        'url' => ['update', 'project_id' => $model->id],
    ];
}

if ($auth->allow('/project/delete', ['project_id'=>$model->id])) {
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('app', 'Delete'),
        'url' => ['delete', 'project_id' => $model->id],
        'data-method' => 'post',
        'data-confirm' => '删除项目,将会删除所有关联目录及文档,请谨慎操作,确认删除?',
    ];
}

$pageUpdateAllow = $auth->allow('/page/update', ['project_id'=>$model->id]);
$pageDeleteAllow = $auth->allow('/page/delete', ['project_id'=>$model->id]);

$catalogUpdateAllow = $auth->allow('/catalog/update', ['project_id'=>$model->id]);
$catalogDeleteAllow = $auth->allow('/catalog/delete', ['project_id'=>$model->id]);

$this->params['left-menu'] = $leftMenu;

function getDocListRenderHtml($docList, $level=0, $pU=true, $pD=true, $cU=true, $cD=true)
{
    $html = '';
    //可以在标题前  添加层级提示
    $prefix = '';//str_repeat('<i class="fa fa-folder-o"></i> ', $level);
    foreach ($docList as $doc)
    {
        if ($doc['type'] == 'page')
        {
            $html .= '<div class="box box-info">';
            $html .=    '<div class="box-header with-border">';
            $html .=        '<h5 class="box-title">'.$prefix.'<i class="fa fa-file-pdf-o"></i> '.$doc['data']['title'].'</h5>';
            $html .=        '<div class="box-tools pull-right">';
            $html .=  $pU ?     Html::a('<i class="fa fa-edit"></i>', [
                                        '/page/update',
                                        'page_id' => $doc['data']['id'],
                                        'project_id' => $doc['data']['project_id'],
                                    ], [
                                        'class' => 'btn btn-box-tool',
                                        'data' => [
                                            'toggle' => 'tooltip',
                                            'original-title' => Yii::t('app', 'Update'),
                                        ],
                                    ]) : '';
            $html .=  $pD ?     Html::a('<i class="fa fa-trash-o"></i>', [
                                        '/page/delete',
                                        'page_id' => $doc['data']['id'],
                                        'project_id' => $doc['data']['project_id']
                                    ], [
                                        'class' => 'btn btn-box-tool',
                                        'data' => [
                                            'toggle' => 'tooltip',
                                            'original-title' => Yii::t('app', 'Delete'),
                                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                            'method' => 'post',
                                        ],
                                    ]) : '';
            $html .=            Html::button('<i class="fa fa-minus"></i>', [
                                        'class' => 'btn btn-box-tool',
                                        'data-toggle' => 'tooltip', 'data-widget' => 'collapse',
                                        'data-original-title' => Yii::t('app', 'Collapse'),
                                    ]);
            $html .=            Html::button('<i class="fa fa-times-circle-o"></i>', [
                                        'class' => 'btn btn-box-tool',
                                        'data-toggle' => 'tooltip', 'data-widget'=>'remove',
                                        'data-original-title' => Yii::t('app', 'Remove'),
                                    ]);
            $html .=        '</div>';
            $html .=    '</div>';
                $html .= '<div class="box-body"><i class="fa fa-code-fork"></i> '.$doc['data']['description'].'</div>';
            $html .= '</div>';
        }
        else if ($doc['type'] == 'catalog')
        {
            $html .= '<div class="box box-success">';
            $html .=    '<div class="box-header with-border">';
            $html .=        '<h4 class="box-title">'.$prefix.'<i class="fa fa-folder-open-o"></i> '.$doc['data']['name'].'</h4>';
            $html .=        '<div class="box-tools pull-right">';
            $html .=    $cU ?   Html::a('<i class="fa fa-edit"></i>', [
                                        '/catalog/update',
                                        'catalog_id' => $doc['data']['id'],
                                        'project_id' => $doc['data']['project_id'],
                                    ], [
                                        'class' => 'btn btn-box-tool',
                                        'data' => [
                                            'toggle' => 'tooltip',
                                            'original-title' => Yii::t('app', 'Update'),
                                        ],
                                    ]) : '';
            $html .=   $cD ?    Html::a('<i class="fa fa-trash-o"></i>', [
                                        '/catalog/delete',
                                        'catalog_id' => $doc['data']['id'],
                                        'project_id' => $doc['data']['project_id']
                                    ], [
                                        'class' => 'btn btn-box-tool',
                                        'data' => [
                                            'toggle' => 'tooltip',
                                            'original-title' => Yii::t('app', 'Delete'),
                                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                            'method' => 'post',
                                        ],
                                    ]) : '';
            $html .=            Html::button('<i class="fa fa-minus"></i>', [
                                        'class' => 'btn btn-box-tool',
                                        'data-toggle' => 'tooltip', 'data-widget' => 'collapse',
                                        'data-original-title' => Yii::t('app', 'Collapse'),
                                    ]);
            $html .=            Html::button('<i class="fa fa-times-circle-o"></i>', [
                                        'class' => 'btn btn-box-tool',
                                        'data-toggle' => 'tooltip', 'data-widget'=>'remove',
                                        'data-original-title' => Yii::t('app', 'Remove'),
                                    ]);
            $html .=        '</div>';
            $html .=    '</div>';
            if (isset($doc['items']) && $doc['items']) {
                $html .= '<div class="box-body">';
                $html .= getDocListRenderHtml($doc['items'], $level+1, $pU, $pD, $cU, $cD);
                $html .= '</div>';
            }
            $html .= '</div>';
        } 
    }
    return $html;
}

?>
<div class="project-manage">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-list"></i><?php echo Yii::t('app','Project Document List');?></h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="<?php echo Yii::t('app','Collapse')?>"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <?php echo getDocListRenderHtml(
                $docList,
                0,
                $pageUpdateAllow,
                $pageDeleteAllow,
                $catalogUpdateAllow,
                $catalogDeleteAllow
            ); ?>
        </div>
    </div>
    <div class="callout callout-warning">
        <h4>提示!</h4>

        <p>管理项目文档需要对应的权限.</p>
    </div>
</div>

<?php
$this->registerCss('
.project-manage .box .box { margin-bottom:3px; }
.project-manage .box .box-info { border-left: 1px solid #00c0ef; border-bottom: 1px solid #00c0ef; }
.project-manage .box .box-success { border-left: 1px solid #00a65a; border-bottom: 1px solid #00a65a; }
.project-manage .box .box .box-body { padding:2px 0 0 4px; }
.project-manage .box .box .box-header { padding:4px 6px; }
.project-manage .box .box .box-header>.box-tools { top:0px; }
');

?>