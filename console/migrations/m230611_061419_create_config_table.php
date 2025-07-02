<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%config}}`.
 */
class m230611_061419_create_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%config}}', [
            'id' => $this->primaryKey(),
            'center_id' => $this->integer(),
            'title' => $this->string(),
            'bot_token' => $this->string(),
            'chat_id' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%config}}');
    }
}
