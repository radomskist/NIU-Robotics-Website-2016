<?php
//@author Steven Radomski
//@email radomskist@yahoo.com
//6-26-2016

/********************
* File Manager Class
*
* This gives you a list of specific files in a directory
* and an interface to add or remove new files.
*
********************/

class fileman {
	private $files = array();

	public function __construct($filedir ,array $filetypes = array()) {
		if(empty($filetypes))
			$this->files = glob($_SERVER['DOCUMENT_ROOT'] . "/" . $filedir);
		else  {
			foreach($filetypes as $curtype) {
				$files = glob($_SERVER['DOCUMENT_ROOT'] . "/" . $filedir . "/*" . $curtype);
				$this->files = array_merge($this->files, $files);
			}
		}
	}
	
	public function echolist() {
		echo "<table border=\"1\" style=\"width:50%;text-align:center;\"><tr><h4>";
		foreach($this->files as $curfile) {
			echo "<td>" . basename($curfile) . "</td>";
		}
		echo "</h4></tr></table>";
	}

	public function echoupload() {
		echo "<form action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\">";
		echo "<input type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\">";
		echo "<input type=\"submit\" value=\"Upload Image\" name=\"submit\">";
		echo "</form>";
	}
}

class imgman extends fileman {
	private $files = array();

	public function __construct($filedir ,array $filetypes = array()) {
		$filetypes = array(".png",".jpg",".gif");

		foreach($filetypes as $curtype) {
			$files = glob($_SERVER['DOCUMENT_ROOT'] . "/" . $filedir . "*" . $curtype);

			foreach($files as $curfile) {
				array_push($this->files, $filedir . "/" . basename($curfile));
			}
		}
	}
	
	public function echolist() {

		$i = 0;
		echo "<table border=\"1\" style=\"width:50%;text-align:center;\"><tr><h4>";
		foreach($this->files as $curfile) {
			if($i % 5 == 0)
				echo "</tr><tr>";

			echo "<td>";
			echo "<img src=\"http://localhost:8000/" . $curfile . "\" style=\"max-width:200px;max-height:200px;\"> <br>\n" . basename($curfile);
			echo "<br> <input type=\"submit\" value=\"Remove\" style=\"background-color:red;color:black;\">";

			//TODO program remove button
			echo "</td>";
			$i++;
		}
		echo "</h4></tr></table>";
	}

	public function echoupload() {
		echo "<form action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\">";
		echo "<input type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\">";
		echo "<input type=\"submit\" value=\"Upload Image\" name=\"submit\">";
		echo "</form>";
	}

}
?>
