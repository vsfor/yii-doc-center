<?php

/**
 * Initializes JRBAC table AdminMenu
 */
class m160720_132106_rbac_init extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%jrbac_menu}}', [
            'id' => $this->primaryKey(),
            'pid' => $this->integer()->defaultValue(0)->comment('父级ID'),
            'label' => $this->string(32)->defaultValue('')->comment('标题'),
            'icon' => $this->string(32)->defaultValue('')->comment('图标'),
            'url' => $this->string(255)->defaultValue('')->comment('链接'),
            'sortorder' => $this->integer()->defaultValue(0)->comment('排序'),
            'content' => $this->string(255)->defaultValue('')->comment('备注'),
            'status' => $this->smallInteger(1)->defaultValue(1)->comment('状态'),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%jrbac_menu}}');
    }
}