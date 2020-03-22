<?php
session_start();
require_once 'include/constants.php';
require_once 'include/config.php';
require_once 'include/login.php';

$configManager = new ConfigManager('doodle.json');
$logger = new Logger($configManager->config);
?>

<html>
<head>
<title> Doodle </title>
<meta name="viewport" content="width=device-width">
</head>
<body>

<?php
	if (!isset($_SESSION[LOGGED_USER])) {
		$insertedUsername = $_POST[USERNAME];
		$insertedPassword = $_POST[PASSWORD];
		if (isset($insertedUsername) && isset($insertedPassword)) {
			$logger->log($insertedUsername, $insertedPassword);
		}
	}
	if (isset($_SESSION[LOGGED_USER])) {
		echo '<script>document.location.href = "./edit.php"</script>';
		exit;
	} else {
?>
	<form action="" method="POST">
	<label>User: </label> <input type="text" name=<?=USERNAME?>></br>
	<label>Password: </label><input type="text" name=<?=PASSWORD?>></br>
	<input type="submit" value="Login">
	</form>

<?php } ?>

</body>
</html>