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
	/* class for 3 columns */
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

		</ul>	
		
		<div id="three-columns" class="grid-container" style="display:block;">
			<ul class="rig columns-3">
			
			<?php
			// Voor lokale test server. Gebruik dit niet op een serieuze server.
			$dbhost = 'localhost';
			$dbuser = 'root';
			$dbpass = 'pass123';

			$conn = mysql_connect($dbhost, $dbuser, $dbpass)
			or die('Error connecting to mysql');

			$dbname = 'borrelbot';
			mysql_select_db($dbname);

			$res = mysql_query("SELECT * FROM cocktails");

			while ($row = mysql_fetch_array($res, MYSQL_BOTH)) {
				echo'<li>
					<img src="' . $row['picture'] . '" />
					<h3>'. $row['name'] . '</h3>
					<p>';
					if ($row['ingr_1'] != null) {
						echo $row['ingr_1']. ': '. $row['amnt_1'].'ml<br>';
					}
					if ($row['ingr_2'] != null) {
						echo $row['ingr_2']. ': '. $row['amnt_2'].'ml<br>';
					}
					if ($row['ingr_3'] != null) {
						echo $row['ingr_3']. ': '. $row['amnt_3'].'ml<br>';
					}
					if ($row['ingr_4'] != null) {
						echo $row['ingr_4']. ': '. $row['amnt_4'].'ml<br>';
					}
				echo'</p>
				</li>';
			
}			?>
			</ul>
		</div>
		<!--/#three-columns-->
		
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