$(document).ready(function(){
	
	$('.search').focus(function() {
		$(this).val("");
	});
	
	$('.search').blur(function() {
		$(this).val( $(this).attr("name") );
	});
				
});