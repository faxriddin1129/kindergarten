<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%invoice}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%pupil}}`
 */
class m230611_063917_create_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoice}}', [
            'id' => $this->primaryKey(),
            'pupil_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'amount' => $this->float(),
            'payment_amount' => $this->float(),
            'status' => $this->smallInteger(),
            'period' => $this->string(),
            'group_id' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `pupil_id`
        $this->createIndex(
            '{{%idx-invoice-pupil_id}}',
            '{{%invoice}}',
            'pupil_id'
        );

        // add foreign key for table `{{%pupil}}`
        $this->addForeignKey(
            '{{%fk-invoice-pupil_id}}',
            '{{%invoice}}',
            'pupil_id',
            '{{%pupil}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%pupil}}`
        $this->dropForeignKey(
            '{{%fk-invoice-pupil_id}}',
            '{{%invoice}}'
        );

        // drops index for column `pupil_id`
        $this->dropIndex(
            '{{%idx-invoice-pupil_id}}',
            '{{%invoice}}'
        );

        $this->dropTable('{{%invoice}}');
    }
}
