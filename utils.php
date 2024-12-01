<?php
include_once('db.php');
//include_once('utils.php');

// Fetch user_id and session_code from cookies
$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';
$session_code = isset($_COOKIE['session_code']) ? $_COOKIE['session_code'] : '';

//echo $user_id . ':' . $session_code;

// Function to make SQL errors readable
function sql_error(){
		global $connection;
		if (mysqli_errno($connection)) {
				return ' <br/>' .  mysqli_errno($connection) . ' : ' . mysqli_error($connection);
		}
		return '';
}

// Function to check if a user is logged in
function is_user_logged_in() {
    // Check if user_id and session_code exist in cookies
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['session_code'])
		&& !empty($_COOKIE['user_id']) && !empty($_COOKIE['session_code'])
    ) {
		// Get user_id and session_code from cookies
				$user_id = $_COOKIE['user_id'] ?? '';
				$session_code = $_COOKIE['session_code'] ?? '';
        //$user_id = $_COOKIE['user_id'];
        //$session_code = $_COOKIE['session_code'];

        // Retrieve user data from the database
        global $connection;
        $query = "SELECT * FROM users WHERE user_id = '$user_id' AND session_code = '$session_code'";
        $result = mysqli_query($connection, $query);

        // Return true if user is authorized, false otherwise
        return (mysqli_num_rows($result) === 1);
    }

    return false;
}

// Function to check if a user is an admin
function is_user_admin() {
    if (is_user_logged_in()) {
        $user_id = $_COOKIE['user_id'];

        // Retrieve the first user's ID from the users table
        global $connection;
        $query = "SELECT user_id FROM users ORDER BY user_id ASC LIMIT 1";
        $result = mysqli_query($connection, $query);
        $first_user = mysqli_fetch_assoc($result);

        // Return true if user is the admin, false otherwise
        return $user_id === $first_user['user_id'];
    }

    return false;
}

// Function to generate a random session code
function generate_session_code() {
    $length = 32;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $session_code = '';
    for ($i = 0; $i < $length; $i++) {
        $session_code .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $session_code;
}

// Function to verify if the inserted password is the stored password
function verify_password($password, $hashed_password) {
    // Use the password_verify function to check if the provided password matches the hashed password
    return $password === $hashed_password;
}
/*
function password_hash($password, $args) {
	return $password;
}*/

function get_username($user_id) {
    global $connection;

    // Prepare the query
    $query = "SELECT username FROM users WHERE user_id = $user_id";

    // Execute the query
    $result = mysqli_query($connection, $query);

    // Check if the query was successful
    if ($result) {
        // Fetch the username from the result
        $row = mysqli_fetch_assoc($result);
        $username = $row['username'];

        // Free the result memory
        mysqli_free_result($result);

        // Return the username
        return $username;
    } else {
        // Query execution failed
        return null;
    }
}

function messages_to_show(){
	global $connection, $success_message, $error_message;
	if (isset($success_message)){
		echo "<p class=\"success\">$success_message</p>";
	}
	if (isset($error_message)){
		echo "<p class=\"error\">$error_message</p>";
	}
	if (mysqli_errno($connection)){
		echo "<p class=\"error\">".sql_error()."</p>";
	}
}

function get_post_url($website_id) {
    // Retrieve the post URL from the websites table for the given website ID
    global $connection;
    $query = "SELECT post_url FROM websites WHERE website_id = $website_id";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['post_url'];
    }

    // Return empty if not found in the database
    return "";
}

function get_redirect_url($website_id) {
    // Retrieve the redirect URL from the websites table for the given website ID
    global $connection;
    $query = "SELECT redirect_url FROM websites WHERE website_id = $website_id";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['redirect_url'];
    }

    // Return empty if not found in the database
    return "";
}

function get_website_url($website_id) {
    // Retrieve the redirect URL from the websites table for the given website ID
    global $connection;
    $query = "SELECT website_url FROM websites WHERE website_id = $website_id";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['website_url'];
    }

    // Return empty if not found in the database
    return "";
}

?>