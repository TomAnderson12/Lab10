<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to update email
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $newEmail = $_POST['email'];
    $currentUsername = $_SESSION['username'];

    // Update email in the database
    $stmt = $conn->prepare("UPDATE users SET email = ? WHERE username = ?");
    $stmt->bind_param("ss", $newEmail, $currentUsername);

    if ($stmt->execute()) {
        $successMessage = "Email updated successfully!";
    } else {
        $errorMessage = "Error updating email: " . $conn->error;
    }

    $stmt->close();
}

// Fetch user details
$currentUsername = $_SESSION['username'];
$stmt = $conn->prepare("SELECT username, email FROM users WHERE username = ?");
$stmt->bind_param("s", $currentUsername);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User details not found.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profile</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

    <form action="profile.php" method="POST">
        <label for="email">Update Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <button type="submit">Update</button>
    </form>
</body>
</html>