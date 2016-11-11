function togglevis(visname) {
	/*
	author Steven Radomski
	email radomskist@yahoo.com
	Copyright (c) 2016, Steven Radomski 
	*/
	var element = document.getElementById(visname);
	if(element.style.display  != 'block') {
		element.style.left = '50%'
		element.style.top = '50%'
		element.style.display = 'block';
	}
	else
		element.style.display = 'none';
}
