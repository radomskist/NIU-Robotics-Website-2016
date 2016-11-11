/*
author Steven Radomski
email radomskist@yahoo.com
Copyright (c) 2016, Steven Radomski 
*/

function loadtabs(year, projdir, tablist, typelist) {
	var parser = new DOMParser()
	xhtmlfile = new XMLHttpRequest();

	for(i = 0;i < tablist.length; i++) {
		document.write('<li><a href=\"' + projdir + '?robot=' + tablist[i] + '&year=' + year + '\">');
		xhtmlfile.open("GET", projdir + '/pages/' + tablist[i] + ".xhtml", false);
		xhtmlfile.send();
		//TODO If fails print error
		doc = parser.parseFromString(xhtmlfile.responseText, "text/html");
		document.write("<img src=" + projdir  + "/pages/" + tablist[i] + ".jpg >");
		document.write("<h2>" + doc.getElementsByTagName("acro")[0].textContent + "</h2>");
		document.write("<b>" + doc.getElementsByTagName("name")[0].textContent + "</b> <br>");

		document.write(typelist[i]);
		document.write("</a></li>");
	}
}



function loadpage(year, projdir, projname) {
	var parser = new DOMParser()
	xhtmlfile = new XMLHttpRequest();
	xhtmlfile.open("GET", projdir + '/pages/' + projname + ".xhtml", false);
	xhtmlfile.send();

	//TODO print error on fail
	doc = parser.parseFromString(xhtmlfile.responseText, "text/html");

	document.write("<h1 style=\"color:#BBB;text-align:center\">" +  doc.getElementsByTagName("name")[0].textContent + "</h1>");
	document.write("<img src=" + projdir  + "/pages/" + projname + ".jpg >");
	document.write("<p>" + doc.getElementsByTagName("text")[0].innerHTML + "</p>");
	document.write("<h3 style=\"color:#BBB;text-align:center;clear:both;\">Contributors</h3>");

}
