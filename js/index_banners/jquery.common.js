$(function() 
{
	
	$('#searchbox').bind('keypress', function(e) { 
        if(e.keyCode==13){ 
		   	window.location.replace($('#searchURL').val() + '?terms=' + $('#searchbox').val());
        } 
    }); 
	  
});


/*
function sortProducts( sortingType )
{
	var url = '?do=setSortingType';
	var noBrowsing = false;
	if( location.href.indexOf("/men")==-1 || location.href.indexOf("/women")==-1 ) 
	{
		url = $('#searchURL').val() + url;
		noBrowsing = true;
	}
	jQuery.ajax( 
	{
		url : url,
		type : "POST",
		data : 
		{
			sortingType: sortingType 
		}, 
		dataType : "xml",
		success : function(xml) 
		{			
			if(jQuery("success", xml).text() == "0") 
			{
				return;
			}
			else
			{
				if( noBrowsing ) location.href = jQuery("message", xml).text();
				else location.reload( true );
			}
		}
		
	});	
}
*/