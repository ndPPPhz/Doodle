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
		$insertedUsername = $_POST[USERNAME];
		$insertedPassword = $_POST[PASSWORD];
		if (isset($insertedUsername) && isset($insertedPassword)) {
			$doodle_facade->log($insertedUsername, $insertedPassword);
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