<?php

// Database configuration
$host = 'localhost';  // Replace with your host name
$username = 'root';  // Replace with your database username
$password = '123456';  // Replace with your database password
$database = 'dizertatie';  // Replace with your database name

if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_REQUEST['host'], $_REQUEST['username'], $_REQUEST['password'], $_REQUEST['database'])) {
        $host = $_REQUEST['host'];
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        $database = $_REQUEST['database'];

        // Generate the contents of the db.php file
        $db_content = "<?php\n";
        $db_content .= "// Database configuration\n";
        $db_content .= "\$db_host = '$host';  // Replace with your host name\n";
        $db_content .= "\$db_username = '$username';  // Replace with your database username\n";
        $db_content .= "\$db_password = '$password';  // Replace with your database password\n";
        $db_content .= "\$db_database = '$database';  // Replace with your database name\n\n";
        $db_content .= "// Establish database connection\n";
        $db_content .= "\$connection = mysqli_connect(\$db_host, \$db_username, \$db_password, \$db_database);\n\n";
        $db_content .= "// Deleting the sensitive variables, in case of code injection.\n";
        $db_content .= "unset(\$db_host);\n";
        $db_content .= "unset(\$db_username);\n";
        $db_content .= "unset(\$db_password);\n";
        $db_content .= "unset(\$db_database);\n\n";
        $db_content .= "// Check if the connection was successful\n";
        $db_content .= "if (!\$connection) {\n";
        $db_content .= "    die('Database connection failed: ' . mysqli_connect_error());\n";
        $db_content .= "}\n\n";
        $db_content .= "// Set the character set to UTF-8 (optional, adjust as needed)\n";
        $db_content .= "mysqli_set_charset(\$connection, 'utf8');\n\n";

        // Write or overwrite the db.php file
        $db_file = 'db.php';
        if (file_put_contents($db_file, $db_content) !== false) {
            // Execute the install.sql file
            $install_file = 'install.sql';

            if (file_exists($install_file)) {
                $sql_content = file_get_contents($install_file);

                try {
                    $connection = mysqli_connect($host, $username, $password, $database);
                  if ($connection !== false) {
										// Execute the entire SQL content
										if (mysqli_multi_query($connection, $sql_content)) {
												// Loop through the result sets (if any)
												do {
														// Consume each result set
														if ($result = mysqli_store_result($connection)) {
																mysqli_free_result($result);
														}
												} while (mysqli_next_result($connection));
											/*
											$queries = explode(';', $sql_content);
											foreach ($queries as $query) {
                        $query = trim($query);
                        if (!empty($query)) {
                            mysqli_query($connection, $query);
                        }
                      */
                      mysqli_close($connection);
                      
                      $error_message = "Installation is successful";

                      // Redirect to index.php if installation is successful
                      header("Location: index.php");
                      exit;
                    }
                  } else {
                      $error_message = 'Database connection failed: ' . mysqli_connect_error();
                  }
                } catch (mysqli_sql_exception $e) {
                  $error_message = 'Database connection error: ' . $e->getMessage();
                }
            } else {
                $error_message = 'Install file not found.';
            }
        } else {
            $error_message = 'Failed to write db.php file.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Installation</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
				<?php if (isset($success_message)) : ?>
						<p class="success"><?php echo $success_message; ?></p>
				<?php endif; ?>		
				<?php if (isset($error_message)) : ?>
						<p class="error"><?php echo $error_message; ?></p>
				<?php endif; ?>

        <h2>Installation</h2>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
						<div class="form-group">
								<label for="host">Host:</label>
								<input type="text" id="host" name="host" value="<?php echo $host; ?>" required>
						</div>
						<div class="form-group">
								<label for="username">Username:</label>
								<input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
						</div>
						<div class="form-group">
								<label for="password">Password:</label>
								<input type="password" id="password" name="password" value="<?php echo $password; ?>" required>
						</div>
						<div class="form-group">
								<label for="database">Database:</label>
								<input type="text" id="database" name="database" value="<?php echo $database; ?>" required>
						</div>
						<div class="form-group">
								<input type="submit" value="Install">
						</div>
				</form>
    </div>
</body>
</html>
