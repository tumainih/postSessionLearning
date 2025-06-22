<?php

use yii\db\Migration;

/**
 * Class m250622_140000_add_auth_fields_to_user
 */
class m250622_140000_add_auth_fields_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Add auth_key column for Yii2 authentication
        $this->addColumn('{{%user}}', 'auth_key', $this->string(32)->notNull()->defaultValue(''));
        
        // Add access_token column (optional, for future use)
        $this->addColumn('{{%user}}', 'access_token', $this->string(255)->null());
        
        // Add password_reset_token column (optional, for password reset functionality)
        $this->addColumn('{{%user}}', 'password_reset_token', $this->string(255)->null());
        
        // Add status column (optional, for account status)
        $this->addColumn('{{%user}}', 'status', $this->smallInteger()->notNull()->defaultValue(10));
        
        // Create indexes
        $this->createIndex('idx-user-auth_key', '{{%user}}', 'auth_key');
        $this->createIndex('idx-user-access_token', '{{%user}}', 'access_token');
        $this->createIndex('idx-user-password_reset_token', '{{%user}}', 'password_reset_token');
        
        // Generate auth_key for existing users
        $users = $this->db->createCommand('SELECT id FROM {{%user}}')->queryAll();
        foreach ($users as $user) {
            $authKey = Yii::$app->security->generateRandomString();
            $this->update('{{%user}}', ['auth_key' => $authKey], ['id' => $user['id']]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop indexes
        $this->dropIndex('idx-user-password_reset_token', '{{%user}}');
        $this->dropIndex('idx-user-access_token', '{{%user}}');
        $this->dropIndex('idx-user-auth_key', '{{%user}}');
        
        // Drop columns
        $this->dropColumn('{{%user}}', 'status');
        $this->dropColumn('{{%user}}', 'password_reset_token');
        $this->dropColumn('{{%user}}', 'access_token');
        $this->dropColumn('{{%user}}', 'auth_key');
    }
} 