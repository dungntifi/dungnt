$(document).ready(function() {
        var pixelRatio = !!window.devicePixelRatio ? window.devicePixelRatio : 1;
        //alert(pixelRatio);
        if (pixelRatio > 10) {
        $('.main_logo').each(function() {
            
                        $(this).attr('src', $(this).attr('src').replace("logo","logo_2x"));       
                    }); 
                    
        $('.social_btn').each(function() {
            
                        $(this).attr('src', $(this).attr('src').replace("sm","sm_2x"));       
                    });
                    
        $('.freeShippingText').each(function() {
            
                        $(this).attr('src', $(this).attr('src').replace("Type","Type_2x"));       
                    }); 
    }
	var os=navigator.platform;
	if(os.indexOf('Win')!=-1)
		$('html').addClass('winOS');
	var br = navigator.userAgent;
	if(br.indexOf('MSIE 8.0')!=-1)
		$('body').addClass('ie8');
	else if(br.indexOf('MSIE 7.0')!=-1)
		$('body').addClass('ie7');
	else if(br.indexOf('MSIE 6.0')!=-1)
		$('body').addClass('ie6');
});







