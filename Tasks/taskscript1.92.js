/*
author Steven Radomski
email radomskist@yahoo.com
Copyright (c) 2016, Steven Radomski 
*/

var tasklist = [];
var lookkey = [];
var ticketkey =  [];
var ticketlist = [];
var userinfo;

function majfilter(majselect) {
	if(majselect.value == "null") {
		majors = "cs bs me ee";
		majlist = majors.split(" ");
		for(i = 0; i < majlist.length; i++) {
			elements = document.getElementsByClassName(majlist[i]);
			for(j = 0; j < elements.length; j++)
				elements[j].style.display = 'block';
		}
		return;
	}
	majors = "cs bs me ee";
	majors = majors.replace(majselect.value,'');
	majlist = majors.split(" ");

	elements = document.getElementsByClassName(majselect.value);
	for(j = 0; j < elements.length; j++) {
		elements[j].style.display = 'block';
	}

	for(i = 0; i < majlist.length; i++) {
		elements = document.getElementsByClassName(majlist[i]);
		for(j = 0; j < elements.length; j++) {
			elements[j].style.display = 'none';
		}
	}
}


function check_year_event(year_id, event_id) {
	var year = document.getElementById(year_id).value;
	var event = document.getElementById(event_id).value;

	callPage('file.php?year='+year+'&'+'event=+'+event,document.getElementById(targetId));
}

function userinfo(info) {
	userinfo = info;
}

function toggletickets(curbutton) {
	if(curbutton.className == "current") {
		curbutton.className = "";
		ton = false;
	}
	else {
		curbutton.className = "current";
		ton = true;
	}

	gallery = document.getElementsByClassName("ticket");

	if(!ton)
		for (i = 0; i < gallery.length; i++)
			gallery[i].style.display = 'none';
	else
		for (i = 0; i < gallery.length; i++)
			gallery[i].style.display = 'block';
}

function toggleprior() {
	curbutton =  document.getElementsByName("PriorLi")[0];

	if(curbutton.className == "current") {
		curbutton.className = "";
		on = false;
	}
	else {
		curbutton.className = "current";
		on = true;
	}

	gallery = document.getElementsByClassName("nonprior");

	if(on)
		for (i = 0; i < gallery.length; i++)
			gallery[i].style.display = 'none';
	else
		for (i = 0; i < gallery.length; i++)
			gallery[i].style.display = 'block';
}

function toggleavaible() {
	curbutton =  document.getElementsByName("AvaibleLi")[0];

	if(curbutton.className == "current") {
		curbutton.className = "";
		aon = false;
	}
	else {
		curbutton.className = "current";
		aon = true;
	}

	gallery = document.getElementsByClassName("avaible");

	if(aon)
		for (i = 0; i < gallery.length; i++)
			gallery[i].style.display = 'none';
	else
		for (i = 0; i < gallery.length; i++)
			gallery[i].style.display = 'block';
}

