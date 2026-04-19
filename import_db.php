<?php
$host = 'gateway01.us-east-1.prod.aws.tidbcloud.com';
$port = '4000';
$user = '3tQCtvUfgTwmE45.root';
$password = 'hSbcU1BY5LGIiGfx';
$db = 'test';

try {
    // Connect to TiDB. SSL is usually required for TiDB Serverless.
    $dsn = "mysql:host=$host;port=$port;dbname=$db";
    $options = [
        PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/isrgrootx1.pem',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    $pdo = new PDO($dsn, $user, $password, $options);
    echo "Connected successfully to TiDB.\n";

    $sql = file_get_contents(__DIR__ . '/import.sql');
    if (!$sql) {
        die("Error reading import.sql\n");
    }

    echo "Importing SQL data...\n";
    $pdo->exec($sql);
    echo "SQL import completed successfully.\n";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
