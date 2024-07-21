# yii2-jrbac
### module for yii2 rbac manage
### improving... you can diy it for your own project

**Yii2 RBAC管理模块**

- 权限管理 - 添加、删除（批量）、自动初始化（根据路由地址）
- 角色管理 - 添加、删除（批量）、用户关联、权限关联、子角色关联
- 规则管理 - 添加、删除（批量）、权限关联
- 菜单管理 - 增删改查、无限分类、图标集成、权限过滤、与模版无缝集成（默认+adminLte）

- set the authManager component in your project config file

        //...
        'components' => [
            //...
            'authManager' => [
                'class' => 'jext\jrbac\src\JDbManager',
            ],
        ]
        //...
        

- load modules in your config file

        //...
        'modules' => [
            'jrbac' => [
                'class' => 'jext\jrbac\Module',
            ],
            //...
        ],
        //...

- to use jrbac menu component

```
//example code
$menuItems = [
    //... your own menu items set
];
$jrbacMenu = \jext\jrbac\src\JMenu::getInstance()->getMenu();

//in adminLte theme template
echo dmstr\widgets\Menu::widget(
[
    'options' => ['class' => 'sidebar-menu'],
    'items' => array_merge($menuItems, $jrbacMenu),
]

//other default views

    NavBar::begin([
        'brandLabel' => Yii::t('app', Yii::$app->name),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default',// navbar-fixed-top
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => array_merge($menuItems, $jrbacMenu),
    ]);

    NavBar::end();
    
//to use this component, make sure you have assigned the right permissions to the logined user
   
```   


#
****
### any other problems ? mail to me: jeen@vsfor.com :)
