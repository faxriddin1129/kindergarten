<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expenses_category}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m230611_064501_create_expenses_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expenses_category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'status' => $this->smallInteger(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-expenses_category-created_by}}',
            '{{%expenses_category}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-expenses_category-created_by}}',
            '{{%expenses_category}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-expenses_category-updated_by}}',
            '{{%expenses_category}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-expenses_category-updated_by}}',
            '{{%expenses_category}}',
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
            '{{%fk-expenses_category-created_by}}',
            '{{%expenses_category}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-expenses_category-created_by}}',
            '{{%expenses_category}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-expenses_category-updated_by}}',
            '{{%expenses_category}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-expenses_category-updated_by}}',
            '{{%expenses_category}}'
        );

        $this->dropTable('{{%expenses_category}}');
    }
}
