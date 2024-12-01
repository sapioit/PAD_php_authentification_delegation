<?php
// Database configuration
$host = 'localhost';  // Replace with your host name
$username = 'root';  // Replace with your database username
$password = '123456';  // Replace with your database password
$database = 'dizertatie';  // Replace with your database name
$tab_prefix = 'atest2_';  // Replace with the prefix you want for table names

// Establish database connection
$connection = mysqli_connect($host, $username, $password, $database);

// Check if the connection was successful
if (!$connection) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// Set the character set to UTF-8 (optional, adjust as needed)
mysqli_set_charset($connection, 'utf8');
