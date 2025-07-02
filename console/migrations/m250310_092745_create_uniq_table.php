<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%uniq}}`.
 */
class m250310_092745_create_uniq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%uniq}}', [
            'id' => $this->primaryKey(),
            'phone' => $this->integer()->notNull()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%uniq}}');
    }
}
