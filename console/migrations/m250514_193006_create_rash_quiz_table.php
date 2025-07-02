<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rash}}`.
 */
class m250514_193006_create_rash_quiz_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rash_quiz}}', [
            'id' => $this->primaryKey(),
            'rash_control_id' => $this->integer(),
            'type' => $this->string(),
            'answer_1' => $this->string(),
            'answer_2' => $this->string(),
            'answer_3' => $this->string(),
            'answer_4' => $this->string(),
            'answer_5' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rash_quiz}}');
    }
}
