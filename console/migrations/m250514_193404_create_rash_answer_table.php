<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rash_answer}}`.
 */
class m250514_193404_create_rash_answer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rash_answer}}', [
            'id' => $this->primaryKey(),
            'rash_control_id' => $this->integer(),
            'full_name' => $this->string(),
            'answers' => $this->text(),
            'created_at' => $this->timestamp()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rash_answer}}');
    }
}
