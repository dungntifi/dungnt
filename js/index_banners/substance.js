///////////////////////////////////////////////
// SUBSTANCE JS                               
///////////////////////////////////////////////

///////////////////////////////////////////////
// VARIABLES                               
///////////////////////////////////////////////

// Globals
var headerHeight = 88;
var gutters = 8;
var aspect = 1.82;
var lineHeightRatio = 0.016;
var format = '';
var template = '';
var minWidth = 1024;
var currFraction = 0;

// Temps
var tempOffset = 0;
var countOffset = 0;

var resizeSwitch = true;

// Template Switches
var mpEnable = false;
var mpTemplateA = false;
var mpTemplateB = false;
var mpTemplateC = false;

var editorialEnable = false;
var editorialTemplateA = false;
var editorialTemplateB = false;
var editorialTemplateC = false;

var focusEnable = false;
var postEnable = false;
var qaEnable = false;
var podcastEnable = false;
var interviewEnable = false;

///////////////////////////////////////////////
// SUBSTANCE FOCUS FUNCTIONS                               
///////////////////////////////////////////////
/*
function ajaxThumbnails() {

    var i = 1;
    for (x=1;x<=9;x++) {
            var tempcurrUrl = jQuery('#focus'+x).parent().attr('href');
            var currUrl = tempcurrUrl.substring(17,tempcurrUrl.length);
            var array = currUrl.split('/');
            var pcode = array[5];

            jQuery.ajax({
                url:    '',
                type:   'post',
                data: {code:pcode,
                    action:'retriveProducts'   
                    },    
                async: 'false',    
                success: function(d) {
                    var obj = jQuery.parseJSON(d);
                    jQuery('#focus'+i).attr('src',obj.IMAGE).attr('alt',obj.BRAND+' - '+obj.TITLE);
                    //jQuery('#focus'+i).parent().attr('href',currUrl);
                    i++;
                }
            });

    }

}
*/
///////////////////////////////////////////////
// READY & RESIZE FUNCTIONS                               
///////////////////////////////////////////////
/*
function loadingGif() {
        jQuery('<div id="loadingGif" style="margin-left:24px;display:inline;z-index:9999;position:absolute;top:104px;left:24px;"><img src="http://ssense.com/frontend/images/gui/ajax-loader.gif" /></div>').prependTo('.content').hide().fadeIn('slow');
}*/
jQuery(document).ready(function(){  
    _fitText();
    _cufon();
    jQuery('.content').addClass('mpHide');
    //jQuery('#loadingIcon').hide();
    //jQuery('.content').hide().removeClass('mpHide').fadeIn('fast');
    //jQuery('html').css('overflow-y','auto');
    // Find the number of rows
    numberRows = jQuery('section#rows').children().length;
    // Initialize arrays for cells and rows
    cll = [];
    row = [];
    // Loop to populate arrays with cells and add a class with array length to each row
    for (y=1;y<=numberRows;y++){
        row[y] = [];    
        for (x=1;x<=8;x++){
            jQuery("#row"+y+" .cell"+x).each(function(){
                cll[x] = jQuery(this).attr('class');
                row[y].push(cll[x]); 
                tempLength = row[y].length;
                
            });
        }   
        jQuery('#row'+y).addClass(""+(tempLength));
        
    }    
    
    cll.length = 0;
    row.length = 0;
    setDims();
    _bigDisplay();
    //jQuery(window).trigger('resize');


   if (focusEnable) {
       //ajaxThumbnails();
       focusCarousel();
   }
   
   
   looksShare();
   
   function looksShare() {
   
        //if (format == 'LOOKS' && template == 'A') {
        if (looksEnable == 'true') {    
                    
                    
                    for (x=1; x<15; x++) {
                        
                        jQuery('#looksThumbnail .sharebox, #looksThumbnail .share, #looksThumbnail .zoom').hide();
                        
                        jQuery('.cell'+x+' #looksThumbnail .share').click(
                            function(){
                                
                                if (jQuery(this).hasClass("clicked")){
                                    jQuery(this).prev().slideUp('fast');
                                    jQuery(this).animate({
                                        "bottom": "-=30px"}, "fast");
                                    jQuery(this).removeClass("clicked");
                                } else {
                                    jQuery(this).animate({
                                        "bottom": "+=30px"}, "fast");
                                    jQuery(this).prev().slideDown('fast');
                                    jQuery(this).addClass("clicked");
                                }
                            }

                        ); 
                    
                        
                        jQuery('.cell'+x+' #looksThumbnail').mouseenter(function(){
                                jQuery(this).children().eq(1).show();
                                jQuery(this).children().eq(2).show();
                        });
                        
                        jQuery('.cell'+x+' #looksThumbnail').mouseleave(function(){
                                jQuery(this).children().eq(0).hide();
                                jQuery(this).children().eq(1).hide();
                                jQuery(this).children().eq(2).hide();
                                if (jQuery(this).children().eq(1).hasClass("clicked")) {
                                    jQuery(this).prev().hide();
                                    jQuery(this).children().eq(1).css('bottom','0');
                                    jQuery(this).children().eq(1).removeClass("clicked");
                                }

                        });
                        
                        jQuery('.cell'+x+' #looksThumbnail .sharebox a').click(function(){
                            jQuery(this).parent().slideUp('fast');
                                    jQuery(this).parent().next().css('bottom','0');
                                    jQuery(this).parent().next().removeClass("clicked");
                        });  
                       
                    }

        }
   
   }
   
    
    function go(data) {
        if (this.href && this.href !== '#') {
        window.open(this.href);
        }
    }
   
});   
   


jQuery(window).resize(function(){  
    
    setDims();
    _fitText();
    _cufon();
    if ( jQuery('#content').hasClass('mp_feature') ) {
        jQuery('#content').height(jQuery('.centreMP').height());
    }
    
});

jQuery(window).load(function(){  
    jQuery('.content').removeClass('mpHide');
    if ( jQuery('#content').hasClass('mp_feature') ) {
        jQuery(window).trigger('resize');
        jQuery('#content').height(jQuery('.centreMP').height());
    } else {
    setDims();
    _fitText();
    _cufon();
    }
});


///////////////////////////////////////////////
// GET & SET DIMS                           
///////////////////////////////////////////////

