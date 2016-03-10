<html>
<head>
<title>BorrelBot - Settings</title>
<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="wrapper">
<div class="container">
<h1>BorrelBot - Settings</h1>
<ul class="grid-nav">
	<li><a href="order.php">Order</a></li>
	<li><a href="#">Settings</a></li>
	<li><a href="#">Edit Recipes</a></li>
</ul>
<br>
<div id="three-columns" class="grid-container" style="display:block;">
<center><ul class="rig columns-3">	
	<li display: block;>
	<h2>Currently installed</h2><br>
	<?php
		$dbhost = 'localhost';
		$dbuser = 'root';
		$dbpass = 'pass123';

		$conn = mysql_connect($dbhost, $dbuser, $dbpass)
		or die('Error connecting to mysql');

		$dbname = 'borrelbot';
		mysql_select_db($dbname);

		if ($_POST['send'] == "true") {
			$query = "DELETE FROM available;";
			mysql_query($query);

			$query = "INSERT INTO available VALUES('".
				mysql_real_escape_string($_POST['slot_1'])."', '".
				mysql_real_escape_string($_POST['slot_2'])."', '".
				mysql_real_escape_string($_POST['slot_3'])."', '".
				mysql_real_escape_string($_POST['slot_4'])."');";
			mysql_query($query);
			echo"<font color='green'>Saved</font><br><br>";
		}
		
		$res = mysql_query("SELECT * FROM available");
		while ($row = mysql_fetch_array($res, MYSQL_BOTH)) {
			echo "	
				<form action='settings.php' method='post'>
					<input type='hidden' name='send' value='true'>Slot 1: 
					<input type='text' name='slot_1' value='" . $row['slot_1'] . "'><br>
					Slot 2: 
					<input type='text' name='slot_2' value='" . $row['slot_2'] . "'><br>
					Slot 3: 
					<input type='text' name='slot_3' value='" . $row['slot_3'] . "'><br>
					Slot 4: 
					<input type='text' name='slot_4' value='" . $row['slot_4'] . "'><br><br>" . "
				<input class='button' type='submit' value='Submit'>
				</form>";
		}
	?>
</li>
</ul>
<div class="settingsfooter">
	<p class="centered">Door: 
	<a href="https://github.com/Timvandijk">Tim</a>,
	<a href="https://github.com/W-M-T">Ward</a> en 
	<a href="https://github.com/Mathiman">Mathis</a></p></center>
</div>
</div>
</div>
</div>
</body>
</html>	