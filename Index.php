<?php
require_once 'config.php';

redirectIfNotLoggedIn();

// Получаем задачи только текущего пользователя
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at ASC");
$stmt->execute([$_SESSION['user_id']]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мой To-Do List</title>
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
</head>
<body>
<div class="container">
    <div style="float: right;">
        HeLlO, <?= htmlspecialchars($_SESSION['username']) ?> !
        <a href="logout.php">LOgOut</a>

    </div>
    <h2>My To-DO-List</h2>
        <h3>Current date-time is_ <span id="live-clock"><?= date('d-m-y H:i:s') ?></span></h3>
            <script>
                function updateClock() {
                    const now = new Date();
                    const formattedTime =
                        `${String(now.getDate()).padStart(2, '0')}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getFullYear()).slice(-0)} ` +
                        `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')}`;

                    document.getElementById('live-clock').textContent = formattedTime;
                }
                // Обновляем каждую секунду
                updateClock(); // сразу вызываем, чтобы не ждать 1 секунду
                setInterval(updateClock, 1000);
            </script>
        <form action="add.php" method="POST" class="add-form">
            <input type="text" name="task" placeholder="new task..." required>
            <input type="number" name="hours" placeholder="hours to complete (Deadline)" min="1" >
            <button type="submit">Add task</button>
        </form>
        
        <ul class="task-list">
            <li class="task-list-header">
                <span class="task-list-header__title">Tasks to-do</span>
                <div class="task-list-header__dates">
                    <span class="task-list-header__date-column_cr">Created_at</span>
                    <span class="task-list-header__date-column_dl">Deadline</span>
                    <span class="task-list-header__date-column_cp">Completed_at</span>
                    <span class="task-list-header__actions">Actions</span>
                </div>
            </li>
            <?php foreach ($tasks as $task): ?>
                <?php
                $is_completed = $task['is_completed'];
                $completed_at = $task['completed_at'];
                $deadline_at = $task['deadline_at'];

                $class = '';
                if ($is_completed) {
                    if ($deadline_at === null) {
                        // Case 1: No deadline + completed = GREEN
                        $class = 'completed-no-deadline';
                    } else {
                        $completed_time = strtotime($completed_at);
                        $deadline_time = strtotime($deadline_at);

                        if ($completed_time <= $deadline_time) {
                            // Case 2: Completed before deadline = GREEN
                            $class = 'completed-on-time';
                        } else {
                            // Case 3: Completed after deadline = RED
                            $class = 'completed-overdue';
                        }
                    }
                } else {
                    if ($deadline_at !== null && strtotime($deadline_at) < time()) {
                        // Case 4: Not completed + deadline passed = RED
                        $class = 'not-completed-overdue';
                    }
                }
                ?>
                <li class="<?= $class ?>">
                    <span><?= htmlspecialchars($task['description']) ?></span>
                    <div class="text_dates">
                        <span><?= date('d-m-y H:i', strtotime($task['created_at'])) ?></span>
                        <span class="date-cell"><?= $deadline_at ?
                                date('d-m-y H:i', strtotime($deadline_at)) :
                                '<span class="empty-date">No deadline</span>' ?></span>
                        <span class="date-cell"><?= $completed_at ?
                                date('d-m-y H:i', strtotime($completed_at)) :
                                '<span class="empty-date">Not completed</span>' ?></span>
                    </div>
                    <div class="actions">
                        <a href="complete.php?id=<?= $task['id'] ?>" class="complete">✓</a>
                        <a href="delete.php?id=<?= $task['id'] ?>" class="delete">✗</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>