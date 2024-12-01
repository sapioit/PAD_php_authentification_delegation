<?php
require_once('db.php');
require_once('utils.php');
?>

<menu>
<ul>
	<li><a href="index.php">Home</a></li>
	<?php if (is_user_logged_in()) : ?>
	<li>
		<a href="#">My, <?php echo get_username($user_id); ?></a>
			<ul>
				<li><a href="my_sessions.php">Sessions</a></li>
				<li><a href="my_users.php">Users</a></li>
				<li><a href="my_websites.php">Websites</a></li>
			</ul>
	</li>
	<li>
		<a href="#">Settings</a>
			<ul>
				<li><a href="settings_sessions.php">Sessions</a></li>
				<li><a href="settings_user.php">User</a></li>
				<li><a href="settings_websites.php">Websites</a></li>
			</ul>
	</li>
	<?php else : ?>
	<li><a href="login.php">Login</a></li>
	<li><a href="register.php">Register</a></li>
	<?php endif; ?>
	<?php if (is_user_admin()) : ?>
	<li>
		<a href="#">Admin</a>
			<ul>
				<li><a href="admin_sessions.php">Sessions</a></li>
				<li><a href="admin_users.php">Users</a></li>
				<li><a href="admin_websites.php">Websites</a></li>
			</ul>
	</li>
	<?php endif; ?>
	<?php if (is_user_logged_in()) : ?>
	<li><a href="logout.php">Logout</a></li>
	<?php endif; ?>
</ul>
</menu>
