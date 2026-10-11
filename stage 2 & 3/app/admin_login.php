<?php
require __DIR__ . '/config.php';

if (empty($_SESSION['stage2_unlocked'])) {
    http_response_code(403);
    exit('Complete Stage 2 before accessing Stage 3.');
}

$error = '';
$results = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

     try {
        $pdo = get_db();
        $sql = "SELECT * FROM sysadmins WHERE username = '" . $username . "' AND password = '" . $password . "'";
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($results)) {
            $error = "Invalid admin credentials.";
        }
    } catch (PDOException $e) {
        $error = "Query error.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Obsidian — Infrastructure Console</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<h1>Infrastructure Console</h1>
<h2>Restricted — operations team only.</h2>
<p>
    Investigation note: Legacy authentication systems may trust
    the input they receive. Investigate how the login request
    is processed.
</p>

<?php if ($error): ?><p><?= htmlspecialchars($error) ?></p><?php endif; ?>

<form method="POST" action="">
    <label for="username">Operator ID:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Key:</label>
    <input type="password" id="password" name="password" required>

    <input type="submit" value="Authenticate">
</form>

<?php if ($results): ?>
    <table>
        <tr><th>Ref</th><th>Operator</th><th>Key</th><th>Log Entry</th></tr>
        <?php foreach ($results as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['password']) ?></td>
            <td><?= htmlspecialchars($row['notes']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
</body>
</html>