$(document).ready(function()
{	
		$.localScroll({duration:800});
		
		$('#show_all_m, #show_all_w').animate({opacity:0},0);
	
		$('#filter_runway_m').click(function() {
			if($.browser.msie){
				
				$('.streetwear_m, .accessories_m, .clothing_m, .shoes_m').animate({opacity:0.1}, 200).css('color', 'gray');
				$('.runway_m').animate({opacity:1}, 200).css('color', 'black');
			}
			else{
				$('.streetwear_m, .accessories_m, .clothing_m, .shoes_m').animate({opacity:0.1}, 200).addClass('grey');
				$('.runway_m').animate({opacity:1}, 200).removeClass('grey');
			}
			
			$('#show_all_m').animate({opacity:1}, 300);
			
			//return false;
		});
		
		$('#filter_street_m').click(function() {
			if($.browser.msie){
				$('.runway_m, .accessories_m, .clothing_m, .shoes_m').animate({opacity:0.1}, 200).css('color', 'gray');
				$('.streetwear_m').animate({opacity:1}, 200).css('color', 'black');
			}
			else{
				$('.runway_m, .accessories_m, .clothing_m, .shoes_m').animate({opacity:0.1}, 200).addClass('grey');
				$('.streetwear_m').animate({opacity:1}, 200).removeClass('grey');
			}
			$('#show_all_m').animate({opacity:1}, 300);
			
			//return false;
		});
		
		$('#filter_acc_m').click(function() {
			if($.browser.msie){
				$('.runway_m, .streetwear_m, .clothing_m, .shoes_m').animate({opacity:0.1}, 200).css('color','grey');
				$('.accessories_m').animate({opacity:1}, 200).css('color','black');
			}
			else{
				$('.runway_m, .streetwear_m, .clothing_m, .shoes_m').animate({opacity:0.1}, 200).addClass('grey');
				$('.accessories_m').animate({opacity:1}, 200).removeClass('grey');
			}
			
			$('#show_all_m').animate({opacity:1}, 300);
			
			//return false;
		});
		
		$('#filter_clothing_m').click(function() {
			if($.browser.msie){
				$('.runway_m, .streetwear_m, .accessories_m, .shoes_m').animate({opacity:0.1}, 200).css('color','grey');
				$('.clothing_m').animate({opacity:1}, 200).css('color','black');
			}
			else{
				$('.runway_m, .streetwear_m, .accessories_m, .shoes_m').animate({opacity:0.1}, 200).addClass('grey');
				$('.clothing_m').animate({opacity:1}, 200).removeClass('grey');
			}
			
			$('#show_all_m').animate({opacity:1}, 300);
			
			//return false;
		});
		
		$('#filter_shoes_m').click(function() {
			if($.browser.msie)
			{
				$('.runway_m, .streetwear_m, .accessories_m, .clothing_m').animate({opacity:0.1}, 200).css('color','grey');
				$('.shoes_m').animate({opacity:1}, 200).css('color','black');
			}
			else{
				$('.runway_m, .streetwear_m, .accessories_m, .clothing_m').animate({opacity:0.1}, 200).addClass('grey');
				$('.shoes_m').animate({opacity:1}, 200).removeClass('grey');
			}
			
			$('#show_all_m').animate({opacity:1}, 300);
			
			//return false;
		});
		
		$('#show_all_m').click(function() {
			if($.browser.msie){
				$('.runway_m, .streetwear_m, .accessories_m, .clothing_m, .shoes_m').animate({opacity:1}, 200).css('color', 'black');
			}
			else{
				$('.runway_m, .streetwear_m, .accessories_m, .clothing_m, .shoes_m').animate({opacity:1}, 200);
			}
			$('#show_all_m').animate({opacity:0},300);
			 
			//return false;
		});
		
		//Womens Filters
		
		$('#filter_runway_w').click(function() {
			if($.browser.msie){
				$('.streetwear_w, .accessories_w, .clothing_w, .shoes_w').animate({opacity:0.1}, 200).css('color','grey');
				$('.runway_w').animate({opacity:1}, 200).css('color','black');
			}
			else{
				$('.streetwear_w, .accessories_w, .clothing_w, .shoes_w').animate({opacity:0.1}, 200).addClass('grey');
				$('.runway_w').animate({opacity:1}, 200).removeClass('grey');
			}
			
			$('#show_all_w').animate({opacity:1}, 300);
			
			//return false;
		});
		
		$('#filter_street_w').click(function() {
			if($.browser.msie){
				$('.runway_w, .accessories_w, .clothing_w, .shoes_w').animate({opacity:0.1}, 200).css('color','grey');
				$('.streetwear_w').animate({opacity:1}, 200).css('color','black');
			}
			else{
				$('.runway_w, .accessories_w, .clothing_w, .shoes_w').animate({opacity:0.1}, 200).addClass('grey');
				$('.streetwear_w').animate({opacity:1}, 200).removeClass('grey');
			}
			
			$('#show_all_w').animate({opacity:1}, 300);
			
			//return false;
		});
		
		$('#filter_acc_w').click(function() {
			if($.browser.msie){
				$('.runway_w, .streetwear_w, .clothing_w, .shoes_w').animate({opacity:0.1}, 200).css('color','grey');
				$('.accessories_w').animate({opacity:1}, 200).css('color','black');
			}
			else{
				$('.runway_w, .streetwear_w, .clothing_w, .shoes_w').animate({opacity:0.1}, 200).addClass('grey');
				$('.accessories_w').animate({opacity:1}, 200).removeClass('grey');
			}
			
			$('#show_all_w').animate({opacity:1}, 300);
			
			//return false;
		});
		
		$('#filter_clothing_w').click(function() {
			if($.browser.msie){
				$('.runway_w, .streetwear_w, .accessories_w, .shoes_w').animate({opacity:0.1}, 200).css('color','grey');
				$('.clothing_w').animate({opacity:1}, 200).css('color','black');
			}
			else{
				$('.runway_w, .streetwear_w, .accessories_w, .shoes_w').animate({opacity:0.1}, 200).addClass('grey');
				$('.clothing_w').animate({opacity:1}, 200).removeClass('grey');
			}
			
			$('#show_all_w').animate({opacity:1}, 300);
			
			//return false;
		});
		
		$('#filter_shoes_w').click(function() {
			if($.browser.msie){
				$('.runway_w, .streetwear_w, .accessories_w, .clothing_w').animate({opacity:0.1}, 200).css('color','grey');
				$('.shoes_w').animate({opacity:1}, 200).css('color','black');
			}
			else{
				$('.runway_w, .streetwear_w, .accessories_w, .clothing_w').animate({opacity:0.1}, 200).addClass('grey');
				$('.shoes_w').animate({opacity:1}, 200).removeClass('grey');
			}
			
			$('#show_all_w').animate({opacity:1}, 300);
			
			//return false;
		});
		
		$('#show_all_w').click(function() {
			if($.browser.msie){
				$('.runway_w, .streetwear_w, .accessories_w, .clothing_w, .shoes_w').css('color','black'); 
			}
			else{
				$('.runway_w, .streetwear_w, .accessories_w, .clothing_w, .shoes_w').animate({opacity:1}, 200); 
			}
			$('#show_all_w').animate({opacity:0},300);
			
			//return false;
		});
		
});