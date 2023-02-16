function addFormField() {
	var id = jQuery('#id').val();

	if (id > 5) {
		alert( "You've reach the limit of the free version. Consider buying the pro version. It's less than $10.");
	} else {
		jQuery("#far_itemlist").append(
		
			"<li id ='row" + id + "'>" +

				"<div style='float: left;'>" +

					"<div style='float: left;'>" +
					"<label for='farfind" + id + "'>Find:</label>" +
					"<br />" +
					"<textarea class='left' name='farfind["+ id +"]' id='farfind" + id + "'></textarea>" +
					"</div>" +

					"<div style='float: left;'>" +
					"<label for='farreplace" + id + "'>Replace:</label>" +
					"<br />" +
					"<textarea class='left' name='farreplace["+ id +"]' id='farreplace" + id + "'></textarea>" +
					"</div>" +

				"</div>" +

				"<div style='float: left;'>" +

					"<label class='side-label' for='farregex" + id + "'>RegEx?:</label>" +
					"<input class='checkbox' type='checkbox' name='farregex[" + id + "]' id='farregex" + id +"' />" +
					"&nbsp;&nbsp;" +
					"<label class='side-label-long' for='faradmin" + id + "'>Admin:&nbsp;</label>" +
					"<input disabled='disabled' class='checkbox' type='checkbox' name='faradmin[" + id + "]' id='faradmin" + id + "' />" +
					"&nbsp;&nbsp;" +
					"<label class='side-label-long' for='farcaseinsensitive" + id + "'>Ignore Case:&nbsp;</label>" +
					"<input disabled='disabled' class='checkbox' type='checkbox' name='farcaseinsensitive[" + id + "]' id='farcaseinsensitive" + id + "' />" +
					"<br />" +

					"<label class='side-label' for='farposttype'>Post Type:</label>" +
					"<select disabled='disabled' name='farposttype[" + id + "]' id='farposttype" + id + "'><option value='any'>any</option></select>'" +
					"<br />" +

					"<label class='side-label' for='farquerystring" + id + "'>Querystring:</label>" +
					"<input disabled='disabled' class='textbox' type='text' name='farquerystring[" + id + "]' id='farquerystring" + id +"' value='pro version only' />" +
					"<br />" +

					"<label class='side-label' for='farreferral" + id + "'>Referrer:</label>" +
					"<input disabled='disabled' class='textbox' type='text' name='farreferrer[" + id + "]' id='farreferrer" + id +"' value='pro version only' />" +
					"<br />" +

					"<label class='side-label' for='faruseragent" + id + "'>User Agent:</label>" +
					"<input disabled='disabled' class='textbox' type='text' name='faruseragent[" + id + "]' id='faruseragent" + id +"' value='pro version only' />" +
					"<br />" +

				"</div>" +

				"<div>" +
					"<input disabled='disabled' style='width: 615px;' type='text' name='fardescription[" + id + "]' id='fardescription" + id + "' value='pro version only' />" +
					"<input style='margin-right: 9px' type='button' class='button right remove' value='Remove' onClick='removeFormField(\"#row" + id + "\"); return false;' />\n" +
				"</div>" +

			"</li>");

		id = (id - 1) + 2;
		document.getElementById("id").value = id;
		jQuery('html, body').animate( {	scrollTop: jQuery("#row"+(id-1)).offset().top }, 1000);
	}
}

function removeFormField(id) {
	jQuery(id).remove();
}

jQuery(function() {
	jQuery( "#far_itemlist" ).sortable();
});