<!DOCTYPE html>
<html>
<head>
	<title>BorrelBot - Settings</title>
	<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
	<link href="style.css" rel="stylesheet" type="text/css">	
</head>
<body>
	<center>
		<h1>BorrelBot - Settings</h1>
	</center>
	<center>
		<a href="order.php"><button class="nav-button">Order</button></a>
		<a href="#"><button class="nav-button">Settings</button></a>
		<a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ"><button class="nav-button">Edit Recipes</button></a>
	</center>
	<div class="wrapper">
	<div class="container">		
		<table>
		<tr>
			<td class="invisible"></td>
			<td>
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
					echo"<center><span><font color='green'>Saved</font><br><br></span></center>";
				}
				
				$res = mysql_query("SELECT * FROM available");
				while ($row = mysql_fetch_array($res, MYSQL_BOTH)) {
					echo "	
						<h2>Currently installed</h2>
						<form action='settings.php' method='post'>
							<input type='hidden' name='send' value='true'>Slot 1: 
							<input type='text' name='slot_1' value='" . $row['slot_1'] . "'><br>
							Slot 2: 
							<input type='text' name='slot_2' value='" . $row['slot_2'] . "'><br>
							Slot 3: 
							<input type='text' name='slot_3' value='" . $row['slot_3'] . "'><br>
							Slot 4: 
							<input type='text' name='slot_4' value='" . $row['slot_4'] . "'><br><br>" . "
						<center><input class='nav-button' type='submit' value='Submit'></center>
						</form>";
				}
			?>
			</td>
			<td class="invisible"></td>
		</tr>
		</table>
	</div>
	<div class="footer">
		Door: 
		<a href="https://github.com/Timvandijk">Tim</a>,
		<a href="https://github.com/W-M-T">Ward</a> en 
		<a href="https://github.com/Mathiman">Mathis</a>
	</div>
	</div>
</body>
</html>