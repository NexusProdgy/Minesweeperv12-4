<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to the login page if not logged in
    header('Location: login.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minesweeper Game</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Welcome to Minesweeper, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <div class="container">
        <a href="leaderboard.php" class="leaderboard-btn">View Leaderboard</a>
    </div>

    <!-- Difficulty Dropdown -->
    <label for="difficulty">Select Difficulty:</label>
    <select id="difficulty">
        <option value="easy">Easy (8x8 grid, 10 mines)</option>
        <option value="medium">Medium (12x12 grid, 20 mines)</option>
        <option value="hard">Hard (16x16 grid, 40 mines)</option>
    </select>

    <!-- Game Canvas -->
    <canvas id="board" width="300" height="300"></canvas>
    <div class="score">
        <div id="best">Best Score: 0</div>
        <div id="score">Score: 0</div>
        <div id="timer">Time: 0s</div>
    </div>
    <button id="startButton">Start Game</button>
    <button id="restartButton" style="display:none;">Restart Game</button>

    <!-- Music Toggle Button -->
    <button id="musicToggleButton">Play Music</button>

    <!-- Audio Element -->
    <audio id="backgroundAudio" loop>
        <source src="audio/Piano.mp3" type="audio/mp3">
        Your browser does not support the audio element.
    </audio>
	<button id="toggleBackgroundButton">Toggle Background Color</button>


    <!-- Game Scripts -->
    <script src="app.js"></script>
    <script>
        const musicToggleButton = document.getElementById("musicToggleButton");
        const audio = document.getElementById("backgroundAudio");

        let isMusicPlaying = false;

        // Toggle music playback
        musicToggleButton.addEventListener("click", () => {
            if (isMusicPlaying) {
                audio.pause();
                musicToggleButton.textContent = "Play Music";
            } else {
                audio.play().catch(err => console.error("Audio playback error:", err));
                musicToggleButton.textContent = "Pause Music";
            }
            isMusicPlaying = !isMusicPlaying;
        });
    </script>
</body>
</html>

