<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%groups}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m230611_062354_create_groups_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%room}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createTable('{{%groups}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'price' => $this->float(),
            'description' => $this->text(),
            'teacher_id' => $this->integer(),
            'educator_id' => $this->integer(),
            'auto_discount' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'status' => $this->smallInteger(),
            'room_id' => $this->integer(),
            'start' => $this->timestamp(),
            'end' => $this->timestamp(),
            'week_1' => $this->string(),
            'week_2' => $this->string(),
            'week_3' => $this->string(),
            'week_4' => $this->string(),
            'week_5' => $this->string(),
            'week_6' => $this->string(),
        ]);

        // creates index for column `teacher_id`
        $this->createIndex(
            '{{%idx-groups-teacher_id}}',
            '{{%groups}}',
            'teacher_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-groups-teacher_id}}',
            '{{%groups}}',
            'teacher_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `educator_id`
        $this->createIndex(
            '{{%idx-groups-educator_id}}',
            '{{%groups}}',
            'educator_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-groups-educator_id}}',
            '{{%groups}}',
            'educator_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-groups-created_by}}',
            '{{%groups}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-groups-created_by}}',
            '{{%groups}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-groups-updated_by}}',
            '{{%groups}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-groups-updated_by}}',
            '{{%groups}}',
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
            '{{%fk-groups-teacher_id}}',
            '{{%groups}}'
        );

        // drops index for column `teacher_id`
        $this->dropIndex(
            '{{%idx-groups-teacher_id}}',
            '{{%groups}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-groups-educator_id}}',
            '{{%groups}}'
        );

        // drops index for column `educator_id`
        $this->dropIndex(
            '{{%idx-groups-educator_id}}',
            '{{%groups}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-groups-created_by}}',
            '{{%groups}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-groups-created_by}}',
            '{{%groups}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-groups-updated_by}}',
            '{{%groups}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-groups-updated_by}}',
            '{{%groups}}'
        );

        $this->dropTable('{{%groups}}');

        $this->dropTable('{{%room}}');
    }
}
