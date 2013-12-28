$(document).ready(function() {
	
	headerHeight = $('div.header').height();
	footerHeight = $('div.footer').height();
	extras = headerHeight + footerHeight + 32;
	listingHeight = $(window).height() - extras;
	
	$('.cp_listing').css({
		minHeight: listingHeight,
		});

});
	
$(window).resize(function() {
	
	headerHeight = $('div.header').height();
	footerHeight = $('div.footer').height();
	extras = headerHeight + footerHeight + 32;
	listingHeight = $(window).height() - extras;
	
	$('.cp_listing').css({
		minHeight: listingHeight,
		});

});
		