<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expenses}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m230611_064534_create_expenses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expenses}}', [
            'id' => $this->primaryKey(),
            'comment' => $this->string(),
            'amount' => $this->float(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'period' => $this->integer(),
            'category_id' => $this->integer(),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-expenses-created_by}}',
            '{{%expenses}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-expenses-created_by}}',
            '{{%expenses}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-expenses-updated_by}}',
            '{{%expenses}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-expenses-updated_by}}',
            '{{%expenses}}',
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
            '{{%fk-expenses-created_by}}',
            '{{%expenses}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-expenses-created_by}}',
            '{{%expenses}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-expenses-updated_by}}',
            '{{%expenses}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-expenses-updated_by}}',
            '{{%expenses}}'
        );

        $this->dropTable('{{%expenses}}');
    }
}
