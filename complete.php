<?php
require_once 'config.php';
redirectIfNotLoggedIn();

$debug_mode =0; // 1 for debug

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // get current state
    $stmt = $pdo->prepare("SELECT is_completed, deadline_at FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($task) {
        $new_state = !$task['is_completed'];

        $completed_at = $new_state ? (new DateTime())->format('Y-m-d H:i:s') : null;

        // flip the state
        $stmt = $pdo->prepare("UPDATE tasks 
                              SET is_completed = ?, 
                                  completed_at = ? 
                              WHERE id = ?");
        $stmt->execute([$new_state, $completed_at, $id]);

        // debug part
        if ($debug_mode) {
            echo "<pre>";
            echo "ID: $id\n";
            echo "New state: " . ($new_state ? 'Completed' : 'Not completed') . "\n";
            echo "Completed at: " . ($completed_at ?? 'NULL') . "\n";

            echo "</pre>";
            exit(); // exit to debug review
        }
    } else {
        if ($debug_mode) {
            echo "Task with ID $id not found!";
            exit();
        }
    }
}

// redirect if not debug
if (!$debug_mode) {
    header('Location: index.php');
    exit();
}
