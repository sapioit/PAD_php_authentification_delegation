<?php
include_once('db.php');

// Function to check if a user is logged in
function is_user_logged_in() {
		global $tab_prefix;
    // Check if user_id and session_code exist in cookies
    if (isset($_COOKIE[$tab_prefix.'user_id']) 
    && isset($_COOKIE[$tab_prefix.'session_code'])
		&& !empty($_COOKIE[$tab_prefix.'user_id']) 
		&& !empty($_COOKIE[$tab_prefix.'session_code']) ) {
		// Get user_id and session_code from cookies
			$user_id = $_COOKIE[$tab_prefix.'user_id'] ?? '';
			$session_code = $_COOKIE[$tab_prefix.'session_code'] ?? '';
        //$user_id = $_COOKIE['user_id'];
        //$session_code = $_COOKIE['session_code'];

        // Retrieve user data from the database
        global $connection;
        $query = "SELECT * FROM ".$tab_prefix."users WHERE user_id = '$user_id' AND session_code = '$session_code'";
        $result = mysqli_query($connection, $query);

        // Return true if user is authorized, false otherwise
        return (mysqli_num_rows($result) === 1);
    }
}

// Check user authorization
if (!is_user_logged_in()) {
    header('Location: login.php');
    exit();
}

// Delete task
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $task_id = $_GET['delete']; // Use the value from $_GET directly
    $user_id = $_COOKIE[$tab_prefix.'user_id'] ?? '';

    // Delete the task for the specific user_id
    $query = "DELETE FROM ".$tab_prefix."todolist WHERE task_id = $task_id AND user_id = $user_id";
    mysqli_query($connection, $query);

    // Redirect back to the todolist page
    header('Location: index.php');
    exit();
}


// Add task
if (isset($_POST['submit'])) {
    $task = $_POST['task']; // Use the value from $_POST directly

    // Insert the task into the test_todolist table
    $query = "INSERT INTO ".$tab_prefix."todolist (user_id, task) VALUES ('{$_COOKIE['user_id']}', '$task')";
    mysqli_query($connection, $query);

    // Redirect back to the todolist page
    header('Location: index.php');
    exit();
}

// Fetch the user's tasks
$query = "SELECT * FROM ".$tab_prefix."todolist WHERE user_id = '{$_COOKIE['user_id']}'";
$result = mysqli_query($connection, $query);

// Check if tasks exist
if (!$result || mysqli_num_rows($result) === 0) {
    //die("No tasks found.");
}

// Fetch all tasks
$tasks = array();
while ($row = mysqli_fetch_assoc($result)) {
    $tasks[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Todo List</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
    <h1>Todo List</h1>
		<div class="wide">
			<ul>
					<?php foreach ($tasks as $task) : ?>
					<li><?php echo $task['task']; ?>
							<a href="index.php?delete=<?php echo $task['task_id']; ?>">Delete</a>
					</li>
					<?php endforeach; ?>
			</ul>
			<form method="POST" action="">
					<input type="text" name="task" required>
					<input type="submit" name="submit" value="Add Task">
			</form>
    </div>
</body>
</html>
