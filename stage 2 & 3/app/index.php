<?php
require __DIR__ . '/config.php';

// Must be logged in to view any profile
if (empty($_SESSION['user_id'])) {
    header('Location: /login.php');
    // ?user_id=' . (int)$_SESSION['user_id']);    
    exit;
}

// Vulnerable: user_id comes straight from the query string with no ownership check.
// A logged-in user can view ANY profile by changing this value.
$requested_id = $_GET['user_id'] ?? $_SESSION['user_id'];

try {
    $pdo = get_db();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$requested_id]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $profile = null;
}

if (!$profile) {
http_response_code(404);
die("<h1>User not found.</h1>");
}

// Correction 5: Unlock Stage 3 when the developer profile is accessed
if (
(int)$requested_id === 1 &&
$profile['role'] === 'developer'
) {
$_SESSION['stage2_unlocked'] = true;
}
?>
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Obsidian — Contact Record</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<h1>Contact Record</h1>
<p>
    Investigation note: Records are assigned numeric IDs.
    The earliest account may contain information that is not
    available in your own profile.
</p>

<p><strong>Handle:</strong> <?= htmlspecialchars($profile['username']) ?></p>
<p><strong>Name:</strong> <?= htmlspecialchars($profile['full_name']) ?></p>
<p><strong>Status:</strong> <?= htmlspecialchars($profile['role']) ?></p>
<p><strong>Summary:</strong> <?= htmlspecialchars($profile['bio']) ?></p>

<?php if ($profile['role'] === 'developer'): ?>
    <hr>
    <h2>Internal Memo</h2>
    <p><?= htmlspecialchars($profile['notes']) ?></p>
    <p><a href="/admin_login.php">Backend system access</a></p>
<?php endif; ?>

<p><a href="/logout.php">Disconnect</a></p>
</body>
</html>