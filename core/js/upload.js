var uploader_id = "";
function uploadFileSelected(id, extensions) {
	"use strict";
	uploader_id = id;
	var file = document.getElementById(id).files[0];
	$('#upload_details').show();
	if (file) {
		var file_parts = file.name.split('.');
		var extension = file_parts[file_parts.length-1].toLowerCase();

		if (extensions===null) {
			extensions = [];
		} else {
			extensions = (typeof extensions==='object') ? extensions : new Array(extensions);
		}
		if (extensions.indexOf(extension)!==-1) {		
			var fileSize = 0;
			if (file.size > 1024 * 1024) {
				fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
			} else {
				fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';
			}
			$('#fileName').html('<strong>Name</strong>: ' + file.name);
			$('#fileSize').html('<strong>Size</strong>: ' + fileSize);
			$('#fileType').html('<strong>Type</strong>: ' + file.type);
		} else {
			$('#fileName').html('');
			$('#fileSize').html('');
			$('#fileType').html('');
			alert('That file type is not allowed. Please choose one of the following: '+extensions.join(' / ').toUpperCase());
		}			
	}
}

function uploadFile(target, callback) {
	"use strict";
	var fd = new FormData();
	
	fd.append('file', document.getElementById(uploader_id).files[0]);
	fd.append('target', target);
	fd.append('function', 'upload_file');
	
	if (callback===null) { callback = uploadComplete; }
	
	var xhr = new XMLHttpRequest();
	xhr.upload.addEventListener("progress", uploadProgress, false);
	xhr.addEventListener("load", callback, false);
	xhr.addEventListener("error", uploadFailed, false);
	xhr.addEventListener("abort", uploadCanceled, false);
	xhr.open("POST", BASE+"/lib/functions/upload_file.php");
	xhr.send(fd);
}

function uploadProgress(evt) {
	"use strict";
	if (evt.lengthComputable) {
		var percentComplete = Math.round(evt.loaded * 100 / evt.total);
		document.getElementById('progressNumber').innerHTML = percentComplete.toString() + '%';
	} else {
		document.getElementById('progressNumber').innerHTML = 'unable to compute';
	}
}

function uploadComplete(evt) {
	"use strict";
	if (evt) {
		$('#audiofile').val(evt.target.responseText);
	}
}

function uploadFailed(evt) {
	"use strict";
	alert("There was an error attempting to upload the file.");
}

function uploadCanceled(evt) {
	"use strict";
	alert("The upload has been canceled by the user or the browser dropped the connection.");
}