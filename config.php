<?php
/** @var PDO $pdo */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Europe/Moscow');
//config set
define('DB_HOST', 'localhost');
define('DB_NAME', 'todo_list');
define('DB_USER', 'root');
define('DB_PASS', '');

//db autontification
try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

$pdo->exec("SET time_zone = '+03:00'");


function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}
//former logout fragment
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit();
}

?>