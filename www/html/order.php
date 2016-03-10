<!DOCTYPE html>
<html>
<head>
<title>BorrelBot - Order</title>
<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="style.css" />	
</head>

<body>
<div class="wrapper">
	<div>
		<h1>BorrelBot - Order</h1>
		<ul class="grid-nav">
			<li><a href="#">Order</a></li>
			<li><a href="settings.php">Settings</a></li>
			<li><a href="#">Edit Recipes</a></li>
		</ul>	
	</div>
	<div class="container">
		<div id="three-columns" class="grid-container" style="display:block;">
			<!-- Handles orders -->
			<?php
				function ingredientToSlot($ingr) {
					$available = mysql_fetch_array(mysql_query("SELECT * FROM available"));
					if ($ingr == $available['slot1'])
						return 1;
					else if ($ingr == $available['slot2'])
						return 2;
					else if ($ingr == $available['slot3'])
						return 3;
					else if ($ingr == $available['slot4'])
						return 4; 
				}


				if (isset($_GET['order']) && !isset($_POST['order'])){
					echo "<div class='header'>Confirm order: " . $_GET['order'];
					echo '	<form action="order.php" method="POST">
								<input type="hidden" name="order" value="'. $_GET['order'] .'">
								<input type="submit" value="Order!">
							</form></div>';
				}
				else if (isset($_POST['order'])){
					$host = "localhost";
					$port = 12345;

					$dbhost = 'localhost';
					$dbuser = 'root';
					$dbpass = 'pass123';

					$conn = mysql_connect($dbhost, $dbuser, $dbpass)
					or die('Error connecting to mysql');

					$dbname = 'borrelbot';
					mysql_select_db($dbname);

					$f = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
					socket_set_option($f, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 1, 'usec' => 500000));
					$s = socket_connect($f, $host, $port);


					$available = mysql_fetch_array(mysql_query("SELECT * FROM available"));
					$msg = "";

					$amounts = array(0,0,0,0);
					$slots = array('slot_1', 'slot_2', 'slot_3', 'slot_4');
					$ingr_columns = array('ingr_1', 'ingr_2', 'ingr_3', 'ingr_4');
					$amnt_columns = array('amnt_1', 'amnt_2', 'amnt_3', 'amnt_4');

					$query = "SELECT * FROM cocktails WHERE name ='".$_POST['order']. "'";
					while ($cocktail = mysql_fetch_array(mysql_query($query))) {
						for ($i = 0; $i < 4; $i++) {
							for ($j = 0; $j < 4; $j++) {
								if ($available[$slots[$i]] == $cocktail[$ingr_columns[$j]]) {
									$amounts[$i] = $cocktail[$amnt_columns[$j]];
								}
							}
						}
						break;
					}
					$msg = $amounts[0]."-".$amounts[1]."-".$amounts[2]."-".$amounts[3]; 
					$len = strlen($msg);

					socket_sendto($f, $msg, $len, 0, $host, $port);

					socket_close($f);
					echo "<div class='header'><font color='green'>Order placed!</font></div>";
				}_
			?>
			<br><p><h2>Currently available</h2></p><br>
			<ul class="rig columns-3">
			<?php
			$dbhost = 'localhost';
			$dbuser = 'root';
			$dbpass = 'pass123';

			$conn = mysql_connect($dbhost, $dbuser, $dbpass)
			or die('Error connecting to mysql');

			$dbname = 'borrelbot';
			mysql_select_db($dbname);

			$available = mysql_fetch_array(mysql_query("SELECT * FROM available"));
			$cocktails = mysql_query("SELECT * FROM cocktails");

			$ingr_rows = array("ingr_1", "ingr_2", "ingr_3", "ingr_4");

			//Split all cocktails into two groups: those that are ready to be made and those that are not.

			$makeable = array();
			$notmakeable = array();

			while ($cocktail = mysql_fetch_array($cocktails, MYSQL_BOTH)) {
				$ok = true;
				foreach($ingr_rows as $ingr_row) {
					if ($cocktail[$ingr_row] != null) {
						if (!in_array($cocktail[$ingr_row], $available)){
							$ok = false;
						}
					}
				}
				if ($ok == true) {
					array_push($makeable, $cocktail);
				}
				else {
					array_push($notmakeable, $cocktail);
				}
			}

			foreach ($makeable as $cocktail) {
				echo'<li>
					<a href="order.php?order=' . $cocktail['name'] . '"><img src="' . $cocktail['picture'] . '" /></a>
					<h3>'. $cocktail['name'] . '</h3>
					<p>';
					if ($cocktail['ingr_1'] != null) {
						echo $cocktail['ingr_1']. ': '. $cocktail['amnt_1'].'ml<br>';
					}
					if ($cocktail['ingr_2'] != null) {
						echo $cocktail['ingr_2']. ': '. $cocktail['amnt_2'].'ml<br>';
					}
					if ($cocktail['ingr_3'] != null) {
						echo $cocktail['ingr_3']. ': '. $cocktail['amnt_3'].'ml<br>';
					}
					if ($cocktail['ingr_4'] != null) {
						echo $cocktail['ingr_4']. ': '. $cocktail['amnt_4'].'ml<br>';
					}
				echo'</p>
				</li>';
			}
			echo "</ul><br><p><h2>Currently unavailable</h2></p><br><ul class='rig columns-3'>";
			foreach ($notmakeable as $cocktail) {
				echo'<li>
					<img src="' . $cocktail['picture'] . '" />
					<h3>'. $cocktail['name'] . '</h3>
					<p>';
					if ($cocktail['ingr_1'] != null) {
						echo $cocktail['ingr_1']. ': '. $cocktail['amnt_1'].'ml<br>';
					}
					if ($cocktail['ingr_2'] != null) {
						echo $cocktail['ingr_2']. ': '. $cocktail['amnt_2'].'ml<br>';
					}
					if ($cocktail['ingr_3'] != null) {
						echo $cocktail['ingr_3']. ': '. $cocktail['amnt_3'].'ml<br>';
					}
					if ($cocktail['ingr_4'] != null) {
						echo $cocktail['ingr_4']. ': '. $cocktail['amnt_4'].'ml<br>';
					}
				echo'</p>
				</li>';
			}
			?>
			</ul>
		</div>
		<div class="footer">
		<p class="centered">
			Door: 
			<a href="https://github.com/Timvandijk">Tim</a>,
			<a href="https://github.com/W-M-T">Ward</a> en 
			<a href="https://github.com/Mathiman">Mathis</a>
		</p>
	</div>
	</div>
	
</div>
</body>
</html>