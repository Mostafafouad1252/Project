<?php
session_start();
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Typing Speed Test</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="theme-toggle">
        <button id="theme-toggle-btn">
            <i class="fas fa-sun"></i>
        </button>
    </div>

    <div class="container home-container">
        <header>
            <h1>Typing Speed Test</h1>
            <p class="tagline">Improve your typing speed and accuracy</p>
            <?php if (isset($_SESSION['user_id'])): ?>
                <nav>
                    <a href="typing_test.php">Typing Test</a>
                    <a href="profile.php">Profile</a>
                    <a href="leaderboard.php">Leaderboard</a>
                    <a href="logout.php">Logout</a>
                </nav>
            <?php endif; ?>
        </header>

        <div class="features">
            <div class="feature-card">
                <i class="fas fa-tachometer-alt"></i>
                <h3>Speed Test</h3>
                <p>Test your typing speed with different difficulty levels</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-chart-line"></i>
                <h3>Track Progress</h3>
                <p>Monitor your improvement over time</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-trophy"></i>
                <h3>Leaderboard</h3>
                <p>Compete with other typists</p>
            </div>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="start-test-container">
                <a href="typing_test.php" class="start-test-btn">
                    <i class="fas fa-keyboard"></i>
                    Start Typing Test
                </a>
            </div>
        <?php else: ?>
            <div class="auth-buttons">
                <a href="login.php" class="btn btn-primary">Login</a>
                <a href="register.php" class="btn btn-secondary">Register</a>
            </div>
        <?php endif; ?>
    </div>
    <script src="theme.js"></script>
</body>
</html> 