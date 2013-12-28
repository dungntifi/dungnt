(function($) {
	$.fn.clonePosition = function(element, options) {
		var options = $.extend( {
			cloneWidth : true,
			cloneHeight : true,
			offsetLeft : 0,
			offsetTop : 0
		}, (options || {}));
		var offsets = $(element).offset();
		$(this).css( {
			position : 'absolute',
			top : (offsets.top + options.offsetTop) + 'px',
			left : (offsets.left + options.offsetLeft) + 'px'
		});
		if (options.cloneWidth)
			$(this).width($(element).width());
		if (options.cloneHeight)
			$(this).height($(element).height());
		return this;
	}
})(jQuery);

function getBaseURL()
{
	return window.location.protocol + '//' + window.location.hostname + '/';
}
function getActualHieght(elementSelector)
{
	var height = 0;
	var item = jQuery(elementSelector);
	height = item.height() ;
	height += parseInt(item.css("margin-top")); 
	height += parseInt(item.css("margin-bottom")); 
	height += parseInt(item.css("padding-top"));
	height += parseInt(item.css("padding-bottom")) ;
	return height;
}

function getActualTopOffset(elementSelector)
{
	var offset = 0;
	var item = jQuery(elementSelector);
	offset = item.offset().top;
	offset -= parseInt(item.css("margin-top")); 
	offset -= parseInt(item.css("padding-top"));
	
	return offset;
}
function getActualBottomOffset(elementSelector)
{
	return getActualTopOffset(elementSelector) + getActualHieght(elementSelector);;
}

