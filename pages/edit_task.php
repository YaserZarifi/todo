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

// Initialize variables for form inputs and error messages
$title = $description = $due_date = '';
$title_err = $description_err = $due_date_err = '';

// Process form submission when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve task data from the form
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $task_id = $_POST['task_id'];

    // Validate form inputs
    // (You can add validation logic here if needed)

    // If there are no errors, update the task in the database
    if (empty($title_err) && empty($description_err) && empty($due_date_err)) {
        // Prepare SQL statement to update task in database
        $sql = "UPDATE tasks SET title = ?, description = ?, due_date = ? WHERE id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind parameters to the prepared statement
            $stmt->bind_param("sssi", $title, $description, $due_date, $task_id);

            // Execute the prepared statement
            if ($stmt->execute()) {
                // Task updated successfully, redirect to dashboard
                header("location: dashboard.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
} else {
    // Retrieve task ID from URL parameter
    $task_id = $_GET['id'];

    // Prepare SQL statement to retrieve task details from database
    $sql = "SELECT title, description, due_date FROM tasks WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind parameter to the prepared statement
        $stmt->bind_param("i", $task_id);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();

            // Check if task exists
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($title, $description, $due_date);
                if ($stmt->fetch()) {
                    // Task details retrieved successfully
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
        $stmt->close();
    }
}

// Close connection
$mysqli->close();
?>

<!-- HTML form to edit task details -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Edit Task</h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="<?php echo $title; ?>">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3"><?php echo $description; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="date" name="due_date" id="due_date" class="form-control" value="<?php echo $due_date; ?>">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Update Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</
