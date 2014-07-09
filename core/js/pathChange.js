function detectHistorySupport() {
	"use strict";
	return !!(window.history && history.pushState);
}

function pathChange(path, title) {	
	"use strict";
	title = (title==null) ? "" : title;
	
	// if there is history support, use it
	if (detectHistorySupport()) {
		// change state and report that it worked
		if(window.history.pushState) {
			window.history.pushState("", title, path);
		} else {
			window.history.replaceState("", title, path);
		}		
		return true;

	// otherwise, report that the pathchange failed
	} else {
		return false;
		
	}
}