function popuptaskid(inidload) {
	var element = document.getElementById('taskwin');

	idload = 0;
	for(i = 0; i < lookkey.length; i++)
		if(inidload == lookkey[i]) {
			idload = i;
			break;
		}

	element.innerHTML = "<h1 style=\"padding-bottom:0;margin-bottom:0;\">" + tasklist[idload]["FULLNAME"] + "</h1>";
	element.innerHTML += "<h2 style=\"padding:0;margin:0;color:#111;\">" + tasklist[idload]['SYSNAME'] + "</h2>";
	element.innerHTML += "<hr width=\"75%\">";
	element.innerHTML += "<p style=\"font-size:20px\">" + tasklist[idload]['DESCRIPT'] + "</p>";
	if(tasklist[idload]['DUEDATE'] != null) {
		//http://stackoverflow.com/questions/3224834/get-difference-between-2-dates-in-javascript
		var today = new Date();
		var enddate = new Date(tasklist[idload]['DUEDATE']);
		var diffDays = Math.ceil((enddate.getTime() - today.getTime()) / (1000 * 3600 * 24)); 

		var checkday = new Date(tasklist[idload]['CHECKDATE']);
		var checkdiff = Math.ceil((checkday.getTime() - today.getTime()) / (1000 * 3600 * 24));

		if(diffDays > 7)
			element.innerHTML += "<h2 style=\"color:#0D0;\">" + diffDays + " Days left</h2>";
		else if(diffDays > -1)
			element.innerHTML += "<h2 style=\"color:#DD0;\">" + diffDays + " Day(s) left</h2>";
		else
			element.innerHTML += "<h2 style=\"color:#D00;\">" + Math.abs(diffDays) + " Day(s) overdue!</h2>";
	}
	else
		element.innerHTML += "<h2 style=\"color:#0D0\">Non-Priority</h2>";

	/*Apply for task*/
	if((userinfo != -1) && ((tasklist[idload]['CONTRIBUTOR'] == null || checkdiff < 0) || (tasklist[idload]['DUEDATE'] != null && diffDays < 0))) {
		element.innerHTML += "<form name=\"appsub\" id=\"appsub\" method=\"post\" action=\"\">"
			+ "<input type=\"hidden\" name=\"taskappid\" value=\"" + tasklist[idload]['TASKID'] + "\">"
			+ "<button name=\"taskappsub\" type=\"submit\">Apply for Task</button>\n"
			+ "</form>";
	}

	/*Refresh task*/
	if((userinfo != -1) && tasklist[idload]['CONTRIBUTOR'] != null && (isNaN(checkday.getTime()) || checkdiff < 0) && (userinfo & 0x0004) != 0) {
		element.innerHTML += "<form name=\"taskref\" id=\"taskref\" method=\"post\" action=\"\">"
			+ "<input type=\"hidden\" name=\"taskrefid\" value=\"" + tasklist[idload]['TASKID'] + "\">"
			+ "<button name=\"taskrefsub\" type=\"submit\">Refresh Task</button>\n"
			+ "</form>";
	}

	/*Load task requests*/
	if((userinfo != -1) && (userinfo & 0x0004) && tasklist[idload]['FLAGS'] & 0x01) {
		formstr = "<form name=\"taskref\" id=\"taskref\" method=\"post\" action=\"\">"
			+ "<select style=\"font-size:15px\" name=\"RequestAccept\">";

		var parser = new DOMParser();
		xhtmlfile = new XMLHttpRequest();
		xhtmlfile.open("GET", 'projarray.php?TID=' + inidload, false);
		xhtmlfile.send();

		var projarray = JSON.parse(xhtmlfile.responseText);
		for(i = 0; i < projarray.length; i++) {
			formstr += "<option value=\"" + inidload + " " + projarray[i]["MID"]  + "\">" + projarray[i]["FNAME"] + " " + projarray[i]["LNAME"] + "</option>";
		}

		formstr += "</select>"
			+ "<button name=\"taskrefsub\" type=\"submit\">Approve Application</button>\n"
			+ "</form>";

		element.innerHTML += formstr;
	}

	/*Delete Task task*/
	if((userinfo != -1) &&  (userinfo & 0x0008) != 0) {
		element.innerHTML += "<form name=\"taskref\" id=\"taskdelete\" method=\"post\" action=\"\">"
			+ "<input type=\"hidden\" name=\"taskdeleteid\" value=\"" + tasklist[idload]['TASKID'] + "\">"
			+ "<button name=\"taskdeletesub\" type=\"submit\">Delete Task</button>\n"
			+ "</form>";
	}

	element.innerHTML += "<h3 style=\"margin:0;padding:0;\">Job Types:</h4>";

	if(tasklist[idload]['FIELDFLAG'] & 0x01)
		 element.innerHTML += "<p  style=\"margin:0;padding:0;\">Mechanical</p>";
	if(tasklist[idload]['FIELDFLAG'] & 0x02)
		 element.innerHTML += "<p  style=\"margin:0;padding:0;\">Electrical</p>";	
	if(tasklist[idload]['FIELDFLAG'] & 0x04)
		 element.innerHTML += "<p  style=\"margin:0;padding:0;\">Computer Science</p>";
	if(tasklist[idload]['FIELDFLAG'] & 0x08)
		 element.innerHTML += "<p  style=\"margin:0;padding:0;\">Business</p>";

	element.innerHTML += "<br>";
	element.innerHTML += "<span><a onclick=\"togglevis('taskwin')\" >Close[x]</a></span>";

	//TODO Load tags

	togglevis('taskwin');
}

