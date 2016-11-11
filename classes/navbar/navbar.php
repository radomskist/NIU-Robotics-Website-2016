<?php
	echo "<link rel=\"stylesheet\" href=\"/classes/navbar/navbar.css\"/>";

	/*
	author Steven Radomski
	email radomskist@yahoo.com
	Copyright (c) 2016, Steven Radomski 
	*/
	$ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/niuweb.ini.php');
	$sqlcon = new mysqli($ini['servername'], $ini['dblog'], $ini['dbpass'], $ini['dbname']);

	include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/popup/popup.php");
	$loginpopup = new popupform("niurnavbar");
	if(isset($_POST['username'])) {
		$zid = strtoupper($_POST["username"]);
		if(strlen($zid) != 8 || ($zid[0] != 'Z' && $zid[0] != 'E') || !is_numeric(substr($zid, 1,7)))
			echo "<script>alert(\"Invalid login\"); </script>";
		else {
			$searchresult = $sqlcon->query("SELECT * FROM MEMBERS WHERE ZID = \"" . $zid . "\"");
			if($searchresult->num_rows == 0)
				echo "<script>alert(\"Invalid login\"); </script>";
			else {
				$searcharray = $searchresult->fetch_assoc();
				if(password_verify($_POST['password'], trim($searcharray['PASS']))) {
					$_SESSION['username'] = $zid;
					$_SESSION['upriv'] = $searcharray['PRIVILEGES'];
				}
				else
					echo "<script>alert(\"Invalid login\"); </script>";
			}
		}
		//$_SESSION['upriv'] = 0x0001;
	}
	else if($_POST['logoutvalue']) {
		session_unset();
		session_destroy(); 

	}

	function echonavbar($num) { 
		global $ini; ?>
		<div id="niurnavbar"><b>
		<a href="/About"><icon/></a>

		<div id="MeetingTimes">
			<?php echo "<h2>" . $ini['building'] . " Room: " . $ini['room'] . "</h2>\n";
				echo "<h3>";
				$times ="";

				for($i = 0; $i < count($ini['date']); $i++) {
					$times .= " <b>" . $ini['date'][$i] . ":</b> " . $ini['time'][$i];
					
				}
				echo "<br>" . $times;
			?>
			</h3>
		</div>
		<ul>

		<?php
		if(!isset($ini))
			$ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/niuweb.ini.php');

		//TODO make this a loop that loads variables from the ini file
		$inter=1;
		echo "<li><a ";
		if($inter == $num)
			echo "class=\"current\" ";
 		echo "href=\"/About\">About</a></li>\n";

		$inter++;
		echo "\t\t<li><a ";
		if($inter == $num)
			echo "class=\"current\" ";
		echo " href=\"/sponsors\">Sponsorship</a></li>\n"; 


		$inter++;
		echo "\t\t<li><a ";
		if($inter == $num)
			echo "class=\"current\" ";
 		echo "href=" . $ini['taskdir'] . ">" . $ini['taskname'] . "</a></li>\n";
	
		$inter++;
		echo "\t\t<li><a ";
		if($inter == $num)
			echo "class=\"current\" ";
		echo "href=" . $ini['projdir'] . ">" . $ini['projname'] . "</a></li>\n";


		echo "\t\t<li class=\"login\"><a href=\"javascript:togglevis('loginnavbar')\">";
		if($_SESSION['username']) {
			echo $_SESSION['username'];
		}
		else {
			echo "Login";
		}
		echo "</a></li>\n"; ?>

		</ul></b>
		</div>
	<?php }

	function echofooter() { ?>
		<div id = niurnavbar>
		<footer style="color:#888; text-align: right;">
			<a href="http://armortechs.com/">
			<b>Hosted By:</b>
			<img style="float:right;margin-right:10px;margin-left:5px;margin-top:1px;" src="../sponsors/ArmorTech.png" width=70 height=18/>
			</a>
		</footer>
		</div>

	<?php } 

	//LOG IN WINDOW============
	?>
	<div class="popupwin" id="loginnavbar">
	<?php if($_SESSION['username']) { ?>
		<h1><?php echo $_SESSION['username']; ?> </h1>
		<?php if($_SESSION['upriv'] & 0x0001): ?>
			<input class="Login" type=button onClick="location.href='/reg'" value="Register Members">
		<?php endif ?>
		<?php if($_SESSION['upriv'] & 0x8000): ?>
			<input class="Login" type=button onClick="location.href='/admin'" value="Admin Page">
		<?php endif ?>
		<input class="Calendar" type=button onClick="location.href='/Calendar'" value="View Calendar">

		<form action="" method="post">
			<input type="hidden" name="logoutvalue" value="logout"/>  
			<button name="logout" type="submit">Logout</button>
		</form>
	<?php } else {?>
			<h1>LOG IN</h1>
			   <form action = "<?php echo $_SERVER['REQUEST_URI'];?>" method = "post">
					<label style="font-size:17px;font-weight:bold;padding-left:58px;" for="username">Zid:</label> <input size="25" type="text" name = "username" placeholder = "Username" required><br>
					<label style="font-size:17px;font-weight:bold;" for="password">Password:</label> <input size="25" type="password" name = "password" placeholder = "Password" required>
					<button name = "login" type="submit">Login</button>
				 </form>
			</br>
			<hr/>
			<h2 style="margin-bottom:-10px;padding-bottom:0px">Registration</h2>
			<p>Please attend a Robotics Club meeting to register. <br><br>In the meantime, you can fill out this form to help us find out where you fit in.</p>
			<button <?php echo "onclick=\"location.href='" .$ini['intform'] . "'\""; ?> >Interest Form</button>
	<?php } ?>

	<span><a onclick="togglevis('loginnavbar')" >Close[x]</a></span>
	</div>


