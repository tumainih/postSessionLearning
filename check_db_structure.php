<?php
require 'vendor/autoload.php';
require 'vendor/yiisoft/yii2/Yii.php';

$config = require 'config/console.php';
$app = new yii\console\Application($config);

$db = Yii::$app->db;

echo "=== DATABASE STRUCTURE CHECK ===\n\n";

$tables = ['user', 'concept', 'course', 'module', 'feedback'];

foreach ($tables as $table) {
    echo "Table: $table\n";
    echo "----------------------------------------\n";
    try {
        $result = $db->createCommand("DESCRIBE $table")->queryAll();
        foreach ($result as $row) {
            echo "  {$row['Field']} - {$row['Type']}\n";
        }
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
} 