$(document).ready(function(){
	
	
	
	
	
/*	
	$('.profile_button').click(function(){
		
		if($('.profile').is(':hidden') == true) {
			$('.profile_button').addClass('on');
			$('.profile').slideDown(300);
			$('.profile_button').html($('.profile_button').html().replace('▼','▲'));
		}
		else {
			$('.profile_button').removeClass('on');
			$('.profile').slideUp(300);
			$('.profile_button').html($('.profile_button').html().replace('▲','▼'));
		}
	});
	
*/
	
	
	$('.cp_menu_cat_button').click(function(){
		if($(this).next().is(':hidden') == true) {
			$('.cp_menu_cat_panel').slideUp(300);
			$(this).next().slideDown(300);
		}
	});

	
	$('.cp_menu_designers_button').click(function()
	{		
		if($('.cp_menu_designers_panel').is(':hidden') == true) {
			$('.cp_menu_designers_panel').slideDown(600);
			$('.cp_menu_designers_button').html($('.cp_menu_designers_button').html().replace('▼','▲'));
		}
		/*else {
			$('.cp_menu_designers_panel').slideUp(600);
			$('.cp_menu_designers_button').html($('.cp_menu_designers_button').html().replace('▲','▼'));
		}*/
	});
	
	$('.cp_menu_size_button').click(function(){
		
		if($('.cp_menu_size_panel').is(':hidden') == true) {
			$('.cp_menu_size_panel').slideDown(300);
			$('.cp_menu_size_button').html($('.cp_menu_size_button').html().replace('▼','▲'));
		}
		else {
			$('.cp_menu_size_panel').slideUp(300);
			$('.cp_menu_size_button').html($('.cp_menu_size_button').html().replace('▲','▼'));
		}
	});
	
	$('.cp_menu_sort_button').click(function(){
		
		if($('.cp_menu_sort_panel').is(':hidden') == true) {
			$('.cp_menu_sort_panel').slideDown(300);
			$('.cp_menu_sort_button').html($('.cp_menu_sort_button').html().replace('▼','▲'));
		}
		else {
			$('.cp_menu_sort_panel').slideUp(300);
			$('.cp_menu_sort_button').html($('.cp_menu_sort_button').html().replace('▲','▼'));
		}
	});


	$('.cp_menu_designers_button').click();
	
});