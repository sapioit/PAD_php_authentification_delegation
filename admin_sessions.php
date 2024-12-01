<?php
include_once('db.php');
include_once('utils.php');

// Fetch user_id and session_id from cookies
$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';
$session_id = isset($_COOKIE['session_id']) ? $_COOKIE['session_id'] : '';

// Redirect to login page if the user is not authorized
if (!is_user_admin()) {
    header("Location: login.php");
    exit;
}

// Fetch all data from the sessions table
$query = "SELECT * FROM sessions";
$result = mysqli_query($connection, $query);

// Fetch column names
$columns = mysqli_fetch_fields($result);

// Fetch filtered and sorted rows based on form submissions (if any)
$filter_column = isset($_GET['filter_column']) ? $_GET['filter_column'] : '';
$filter_value = isset($_GET['filter_value']) ? $_GET['filter_value'] : '';
$orderBy = isset($_GET['order_by']) ? $_GET['order_by'] : '';

$filteredRows = fetch_filtered_rows($connection, $filter_column, $filter_value, $orderBy);

// Function to fetch filtered and sorted rows based on form submissions
function fetch_filtered_rows($connection, $filter_column, $filter_value, $order_by)
{
    $query = "SELECT * FROM sessions";

    // Add filters if provided
    if (!empty($filter_column) && !empty($filter_value)) {
        $query .= " WHERE $filter_column = '$filter_value'";
    }

    // Add order by clause if provided
    if (!empty($order_by)) {
        $query .= " ORDER BY $order_by";
    }

    $result = mysqli_query($connection, $query);

    $rows = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Sessions</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
		<?php require_once('menu.php'); ?>
    <h1>Admin Sessions</h1>
		
		<div class="wide">
				<?php echo messages_to_show(); ?>

				<?php if (is_user_admin()) { ?>
						<!-- Filter Form -->
						<form  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
								<div class="filter-form">
										<label for="filter_column">Filter Column:</label>
										<select id="filter_column" name="filter_column">
												<option value="">None</option>
												<?php foreach ($columns as $column) { ?>
														<option value="<?= $column->name; ?>"><?= $column->name; ?></option>
												<?php } ?>
										</select>
										<label for="filter_value">Filter Value:</label>
										<input type="text" id="filter_value" name="filter_value">
										<input type="submit" value="Apply Filter">
								</div>
						</form>

						<!-- Table -->
						<table class="center-table">
								<tr>
										<?php foreach ($columns as $column) { ?>
												<th><?= $column->name; ?></th>
										<?php } ?>
								</tr>
								<?php foreach ($filteredRows as $row) { ?>
										<tr>
												<?php foreach ($row as $key => $value) { ?>
														<td><?= $value; ?></td>
												<?php } ?>
										</tr>
								<?php } ?>
						</table>
				<?php } else { ?>
						<p>You are not authorized to access this page.</p>
				<?php } ?>
    </div>
</body>

</html>
