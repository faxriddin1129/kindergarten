<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rash_control}}`.
 */
class m250514_192958_create_rash_control_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rash_control}}', [
            'id' => $this->primaryKey(),
            'quiz_id' => $this->string()->unique(),
            'status' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rash_control}}');
    }
}
