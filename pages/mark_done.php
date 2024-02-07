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

    // Retrieve current status of the task
    $sql_status = "SELECT status FROM tasks WHERE id = ?";
    if ($stmt_status = $mysqli->prepare($sql_status)) {
        // Bind parameter to the prepared statement
        $stmt_status->bind_param("i", $task_id);

        // Execute the prepared statement
        if ($stmt_status->execute()) {
            // Store result
            $stmt_status->store_result();

            // Check if task exists
            if ($stmt_status->num_rows == 1) {
                // Bind result variable
                $stmt_status->bind_result($current_status);
                if ($stmt_status->fetch()) {
                    // Toggle status
                    $new_status = ($current_status == 'Complete') ? 'Incomplete' : 'Complete';

                    // Update task status in the database
                    $sql_update = "UPDATE tasks SET status = ? WHERE id = ?";
                    if ($stmt_update = $mysqli->prepare($sql_update)) {
                        // Bind parameters to the prepared statement
                        $stmt_update->bind_param("si", $new_status, $task_id);

                        // Execute the prepared statement
                        if ($stmt_update->execute()) {
                            // Task status updated successfully, redirect to dashboard
                            header("location: dashboard.php");
                            exit();
                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }

                        // Close statement
                        $stmt_update->close();
                    }
                }
            } else {
                // Task not found, redirect to dashboard or display error message
                header("location: dashboard.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt_status->close();
    }
} else {
    // Redirect to dashboard if task ID is not provided
    header("location: dashboard.php");
    exit();
}

// Close connection
$mysqli->close();
?>
