import {
	notify_once,
	l,
	filter_url,
	$db,
	btn_spinner,
	hdKey
} from './../parts.min.js?v=1bc';
import {
	Screen
} from './../current_screen.min.js?v=2';

import {
	Loader
} from './../loader.min.js?v=2';

$(function () {
	let aboutDescValue;

	ClassicEditor
		.create(document.querySelector('#editor'))
		.then(editor => {
			aboutDescValue = editor;
		})
		.catch(error => {
			console.error(error);
		});
})
