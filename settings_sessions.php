<?php
include_once('db.php');
include_once('utils.php');

// Check user authorization
if (!is_user_logged_in()) {
    header('Location: login.php');
    exit();
}

$sessions = array();

// Fetch user's websites
$query = "SELECT * FROM websites WHERE user_id = '$user_id'";
$result = mysqli_query($connection, $query);

// Check if websites exist
if (!$result || mysqli_num_rows($result) === 0) {
    $error_message = "No websites found for the user.";
} else {
		// Fetch website IDs
		$website_ids = array();
		while ($row = mysqli_fetch_assoc($result)) {
				$website_ids[] = $row['website_id'];
		}

		// Fetch sessions for the user's websites
		$website_ids_str = implode(',', $website_ids);
		$query = "SELECT * FROM sessions WHERE website_id IN ($website_ids_str)";
		$result = mysqli_query($connection, $query);

		// Check if sessions exist
		if (!$result || mysqli_num_rows($result) === 0) {
				$error_message = "No sessions found for the user's websites.";
		}

		// Fetch all sessions
		$sessions = array();
		while ($row = mysqli_fetch_assoc($result)) {
				$sessions[] = $row;
		}
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Settings - Sessions</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
		<?php require_once('menu.php'); ?>
    <h1>Settings - Sessions</h1>
    
		<div class="wide">
				<?php echo messages_to_show(); ?>

				<table class="center-table">
						<tr>
								<th>Session ID</th>
								<th>User ID</th>
								<th>Website ID</th>
								<th>Session Code</th>
						</tr>
						<?php foreach ($sessions as $session) : ?>
						<tr>
								<td><?php echo $session['session_id']; ?></td>
								<td><?php echo $session['user_id']; ?></td>
								<td><?php echo $session['website_id']; ?></td>
								<td><?php echo $session['session_code']; ?></td>
						</tr>
						<?php endforeach; ?>
				</table>
    </div>
</body>
</html>
