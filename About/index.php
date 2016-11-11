<?php 
	//Setting cookies
	//1209600 = two weeks
	ini_set('session.gc_maxlifetime', 1209600); //Client side
	session_set_cookie_params(1209600); //Server side

	session_start(); ?>

<html>

<head>
	<meta charset="utf-8">
	<title>About | Northern Illinois University Robotics Club</title>
	<link rel="shortcut icon" href="http://www.niurobotics.com/favicon.ico" />
	<link rel="stylesheet" href="/classes/navbar.css"/>
	<link rel="stylesheet" href="/About/about.css"/>
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
	echonavbar(1);?>
	<img class="banner" src="/About/roboticsbanner.jpg">
	<div id="aboutback">
		<h1>About the Club</h1>
		<hr>
		<h2>The Team:</h2>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;NIU Robotics club participates in the annual 
<a href="http://mrdc.ec.illinois.edu/" target="_blank">Midwestern Robotics Design Competition (MRDC)</a>, previously named the <a href="http://jsdc.ec.illinois.edu/" target="_blank">Jerry Sanders Creative Design Competition (JSDC)</a> where, in our <strong>first two years</strong>, we received the <strong>Most Innovative</strong> award <strong>twice</strong>. In addition to mentoring local 
<a href="http://www.usfirst.org/roboticsprograms/ftc" target="_blank">FTC</a> and <a href="http://www.usfirst.org/roboticsprograms/frc" target="_blank">FRC</a>
 teams, we also support local STEM activities including <a href="http://www.niu.edu/stemfest/" target="_blank">STEMfest</a>, regular presentations to 
<a href="http://www.niu.edu/ceet/" target="_blank">NIU CEET</a> visitors, engineering classes, and <a href="http://www.niu.edu/STEM/camps/index.shtml" target="_blank">STEM Summer Camps</a>. </p>

		<h2>The Awards:</h2>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;During the <a href="http://jsdc.ec.illinois.edu/" target="_blank">JSDC</a> (2012-2013), the club made history, entering the first flying robot into the competition. This first quadcopter, <a href="quin.php">QUIN</a> standing for NIU Quadcopter in reverse, won the JSDC Most Innovative award.</p>

		<p>&nbsp;&nbsp;&nbsp;&nbsp;During the <a href="http://jsdc.ec.illinois.edu/" target="_blank">JSDC</a> (2013-2014), the club again made history, entering the first autonomous robot into the competition. This first AI bot, <a href="nai.php">NAI</a> standing for NIU Artificial Intelligence, won the club's second (in two years) JSDC Most Innovative award.</p>

		<hr>
		<h2>Officers</h2>
		<p><strong>Faculty Advisor(s):</strong> <a href="mailto:jyru@niu.edu">Dr. Ryu</a></p> 
		<p><strong>President:</strong> <a href="mailto:president@niurobotics.com">Joel Rushton</a></p>
		<p><strong>Vice-President:</strong> <a href="mailto:vice-president@niurobotics.com">Jake Klein</a></p>
		<p><strong>Secretary:</strong> <a href="mailto:secretary@niurobotics.com">Dennis Grekousis</a></p>
		<p><strong>Treasurer:</strong> <a href="mailto:treasurer@niurobotics.com">Andrew Widmar</a></p>
		<p><strong>Webmaster:</strong> <a href="mailto:webmaster@niurobotics.com">Steve Radomski</a></p>
		<p><strong>Founded</strong> in August 2012 by <a href="mailto:rsriddel@gmail.com">Ryan Riddel</a></p>
	</div>

	<?php echofooter(); ?>
</body>
</html>
