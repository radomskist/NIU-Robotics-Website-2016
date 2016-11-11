<?php
	//Setting cookies
	//1209600 = two weeks
	ini_set('session.gc_maxlifetime', 1209600); //Client side
	session_set_cookie_params(1209600); //Server side
	session_start();
	if(!($_SESSION['upriv'] & 0x8000)) {
		echo $_SESSION['upriv'] ."You do not have permission to visit this webpage";
		return;
	}
	$ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/niuweb.ini.php');

	if(isset($_GET['miss']))
		echo "<h1 style=\"color:red\">MISSING FIELD</h1>";

	if(isset($_GET['sqlr']))
		echo "<h1 style=\"color:red\">MYSQLI ERROR</h1>";

	$sqlcon = new mysqli($ini['servername'], $ini['dblog'], $ini['dbpass'], $ini['dbname']);
	if($sqlcon->connect_errno) {
		echo "<h1 style=\"color:red\">DATABASE ERROR</h1>";
		return;
	}

	/*classes*/
	include_once("../classes/mysqlviewer.php");
	include_once("../classes/filemanage.php");
	
	/*data base managers*/
	$sponsorsql = new sqleditor("Sponsor",$sqlcon,"sponsors",array("SPONID"));
	$projsql = new sqleditor("Projects",$sqlcon,"projects",array("ID"));
	$tasksql = new sqleditor("Tasks",$sqlcon,"tasks",array("TASKID"));

	/*file managers*/
	$bgimg = new imgman("bg");
	$projxml = new fileman("Projects/*.xml");
	$projimg = new imgman("Projects/");
	$sponimg = new imgman("sponsors/");

	//TODO CLEANUP
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$sponsorsql->inserttotable();
		$projsql->inserttotable();
		$tasksql->inserttotable();
	}

	function submitsponsor() {
		global $sqlcon;

		if(!($inspon = $sqlcon->prepare("INSERT INTO sponsors (SPONNAME,SPONLOGO,SPONWEB) VALUES (?,?,?)"))) {
			echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?&sqlr";
			return;
		}

		$inspon->bind_param("sss", $SETSNAME,$SETSLOGO,$SETSWEB);

		$SETSNAME = $_POST['SNAME'];
		$SETSLOGO = $_POST['SLOGO'];
		$SETSWEB = $_POST['SWEB'];

		if($SETSNAME == NULL || $SETSLOGO == NULL || $SETSWEB == NULL) {
			echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?&miss";
			return;
		}

		$inspon->execute();
	}
?>

<html lang="en">
	<!-- 
	author Steven Radomski
	email radomskist@yahoo.com
	Copyright (c) 2016, Steven Radomski 
	-->
<head>
	<title>Admin | Northern Illinois University Robotics Club</title>
	<link rel="shortcut icon" href="http://www.niurobotics.com/favicon.ico" />
</head>

<body>
	<h1>SPONSORS</h1>
	<h2>New Sponsor (All fields are required)</h2>
	<?php $sponsorsql->echoinsert(); ?>

	<h3>Current Sponsors</h2>
	<?php $sponsorsql->echotable(); ?>

	<h1>=================================</h1>
	<h2>Sponsor Logos<h2>
	<?php $sponimg->echolist();
	echo "<br>";
	$sponimg->echoupload(); ?>
	<br><br>


	<h1>JOB REQUESTS</h1>
	<table border="1" style="width:50%;text-align:center;">	
	<th>JRID</th>
	<th>DESCRIPT</th> <!-- wont be stored in the database, just look it up for convinience -->

	</tr>
	</table>
	<h1>=================================</h1>
	<br><br>

	<h1>TASKS</h1>
	<h2>Add new task</h2>
	<?php $tasksql->echoinsert(); ?>


	<h2>Current Tasks</h2>
	<?php $tasksql->echotable(); ?>

	<h1>=================================</h1>
	<br><br>
	<h1>PROJECTS</h1>

	<h2>New Project</h2>
	<?php $projsql->echoinsert(); ?>

	<h2>Projects Database</h2>
	<?php $projsql->echotable(); ?>

	<h2>Project XHTML files<h2>
	<?php $projxml->echolist();
	echo "<br>";
	$projxml->echoupload(); ?>

	<h2>Project Images files<h2>
	<?php $projimg->echolist();
	echo "<br>";
	$projimg->echoupload(); ?>

	<h1>=================================</h1>
	<br><br>
	<h1>JOB TICKETS</h1>

	<h1>=================================</h1>

	<h2>Background Images files<h2>
	<?php $bgimg->echolist(); ?>
</body>
</html>
