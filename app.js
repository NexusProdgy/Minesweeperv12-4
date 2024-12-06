// DOM Elements
const boardElement = document.getElementById("board");
const scoreElement = document.getElementById("score");
const bestScoreElement = document.getElementById("best");
const timerElement = document.getElementById("timer");
const startButton = document.getElementById("startButton");
const restartButton = document.getElementById("restartButton");
const difficultySelect = document.getElementById("difficulty");

// Game Variables
let boardSize = 8; // Default board size
let cellSize = 30; // Size of each cell in pixels
let totalMines = 10; // Default mine count
let board = [];
let mines = [];
let revealedCells = 0;
let currentScore = 0;
let bestScore = 0;
let timer;
let timeElapsed = 0;
let gameOverImage = new Image(); // Create a new image object

// Set the source for the game over image
gameOverImage.src = 'go.jpg'; 

// Set difficulty based on selection
function setDifficulty() {
    const difficulty = difficultySelect.value;
    if (difficulty === "easy") {
        boardSize = 8;
        totalMines = 10;
    } else if (difficulty === "medium") {
        boardSize = 12;
        totalMines = 20;
    } else if (difficulty === "hard") {
        boardSize = 16;
        totalMines = 40;
    }
    boardElement.width = boardSize * cellSize;
    boardElement.height = boardSize * cellSize;
}

// Start the game when Start Game button is clicked
startButton.addEventListener("click", () => {
    setDifficulty();
    startGame();
    startButton.style.display = "none";
    restartButton.style.display = "inline-block";
});

// Restart the game when Restart Game button is clicked
restartButton.addEventListener("click", () => {
    resetGame();
    startGame();
});

// Timer function
function startTimer() {
    timer = setInterval(() => {
        timeElapsed++;
        timerElement.textContent = `Time: ${timeElapsed}s`;
    }, 1000);
}

function stopTimer() {
    clearInterval(timer);
    timeElapsed = 0;
}

// Start the game
function startGame() {
    resetGame();
    createBoard();
    placeMines();
    startTimer();
    boardElement.addEventListener("click", handleCanvasClick);
    boardElement.addEventListener("contextmenu", handleRightClick); // Add right-click event
}

// Reset the game state
function resetGame() {
    stopTimer();
    timeElapsed = 0;
    revealedCells = 0;
    currentScore = 0;
    scoreElement.textContent = "Score: 0";
    timerElement.textContent = "Time: 0s";

    // Clear the board and mines
    board = [];
    mines = [];

    // Clear the canvas
    const context = boardElement.getContext("2d");
    context.clearRect(0, 0, boardElement.width, boardElement.height);
}

// Function to create the board and draw cells
function createBoard() {
    const context = boardElement.getContext("2d");
    context.strokeStyle = "#000";

    // Draw cells based on the board size
    for (let i = 0; i < boardSize; i++) {
        board[i] = [];
        for (let j = 0; j < boardSize; j++) {
            // Draw cell borders
            context.strokeRect(i * cellSize, j * cellSize, cellSize, cellSize);

            // Initialize cell properties
            board[i][j] = {
                row: i,
                col: j,
                revealed: false,
                hasMine: false,
                neighborMines: 0,
                flagged: false, // Add flagged state
            };
        }
    }
}

// Function to place mines on the board
function placeMines() {
    let minesPlaced = 0;
    while (minesPlaced < totalMines) {
        const row = Math.floor(Math.random() * boardSize);
        const col = Math.floor(Math.random() * boardSize);

        if (!board[row][col].hasMine) {
            board[row][col].hasMine = true;
            mines.push({ row, col });
            minesPlaced++;
        }
    }
}

// Calculate and display neighbor mine counts
function countNeighborMines(row, col) {
    let count = 0;
    for (let i = row - 1; i <= row + 1; i++) {
        for (let j = col - 1; j <= col + 1; j++) {
            if (i >= 0 && i < boardSize && j >= 0 && j < boardSize && board[i][j].hasMine) {
                if (i !== row || j !== col) {
                    count++;
                }
            }
        }
    }
    board[row][col].neighborMines = count;
    return count;
}

