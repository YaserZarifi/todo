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

// Check if task ID is provided in the URL
if (isset($_GET['id'])) {
    // Retrieve task ID from URL parameter
    $task_id = $_GET['id'];

    // Prepare SQL statement to delete task from database
    $sql = "DELETE FROM tasks WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind parameter to the prepared statement
        $stmt->bind_param("i", $task_id);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Task deleted successfully, redirect to dashboard
            header("location: dashboard.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt->close();
    }
} else {
    // Redirect to dashboard if task ID is not provided
    header("location: dashboard.php");
    exit();
}

// Close connection
$mysqli->close();
?>
