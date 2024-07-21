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

- ` api/user/register `

**请求方式：**

- POST

**参数：**

|参数名|必要|类型|说明|
|:--|:--|:--|:--|
| username | 是 | string | 用户名 |
| password | 是 | string | 密码 |
| nickname | 否 | string | 昵称 |


**返回参数说明**

|参数名|类型|说明|
|:--|:--|:--|
| groupid | int | 用户组id,1超级管理员,2普通用户 |

**备注**

- 更多返回错误代码请看首页的错误代码描述


'; break;
            case 'table':
                $content = '
####用户主表 ll_user

-  储存用户登录及状态信息

|字段|类型|空|默认|注释|
|:--|:--|:--|:--|:--|
| id | int(11) | 否 | - | 用户ID|
| mobile | bigint(11) | 否 | 0 | 手机号 |
| password |char(32)| 否 |  | 密码 |
| status | int(11) | | 1 | 状态 |
| created_at | int(11) | | 0 | 创建时间 |
| updated_at | int(11) | | 0 | 更新时间 |

- 备注：无
 · 唯一索引：手机号
 · 手机号为 11 位，注册时需要通过正则匹配
'; break;
        }
        return $content;
    }

}