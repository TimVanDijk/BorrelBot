<html>
<head>
<title>BorrelBot - Settings</title>
</head>
<body>
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
			echo"Saved<br>";
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
					<input type='text' name='slot_4' value='" . $row['slot_4'] . "'><br>" . "
				<input type='submit' value='Submit'>
				</form>";
		}
		
		
	?>

</body>
</html>	