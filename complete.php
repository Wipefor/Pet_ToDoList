<?php
require_once 'config.php';
redirectIfNotLoggedIn();
// Временно отключаем редирект для отладки
$debug_mode =0; // Переключите на false после отладки

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Получаем текущее состояние
    $stmt = $pdo->prepare("SELECT is_completed, deadline_at FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
    var_dump($task);
    if ($task) {
        $new_state = !$task['is_completed'];

        $completed_at = $new_state ? (new DateTime())->format('Y-m-d H:i:s') : null;

        // Обновляем задачу
        $stmt = $pdo->prepare("UPDATE tasks 
                              SET is_completed = ?, 
                                  completed_at = ? 
                              WHERE id = ?");
        $stmt->execute([$new_state, $completed_at, $id]);

        // Отладочная информация
        if ($debug_mode) {
            echo "<pre>";
            echo "debug mode $debug_mode\n";
            echo "ID: $id\n";
            echo "New state: " . ($new_state ? 'Completed' : 'Not completed') . "\n";
            echo "Completed at: " . ($completed_at ?? 'NULL') . "\n";

            echo "</pre>";
            exit(); // Останавливаем выполнение для просмотра отладки
        }
    } else {
        if ($debug_mode) {
            echo "Task with ID $id not found!";
            exit();
        }
    }
}

// Редирект только если не в режиме отладки
if (!$debug_mode) {
    header('Location: index.php');
    exit();
}
