<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header('Location: login.html');
    exit;
}

// Get the score from the POST request
$score = $_POST['score'];
$username = $_SESSION['username'];

// Database connection (make sure to configure with your database details)
$host = 'localhost'; // Database host
$dbname = 'minesweeper_game'; // The database name
$username_db = 'root'; // Database username (adjust as necessary)
$password_db = ''; // Database password (adjust as necessary)

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insert the score into the leaderboard table
    $stmt = $pdo->prepare("INSERT INTO leaderboard (username, score) VALUES (:username, :score)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':score', $score);
    $stmt->execute();

    // Return success message
    echo "Score submitted successfully!";
} catch (PDOException $e) {
    // Handle connection errors
    echo "Error: " . $e->getMessage();
}
?>