ticket = false;
function ticketcreate() {
	if(ticket  == true) {
		togglevis('tickcreate');
		return;
	}

	var element = document.getElementById('tickcreate');
	element.style.height = element.style.height + 750;
	element.style.width = element.style.width + 575;
	element.innerHTML = "<h1 style=\"padding-bottom:0;margin-bottom:0;\">Create new Task!</h1>";

	var parser = new DOMParser();
	xhtmlfile = new XMLHttpRequest();
	xhtmlfile.open("GET", 'projarray.php', false);
	xhtmlfile.send();

	var projarray = JSON.parse(xhtmlfile.responseText);
	var liststring = "<form method=\"post\" action=\"\" style=\"position:relative;text-align:center;\">";

	/*select project*/
	liststring += "<label style=\"font-size:20px\" for=\"setproject\">What Project?</label>"
	liststring += "<select style=\"font-size:15px;display:inline;margin-left: auto;position:relative;\" name=\"setproject\" id=\"setproject\" onchange=\"showsystems(this)\">";

	for(i = 0; i < projarray.length; i++) {
		liststring += "<option value=\"" + projarray[i]  + "\">" + projarray[i] + "</option>";
	}
	liststring += "</select><br><br>";

	/*system*/
	liststring += "<label style=\"font-size:20px\" for=\"setsystem\">What System?</label>"
	liststring += "<select style=\"font-size:15px;display:inline;margin-left: auto;position:relative;\" name=\"setsystem\" id=\"setsystem\" hidden>";
	liststring += "</select><br><br>";

	/*what is the job*/
	liststring += "<label style=\"font-size:20px\" for=\"setproject\">What is the job (255 characters)?</label>"
	liststring += "<textarea style=\"font-size:15px;\" name=\"robotdesc\" cols=\"30\" rows=\"1\" maxlength=\"255\" required></textarea><br><br>"

	/*job type*/
	liststring += "<label style=\"font-size:20px;\" for=\"jobtype\">What job type (can select multiple)</label><br>"
	liststring += "<select name=\"jobtype[]\" multiple required style=\"all:unset;font-size:20px;background-color:grey;\">"
	liststring += "<option value=\"1\">Mechanical</option>"
	liststring += "<option value=\"2\">Electrical</option>"
	liststring += "<option value=\"4\">Computer</option>"
	liststring += "<option value=\"8\">Business</option>"
	liststring += "</select><br><br>"

	/*select due date*/
	liststring += "<label style=\"font-size:20px\" for=\"jobtype\">When should it be due by (can leave blank)</label>"
	liststring += "<input style=\"font-size:20px\" type=\"ticketduedate\" name=\"ticketduedate\" id=\"ticketduedate\" placeholder=\"YYYY-MM-DD\"/><br><br>"

	/*selectdependencies*/
	liststring += "<label style=\"font-size:20px\" for=\"jobtype\">Does it depend on a job? (can leave blank)</label><br>"
	liststring += "<select name=\"setdepends\" style=\"font-size:20px\">"
	liststring += "<option disabled selected value> -- select an option -- </option>"
 
	for(i = 0; i < tasklist.length; i++) {
		liststring += "<option value=\"" + tasklist[i]['DESCRIPT'] + "\">" + tasklist[i]['DESCRIPT'] + "</option>"
	}
	liststring += "</select><br><br>"

	liststring += "<input style=\"font-size:20px;display:inline;margin-left:auto;position:relative;margin-top:-50px;padding-top:-50px;\" type=\"submit\" value=\"Create Task!\">";
	liststring += "</form>";
	
	element.innerHTML += liststring;
	element.innerHTML += "<span><a onclick=\"togglevis('tickcreate')\" >Close[x]</a></span>";
	showsystems(projarray[0]);
	togglevis('tickcreate');
	ticket = true;
}

