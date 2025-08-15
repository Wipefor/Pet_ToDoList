<?php
/** @var PDO $pdo */
require_once 'config.php';
redirectIfNotLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['task'])) {
    $task = trim($_POST['task']);
    $hours = isset($_POST['hours']) && $_POST['hours'] !== '' ? (int)$_POST['hours'] : null;
    $userId = $_SESSION['user_id'];

    if ($hours !== null) {
        $deadline = date('Y-m-d H:i:s', strtotime("+$hours hours"));
        $stmt = $pdo->prepare("INSERT INTO tasks (description, deadline_at, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$task, $deadline, $userId]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO tasks (description, user_id) VALUES (?, ?)");
        $stmt->execute([$task, $userId]);
    }
}
header('Location: index.php');
exit();
?>
