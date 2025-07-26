<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get difficulty from URL parameter
$difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : 'easy';
if (!in_array($difficulty, ['easy', 'medium', 'hard'])) {
    $difficulty = 'easy';
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Typing Speed Test - <?php echo ucfirst($difficulty); ?></title>
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
            <h1>Typing Speed Test</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="profile.php">Profile</a>
                <a href="leaderboard.php">Leaderboard</a>
                <a href="logout.php">Logout</a>
            </nav>
        </header>

        <div class="difficulty-selector">
            <button id="difficulty-toggle" class="difficulty-toggle-btn">
                Select Difficulty <i class="fas fa-chevron-down"></i>
            </button>
            <div class="difficulty-options" id="difficulty-options">
                <button class="difficulty-btn" data-difficulty="easy">Easy</button>
                <button class="difficulty-btn" data-difficulty="medium">Medium</button>
                <button class="difficulty-btn" data-difficulty="hard">Hard</button>
            </div>
        </div>

        <div class="typing-test">
            <div class="stats">
                <div class="stat">
                    <span>Time:</span>
                    <span id="timer">0</span>s
                </div>
                <div class="stat">
                    <span>WPM:</span>
                    <span id="wpm">0</span>
                </div>
                <div class="stat">
                    <span>Accuracy:</span>
                    <span id="accuracy">0</span>%
                </div>
            </div>

            <div id="text-display" class="text-display"></div>
            <textarea id="text-input" placeholder="Start typing here..." disabled></textarea>
            <button id="start-btn">Start Test</button>
        </div>

        <div class="history">
            <h2>Your History</h2>
            <div id="history-list"></div>
        </div>
    </div>

    <script src="script.js"></script>
    <script src="theme.js"></script>
    <script>
        // Set initial difficulty
        const urlParams = new URLSearchParams(window.location.search);
        const difficulty = urlParams.get('difficulty') || 'easy';
        document.querySelectorAll('.difficulty-btn').forEach(btn => {
            if (btn.dataset.difficulty === difficulty) {
                btn.classList.add('active');
            }
        });
    </script>
</body>
</html> 