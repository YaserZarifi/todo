<?php
// Start the session
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: login.php");
    exit;
}

// Include the database connection script
require_once('../includes/db.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];

    // Check if the new username is already taken
    $sql = "SELECT id FROM users WHERE username = ? AND id != ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("si", $newUsername, $_SESSION["id"]);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        // Username already exists
        $_SESSION['error'] = "Username is already taken.";
        $stmt->close();
        header("location: edit_profile.php");
        exit;
    }
    $stmt->close();

    // Check if the new email is already taken
    $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("si", $newEmail, $_SESSION["id"]);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        // Email already exists
        $_SESSION['error'] = "Email is already registered.";
        $stmt->close();
        header("location: edit_profile.php");
        exit;
    }
    $stmt->close();

    // Update user's profile information in the database
    $user_id = $_SESSION["id"];
    $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssi", $newUsername, $newEmail, $user_id);
    $stmt->execute();
    $stmt->close();

    // Update session variables if needed
    $_SESSION['username'] = $newUsername;
    $_SESSION['email'] = $newEmail;

    // Redirect back to the profile page with a success message
    header("location: profile.php?success=1");
    exit;
} else {
    // If the form is not submitted via POST method, redirect to the profile page
    header("location: profile.php");
    exit;
}
?>
