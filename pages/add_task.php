<!-- add_task.php -->

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

// Process form submission when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve task data from the form
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $user_id = $_SESSION['id'];

    // Prepare SQL statement to insert task into database
    $sql = "INSERT INTO tasks (user_id, title, description, due_date, status) VALUES (?, ?, ?, ?, 'Incomplete')";
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind parameters to the prepared statement
        $stmt->bind_param("isss", $user_id, $title, $description, $due_date);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Task added successfully, redirect to dashboard
            header("location: dashboard.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt->close();
    }
}

// Close connection
$mysqli->close();
?>
