<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $password]);

        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        $error = "User already exists";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <script src="toggleTheme.js"></script>
    <button id="theme-toggle" onclick="toggleTheme()" class="theme-button" >
        🌙
    </button>
    <h2>Registration</h2>
    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" class="add-form">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
    <p class="greetings" >Already have an account? <a class="hyperlink" href="login.php">LogIn</a></p>
</div>
</body>
</html>