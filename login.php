<?php
// login.php

$host = 'localhost';
$db   = 'kidsread1';  // Your DB name
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!$username || !$password) {
        die('Please enter both username and password.');
    }

    // Get the user
    $stmt = $pdo->prepare('SELECT * FROM users1 WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // ✅ Success
        echo '<h2 style="color: green; font-family: Comic Sans MS;">Login successful! 🎉</h2>';
        echo '<p>Welcome back, <strong>' . htmlspecialchars($username) . '</strong>!</p>';
        echo '<a href="myprojectkr.html">Go to KidsRead Home</a>';
    } else {
        // ❌ Failed
        echo '<h2 style="color: red; font-family: Comic Sans MS;">Invalid username or password.</h2>';
        echo '<a href="login.html">Try again</a>';
    }
} else {
    header('Location: login.html');
    exit;
}
?>
