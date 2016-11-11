<?php
	//Setting cookies
	//1209600 = two weeks
	ini_set('session.gc_maxlifetime', 1209600); //Client side
	session_set_cookie_params(1209600); //Server side
	session_start();

	/*TODO: Make a global variable so it doesn't need to load data base every time*/
	$ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/niuweb.ini.php');
	$typesini = parse_ini_file("./robottype.ini");
	$sqlcon = new mysqli($ini['servername'], $ini['dblog'], $ini['dbpass'], $ini['dbname']);
	if($sqlcon->connect_errno) {
		echo "<h2 style=\"color:red\">DATABASE ERROR</h2>";
		return;
	}

	$yeararray = array();
	$currentrobot = $_GET['robot'];
	
	$query = $sqlcon ->query("SELECT MAX(PROJYEAR) FROM projects LIMIT 1");
	$assoc = $query->fetch_assoc();
	$query->free_result();
	$yearmax = $assoc["MAX(PROJYEAR)"];

	if($yearmax < 2000) {
		echo "<h2 style=\"color:red\">DATABASE TABLE ERROR</h2>";
		return;
	}

	$query = $sqlcon ->query("SELECT MIN(PROJYEAR) FROM projects LIMIT 1");
	$assoc = $query->fetch_assoc();
	$query->free_result();
	$yearmin = $assoc["MIN(PROJYEAR)"];

	for ($i = $yearmax; $i >= $yearmin; $i--) {
		if($i == date("Y")) //skip current year
			continue;
		$yeararray[] = $i;
	}

	$currentselect = htmlspecialchars($_GET['year']);
	if($currentselect == NULL)
		$currentselect = $yeararray[0];

	$projectlist = $sqlcon ->query("SELECT * FROM projects WHERE PROJYEAR = " . $currentselect);

	/*Generating sidepanel*/
	$currentspot;
	$PanelYears = "<div id=\"niurpanel\"> \n <ul><b>\n";
	for($i = 0; $i < sizeof($yeararray); $i++) {
		if($yeararray[$i] == $currentselect) {
			$PanelYears .= "<li><a class=\"current\" ";
			$currentspot = $currentselect;
		}
		else
			$PanelYears .= "<li><a ";

		$PanelYears .= "href=" . $ini['projdir'] . "?year=". $yeararray[$i] . " >" . $yeararray[$i] . "</a></li>\n";
	}
	$PanelYears .= "\n</b></ul>\n</div>";

?>

<html>
<!-- 
author Steven Radomski
email radomskist@yahoo.com
Copyright (c) 2016, Steven Radomski 
-->

<head>
	<meta charset="utf-8">
	<title>Projects | Northern Illinois University Robotics Club</title>
	<link rel="shortcut icon" href="http://www.niurobotics.com/favicon.ico" />
	<link rel="stylesheet" href="/classes/panel.css"/>
	<link rel="stylesheet" href="/classes/imggal.css"/>
	<link rel="stylesheet" href="/Projects/infopage.css"/>
</head>

<body>
	<script src="/Projects/projectscript.js"></script> 

	<style type="text/css">
	body {
		/*Background image*/
		background-image: url(../DefaultBackground.png);
		background-size: cover;
		background-attachment: fixed;
	}	
	</style>

	<?php include_once("../classes/navbar/navbar.php");?>
	<?php echonavbar(4); ?>

	<div>
	<?php echo $PanelYears ?>

	<?php if(!$currentrobot) : ?>
		<!--Loading year page-->
		<div id="imgalcont"> <ul>
		<?php 
		$robolist = array();
		$typelist = array();
		while($currob = $projectlist->fetch_assoc()) : 
			$robolist[] = $currob['PROJNAME'];
			$typelist[] = $typesini['name'][$currob['TYPE']];
		endwhile; 
		echo "<script>loadtabs(\"" . $currentselect . "\",\"" . $ini['projdir'] . "\"," . json_encode($robolist) . "," . json_encode($typelist) . ")</script>";
		?>
		</ul></div>
	<?php else : ?>
		<div id="infopage"> 
		<?php echo "<script>loadpage(\"" . $currentselect . "\",\"" . $ini['projdir'] . "\",\"" . $currentrobot . "\");</script>"; ?>
		</div>
	<?php endif; ?>
	</div>
	<?php echofooter(); ?>
</body>
</html>
