<?php
require_once 'config.php';
require_once 'task_sort.php';

redirectIfNotLoggedIn();

// Get all tasks of the currently logged-in user
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
    <script src="toggleTheme.js"></script>
    <button id="theme-toggle" onclick="toggleTheme()" class="theme-button" >🌙</button>

    <div class="greetings" style="float: right;">
        Hello, <?= htmlspecialchars($_SESSION['username']) ?> !
        <a href="?action=logout" class="hyperlink">Logout</a> <!action in config.php>
    </div>

    <h2>My To-Dgito-List</h2>
        <h3>Current date-time is  <span id="live-clock"><?= date('d-m-Y H:i:s') ?></span></h3>
        <script src=" updateClock.js " ></script>

        <form action="add.php" method="POST" class="add-form">
            <input type="text" name="task" placeholder="new task..." required>
            <input type="number" name="hours" placeholder="hours to complete (Deadline)" min="1" >
            <button type="submit">Add task</button>
        </form>
        
        <ul class="task-list">
            <li class="task-list-header">
                <span class="task-list-header__title">Tasks to-do</span>
                <div class="task-list-header__dates">
                    <span class="task-list-header__date-column_created">Created_at</span>
                    <span class="task-list-header__date-column_deadline">Deadline</span>
                    <span class="task-list-header__date-column_completed">Completed_at</span>
                    <span class="task-list-header__actions">Actions</span>
                </div>
            </li>
            <?php foreach ($tasks as $task): ?>
                <li class="<?= task_sort($task) ?>">
                    <span><?= htmlspecialchars($task['description']) ?></span>
                    <div class="text_dates">
                        <span><?= formatTaskDate($task['created_at']) ?></span>
                        <span class="date-cell"><?= formatTaskDate($task['deadline_at'], 'No deadline') ?></span>
                        <span class="date-cell"><?= formatTaskDate($task['completed_at'], 'Not completed') ?></span>
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