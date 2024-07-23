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
###### 接口描述

- 用户登录

###### 请求方式及URI

- `post` `api/user/login`

###### 参数说明

|参数名|必要|类型|说明|
|:--|:--|:--|:--|
| username | 是 | string | 用户名 |
| password | 否 | string | 密码 |
| smsCode | 否 | string | 验证码 |

###### 返回值说明

|参数名|类型|说明|
|:--|:--|:--|
| groupId | int | 用户组 `1超级管理员 2普通用户` |
| nickname | string | 昵称 `回传` |
| msgList | array[map] | 消息列表 |
| &emsp;&emsp; msgId | int | 消息ID |
| &emsp;&emsp; msgText | string | 消息内容 |

###### 变更记录及注意事项

- 密码与验证码 二选一
- yyyy-MM-dd 基于某项目某需求初始化

'; break;
            case 'table':
                $content = '
###### 用户主表 `user`

-  储存用户登录及状态信息

|字段|类型|默认|注释|
|:--|:--|:--|:--|
| id | int | - | 用户ID|
| mobile | bigint | 0 | 手机号 |
| password |char(32) |  | 密码 |
| status | tinyint | 1 | 状态 |
| created_at | int(11) | 0 | 创建时间 |
| updated_at | int(11) | 0 | 更新时间 |

- `status` 状态取值说明

```
1 正常
5 待激活
9 冻结
```

###### 备注说明

- 唯一索引：手机号
- 手机号为 11 位，注册时需要通过正则匹配

'; break;
        }
        return $content;
    }

}