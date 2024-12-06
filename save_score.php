<?php
// Database connection
$servername = "localhost";
$username = "root";  
$password = "";     
$dbname = "minesweeper_game";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the request
$username = $_POST['username'];
$score = $_POST['score'];

// Validate inputs
if (empty($username) || empty($score)) {
    die("Username and score are required.");
}

// Insert into leaderboard table
$stmt = $conn->prepare("INSERT INTO leaderboard (username, score) VALUES (?, ?)");
$stmt->bind_param("si", $username, $score);

if ($stmt->execute()) {
    echo "Score saved successfully!";
} else {
    echo "Error saving score: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>