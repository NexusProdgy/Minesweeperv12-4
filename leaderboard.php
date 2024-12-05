<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header('Location: login.html');
    exit;
}

// Database connection (adjust according to your settings)
$host = 'localhost';
$dbname = 'minesweeper_game'; // The name of the database
$username_db = 'root'; // Database username (adjust as necessary)
$password_db = ''; // Database password (adjust as necessary)

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get the leaderboard
    $stmt = $pdo->prepare("SELECT username, score FROM leaderboard ORDER BY score DESC LIMIT 10");
    $stmt->execute();
    $leaderboard = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="leaderboard.css">
</head>
<body>
    <h1>Leaderboard</h1>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Username</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rank = 1;
            foreach ($leaderboard as $entry) {
                echo "<tr><td>{$rank}</td><td>{$entry['username']}</td><td>{$entry['score']}</td></tr>";
                $rank++;
            }
            ?>
        </tbody>
    </table>
    <a href="Minesweeper.php">Back to Game</a>
</body>
</html>


