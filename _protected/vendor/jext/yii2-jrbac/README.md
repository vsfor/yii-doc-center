# yii2-jrbac
### backend actions for yii2 rbac manage
### improving... you can diy it for your own project

- set the authManager component in your project config file

        //...
        'components' => [
            //...
            'authManager' => [
                'class' => 'jext\jrbac\vendor\JDbManager',
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
$jrbacMenu = \jext\jrbac\vendor\JMenu::getInstance()->getMenu();

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
   
```   


#
****
### any other problems ? mail to me: jeen@vsfor.com :)
