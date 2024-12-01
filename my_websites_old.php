<?php
include_once('db.php');
include_once('utils.php');

// Check user authorization
if (!is_user_logged_in()) {
    header('Location: login.php');
    exit();
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
    <h1>My Websites</h1>
    
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
										<form action="logoff.php" method="POST">
												<input type="hidden" name="website_id" value="<?php echo $website['website_id']; ?>">
												<button type="submit">Log Off All</button>
										</form>
										<form action="settings_websites.php" method="GET">
												<input type="hidden" name="website_id" value="<?php echo $website['website_id']; ?>">
												<button type="submit">Settings</button>
										</form>
										<form action="settings_sessions.php" method="GET">
												<input type="hidden" name="website_id" value="<?php echo $website['website_id']; ?>">
												<button type="submit">Sessions</button>
										</form>
								</td>
						</tr>
						<?php endforeach; ?>
				</table>
    </div>
</body>
</html>
