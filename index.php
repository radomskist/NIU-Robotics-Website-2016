<?php 
	/*TODO: MEMCACHE THIS*/
	$ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/niuweb.ini.php');
?>

<!DOCTYPE html>
<html>
<!-- 
author Steven Radomski
email radomskist@yahoo.com
Copyright (c) 2016, Steven Radomski 
-->

<head>
	<meta charset="utf-8">
	<title>Northern Illinois University Robotics Club</title>
	<link rel="shortcut icon" href="http://www.niurobotics.com/favicon.ico" />

	<style type="text/css">
	.HostBox {
			position:fixed; 
			bottom:20px; 
			right:20px; 
		width:145px; 
		height:90px;
	}
	.HostBox a {
		color:inherit;
		text-decoration: none;
	}

	/*This is the navigation bar*/
	ul {
		font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
		list-style-type: none;
		margin: 0;
		padding: 0;
		border-color: transparent;
		text-align: center;
	}

	li {
		display: inline;
	}

	/*This is for putting your mouse over a button*/
	li a {
		font-size:20px;
		display: inline-block;
		color: white;
		text-align: center;
		padding: 14px 16px;
		text-decoration: none;
	}

	li a:hover {
		background-color: #11;
	}

	</style>
</head>


<body>
	<?php
		/*TODO Make it load this into memcached once so it wont load everytime the page is loaded*/
		$RandBg = glob('bg/*.jpg*');
	?>
	<div style="text-align: center"><a href="/About"><img src="logo.png" class="img-thumbnail" width="200" height="200"/></a></div>

	<ul style ="Color:#777; padding: 0px; margin: 10px; padding-left:8px;text-shadow: 1px 0 black, -1px 0 black, 0 1px black, 0 -1px black;"><b>
	  <?php echo "<li><a href=" . $ini['projdir'] . ">" . $ini['projname'] . "</a></li>"; ?>
	  <li><a href="/Tasks">Tasks</a></li>
	  <li><a href="/sponsors">Sponsorship</a></li>
	  <li><a href="/About">About</a></li>
	</b></ul>

	<div class="HostBox" style="">
		<a href="http://armortechs.com/">
		<h3 style ="Color:#777; padding: 0px; margin: 10px; padding-left:8px;text-shadow: 1px 0 black, -1px 0 black, 0 1px black, 0 -1px black;" >Hosted by:</h2>
		<img src="sponsors/ArmorTech.png" style="padding-left:15px; padding-bottom:10px;" width=110 height=37 />
		<br>
		</a>
	</div>

	<h2 style ="Color:#FFF; padding: 0px; margin: 10px; padding-left:8px;text-shadow: 1px 0 black, -1px 0 black, 0 1px black, 0 -1px black; position:fixed; bottom:120px;left:8px;" >Platinum Sponsors:</h2>
	<a href="http://www.idealindustries.com/">
		<img style="padding:10px;max-width:200px;position:fixed; bottom:20px; left:10px;" src="sponsors/ideallogo.png">
	</a>


	<a href="http://www.solidworks.com/">
		<img style="padding:10px;max-width:200px;position:fixed; bottom:20px; left:200px;" src="sponsors/solidworks.png">
	</a>

	<img class="overlay">
	<style type="text/css">
		navbar {
		   background-color: transparent;
		   background: transparent;
		}

		html {
			min-width:100%;
			min-height:100%;
			overflow: hidden;
			/*Background image*/
			background-image: linear-gradient(
      			rgba(100, 100, 200, 0.15), 
     			rgba(100, 100, 200, 0.15)),
				url(<?php echo $RandBg[array_rand($RandBg,1)]; ?>);
			background-size: cover;
			background-repeat: no-repeat;
			background-position: 50% 50%;
			background-color: #231f20;
		}
	</style>
</body>
</html>
