<?php

use yii\db\Migration;

class m1711071852_jext_jrbac extends Migration
{
    public $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    protected function getAuthManager()
    {
        $authManager = \Yii::$app->getAuthManager();
        if (!$authManager instanceof \jext\jrbac\src\JDbManager) {
            throw new \yii\base\InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }
        return $authManager;
    }

    public function up()
    {
        /** @var \jext\jrbac\src\JDbManager $am */
        $am = $this->getAuthManager();

        $this->execute('SET foreign_key_checks = 0');

        if (!$this->tableExists($am->ruleTable)) {
            $this->createTable($am->ruleTable, [
                'name' => $this->string(64)->notNull(),
                'data' => $this->binary(),
                'created_at' => $this->integer()->unsigned()->defaultValue(0)->comment('创建时间'),
                'updated_at' => $this->integer()->unsigned()->defaultValue(0)->comment('更新时间'),
                'PRIMARY KEY ([[name]])',
            ], $this->tableOptions);
        }

        if (!$this->tableExists($am->itemTable)) {
            $this->createTable($am->itemTable, [
                'name' => $this->string(64)->notNull(),
                'type' => $this->smallInteger()->notNull(),
                'description' => $this->string(64),
                'rule_name' => $this->string(64),
                'data' => $this->binary(),
                'created_at' => $this->integer()->unsigned()->defaultValue(0)->comment('创建时间'),
                'updated_at' => $this->integer()->unsigned()->defaultValue(0)->comment('更新时间'),
                'PRIMARY KEY ([[name]])',
                'FOREIGN KEY ([[rule_name]]) REFERENCES ' . $am->ruleTable . ' ([[name]])'.
                $this->buildFkClause('ON DELETE SET NULL', 'ON UPDATE CASCADE'),
            ], $this->tableOptions);
            $this->createIndex('idx-auth_item-type', $am->itemTable, 'type');
        }

        if (!$this->tableExists($am->itemChildTable)) {
            $this->createTable($am->itemChildTable, [
                'parent' => $this->string(64)->notNull(),
                'child' => $this->string(64)->notNull(),
                'PRIMARY KEY ([[parent]], [[child]])',
                'FOREIGN KEY ([[parent]]) REFERENCES ' . $am->itemTable . ' ([[name]])'.
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
                'FOREIGN KEY ([[child]]) REFERENCES ' . $am->itemTable . ' ([[name]])'.
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            ], $this->tableOptions);
        }

        if (!$this->tableExists($am->assignmentTable)) {
            $this->createTable($am->assignmentTable, [
                'item_name' => $this->string(64)->notNull(),
                'user_id' => $this->string(64)->notNull(),
                'created_at' => $this->integer()->unsigned()->defaultValue(0)->comment('创建时间'),
                'PRIMARY KEY ([[item_name]], [[user_id]])',
                'FOREIGN KEY ([[item_name]]) REFERENCES ' . $am->itemTable . ' ([[name]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            ], $this->tableOptions);
        }

        if (!$this->tableExists($am->menuTable)) {
            $this->createTable($am->menuTable, [
                'id' => $this->primaryKey()->unsigned(),
                'pid' => $this->integer()->unsigned()->defaultValue(0)->comment('父ID'),
                'label' => $this->string(32)->defaultValue('')->comment('标签'),
                'icon' => $this->string(32)->defaultValue('')->comment('图标'),
                'url' => $this->string(255)->defaultValue('')->comment('链接'),
                'content' => $this->string(255)->defaultValue('')->comment('备注'),
                'sort_order' => $this->integer()->unsigned()->defaultValue(0)->comment('排序'),
                'status' => $this->boolean()->unsigned()->defaultValue(0)->comment('状态'),
            ], $this->tableOptions);
        }

        $this->execute('SET foreign_key_checks = 1');
    }

    public function down()
    {
        /** @var jext\jrbac\src\JDbManager $am */
        $am = $this->getAuthManager();
        $this->execute('SET foreign_key_checks = 0');
        $this->dropTable($am->assignmentTable);
        $this->dropTable($am->itemChildTable);
        $this->dropTable($am->itemTable);
        $this->dropTable($am->ruleTable);
        $this->dropTable($am->menuTable);
        $this->execute('SET foreign_key_checks = 1');
    }

    protected function buildFkClause($delete = '', $update = '')
    {
        return implode(' ', ['', $delete, $update]);
    }


    protected function tableExists($tableName)
    {
        return \Yii::$app->getDb()->getSchema()->getTableSchema($tableName, true) !== null;
    }
}