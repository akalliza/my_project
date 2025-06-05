<?php
// DATABASE CONNECTION
$host = 'localhost';
$db   = 'kidsread1';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// FORM HANDLER
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $confirm = $_POST["password_confirm"] ?? '';

    if (!$username || !$email || !$password || !$confirm) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email.");
    }

    if ($password !== $confirm) {
        die("Passwords do not match.");
    }

    $stmt = $pdo->prepare("SELECT id FROM users1 WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        die("Username or email already exists.");
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users1 (username, password_hash, email) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hash, $email]);

    echo "<h2>✅ Success! You can now <a href='login.html'>log in</a>.</h2>";
} else {
    echo "<p>Please access this page from the form.</p>";
}
?>
