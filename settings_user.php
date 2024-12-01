<?php
include_once('db.php');
include_once('utils.php');

// Check user authorization
if (!is_user_logged_in()) {
    header('Location: login.php');
    exit();
}

// Fetch user data
$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($connection, $query);

// Check if user exists
if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    $error_message = "User not found.";
}

// Handle form submission
if ( count($_POST) > 0 ) {
	// Update user password if provided
	$password = $_POST['password'] ?? '';
	$old_password = $_POST['old_password'] ?? '';
	if (!empty($password)) {
		$password = mysqli_real_escape_string($connection, $password);
		if ($old_password == $user['password'] ){
			//$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
			$hashedPassword = $password;
			$query = "UPDATE users SET password = '$hashedPassword' WHERE user_id = '$user_id'";
			mysqli_query($connection, $query);
			$success_message = "Password change successful.";
		} else {
			$error_message = "Old password is incorrect.";
		}
	} else {
		$error_message = "Old password is missing.";
	}
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Settings - User</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
		<?php require_once('menu.php'); ?>
    <h1>Settings - User</h1>
    <form class="container" method="POST">
				<?php echo messages_to_show(); ?>

        <label for="old_password">Old Password:</label>
        <input type="password" name="old_password" id="old_password" required>
        <label for="password">New Password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit" value="submit">Update Password</button>
    </form>
</body>
</html>
