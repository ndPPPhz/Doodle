<?php
session_start();
require_once 'include/constants.php';
require_once 'include/facade.php';
$doodle_facade = new DoodleFacade('doodle.json');
?>

<html lang="en">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="canonical" href="https://getbootstrap.com/docs/4.4/examples/sign-in/"><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link href="https://getbootstrap.com/docs/4.4/examples/sign-in/signin.css" rel="stylesheet">

<title> Doodle </title>
</head>
<body class="text-center">

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
<form class="form-signin" action="" method="POST">
<img class="mb-4" src="./doodle.svg" alt="" width="72" height="72">
<h1 class="h3 mb-3 font-weight-normal"> Personal Doodle </h1>
<input type="text" class="form-control" placeholder="Username" name=<?=USERNAME?>></br>
<input type="text" class="form-control" placeholder="Password" name=<?=PASSWORD?>></br>
<input class="btn btn-lg btn-primary btn-block" type="submit" value="Login">
</form>

<?php } ?>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>