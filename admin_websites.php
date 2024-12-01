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

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_selected'])) {
        // Update selected rows
        $selected_rows = isset($_POST['selected_rows']) ? $_POST['selected_rows'] : array();
        if (!empty($selected_rows)) {
            foreach ($selected_rows as $row_id) {
                $query = "UPDATE websites SET 
                    website_id = '{$selected_rows[$row_id]['website_id']}', 
                    user_id = '{$selected_rows[$row_id]['user_id']}', 
                    website_name = '{$selected_rows[$row_id]['website_name']}', 
                    website_url = '{$selected_rows[$row_id]['website_url']}', 
                    post_url = '{$selected_rows[$row_id]['post_url']}', 
                    redirect_url = '{$selected_rows[$row_id]['redirect_url']}' 
                    WHERE website_id = '{$selected_rows[$row_id]['website_id']}'";
                mysqli_query($connection, $query);
            }
        }
    } elseif (isset($_POST['delete_selected'])) {
        // Delete selected rows
        $selected_rows = isset($_POST['selected_rows']) ? $_POST['selected_rows'] : array();
        if (!empty($selected_rows)) {
            foreach ($selected_rows as $row_id) {
                $query = "DELETE FROM websites WHERE website_id = '$row_id'";
                mysqli_query($connection, $query);
            }
        }
    }
}

// Fetch all data from the websites table
$query = "SELECT * FROM websites";
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
    $query = "SELECT * FROM websites";

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
    <title>Admin Websites</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
		<?php require_once('menu.php'); ?>		
    <h1>Admin Websites</h1>

		<div class="wide">
				<?php echo messages_to_show(); ?>

				<?php if (is_user_admin()) { ?>
						<!-- Filter Form -->
						<form action="" method="get">
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
						<form action="" method="post">
								<table class="data-table">
										<tr>
												<?php foreach ($columns as $column) { ?>
														<th><?= $column->name; ?></th>
												<?php } ?>
												<th>Update</th>
												<th>Delete</th>
												<th>Select</th>
										</tr>
										<?php foreach ($filteredRows as $row) { ?>
												<tr>
														<?php foreach ($row as $key => $value) { ?>
																<td>
																		<?php if ($key === 'website_id') { ?>
																				<input type="hidden" name="selected_rows[<?= $value; ?>][<?= $key; ?>]" value="<?= $value; ?>">
																				<?= $value; ?>
																		<?php } else { ?>
																				<input type="text" name="selected_rows[<?= $row['website_id']; ?>][<?= $key; ?>]" value="<?= $value; ?>">
																		<?php } ?>
																</td>
														<?php } ?>
														<td><button type="submit" name="update_selected">Update</button></td>
														<td><button type="submit" name="delete_selected">Delete</button></td>
														<td><input type="checkbox" name="selected_rows[]" value="<?= $row['website_id']; ?>"></td>
												</tr>
										<?php } ?>
								</table>

								<!-- Update/Delete Selected Buttons -->
								<div class="action-buttons">
										<button type="submit" name="update_selected">Update Selected</button>
										<button type="submit" name="delete_selected">Delete Selected</button>
								</div>
						</form>
				<?php } else { ?>
						<p>You are not authorized to access this page.</p>
				<?php } ?>
    </div>
</body>

</html>
