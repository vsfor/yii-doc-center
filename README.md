## help info
 
** 在线使用: http://ydc.jeen.wang   **


- 现有功能

```
  `项目管理,可添加多个项目,实现成员分级`
  `多级分类目录,使文档层次更清晰`
  `基于markdown的富文本编辑器,记录修改历史版本`
  `账号-微信多对多绑定,免去密码记忆烦恼`
  `基于文档内容的简单搜索功能`
  `简单的API在线测试功能,开发中...`
```
  
- 欢迎体验并参与 :)  


- 安装简要说明

```
注意： 暂不支持便捷安装，仅提供代码流程参考。
配置文件使用 yii2-app-basic 样例配置
数据结构可从 model 文件提取
如需深入研究或搭建独立服务，请使用站内联系，沟通配置细节，索取相关数据库文件

git clone

composer update

chmod -R 0777 assets/ uploads/ _protected/runtime/
```

> 使用工具概况列表

`wechat login used`

`yii2-basic-template used`

`adminlte-assets used`

`editor.md used`



yii2-basic-template
===================

Yii2-basic-template is based on yii2-app-basic created by yii2 core developers, but it also uses some of the features presented in their advanced template too.
There are several upgrades made to this template.

1. This template comes with almost all features that default yii2-app-advanced has.
2. It has additional features listed in the next section of this guide.
3. Application structure has been changed to be 'shared hosting friendly'.
 

Installation
-------------------
>I am assuming that you know how to: install and use Composer, and install additional packages/drivers that may be needed for you to run everything on your system. In case you are new to all of this, you can check my guides for installing default yii2 application templates, provided by yii2 developers, on Windows 8 and Ubuntu based Linux operating systems, posted on www.freetuts.org.

1. Create database that you are going to use for your application (you can use phpMyAdmin or any other tool that you like).

2. Now open up your console and ```cd``` to your web root directory, for example: ``` cd /var/www/html/ ```

3. Run the Composer ```create-project``` command:

   ``` composer create-project nenad/yii2-basic-template basic ```

.... and so on ...