// Reveal cell on canvas
function revealCell(cell) {
    const context = boardElement.getContext("2d");

    // Return if cell is already revealed or flagged
    if (cell.revealed || cell.flagged) return;

    cell.revealed = true;
    revealedCells++;

    const { row, col } = cell;
    const x = row * cellSize;
    const y = col * cellSize;

    if (cell.hasMine) {
        context.fillStyle = "#FF0000";
        context.fillRect(x, y, cellSize, cellSize);
        // End game logic here if mine is revealed
        gameOver();
    } else {
        const neighborMines = countNeighborMines(row, col);
        context.fillStyle = "#B0E0E6";
        context.fillRect(x, y, cellSize, cellSize);
        context.fillStyle = "#000";
        if (neighborMines > 0) {
            context.fillText(neighborMines, x + cellSize / 3, y + cellSize / 1.5);
        } else {
            // If no neighboring mines, reveal surrounding cells
            revealEmptyCells(row, col);
        }
        currentScore++;
        scoreElement.textContent = `Score: ${currentScore}`;

        // Check for victory
        if (revealedCells === boardSize * boardSize - totalMines) {
            victory();
        }
    }
}

// Handle right-click for flagging cells
function handleRightClick(event) {
    event.preventDefault(); // Prevent the default context menu from showing
    const rect = boardElement.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;

    const row = Math.floor(x / cellSize);
    const col = Math.floor(y / cellSize);
    const cell = board[row][col];

    // Toggle flagged state
    cell.flagged = !cell.flagged;

    // Redraw the board to show flagged cells
    drawBoard();
}

// Recursive function to reveal empty cells and their neighbors
function revealEmptyCells(row, col) {
    for (let i = row - 1; i <= row + 1; i++) {
        for (let j = col - 1; j <= col + 1; j++) {
            if (
                i >= 0 && i < boardSize &&
                j >= 0 && j < boardSize &&
                !board[i][j].revealed &&
                !board[i][j].hasMine
            ) {
                const neighborCell = board[i][j];
                revealCell(neighborCell); // Reveal this cell
            }
        }
    }
}

// Draw the board to show all cells, including flagged cells
function drawBoard() {
    const context = boardElement.getContext("2d");
    context.clearRect(0, 0, boardElement.width, boardElement.height);

    for (let i = 0; i < boardSize; i++) {
        for (let j = 0; j < boardSize; j++) {
            const cell = board[i][j];
            const x = i * cellSize;
            const y = j * cellSize;

            // Draw cell border
            context.strokeRect(x, y, cellSize, cellSize);

            // Draw flagged cells
            if (cell.flagged) {
                context.fillStyle = "#FFD700"; // Flag color
                context.fillRect(x + 5, y + 5, cellSize - 10, cellSize - 10); // Draw flag rectangle
                context.fillStyle = "#000"; // Reset color for other drawings
                context.fillText("🚩", x + cellSize / 3, y + cellSize / 1.5); // Draw flag emoji
            }

            // If cell is revealed, draw it
            if (cell.revealed) {
                if (cell.hasMine) {
                    context.fillStyle = "#FF0000";
                    context.fillRect(x, y, cellSize, cellSize);
                } else {
                    const neighborMines = countNeighborMines(i, j);
                    context.fillStyle = "#B0E0E6";
                    context.fillRect(x, y, cellSize, cellSize);
                    context.fillStyle = "#000";
                    if (neighborMines > 0) {
                        context.fillText(neighborMines, x + cellSize / 3, y + cellSize / 1.5);
                    }
                }
            }
        }
    }
}

// Handle clicks on canvas for cell interaction
function handleCanvasClick(event) {
    const rect = boardElement.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;

    const row = Math.floor(x / cellSize);
    const col = Math.floor(y / cellSize);
    const cell = board[row][col];

    revealCell(cell);
}

function victory() {
    alert("Congratulations! You cleared the board.");

    // Submit the score after victory
    submitScore(currentScore);

    resetGame();
}

function gameOver() {
    const context = boardElement.getContext("2d");
    context.clearRect(0, 0, boardElement.width, boardElement.height); // Clear the board
    context.drawImage(gameOverImage, 0, 0, boardElement.width, boardElement.height); // Draw game over image

    // Submit the score after game over
    submitScore(currentScore);
}

// Function to submit the score to the leaderboard
function submitScore(score) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "submit_score.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log("Score submitted: " + score);
            alert("Your score has been submitted to the leaderboard!");
        }
    };
    xhr.send("score=" + score);
}

// Function to start or restart the game
function startGameLogic() {
    setDifficulty(); // Adjust game difficulty
    resetGame();     
    startGame();    
}

document.addEventListener("DOMContentLoaded", function () {
    const button = document.getElementById("toggleBackgroundButton");

    // Define two background color themes
    const backgroundThemes = {
        default: "#FFFFFF", 
        fresnoState: "#005A9C", 
    };

    let currentBackgroundTheme = "default";

    button.addEventListener("click", function () {
        // Toggle the background theme
        currentBackgroundTheme = (currentBackgroundTheme === "default") ? "fresnoState" : "default";

        // Apply the new background color
        document.body.style.backgroundColor = backgroundThemes[currentBackgroundTheme];
    });
});
