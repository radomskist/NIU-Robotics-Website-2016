<?php
	//Setting cookies
	//1209600 = two weeks
	ini_set('session.gc_maxlifetime', 1209600); //Client side
	session_set_cookie_params(1209600); //Server side
	session_start();

	$ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/niuweb.ini.php');
	$prioritymode = isset($_GET['prior']);
	$avaiblemode = isset($_GET['available']);
	$ticketmode = isset($_GET['tickets']);
	$sqlcon = new mysqli($ini['servername'], $ini['dblog'], $ini['dbpass'], $ini['dbname']);
	include_once("../classes/popup/popup.php");
	include_once("../classes/navbar/navbar.php");
	if($sqlcon->connect_errno) {
		echo "<h2 style=\"color:white\">DATABASE ERROR</h2>";
	}
	$stuffpopup = new popupform("taskwin");
	$createticket = new popupform("tickcreate");

	if(isset($_SESSION['username']))
		$userinfo = $_SESSION['upriv'];
	else
		$userinfo = -1;

?>
<!-- 
author Steven Radomski
email radomskist@yahoo.com
Copyright (c) 2016, Steven Radomski 
-->
<html>

<head>
	<meta charset="utf-8">
	<title>Tasks | Northern Illinois University Robotics Club</title>
	<link rel="stylesheet" href="/classes/panel.css"/>
	<link rel="stylesheet" href="/classes/imggal.css"/>
	<link rel="shortcut icon" href="http://www.niurobotics.com/favicon.ico" />
	<style type="text/css">
		#ticketbutton {

		}

		#ticketbutton button {
			cursor:pointer;
			font-size: 100%;
			width : 120;
			height : 40;
			color:white;
			list-style-type: none;
			margin : 0px;
			background-color: #1A4;
			padding: 0px;
			border-radius: 10px;
			display: block;
			float: right;
			margin-top : -50px;
			margin-right: 50px;
		}

		#ticketbutton select {
			cursor:pointer;
			font-size: 100%;
			width : 120;
			height : 40;
			color:white;
			list-style-type: none;
			margin : 0px;
			background-color: #1A4;
			padding: 0px;
			border-radius: 10px;
			display: block;
			float: right;
			margin-top : -50px;
			margin-right: 50px;
		}

	</style>
</head>

