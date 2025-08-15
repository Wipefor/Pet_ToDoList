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
    <h2>Log-Into-TheSystem</h2>
    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="UserName" required>
        <input type="password" name="password" placeholder="PassWord" required>
        <button type="submit">LogIn</button>
    </form>
    <p>No account? <a href="register.php">Register account</a></p>
</div>
</body>
    </html>
