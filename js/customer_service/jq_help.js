//help javascript file
//alert('foo');
var scrollPosition;
jQuery(function()
{
	setScrollPosition();
});

function floatIndex( offset )
{
	var defaultTop = 0;
	if( offset )  defaultTop = Math.min(offset + 141, 177);
	else defaultTop = 121;
	
	temp = Math.max(scrollPosition, defaultTop);
	jQuery("#helpIndex").stop().css({top: temp + 'px'});
}

function setScrollPosition()
{
	scrollPosition = jQuery(window).scrollTop();
	floatIndex();
	jQuery(window).scroll(function() 
	{ 
		scrollPosition = jQuery(window).scrollTop();
		floatIndex();
	});
	jQuery(window).resize(function() 
	{
		scrollPosition = jQuery(window).scrollTop();
		floatIndex();
	});
}
jQuery(function() 
{
	jQuery('#submitQuery').click(function() 
	{
		jQuery(".addForm").each(function() { jQuery("#" + this.id).removeClass('error'); });

		var name 		= jQuery('#userName').val();				
		var email 		= jQuery('#userEmail').val();				
		var subject 	= jQuery('#userSubject').val();				
		var department 	= jQuery('#requestedDepartment').val(); 	
		var message 	= jQuery('#userQuery').val();				
		var errorMessage= "";
		var offset		= 36;
		
		if(name == '')
		{
			jQuery("#userName").addClass('error');
			if( errorMessage ) errorMessage += ", ";
			errorMessage += jQuery('#errorNameMissed').html();
			//offset += 18;
		}
		if(email == '' )
		{
			jQuery("#userEmail").addClass('error');
			if( errorMessage ) errorMessage += ", ";
			errorMessage += jQuery('#errorEmailMissed').html();
			//offset += 18;
		}
		if(subject == '' )
		{
			jQuery("#userSubject").addClass('error');
			if( errorMessage ) errorMessage += ", ";
			errorMessage += jQuery('#errorSubjectMissed').html();
			//offset += 18;
		}
		if( parseInt(department) == 0 ) 
		{
			jQuery("#requestedDepartment").addClass('error');
			if( errorMessage ) errorMessage += ", ";
			errorMessage += jQuery('#errorDepartmentMissed').html();
			//offset += 18;
		}
		if(message == '' ) 	
		{
			jQuery("#userQuery").addClass('error');
			if( errorMessage ) errorMessage += ", ";
			errorMessage += jQuery('#errorMessageMissed').html();
			//offset += 18;
		}
		if( errorMessage != "" ) 
		{
			floatIndex(offset);
			setTimeout( "floatIndex()", 6001 );
			return messageError(jQuery('#panel_error_title').html(), errorMessage, 500);		
		}
		
		jQuery("#loader_account_address").fadeIn();
		jQuery.ajax( 
		{
			url : '?do=sendQuery',
			type : "POST",
			data : 
			{
				name : 		name,
				email: 		email,
				subject:	subject,
				department:	department,
				message:	message	
			}, 
			success : function(xml) 
			{
				jQuery("#loader_account_address").fadeOut();
				jQuery(".addForm").each(function() { jQuery("#" + this.id).removeClass('error'); });
				if(jQuery("success", xml).text() == "1") 
				{
					floatIndex(offset);
					setTimeout( "floatIndex()", 6001 );	
					jQuery("#userName").val('');
					jQuery("#userEmail").val('');
					jQuery("#userSubject").val('');
					jQuery("#requestedDepartment").val('');
					jQuery("#userQuery").val('');
					return  messageSuccess(jQuery('#panel_success_title').html(), jQuery('#panel_success_details').html(), 500);
				}
				else
				{
					var errorList = jQuery("message", xml).text().split("@");
					while( errorList.length ) switch( errorList.shift() )
					{
					case "name":
						jQuery("#userName").addClass('error');
						if( errorMessage ) errorMessage += ", ";
						errorMessage += jQuery('#errorNameMissed').html();
						//offset += 18;
						break;
					case "email":
						jQuery("#userEmail").addClass('error');
						if( errorMessage ) errorMessage += ", ";
						errorMessage += jQuery('#errorEmailMissed').html();
						//offset += 18;
						break;
					case "subject":
						jQuery("#userSubject").addClass('error');
						if( errorMessage ) errorMessage += ", ";
						errorMessage += jQuery('#errorSubjectMissed').html();
						//offset += 18;
						break;
					case "department":
						jQuery("#requestedDepartment").addClass('error');
						if( errorMessage ) errorMessage += ", ";
						errorMessage += jQuery('#errorDepartmentMissed').html();
						//offset += 18;
						break;
					case "message":
						jQuery("#userQuery").addClass('error');
						if( errorMessage ) errorMessage += ", ";
						errorMessage += jQuery('#errorMessageMissed').html();
						//offset += 18;
						break;
					}
					floatIndex(offset);
					setTimeout( "floatIndex()", 6001 );	
					return messageError(jQuery('#panel_error_title').html(), errorMessage, 500);
				}
			}			
		});
	});
});