function showsystems(projname) {
	var parser = new DOMParser();
	var element = document.getElementById('setsystem');
	xhtmlfile = new XMLHttpRequest();
	if(projname.value != null)
		projstuff = projname.value;
	else
		projstuff = projname;

	xhtmlfile.open("GET", "../Projects/pages/" + projstuff + ".xhtml", false);
	xhtmlfile.send();
	doc = parser.parseFromString(xhtmlfile.responseText, "text/html");
	if(xhtmlfile.responseText == null) {
		alert("PROJECT FILE NOT FOUND</h2><h4>" + '../Projects/pages/' + projname.value  + ".xhtml</h4></li>");
		return;
	}

	element.style.visibility = "visible";
	element.style.display = "inline";
	syslist = doc.getElementsByTagName("sys");
	element.innerHTML = "";
	for(i = 0; i < syslist.length; i++) {
		element.innerHTML += "<option value=\"" + syslist[i].getAttribute("acro")  + "\">" + syslist[i].getAttribute("name") + "</option>";
	}
}


function loadtabs(projdir, inarray) {
		var parser = new DOMParser();
		xhtmlfile = new XMLHttpRequest();
		xhtmlfile.open("GET", projdir + '/pages/' + inarray['PROJNAME'] + ".xhtml", false);
		xhtmlfile.send();
		if(xhtmlfile.responseText == null) {
			document.write("<li><h2 style=\"color:#F00\">PROJECT FILE NOT FOUND</h2><h4>" + projdir + '/pages/' + inarray['PROJNAME'] + ".xhtml</h4></li>");
			return;
		}
		lookkey.push(inarray["TASKID"]);
		keyspot = lookkey.length - 1;
		tasklist.push(inarray);
		doc = parser.parseFromString(xhtmlfile.responseText, "text/html");

		jobtype = "";
		color = "#231f20";
		newr = 1;
		newg = 1;
		newb = 1;
		//Job is being done by YOU!
		if(inarray["FLAGS"] & 0x04) {
			jobtype += "taken ";
			newb += 2;
		}
		//Someoneelse is doing the job
		else if(inarray["FLAGS"] & 0x02) {
			jobtype += "avaible ";
		}
		else {
			jobtype += "taken ";
			newg += 2;
		}

		if(inarray['FIELDFLAG'] & 0x01)
			jobtype += "me ";
		if(inarray['FIELDFLAG'] & 0x02)
			jobtype += "ee ";
		if(inarray['FIELDFLAG'] & 0x04)
			jobtype += "cs ";
		if(inarray['FIELDFLAG'] & 0x08)
			jobtype += "bs ";


		//someone wants it
		if(inarray["FLAGS"] & 0x01) {
			jobtype += "taken requested ";
			newr = 4;
			newg = 1;
			if(inarray["FLAGS"] & 0x02)
				newg = 3;
		}

		if(inarray['DUEDATE'] == null) 
			jobtype += "nonprior ";
		else {
			jobtype += "prior "

			//http://stackoverflow.com/questions/3224834/get-difference-between-2-dates-in-javascript
			var today = new Date();
			var enddate = new Date(inarray['DUEDATE']);
			var diffDays = Math.ceil((enddate.getTime() - today.getTime()) / (1000 * 3600 * 24)); 

			var checkday = new Date(inarray['CHECKDATE']);
			var checkdiff = Math.ceil((checkday.getTime() - today.getTime()) / (1000 * 3600 * 24)); 

			//Job is stail
			if(inarray['CONTRIBUTOR'] != null && (checkdiff <= 0 || isNaN(checkday.getTime()))) {
				newr += 3;
				newg += 2;
				newb += 1;
			}
		}

		if(!(newr == 1 && newg == 1 && newb == 1)) {
			color = "#";
			color += newr;
			color += newg;
			color += newb;
		}

		syslist = doc.getElementsByTagName("sys");
		for(i = 0; i < syslist.length; i++) {
			if(syslist[i].getAttribute("acro") == inarray["SYSTEM"])
				break;
		}
		if(i == syslist.length) {
			document.write("<li><h2 style=\"color:#F00\">SYSTEM NOT FOUND " + inarray["SYSTEM"] + "</h2><h4>" + projdir + '/pages/' + inarray['PROJNAME'] + ".xhtml</h4></li>");
			return;
		}

		setdesc = inarray["DESCRIPT"];
		if(setdesc.length > 100)
			setdesc = setdesc.substr(0,75) + "...";
		document.write("<div name=\"galel\" class=\"" + jobtype + "\">");
		document.write("<li style=\"background-color:" + color + ";\"> <a onclick=\"javascript:popuptaskid('" + inarray["TASKID"] + "');\">");
		document.write("<img style=\"max-width:200;max-height:200;\" src=\"" + projdir  + "/systems/" + syslist[i].getAttribute("img") + "\" >");
		document.write("<h3 style=\"padding:0;margin:0;\">" + setdesc + "</h3>");
		document.write("<h4 style=\"color:#AAA;padding:0;margin:0;\">" + syslist[i].getAttribute("name") + "</h4>");
		document.write(doc.getElementsByTagName("acro")[0].textContent);
		tasklist[keyspot]["FULLNAME"] = doc.getElementsByTagName("acro")[0].textContent;
		tasklist[keyspot]["SYSNAME"] = syslist[i].getAttribute("name");

		document.write("<h4 ");
		if(inarray['DUEDATE'] != null) {
			if(diffDays > -1) {
				if(diffDays > 7)
					document.write("style=\"margin:0;padding:0;color:#0F0;\">" + diffDays + " Days left");
				else
					document.write("style=\"margin:0;padding:0;color:#FF0;\">" + diffDays + " Day(s) left");
			}
			else
				document.write("style=\"margin:0;padding:0;color:#F00;\">" + Math.abs(diffDays) + " Day(s) overdue!");

		}

		document.write("</h4></a></li></div>");

}

