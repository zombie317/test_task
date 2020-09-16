<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%organization}}`.
 */
class m200910_170708_create_organization_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%heap}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);
        $this->createTable('{{%node}}', [
            'id' => $this->primaryKey(),
            'id_heap' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
        ]);
        $this->createTable('{{%edge}}', [
            'id' => $this->primaryKey(),
            'id_first_node' => $this->integer()->notNull(),
            'id_second_node' => $this->integer()->notNull(),
            'weight' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%heap}}');
        $this->dropTable('{{%node}}');
        $this->dropTable('{{%edge}}');
    }
}
