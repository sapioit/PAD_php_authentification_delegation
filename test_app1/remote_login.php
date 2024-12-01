<?php
include_once('db.php');
// Retrieve the user_id and session_id from POST or GET
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : (isset($_GET['user_id']) ? $_GET['user_id'] : '');
$session_code = isset($_POST['session_code']) ? $_POST['session_code'] : (isset($_GET['session_code']) ? $_GET['session_code'] : '');

// Set the cookies with the user_id and session_code
setcookie(($tab_prefix.'user_id'), $user_id, time() + (86400 * 30), '/'); // Cookie valid for 30 days
setcookie(($tab_prefix.'session_code'), $session_code, time() + (86400 * 30), '/'); // Cookie valid for 30 days

// Redirect to a success page or perform any additional actions
header('Location: index.php');
exit();