<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user statistics for leaderboard
$sql = "SELECT 
            u.id as user_id,
            u.username,
            MAX(t.wpm) as best_wpm,
            AVG(t.wpm) as avg_wpm,
            COUNT(t.id) as total_tests
        FROM users u
        LEFT JOIN typing_results t ON u.id = t.user_id
        GROUP BY u.id, u.username
        ORDER BY best_wpm DESC, avg_wpm DESC
        LIMIT 10";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching leaderboard data: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Typing Speed Test</title>
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
            <h1>Leaderboard</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="typing_test.php">Typing Test</a>
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            </nav>
        </header>

        <div class="leaderboard">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Username</th>
                            <th>Best WPM</th>
                            <th>Average WPM</th>
                            <th>Tests Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rank = 1;
                        while ($row = $result->fetch_assoc()) {
                            $isCurrentUser = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id'];
                            echo "<tr" . ($isCurrentUser ? " class='current-user'" : "") . ">";
                            echo "<td>" . $rank++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>" . ($row['best_wpm'] ? number_format($row['best_wpm']) : '0') . "</td>";
                            echo "<td>" . ($row['avg_wpm'] ? number_format($row['avg_wpm'], 1) : '0.0') . "</td>";
                            echo "<td>" . $row['total_tests'] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-results">No typing test results available yet. Be the first to take a test!</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="theme.js"></script>
</body>
</html> 