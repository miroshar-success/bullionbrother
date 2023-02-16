document.addEventListener('DOMContentLoaded', function () {
	let adminBar = document.querySelector('#wpadminbar');
	let html = document.querySelector('html');
	let body = document.body;

	/**
	 * Return if condition not met
	 */
	if (adminBar === null) { return; }

	/**
	 * Add class for compatibility
	 */
	adminBar.classList.add('bottom-admin-toolbar');
	let adminBarHeight = adminBar.clientHeight + 'px';
	adminBar.style.setProperty("--bab-data-height", adminBarHeight, "");


	/**
	 * Add class on backend
	 */
	if (body.classList.contains('wp-admin')){
		html.classList.add('bottom-admin-toolbar');
	}

	/**
	 * Listen keyboard keydown press
	 */
	document.addEventListener('keydown', function (event) {
		let shiftKey = event.shiftKey;
		let eventKey = event.which;
		let arrowDownKey = 40;
		if (shiftKey && eventKey === arrowDownKey) {
			adminBar.classList.toggle('is-hidden');
		}
	})

	/**
	 * Fix tinyMCE bug
	 */
	function resetBar() {
		adminBar.css('top', 0);
	}
	if (typeof (tinyMCE) !== 'undefined') {
		tinyMCE.init({
			oninit: resetBar()
		});
	}
})