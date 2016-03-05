<!DOCTYPE html>
<html>
<head>
<title>BorrelBot - Order</title>
<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
<style type="text/css">
	* {
		margin: 0;
		padding: 0;
	}
	body {
		background: url(images/noise_light-grey.jpg);
		font-family: 'Helvetica Neue', arial, sans-serif;
		font-weight: 200;
	}

	h1 {
		font-family: 'Oswald', sans-serif;
		font-size: 4em;
		font-weight: 400;
		margin: 0 0 20px;
		text-align: center;
		text-shadow: 1px 1px 0 #fff, 2px 2px 0 #bbb;
	}
	hr {
		border-top: 1px solid #ccc;
		border-bottom: 1px solid #fff;
		margin: 25px 0;
		clear: both;
	}
	.centered {
		text-align: center;
	}
	.wrapper {
		width: 100%;
		padding: 30px 0;
	}
	.container {
		width: 1200px;
		margin: 0 auto;
	}
	ul.grid-nav {
		list-style: none;
		font-size: .85em;
		font-weight: 200;
		text-align: center;
	}
	ul.grid-nav li {
		display: inline-block;
	}
	ul.grid-nav li a {
		display: inline-block;
		background: #999;
		color: #fff;
		padding: 10px 20px;
		text-decoration: none;
		border-radius: 4px;
		-moz-border-radius: 4px;
		-webkit-border-radius: 4px;
	}
	ul.grid-nav li a:hover {
		background: #7b0;
	}
	ul.grid-nav li a.active {
		background: #333;
	}
	.grid-container {
		display: none;
	}
	/* ----- Image grids ----- */
	ul.rig {
		list-style: none;
		font-size: 0px;
		margin-left: -2.5%; /* should match li left margin */
	}
	ul.rig li {
		display: inline-block;
		padding: 10px;
		margin: 0 0 2.5% 2.5%;
		background: #fff;
		border: 1px solid #ddd;
		font-size: 16px;
		font-size: 1rem;
		vertical-align: top;
		box-shadow: 0 0 5px #ddd;
		box-sizing: border-box;
		-moz-box-sizing: border-box;
		-webkit-box-sizing: border-box;
	}
	ul.rig li img {
		max-width: 100%;
		height: auto;
		margin: 0 0 10px;
	}
	ul.rig li h3 {
		margin: 0 0 5px;
	}
	ul.rig li p {
		font-size: .9em;
		line-height: 1.5em;
		color: #999;
	}
	ul.rig.columns-3 li {
		width: 30.83%; /* this value + 2.5 should = 33% */
	}

	@media (max-width: 1199px) {
		.container {
			width: auto;
			padding: 0 10px;
		}
	}

	@media (max-width: 480px) {
		ul.grid-nav li {
			display: block;
			margin: 0 0 5px;
		}
		ul.grid-nav li a {
			display: block;
		}
		ul.rig {
			margin-left: 0;
		}
		ul.rig li {
			width: 100% !important; /* over-ride all li styles */
			margin: 0 0 20px;
		}
	}
</style>
</head>

<body>
<div class="wrapper">
	<div class="container">
		<h1>BorrelBot</h1>
		<ul class="grid-nav">
			<li><a href="#">Order</a></li>
			<li><a href="settings.php">Settings</a></li>
			<li><a href="#">Edit Recipes</a></li>
		</ul>	
		
		<div id="three-columns" class="grid-container" style="display:block;">
			<h3>Currently available</h3>
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
			echo "</ul><br><h3>Currently unavailable</h3><br><ul class='rig columns-3'>";
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
		
		<!-- Verander de links -->
		<p class="centered">Door: 
		<a href="https://github.com/Timvandijk">Tim</a>,
		<a href="https://github.com/W-M-T">Ward</a> en 
		<a href="https://github.com/Mathiman">Mathis</a></p>
	</div>
	<!--/.container-->
</div>
<!--/.wrapper-->
</body>
</html>