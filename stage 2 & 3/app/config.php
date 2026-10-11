<?php
$dbPath = __DIR__ . '/db/Dinithdatabase.sqlite';

function get_db(): PDO {
    global $dbPath;
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

session_start();