function loadtickets(projdir, inarray) {
		var parser = new DOMParser();
		xhtmlfile = new XMLHttpRequest();
		xhtmlfile.open("GET", projdir + '/pages/' + inarray['PROJNAME'] + ".xhtml", false);
		xhtmlfile.send();
		if(xhtmlfile.responseText == null) {
			document.write("<li><h2 style=\"color:#F00\">PROJECT FILE NOT FOUND</h2><h4>" + projdir + '/pages/' + inarray['PROJNAME'] + ".xhtml</h4></li>");
			return;
		}
		ticketkey.push(inarray["TICKID"]);
		keyspot = ticketkey.length - 1;
		ticketlist.push(inarray);
		doc = parser.parseFromString(xhtmlfile.responseText, "text/html");

		jobtype = "ticket ";
		color = "#111111";

		if(inarray['DUEDATE'] == null) 
			jobtype += "nonprior ";
		else {
			jobtype += "prior "

			//http://stackoverflow.com/questions/3224834/get-difference-between-2-dates-in-javascript
			var today = new Date();
			var enddate = new Date(inarray['DUEDATE']);
			var diffDays = Math.ceil((enddate.getTime() - today.getTime()) / (1000 * 3600 * 24)); 

			var checkday = new Date(inarray['CHECKDATE']);
			var checkdiff = Math.ceil((checkday.getTime() - today.getTime()) / (1000 * 3600 * 24)); 

			if(inarray['CONTRIBUTOR'] != null && checkdiff <= 0)
				color = "#430";
		}

		syslist = doc.getElementsByTagName("sys");
		for(i = 0; i < syslist.length; i++) {
			if(syslist[i].getAttribute("acro") == inarray["SYSTEM"])
				break;
		}
		if(i == syslist.length) {
			document.write("<li><h2 style=\"color:#F00\">SYSTEM NOT FOUND " + inarray["SYSTEM"] + "</h2><h4>" + projdir + '/pages/' + inarray['PROJNAME'] + ".xhtml</h4></li>");
			return;
		}


		document.write("<div style=\"display:none;\" name=\"galel\" class=\"" + jobtype + "\">");
		document.write("<li style=\"background-color:" + color + ";\"> <a onclick=\"javascript:popuptticketid('" + inarray["TICKID"] + "');\">");
		document.write("<img style=\"max-width:200;max-height:200;\" src=\"" + projdir  + "/systems/" + syslist[i].getAttribute("img") + "\" >");
		document.write("<h3 style=\"padding:0;margin:0;\">" + inarray["DESC"] + "</h3>");
		document.write("<h4 style=\"color:#AAA;padding:0;margin:0;\">" + syslist[i].getAttribute("name") + "</h4>");
		document.write(doc.getElementsByTagName("acro")[0].textContent);
		ticketlist[keyspot]["FULLNAME"] = doc.getElementsByTagName("acro")[0].textContent;
		ticketlist[keyspot]["SYSNAME"] = syslist[i].getAttribute("name");

		document.write("<h4 ");
		if(inarray['DUEDATE'] != null) {
			if(diffDays > -1) {
				if(diffDays > 7)
					document.write("style=\"margin:0;padding:0;color:#0F0;\">" + diffDays + " Days left");
				else
					document.write("style=\"margin:0;padding:0;color:#FF0;\">" + diffDays + " Day(s) left");
			}
			else
				document.write("style=\"margin:0;padding:0;color:#F00;\">" + Math.abs(diffDays) + " Day(s) overdue!");

		}

		document.write("</h4></a></li></div>");
}

