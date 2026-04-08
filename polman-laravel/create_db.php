<?php
try {
    $pdo = new PDO('pgsql:host=127.0.0.1;port=5432', 'postgres', 'polman');
    $pdo->exec('CREATE DATABASE pelaporan_laravel');
    echo "Database pelaporan_laravel created successfully!\n";
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}
