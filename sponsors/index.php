<?php 
	//Setting cookies
	//1209600 = two weeks
	ini_set('session.gc_maxlifetime', 1209600); //Client side
	session_set_cookie_params(1209600); //Server side

	session_start(); ?>
<html>
<!-- 
author Steven Radomski
email radomskist@yahoo.com
Copyright (c) 2016, Steven Radomski 
-->

<head>
	<meta charset="utf-8">
	<title>Northern Illinois University Robotics Club</title>
	<link rel="stylesheet" href="/classes/navbar.css"/>
	<link rel="stylesheet" href="/sponsors/sponsor.css"/>
	<link rel="shortcut icon" href="http://www.niurobotics.com/favicon.ico" />
</head>

<body>
	<style type="text/css">
	body {
		/*Background image*/
		background-image: url(../DefaultBackground.png);
		background-size: cover;
		background-attachment: fixed;
	}	
	</style>
	<?php include_once("../classes/navbar/navbar.php");
	echonavbar(2);?>
	<div id="mainback">
		<script src="sponsors.js"></script>
		<h1 style="color:white;padding:10px; padding-bottom:0px; margin-bottom:0px; font-size:4vw;">Thank you to our sponsors!</h1>
		<h2 style="color:#AAA;margin-top:0px;padding-top:0px; font-size:1.25vw;">The NIU robotics club would not be possible without our sponsors</h1>

		<br>
		<h1 style="color:#AAF;margin-top:0px;padding-top:0px; ">Interested in sponsoring NIU robotics?</h1>
		<a href="RoboticsSponsorship.pdf" class="button"style="cursor:pointer;font-size: 20px;min-width :120;height : 100;color:white;border-radius: 10px;display: block;position:relative; margin:10px auto;background-color: #1A4; width:100px;text-decoration:none;font-weight: bold;"> 
			<br>Sponsor <br>Info
		</a>

		<script> listsponsors(); </script>

	</div>


	<?php echofooter(); ?>
</body>
</html>
