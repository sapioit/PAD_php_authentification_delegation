<?php
include_once('db.php');
include_once('utils.php');

// Check user authorization
if (!is_user_logged_in()) {
    header('Location: login.php');
    exit();
}

// Fetch the user's sessions with website, user, and user_id information
$query = "SELECT sessions.session_id, websites.website_name, users.username, sessions.user_id 
          FROM sessions
          JOIN websites ON sessions.website_id = websites.website_id
          JOIN users ON sessions.user_id = users.user_id
          WHERE websites.user_id = '$user_id'";
$result = mysqli_query($connection, $query);

// Check if sessions exist
if (!$result || mysqli_num_rows($result) === 0) {
    $error_message = "No user sessions found.";
}

// Fetch all sessions
$sessions = array();
while ($row = mysqli_fetch_assoc($result)) {
    $sessions[] = $row;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>My Users</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php require_once('menu.php'); ?>
    <h1>My Users - Users logged in on my websites.</h1>

    <div class="wide">
        <?php echo messages_to_show(); ?>

        <table class="center-table">
            <tr>
                <th>Session ID</th>
                <th>Website Name</th>
                <th>Username</th>
                <th>User ID</th>
            </tr>
            <?php foreach ($sessions as $session) : ?>
                <tr>
                    <td><?php echo $session['session_id']; ?></td>
                    <td><?php echo $session['website_name']; ?></td>
                    <td><?php echo $session['username']; ?></td>
                    <td><?php echo $session['user_id']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
