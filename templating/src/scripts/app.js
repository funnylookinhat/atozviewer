// app.js

$(function() {
	// do your worst!
	
	$(document).foundation({
		equalizer : {
			equalize_on_stack: true
		}
	});

	$('.keyvalue').each(function () {
		var w = 0;
		$('> ul > li > .name', $(this)).each(function () {
			if( $(this).width() > w ) {
				w = $(this).width();
			}
		});
		$('> ul > li > .name', $(this)).width(w);
	});
});