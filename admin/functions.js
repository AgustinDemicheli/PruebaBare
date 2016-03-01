function setDivHeight() {
	if(parseInt(navigator.appVersion) > 3) {
		if(window.innerHeight) {
			h	= window.innerHeight;
		}else if (document.documentElement && document.documentElement.clientHeight) {
			h	= document.documentElement.clientHeight;
		}else if (document.body) {
			h	= document.body.clientHeight;
		}

		document.getElementById("container").style.height	= h + "px";
		document.getElementById("iframe").height		= h + "px";
	}
}
