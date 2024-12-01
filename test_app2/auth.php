<?php
include_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : (isset($_GET['user_id']) ? $_GET['user_id'] : '');
    $session_code = isset($_POST['session_code']) ? $_POST['session_code'] : (isset($_GET['session_code']) ? $_GET['session_code'] : '');

		// Check if the user ID exists in the test_users table
		$query = "SELECT * FROM ".$tab_prefix."users WHERE user_id = '$user_id'";
		$result = mysqli_query($connection, $query);

		if ($result && mysqli_num_rows($result) > 0) {
				// User ID exists, update the session_code
				$update_query = "UPDATE ".$tab_prefix."users SET session_code = '$session_code' WHERE user_id = '$user_id'";
				mysqli_query($connection, $update_query);
		} else {
				// User ID doesn't exist, insert the user_id and session_code
				$insert_query = "INSERT INTO ".$tab_prefix."users (user_id, session_code) VALUES ('$user_id', '$session_code')";
				mysqli_query($connection, $insert_query);
		}
}
?>
