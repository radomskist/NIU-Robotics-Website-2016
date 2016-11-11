<?php
	//Setting cookies
	//3600 = on hour
	ini_set('session.gc_maxlifetime', 3600); //Client side
	session_set_cookie_params(3600); //Server side
	session_start();
	if(!(($_SESSION['upriv'] & 0x0001) || $_SESSION['register'])) {
		echo "You do not have permission to visit this webpage.";
		return;
	}
	else {
		session_unset();
		$_SESSION['register'] = true;//TODO fix potential bug
	}
	$ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/niuweb.ini.php');

	$sqlcon = new mysqli($ini['servername'], $ini['dblog'], $ini['dbpass'], $ini['dbname']);
	if($sqlcon->connect_errno) {
		echo "<h2 style=\"color:white\">DATABASE ERROR</h2>";
	}
?>

<html lang="en">
<head>
	<title>Registration | Northern Illinois University Robotics Club</title>
	<link rel="stylesheet" href="/reg/reg.css"/>
	<link rel="shortcut icon" href="http://www.niurobotics.com/favicon.ico" />
	<script src="reg.js"></script>
</head>


<body>
<!-- 
author Steven Radomski
email radomskist@yahoo.com
Copyright (c) 2016, Steven Radomski 
-->

	<style type="text/css">
		body {
			background-color: #231f20;
		}
	</style>

	<script src="reg/reg.js"></script>
	<div id="regback">
	<img id="mainlogo" src="../logo.png">
	<h2>MEMBER REGISTRATION</h2>
	   <form id="regform" name="regform" onsubmit="if(!checkform()) event.preventDefault();" method="post">
			<label style="font-size:17px;font-weight:bold;color:white;" for="zid">ZID:</label>
			<label style="font-size:17px;font-weight:bold;color:RED;" for="zid">*</label>
			<input size="25" type="text" name = "zid" id= "zid" placeholder = "Required" maxlength="8" required>
			</br>

			<label style="font-size:17px;font-weight:bold;color:white;" for="password">Password:</label>
			<label style="font-size:17px;font-weight:bold;color:RED;" for="password">*</label>
			<input size="25" type="password" name = "password" id="password" placeholder = "Required" required>
			</br>

			<label style="font-size:17px;font-weight:bold;color:white;" for="password">Confirm Password:</label>
			<label style="font-size:17px;font-weight:bold;color:RED;" for="password">*</label>
			<input size="25" type="password" name = "cpassword" id="cpassword" placeholder = "Required" required>
			</br>

			<label style="font-size:17px;font-weight:bold;color:white;" for="firstname">First Name:</label> 
			<input size="25" type="text" name = "firstname" id="firstname" placeholder = "Not Required" maxlength="20">
			</br>

			<label style="font-size:17px;font-weight:bold;color:white;" for="lastname">Last Name:</label> 
			<input size="25" type="text" name = "lastname" id="lastname" placeholder = "Not Required" maxlength="20">
			</br>

			<label style="font-size:17px;font-weight:bold;color:white;" for="email">Email:</label> 
			<input size="25" type="text" name = "email" id="email" placeholder = "Not Required" maxlength="255">
			</br>

			<h4 style="color:red;">* = REQUIRED</h4>
			<input class="submit" type="submit" value="Register!" />
		 </form>
	</div>
	<?php
		//TODO report errors to database
		$errors = 0;
		if(isset($_POST["zid"]) && isset($_POST["password"])) {
			$zid = strtoupper($_POST["zid"]);
			//Doing server side error checking just to be safe
			//ZID
			if(strlen($zid) != 8)
				$errors |= 1;

			if(!($errors & 1)) { //If it's not even the right size skip this
				if($zid[0] != 'Z' && $zid[0] != 'E')
					$errors |= 2;

				if(!is_numeric(substr($zid, 1,7)))
					$errors |= 4;	
			}

			if((isset($_POST["firstname"]) && is_numeric($_POST["firstname"])) || (isset($_POST["lastname"]) && is_numeric($_POST["lastname"])))
					$errors |= 8;

			if(isset($_POST["email"]) && strlen($_POST["email"]) != 0 && !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
					$errors |= 16;

			if($_POST["cpassword"] != $_POST["password"])
				$errors |= 64;

			if($errors != 0) {
				echo "<script>clienterror(" . $errors . "); </script>";
				return;
			}

			$hashpassword = password_hash($_POST["password"],PASSWORD_DEFAULT);
			//TODO check if ZID is already used
			if(!($inspon = $sqlcon->prepare("INSERT INTO MEMBERS (ZID,PASS,FNAME,LNAME,EMAIL,JOINDATE) VALUES (?,?,?,?,?,?)"))) {
				$errors |= 32;
				echo "<script>clienterror(" . $errors . "); </script>";
				return;
			}

			if($sqlcon->query("SELECT * FROM MEMBERS WHERE ZID = \"" . $zid . "\"")->num_rows > 0) {
				echo "<script>alert(\"ZID ALREADY REGISTERED!\"); </script>";
				return;
			}

			$inspon->bind_param("ssssss", $SETZID,$SETPASS,$SETFNAME,$SETLNAME,$SETEMAIL,$SETJOINDATE);

			$SETZID = $zid;
			$SETPASS = $hashpassword;
			$SETFNAME = $_POST['firstname'];
			$SETLNAME = $_POST['lastname'];
			$SETEMAIL = $_POST['email'];
			$SETJOINDATE = date("Y-m-d");

			$inspon->execute();

			echo "<script>alert(\"Successfully registered!\");</script>";
		}

	?>
</body>
