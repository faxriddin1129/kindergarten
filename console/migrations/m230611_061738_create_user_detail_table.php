<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_detail}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m230611_061738_create_user_detail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_detail}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'passport' => $this->string(),
            'birthday' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'card_id' => $this->string(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_detail-user_id}}',
            '{{%user_detail}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_detail-user_id}}',
            '{{%user_detail}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_detail-user_id}}',
            '{{%user_detail}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_detail-user_id}}',
            '{{%user_detail}}'
        );

        $this->dropTable('{{%user_detail}}');
    }
}
