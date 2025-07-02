<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pupil}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m230611_062929_create_pupil_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pupil}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(),
            'last_name' => $this->string(),
            'user_id' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'social' => $this->integer(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-pupil-user_id}}',
            '{{%pupil}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-pupil-user_id}}',
            '{{%pupil}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-pupil-created_by}}',
            '{{%pupil}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-pupil-created_by}}',
            '{{%pupil}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-pupil-updated_by}}',
            '{{%pupil}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-pupil-updated_by}}',
            '{{%pupil}}',
            'updated_by',
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
            '{{%fk-pupil-user_id}}',
            '{{%pupil}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-pupil-user_id}}',
            '{{%pupil}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-pupil-created_by}}',
            '{{%pupil}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-pupil-created_by}}',
            '{{%pupil}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-pupil-updated_by}}',
            '{{%pupil}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-pupil-updated_by}}',
            '{{%pupil}}'
        );

        $this->dropTable('{{%pupil}}');
    }
}
