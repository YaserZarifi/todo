<?php
// Database credentials
$host = 'localhost'; // Hostname
$db_username = 'root'; // MySQL username
$db_password = ''; // MySQL password (leave blank if none)
$database = 'TODO'; // Database name

// Attempt to establish a connection to the database
$mysqli = new mysqli($host, $db_username, $db_password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Set charset to UTF-8
$mysqli->set_charset("utf8mb4");
