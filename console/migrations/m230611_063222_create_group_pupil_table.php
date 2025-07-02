<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%group_pupil}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%groups}}`
 * - `{{%pupil}}`
 */
class m230611_063222_create_group_pupil_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%group_pupil}}', [
            'id' => $this->primaryKey(),
            'group_id' => $this->integer(),
            'pupil_id' => $this->integer(),
            'date' => $this->string(),
            'status' => $this->integer(),
            'leave_date' => $this->date(),
            'comment' => $this->text(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `group_id`
        $this->createIndex(
            '{{%idx-group_pupil-group_id}}',
            '{{%group_pupil}}',
            'group_id'
        );

        // add foreign key for table `{{%groups}}`
        $this->addForeignKey(
            '{{%fk-group_pupil-group_id}}',
            '{{%group_pupil}}',
            'group_id',
            '{{%groups}}',
            'id',
            'CASCADE'
        );

        // creates index for column `pupil_id`
        $this->createIndex(
            '{{%idx-group_pupil-pupil_id}}',
            '{{%group_pupil}}',
            'pupil_id'
        );

        // add foreign key for table `{{%pupil}}`
        $this->addForeignKey(
            '{{%fk-group_pupil-pupil_id}}',
            '{{%group_pupil}}',
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
        // drops foreign key for table `{{%groups}}`
        $this->dropForeignKey(
            '{{%fk-group_pupil-group_id}}',
            '{{%group_pupil}}'
        );

        // drops index for column `group_id`
        $this->dropIndex(
            '{{%idx-group_pupil-group_id}}',
            '{{%group_pupil}}'
        );

        // drops foreign key for table `{{%pupil}}`
        $this->dropForeignKey(
            '{{%fk-group_pupil-pupil_id}}',
            '{{%group_pupil}}'
        );

        // drops index for column `pupil_id`
        $this->dropIndex(
            '{{%idx-group_pupil-pupil_id}}',
            '{{%group_pupil}}'
        );

        $this->dropTable('{{%group_pupil}}');
    }
}
