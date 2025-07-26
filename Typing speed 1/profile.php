<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user information
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

if (!$user) {
    die("Error fetching user data: " . $conn->error);
}

// Get user's typing history
$sql = "SELECT wpm, accuracy, test_date FROM typing_results WHERE user_id = ? ORDER BY test_date DESC LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$history_result = $stmt->get_result();

if (!$history_result) {
    die("Error fetching typing history: " . $conn->error);
}

// Store recent tests in an array
$recent_tests = [];
while ($row = $history_result->fetch_assoc()) {
    $recent_tests[] = $row;
}

// Calculate user statistics
$sql = "SELECT 
            MAX(wpm) as best_wpm,
            AVG(wpm) as avg_wpm,
            AVG(accuracy) as avg_accuracy,
            COUNT(*) as total_tests
        FROM typing_results 
        WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stats_result = $stmt->get_result();
$stats = $stats_result->fetch_assoc();

if (!$stats) {
    die("Error fetching statistics: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Typing Speed Test</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="theme-toggle">
        <button id="theme-toggle-btn">
            <i class="fas fa-sun"></i>
        </button>
    </div>

    <div class="container">
        <header>
            <h1>Profile</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="typing_test.php">Typing Test</a>
                <a href="leaderboard.php">Leaderboard</a>
                <a href="logout.php">Logout</a>
            </nav>
        </header>

        <div class="profile-info">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            <p>Member since: <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
        </div>

        <div class="stats-grid">
            <div class="stat-box">
                <h3>Average WPM</h3>
                <p><?php echo number_format($stats['avg_wpm'], 1); ?></p>
            </div>
            <div class="stat-box">
                <h3>Best WPM</h3>
                <p><?php echo $stats['best_wpm']; ?></p>
            </div>
            <div class="stat-box">
                <h3>Average Accuracy</h3>
                <p><?php echo number_format($stats['avg_accuracy'], 1); ?>%</p>
            </div>
            <div class="stat-box">
                <h3>Tests Completed</h3>
                <p><?php echo $stats['total_tests']; ?></p>
            </div>
        </div>

        <div class="typing-history">
            <h2>Recent Tests</h2>
            <?php if (empty($recent_tests)): ?>
                <p class="no-results">No tests completed yet. <a href="typing_test.php">Start your first test!</a></p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>WPM</th>
                            <th>Accuracy</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_tests as $test): ?>
                            <tr>
                                <td><?php echo date('M j, Y g:i A', strtotime($test['test_date'])); ?></td>
                                <td><?php echo $test['wpm']; ?></td>
                                <td><?php echo $test['accuracy']; ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    <script src="theme.js"></script>
</body>
</html> 