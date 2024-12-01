<?php
include_once('db.php');
include_once('utils.php');

// Check user authorization
if (is_user_logged_in()) {
    header('Location: my_sessions.php');
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];

    // Check if the username already exists in the database
    $query = "SELECT user_id FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) {
        $error_message = "Username already exists. Please choose a different username.";
    } else {
        // Hash the password
        $hashed_password = $password;

        // Generate a session code
        $session_code = generate_session_code();

        // Insert user data into the database
        $query = "INSERT INTO users (username, password, email, session_code) VALUES ('$username', '$hashed_password', '$email', '$session_code')";
        $result = mysqli_query($connection, $query);

        if ($result) {
            // Redirect to the login page
            header("Location: login.php");
            exit;
        } else {
            $error_message = "Error: " . mysqli_error($connection);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <?php require_once('menu.php'); ?>
		<div class="container">
				<h2>Sign Up</h2>
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
						<label for="confirm_password">Confirm Password:</label>
						<input type="password" id="confirm_password" name="confirm_password" required>
					<div class="form-group">
						<label for="email">Email:</label>
						<input type="email" id="email" name="email">
					</div>
					<div class="form-group">
						<input type="submit" value="Sign Up">
					</div>
				</form>
		</div>
</body>
</html>
