<?php
//@author Steven Radomski
//@email radomskist@yahoo.com
//6-15-2016

/********************
* MySQLi wrapper class
*
* This is for the admin page
* of the niurobotics website
*
********************/

class sqleditor {

	private $editcolumns = array();
	private $columns = array();
	private $dname; //Display name
	private $tname; //Table name
	private $sqlc; //sqlconnection
	private $argtypes = "";

	public function __construct($setname, $setconnection, $settable, $dontdraw) {
		$this->tname = $settable;
		$this->sqlc = $setconnection;
		$this->dname = $setname;

		//Creating columns array
		//TODO CHECK IF THIS IS SLOW!
		$checkfield = $this->sqlc->query("SELECT * FROM " . $this->tname);
		if($checkfield == NULL)
			print_r("Table: " . $this->tname . " does not exist");

		while($finfo = $checkfield->fetch_field()) {
			array_push($this->columns, $finfo->name);

			if(!in_array($finfo->name, $dontdraw)) {
				$nullornot = (($finfo->flags & 1) ? true : false);
				array_push($this->editcolumns, array($finfo->name, $nullornot));
		
				if($finfo->type == 3) 
					$this->argtypes .= "i";		
				elseif($finfo->type == 253)
					$this->argtypes .= "s";	
				elseif($finfo->type == 10)
					$this->argtypes .= "s";
			}
		}
	}

	function echotable() {
		$tablelist = $this->sqlc ->query("SELECT * FROM " . $this->tname);
		if($tablelist == false) {
			echo "<h3 style=\"color:red\">TABLE ERROR</h3>"; 
			return;
		}?>
		<table border="1" style="width:50%;text-align:center;">
		<tr>
		<?php foreach($this->columns as $curcol)
			echo "<th>" . $curcol . "</th>";
 		?>
		</tr>

		<?php while($curvalue = $tablelist->fetch_assoc()) :
			echo "<tr>";
			foreach($this->columns as $curcol)
				echo "<td>" . $curvalue[$curcol] . "</td>";
			endwhile; 
		?>
		</table>
		<?php
		$tablelist->free_result();
	}

	function echoinsert() { ?>
		<form <?php echo "action=\"" . htmlspecialchars($_SERVER['PHP_SELF']) . "?form=" . $this->dname; ?>" method="POST">
		<?php foreach($this->editcolumns as $curcol){
			$echostr = "";
			if($curcol[1]) 
				$echostr = "<font style=\"color:red\">*</font>";

			$echostr .= $curcol[0] . "<input type=\"text\" name=\"" . $curcol[0] . "\" value=\"\"><br>";
			echo $echostr;
		}
		echo "<input type=\"submit\" value=\"Create new " . $this->dname . "\" name=\"" . $this->dname . "sub\">";?>
		</form>
		<?php
	}

	function inserttotable() {
		if($_GET['form'] != $this->dname)
			return;

		if($_SERVER['REQUEST_METHOD'] != "POST") {
			echo "<h1 style=\"color:red\">Not a post!</h1>";
			return;
		}
		//Building insertion function
		$argcolumns = "";
		$arguments = "";
		$arrayargs = array();

		$arrayargs[] = $this->argtypes;
		foreach($this->editcolumns as $curcol) {
			$argcolumns .= $curcol[0] . ",";
			$arguments .= "?" . ",";
			$arrayargs[] = &$_POST[$curcol[0]];
		}

		$insertsqlfunc = "INSERT INTO " . $this->tname . " (" . substr($argcolumns, 0, -1) . ") VALUES (" . substr($arguments, 0, -1) . ")";

		//foreach($this->editcolumns as $curcol)
		//	$insertsqlfunc->bind_param(":" . $curcol, $_POST[$curcol]);
		if(!($inspon = $this->sqlc->prepare($insertsqlfunc))) {
			echo "<h1 style=\"color:red\">MySQL function format error!</h1>" . $insertsqlfunc;
			return;
		}
		call_user_func_array(array($inspon, 'bind_param'), $arrayargs);

		$inspon->execute();
	}
}
?>