<?php
	//TODO Move print errors to end so whole page loads when showing this
	if(isset($_SESSION['username'])) { //TODO check if they tried to apply despite not being logged in? 
		$cursqlarray = $sqlcon ->query("SELECT * FROM MEMBERS WHERE ZID=\"" . $_SESSION['username'] . "\"");
		$curmemb = $cursqlarray->fetch_assoc();

		if(isset($_POST['RequestAccept']) && ($_SESSION['upriv'] & 0x0004)) {
			$raarray = explode(" ", $_POST['RequestAccept']);
			if(is_nan($raarray[0]) && is_nan($raarray[1])) {
				echo "<script>alert(\"ERROR: Invalid Input\");</script>";
				return;
			}

			$sqlcon ->query("UPDATE tasks SET CONTRIBUTOR=" . $raarray[1] . ",LMOD=" . $curmemb['MEMID'] . ",CHECKDATE = " . date("Y-m-d", strtotime("+1 week")) . " WHERE TASKID=" . $raarray[0]);
			if(!$sqlcon->error)
				echo "<script>alert(\"Sucessfully Added\");</script>";
			else
				echo "<script>alert(\"Error! " . $sqlcon->error . "\");</script>";

			$sqlcon ->query("DELETE FROM rtasks WHERE TID=" . $raarray[0]);

		}

		if(isset($_POST['taskappid'])) {
			//Check if applicant hasn't already applied
			$curtask = $sqlcon ->query("SELECT * FROM MEMBERS WHERE ZID=\"" . $_SESSION['username'] . "\"");
			if($curtask->num_rows == 0){ //idk if this actually works
				echo "<script>alert(\"ERROR: Not a member\");</script>";
				return;
			}
			$curarray = $curtask->fetch_assoc();
			$memid = $curarray['MEMID'];

			if(is_nan($_POST['taskappid'])) {
				echo "<script>alert(\"ERROR: Invalid Submission\");</script>";
				return;
			}

			//Chceking if job exists
			$curtask = $sqlcon ->query("SELECT * FROM tasks WHERE TASKID=" . $_POST['taskappid']);
			if($curtask->num_rows == 0){ //idk if this actually works
				echo "<script>alert(\"ERROR: Invalid Submission. Job doesn't exist.\");</script>";
				return;
			}
			$curarray = $curtask->fetch_assoc();

			//Check if applicant hasn't already applied
			$curtask = $sqlcon ->query("SELECT * FROM rtasks WHERE MID=" . $memid . " AND TID = " . $_POST['taskappid']);
			if($curtask->num_rows > 0){ //idk if this actually works
				echo "<script>alert(\"ERROR: You have already applied for this job.\");</script>";
				return;
			}

			if(!($inspon = $sqlcon->prepare("INSERT INTO rtasks (TID,MID,REDATE) VALUES (?,?,?)"))) {
				echo "<script>alert(\"DATABASE ERROR!\");</script>";
				return;
			}

			$inspon->bind_param("sss", $SETTID, $SETMID, $SETREDATE);
			$SETTID = $_POST['taskappid'];
			$SETMID = $memid;
			$SETREDATE = date("Y-m-d");
			$inspon->execute() or die("<script>alert(\"Sumission failed!" . $inspon->error . "\");</script>"); 

			echo "<script>alert(\"Applied successfully!\");</script>";
		}

		if(isset($_POST['taskrefid']) && ($_SESSION['upriv'] & 0x0004)) {
			if(is_nan($_POST['taskrefid'])) {
				echo "<script>alert(\"ERROR: Invalid Submission\");</script>";
				return;
			}

			if(!($inspon = $sqlcon->query("UPDATE tasks SET CHECKDATE='" . date("Y-m-d", strtotime("+1 week")) . "',LMOD='" . $curmemb['MEMID'] . "' WHERE TASKID =" . $_POST['taskrefid']))) {
				echo "<script>alert(\"DATABASE ERROR!\");</script>";
				return;
			}
			echo "<script>alert(\"Task refreshed successfully!\");</script>";
		}

		if(isset($_POST['taskdeleteid']) && ($_SESSION['upriv'] & 0x0008)) {
			if(is_nan($_POST['taskdeleteid'])) {
				echo "<script>alert(\"ERROR: Invalid Submission\");</script>";
				return;
			}

			if(!($inspon = $sqlcon->query("DELETE FROM tasks WHERE TASKID =" . $_POST['taskdeleteid']))) {
				echo "<script>alert(\"DATABASE ERROR!\");</script>";
				return;
			}
			echo "<script>alert(\"Task deleted successfully!\");</script>";
		}

		/*TICKETS*/
		if(isset($_POST['ticketdelete']) && ($_SESSION['upriv'] & 0x0010)) {
			if(is_nan($_POST['ticketdelete'])) {
				echo "<script>alert(\"ERROR: Invalid Submission\");</script>";
				return;
			}

			if(!($inspon = $sqlcon->query("DELETE FROM tickets WHERE TICKID = " . $_POST['ticketdelete']))) {
				echo "<script>alert(\"DATABASE ERROR!\");</script>";
				return;
			}
			echo "<script>alert(\"Ticket deleted successfully!\");</script>";
		}

		if(isset($_POST['ticketapprove']) && ($_SESSION['upriv'] & 0x0010)) {
			if(is_nan($_POST['ticketapprove'])) {
				echo "<script>alert(\"ERROR: Invalid Submission\");</script>";
				return;
			}

			if(!($instick = $sqlcon->query("SELECT * FROM tickets WHERE TICKID = " . $_POST['ticketapprove']))) {
				echo "<script>alert(\"ERROR: Invalid Submission\");</script>";
				return;
			}
			$intick = $instick->fetch_assoc();

			$depres = null;
			if($intick["DEPENDS"] != null) {
				if(!($depquery = $sqlcon->query("SELECT * FROM tasks WHERE DESCRIPT = '" . $intick["DESC"]))) {
					echo "<script>alert(\"ERROR: Invalid Submission\");</script>";
					return;
				}
				$depres = $depquery->fetch_assoc();
			}

			if(!($inspon = $sqlcon->query("INSERT INTO tasks (`PROJNAME`, `SYSTEM`, `DESCRIPT`, `DEPENDS`, `DUEDATE`, `FIELDFLAG`, `LMOD`) VALUES ('" . $intick["PROJNAME"] . "','" . $intick["SYSTEM"] . "','" . $intick["DESC"] . "','" . $depres . "','" .  $intick["DUEDATE"] . "','" . $intick["FIELD"] . "','". $curmemb['MEMID'] . "')"))) {
				echo "<script>alert(\"DATABASE ERROR! " . $sqlcon->error . " \");</script>";
				return;
			}

			if(!($inspon = $sqlcon->query("DELETE FROM tickets WHERE TICKID = " . $_POST['ticketapprove']))) {
				echo "<script>alert(\"DATABASE ERROR!\");</script>";
				return;
			}

			echo "<script>alert(\"Ticket approved successfully!\");</script>";
		}

		/*TICKET CREATION*/
		if(($_POST['robotdesc'] !== null) && ($_SESSION['upriv'] & 0x0010)) {

			/*TODO check inputs*/
			if(!($inspon = $sqlcon->prepare("INSERT INTO tickets ( `TICKID` ,`PROJNAME` ,`SYSTEM` ,`DESC` ,`DEPENDS` ,`DUEDATE` ,`FIELD` ) VALUES (?,?,?,?,?,?,?)"))) {
				echo "<script>alert(\"Preparing failed!\")</script>";
				return;
			}

			$inspon->bind_param("sssssss", $TICKETID, $SETPNAME,$SETSNAME,$SETDESC,$SETDEP,$SETDUEDATE,$SETFIELD);

			$TICKETID = null;
			$SETPNAME = $_POST['setproject'];
			$SETSNAME = $_POST['setsystem'];
			$SETDESC = $_POST['robotdesc'];
			$SETDEP = $_POST['setdepends'];
			$SETDUEDATE = null;
		
			if($_POST['ticketduedate'] != null)
				$SETDUEDATE = date('Y-m-d', strtotime($_POST['ticketduedate']));

			$intfield = 0;	
			if(is_array ($_POST['jobtype'])) {
				foreach ($_POST['jobtype'] as $op)
					$intfield |= $op;
			}
			else
				$intfield = $_POST['jobtype'];

			$SETFIELD = $intfield;

			$inspon->execute() or die("<script>alert(\"Sumission failed!" . $inspon->error . "\");</script>"); 

			echo "<script>alert(\"Success\");</script>";
		}
	}
	$tasklist = $sqlcon ->query("SELECT * FROM tasks ORDER BY CASE WHEN DUEDATE IS NULL THEN 1 ELSE 0 END, DUEDATE");
	$ticketlist = $sqlcon ->query("SELECT * FROM tickets ORDER BY CASE WHEN DUEDATE IS NULL THEN 1 ELSE 0 END, DUEDATE");

	?>

