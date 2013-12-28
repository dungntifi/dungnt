$.fn.image = function(src){
	
	return this.each(function(){
		var i = new Image();
        i.src = src;
        i.onload = function(){
        	this.src = src;
        };
        i.onerror = function(){
        	//alert('not existed');
        };
        //this.appendChild(i);
    });
}



/*$(document).ready(function(){
	
	$('.show_back').each(function(){

		var newSRC = $(this).attr('src').replace('1_3.jpg', '2_3.jpg');
		
		
		var imageElement = $(this); 
		$.ajax({
		    url: newSRC,
		    type:'GET',
		    success: function(transport)
		    {
				//alert('success');
		    },
		    error: function()
		    {      alert('fail');
				$(imageElement).removeClass('show_back');
	        },
		    
		    complete: function(xhr,textStatus)
		    {
	        	alert(textStatus);
				//alert(xhr.status );
			
	        	if(xhr.status == 404)
	        	{
	        		alert('failed');
	        		
	        	}
	        }
		
		    
		});
		
		
		

	});
});*/
var lastSwitched = null;
function reverseAll()
{
	$.each($('.show_back'), function(index, value) { if( value.src != lastSwitched ) value.src = value.src.replace('2_3.jpg', '1_3.jpg'); });
}
$(document).ready(function()
{
	var temp1 = document.URL.split("http://");
        //alert(temp1[1]);
        var pixelRatio = !!window.devicePixelRatio ? window.devicePixelRatio : 1;
        //alert(pixelRatio);
        if (pixelRatio > 10) {
        $('img').each(function() {

            // Very naive replacement that assumes no dots in file names.
            if($(this).attr('src').indexOf('1_3.jpg') != -1)
            {    
                var temp = $(this).attr('src').split("http://");
                var temp1 = temp[1].split("/");
                var temp2 = temp1[6].split("_");
                var sku = temp2[0];
                
                if(sku.charAt(0)!= '0' && sku.charAt(0)!= '1'  )$(this).attr('src', "http://"+temp1[0]+"/"+temp1[1]+"/"+temp1[2]+"/"+temp1[3]+"/"+temp1[4]+"/"+"4/"+sku+"_1_4.jpg");
                else $(this).attr('src', "http://"+temp1[0]+"/"+temp1[1]+"/"+temp1[2]+"/"+temp1[3]+"/"+temp1[4]+"/"+"2/"+sku+"_1_2.jpg"); 
               
            }        
            });     
    }
        setInterval("reverseAll()",500);
	$('.show_back').hover(
	  function () { 
		  if($(this).attr('src').indexOf('1_3.jpg') != -1)
                       var newSRC = $(this).attr('src').replace('1_3.jpg', '2_3.jpg');
		  else if($(this).attr('src').indexOf('1_4.jpg') != -1)
                       var newSRC = $(this).attr('src').replace('1_4.jpg', '2_4.jpg'); 
                  else var newSRC = $(this).attr('src').replace('1_2.jpg', '2_2.jpg');
                  var imageElement = $(this); 
		  var i = new Image();
		  i.src = newSRC;
		  i.onload = function(){
			  $(imageElement).attr('src', newSRC);
		  };
		  i.onerror = function(){
			  //alert('not existed');
		  };
		  //$(this).attr('src', $(this).attr('src').replace('2_3.jpg', '1_3.jpg'));
		  lastSwitched = newSRC;
		  //$(this).attr('src', $(this).attr('src').replace('1_3.jpg', '2_3.jpg'));
	  }, 
	  function () {
		lastSwitched = null;
		if($(this).attr('src').indexOf('2_3.jpg') != -1)
                         $(this).attr('src', $(this).attr('src').replace('2_3.jpg', '1_3.jpg'));
                else if($(this).attr('src').indexOf('2_4.jpg') != -1) 
                        $(this).attr('src', $(this).attr('src').replace('2_4.jpg', '1_4.jpg'));
                else    $(this).attr('src', $(this).attr('src').replace('2_2.jpg', '1_2.jpg')); 
	  }
	);
	
	$('.mini_item_img').hover(
	  function () {
		
		  var newSRC = $(this).attr('src').replace('1_3.jpg', '2_3.jpg');
		  var imageElement = $(this); 
		  var i = new Image();
		  i.src = newSRC;
		  i.onload = function(){
			  $(imageElement).attr('src', newSRC);
		  };
		  i.onerror = function(){
			  //alert('not existed');
		  };
		  
		  //$(this).attr('src', $(this).attr('src').replace('1_3.jpg', '2_3.jpg'));
	  }, 
	  function () {
		$(this).attr('src', $(this).attr('src').replace('2_3.jpg', '1_3.jpg'));
	  }
	);

});
