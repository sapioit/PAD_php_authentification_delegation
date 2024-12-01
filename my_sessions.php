<?php
require_once('db.php');
require_once('utils.php');

// Check user authorization
if (!is_user_logged_in()) {
    header('Location: login.php');
    exit();
}

// Fetch the user's sessions with website and user information
$query = "SELECT sessions.session_id, users.username, websites.website_name, sessions.session_code
          FROM sessions
          JOIN users ON sessions.user_id = users.user_id
          JOIN websites ON sessions.website_id = websites.website_id
          WHERE sessions.user_id = '$user_id'";
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
    <title>My Sessions</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php require_once('menu.php'); ?>
    <h1>My Sessions - Where I am logged in.</h1>

    <div class="wide">
        <?php echo messages_to_show(); ?>

        <table class="center-table">
            <tr>
                <th>Session ID</th>
                <th>User Name</th>
                <th>Website Name</th>
                <th>Session Code</th>
            </tr>
            <?php foreach ($sessions as $session) : ?>
                <tr>
                    <td><?php echo $session['session_id']; ?></td>
                    <td><?php echo $session['username']; ?></td>
                    <td><?php echo $session['website_name']; ?></td>
                    <td><?php echo $session['session_code']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
