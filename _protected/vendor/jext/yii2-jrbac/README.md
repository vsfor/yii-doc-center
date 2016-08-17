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

- make sure init file write permission allowed

        chmod 0777 /path/to/your/project/vendor/jext/yii2-jrbac/controllers/init.lock   


#
****
### any other problems ? mail to me: jeen@vsfor.com :)
