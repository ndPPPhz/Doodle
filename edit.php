<?php
	session_start();
	require_once 'include/dateManager.php';
	require_once 'include/config.php';
	require_once 'include/constants.php';
	$configManager = new ConfigManager('doodle.json');
?>

<!DOCTYPE html>
<html>
<head>
	<title> Edit mode</title>
</head>
<body>
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
<?php
		} else {
			// If logged then load and display the results
			if (isset($_POST['dates']) && !empty(array_filter($_POST['dates']))) {
				$dateManager = new DateManager($_POST['dates'], $_POST['from'], $_POST['to'], $configManager);
				$dateManager->storeNewDates();
			}

			if (!empty($userEntries = $configManager->getData()[$_SESSION[LOGGED_USER]])) {
				echo "<table><tr><td>Date</td><td>From</td><td>To</td></tr>";
				foreach($userEntries as $key => $item) {
		      		echo "<tr>";
		      		foreach ($item as $value) {
		      			echo "<td>".$value."</td>";
		      		}
		      		echo "</tr>";
			    }
				echo "</table>";
			}
	?>
	
	<form action="" method="POST">
	<div id="field-container">

	<input name="dates[]" type="date" value="2020-03-21"><input name="from[]" type="time" value="15:44"><input name="to[]" type="time" value="15:44">

	<input type="button" id="add-field" value="Add field"/>
	<div id="field-extra"></div>

	<script type="application/javascript">
		document.getElementById('add-field').addEventListener("click", function(e) {
		 	e.preventDefault();
		  		var div = document.createElement('div');

			    var date = document.createElement('input');
			    date.name = `dates[]`;
			   	date.value = "2020-03-21";
			    date.type = 'date';
			    div.appendChild(date);

			    var from = document.createElement('input');
			    from.name = `from[]`;
			   	from.value = "15:44";
			    from.type = 'time';
			    div.appendChild(from);

			    var to = document.createElement('input');
			    to.name = `to[]`;
			   	to.value = "15:44";
			    to.type = 'time';
			    div.appendChild(to);

			document.getElementById('field-extra').appendChild(div);
		})
	</script>
	</div>
	<input type="submit">
	</form>

	<form action="" method="GET">
		<input type="hidden" name="logout" value="y" />
		<input type="submit" value="Logout">
	</form>
	<?php
		}
		if ($_GET['logout']) {
			unset($_SESSION['currentUser']);
			echo '<script>document.location = document.location.toString().replace(/\?.+$/, "");</script>';
			exit;
		}
	?>
</body>
</html>