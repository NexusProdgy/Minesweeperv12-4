<?php
session_start();

// Database configuration
$host = 'localhost';
$username_db = 'root'; // Basic Database username
$password_db = ''; //  Basic Database password
$database = 'minesweeper_game'; // Database name

// Establish a database connection
$conn = new mysqli($host, $username_db, $password_db, $database);

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$email = trim($_POST['email']);
$password = trim($_POST['password']);

// Basic form validation
if (empty($email) || empty($password)) {
    die("Please fill in all fields.");
}

// Prepare and execute SQL statement to fetch user data
$stmt = $conn->prepare("SELECT username, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Verify user credentials
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        // Set session variables
        $_SESSION['username'] = $row['username'];
        $_SESSION['loggedin'] = true;

        // Redirect to the game page
        header('Location: minesweeper.php');
        exit();
    } else {
        echo "Login failed. Incorrect password.";
    }
} else {
    echo "Login failed. No account found with that email.";
}

$stmt->close();
$conn->close();
?>

