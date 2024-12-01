<?php
// Send the GET request to app_login.php
$url = '../app_login.php?website_id=2';
header("Location: $url");
file_get_contents($url, false, $context);
exit();
?>
