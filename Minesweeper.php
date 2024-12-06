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

<!-- Modal -->
	<div id="gameGuideModal" class="modal">
		<div class="modal-content">
        <span class="close">&times;</span>
        <h2>How to Play Minesweeper</h2>
        <p>
            1. Select a difficulty level to set the grid size and the number of mines.<br>
            2. Left-click a square to reveal it. If it contains a mine, you lose!<br>
            3. Numbers indicate how many mines are adjacent to that square.<br>
            4. Right-click to flag a square if you suspect it has a mine.<br>
            5. Clear all non-mine squares to win the game, Good luck and have fun!<br>
        </p>
        <button id="closeGuideButton">Start Game</button>
		</div>
	</div>


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
		
		
	document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('gameGuideModal');
    const closeBtn = document.querySelector('.close');
    const startBtn = document.getElementById('closeGuideButton');

    // Show the modal when the page loads
    modal.style.display = 'block';

    // Close the modal when the close button or start button is clicked
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    startBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Close the modal if the user clicks outside the modal
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});

		
    </script>
</body>
<footer>
<p>
All code done by Javier Escareno 300593944, To contact me please email me at jayescareno22@mail.fresnostate.edu<br>

</P>
</footer>
</html>


