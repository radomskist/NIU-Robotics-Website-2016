<?php
	//Setting cookies
	//1209600 = two weeks
	ini_set('session.gc_maxlifetime', 1209600); //Client side
	session_set_cookie_params(1209600); //Server side
	session_start();
	include_once("../classes/navbar/navbar.php");
?>

<html>
	<?php echonavbar(0); ?>
	<style type="text/css">
	body {
		/*Background image*/
		background-image: url(../DefaultBackground.png);
		background-size: cover;
	}	
	</style>
	<div style="text-align:center">
		<iframe src="https://calendar.google.com/calendar/embed?src=niurobotics%40gmail.com&ctz=America/Chicago" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
	</div>
	<?php echofooter(); ?>
</html>