function setDims() {
    
    
        
    winWidth = jQuery(window).width();
    row1height = jQuery('#row1').height();
    row2height = jQuery('#row2').height();
    row3height = jQuery('#row3').height();
    row4height = jQuery('#row4').height();
    totalRowHeight = row1height + row2height + row3height + row4height;
    globalHeight = Math.ceil(((winWidth/2)) / aspect); 
        
    // Window Math
    winFull = winWidth;
    winEighth = (winWidth/8);
    winThreeQuarter = (winWidth/4)*3;
    winQuarter = (winWidth/4);
    winTwoThird = (winWidth/3)*2;
    winThird = (winWidth/3);
    winHalf = (winWidth/2);
    winTwoFifth = (winWidth/5)*2;
    winThreeFifth = (winWidth/5)*3;
    winFifth = (winWidth/5);

        
    // Cell Math
    jQuery('.full').width(Math.ceil(winFull));
    jQuery('.twothird').width(winTwoThird-gutters);
    jQuery('.half').width(Math.ceil(winHalf-gutters/2));
    jQuery('.third').width(Math.ceil(winThird-gutters));
    jQuery('.quarter').width(Math.ceil(winQuarter-gutters/2));
    jQuery('.fifth').width(Math.ceil(winFifth-gutters));
    jQuery('.eighth').width(Math.ceil(winEighth-gutters));
        
    // Calculate Top Offsets
    smallRow = 0.75;
    normalRow = 1;
    bigRow = 1.25;
        
        
    // Check Gutter Fraction
    function checkGutter(){
        if (tempCells > 1) {
            gutterFraction = (tempCells-1)/tempCells;
        } else {
            gutterFraction = 1;    
        }
    }
        
    function checkFraction(){
        if (tempCells == 1) {
            currFraction = winFull;
        } else if (tempCells == 2) {
            currFraction = winHalf;
        } else if (tempCells == 3) {
            currFraction = winThird;
        } else if (tempCells == 4) {
            currFraction = winQuarter;
        } else if (tempCells == 5) {
            currFraction = winFifth;
        } else if (tempCells == 6) {
            currFraction = winSixth;
        } else if (tempCells == 7) {
            currFraction = winSeventh;
        } else if (tempCells == 8) {
            currFraction = winEighth;
        }
    }
        
    function checkminFraction(){
        if (tempCells == 1) {
            currminFraction = minwinFull;
        } else if (tempCells == 2) {
            currminFraction = minwinHalf;
        } else if (tempCells == 3) {
            currminFraction = minwinThird;
        } else if (tempCells == 4) {
            currminFraction = minwinQuarter;
        } else if (tempCells == 5) {
            currminFraction = minwinFifth;
        } else if (tempCells == 6) {
            currminFraction = minwinSixth;
        } else if (tempCells == 7) {
            currminFraction = minwinSeventh;
        } else if (tempCells == 8) {
            currminFraction = minwinEighth;
        }
    }
        
        
    function checkRow() {
        var first = jQuery('#row1');
        var second = jQuery('#row2');
        var third = jQuery('#row3');
            
        if (first.hasClass("smallRow")) {
            globalHeight = globalHeight*0.66;
        }
                
    }
        
    function resetRow() {
        globalHeight = Math.ceil(((winWidth/2)) / aspect); 
    }
        
    function lineHeight() {
        // LINE-hEIGHT ADJSUTMENT
        tempLineheight = (winWidth*lineHeightRatio);
        jQuery('.quartercopy').css('line-height', tempLineheight+'px' ); 
        jQuery('.thirdcopy').css('line-height', tempLineheight+'px' );
        jQuery('.fifthcopy').css('line-height', tempLineheight+'px' );
        jQuery('.eighthcopy').css('line-height', tempLineheight+'px' );
        jQuery('#podcastDescription p').css('line-height', (tempLineheight*0.8)+'px' );
        jQuery('#podcastBlockquote p').css('line-height', (tempLineheight*1.2)+'px' );
           }
        
    function fixedlineHeight() {
        // FIXED LINE-hEIGHT ADJSUTMENT
        tempLineheight = 17;
        jQuery('.quartercopy').css('line-height', tempLineheight+'px' ); 
        jQuery('.thirdcopy').css('line-height', tempLineheight+'px' );
        jQuery('.fifthcopy').css('line-height', tempLineheight+'px' );
        jQuery('.eighthcopy').css('line-height', tempLineheight+'px' );
    }
        
    // MATCH FONT-SIZE FOR ALL COPY
    var tempfontSize = jQuery('.quartercopy').css('font-size');
    jQuery('.thirdcopy, .fifthcopy, .eighthcopy').css('font-size',tempfontSize);
        
    jQuery('#content').width(winWidth).height(totalRowHeight);
    //jQuery('#rows img').height(globalHeight);
    jQuery('img.fifth, img.quarter, .img.half, img.third, img.full, img.twothird, img.eighth').height(globalHeight);
    jQuery('img.quarter').css('width','100%');
    jQuery('img.fifth').height(globalHeight*1.336);
    lineHeight();
    tempRows = numberRows;
    tempCells = 12;
        
        looksEnable = jQuery('#looksEnable').text();
/*     */// LOOKS - TEMPLATE 'A'
        //if (winWidth > 1190 && format == 'LOOKS' && template == 'A') {
        if (looksEnable == 'true') {
            jQuery('img').mapster('unbind');
            for (y=1;y<=tempRows;y++) {
                jQuery('#row1 .cell1').width(winQuarter-gutters).height(globalHeight*1.3454198473282444).css({'left':'0','top':headerHeight+gutters*4});tempCells = jQuery('#row'+y).attr("class");checkGutter();checkFraction();currCell = jQuery('#row'+y+' .cell'+(x+1));
                for (x=0;x<=tempCells;x++) {
                    jQuery('#row'+(y)+' .cell'+(x+1)).width(currFraction-(gutters*gutterFraction)).height(globalHeight*1.3454198473282444).css({'left':currFraction*x+(gutters/tempCells)*x,'top':(globalHeight*1.3454198473282444*(y-1)+headerHeight+gutters*(y+3))});
                    jQuery('#looksThumbnail #looksTitleImage img').width(currFraction-(gutters*gutterFraction)).height(globalHeight*1.3454198473282444);
                    //jQuery('#row'+(y)+' .cell'+(x)+' #looksThumbnail img').mapster();
                    //jQuery('#row'+(y)+' .cell'+(x+1)+' #looksThumbnail img').mapster('resize',currFraction-(gutters*gutterFraction),globalHeight*1.3454198473282444);
                    //jQuery('#row'+(y)+' .cell'+(x+1)+' .mapster_el').css({'width':currFraction-(gutters*gutterFraction),'height':globalHeight*1.3454198473282444});  
                    //jQuery('#row'+(y)+' .cell'+(x+1)+' #looksThumbnail img').mapster('rebind');
                }
            }
            //initialize image maps
            focus_2012_07_24();
            
            function makeBind(){ jQuery(this).attr('usemap','#look072613'); jQuery(this).mapster(); }
            function makeUnbind(){ jQuery(this).mapster('unbind'); jQuery(this).removeAttr('usemap'); } 
            
            jQuery('#looksTitleImage img').hoverIntent(makeBind,makeUnbind);
            
            jQuery('section#rows').css('height','2200px');
            
            
        } else if (winWidth < 1190 && format == 'LOOKS' && template == 'A') { 
            
            for (y=1;y<=tempRows;y++) {
                jQuery('#row1 .cell1').width(minwinFull).height(373).css({'left':'0','top':headerHeight+gutters*4});tempCells = jQuery('#row'+y).attr("class");checkGutter();checkminFraction();currCell = jQuery('#row'+y+' .cell'+(x+1));
                for (x=1;x<=tempCells;x++) {
                    jQuery('#row'+(y)+' .cell'+(x+1)).width(currminFraction-(gutters*gutterFraction)).height(373).css({'left':currminFraction*x+(gutters/tempCells)*x,'top':(373*(y-1)+headerHeight+gutters*(y+3))});
                    jQuery("embed").css('height',globalHeight*1.336);
                    
                 }
                
            }
        
        }
        
        
        // PODCAST - TEMPLATE 'A'
        podcastEnable = jQuery('#podcastEnable').hasClass("enabled");
       
        if (podcastEnable && winWidth > 1024) {  
            jQuery('.cell1').tabs();
            
            var rowOffset = '0';
            
            jQuery('#content').width(winWidth).height(totalRowHeight);
            jQuery('body#acc').children('.header').next().width(winWidth).height(globalHeight*2.5);
            
            
            jQuery(' img.threefifth').width(winThreeFifth+gutters/2);
            
            jQuery('#podcastTitleImage, #podcastTitleImage img, #podcastPlay, #podcastPlayImage, #podcastPlayImage img, img.twofifth, img.threefifth').css({'width':'100%','height':'100%'});
            
            jQuery('#podcastTracklistImage img').width(winTwoFifth-gutters/2);
            podcastGap = 48;
            // Row 1
            jQuery('#row1 .cell1').width(winTwoFifth-(gutters/2)).height(winTwoFifth-(gutters/2)).css('left','0');
            jQuery('#row1 .cell2').width(winFifth-(gutters/2)).height(winTwoFifth-(gutters/2)).css('left', winFifth*2+(gutters/2)*2);
            jQuery('#row1 .cell3').width(winTwoFifth-(gutters/2)).height(winTwoFifth-(gutters/2)).css('left', winFifth+winFifth*2+(gutters/2)*3);
            // Row 2
            jQuery('#row2 .cell1').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':'0','top':podcastGap+(winTwoFifth-(gutters/2)+headerHeight+gutters*4)});
            jQuery('#row2 .cell2').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':winFifth+(gutters/2)*1,'top':podcastGap+(winTwoFifth-(gutters/2)*1+headerHeight+gutters*4)});
            jQuery('#row2 .cell3').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':winFifth*2+(gutters/2)*2,'top':podcastGap+(winTwoFifth-(gutters/2)*1+headerHeight+gutters*4)});
            jQuery('#row2 .cell4').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':winFifth*3+(gutters/2)*3,'top':podcastGap+(winTwoFifth-(gutters/2)*1+headerHeight+gutters*4)});
            jQuery('#row2 .cell5').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':winFifth*4+(gutters/2)*4,'top':podcastGap+(winTwoFifth-(gutters/2)*1+headerHeight+gutters*4)});
            jQuery('#row2 img.fifth').width(winFifth-(gutters/2)).height(winFifth-(gutters/2));
            rowOffset = winFifth-(gutters/2);
            
            jQuery('section#rows').css('height',(winTwoFifth-(gutters/2)+(gutters/2)+headerHeight+gutters*5)+rowOffset-5);
            jQuery('.content').parent().css('height',(winTwoFifth-(gutters/2)+(gutters/2)+headerHeight+gutters*5)+rowOffset-5);
            
            // Trigger greyscale hover
            /*
            jQuery('#podcastPlayImage').mouseover(function(){
                thisImg = jQuery('#podcastPlay img');
                //console.log(thisImg);
                bwImg = Pixastic.process(thisImg, "desaturate", {average : false});
                //console.log(bwImg);
                jQuery('#podcastPlayImage').onmouseout = function() {
                    Pixastic.revert(bwImg);
                }
            });
            */
            /*
            function desaturate(img) {
		var img2 = Pixastic.process(img, "desaturate");
		img2.onmouseout = function() {
			Pixastic.revert(this);
		}
            }

            jQuery('#titleoverlay').mouseover(function(){
                thisImg = jQuery('#podcastRelated').find('img');
                thisImg = thisImg.text;
                //console.log(thisImg);
                desaturate(thisImg);
            });
            */
            /*
            // Scroll overflowing description text
            var cell1Width = Math.floor(jQuery('#row1 .cell1').width());
            var newDescHeight = Math.floor(cell1Width*0.4);
            var descHeight = Math.floor(jQuery('#podcastAbout').height());
            jQuery('#podcastAbout').height(newDescHeight);
            if (descHeight > newDescHeight) {
                jQuery('#podcastAbout').css({'overflow-y':'scroll', 'height':Math.floor(newDescHeight)});
            } else {
                jQuery('#podcastAbout').css({'overflow-y':'hidden', 'height':'auto'});
            }
            */
            jQuery('.tabs-1').click(function(){
                _fitText();
                _cufon();
            });
            
            jQuery('#tabs-2').click(function(){
                _fitText();
                _cufon();
            });
            
            
            lineHeight();
            
            
        } else if (podcastEnable && winWidth <= 1024) { 
            jQuery('.cell1').tabs();
            
            
            
            jQuery('#content').width(winWidth).height(totalRowHeight);
            jQuery('body#acc').children('.header').next().width(winWidth).height(globalHeight*2.5);
            
            
            jQuery(' img.threefifth').width(winThreeFifth+gutters/2);
            
            jQuery('#podcastTitleImage, #podcastTitleImage img, #podcastPlay, #podcastPlayImage, #podcastPlayImage img, img.twofifth, img.threefifth').css({'width':'100%','height':'100%'});
            
            jQuery('#podcastTracklistImage img').width(200);
            podcastGap = 48;
            // Row 1
            jQuery('#row1 .cell1').width(400-(gutters/2)).height(400).css('left','0');
            jQuery('#row1 .cell2').width(198-(gutters/2)).height(400).css('left', 198*2+(gutters));
            jQuery('#row1 .cell3').width(400).height(400).css('left', 198+198*2+(gutters)+4);
            // Row 2
            jQuery('#row2 .cell1').width(198-(gutters/2)).height(198-(gutters/2)).css({'left':'0','top':526});
            jQuery('#row2 .cell2').width(198-(gutters/2)).height(198-(gutters/2)).css({'left':198+(gutters/2)*1,'top':526});
            jQuery('#row2 .cell3').width(198-(gutters/2)).height(198-(gutters/2)).css({'left':198*2+(gutters/2)*2,'top':526});
            jQuery('#row2 .cell4').width(198-(gutters/2)).height(198-(gutters/2)).css({'left':198*3+(gutters/2)*3,'top':526});
            jQuery('#row2 .cell5').width(198-(gutters/2)).height(198-(gutters/2)).css({'left':198*4+(gutters/2)*4,'top':526});
            jQuery('#row2 img.fifth').width(198-(gutters/2)).height(198-(gutters/2));
            
            jQuery('#relatedTitle').css({'left':'24px','top':715,'width':'100%','border-bottom':'1px solid #efefef','padding-bottom':'2px'});
            jQuery('section#rows').css('height',podcastGap+((598)+gutters*5-6));
            jQuery('.content').parent().css('height',podcastGap+((598)+gutters*5-6));
            
            /*
            function desaturate(img) {
		var img2 = Pixastic.process(img, "desaturate");
		img2.onmouseout = function() {
			Pixastic.revert(this);
		}
            }
        
            
        
            jQuery('#podcastPlayImage').mouseover(function(){
                var thisImg = jQuery('#podcastPlay').find('img');
                desaturate(thisImg);
            });


            // Scroll overflowing description text
            cell1Width = jQuery('#row1 .cell1').width();
            newDescHeight = Math.floor(cell1Width*0.4);
            descHeight = Math.floor(jQuery('#podcastAbout').height());
            
            jQuery('#podcastAbout').height(newDescHeight);
            if (descHeight > newDescHeight) {
                jQuery('#podcastAbout').css({'overflow-y':'scroll', 'height':'150px'});
                jQuery('#podcastAbout').css({'min-height':cell1Width*0.2});
            } else {
                jQuery('#podcastAbout').css({'overflow-y':'scroll', 'height':'150px'});
            }
            */
            

            jQuery('.tabs-1').click(function(){
                _fitText();
                _cufon();
            });
            
            jQuery('#tabs-2').click(function(){
                _fitText();
                _cufon();
            });

            
        }
        
        
        // INTERVIEW - TEMPLATE 'A'
        interviewEnable = jQuery('#interviewEnable').hasClass('enabled');
        interviewTemplateA = jQuery('#interviewEnable').hasClass('a');
        interviewTemplateB = jQuery('#interviewEnable').hasClass('b');
        var interviewOffset = headerHeight +32;
        
        
        if (interviewEnable && winWidth > 1024) {
            jQuery('#backtoTop').hide();
            currScroll = jQuery(window).scrollTop();
            
            if (currScroll >= 56) {jQuery('#backtoTop').fadeIn('fast');}else{jQuery('#backtoTop').fadeOut('fast');}
            
            if (interviewTemplateA) {
                jQuery('#row1 .cell1').width(winQuarter-(gutters/2)).height(winQuarter-(gutters/2)).css({'left':'0'});
                jQuery('#row1 .cell2').width(winThreeQuarter-(gutters/2)).css('left',winQuarter+(gutters/2));
            } else if (interviewTemplateB) {
                jQuery('#row1 .cell1').width(winQuarter-(gutters/2)).height(winQuarter-(gutters/2)).css({'left':winThreeQuarter+(gutters/2)});
                jQuery('#row1 .cell2').width(winThreeQuarter-(gutters/2)).css('left',0);
                jQuery('.interviewCopy').css({'margin-left':(jQuery('.interviewCopy').parent().width())/2-gutters});
                jQuery('#backtoTop').css({'position':'fixed','right':'24px','bottom':'24px','left':'auto'});
            }
            interviewOffset = interviewOffset + jQuery('#row1 .cell2').height() + 48;
            jQuery('#row2 .cell1').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':'0','top':interviewOffset});
            jQuery('#row2 .cell2').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':(winFifth-(gutters/2))*1+(gutters)*1,'top':interviewOffset});
            jQuery('#row2 .cell3').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':(winFifth-(gutters/2))*2+(gutters)*2,'top':interviewOffset});
            jQuery('#row2 .cell4').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':(winFifth-(gutters/2))*3+(gutters)*3,'top':interviewOffset});
            jQuery('#row2 .cell5').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':(winFifth-(gutters/2))*4+(gutters)*4,'top':interviewOffset});
            jQuery('.interviewCopy').css({'width':(jQuery('.interviewCopy').parent().width())/2-gutters});
            jQuery('section#rows').height(interviewOffset+jQuery('#row2 .cell1').height()-headerHeight-32-25);
            jQuery(window).scroll(function() { var currScroll = jQuery(window).scrollTop();
                if (currScroll >= 56) {
                    jQuery('#row1 .cell1').css('top',currScroll).css('margin-top','24px');
                    jQuery('#backtoTop').fadeIn('fast');
                } else {
                    jQuery('#row1 .cell1').css('top',headerHeight+32).css('margin-top','0px');
                    jQuery('#backtoTop').fadeOut('fast');
                }
            });
            
            
        } else if (interviewEnable && winWidth <= 1024) {
            jQuery('#backtoTop').hide();
            currScroll = jQuery("html").scrollTop();
            if (currScroll >= 56) {jQuery('#backtoTop').fadeIn('fast');}else{jQuery('#backtoTop').fadeOut('fast');}
            
            if (interviewTemplateA) {
            jQuery('#row1 .cell1').width(248).height(winQuarter-(gutters/2)).css({'left':'0'});
            jQuery('#row1 .cell2').width(753).css('left',248+(gutters/2));
            } else if (interviewTemplateB) {
            jQuery('#row1 .cell1').width(248).height(winQuarter-(gutters/2)).css({'left':'753px'});
            jQuery('#row1 .cell2').width(753).css('left',0);   
            jQuery('.interviewCopy').css({'margin-left':(jQuery('.interviewCopy').parent().width())/2-gutters});
            jQuery('#backtoTop').css({'position':'fixed','right':'24px','bottom':'24px','left':'auto'});
            }
            interviewOffset = interviewOffset + jQuery('#row1 .cell2').height() + 48;
            jQuery('#row2 .cell1').width(198).height(198).css({'left':'0','top':interviewOffset});
            jQuery('#row2 .cell2').width(198).height(198).css({'left':(198)*1+(gutters)*1,'top':interviewOffset});
            jQuery('#row2 .cell3').width(198).height(198).css({'left':(198)*2+(gutters)*2,'top':interviewOffset});
            jQuery('#row2 .cell4').width(198).height(198).css({'left':(198)*3+(gutters)*3,'top':interviewOffset});
            jQuery('#row2 .cell5').width(198).height(198).css({'left':(198)*4+(gutters)*4,'top':interviewOffset});
            jQuery('.interviewCopy').css({'width':(jQuery('.interviewCopy').parent().width())/2-gutters});
            jQuery('section#rows').height(interviewOffset+jQuery('#row2 .cell1').height()-headerHeight-32-25);
            jQuery(window).scroll(function() { var currScroll = jQuery("html").scrollTop();
                if (currScroll >= 56) {
                    jQuery('#row1 .cell1').css('top',currScroll).css('margin-top','24px');
                    jQuery('#backtoTop').fadeIn('fast');
                } else {
                    jQuery('#row1 .cell1').css('top',headerHeight+32).css('margin-top','0px');
                    jQuery('#backtoTop').fadeOut('fast');
                }
            });
            
            
        }
        
        
        
        
        // Q&A - TEMPLATE 'A'
        qaEnable = jQuery('#qaEnable').hasClass('enabled');
        qaTemplateA = jQuery('#qaTemplate').hasClass('a');
        if (qaEnable && winWidth > 1024 && qaTemplateA) {  
            var qaGap = 0;
            
            //jQuery('#content').width(winWidth).height(totalRowHeight);
            //jQuery('.header').next().width(winWidth).height(globalHeight*2.5);
            jQuery('img.twofifth, img.threefifth').height(globalHeight);
            jQuery('#podcastTitleImage img').width(winFifth-gutters/2);
            jQuery(' img.threefifth').width(winThreeFifth+gutters/2);
            jQuery('#podcastPlayImage img').width(winTwoFifth-gutters/2);
            
            jQuery('#podcastTracklistImage img').width(winTwoFifth-gutters/2);
            
            // Row 1
            jQuery('#row1 .cell1').width(winTwoFifth-(gutters/2)).height(globalHeight).css('left','0');
            jQuery('#row1 .cell2').width(winFifth-(gutters/2)).height(globalHeight).css('left', winTwoFifth+(gutters/2)*2);
            jQuery('#row1 .cell3').width(winFifth-(gutters/2)).height(globalHeight).css('left', winTwoFifth+winFifth+(gutters/2)*3);
            jQuery('#row1 .cell4').width(winFifth-(gutters/2)).height(globalHeight).css('left', winTwoFifth+winFifth*2+(gutters/2)*4);
            qaGap = globalHeight;
            // Row 2
            jQuery('#row2 .cell1').width(winFifth-(gutters/2)).height(globalHeight).css({'left':'0','top':(globalHeight+headerHeight+gutters*5)});
            jQuery('#row2 .cell2').width(winFifth-(gutters/2)).height(globalHeight).css({'left':winFifth+(gutters/2)*1,'top':((globalHeight)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell3').width(winFifth-(gutters/2)).height(globalHeight).css({'left':winFifth*2+(gutters/2)*2,'top':((globalHeight)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell4').width(winFifth-(gutters/2)).height(globalHeight).css({'left':winFifth*3+(gutters/2)*3,'top':((globalHeight)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell5').width(winFifth-(gutters/2)).height(globalHeight).css({'left':winFifth*4+(gutters/2)*4,'top':((globalHeight)*1+headerHeight+gutters*5)});
            jQuery('#row2 img.fifth').width(winFifth-(gutters/2)).height(globalHeight);
            qaGap = qaGap + globalHeight+gutters*6;
            /*
            // Row 2
            jQuery('#row3 .cell1').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':'0','top':((globalHeight)*2+headerHeight+gutters*6)+40});
            jQuery('#row3 .cell2').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':winFifth+(gutters/2)*1,'top':((globalHeight)*2+headerHeight+gutters*6)+40});
            jQuery('#row3 .cell3').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':winFifth*2+(gutters/2)*2,'top':((globalHeight)*2+headerHeight+gutters*6)+40});
            jQuery('#row3 .cell4').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':winFifth*3+(gutters/2)*3,'top':((globalHeight)*2+headerHeight+gutters*6)+40});
            jQuery('#row3 .cell5').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':winFifth*4+(gutters/2)*4,'top':((globalHeight)*2+headerHeight+gutters*6)+40});
            jQuery('#row3 img.fifth').width(winFifth-(gutters/2)).height(winFifth-(gutters/2));
            qaGap = qaGap + winFifth-(gutters/2)+headerHeight+gutters*6;
            */
            jQuery('section#rows').css('height',qaGap+40);
            jQuery('.content').parent().css('height',qaGap+40);
            
        } else if (qaEnable && winWidth <= 1024 && qaTemplateA) {  

           
            // Row 1
            jQuery('#row1 .cell1').width(400).height(278).css('left','0');
            jQuery('#row1 .cell2').width(198).height(278).css('left', 400-4+8*2);
            jQuery('#row1 .cell3').width(198).height(278).css('left', 400-4+198+8*3);
            jQuery('#row1 .cell4').width(198).height(278).css('left', 400-4+198*2+8*4);
            // Row 2
            jQuery('#row2 .cell1').width(198).height(278).css({'left':'0','top':(278+headerHeight+gutters*5)});
            jQuery('#row2 .cell2').width(198).height(278).css({'left':198+8,'top':((278)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell3').width(198).height(278).css({'left':198*2+8*2,'top':((278)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell4').width(198).height(278).css({'left':198*3+8*3,'top':((278)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell5').width(198).height(278).css({'left':198*4+8*4,'top':((278)*1+headerHeight+gutters*5)});
            jQuery('#row2 img.fifth').width(198).height(278);

            jQuery('section#rows').css('height',287*2+gutters*8+5);
            jQuery('.content').parent().css('height',287*2+gutters*8+5);
            
        }
        
        // Q&A - TEMPLATE 'B'
        qaEnable = jQuery('#qaEnable').hasClass('enabled');
        qaTemplateB = jQuery('#qaTemplate').hasClass('b');
        if (qaEnable && winWidth > 1024 && qaTemplateB) {  
            var qaGap = 0;
            
            //jQuery('#content').width(winWidth).height(totalRowHeight);
            //jQuery('.header').next().width(winWidth).height(globalHeight*2.5);
            jQuery('img.twofifth, img.threefifth').height(globalHeight);
            jQuery('#podcastTitleImage img').width(winFifth-gutters/2);
            jQuery(' img.threefifth').width(winThreeFifth+gutters/2);
            jQuery('#podcastPlayImage img').width(winTwoFifth-gutters/2);
            
            jQuery('#podcastTracklistImage img').width(winTwoFifth-gutters/2);
            
            // Row 1
            jQuery('#row1 .cell1').width(winFifth-(gutters/2)).height(globalHeight).css('left','0');
            jQuery('#row1 .cell2').width(winFifth-(gutters/2)).height(globalHeight).css('left', winFifth+(gutters/2)*1);
            jQuery('#row1 .cell3').width(winFifth-(gutters/2)).height(globalHeight).css('left', winFifth*2+(gutters/2)*2);
            jQuery('#row1 .cell4').width(winTwoFifth-(gutters/2)).height(globalHeight).css('left', winFifth*3+(gutters/2)*3);
            qaGap = globalHeight;
            // Row 2
            jQuery('#row2 .cell1').width(winFifth-(gutters/2)).height(globalHeight).css({'left':'0','top':(globalHeight+headerHeight+gutters*5)});
            jQuery('#row2 .cell2').width(winFifth-(gutters/2)).height(globalHeight).css({'left':winFifth+(gutters/2)*1,'top':((globalHeight)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell3').width(winFifth-(gutters/2)).height(globalHeight).css({'left':winFifth*2+(gutters/2)*2,'top':((globalHeight)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell4').width(winFifth-(gutters/2)).height(globalHeight).css({'left':winFifth*3+(gutters/2)*3,'top':((globalHeight)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell5').width(winFifth-(gutters/2)).height(globalHeight).css({'left':winFifth*4+(gutters/2)*4,'top':((globalHeight)*1+headerHeight+gutters*5)});
            jQuery('#row2 img.fifth').width(winFifth-(gutters/2)).height(globalHeight);
            qaGap = qaGap + globalHeight+gutters*6;
            /*
            // Row 2
            jQuery('#row3 .cell1').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':'0','top':((globalHeight)*2+headerHeight+gutters*6)+40});
            jQuery('#row3 .cell2').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':winFifth+(gutters/2)*1,'top':((globalHeight)*2+headerHeight+gutters*6)+40});
            jQuery('#row3 .cell3').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':winFifth*2+(gutters/2)*2,'top':((globalHeight)*2+headerHeight+gutters*6)+40});
            jQuery('#row3 .cell4').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':winFifth*3+(gutters/2)*3,'top':((globalHeight)*2+headerHeight+gutters*6)+40});
            jQuery('#row3 .cell5').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':winFifth*4+(gutters/2)*4,'top':((globalHeight)*2+headerHeight+gutters*6)+40});
            jQuery('#row3 img.fifth').width(winFifth-(gutters/2)).height(winFifth-(gutters/2));
            qaGap = qaGap + winFifth-(gutters/2)+headerHeight+gutters*6;
            */
            jQuery('section#rows').css('height',qaGap+40);
            jQuery('.content').parent().css('height',qaGap+40);
            
        } else if (qaEnable && winWidth <= 1024 && qaTemplateB) {  

           
            // Row 1
            jQuery('#row1 .cell1').width(198).height(278).css('left','0');
            jQuery('#row1 .cell2').width(198).height(278).css('left', 198+8);
            jQuery('#row1 .cell3').width(198).height(278).css('left', 198*2+8*2);
            jQuery('#row1 .cell4').width(400).height(278).css('left', 198*3+8*3);
            // Row 2
            jQuery('#row2 .cell1').width(198).height(278).css({'left':'0','top':(278+headerHeight+gutters*5)});
            jQuery('#row2 .cell2').width(198).height(278).css({'left':198+8,'top':((278)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell3').width(198).height(278).css({'left':198*2+8*2,'top':((278)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell4').width(198).height(278).css({'left':198*3+8*3,'top':((278)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell5').width(198).height(278).css({'left':198*4+8*4,'top':((278)*1+headerHeight+gutters*5)});
            jQuery('#row2 img.fifth').width(198).height(278);

            jQuery('section#rows').css('height',287*2+gutters*8+5);
            jQuery('.content').parent().css('height',287*2+gutters*8+5);
            
        }
        
        // POST - TEMPLATE 'A'
        postEnable = jQuery('#postEnable').hasClass('enabled');
        totalMasonHeight = 0;

        if (postEnable && winWidth > 1024) {       

            var highestBox = 0;
            
            //jQuery('#content').width(winWidth).height(totalRowHeight);
            //jQuery('.header').next().width(winWidth).height(globalHeight*2.5);
            jQuery('img.twofifth, img.threefifth').height(globalHeight);
            jQuery('#podcastTitleImage img').width(winFifth-gutters/2);
            jQuery(' img.threefifth').width(winThreeFifth+gutters/2);
            jQuery('#podcastPlayImage img').width(winTwoFifth-gutters/2);
            
            jQuery('#podcastTracklistImage img').width(winTwoFifth-gutters/2);
            
            // Row 1
            jQuery('#row1 .cell1').width(winTwoFifth-(gutters/2)).height(globalHeight).css('left','0');
            jQuery('#row1 .cell2').width(winThreeFifth-(gutters/2)+16).css({'left': winTwoFifth+(gutters),'height':'auto'});
            
            var blockWidth = Math.floor(jQuery('#row1 .cell2').width());
            
            jQuery('#row1 .cell2 img').width(Math.floor((blockWidth/3)-8));
            jQuery('#row1 .cell2 img.double').width(Math.floor((blockWidth/3)*2-8));
            jQuery('#row1 .cell2 img.triple').width(Math.floor(blockWidth));
            
            jQuery('#row1 .cell2').masonry({
                itemSelector: '.box',
                columnWidth: (blockWidth/3)
            });
            
            var masonryHeights = [];
            jQuery('.box').each(function(){
                
                masonryHeights.push(parseInt(jQuery(this).css('top')));
                if (parseInt(jQuery(this).css('left')) == 0) {
                    totalMasonHeight = totalMasonHeight + jQuery(this).height();
                }
                
            });
            
            
            highestBox = Math.max.apply(Math, masonryHeights);

            
            
            jQuery('#row1 .cell1, #row1 .cell2').css('height','auto');
      
            jQuery('#row1 .cell2').css('min-height',totalMasonHeight+48);
            var hasVideo = jQuery('.VideoEmbed-container');
            
            if (hasVideo) {
                videoHeight = (jQuery('.VideoEmbed-container iframe').height());
                videoWidth = (jQuery('.VideoEmbed-container iframe').width());
                jQuery('#row1 .cell2').css('height',videoHeight+48);
                postOffset = videoHeight + 16;
            } 
            
            if (totalMasonHeight > 0) {
                postOffset = totalMasonHeight + 24;
            }
            
            
            /*
            var tempcell1 = jQuery('#row1 .cell1').height();
            var tempcell2 = jQuery('#row1 .cell2').height();
            if (tempcell1 > tempcell2) {
                leadCheck = tempcell1;
                
            } else if ( tempcell2 >= tempcell1 ) {
                leadCheck = tempcell2;
            }
            if (highestBox > 0) {
                postOffset = leadCheck + 48 + highestBox;
            } else { 
                postOffset = leadCheck + 48;
            }
            */
            
            // Row 2
            jQuery('#row2 .cell1').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':'0','top':(postOffset+headerHeight+gutters*8)});
            jQuery('#row2 .cell2').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':winFifth+(gutters/2)*1,'top':((postOffset)*1+headerHeight+gutters*8)});
            jQuery('#row2 .cell3').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':winFifth*2+(gutters/2)*2,'top':((postOffset)*1+headerHeight+gutters*8)});
            jQuery('#row2 .cell4').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':winFifth*3+(gutters/2)*3,'top':((postOffset)*1+headerHeight+gutters*8)});
            jQuery('#row2 .cell5').width(winFifth-(gutters/2)).height(winFifth-(gutters/2)).css({'left':winFifth*4+(gutters/2)*4,'top':((postOffset)*1+headerHeight+gutters*8)});
            jQuery('#row2 img.fifth').width(winFifth-(gutters/2)).height(winFifth-(gutters/2));
           

            jQuery('section#rows').css('height',((postOffset)+gutters*9)+winFifth-(gutters/2));
            jQuery('.content').parent().css('height',((postOffset)+gutters*9)+winFifth-(gutters/2));
            
            if (resizeSwitch) {
                var t = setTimeout(function(){
                    jQuery(window).trigger('resize');
                },1);
                resizeSwitch = !resizeSwitch;
            }
                   
        } else if (postEnable && winWidth <= 1024) {  
            
            
            
            jQuery('#podcastTracklistImage img').width(winTwoFifth-gutters/2);
            
            // Row 1
            jQuery('#row1 .cell1').width(400-(gutters/2)).height(globalHeight).css('left','0');
            jQuery('#row1 .cell2').width(601-(gutters)+12).css({'left': 400+4,'height':'auto'});
            
            var blockWidth = Math.floor(jQuery('#row1 .cell2').width());
            
            
            jQuery('#row1 .cell2 img').width(Math.floor((blockWidth/3)-8));
            jQuery('#row1 .cell2 img.double').width(Math.floor((blockWidth/3)*2-8));
            jQuery('#row1 .cell2 img.triple').width(Math.floor(blockWidth));
            
            jQuery('#row1 .cell2').masonry({
                itemSelector: '.box',
                columnWidth: (blockWidth/3)
            });
            
            var masonryHeights = [];
            jQuery('.box').each(function(){
                
                masonryHeights.push(parseInt(jQuery(this).css('top')));
                if (parseInt(jQuery(this).css('left')) == 0) {
                    totalMasonHeight = totalMasonHeight + jQuery(this).height();
                }
                
            });
            
            var hasVideo = jQuery('.VideoEmbed-container');
            
            if (hasVideo) {
                videoHeight = (jQuery('.VideoEmbed-container iframe').height());
                videoWidth = (jQuery('.VideoEmbed-container iframe').width());
                jQuery('#row1 .cell2').height(videoHeight);
            }
            
            jQuery('#row1 .cell1, #row1 .cell2').css('height','auto');
            var tempcell1 = jQuery('#row1 .cell1').height();
            var tempcell2 = jQuery('#row1 .cell2').height();
            if (tempcell1 > tempcell2) {
                leadCheck = tempcell1;
                
            } else if ( tempcell2 >= tempcell1 ) {
                leadCheck = tempcell2;
            }
            
            var postOffset = leadCheck + 40;
            // Row 2
            jQuery('#row2 .cell1').width(198-gutters/2).height(198).css({'left':'0','top':(postOffset+headerHeight+gutters*5)});
            jQuery('#row2 .cell2').width(198-gutters/2).height(198).css({'left':198+(gutters/2)*1,'top':((postOffset)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell3').width(198-gutters/2).height(198).css({'left':198*2+(gutters/2)*2,'top':((postOffset)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell4').width(198-gutters/2).height(198).css({'left':198*3+(gutters/2)*3,'top':((postOffset)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell5').width(198-gutters/2).height(198).css({'left':198*4+(gutters/2)*4,'top':((postOffset)*1+headerHeight+gutters*5)});
            jQuery('#row2 img.fifth').css({'width':'100%','height':'100%'});
           
            jQuery('#row1 .cell2').css('min-height',totalMasonHeight);
            
            jQuery('section#rows').css('height',((postOffset)+gutters*6)+198);
            jQuery('.content').parent().css('height',((postOffset)+gutters*6)+198);
            
            if (resizeSwitch) {
                var t = setTimeout(function(){
                    jQuery(window).trigger('resize');
                },1);
                resizeSwitch = !resizeSwitch;
            }
            
        }
    
        
        newsEnable = jQuery('#newsEnable').hasClass("enabled");
        if (newsEnable && winWidth > 1024) {
            
            var newsRows = 6; // Set the # of rows
            
            var rowOffset = 0;
            // Row 1
            jQuery('#row1 .cell1').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':'0'});
            jQuery('#row1 .cell2').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth+(gutters/4)});
            jQuery('#row1 .cell3').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*2+(gutters/4)*2});
            jQuery('#row1 .cell4').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*3+(gutters/4)*3});
            jQuery('#row1 .cell5').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*4+(gutters/4)*4});
            
            // Row 2
            jQuery('#row2 .cell1').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':'0','top':(winFifth-(gutters*0.75)+headerHeight+gutters*5)});
            jQuery('#row2 .cell2').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth+(gutters/4),'top':((winFifth-(gutters*0.75))*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell3').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*2+(gutters/4)*2,'top':((winFifth-(gutters*0.75))*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell4').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*3+(gutters/4)*3,'top':((winFifth-(gutters*0.75))*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell5').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*4+(gutters/4)*4,'top':((winFifth-(gutters*0.75))*1+headerHeight+gutters*5)});
            
            // Row 2
            jQuery('#row3 .cell1').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':'0','top':((winFifth-(gutters*0.75))*2+headerHeight+gutters*6)});
            jQuery('#row3 .cell2').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth+(gutters/4),'top':((winFifth-(gutters*0.75))*2+headerHeight+gutters*6)});
            jQuery('#row3 .cell3').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*2+(gutters/4)*2,'top':((winFifth-(gutters*0.75))*2+headerHeight+gutters*6)});
            jQuery('#row3 .cell4').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*3+(gutters/4)*3,'top':((winFifth-(gutters*0.75))*2+headerHeight+gutters*6)});
            jQuery('#row3 .cell5').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*4+(gutters/4)*4,'top':((winFifth-(gutters*0.75))*2+headerHeight+gutters*6)});

            // Row 4
            jQuery('#row4 .cell1').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':'0','top':((winFifth-(gutters*0.75))*3+headerHeight+gutters*7)});
            jQuery('#row4 .cell2').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth+(gutters/4),'top':((winFifth-(gutters*0.75))*3+headerHeight+gutters*7)});
            jQuery('#row4 .cell3').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*2+(gutters/4)*2,'top':((winFifth-(gutters*0.75))*3+headerHeight+gutters*7)});
            jQuery('#row4 .cell4').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*3+(gutters/4)*3,'top':((winFifth-(gutters*0.75))*3+headerHeight+gutters*7)});
            jQuery('#row4 .cell5').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*4+(gutters/4)*4,'top':((winFifth-(gutters*0.75))*3+headerHeight+gutters*7)});
            
            // Row 5
            jQuery('#row5 .cell1').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':'0','top':((winFifth-(gutters*0.75))*4+headerHeight+gutters*8)});
            jQuery('#row5 .cell2').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth+(gutters/4),'top':((winFifth-(gutters*0.75))*4+headerHeight+gutters*8)});
            jQuery('#row5 .cell3').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*2+(gutters/4)*2,'top':((winFifth-(gutters*0.75))*4+headerHeight+gutters*8)});
            jQuery('#row5 .cell4').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*3+(gutters/4)*3,'top':((winFifth-(gutters*0.75))*4+headerHeight+gutters*8)});
            jQuery('#row5 .cell5').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*4+(gutters/4)*4,'top':((winFifth-(gutters*0.75))*4+headerHeight+gutters*8)});
            
            // Row 6
            jQuery('#row6 .cell1').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':'0','top':((winFifth-(gutters*0.75))*5+headerHeight+gutters*9)});
            jQuery('#row6 .cell2').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth+(gutters/4),'top':((winFifth-(gutters*0.75))*5+headerHeight+gutters*9)});
            jQuery('#row6 .cell3').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*2+(gutters/4)*2,'top':((winFifth-(gutters*0.75))*5+headerHeight+gutters*9)});
            jQuery('#row6 .cell4').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*3+(gutters/4)*3,'top':((winFifth-(gutters*0.75))*5+headerHeight+gutters*9)});
            jQuery('#row6 .cell5').width(winFifth-(gutters*0.75)).height(winFifth-(gutters*0.75)).css({'left':winFifth*4+(gutters/4)*4,'top':((winFifth-(gutters*0.75))*5+headerHeight+gutters*9)});
            
            
            tempCells = jQuery('#row6').attr("class"); checkGutter(); checkFraction();
            currCell = jQuery('#row6 .cell'+(x+1));
            /*    for (x=0;x<=tempCells;x++) {
                    jQuery('#row5 .cell'+(x+1)).width(currFraction-(gutters*gutterFraction)).height(winFifth-(gutters*0.75)).css({'left':currFraction*x+(gutters/tempCells)*x,'top':(winFifth-(gutters*0.75)*4+headerHeight+gutters*8)+rowOffset});
                  }
            */
           
            jQuery('img.quarter, #newsThumbnail').css({'width':'100%','height':'100%'});
            jQuery('.half .ovr_mid_news').css({'padding':'0 20%','width':'60%'});
            
            rowOffset = winFifth-(gutters*0.75)*5+gutters*8+headerHeight;
            jQuery('section#rows').css('height',((winFifth-(gutters*0.75))*newsRows+gutters*newsRows+40-headerHeight));
            //jQuery('.content').parent().css('height',((winFifth-(gutters*0.75))*3+headerHeight+gutters*6));
            
        }   else if (newsEnable && winWidth <= 1024) {
            
            var newsRows = 6; // Set the # of rows
            
            var rowOffset = 0;
            // Row 1
            jQuery('#row1 .cell1').width(196).height(196).css({'left':'0'});
            jQuery('#row1 .cell2').width(196).height(196).css({'left':196+(gutters)});
            jQuery('#row1 .cell3').width(196).height(196).css({'left':196*2+(gutters)*2});
            jQuery('#row1 .cell4').width(196).height(196).css({'left':196*3+(gutters)*3});
            jQuery('#row1 .cell5').width(196).height(196).css({'left':196*4+(gutters)*4});
            
            // Row 2
            jQuery('#row2 .cell1').width(196).height(196).css({'left':'0','top':(196+headerHeight+gutters*5)});
            jQuery('#row2 .cell2').width(196).height(196).css({'left':196+(gutters),'top':((196)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell3').width(196).height(196).css({'left':196*2+(gutters)*2,'top':((196)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell4').width(196).height(196).css({'left':196*3+(gutters)*3,'top':((196)*1+headerHeight+gutters*5)});
            jQuery('#row2 .cell5').width(196).height(196).css({'left':196*4+(gutters)*4,'top':((196)*1+headerHeight+gutters*5)});
            
            // Row 3
            jQuery('#row3 .cell1').width(196).height(196).css({'left':'0','top':((196)*2+headerHeight+gutters*6)});
            jQuery('#row3 .cell2').width(196).height(196).css({'left':196+(gutters),'top':((196)*2+headerHeight+gutters*6)});
            jQuery('#row3 .cell3').width(196).height(196).css({'left':196*2+(gutters)*2,'top':((196)*2+headerHeight+gutters*6)});
            jQuery('#row3 .cell4').width(196).height(196).css({'left':196*3+(gutters)*3,'top':((196)*2+headerHeight+gutters*6)});
            jQuery('#row3 .cell5').width(196).height(196).css({'left':196*4+(gutters)*4,'top':((196)*2+headerHeight+gutters*6)});

			// Row 4
            jQuery('#row4 .cell1').width(196).height(196).css({'left':'0','top':((196)*3+headerHeight+gutters*7)});
            jQuery('#row4 .cell2').width(196).height(196).css({'left':196+(gutters),'top':((196)*3+headerHeight+gutters*7)});
            jQuery('#row4 .cell3').width(196).height(196).css({'left':196*2+(gutters)*2,'top':((196)*3+headerHeight+gutters*7)});
            jQuery('#row4 .cell4').width(196).height(196).css({'left':196*3+(gutters)*3,'top':((196)*3+headerHeight+gutters*7)});
            jQuery('#row4 .cell5').width(196).height(196).css({'left':196*4+(gutters)*4,'top':((196)*3+headerHeight+gutters*7)});
            
            	// Row 5
            jQuery('#row5 .cell1').width(196).height(196).css({'left':'0','top':((196)*4+headerHeight+gutters*8)});
            jQuery('#row5 .cell2').width(196).height(196).css({'left':196+(gutters),'top':((196)*4+headerHeight+gutters*8)});
            jQuery('#row5 .cell3').width(196).height(196).css({'left':196*2+(gutters)*2,'top':((196)*4+headerHeight+gutters*8)});
            jQuery('#row5 .cell4').width(196).height(196).css({'left':196*3+(gutters)*3,'top':((196)*4+headerHeight+gutters*8)});
            jQuery('#row5 .cell5').width(196).height(196).css({'left':196*4+(gutters)*4,'top':((196)*4+headerHeight+gutters*8)});
            
             	// Row 6
            jQuery('#row6 .cell1').width(196).height(196).css({'left':'0','top':((196)*5+headerHeight+gutters*9)});
            jQuery('#row6 .cell2').width(196).height(196).css({'left':196+(gutters),'top':((196)*5+headerHeight+gutters*9)});
            jQuery('#row6 .cell3').width(196).height(196).css({'left':196*2+(gutters)*2,'top':((196)*5+headerHeight+gutters*9)});
            jQuery('#row6 .cell4').width(196).height(196).css({'left':196*3+(gutters)*3,'top':((196)*5+headerHeight+gutters*9)});
            jQuery('#row6 .cell5').width(196).height(196).css({'left':196*4+(gutters)*4,'top':((196)*5+headerHeight+gutters*9)});
            
            tempCells = jQuery('#row6').attr("class"); checkGutter(); checkFraction();
            currCell = jQuery('#row6 .cell'+(x+1));
              /*  for (x=0;x<=tempCells;x++) {
                    jQuery('#row5 .cell'+(x+1)).width(currFraction-(gutters*gutterFraction)).height(winFifth-(gutters*0.75)).css({'left':currFraction*x+(gutters/tempCells)*x,'top':(winFifth-(gutters*0.75)*4+headerHeight+gutters*8)+rowOffset});
                } */
            jQuery('img.quarter, #newsThumbnail').css({'width':'100%','height':'100%'});
            jQuery('.half .ovr_mid_news').css({'padding':'0 20%','width':'60%'});
            
            rowOffset = 196*5+gutters*8+headerHeight;
            jQuery('section#rows').css('height',(196*newsRows+gutters*newsRows-headerHeight+40));
            //jQuery('.content').parent().css('height',((winFifth-(gutters*0.75))*3+headerHeight+gutters*6));
            
        }    
        
        
        // MP - TEMPLATE 'a'
        
        mpEnable = jQuery('#mpEnable').hasClass("enabled");
        mpTemplateA = jQuery('#mpEnable').hasClass("a")
        
        if (mpEnable && mpTemplateA) {
            var rowOffset = '0';
            
            var mpP = jQuery('.ovr_mid_p');
            var mpH2 = jQuery('ovr_mid_h2');
            var mpH4 = jQuery('.shop_now_mp');
            
            mpP.addClass("mpHide");
            mpH2.addClass("mpHide");
            mpH4.addClass("mpHide");
            
            // Row 1
            jQuery('#row1 .cell1').width(winFull).height(globalHeight*1.35).css('left','0');
            //jQuery('#row1 .cell2').width(winQuarter-(gutters/2)).height(globalHeight*1.35).css('left',winThreeQuarter+gutters/2);
            rowOffset = (globalHeight*1.35)-globalHeight;
            // Row 2
            var row2cell1 = jQuery('#row2 .cell1').width(winHalf).height(globalHeight).css({'left':'0','top':(globalHeight+headerHeight+gutters*5+rowOffset)});
            
            jQuery('#row2 .cell2').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winHalf+(gutters/2)*2,'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
            jQuery('#row2 .cell3').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winQuarter+winHalf+(gutters/2)*3,'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
            // Row 3
            jQuery('#row3 .cell1').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':'0','top':(globalHeight*2+headerHeight+gutters*6+rowOffset)});
            jQuery('#row3 .cell2').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winQuarter+(gutters/2)*1,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
            jQuery('#row3 .cell3').width(winHalf-(gutters/2)).height(globalHeight).css({'left':winQuarter*2+(gutters/2)*2,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
            // Row 4
            jQuery('#row4 .cell1').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':'0','top':(globalHeight*3+headerHeight+gutters*7+rowOffset)});
            jQuery('#row4 .cell2').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winQuarter+(gutters/2)*1,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});
            jQuery('#row4 .cell3').width(winHalf-(gutters/2)).height(globalHeight).css({'left':winQuarter*2+(gutters/2)*2,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});

            jQuery('.full').css({'height':'100%','width':'100%'});
            jQuery('.eighth img').css({'width':'100%','height':'100%'});
            jQuery('section#rows').css('height',(globalHeight)*4+headerHeight+gutters*7+rowOffset);
            jQuery('.half .ovr_mid_focus').css({'padding':'0 20%','width':'60%'});
            
            // Reveal CTA & subheader on hover
            jQuery('.quarter, .half, .threequarter').mouseover(function(){
                jQuery(this).find(mpP).removeClass("mpHide");
                jQuery(this).find(mpH4).removeClass("mpHide");
            });
            jQuery('.quarter, .half, .threequarter').mouseout(function(){
                jQuery(this).find(mpP).addClass("mpHide");
                jQuery(this).find(mpH4).addClass("mpHide");
            });    
        }    
        
        // MP - TEMPLATE 'b'
        
        mpEnable = jQuery('#mpEnable').hasClass('enabled');
        mpTemplateB = jQuery('#mpEnable').hasClass("b")
        
        if (mpEnable && mpTemplateB) {
            var rowOffset = 0;
            
            var mpP = jQuery('.ovr_mid_p');
            var mpH2 = jQuery('ovr_mid_h2');
            var mpH4 = jQuery('.shop_now_mp');
            
            mpP.addClass("mpHide");
            mpH2.addClass("mpHide");
            mpH4.addClass("mpHide");
            
            // Row 1
            jQuery('#row1 .cell1').width(winFull).height(globalHeight*1.35).css('left','0');
            //jQuery('#row1 .cell2').width(winQuarter-(gutters/2)).height(globalHeight*1.35).css('left',winThreeQuarter+gutters/2);
            rowOffset = (globalHeight*1.35)-globalHeight;
            // Row 2
            jQuery('#row2 .cell1').width(winHalf).height(globalHeight).css({'left':'0','top':(globalHeight+headerHeight+gutters*5+rowOffset)});
            jQuery('#row2 .cell2').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winHalf+(gutters/2)*2,'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
            jQuery('#row2 .cell3').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winQuarter+winHalf+(gutters/2)*3,'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
            // Row 3
            jQuery('#row3 .cell1').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':'0','top':(globalHeight*2+headerHeight+gutters*6+rowOffset)});
            jQuery('#row3 .cell2').width(winHalf).height(globalHeight).css({'left':winQuarter+(gutters/2)*1,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
            jQuery('#row3 .cell3').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winQuarter*3+(gutters/2)*3,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
            // Row 4
            jQuery('#row4 .cell1').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':'0','top':(globalHeight*3+headerHeight+gutters*7+rowOffset)});
            jQuery('#row4 .cell2').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winQuarter+(gutters/2)*1,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});
            jQuery('#row4 .cell3').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winQuarter*2+(gutters/2)*2,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});
            jQuery('#row4 .cell4').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winQuarter*3+(gutters/2)*3,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});

            jQuery('.full').css({'height':'100%','width':'100%'});
            jQuery('.eighth img').css({'width':'100%','height':'100%'});
            jQuery('section#rows').css('height',(globalHeight)*4+headerHeight+gutters*7+rowOffset-41);
            jQuery('.content').parent().css('height',(globalHeight)*4+headerHeight+gutters*7+rowOffset-41);
            jQuery('.half .ovr_mid_focus').css({'padding':'0 20%','width':'60%'});
            
            // Reveal CTA & subheader on hover
            jQuery('.quarter, .half, .threequarter').mouseover(function(){
                jQuery(this).find(mpP).removeClass("mpHide");
                jQuery(this).find(mpH4).removeClass("mpHide");
            });
            jQuery('.quarter, .half, .threequarter').mouseout(function(){
                jQuery(this).find(mpP).addClass("mpHide");
                jQuery(this).find(mpH4).addClass("mpHide");
            });
            
            jQuery('.quarter, .half, .threequarter').mouseover(function(){
                jQuery(this).find(mpH4).parent().parent().addClass("darkBg");
            });
            
            jQuery('.quarter, .half, .threequarter').mouseout(function(){
                jQuery(this).find(mpH4).parent().parent().removeClass("darkBg");
            });
           
        }    
        
        
        // MP - TEMPLATE 'c'
        
        mpEnable = jQuery('#mpEnable').hasClass("enabled");
        mpTemplateC = jQuery('#mpEnable').hasClass("c");
        mpPagination = 16;
        if (mpEnable && mpTemplateC && winWidth > 1024) {
            var rowOffset = 0;
            
            var mpP = jQuery('.ovr_mid_p');
            var mpH2 = jQuery('ovr_mid_h2');
            var mpH4 = jQuery('.shop_now_mp');
            
            mpP.addClass("mpHide");
            mpH2.addClass("mpHide");
            mpH4.addClass("mpHide");
            var paginationHeight = jQuery('#row5 .cell1 section').height();
            
            mpPagination = paginationHeight + mpPagination;
            
            // Row 1
            jQuery('#row1 .cell1').width(winFull).height(globalHeight*1.35).css('left','0');
            //jQuery('#row1 .cell2').width(winQuarter-(gutters/2)).height(globalHeight*1.35).css('left',winThreeQuarter+gutters/2);
            rowOffset = (globalHeight*1.35)-globalHeight;
            // Row 2
            jQuery('#row2 .cell1').width(winHalf).height(globalHeight).css({'left':'0','top':(globalHeight+headerHeight+gutters*5+rowOffset)});
            jQuery('#row2 .cell2').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winHalf+(gutters/2)*2,'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
            jQuery('#row2 .cell3').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winQuarter+winHalf+(gutters/2)*3,'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
            // Row 3
            jQuery('#row3 .cell1').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':'0','top':(globalHeight*2+headerHeight+gutters*6+rowOffset)});
            jQuery('#row3 .cell2').width(winHalf).height(globalHeight).css({'left':winQuarter+(gutters/2)*1,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
            jQuery('#row3 .cell3').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winQuarter*3+(gutters/2)*3,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
            // Row 4
            jQuery('#row4 .cell1').width(winHalf-(gutters/2)).height(globalHeight).css({'left':'0','top':(globalHeight*3+headerHeight+gutters*7+rowOffset)});
            jQuery('#row4 .cell2').width(winHalf-(gutters/2)).height(globalHeight).css({'left':winHalf+(gutters/2)*1,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});
            // Row 5 - Pagination
            jQuery('#row5 .cell1').width(winFull).height(mpPagination).css({'left':'0','top':(globalHeight*4+headerHeight+gutters*8+rowOffset)});
            
            
            jQuery('img.full').css({'height':'100%','width':'100%'});
            jQuery('.eighth img').css({'width':'100%','height':'100%'});
            jQuery('section#rows').css('height',(globalHeight)*4+headerHeight+mpPagination+gutters*7+rowOffset-41);
            jQuery('.content').parent().css('height',(globalHeight)*4+headerHeight+mpPagination+gutters*7+rowOffset-41);
            jQuery('.half .ovr_mid_focus').css({'padding':'0 20%','width':'60%'});
            
            // Reveal CTA & subheader on hover
            jQuery('.quarter, .half, .threequarter').mouseover(function(){
                jQuery(this).find(mpP).removeClass("mpHide");
                jQuery(this).find(mpH4).removeClass("mpHide");
            });
            jQuery('.quarter, .half, .threequarter').mouseout(function(){
                jQuery(this).find(mpP).addClass("mpHide");
                jQuery(this).find(mpH4).addClass("mpHide");
            });
            
            jQuery('.quarter, .half, .threequarter').mouseover(function(){
                jQuery(this).find(mpH4).parent().parent().addClass("darkBg");
            });
            
            jQuery('.quarter, .half, .threequarter').mouseout(function(){
                jQuery(this).find(mpH4).parent().parent().removeClass("darkBg");
            });
           
        } else if (mpEnable && mpTemplateC && winWidth <= 1024) {
            var rowOffset = '0';
            
            var mpP = jQuery('.ovr_mid_p');
            var mpH2 = jQuery('ovr_mid_h2');
            var mpH4 = jQuery('.shop_now_mp');
            var paginationHeight = jQuery('#row5 .cell1 section').height();
            
            mpPagination = paginationHeight + mpPagination;
            
            mpP.addClass("mpHide");
            mpH2.addClass("mpHide");
            mpH4.addClass("mpHide");
            
            // Row 1
            jQuery('#row1 .cell1').width(1009).height(375).css('left','0');
            //jQuery('#row1 .cell2').width(winQuarter-(gutters/2)).height(globalHeight*1.35).css('left',winThreeQuarter+gutters/2);
            rowOffset = (278*1.35)-278;
            // Row 2
            jQuery('#row2 .cell1').width(505).height(278).css({'left':'0','top':(278+headerHeight+8*5+rowOffset)});
            jQuery('#row2 .cell2').width(248).height(278).css({'left':505+8,'top':((278)*1+headerHeight+8*5+rowOffset)});
            jQuery('#row2 .cell3').width(248).height(278).css({'left':248+505+8*2,'top':((278)*1+headerHeight+8*5+rowOffset)});
            // Row 3
            jQuery('#row3 .cell1').width(248).height(278).css({'left':'0','top':(278*2+headerHeight+gutters*6+rowOffset)});
            jQuery('#row3 .cell2').width(505).height(278).css({'left':248+8,'top':(278*2+headerHeight+gutters*6+rowOffset)});
            jQuery('#row3 .cell3').width(248).height(278).css({'left':248+505+16,'top':(278*2+headerHeight+gutters*6+rowOffset)});
            // Row 4
            jQuery('#row4 .cell1').width(505).height(278).css({'left':'0','top':(278*3+headerHeight+gutters*7+rowOffset)});
            jQuery('#row4 .cell2').width(505).height(278).css({'left':505+8,'top':((278)*3+headerHeight+gutters*7+rowOffset)});
            
            // Row 5 - Pagination
            jQuery('#row5 .cell1').width(1024).height(mpPagination).css({'left':'0','top':((278)*4+headerHeight+gutters*8+rowOffset)});
            
            jQuery('img.full').css({'height':'100%','width':'100%'});
            jQuery('.eighth img, .quarter img, .half img').css({'width':'100%','height':'100%'});
            jQuery('section#rows').css('height',(278)*4+headerHeight+mpPagination+gutters*7+rowOffset-41);
            jQuery('.content').parent().css('height',(278)*4+headerHeight+mpPagination+gutters*7+rowOffset-41);
            jQuery('.half .ovr_mid_focus').css({'padding':'0 20%','width':'60%'});
            
            // Reveal CTA & subheader on hover
            jQuery('.quarter, .half, .threequarter').mouseover(function(){
                jQuery(this).find(mpP).removeClass("mpHide");
                jQuery(this).find(mpH4).removeClass("mpHide");
            });
            jQuery('.quarter, .half, .threequarter').mouseout(function(){
                jQuery(this).find(mpP).addClass("mpHide");
                jQuery(this).find(mpH4).addClass("mpHide");
            });
            
            jQuery('.quarter, .half, .threequarter').mouseover(function(){
                jQuery(this).find(mpH4).parent().parent().addClass("darkBg");
            });
            
            jQuery('.quarter, .half, .threequarter').mouseout(function(){
                jQuery(this).find(mpH4).parent().parent().removeClass("darkBg");
            });
           
        } 
        
        
        // MP - TEMPLATE 'd'
        
        mpEnable = jQuery('#mpEnable').hasClass("enabled");
        mpTemplateD = jQuery('#mpEnable').hasClass("d");
        mpPagination = 16;
        if (mpEnable && mpTemplateD && winWidth > 1024) {
            var rowOffset = 0;
            
            var mpP = jQuery('.ovr_mid_p');
            var mpH2 = jQuery('ovr_mid_h2');
            var mpH4 = jQuery('.shop_now_mp');
            var paginationHeight = jQuery('#row5 .cell1 section').height();
            
            mpPagination = paginationHeight + mpPagination;
            
            mpP.addClass("mpHide");
            mpH2.addClass("mpHide");
            mpH4.addClass("mpHide");
            
            // Row 1
            jQuery('#row1 .cell1').width(winFull).height(globalHeight*1.35).css('left','0');
            //jQuery('#row1 .cell2').width(winQuarter-(gutters/2)).height(globalHeight*1.35).css('left',winThreeQuarter+gutters/2);
            rowOffset = (globalHeight*1.35)-globalHeight;
            // Row 2
            jQuery('#row2 .cell1').width(winHalf).height(globalHeight).css({'left':'0','top':(globalHeight+headerHeight+gutters*5+rowOffset)});
            jQuery('#row2 .cell2').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winHalf+(gutters/2)*2,'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
            jQuery('#row2 .cell3').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winQuarter+winHalf+(gutters/2)*3,'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
            // Row 3
            jQuery('#row3 .cell1').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':'0','top':(globalHeight*2+headerHeight+gutters*6+rowOffset)});
            jQuery('#row3 .cell2').width(winHalf).height(globalHeight).css({'left':winQuarter+(gutters/2)*1,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
            jQuery('#row3 .cell3').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winQuarter*3+(gutters/2)*3,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
            // Row 4
            jQuery('#row4 .cell1').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':'0','top':(globalHeight*3+headerHeight+gutters*7+rowOffset)});
            jQuery('#row4 .cell2').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winQuarter+(gutters/2)*1,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});
            jQuery('#row4 .cell3').width(winHalf-(gutters/2)).height(globalHeight).css({'left':winQuarter*2+(gutters/2)*2,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});
            // Row 5 - Pagination
            jQuery('#row5 .cell1').width(winFull).height(mpPagination).css({'left':'0','top':(globalHeight*4+headerHeight+gutters*8+rowOffset)});
            
            jQuery('img.full').css({'height':'100%','width':'100%'});
            jQuery('.eighth img').css({'width':'100%','height':'100%'});
            jQuery('section#rows').css('height',(globalHeight)*4+(mpPagination)+headerHeight+gutters*8+rowOffset-41);
            jQuery('.content').parent().css('height',(globalHeight)*4+(mpPagination)+headerHeight+gutters*8+rowOffset-41);
            jQuery('.half .ovr_mid_focus').css({'padding':'0 20%','width':'60%'});
            
            // Reveal CTA & subheader on hover
            jQuery('.quarter, .half, .threequarter').mouseover(function(){
                jQuery(this).find(mpP).removeClass("mpHide");
                jQuery(this).find(mpH4).removeClass("mpHide");
            });
            jQuery('.quarter, .half, .threequarter').mouseout(function(){
                jQuery(this).find(mpP).addClass("mpHide");
                jQuery(this).find(mpH4).addClass("mpHide");
            });
            
            jQuery('.quarter, .half, .threequarter').mouseover(function(){
                jQuery(this).find(mpH4).parent().parent().addClass("darkBg");
            });
            
            jQuery('.quarter, .half, .threequarter').mouseout(function(){
                jQuery(this).find(mpH4).parent().parent().removeClass("darkBg");
            });
           
        } else if (mpEnable && mpTemplateD && winWidth <= 1024) {
            var rowOffset = '0';
            
            var mpP = jQuery('.ovr_mid_p');
            var mpH2 = jQuery('ovr_mid_h2');
            var mpH4 = jQuery('.shop_now_mp');
            var paginationHeight = jQuery('#row5 .cell1 section').height();
            
            mpPagination = paginationHeight + mpPagination;
            
            mpP.addClass("mpHide");
            mpH2.addClass("mpHide");
            mpH4.addClass("mpHide");
            
            // Row 1
            jQuery('#row1 .cell1').width(1009).height(375).css('left','0');
            //jQuery('#row1 .cell2').width(winQuarter-(gutters/2)).height(globalHeight*1.35).css('left',winThreeQuarter+gutters/2);
            rowOffset = (278*1.35)-278;
            // Row 2
            jQuery('#row2 .cell1').width(505).height(278).css({'left':'0','top':(278+headerHeight+8*5+rowOffset)});
            jQuery('#row2 .cell2').width(248).height(278).css({'left':505+8,'top':((278)*1+headerHeight+8*5+rowOffset)});
            jQuery('#row2 .cell3').width(248).height(278).css({'left':248+505+8*2,'top':((278)*1+headerHeight+8*5+rowOffset)});
            // Row 3
            jQuery('#row3 .cell1').width(248).height(278).css({'left':'0','top':(278*2+headerHeight+gutters*6+rowOffset)});
            jQuery('#row3 .cell2').width(505).height(278).css({'left':248+8,'top':(278*2+headerHeight+gutters*6+rowOffset)});
            jQuery('#row3 .cell3').width(248).height(278).css({'left':248+505+16,'top':(278*2+headerHeight+gutters*6+rowOffset)});
            // Row 4
            jQuery('#row4 .cell1').width(248).height(278).css({'left':'0','top':(278*3+headerHeight+gutters*7+rowOffset)});
            jQuery('#row4 .cell2').width(248).height(278).css({'left':248+8,'top':((278)*3+headerHeight+gutters*7+rowOffset)});
            jQuery('#row4 .cell3').width(505).height(278).css({'left':248*2+8*2,'top':((278)*3+headerHeight+gutters*7+rowOffset)});
            // Row 5 - Pagination
            jQuery('#row5 .cell1').width(1024).height(mpPagination).css({'left':'0','top':((278)*4+headerHeight+gutters*8+rowOffset)});
            
            
            jQuery('img.full').css({'height':'100%','width':'100%'});
            jQuery('.eighth img, .quarter img, .half img').css({'width':'100%','height':'100%'});
            jQuery('section#rows').css('height',(278)*4+headerHeight+mpPagination+gutters*7+rowOffset-41);
            jQuery('.content').parent().css('height',(278)*4+headerHeight+mpPagination+gutters*7+rowOffset-41);
            jQuery('.half .ovr_mid_focus').css({'padding':'0 20%','width':'60%'});
            
            // Reveal CTA & subheader on hover
            jQuery('.quarter, .half, .threequarter').mouseover(function(){
                jQuery(this).find(mpP).removeClass("mpHide");
                jQuery(this).find(mpH4).removeClass("mpHide");
            });
            jQuery('.quarter, .half, .threequarter').mouseout(function(){
                jQuery(this).find(mpP).addClass("mpHide");
                jQuery(this).find(mpH4).addClass("mpHide");
            });
            
            jQuery('.quarter, .half, .threequarter').mouseover(function(){
                jQuery(this).find(mpH4).parent().parent().addClass("darkBg");
            });
            
            jQuery('.quarter, .half, .threequarter').mouseout(function(){
                jQuery(this).find(mpH4).parent().parent().removeClass("darkBg");
            });
           
        } 
        
        
        // MP - TEMPLATE 'e'
        
        mpEnable = jQuery('#mpEnable').hasClass("enabled");
        mpTemplateE = jQuery('#mpEnable').hasClass("e");
        mpPagination = 8;
        if (mpEnable && mpTemplateE && winWidth > 1024) {
            var rowOffset = 0;
            
            var mpP = jQuery('.ovr_mid_p');
            var mpH2 = jQuery('ovr_mid_h2');
            var mpH4 = jQuery('.shop_now_mp');
            var paginationHeight = jQuery('#row5 .cell1 section').height();
            
            mpPagination = paginationHeight + mpPagination;
            
            mpP.addClass("mpHide");
            mpH2.addClass("mpHide");
            mpH4.addClass("mpHide");
            
            // Row 1
            jQuery('#row1 .cell1').width(winFull).height(globalHeight*1.35).css('left','0');
            //jQuery('#row1 .cell2').width(winQuarter-(gutters/2)).height(globalHeight*1.35).css('left',winThreeQuarter+gutters/2);
            rowOffset = (globalHeight*1.35)-globalHeight;
            // Row 2
            jQuery('#row2 .cell1').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':'0','top':(globalHeight+headerHeight+gutters*5+rowOffset)});
            jQuery('#row2 .cell2').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winQuarter+(gutters/2),'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
            jQuery('#row2 .cell3').width(winHalf).height(globalHeight).css({'left':winQuarter*2+(gutters/2)*2,'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
            // Row 3
            jQuery('#row3 .cell1').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':'0','top':(globalHeight*2+headerHeight+gutters*6+rowOffset)});
            jQuery('#row3 .cell2').width(winHalf).height(globalHeight).css({'left':winQuarter+(gutters/2)*1,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
            jQuery('#row3 .cell3').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winQuarter*3+(gutters/2)*3,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
            // Row 4
            jQuery('#row4 .cell1').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':'0','top':(globalHeight*3+headerHeight+gutters*7+rowOffset)});
            jQuery('#row4 .cell2').width(winQuarter-(gutters/2)).height(globalHeight).css({'left':winQuarter+(gutters/2)*1,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});
            jQuery('#row4 .cell3').width(winHalf-(gutters/2)).height(globalHeight).css({'left':winQuarter*2+(gutters/2)*2,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});
            // Row 5 - Pagination  BINGO
            jQuery('#row5 .cell1').width(winFull).height(mpPagination).css({'left':'0','top':(globalHeight*4+headerHeight+gutters*8+rowOffset)});
            
            jQuery('img.full').css({'height':'100%','width':'100%'});
            jQuery('.eighth img').css({'width':'100%','height':'100%'});
            jQuery('section#rows').css('height',(globalHeight)*4+(mpPagination)+headerHeight+gutters*8+rowOffset-41);
            jQuery('.content').parent().css('height',(globalHeight)*4+(mpPagination)+gutters*8+rowOffset-41);
            jQuery('.half .ovr_mid_focus').css({'padding':'0 20%','width':'60%'});
            
            // Reveal CTA & subheader on hover
            jQuery('.quarter, .half, .threequarter').mouseover(function(){
                jQuery(this).find(mpP).removeClass("mpHide");
                jQuery(this).find(mpH4).removeClass("mpHide");
            });
            jQuery('.quarter, .half, .threequarter').mouseout(function(){
                jQuery(this).find(mpP).addClass("mpHide");
                jQuery(this).find(mpH4).addClass("mpHide");
            });
            
            jQuery('.quarter, .half, .threequarter').mouseover(function(){
                jQuery(this).find(mpH4).parent().parent().addClass("darkBg");
            });
            
            jQuery('.quarter, .half, .threequarter').mouseout(function(){
                jQuery(this).find(mpH4).parent().parent().removeClass("darkBg");
            });
           
        } else if (mpEnable && mpTemplateE && winWidth <= 1024) {
            var rowOffset = '0';
            
            var mpP = jQuery('.ovr_mid_p');
            var mpH2 = jQuery('ovr_mid_h2');
            var mpH4 = jQuery('.shop_now_mp');
            var paginationHeight = jQuery('#row5 .cell1 section').height();
            
            mpPagination = paginationHeight + mpPagination;
            
            mpP.addClass("mpHide");
            mpH2.addClass("mpHide");
            mpH4.addClass("mpHide");
            
            // Row 1
            jQuery('#row1 .cell1').width(1009).height(375).css('left','0');
            //jQuery('#row1 .cell2').width(winQuarter-(gutters/2)).height(globalHeight*1.35).css('left',winThreeQuarter+gutters/2);
            rowOffset = (278*1.35)-278;
            // Row 2
            jQuery('#row2 .cell1').width(248).height(278).css({'left':'0','top':(278+headerHeight+8*5+rowOffset)});
            jQuery('#row2 .cell2').width(248).height(278).css({'left':248+8,'top':((278)*1+headerHeight+8*5+rowOffset)});
            jQuery('#row2 .cell3').width(505).height(278).css({'left':248*2+8*2,'top':((278)*1+headerHeight+8*5+rowOffset)});
            // Row 3
            jQuery('#row3 .cell1').width(248).height(278).css({'left':'0','top':(278*2+headerHeight+gutters*6+rowOffset)});
            jQuery('#row3 .cell2').width(505).height(278).css({'left':248+8,'top':(278*2+headerHeight+gutters*6+rowOffset)});
            jQuery('#row3 .cell3').width(248).height(278).css({'left':248+505+16,'top':(278*2+headerHeight+gutters*6+rowOffset)});
            // Row 4
            jQuery('#row4 .cell1').width(248).height(278).css({'left':'0','top':(278*3+headerHeight+gutters*7+rowOffset)});
            jQuery('#row4 .cell2').width(248).height(278).css({'left':248+8,'top':((278)*3+headerHeight+gutters*7+rowOffset)});
            jQuery('#row4 .cell3').width(505).height(278).css({'left':248*2+8*2,'top':((278)*3+headerHeight+gutters*7+rowOffset)});
            // Row 5 - Pagination
            jQuery('#row5 .cell1').width(1024).height(mpPagination).css({'left':'0','top':((278)*4+headerHeight+gutters*8+rowOffset)});
            
            
            jQuery('img.full').css({'height':'100%','width':'100%'});
            jQuery('.eighth img, .quarter img, .half img').css({'width':'100%','height':'100%'});
            jQuery('section#rows').css('height',(278)*4+headerHeight+mpPagination+gutters*7+rowOffset-41);
            jQuery('.content').parent().css('height',(278)*4+mpPagination+gutters*7+rowOffset-33);
            jQuery('.half .ovr_mid_focus').css({'padding':'0 20%','width':'60%'});
            
            // Reveal CTA & subheader on hover
            jQuery('.quarter, .half, .threequarter').mouseover(function(){
                jQuery(this).find(mpP).removeClass("mpHide");
                jQuery(this).find(mpH4).removeClass("mpHide");
            });
            jQuery('.quarter, .half, .threequarter').mouseout(function(){
                jQuery(this).find(mpP).addClass("mpHide");
                jQuery(this).find(mpH4).addClass("mpHide");
            });
            
            jQuery('.quarter, .half, .threequarter').mouseover(function(){
                jQuery(this).find(mpH4).parent().parent().addClass("darkBg");
            });
            
            jQuery('.quarter, .half, .threequarter').mouseout(function(){
                jQuery(this).find(mpH4).parent().parent().removeClass("darkBg");
            });
           
        } 
        
        
        // EDITORIAL - TEMPLATES
        //if (winWidth > 1024 && format == 'EDITORIAL' && template == 'B') {
        if (jQuery('#editorialEnable').hasClass('enabled')) {
            editorialEnable = true;
        }
        if (jQuery('#editorialTemplate').hasClass('a')) {
            editorialTemplateA = true;
        }
        
        if (editorialEnable && winWidth > 1024 && editorialTemplateA) {
             
            function editorialInitA() {
                var rowOffset = '0';
                // Row 1
                jQuery('#row1 .cell1').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css('left','0');
                jQuery('#row1 .cell2').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css('left',winThird+gutters/2);
                jQuery('#row1 .cell3').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css('left',winThird*2+gutters);
                rowOffset = ((winThird-(gutters/2))*1.5)-globalHeight;
                // Row 2
                jQuery('#row2 .cell1').width(winTwoThird).height((winThird-(gutters/2))*1.5).css({'left':'0','top':(globalHeight+headerHeight+gutters*5+rowOffset)});
                jQuery('#row2 .cell2').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':winTwoThird+(gutters/2)*2,'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
                rowOffset = rowOffset+((winThird-(gutters/2))*1.5)-globalHeight;
                // Row 3
                jQuery('#row3 .cell1').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':'0','top':(globalHeight*2+headerHeight+gutters*6+rowOffset)});
                jQuery('#row3 .cell2').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':winThird+(gutters/2)*1,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
                jQuery('#row3 .cell3').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':winThird*2+(gutters/2)*2,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
                rowOffset = rowOffset+((winThird-(gutters/2))*1.5)-globalHeight;
                // Row 4
                jQuery('#row4 .cell1').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':'0','top':(globalHeight*3+headerHeight+gutters*7+rowOffset)});
                jQuery('#row4 .cell2').width(winTwoThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':winThird+(gutters/2)*1,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});
                rowOffset = rowOffset+((winThird-(gutters/2))*1.5)-globalHeight;
                // Row 5
                jQuery('#row5 .cell1').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':'0','top':(globalHeight*4+headerHeight+gutters*8+rowOffset)});
                jQuery('#row5 .cell2').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':winThird+(gutters/2)*1,'top':((globalHeight)*4+headerHeight+gutters*8+rowOffset)}); 
                jQuery('#row5 .cell3').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':winThird*2+(gutters/2)*2,'top':((globalHeight)*4+headerHeight+gutters*8+rowOffset)}); 
                rowOffset = rowOffset+((winThird-(gutters/2))*1.5)-globalHeight;
                
                jQuery('.content').parent().css('height',((globalHeight)*5+headerHeight+gutters*12+rowOffset-32));
                
                jQuery('.full').css({'height':'100%','width':'100%'});
                jQuery('.eighth img').css({'width':'100%','height':'100%'});
                //jQuery('section#rows').css('height',(globalHeight)*5.7+headerHeight+gutters*8+rowOffset);
                jQuery('.half .ovr_mid_focus').css({'padding':'0 20%','width':'60%'});

                
                /*****/// BEGIN: EDITORIAL IMAGE MAP BINDING SCRIPT
                /**/jQuery('.ediImg').each(function(){
                /**/    jQuery(this).mouseover(function(){
                /**/        // Make sure only one map is bound at a time
                /**/        jQuery('.ediImg').each(function(){
                /**/            jQuery(this).mapster('unbind');
                /**/        });
                /**/        // Initialize on hover
                /**/        jQuery(this).mapster().mapster('resize');
                /**/        initTooltips();
                /**/    });
                /**/    
                /**/    jQuery(window).resize(function(){
                /**/        // Make sure no maps are bound during the resize event to avoid canvas locking
                /**/        jQuery('.ediImg').each(function(){
                /**/            jQuery(this).mapster('unbind');
                /**/        });
                /**/    });
                /**/    
                /**/});
                /*****/// END: EDITORIAL IMAGE MAP BINDING SCRIPT
            }
            editorialInitA();
            initTooltips();
        } else if (editorialEnable && winWidth <= 1024 && editorialTemplateA) {
    
            function editorialInitMinA() {
                var rowOffset = 0;
                // Row 1
                jQuery('#row1 .cell1').width(332).height(499).css('left','0');
                jQuery('#row1 .cell2').width(332).height(499).css('left',332+gutters);
                jQuery('#row1 .cell3').width(332).height(499).css('left',332*2+gutters*2);
                rowOffset = (499-globalHeight);
                // Row 2
                jQuery('#row2 .cell1').width(673).height(499).css({'left':'0','top':(globalHeight+headerHeight+gutters*5+rowOffset)});
                jQuery('#row2 .cell2').width(332-(gutters/2)).height(499).css({'left':673+(gutters/2)*2,'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
                rowOffset = rowOffset+499-globalHeight;
                // Row 3
                jQuery('#row3 .cell1').width(332).height(499).css({'left':'0','top':(globalHeight*2+headerHeight+gutters*6+rowOffset)});
                jQuery('#row3 .cell2').width(332).height(499).css({'left':332+(gutters)*1,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
                jQuery('#row3 .cell3').width(332).height(499).css({'left':332*2+(gutters)*2,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
                rowOffset = rowOffset+499-globalHeight;
                // Row 4
                jQuery('#row4 .cell1').width(332).height(499).css({'left':'0','top':(globalHeight*3+headerHeight+gutters*7+rowOffset)});
                jQuery('#row4 .cell2').width(673).height(499).css({'left':332+(gutters)*1,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});
                rowOffset = rowOffset+499-globalHeight;
                // Row 5
                jQuery('#row5 .cell1').width(332).height(499).css({'left':'0','top':(globalHeight*4+headerHeight+gutters*8+rowOffset)});
                jQuery('#row5 .cell2').width(332).height(499).css({'left':332+(gutters)*1,'top':((globalHeight)*4+headerHeight+gutters*8+rowOffset)}); 
                jQuery('#row5 .cell3').width(332).height(499).css({'left':332*2+(gutters)*2,'top':((globalHeight)*4+headerHeight+gutters*8+rowOffset)}); 
                rowOffset = rowOffset+499-globalHeight;
                
                jQuery('.content').parent().css('height',((globalHeight)*5+headerHeight+gutters*12+rowOffset-32));
                
                jQuery('.full').css({'height':'100%','width':'100%'});
                jQuery('.eighth img').css({'width':'100%','height':'100%'});
                //jQuery('section#rows').css('height',(globalHeight)*5.7+headerHeight+gutters*8+rowOffset);
                jQuery('.half .ovr_mid_focus').css({'padding':'0 20%','width':'60%'});

                
                /*****/// BEGIN: EDITORIAL IMAGE MAP BINDING SCRIPT
                /**/jQuery('.ediImg').each(function(){
                /**/    jQuery(this).mouseover(function(){
                /**/        // Make sure only one map is bound at a time
                /**/        jQuery('.ediImg').each(function(){
                /**/            jQuery(this).mapster('unbind');
                /**/        });
                /**/        // Initialize on hover
                /**/        jQuery(this).mapster().mapster('resize');
                /**/        initTooltips();
                /**/    });
                /**/    
                /**/    jQuery(window).resize(function(){
                /**/        // Make sure no maps are bound during the resize event to avoid canvas locking
                /**/        jQuery('.ediImg').each(function(){
                /**/            jQuery(this).mapster('unbind');
                /**/        });
                /**/    });
                /**/    
                /**/});
                /*****/// END: EDITORIAL IMAGE MAP BINDING SCRIPT
            }
            editorialInitMinA();
            initTooltips();
        } 
        
        if (jQuery('#editorialTemplate').hasClass('b')) {
            editorialTemplateB = true;
        }
        
        if (editorialEnable && winWidth > 1024 && editorialTemplateB) {
             
            function editorialInitB() {
                var rowOffset = '0';
                // Row 1
                jQuery('#row1 .cell1').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css('left','0');
                jQuery('#row1 .cell2').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css('left',winThird+gutters/2);
                jQuery('#row1 .cell3').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css('left',winThird*2+gutters);
                rowOffset = ((winThird-(gutters/2))*1.5)-globalHeight;
                // Row 2
                jQuery('#row2 .cell1').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':'0','top':(globalHeight+headerHeight+gutters*5+rowOffset)});
                jQuery('#row2 .cell2').width(winTwoThird).height((winThird-(gutters/2))*1.5).css({'left':winThird+(gutters/2),'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
                rowOffset = rowOffset+((winThird-(gutters/2))*1.5)-globalHeight;
                // Row 3
                jQuery('#row3 .cell1').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':'0','top':(globalHeight*2+headerHeight+gutters*6+rowOffset)});
                jQuery('#row3 .cell2').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':winThird+(gutters/2)*1,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
                jQuery('#row3 .cell3').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':winThird*2+(gutters/2)*2,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
                rowOffset = rowOffset+((winThird-(gutters/2))*1.5)-globalHeight;
                // Row 4
                jQuery('#row4 .cell1').width(winTwoThird).height((winThird-(gutters/2))*1.5).css({'left':'0','top':(globalHeight*3+headerHeight+gutters*7+rowOffset)});
                jQuery('#row4 .cell2').width(winThird-(gutters)).height((winThird-(gutters/2))*1.5).css({'left':winTwoThird+(gutters)*1,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});
                rowOffset = rowOffset+((winThird-(gutters/2))*1.5)-globalHeight;
                // Row 5
                jQuery('#row5 .cell1').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':'0','top':(globalHeight*4+headerHeight+gutters*8+rowOffset)});
                jQuery('#row5 .cell2').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':winThird+(gutters/2)*1,'top':((globalHeight)*4+headerHeight+gutters*8+rowOffset)}); 
                jQuery('#row5 .cell3').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':winThird*2+(gutters/2)*2,'top':((globalHeight)*4+headerHeight+gutters*8+rowOffset)}); 
                rowOffset = rowOffset+((winThird-(gutters/2))*1.5)-globalHeight;
                
                jQuery('.content').parent().css('height',((globalHeight)*5+headerHeight+gutters*12+rowOffset-32));
                
                jQuery('.full').css({'height':'100%','width':'100%'});
                jQuery('.eighth img').css({'width':'100%','height':'100%'});
                //jQuery('section#rows').css('height',(globalHeight)*5.7+headerHeight+gutters*8+rowOffset);
                jQuery('.half .ovr_mid_focus').css({'padding':'0 20%','width':'60%'});

                
                /*****/// BEGIN: EDITORIAL IMAGE MAP BINDING SCRIPT
                /**/jQuery('.ediImg').each(function(){
                /**/    jQuery(this).mouseover(function(){
                /**/        // Make sure only one map is bound at a time
                /**/        jQuery('.ediImg').each(function(){
                /**/            jQuery(this).mapster('unbind');
                /**/        });
                /**/        // Initialize on hover
                /**/        jQuery(this).mapster().mapster('resize');
                /**/        initTooltips();
                /**/    });
                /**/    
                /**/    jQuery(window).resize(function(){
                /**/        // Make sure no maps are bound during the resize event to avoid canvas locking
                /**/        jQuery('.ediImg').each(function(){
                /**/            jQuery(this).mapster('unbind');
                /**/        });
                /**/    });
                /**/    
                /**/});
                /*****/// END: EDITORIAL IMAGE MAP BINDING SCRIPT
            }
            editorialInitB();
            initTooltips();
        } else if (editorialEnable && winWidth <= 1024 && editorialTemplateB) {
    
            function editorialInitMinB() {
                var rowOffset = 0;
                // Row 1
                jQuery('#row1 .cell1').width(332).height(499).css('left','0');
                jQuery('#row1 .cell2').width(332).height(499).css('left',332+gutters);
                jQuery('#row1 .cell3').width(332).height(499).css('left',332*2+gutters*2);
                rowOffset = (499-globalHeight);
                // Row 2
                jQuery('#row2 .cell1').width(332).height(499).css({'left':'0','top':(globalHeight+headerHeight+gutters*5+rowOffset)});
                jQuery('#row2 .cell2').width(673).height(499).css({'left':332+(gutters/2)*2,'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
                rowOffset = rowOffset+499-globalHeight;
                // Row 3
                jQuery('#row3 .cell1').width(332).height(499).css({'left':'0','top':(globalHeight*2+headerHeight+gutters*6+rowOffset)});
                jQuery('#row3 .cell2').width(332).height(499).css({'left':332+(gutters)*1,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
                jQuery('#row3 .cell3').width(332).height(499).css({'left':332*2+(gutters)*2,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
                rowOffset = rowOffset+499-globalHeight;
                // Row 4
                jQuery('#row4 .cell1').width(673).height(499).css({'left':'0','top':(globalHeight*3+headerHeight+gutters*7+rowOffset)});
                jQuery('#row4 .cell2').width(332).height(499).css({'left':673+(gutters)*1,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});
                rowOffset = rowOffset+499-globalHeight;
                // Row 5
                jQuery('#row5 .cell1').width(332).height(499).css({'left':'0','top':(globalHeight*4+headerHeight+gutters*8+rowOffset)});
                jQuery('#row5 .cell2').width(332).height(499).css({'left':332+(gutters)*1,'top':((globalHeight)*4+headerHeight+gutters*8+rowOffset)}); 
                jQuery('#row5 .cell3').width(332).height(499).css({'left':332*2+(gutters)*2,'top':((globalHeight)*4+headerHeight+gutters*8+rowOffset)}); 
                rowOffset = rowOffset+499-globalHeight;
                
                jQuery('.content').parent().css('height',((globalHeight)*5+headerHeight+gutters*12+rowOffset-32));
                
                jQuery('.full').css({'height':'100%','width':'100%'});
                jQuery('.eighth img').css({'width':'100%','height':'100%'});
                //jQuery('section#rows').css('height',(globalHeight)*5.7+headerHeight+gutters*8+rowOffset);
                jQuery('.half .ovr_mid_focus').css({'padding':'0 20%','width':'60%'});

                
                /*****/// BEGIN: EDITORIAL IMAGE MAP BINDING SCRIPT
                /**/jQuery('.ediImg').each(function(){
                /**/    jQuery(this).mouseover(function(){
                /**/        // Make sure only one map is bound at a time
                /**/        jQuery('.ediImg').each(function(){
                /**/            jQuery(this).mapster('unbind');
                /**/        });
                /**/        // Initialize on hover
                /**/        jQuery(this).mapster().mapster('resize');
                /**/        initTooltips();
                /**/    });
                /**/    
                /**/    jQuery(window).resize(function(){
                /**/        // Make sure no maps are bound during the resize event to avoid canvas locking
                /**/        jQuery('.ediImg').each(function(){
                /**/            jQuery(this).mapster('unbind');
                /**/        });
                /**/    });
                /**/    
                /**/});
                /*****/// END: EDITORIAL IMAGE MAP BINDING SCRIPT
            }
            editorialInitMinB();
            initTooltips();
        } 
        
        
        
        if (jQuery('#editorialTemplate').hasClass('c')) {
            editorialTemplateC = true;
        }
        
        if (editorialEnable && winWidth > 1024 && editorialTemplateC) {
             
            function editorialInitC() {
                var rowOffset = '0';
                // Row 1
                jQuery('#row1 .cell3').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css('left','0');
                jQuery('#row1 .cell2').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css('left',winThird+gutters/2);
                jQuery('#row1 .cell1').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css('left',winThird*2+gutters);
                rowOffset = ((winThird-(gutters/2))*1.5)-globalHeight;
                // Row 2
                jQuery('#row2 .cell1').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':'0','top':(globalHeight+headerHeight+gutters*5+rowOffset)});
                jQuery('#row2 .cell2').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':winThird+(gutters/2),'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
                jQuery('#row2 .cell3').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':winThird*2+(gutters/2)*2,'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
                rowOffset = rowOffset+((winThird-(gutters/2))*1.5)-globalHeight;
                // Row 3
                jQuery('#row3 .cell1').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':'0','top':(globalHeight*2+headerHeight+gutters*6+rowOffset)});
                jQuery('#row3 .cell2').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':winThird+(gutters/2)*1,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
                jQuery('#row3 .cell3').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':winThird*2+(gutters/2)*2,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
                rowOffset = rowOffset+((winThird-(gutters/2))*1.5)-globalHeight;
                // Row 4
                jQuery('#row4 .cell1').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':'0','top':(globalHeight*3+headerHeight+gutters*7+rowOffset)});
                jQuery('#row4 .cell2').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':winThird+(gutters/2)*1,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});
                jQuery('#row4 .cell3').width(winThird-(gutters/2)).height((winThird-(gutters/2))*1.5).css({'left':winThird*2+(gutters/2)*2,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});
                
                
                
                jQuery('.content').parent().css('height',((globalHeight)*5+headerHeight+gutters*3+rowOffset-32));
                
                jQuery('.full').css({'height':'100%','width':'100%'});
                jQuery('.eighth img').css({'width':'100%','height':'100%'});
                //jQuery('section#rows').css('height',(globalHeight)*5.7+headerHeight+gutters*8+rowOffset);
                jQuery('.half .ovr_mid_focus').css({'padding':'0 20%','width':'60%'});

                
                /*****/// BEGIN: EDITORIAL IMAGE MAP BINDING SCRIPT
                /**/jQuery('.ediImg').each(function(){
                /**/    jQuery(this).mouseover(function(){
                /**/        // Make sure only one map is bound at a time
                /**/        jQuery('.ediImg').each(function(){
                /**/            jQuery(this).mapster('unbind');
                /**/        });
                /**/        // Initialize on hover
                /**/        jQuery(this).mapster().mapster('resize');
                /**/        initTooltips();
                /**/    });
                /**/    
                /**/    jQuery(window).resize(function(){
                /**/        // Make sure no maps are bound during the resize event to avoid canvas locking
                /**/        jQuery('.ediImg').each(function(){
                /**/            jQuery(this).mapster('unbind');
                /**/        });
                /**/    });
                /**/    
                /**/});
                /*****/// END: EDITORIAL IMAGE MAP BINDING SCRIPT
            }
            editorialInitC();
            initTooltips();
        } else if (editorialEnable && winWidth <= 1024 && editorialTemplateC) {
    
            function editorialInitMinC() {
                var rowOffset = 0;
                // Row 1
                jQuery('#row1 .cell3').width(332).height(499).css('left','0');
                jQuery('#row1 .cell2').width(332).height(499).css('left',332+gutters);
                jQuery('#row1 .cell1').width(332).height(499).css('left',332*2+gutters*2);
                rowOffset = (499-globalHeight);
                // Row 2
                jQuery('#row2 .cell1').width(332).height(499).css({'left':'0','top':(globalHeight+headerHeight+gutters*5+rowOffset)});
                jQuery('#row2 .cell2').width(332).height(499).css({'left':332+(gutters/2)*2,'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
                jQuery('#row2 .cell3').width(332).height(499).css({'left':332*2+(gutters/2)*4,'top':((globalHeight)*1+headerHeight+gutters*5+rowOffset)});
                rowOffset = rowOffset+499-globalHeight;
                // Row 3
                jQuery('#row3 .cell1').width(332).height(499).css({'left':'0','top':(globalHeight*2+headerHeight+gutters*6+rowOffset)});
                jQuery('#row3 .cell2').width(332).height(499).css({'left':332+(gutters)*1,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
                jQuery('#row3 .cell3').width(332).height(499).css({'left':332*2+(gutters)*2,'top':((globalHeight)*2+headerHeight+gutters*6+rowOffset)});
                rowOffset = rowOffset+499-globalHeight;
                // Row 4
                jQuery('#row4 .cell1').width(332).height(499).css({'left':'0','top':(globalHeight*3+headerHeight+gutters*7+rowOffset)});
                jQuery('#row4 .cell2').width(332).height(499).css({'left':332+(gutters)*1,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});
                jQuery('#row4 .cell3').width(332).height(499).css({'left':332*2+(gutters)*2,'top':((globalHeight)*3+headerHeight+gutters*7+rowOffset)});
                rowOffset = rowOffset+499-globalHeight;
                
                
                jQuery('.content').parent().css('height',((globalHeight)*4+headerHeight+gutters*12+rowOffset-32)).css('overflow-y','hidden');
                
                jQuery('.full').css({'height':'100%','width':'100%'});
                jQuery('.eighth img').css({'width':'100%','height':'100%'});
                //jQuery('section#rows').css('height',(globalHeight)*5.7+headerHeight+gutters*8+rowOffset);
                jQuery('.half .ovr_mid_focus').css({'padding':'0 20%','width':'60%'});

                
                /*****/// BEGIN: EDITORIAL IMAGE MAP BINDING SCRIPT
                /**/jQuery('.ediImg').each(function(){
                /**/    jQuery(this).mouseover(function(){
                /**/        // Make sure only one map is bound at a time
                /**/        jQuery('.ediImg').each(function(){
                /**/            jQuery(this).mapster('unbind');
                /**/        });
                /**/        // Initialize on hover
                /**/        jQuery(this).mapster().mapster('resize');
                /**/        initTooltips();
                /**/    });
                /**/    
                /**/    jQuery(window).resize(function(){
                /**/        // Make sure no maps are bound during the resize event to avoid canvas locking
                /**/        jQuery('.ediImg').each(function(){
                /**/            jQuery(this).mapster('unbind');
                /**/        });
                /**/    });
                /**/    
                /**/});
                /*****/// END: EDITORIAL IMAGE MAP BINDING SCRIPT
            }
            editorialInitMinC();
            initTooltips();
        } 

        // FOCUS - TEMPLATE A
        if (jQuery('#focusEnable').hasClass('enabled')){
            focusEnable = true;
        }
        
        if (focusEnable && winWidth > 1024) {
            
            for (y=1;y<=tempRows;y++) {
                jQuery('#row1 .cell1').width(winQuarter-gutters).height(globalHeight*1.8034351145).css({'left':'0','top':headerHeight+gutters*4});tempCells = jQuery('#row'+y).attr("class");checkGutter();checkFraction();currCell = jQuery('#row'+y+' .cell'+(x+1));
                for (x=0;x<=tempCells;x++) {
                    jQuery('#row'+(y)+' .cell'+(x+1)).width(currFraction-(gutters*gutterFraction)).height(globalHeight*1.8034351145).css({'left':currFraction*x+(gutters/tempCells)*x,'top':(globalHeight*1.8034351145*(y-1)+headerHeight+gutters*(y+3))});
                    jQuery("embed").css('height',globalHeight*1.8034351145);
                    jQuery('#focusThumbnail img').width(currFraction-(gutters*gutterFraction)).height((winWidth/2)*0.9827083);
                 }
            }
            
            tempThumbContainer = jQuery('#row1 .cell3').width();
            jQuery('#focusThumbs .focusItem:nth-child(1)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:0, top:0});
            jQuery('#focusThumbs .focusItem:nth-child(2)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:tempThumbContainer/3-(gutters*2/3)+gutters, top:0});
            jQuery('#focusThumbs .focusItem:nth-child(3)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:(tempThumbContainer/3-(gutters*2/3)+gutters)*2, top:0});
            jQuery('#focusThumbs .focusItem:nth-child(4)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:0, top:((tempThumbContainer/3-gutters*2/3)*1.5)+gutters});
            jQuery('#focusThumbs .focusItem:nth-child(5)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:tempThumbContainer/3-(gutters*2/3)+gutters, top:((tempThumbContainer/3-gutters*2/3)*1.5)+gutters});
            jQuery('#focusThumbs .focusItem:nth-child(6)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:(tempThumbContainer/3-(gutters*2/3)+gutters)*2, top:((tempThumbContainer/3-gutters*2/3)*1.5)+gutters});
            jQuery('#focusThumbs .focusItem:nth-child(7)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:0, top:(((tempThumbContainer/3-gutters*2/3)*1.5)+gutters)*2});
            jQuery('#focusThumbs .focusItem:nth-child(8)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:tempThumbContainer/3-(gutters*2/3)+gutters, top:(((tempThumbContainer/3-gutters*2/3)*1.5)+gutters)*2});
            jQuery('#focusThumbs .focusItem:nth-child(9)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:(tempThumbContainer/3-(gutters*2/3)+gutters)*2, top:(((tempThumbContainer/3-gutters*2/3)*1.5)+gutters)*2});
            jQuery('.fb_ltr').css('width','75px');
            
            jQuery('#focusTitleImage img, #row1 .cell1, #row1 .cell2, #row1 .cell3').height((((tempThumbContainer/3-gutters*2/3)*1.5)*3)+gutters*2);
            
            /*****/// BEGIN: FOCUS IMAGE MAP BINDING SCRIPT
            /**/jQuery('#focusTitleImage img').each(function(){
            /**/    jQuery(this).mouseover(function(){
            /**/        jQuery('#focusTitleImage img').each(function(){ // Make sure only one map is bound at a time
            /**/            jQuery(this).mapster('unbind');
            /**/        });
            /**/        jQuery(this).mapster().mapster('resize'); // Initialize on hover
            /**/        initTooltips();
            /**/    });
            /**/    jQuery(window).resize(function(){
            /**/        jQuery('#focusTitleImage img').each(function(){ // Make sure no maps are bound during the resize event to avoid canvas locking
            /**/            jQuery(this).mapster('unbind');
            /**/        });
            /**/    });
            /**/});
            /*****/// END: FOCUS IMAGE MAP BINDING SCRIPT
            
            focusCarousel();
            jQuery('section#rows').css('height',(((tempThumbContainer/3-gutters*2/3)*1.5)*3)+gutters*6+headerHeight);
            jQuery('.content').parent().css('height',(((tempThumbContainer/3-gutters*2/3)*1.5)*3)+gutters*6+headerHeight);
            
            /* Internet Explorer Footer Fix 
            var IE;
            //@cc_on IE = navigator.appVersion;
            if (IE) { 
                jQuery('.footer').css('margin-top','50%');
            }
            */
            if (winWidth > 1200) {
            // MANUALLY SEPARATE AND PLACE TITLE AND COPY
            var h1Height = jQuery('.ovr_mid_focus h1').height();
            var h2Height = jQuery('.ovr_mid_focus h2').height();
            var focusHeaderHeight = h1Height + h2Height;
            jQuery('.ovr_mid_focus header').css({'position':'absolute','text-align':'center','left':0,'width':'100%','top':((tempThumbContainer/3-gutters*2/3)*1.5)/2-(focusHeaderHeight/2)});
            jQuery('#focusCopy').css({'position':'absolute','width':'100%','margin':'0 24px','left':'0','top':((tempThumbContainer/3-gutters*2/3)*1.5)+gutters});
            var currCopyWidth = jQuery('#focusCopy').width();
            jQuery('#focusCopy').width(currCopyWidth-48);
            jQuery('#focusTitleImage img, #row1 .cell1, #row1 .cell2, #row1 .cell3').height((((tempThumbContainer/3-gutters*2/3)*1.5)*3)+gutters*2);
            } else {
                jQuery('.ovr_mid_focus header').css({'position':'relative','top':'auto','left':'auto','width':'100%'});
                jQuery('#focusCopy').css({'position':'relative','top':'auto','left':'auto','width':'100%','margin':'0 24px 0 12px'});
                var currCopyWidth = jQuery('#focusCopy').width();
                jQuery('#focusCopy').width(currCopyWidth-24);
            }
            
            
        } else if (focusEnable && winWidth <= 1024) { 
            
            for (y=1;y<=tempRows;y++) {
                jQuery('#row1 .cell1').width(331-gutters/2).height(501).css({'left':'0','top':headerHeight+gutters*4});tempCells = jQuery('#row'+y).attr("class");checkGutter();checkFraction();currCell = jQuery('#row'+y+' .cell'+(x+1));
                for (x=0;x<=tempCells;x++) {
                    jQuery('#row'+(y)+' .cell'+(x+1)).width(331).height(501).css({'left':331*x+gutters*x,'top':(globalHeight*1.8034351145*(y-1)+headerHeight+gutters*(y+3))});
                  
                    jQuery('#focusThumbnail img').width(331).height(501);
                 }
            }
            
            tempThumbContainer = 331;
            jQuery('#focusThumbs .focusItem:nth-child(1)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:0, top:0});
            jQuery('#focusThumbs .focusItem:nth-child(2)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:tempThumbContainer/3-(gutters*2/3)+gutters, top:0});
            jQuery('#focusThumbs .focusItem:nth-child(3)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:(tempThumbContainer/3-(gutters*2/3)+gutters)*2, top:0});
            jQuery('#focusThumbs .focusItem:nth-child(4)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:0, top:((tempThumbContainer/3-gutters*2/3)*1.5)+gutters});
            jQuery('#focusThumbs .focusItem:nth-child(5)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:tempThumbContainer/3-(gutters*2/3)+gutters, top:((tempThumbContainer/3-gutters*2/3)*1.5)+gutters});
            jQuery('#focusThumbs .focusItem:nth-child(6)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:(tempThumbContainer/3-(gutters*2/3)+gutters)*2, top:((tempThumbContainer/3-gutters*2/3)*1.5)+gutters});
            jQuery('#focusThumbs .focusItem:nth-child(7)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:0, top:(((tempThumbContainer/3-gutters*2/3)*1.5)+gutters)*2});
            jQuery('#focusThumbs .focusItem:nth-child(8)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:tempThumbContainer/3-(gutters*2/3)+gutters, top:(((tempThumbContainer/3-gutters*2/3)*1.5)+gutters)*2});
            jQuery('#focusThumbs .focusItem:nth-child(9)').css({'width':tempThumbContainer/3-(gutters*2/3), 'height':(tempThumbContainer/3-gutters*2/3)*1.5, left:(tempThumbContainer/3-(gutters*2/3)+gutters)*2, top:(((tempThumbContainer/3-gutters*2/3)*1.5)+gutters)*2});
            jQuery('.fb_ltr').css('width','75px');
          
            focusCarousel();
            
            jQuery('.ovr_mid_focus').css('font-size','10px');
            jQuery('section#rows').css('height',600);
            jQuery('.content').parent().css('height',600);
            jQuery('section#rows').css('height',(((tempThumbContainer/3-gutters*2/3)*1.5)*3)+gutters*6-4+headerHeight+3);
            jQuery('.content').parent().css('height',(((tempThumbContainer/3-gutters*2/3)*1.5)*3)+gutters*6-4+headerHeight+3);
            
            
            jQuery('.ovr_mid_focus header').css({'position':'relative','top':'auto','left':'auto','width':'100%'});
                jQuery('#focusCopy').css({'position':'relative','top':'auto','left':'auto','width':'100%','margin':'0'});
                var currCopyWidth = jQuery('#focusCopy').width();
                jQuery('#focusCopy').width(currCopyWidth);
                
                jQuery('#focusTitleImage img, #row1 .cell1, #row1 .cell2, #row1 .cell3').height((((tempThumbContainer/3-gutters*2/3)*1.5)*3)+gutters*2);
         
        }
            _bigDisplay();
        
    }


    




///////////////////////////////////////////////
// MISCELLANEOUS FUNCTIONS                               
///////////////////////////////////////////////

// Disable Left/Right arrow keys

var ar=new Array(33,34,35,36,37,39);

jQuery(document).keydown(function(e) {
     var key = e.which;
      ////console.log(key);
      //if(key == 37 || key == 39)
      if(jQuery.inArray(key,ar) > -1) {
          e.preventDefault();
          return false;
      }
      return true;
});

// Social Network Function

function socialNetworkShare(type, url, msg){
    var url = encodeURIComponent(url||location.href);
    var msg = encodeURIComponent(msg||document.title);
    var pathPrefix, pathShare;
	
    switch(type){
        case "facebook":
            pathPrefix = "http://www.facebook.com/sharer.php?";
            pathShare = pathPrefix + "u=" + url + "&t=" + msg + " at ssense.com";
            _gaq.push(['_trackEvent', 'Product Page', 'Facebook']);
            break;
        case "twitter":
            pathPrefix = "http://twitter.com/share?";
            pathShare = pathPrefix + "url=" + url + "&text=" + msg + " @SSENSE";
            _gaq.push(['_trackEvent', 'Product Page', 'Twitter']);
            break;
        case "pinterest":
            pathPrefix = "http://pinterest.com/pin/create/button/?url=";
            pathShare = pathPrefix + url + "&media=" + "http://ssense.com/frontend/editorial/mannequin/images/gallery/1.jpg" + "&description=" + msg;
            _gaq.push(['_trackEvent', 'Product Page', 'Pinterest']);
            break;			
        case "tumblr":
            pathPrefix = "http://www.tumblr.com/share?v=3";
            pathShare = pathPrefix + "&u=" + url + "&t=" + msg + " at ssense.com";
            _gaq.push(['_trackEvent', 'Product Page', 'Tumblr']);
            break;
        case "googleplus":
            pathPrefix = "https://plusone.google.com/_/+1/confirm?hl=en";
            pathShare = pathPrefix + "&url=" + url;
            _gaq.push(['_trackEvent', 'Product Page', 'Google Plus']);
            break;
    }
	
    window.open(pathShare,"_blank","toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=640, height=480");
    return false;
}


///////////////////////////////////////////////
// TEXT FUNCTIONS - CUFON // FITTEXT // BIGTXT                      
///////////////////////////////////////////////

function _fitText() {
    jQuery('.thirdcopy, .quartercopy, .fifthcopy').addClass("sweet-justice");
    /*
    // Assign font-size depending on character count
    jQuery('.ovr_mid_h2').each(function(){
        var tempTxt = jQuery(this).text();
        if (tempTxt.length <= 20) {
            jQuery(this).addClass("large");
        } else if ( tempTxt.length > 20 && tempTxt.length < 50 ) {
            jQuery(this).addClass("medium");
        } else if ( tempTxt.length >= 50 ) {
            jQuery(this).addClass("small");
        }
    });
    */
    // GLOBAL TEXT SIZES
   
    jQuery('.xsmall').deltaText(1, {minimum: '11px'});
    jQuery('.small').deltaText(1, {minimum: '14px'});
    jQuery('.medium').deltaText(1, {minimum: '24px'});
    jQuery('.large').deltaText(1, {minimum: '48px'});
    jQuery('.xlarge').deltaText(1, {minimum: '80px'});
    
    // SPECIFIC ELEMENT OVERWRITES
    jQuery('.dala').css('text-transform','lowercase');

  
        
        
}

function _cufon() {
    
    Cufon.replace('.goth', {fontFamily: 'Gotham', fontWeight: 'normal'});
    Cufon.replace('.gothBold', {fontFamily: 'Gotham', fontWeight: 'bold'});
    Cufon.replace('.gothWhite', {fontFamily: 'Gotham', fontWeight: 'normal',  color:'#fff'});
    Cufon.replace('.gothBoldWhite', {fontFamily: 'Gotham', fontWeight: 'bold',  color:'#fff'});
    Cufon.replace('.dala', {fontFamily: 'DalaFloda', fontStyle:'italic', fontWeight: 'normal'});
    Cufon.replace('.dalaBold', {fontFamily: 'DalaFloda', fontStyle:'italic', fontWeight: 'bold'});
    Cufon.replace('.dalaWhite', {fontFamily: 'DalaFloda', fontStyle:'italic', fontWeight: 'normal',  color:'#fff'});
    Cufon.replace('.dalaBoldWhite', {fontFamily: 'DalaFloda', fontStyle:'italic', fontWeight: 'bold',  color:'#fff'});
    
}
    
    
///////////////////////////////////////////////
// BEGINS CAROUSEL FOR FOCUS  

function focusCarousel() {
    
    
    var focusIndexArray = jQuery('#focusTitleImage').children();
    var focusIndex = (focusIndexArray.length);
    var currFocus = 1;
    
    
        jQuery('#focusTitleImage > img').hide();
        jQuery('#focusTitleImage > img:first-child').show();

        jQuery('#focusNext').click(function(){     
            if (currFocus < focusIndex && currFocus >= 1) {
                jQuery('#focusTitleImage #'+currFocus).mapster('unbind').hide();
                currFocus++;
                jQuery('#focusTitleImage #'+currFocus).show();
            } else if (currFocus == focusIndex) {
                jQuery('#focusTitleImage #1').show();
                jQuery('#focusTitleImage #'+currFocus).mapster('unbind').hide();
                currFocus = 1;
            } 
            return false; // Remove any jumping when clicking the buttons
            
        });
   
    
        jQuery('#focusPrev').click(function(){
            if (currFocus > 1 && currFocus <= focusIndex) {
                jQuery('#focusTitleImage #'+currFocus).mapster('unbind').hide();
                currFocus--;
                jQuery('#focusTitleImage #'+currFocus).show();
            } else if (currFocus <= 1) {
                jQuery('#focusTitleImage #'+currFocus).mapster('unbind').hide();
                jQuery('#focusTitleImage #'+focusIndex).show();
                currFocus = focusIndex;
            }
            return false; // Remove any jumping when clicking the buttons
        });
    
    /* Autoplay
    var t = '';
    t = setInterval(function(){jQuery('#focusNext').trigger('click')},3000);
    jQuery('#focusTitleImage, #mapster_wrap_0').mouseover(function(){
        window.clearInterval(t);
    });
    
    jQuery('#focusTitleImage, #mapster_wrap_0').mouseout(function(){
        t = setInterval(function(){jQuery('#focusNext').trigger('click')},3000);
    });
    */
}


// ENDS CAROUSEL FOR FOCUS
///////////////////////////////////////////////


function _bigDisplay() {
    winHeight = jQuery(window).height();
    footerHeight = 194;
    sectionHeight = jQuery('.content').parent().height() + headerHeight + footerHeight;
    console.log(sectionHeight);
    if (winHeight > sectionHeight) {
        jQuery('#footer, .footer').css({
            'position':'absolute',
            'bottom':'0',
            'width':'100%'
        });
        //jQuery('section#rows').css('height',winHeight-194);
        //jQuery('.content').parent().css('height',winHeight-194);
        jQuery('section#rows').css('height','100%');
    } else {
        jQuery('#footer, .footer').css({
            'position':'relative',
            'bottom':'auto',
            'width':'auto'
        });
    }
    
}

function _extraResize() {
    var t = setTimeout(jQuery(window).trigger('resize'),30);
}