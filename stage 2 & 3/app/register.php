<?php

require __DIR__ . '/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $full_name = trim($_POST['full_name'] ?? '');


    if (empty($username) || empty($password) || empty($full_name)) {
        $error = "All fields are required.";
    }else {
        $bio = "Player who is attempting the CTF ";
        $role = "guest";
        
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $pdo = get_db();
            $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, role, bio) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $hashed_password, $full_name, $role, $bio]);
            $success = "User registered successfully!";
        } catch (PDOException $e) {
            $error = "Registeration failed. That username may already be taken.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Obsidian — Request Access</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<h1>OBSIDIAN</h1>
<h2>Submit your details to request a contact handle.</h2>

<?php if ($error): ?><p><?= htmlspecialchars($error) ?></p><?php endif; ?>
<?php if ($success): ?><p style="color:#3fb950;"><?= htmlspecialchars($success) ?> <a href="/login.php">Proceed to access portal</a></p><?php endif; ?>

<form method="POST" action="">
    <label for="username">Handle:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Passphrase:</label>
    <input type="password" id="password" name="password" required>

    <label for="full_name">Real name (internal use only):</label>
    <input type="text" id="full_name" name="full_name" required>

    <input type="submit" value="Submit">
</form>
</body>
</html>