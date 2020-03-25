<?php
session_start();
require_once 'include/constants.php';
require_once 'include/facade.php';
$doodle_facade = new DoodleFacade('doodle.json');
?>

<html>
<head>
<title> Doodle </title>
<meta name="viewport" content="width=device-width">
</head>
<body>

<?php
	if (!isset($_SESSION[LOGGED_USER])) {
		$username = $_POST[USERNAME];
		$password = $_POST[PASSWORD];
		// Check if the user has inserted username and password
		if (isset($username) && isset($password)) {
			// Log using the inserted username and password
			if ($doodle_facade->log($username, $password)) {
				$_SESSION[LOGGED_USER] = $username;
			}
		}
	}

	// If the login went through show the edit area
	if (isset($_SESSION[LOGGED_USER])) {
		echo '<script>document.location.href = "./edit.php"</script>';
		exit;
	} else {
	// Otherwise show again the login 
?>

<form action="" method="POST">
<label>User: </label> <input type="text" name=<?=USERNAME?>></br>
<label>Password: </label><input type="text" name=<?=PASSWORD?>></br>
<input type="submit" value="Login">
</form>

<?php } ?>

</body>
</html>