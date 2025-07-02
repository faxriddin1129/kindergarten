<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transactions}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%invoice}}`
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m230611_064320_create_transactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transactions}}', [
            'id' => $this->primaryKey(),
            'invoice_id' => $this->integer(),
            'amount' => $this->integer(),
            'type' => $this->integer(),
            'status' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'date' => $this->dateTime(),
            'comment' => $this->text(),
            'discount' => $this->float(),
        ]);

        // creates index for column `invoice_id`
        $this->createIndex(
            '{{%idx-transactions-invoice_id}}',
            '{{%transactions}}',
            'invoice_id'
        );

        // add foreign key for table `{{%invoice}}`
        $this->addForeignKey(
            '{{%fk-transactions-invoice_id}}',
            '{{%transactions}}',
            'invoice_id',
            '{{%invoice}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-transactions-created_by}}',
            '{{%transactions}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-transactions-created_by}}',
            '{{%transactions}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-transactions-updated_by}}',
            '{{%transactions}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-transactions-updated_by}}',
            '{{%transactions}}',
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
        // drops foreign key for table `{{%invoice}}`
        $this->dropForeignKey(
            '{{%fk-transactions-invoice_id}}',
            '{{%transactions}}'
        );

        // drops index for column `invoice_id`
        $this->dropIndex(
            '{{%idx-transactions-invoice_id}}',
            '{{%transactions}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-transactions-created_by}}',
            '{{%transactions}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-transactions-created_by}}',
            '{{%transactions}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-transactions-updated_by}}',
            '{{%transactions}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-transactions-updated_by}}',
            '{{%transactions}}'
        );

        $this->dropTable('{{%transactions}}');
    }
}
