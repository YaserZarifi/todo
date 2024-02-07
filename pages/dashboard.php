<!-- dashboard.php -->

<?php
ini_set('session.gc_lifetime', 10);
// Start the session
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: login.php");
    exit;
}

// Include the database connection script
require_once('../includes/db.php');

// Function to retrieve user's tasks from the database
function getTasks($mysqli, $user_id) {
    $tasks = array();
    $sql = "SELECT id, title, description, due_date, status FROM tasks WHERE user_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($task_id, $title, $description, $due_date, $status);
        while ($stmt->fetch()) {
            $tasks[] = array(
                'id' => $task_id,
                'title' => $title,
                'description' => $description,
                'due_date' => $due_date,
                'status' => $status
            );
        }
        $stmt->close();
    }
    return $tasks;
}

// Retrieve user's tasks
$user_id = $_SESSION["id"];
$tasks = getTasks($mysqli, $user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager - Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
            <hr>
            <h3>Your Tasks</h3>
            <table class="table">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?php echo $task['title']; ?></td>
                        <td><?php echo $task['description']; ?></td>
                        <td><?php echo $task['due_date']; ?></td>
                        <td><?php echo $task['status']; ?></td>
                        <td>
                            <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                            <?php
                            $button_label = ($task['status'] == 'Complete') ? 'Undone' : 'Done';
                            $button_class = ($task['status'] == 'Complete') ? 'btn-success' : 'btn-info';
                            ?>
                            <a href="mark_done.php?id=<?php echo $task['id']; ?>" class="btn btn-sm <?php echo $button_class; ?>"><?php echo $button_label; ?></a>

                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <h3>Add New Task</h3>
            <form action="add_task.php" method="post">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <input type="date" name="due_date" id="due_date" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Task</button>
            </form>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-12">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <div class="col-md-12">
            <a href="profile.php" class="btn btn-primary">Profile</a>
        </div>
    </div>
</div>



<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
