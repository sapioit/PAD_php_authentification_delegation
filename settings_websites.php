<?php
include_once('db.php');
include_once('utils.php');

// Redirect to login page if the user is not authorized
if (!is_user_logged_in()) {
    header("Location: login.php");
    exit;
}

// Fetch websites of the current user
$query = "SELECT * FROM websites WHERE website_id IN (SELECT website_id FROM SESSIONS WHERE user_id = '$user_id')";
$result = mysqli_query($connection, $query);

// Fetch column names
$columns = mysqli_fetch_fields($result);


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout'])) {
        // Handle Log Out action
        $website_id = $_POST['website_id'];
        $new_session_code = ''; // Set the session code to empty
        $success = update_session_code($website_id, $new_session_code);
        if ($success) {
            $success_message = 'Log Out successful.';
        } else {
            $error_message = 'Failed to Log Out.';
        }
    } elseif (isset($_POST['login'])) {
        // Handle Log In action
        $website_id = $_POST['website_id'];
        $new_session_code = generate_session_code(); // Generate a new session code
        $success = update_session_code($website_id, $new_session_code);
        if ($success) {
            $success_message = 'Log In successful.';
        } else {
            $error_message = 'Failed to Log In.';
        }
    }
}
// Function to update the session code for a given website ID
function update_session_code($website_id, $new_session_code) {
    global $connection;
    
    // Update sessions table with new session code
    $update_query = "UPDATE sessions SET session_code = '$new_session_code' WHERE website_id = '$website_id'";
    $success = mysqli_query($connection, $update_query);
    
    if ($success) {
        // Fetch the session code and user ID from the database
        $fetch_query = "SELECT user_id, session_code FROM sessions WHERE website_id = '$website_id'";
        $result = mysqli_query($connection, $fetch_query);
        
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
                $error_message = "Invalid post URL or redirect URL or website URL.";
                // Handle error message display or action
                return $error_message;
            }
            
            if (!file_exists($post_url)) {
                $error_message = "Error: $post_url file does not exist.";
                // Handle error message display or action
                return $error_message;
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
            
            if (!str_starts_with($post_url, 'http://') && !str_starts_with($post_url, 'https://')) { 
                $absolute_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
                $post_url = $absolute_url . $post_url;
            }
            
            $context = stream_context_create($options);
            $result = fopen($post_url, 'r');
    
            $redirect_url .= '?user_id=' . urlencode($user_id) . '&session_code=' . urlencode($session_code);
    
            if ($result !== false) {
                // Redirect the user to the website redirect URL
                // header("Location: $redirect_url");
                // exit;
                $success_message = "Data sent to the website successfully.";
                // Handle success message display or action
                return true;
            } else {
                // Error occurred while sending data to the website, show error message or take appropriate action
                $error_message = "Error occurred while sending data to the website.";
                // Handle error message display or action
                return false;
            }
        } else {
            // Session code not found for the website ID, show error message or take appropriate action
            $error_message = "Invalid website ID.";
            // Handle error message display or action
            return false;
        }
    } else {
        // Error occurred while updating the session code, show error message or take appropriate action
        $error_message = "Failed to update the session code.";
        // Handle error message display or action
        return false;
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Settings - Websites</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <?php require_once('menu.php'); ?>
    <h1>Settings - Websites</h1>

    <div class="wide">
        <?php echo messages_to_show(); ?>

        <?php if (is_user_logged_in()) { ?>
            <!-- Websites Table -->
            <table class="center-table">
                <tr>
                    <?php foreach ($columns as $column) {
                        if ($column->name !== 'password') { ?>
                            <th><?= $column->name; ?></th>
                        <?php }
                    } ?>
                    <th>Actions</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <?php foreach ($row as $key => $value) {
                            if ($key !== 'password') { ?>
                                <td><?= $value; ?></td>
                            <?php }
                        } ?>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="website_id" value="<?= $row['website_id']; ?>">
                                <input type="submit" name="logout" value="Log Out">
                                <input type="submit" name="login" value="Log In">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <?php if (isset($redirect_url) && !empty($redirect_url)) { ?>
								<iframe src="<?= $redirect_url; ?>" style="width: 1px; height: 1px; opacity: 0; z-index: -1;"></iframe>
						<?php } ?>
        <?php } else { ?>
            <p>You are not authorized to access this page.</p>
        <?php } ?>
    </div>
</body>

</html>
