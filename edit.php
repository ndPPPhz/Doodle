<?php
session_start();
require_once 'include/facade.php';
require_once 'include/constants.php';
require_once 'include/helper.php';
$doodle_facade = new DoodleFacade('doodle.json');
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="canonical" href="https://getbootstrap.com/docs/4.4/examples/sign-in/"><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link href="https://getbootstrap.com/docs/4.4/examples/sign-in/signin.css" rel="stylesheet">

<title> Edit mode</title>
</head>

<body class="text-center">
<?php
	if (!isset($_SESSION['currentUser'])) {
?>
<label>You are not logged in</label></br>
<input type="submit" value="Back to login" onclick="backToLogin()">
<script type="text/javascript"> 
function backToLogin() {
	document.location.href = '.'
}
</script>

<?php } else { ?>

<div class="container">
<?php
	echo "<h1>Hello ".$_SESSION[LOGGED_USER]."</h1>";
	// If logged then load and display the results
	if (isset($_POST['dates']) && !empty(array_filter($_POST['dates']))) {
		$doodle_facade->addEntries($_POST['dates'], $_POST['from'], $_POST['to']);
	}

	echo "<h3>Your availability:</h3>";
	if (!empty($userEntries = $doodle_facade->getUsersEntries()[$_SESSION[LOGGED_USER]])) {
		echo "<table class='table'><tr><td>Date</td><td>From</td><td>To</td></tr>";
		foreach($userEntries as $key => $item) {
	  		echo "<tr>";
	  		foreach ($item as $value) {
	  			echo "<td>".$value."</td>";
	  		}
	  		echo "</tr>";
	    }
		echo "</table>";
	} else {
		echo 'No results';
	}

	$commonRange = $doodle_facade->solve();

	if (!empty($commonRange)) {
		echo "<h3>Common slot time</h3>";
		echo "<table class='table'>";
		foreach ($commonRange as $dataRange) {
			$from = $dataRange['from']->format('d-m-Y H:i');
			$to = $dataRange['to']->format('d-m-Y H:i');
			echo "<tr><td>$from</td><td>$to</td></tr>";
		}
		echo "</table>";
	}
?>

<h3>Insert new time availability:</h3>

<div id="field-extra">
<div class="row">
	<div class="col">
		<input class="form-control" name="dates[]" value="21-03-2020">
	</div>

	<div class="col">
		<select class="form-control" name="from[]"><?= get_times('15:00') ?></select>
	</div>

	<div class="col">
		<select class="form-control" name="to[]"><?= get_times('17:00') ?></select>
	</div>
</div>
</div>

<div class="row" style="margin-top: 12px">
	<div class="col">
		<input type="button" id="add-field" class="btn btn-primary btn-block" value="Add field"/>
	</div>
<script type="application/javascript">
	document.getElementById('add-field').addEventListener("click", function(e) {
	 	e.preventDefault();
	  		var div = document.createElement('div');
	  		div.className = 'row';

	  		var dateDiv = document.createElement('div');
	  		dateDiv.className = 'col';
		    var date = document.createElement('input');
		    date.className = 'form-control';
		    date.name = `dates[]`;
		   	date.value = "2020-03-21";
		    date.type = 'date';
		    dateDiv.appendChild(date);

		    var fromDiv = document.createElement('div');
		    fromDiv.className = 'col';
		    var from = document.createElement('select');
		    from.className = 'form-control';
		    from.name = `from[]`;
		   	from.value = "15:44";
		    from.type = 'time';
		    from.innerHTML = '<?php echo get_times('15:00') ?>';
		    fromDiv.appendChild(from);

		    var toDiv = document.createElement('div');
		    toDiv.className = 'col';
		    var to = document.createElement('select');
		    to.className = 'form-control';
		    to.name = `to[]`;
		   	to.value = "15:44";
		    to.type = 'time';
		    to.innerHTML = '<?php echo get_times('17:00') ?>';
		    toDiv.appendChild(to);

		    div.appendChild(dateDiv);
		    div.appendChild(fromDiv);
		    div.appendChild(toDiv);

		document.getElementById('field-extra').appendChild(div);
	})
</script>

	<div class="col">
		<form action="" method="POST">
			<input class="btn btn-success btn-block" type="submit">
		</form>
	</div>
	<div class="col">
		<form action="" method="GET">
			<input type="hidden" name="logout" value="y" />
			<input class="btn btn-danger btn-block" type="submit" value="Logout">
		</form>

	</div>
</div>
</div>
<?php
		}
		if ($_GET['logout']) {
			unset($_SESSION['currentUser']);
			session_destroy();
			echo '<script>document.location = document.location.toString().replace(/\?.+$/, "");</script>';
			exit;
		}
?>
</body>
</html>
