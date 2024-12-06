<?php
// Start the session
session_start();

// Database configuration
$host = 'localhost';
$username_db = 'root'; // Database username
$password_db = ''; // Database password
$database = 'minesweeper_game'; // Database name

// Establish a database connection
$conn = new mysqli($host, $username_db, $password_db, $database);

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$email = trim($_POST['email']);
$username = trim($_POST['username']);
$password = trim($_POST['password']);

// Basic validation
if (empty($email) || empty($username) || empty($password)) {
    die("Please fill in all fields.");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}
if (strlen($password) < 6) {
    die("Password must be at least 6 characters long.");
}

// Hash the password for secure storage
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare and execute SQL statement to insert user data
$stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $username, $hashed_password);

try {
    $stmt->execute();
    $stmt->close();

    // Set session variables and redirect to game page
    $_SESSION['email'] = $email;
    $_SESSION['username'] = $username;
    $_SESSION['loggedin'] = true;

    header('Location: minesweeper.php');
    exit();
} catch (mysqli_sql_exception $e) {
    if ($conn->errno === 1062) {
        die("Error: The email is already registered.");
    } else {
        die("Error: " . $e->getMessage());
    }
}
?>

