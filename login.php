<?php
include_once('db.php');
include_once('utils.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve user data from the database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        // Verify the password
        if (verify_password($password, $user['password'])) {
            // Set session code and user ID
            $session_code = generate_session_code();
            $user_id = $user['user_id'];

            // Update session code in the database
            $update_query = "UPDATE users SET session_code = '$session_code' WHERE user_id = $user_id";
            $update_result = mysqli_query($connection, $update_query);

            if ($update_result) {
                // Set cookies for user ID and session code
                setcookie('user_id', $user_id, time() + (86400 * 30), '/'); // 30 days expiration
                setcookie('session_code', $session_code, time() + (86400 * 30), '/'); // 30 days expiration

                // Redirect to index.php
                header("Location: index.php");
                exit;
            } else {
                $error_message = "Error updating session code: " . mysqli_error($connection);
            }
        } else {
            $error_message = "Incorrect password. Please try again.";
        }
    } else {
        $error_message = "Invalid username. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
		<?php require_once('menu.php'); ?>
		<div class="container">
				<h2>Login</h2>
				<?php echo messages_to_show(); ?>

				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
					<div class="form-group">
						<label for="username">Username:</label>
						<input type="text" id="username" name="username" required>
					</div>
					<div class="form-group">
						<label for="password">Password:</label>
						<input type="password" id="password" name="password" required>
					</div>
					<div class="form-group">
						<input type="submit" value="Login">
					</div>
				</form>
		</div>
</body>
</html>
