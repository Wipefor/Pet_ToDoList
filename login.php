<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: index.php');
        echo $user['password'];
        exit();
    } else {
        $error = "incorrect username or password";
    }
}
?>

    <!DOCTYPE html>
    <html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>EnterinSystem</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <script src="toggleTheme.js"></script>
    <button id="theme-toggle" onclick="toggleTheme()" class="theme-button" >
        🌙
    </button>
    <h2>Log-Into-System</h2>
    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" class="add-form">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">LogIn</button>
    </form>
    <p  class="greetings" >No account? <a class="hyperlink" href="register.php">Register account</a></p>
</div>
</body>
    </html>
