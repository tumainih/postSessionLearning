<?php

use yii\db\Migration;

/**
 * Class m250622_130000_create_base_tables
 */
class m250622_130000_create_base_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Create `user` table
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'role' => $this->string()->notNull()->defaultValue('student'),
            'contact_number' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // 2. Create `course` table
        $this->createTable('{{%course}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // 3. Create `module` table
        $this->createTable('{{%module}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'year' => $this->integer()->notNull(),
            'course_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // 4. Create `lecture` table
        $this->createTable('{{%lecture}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'module_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // 5. Create `concept` table
        $this->createTable('{{%concept}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'lecture_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // 6. Create `feedback` table
        $this->createTable('{{%feedback}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'concept_id' => $this->integer()->notNull(),
            'status' => $this->string()->notNull(), // 'understood' or 'not_understood'
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Add foreign keys
        $this->addForeignKey('fk-module-course_id', '{{%module}}', 'course_id', '{{%course}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-lecture-module_id', '{{%lecture}}', 'module_id', '{{%module}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-concept-lecture_id', '{{%concept}}', 'lecture_id', '{{%lecture}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-feedback-user_id', '{{%feedback}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-feedback-concept_id', '{{%feedback}}', 'concept_id', '{{%concept}}', 'id', 'CASCADE');

        // Create indexes
        $this->createIndex('idx-feedback-user-concept', '{{%feedback}}', ['user_id', 'concept_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign keys first
        $this->dropForeignKey('fk-feedback-concept_id', '{{%feedback}}');
        $this->dropForeignKey('fk-feedback-user_id', '{{%feedback}}');
        $this->dropForeignKey('fk-concept-lecture_id', '{{%concept}}');
        $this->dropForeignKey('fk-lecture-module_id', '{{%lecture}}');
        $this->dropForeignKey('fk-module-course_id', '{{%module}}');

        // Drop tables in reverse order
        $this->dropTable('{{%feedback}}');
        $this->dropTable('{{%concept}}');
        $this->dropTable('{{%lecture}}');
        $this->dropTable('{{%module}}');
        $this->dropTable('{{%course}}');
        $this->dropTable('{{%user}}');
    }
} 