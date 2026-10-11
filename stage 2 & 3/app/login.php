<?php

require __DIR__ . '/config.php';

$error = '';

//if its alreayd logged in then redirect to index.php skipping the unnecsary steps 
if (!empty($_SESSION['user_id'])) {
    header('Location: /index.php?user_id='.(int)$_SESSION['user_id']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']?? '');
    $password = $_POST['password']?? '';
    
    try {
        $pdo = get_db();
        $smrt = $pdo->prepare("SELECT * FROM users WHERE username = ? ");
        $smrt->execute([$username]);
        $storedDetail = $smrt->fetch(PDO::FETCH_ASSOC);

        if ($storedDetail && password_verify($password,$storedDetail['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $storedDetail['user_id'];
            header('Location: /index.php?user_id='.(int)$storedDetail['user_id']);
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } catch (PDOException $e) {
      $error = "something went wrong, we didnt ask you to break the system, please try again later.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Obsidian — Member Access</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<h1>OBSIDIAN</h1>
<h2>Member access portal — invite only.</h2>

<?php if ($error): ?><p><?= htmlspecialchars($error) ?></p><?php endif; ?>

<form method="POST" action="">
    <label for="username">Handle:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Passphrase:</label>
    <input type="password" id="password" name="password" required>

    <input type="submit" value="Enter">
</form>

<p><a href="/register.php">Request access</a></p>
</body>
</html>