<body>
	
	<div class="popupwin" id="tickcreate" style="width:550;max-width:700px">
		<h1>Create a ticket</h1>
		<span><a onclick="togglevis('tickcreate')" >Close[x]</a></span>
	</div>

	<div class="popupwin" id="taskwin">

		<span><a onclick="togglevis('taskwin')" >Close[x]</a></span>
	</div>

	<?php echo "<script src=\" " . $ini['taskdir'] . "/taskscript1.92.js\"></script>"; ?>

	<style type="text/css">
		body {
			/*Background image*/
			background-image: url(../DefaultBackground.png);
			background-size: cover;
			background-attachment: fixed;
		}
	</style>

	<?php echonavbar(3); ?>

	<div id=ticketbutton>
		<select name="FilterMajor" onchange="majfilter(this)">
			<option value="null"> -- Major-- </option>
			<option value="me">Mechanical</option>
			<option value="ee">Electrical</option>
			<option value="cs">Computer Science</option>
			<option value="bs">Business</option>
		</select>

		<button style="margin-right: 180px;" onclick="toggletickets(this)">Toggle Tickets</button>

		<?php if(($_SESSION['username']) && ($_SESSION['upriv'] & 0x0002)):?>
			<button style="margin-right: 310px;" <?php echo "onclick=\"javascript:ticketcreate()\"";?> >Create Ticket</button>
		<?php endif; ?>
		<br>

	<div>

	<div id=niurpanel>
		<ul><b>
		<li><a class="button">All</a></li>
		<li> <a name="PriorLi" onclick="toggleprior()" >Priority</a></li>
		<li><a name="AvaibleLi" onclick="toggleavaible()">Available</a></li>
		</b></ul>
	</div>

	<div id="imgalcont"> 
	<ul>
	<?php 
	echo "<script>userinfo(" . $userinfo . ")\n";


	while($currob = $tasklist->fetch_assoc()) :
		//there are requests for this job
		$currob['FLAGS'] = 0x00;	
		if(($_SESSION['upriv'] & 0x0004)) {
			$curtask = $sqlcon ->query("SELECT * FROM rtasks WHERE TID=\"" . $currob['TASKID'] . "\"");
			if($curtask->num_rows > 0)
				$currob['FLAGS'] |= 0x01;
		}

		// you're doing it
		if($currob["CONTRIBUTOR"] > 0) {
			if($currob["CONTRIBUTOR"] == $curmemb['MEMID'])
				$currob['FLAGS'] |= 0x06;
			// another member doing it
			else if($currob["CONTRIBUTOR"] > 0)
				$currob['FLAGS'] |= 0x02;
		}

	
		echo "loadtabs(\"" . $ini['projdir'] . "\"," . json_encode($currob) . ");\n";
	endwhile; 
	while($currob = $ticketlist->fetch_assoc()) :
		echo "loadtickets(\"" . $ini['projdir'] . "\"," . json_encode($currob) . ");\n";
	endwhile;
	echo "</script>";?>

	<!--TODO: TICKETS -->
	</ul></div>

	<?php echofooter(); ?>
</body>
</html>
