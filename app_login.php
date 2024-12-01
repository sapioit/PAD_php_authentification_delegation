<?php
include_once('db.php');
include_once('utils.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if website ID is provided via POST
    if (!empty($_POST['website_id'])) {
        $website_id = $_POST['website_id'];
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if website ID is provided via GET
    if (!empty($_GET['website_id'])) {
        $website_id = $_GET['website_id'];
    }
} else {
    if (!empty($_REQUEST['website_id'])) {
        $website_id = $_REQUEST['website_id'];
    }
}

if (!isset($website_id)) {
    // Website ID not provided, show error message or take appropriate action
    echo "Website ID is required.";
    exit;
}

// Check if session code exists in sessions table for the given website ID
// Update sessions.session_code with a new session code
$new_session_code = generate_session_code();
$update_query = "UPDATE sessions SET session_code = '$new_session_code' WHERE website_id = '$website_id'";
$success = mysqli_query($connection, $update_query);

if (!$success) {
    // Failed to update the session code, handle the error or take appropriate action
    echo "Failed to update the session code.";
    exit;
}
// The session code has been updated successfully
// Continue with the remaining code

// Check if session code exists in sessions table for the given website ID
$query = "SELECT * FROM sessions WHERE website_id = '$website_id'";
$result = mysqli_query($connection, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $user_id = $row['user_id'];
    $session_code = $row['session_code'];

    // Send the user ID and session code to the post URL
    $post_url = get_post_url($website_id);
    $redirect_url = get_redirect_url($website_id);
    $website_url = get_website_url($website_id);

    if (empty($post_url) || empty($redirect_url) || empty($website_url)) {
        // Post URL or redirect URL or website URL is empty, show error message or take appropriate action
        echo "Invalid post URL or redirect URL or website URL.";
        exit;
    }
    
		if (!file_exists($post_url)) {
				echo "Error: $post_url file does not exist.";
				exit;
		}
    
    // Add hidden input fields for user_id and session_code
		$hidden_inputs = '<input type="hidden" name="user_id" value="' . $user_id . '">';
		$hidden_inputs .= '<input type="hidden" name="session_code" value="' . $session_code . '">';

		$post_url .= '?user_id=' . urlencode($user_id) . '&session_code=' . urlencode($session_code);

		$options = array(
				'http' => array(
						'header' => "Content-type: application/x-www-form-urlencoded\r\n",
						'method' => 'GET',
				)
		);
		if ( !str_starts_with($post_url, 'http://') && !str_starts_with($post_url, 'https://') ) { 
				$absolute_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
				$post_url = $absolute_url . $post_url;
		}
		$context = stream_context_create($options);
		$result = fopen($post_url, 'r');

    
		$redirect_url .= '?user_id=' . urlencode($user_id) . '&session_code=' . urlencode($session_code);

    if ($result !== false) {
        // Redirect the user to the website redirect URL
        header("Location: $redirect_url");
        exit;
    } else {
        // Error occurred while sending data to the website, show error message or take appropriate action
        echo "Error occurred while sending data to the website.";
        exit;
    }
} else {
    // Session code not found for the website ID, show error message or take appropriate action
    echo "Invalid website ID.";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>App Login Page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
				<?php echo messages_to_show(); ?>

        <h2>Login</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="input-group">
                <label for="website_id">Website ID:</label>
                <input type="text" id="website_id" name="website_id" required>
            </div>
            <?php echo $hidden_inputs; ?> <!-- Add the hidden input fields here -->
            <div class="input-group">
                <button type="submit" class="btn">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
