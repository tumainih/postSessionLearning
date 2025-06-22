<?php
require 'vendor/autoload.php';
require 'vendor/yiisoft/yii2/Yii.php';

$config = require 'config/console.php';
$app = new yii\console\Application($config);

echo "=== POST SESSION LEARNING SYSTEM CHECK ===\n\n";

// 1. Database Connection Test
echo "1. Testing Database Connection...\n";
try {
    $db = Yii::$app->db;
    $result = $db->createCommand('SELECT COUNT(*) as count FROM user')->queryOne();
    echo "   ✓ Database connected successfully\n";
    echo "   ✓ User table has {$result['count']} records\n";
} catch (Exception $e) {
    echo "   ✗ Database error: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Check All Tables
echo "\n2. Checking Database Tables...\n";
$tables = ['user', 'concept', 'course', 'module', 'feedback'];
foreach ($tables as $table) {
    try {
        $result = $db->createCommand("SELECT COUNT(*) as count FROM $table")->queryOne();
        echo "   ✓ Table '$table' exists with {$result['count']} records\n";
    } catch (Exception $e) {
        echo "   ✗ Table '$table' error: " . $e->getMessage() . "\n";
    }
}

// 3. Test User Model
echo "\n3. Testing User Model...\n";
try {
    $user = new app\models\User(['scenario' => 'register']);
    $user->username = 'test_user_' . time();
    $user->email = 'test@example.com';
    $user->password = 'testpass123';
    $user->role = 'student';
    $user->contact_number = '1234567890';
    
    if ($user->save()) {
        echo "   ✓ User model can create and save users\n";
        // Clean up test user
        $user->delete();
    } else {
        echo "   ✗ User model save failed: " . print_r($user->errors, true) . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ User model error: " . $e->getMessage() . "\n";
}

// 4. Test Concept Model
echo "\n4. Testing Concept Model...\n";
try {
    $concept = new app\models\Concept();
    $concept->title = 'Test Concept';
    $concept->lecture_id = 1; // Use a valid lecture_id or 1 for test
    
    if ($concept->save()) {
        echo "   ✓ Concept model can create and save concepts\n";
        // Clean up test concept
        $concept->delete();
    } else {
        echo "   ✗ Concept model save failed: " . print_r($concept->errors, true) . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ Concept model error: " . $e->getMessage() . "\n";
}

// 5. Check Admin User
echo "\n5. Checking Admin User...\n";
try {
    $admin = app\models\User::findOne(['username' => 'admin']);
    if ($admin) {
        echo "   ✓ Admin user exists\n";
        echo "   ✓ Admin role: {$admin->role}\n";
        echo "   ✓ Admin status: {$admin->status}\n";
    } else {
        echo "   ✗ Admin user not found\n";
    }
} catch (Exception $e) {
    echo "   ✗ Admin user check error: " . $e->getMessage() . "\n";
}

// 6. Test File Permissions
echo "\n6. Checking File Permissions...\n";
$directories = ['runtime', 'web/assets'];
foreach ($directories as $dir) {
    if (is_dir($dir) && is_writable($dir)) {
        echo "   ✓ Directory '$dir' is writable\n";
    } else {
        echo "   ✗ Directory '$dir' is not writable\n";
    }
}

// Create uploads directory if it doesn't exist
if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
    echo "   ✓ Created uploads directory\n";
} else {
    echo "   ✓ Uploads directory exists\n";
}

// 7. Check Configuration Files
echo "\n7. Checking Configuration Files...\n";
$configFiles = ['config/web.php', 'config/console.php', 'config/db.php'];
foreach ($configFiles as $file) {
    if (file_exists($file)) {
        echo "   ✓ Configuration file '$file' exists\n";
    } else {
        echo "   ✗ Configuration file '$file' missing\n";
    }
}

// 8. Test Web Application
echo "\n8. Testing Web Application...\n";
try {
    $webConfig = require 'config/web.php';
    $webApp = new yii\web\Application($webConfig);
    echo "   ✓ Web application can be initialized\n";
} catch (Exception $e) {
    echo "   ✗ Web application error: " . $e->getMessage() . "\n";
}

echo "\n=== SYSTEM CHECK COMPLETE ===\n";
echo "If all tests passed, your system is ready to use!\n";
echo "Access the application at: http://localhost:8081\n"; 