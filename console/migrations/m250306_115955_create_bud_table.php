<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bud}}`.
 */
class m250306_115955_create_bud_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bud}}', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->string(50),
            'messages' => $this->text(),
            'balance' => $this->float(),
            'admin' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'status' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bud}}');
    }
}
