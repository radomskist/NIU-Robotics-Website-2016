<?php
	session_start();
	$ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/niuweb.ini.php');
	$sqlcon = new mysqli($ini['servername'], $ini['dblog'], $ini['dbpass'], $ini['dbname']);
	if($sqlcon->connect_errno) {
		echo "<h2 style=\"color:white\">DATABASE ERROR</h2>";
	}

	if(!isset($_GET["TID"])) {
		$projlist = $sqlcon ->query("SELECT * FROM projects WHERE PROJYEAR = " . date("Y"));

		if($sqlcon->error) {
			echo "ERROR!";
			return;
		}

		$arr = array();
		while($row = $projlist->fetch_assoc()) {
			$arr[] = $row['PROJNAME']; 
		}
		echo json_encode($arr);
		return;
	}
	else {
		if(!($_SESSION['upriv'] & 0x0004) || is_nan($_GET["TID"]))
			return;

		
		
		$projlist = $sqlcon ->query("SELECT * FROM rtasks WHERE TID = " . $_GET["TID"]);

		if($sqlcon->error) {
			echo "ERROR!";
			return;
		}
		$arr = array();

		while($row = $projlist->fetch_assoc()) {
			$memlist = $sqlcon ->query("SELECT * FROM MEMBERS WHERE MEMID = " . $row["MID"]);
			if($sqlcon->error) {
				echo "ERROR!";
				return;
			}

			$meminfo = $memlist->fetch_assoc();

			$newar = array();
			$newar['MID'] = $row['MID'];
			$newar['ZID'] = $meminfo["ZID"];
			$newar['FNAME'] = $meminfo['FNAME'];
			$newar['LNAME'] = $meminfo['LNAME'];
 			$newar['DATE'] = $row['REDATE'];

			$arr[] = $newar;
		}

		echo json_encode($arr);
			return;
	}
?>
