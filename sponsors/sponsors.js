function listsponsors() {
	var parser = new DOMParser();
	xhtmlfile = new XMLHttpRequest();
	xhtmlfile.open("GET", 'sponsors.xml', false);
	xhtmlfile.send();
	doc = parser.parseFromString(xhtmlfile.responseText, "text/html");

	if(xhtmlfile.responseText == null) {
		alert("SPONSOR XML NOT FOUND!");
		return;
	}

	document.write("<br>");
	document.write("<br>");
	document.write("<hr width=\"80%\">");


	document.write("<h1 style=\"color:#CCC;padding:10px; padding-bottom:0px; margin-bottom:0px; font-size:100px;\">Platinum Sponsors:</h1><br>");
	sponsorslist = parsetier("platinum");

	document.write("<h1 style=\"color:#FD0;padding:10px; padding-bottom:0px; margin-bottom:0px; font-size:100px;\">Gold Sponsors:</h1><br>");
	sponsorslist = parsetier("gold");

	document.write("<h1 style=\"color:#AAA;padding:10px; padding-bottom:0px; margin-bottom:0px; font-size:100px;\">Silver Sponsors:</h1><br>");
	sponsorslist = parsetier("silver");

	document.write("<h1 style=\"color:#D80;padding:10px; padding-bottom:0px; margin-bottom:0px; font-size:100px;\">Bronze Sponsors:</h1><br>");
	sponsorslist = parsetier("bronze");

}

function parsetier(tiername) {
	curlist = doc.getElementsByTagName(tiername)[0];
	sponsorslist = curlist.getElementsByTagName("sponsor");
	
	for(i = 0; i < sponsorslist.length; i++)
		document.write("<a href='" + sponsorslist[i].getAttribute("website") + "'><img class='plat' src='" + sponsorslist[i].getAttribute("img") + "'></a>");
}


