function addCKEditor(id) {
	"use strict";
	
	if (typeof CKEDITOR !== 'undefined') {
		CKEDITOR.replace(id, {
			//toolbar : 'PhiloToolbar',
			uiColor : '#cccccc',
			allowedContent: 'a[target,!href]; p{text-align}; br; h1; h2; h3; h4; h5; h6; address; blockquote; ul; li; ol; strong; em; div; script;'
		});
	}
}