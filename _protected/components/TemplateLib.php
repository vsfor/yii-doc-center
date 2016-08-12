<?php
namespace app\components;

use app\models\Template;
use Yii;
use yii\base\Component;

class TemplateLib extends Component
{
    public function getListByUserId($userId)
    {
        $rows = Template::find()->where('`author_id`=:user_id', [
            ':user_id' => intval($userId)
        ])->all();
        return $rows;
    }

    public function getSystemTemplateContent($id)
    {
        $content = '';
        switch ($id) {
            case 'api':
                $content = '


**简要描述：** 

- 用户注册接口

**请求URL：** 
- ` http://xx.com/api/user/register `
  
**请求方式：**
- POST 

**参数：** 

|参数名|必选|类型|说明|
|:----    |:---|:----- |-----   |
|username |是  |string |用户名   |
|password |是  |string | 密码    |
|nickname     |否  |string | 昵称    |

 **返回示例**

``` 
  {
    "resultCode": 1000,
    "resultData": {
      "user_id": "1",
      "username": "12154545",
      "nickname": "吴系挂",
      "group_id": 2 ,
      "reg_time": "1436864169",
      "last_login_time": "0",
    },
    "dataZipped": 0,
    //...
  }
```

 **返回参数说明** 

|参数名|类型|说明|
|:-----  |:-----|-----                           |
|groupid |int   |用户组id，1：超级管理员；2：普通用户  |

 **备注** 

- 更多返回错误代码请看首页的错误代码描述


                '; break;
            case 'table':
                $content = '


-  用户表，储存用户信息

|字段|类型|空|默认|注释|
|:----    |:-------    |:--- |-- -|------      |
|user_id	  |int(10)     |否	|	 |	           |
|username |varchar(20) |否	|    |	 用户名	|
|password |varchar(50) |否   |    |	 密码		 |
|nickname     |varchar(15) |是   |    |    昵称     |
|reg_time |int(11)     |否   | 0  |   注册时间  |

- 备注：无


                '; break;
        }
        return $content;
    }

}