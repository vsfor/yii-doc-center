<?php
namespace jext\jrbac\src;

class JMenu
{
    private static $instance;
    public static function getInstance()
    {
        if(empty(self::$instance)) self::$instance = new self();
        return self::$instance;
    }
    //jrbac模块中的样式前缀
    public $iconPrefix = 'glyphicon glyphicon-'; //bootstrap.css 可修改为font-awesome字体,$icons列表中为公共样式

    public $icons = [
                'record','cog','share','user','plus','dashboard','home','trash','wrench','lock',
                'star','heart','tags','bookmark','book','paperclip','pushpin','filter','edit','refresh',
                'list','tint','play','leaf','fire','bell','wrench','flash','tasks','globe'
            ];
    public $defaultIcon = 'record';
    public function getMenuIconOptionItems()
    {
        $iconArray = [];
        foreach ($this->icons as $icon) {
            $iconArray[$icon] = '<i class="'.$this->iconPrefix.$icon.'">&nbsp;&nbsp;</i>';
        }
        return $iconArray;
    }
    
    
    public function getMenu()
    {
        return $this->getItems(0);
    }

    public function getItems($pid = 0)
    {
        $query = JrbacMenu::find()->where('`status`=1 and `pid`=:pid',[
            ':pid'=>$pid
        ])->orderBy('`sort_order` asc');
        $items = $query->asArray()->all();
        $list = [];
        foreach($items as $k=>$item) {
            if (strpos($item['url'], '://')) { //外链判断
                $list[$k]['label'] = $item['label'];
                $list[$k]['url'] = $item['url'];
                $list[$k]['icon'] = $this->iconPrefix.(isset($item['icon']) && $item['icon'] ? $item['icon'] : $this->defaultIcon);
                $sub = $this->getItems($item['id']);
                if($sub) {
                    $list[$k]['items'] = $sub;
                } else if(in_array($item['url'],['/','#','javascript:;'])) {
                    unset($list[$k]);
                }
            } else if($this->checkAllow($item['url'])) {
                $list[$k]['label'] = $item['label'];
                $list[$k]['url'] = in_array($item['url'],['/','#','javascript:;']) ? $item['url'] : [$item['url']];
                $list[$k]['icon'] = $this->iconPrefix.(isset($item['icon']) && $item['icon'] ? $item['icon'] : $this->defaultIcon);
                $sub = $this->getItems($item['id']);
                if($sub) {
                    $list[$k]['items'] = $sub;
                } else if(in_array($item['url'],['/','#','javascript:;'])) {
                    unset($list[$k]);
                }
            }
        }
        return $list;
    }

    public function getOptionList($pid = 0,$level = 0,$depth=0)
    {
        $query = JrbacMenu::find()->where('`status`=1 and `pid`=:pid',[
            ':pid'=>$pid
        ])->orderBy('`sort_order` asc');
        $items = $query->asArray()->all();
        $list = [];
        $subPrefix = '';
        for($i = 0; $i<$level; $i++) {
            $subPrefix .= '--';
        }
        foreach($items as $k=>$item) {
            $list[] = [
                'id' => $item['id'],
                'label' => $subPrefix.$item['label']
            ];
            if (!$depth || ($level+1)<$depth) {
                $sub = $this->getOptionList($item['id'],$level+1,$depth);
                foreach($sub as $subItem) {
                    $list[] = $subItem;
                }
            }
        }
        return $list;
    }

    public function getPidFilter($pid = 0,$level = 0)
    {
        $pMenuItems = $this->getOptionList($pid,$level,1);
        $pMenuList = [];
        foreach($pMenuItems as $item) {
            $pMenuList[$item['id']] = $item['label'];
        }
        return $pMenuList;
    }

    public function checkAllow($url)
    {
        return \Yii::$app->getAuthManager()->allow($url, ['check_by_menu' => 'jrbac']);
    }

}
