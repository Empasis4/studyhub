<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>StudyHub Database Diagnostic</h1>";

$host = getenv('DB_HOST') ?: getenv('MYSQLHOST');
$db   = getenv('DB_DATABASE') ?: getenv('MYSQLDATABASE');
$user = getenv('DB_USERNAME') ?: getenv('MYSQLUSER');
$pass = getenv('DB_PASSWORD') ?: getenv('MYSQLPASSWORD');
$port = getenv('DB_PORT') ?: getenv('MYSQLPORT');

echo "<p><strong>Attempting to connect to:</strong> $host on port $port</p>";
echo "<p><strong>Database Name:</strong> $db</p>";
echo "<p><strong>Username:</strong> $user</p>";

try {
    $dsn = "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "<h2 style='color: green;'>✅ SUCCESS: Connection Established!</h2>";
    
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<h3>Tables found:</h3>";
    if (empty($tables)) {
        echo "<p style='color: orange;'>⚠️ No tables found. Database is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    }
} catch (\PDOException $e) {
    echo "<h2 style='color: red;'>❌ FAILURE: Could not connect to database</h2>";
    echo "<p><strong>Error Message:</strong> " . $e->getMessage() . "</p>";
    
    echo "<h3>Available Environment Variable Keys (Variable Hunter):</h3>";
    echo "<ul>";
    foreach ($_SERVER as $key => $value) {
        if (strpos($key, 'MYSQL') !== false || strpos($key, 'DATABASE') !== false || strpos($key, 'DB_') !== false) {
            echo "<li><strong>$key</strong> (Found)</li>";
        } else {
            // echo "<li>$key</li>"; // Hidden for brevity
        }
    }
    echo "</ul>";
    echo "<p><strong>Helpful Tip:</strong> If the list above is empty, you MUST link the MySQL service to the App service in the Railway Canvas.</p>";
}
