<?php
include_once('db.php');
include_once('utils.php');

// Redirect to login page if the user is not authorized
if (!is_user_logged_in()) {
    header("Location: login.php");
    exit;
}

// Fetch websites of the current user
$query = "SELECT * FROM websites WHERE user_id = '$user_id'";
$result = mysqli_query($connection, $query);

// Fetch column names
$columns = mysqli_fetch_fields($result);

// Update website password if provided
if (isset($_POST['update_password'])) {
    $website_id = $_POST['website_id'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $query = "UPDATE websites SET password = '$password' WHERE website_id = '$website_id'";
        mysqli_query($connection, $query);
				$error_message = sql_errors($error_message);
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
									<th>Update Password</th>
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
															<input type="password" name="password" placeholder="New Password">
															<input type="submit" name="update_password" value="Update">
													</form>
											</td>
									</tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>You are not authorized to access this page.</p>
    <?php } ?>
    </div>
</body>

</html>
