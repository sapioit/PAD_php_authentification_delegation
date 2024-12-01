<?php
include_once('db.php');
include_once('utils.php');

// Check user authorization
if (!is_user_logged_in()) {
    header('Location: login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logoff'])) {
				// Delete the session records for the specified website_id
				$delete_query = "DELETE FROM sessions WHERE website_id = '".$_POST['website_id']."';";
				$delete_result = mysqli_query($connection, $delete_query);

				if ($delete_result) {										
						// Display success message or perform additional actions
						$success_message = "Session code reset for website ID: "
.$_POST['website_id'];
				} else {
						$error_message = "Failed to delete the sessions for the website.";
				}
    } elseif (isset($_POST['settings'])) {
				// Redirect or perform the desired action
				header('Location: settings_websites.php?website_id=' 
							. $website['website_id'] );
				exit();

    } elseif (isset($_POST['sessions'])) {
				// Redirect or perform the desired action
				header('Location: settings_sessions.php?website_id=' 
							. $website['website_id'] );
				exit();

    } elseif (isset($_POST['delete'])) {
        $website_id = $_POST['website_id'];

        // Delete the session records for the specified website_id
        $delete_sessions_query = "DELETE FROM sessions WHERE website_id = '$website_id'";
        $delete_sessions_result = mysqli_query($connection, $delete_sessions_query);

        // Delete the website record
        $delete_website_query = "DELETE FROM websites WHERE website_id = '$website_id'";
        $delete_website_result = mysqli_query($connection, $delete_website_query);

        if ($delete_sessions_result && $delete_website_result) {
            // Display success message or perform additional actions
            $success_message = "Website and associated sessions deleted successfully.";
        } else {
            $error_message = "Failed to delete the website and associated sessions.";
        }
    }
}

// Fetch the user's websites
$query = "SELECT * FROM websites WHERE user_id = '$user_id'";
$result = mysqli_query($connection, $query);

// Check if websites exist
if (!$result || mysqli_num_rows($result) === 0) {
    $error_message = "No websites found for the user.";
}

// Fetch all websites
$websites = array();
while ($row = mysqli_fetch_assoc($result)) {
    $websites[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Websites</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php require_once('menu.php'); ?>
    <h1>My Websites - Websites I added.</h1>

    <div class="wide">
        <?php echo messages_to_show(); ?>

        <table class="center-table">
            <tr>
                <th>Website ID</th>
                <th>User ID</th>
                <th>Website Name</th>
                <th>Website URL</th>
                <th>Post URL</th>
                <th>Redirect URL</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($websites as $website) : ?>
            <tr>
                <td><?php echo $website['website_id']; ?></td>
                <td><?php echo $website['user_id']; ?></td>
                <td><?php echo $website['website_name']; ?></td>
                <td><a href="<?php echo $website['website_url']; ?>"><?php echo $website['website_url']; ?></a></td>
                <td><a href="<?php echo $website['post_url']; ?>"><?php echo $website['post_url']; ?></a></td>
                <td><a href="<?php echo $website['redirect_url']; ?>"><?php echo $website['redirect_url']; ?></a></td>
                <td>
                    <form action="" method="POST">
                        <input type="hidden" name="website_id" value="<?php echo $website['website_id']; ?>">
                        <div class="action-menu">
                            <input type="submit" name="logoff" value="&#x1F6AA;" title="Log Off All">
                            <input type="submit" name="settings" value="&#x2699;" title="Settings">
                            <input type="submit" name="sessions" value="&#x1F4C3;" title="Sessions">
                            <input type="submit" name="delete" value="&#x1F5D1;" title="Delete">
                        </div>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
