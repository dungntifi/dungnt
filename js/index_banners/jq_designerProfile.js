$(document).ready(function(){
	$('#profile_button').click(function() {
	$('section.profile_body').animate({
		height: 'toggle',
		opacity: 'toggle'
	}, 275);
	return false;
	});
});