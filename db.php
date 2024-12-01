<?php
// Database configuration
$db_host = 'localhost';  // Replace with your host name
$db_username = 'root';  // Replace with your database username
$db_password = '123456';  // Replace with your database password
$db_database = 'dizertatie';  // Replace with your database name

// Establish database connection
$connection = mysqli_connect($db_host, $db_username, $db_password, $db_database);

// Deleting the sensitive variables, in case of code injection.
unset($db_host);
unset($db_username);
unset($db_password);
unset($db_database);

// Check if the connection was successful
if (!$connection) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// Set the character set to UTF-8 (optional, adjust as needed)
mysqli_set_charset($connection, 'utf8');

