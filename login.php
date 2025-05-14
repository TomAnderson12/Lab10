<?php
session_start();

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "user"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Prepare and execute SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $inputUsername, $inputPassword);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login successful
        $_SESSION['username'] = $inputUsername; // Store username in session
        header("Location: profile.php"); // Redirect to profile.php
        exit();
    }

    $stmt->close();
}

$conn->close();
?>

<form action="login.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <button type="submit">Login</button>
</form>