function popuptticketid(inidload) {
	var element = document.getElementById('taskwin');

	idload = 0;
	for(i = 0; i < lookkey.length; i++)
		if(inidload == ticketkey[i]) {
			idload = i;
			break;
		}

	element.innerHTML = "<h1 style=\"padding-bottom:0;margin-bottom:0;\">" + ticketlist[idload]["FULLNAME"] + "</h1>";
	element.innerHTML += "<h2 style=\"padding:0;margin:0;color:#111;\">" + ticketlist[idload]['SYSNAME'] + "</h2>";
	element.innerHTML += "<hr width=\"75%\">";
	element.innerHTML += "<p style=\"font-size:20px\">" + ticketlist[idload]['DESC'] + "</p>";
	if(ticketlist[idload]['DUEDATE'] != null) {
		//http://stackoverflow.com/questions/3224834/get-difference-between-2-dates-in-javascript
		var today = new Date();
		var enddate = new Date(ticketlist[idload]['DUEDATE']);
		var diffDays = Math.ceil((enddate.getTime() - today.getTime()) / (1000 * 3600 * 24)); 

		var checkday = new Date(ticketlist[idload]['CHECKDATE']);
		var checkdiff = Math.ceil((checkday.getTime() - today.getTime()) / (1000 * 3600 * 24));

		if(diffDays > 7)
			element.innerHTML += "<h2 style=\"color:#0D0;\">" + diffDays + " Days left</h2>";
		else if(diffDays > -1)
			element.innerHTML += "<h2 style=\"color:#DD0;\">" + diffDays + " Day(s) left</h2>";
		else
			element.innerHTML += "<h2 style=\"color:#D00;\">" + Math.abs(diffDays) + " Day(s) overdue!</h2>";
	}
	else
		element.innerHTML += "<h2 style=\"color:#0D0\">Non-Priority</h2>";

	/*Approve task*/
	if(userinfo & 0x0010) {
		element.innerHTML += "<form name=\"taskprove\" id=\"appsub\" method=\"post\" action=\"\">"
			+ "<input type=\"hidden\" name=\"ticketapprove\" value=\"" + ticketlist[idload]['TICKID'] + "\">"
			+ "<button name=\"tickappsub\" type=\"submit\">Approve task</button>\n"
			+ "</form>";
	}

	/*Delete task*/
	if(userinfo & 0x0010) {
		element.innerHTML += "<form name=\"taskdel\" id=\"taskref\" method=\"post\" action=\"\">"
			+ "<input type=\"hidden\" name=\"ticketdelete\" value=\"" + ticketlist[idload]['TICKID'] + "\">"
			+ "<button style=\"background-color:#F44\" name=\"tikdelsub\" type=\"submit\">Delete Task</button>\n"
			+ "</form>";
	}

	element.innerHTML += "<h3 style=\"margin:0;padding:0;\">Job Types:</h4>";

	if(ticketlist[idload]['FIELD'] & 0x01)
		 element.innerHTML += "<p  style=\"margin:0;padding:0;\">Mechanical</p>";
	if(ticketlist[idload]['FIELD'] & 0x02)
		 element.innerHTML += "<p  style=\"margin:0;padding:0;\">Electrical</p>";
	if(ticketlist[idload]['FIELD'] & 0x04)
		 element.innerHTML += "<p  style=\"margin:0;padding:0;\">Computer Science</p>";
	if(ticketlist[idload]['FIELD'] & 0x08)
		 element.innerHTML += "<p  style=\"margin:0;padding:0;\">Business</p>";


	element.innerHTML += "<span><a onclick=\"togglevis('taskwin')\" >Close[x]</a></span>";

	//TODO Load tags

	togglevis('taskwin');
}
