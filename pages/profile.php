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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>User Profile</h2>
    <?php
    $usernames = $_SESSION["username"];
    $sql = "SELECT email FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $usernames);
    $stmt->execute();
    $stmt->bind_result($user_email);
    $stmt->fetch();
    $stmt->close();
    ?>
    <p><strong>Username:</strong> <?php echo $usernames; ?></p>
    <p><strong>Email:</strong> <?php echo $user_email; ?></p>
</div>
<div class="container mt-5">
    <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
    <a href="dashboard.php" class="btn btn-primary">Dashboard</a>
</div>

</body>
</html>
