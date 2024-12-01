<?php
include_once('db.php');

// Clear the session code for the logged-in user
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];

    $update_query = "UPDATE users SET session_code = '' WHERE user_id = $user_id";
    $update_result = mysqli_query($connection, $update_query);

    if (!$update_result) {
        echo "Error updating session code: " . mysqli_error($connection);
        exit;
    }
}

// Clear the user_id and session_code cookies
setcookie('user_id', '', time() - 3600, '/'); // Expire the cookie (set it in the past)
setcookie('session_code', '', time() - 3600, '/'); // Expire the cookie (set it in the past)

if (isset($error_message)) {
      echo '<p class="error container">'.sql_error().'</p>';
}

// Redirect to the login page
header("Location: login.php");
exit;
?>
