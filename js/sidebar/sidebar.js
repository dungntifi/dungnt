// Autor: Klerith
// Page:  http://codecanyon.net/user/klerith
// Contact me from CodeCanyon site.
// :) Thank you for your support

// ==============================
// V.1 First Release
// ==============================


(function (jQuery) 
{

    // Swiper and touch functions
    var Swiper=function(l,a){if(document.body.__defineGetter__){if(HTMLElement){var t=HTMLElement.prototype;if(t.__defineGetter__){t.__defineGetter__("outerHTML",function(){return new XMLSerializer().serializeToString(this)})}}}if(!window.getComputedStyle){window.getComputedStyle=function(i,j){this.el=i;this.getPropertyValue=function(ag){var p=/(\-([a-z]){1})/g;if(ag==="float"){ag="styleFloat"}if(p.test(ag)){ag=ag.replace(p,function(){return arguments[2].toUpperCase()})}return i.currentStyle[ag]?i.currentStyle[ag]:null};return this}}if(!Array.prototype.indexOf){Array.prototype.indexOf=function(ah,ai){for(var ag=(ai||0),p=this.length;ag<p;ag++){if(this[ag]===ah){return ag}}return -1}}if(!document.querySelectorAll){if(!window.jQuery){return}}function R(i,j){if(document.querySelectorAll){return(j||document).querySelectorAll(i)}else{return jQuery(i,j)}}if(typeof l==="undefined"){return}if(!(l.nodeType)){if(R(l).length===0){return}}var f=this;f.touches={start:0,startX:0,startY:0,current:0,currentX:0,currentY:0,diff:0,abs:0};f.positions={start:0,abs:0,diff:0,current:0};f.times={start:0,end:0};f.id=(new Date()).getTime();f.container=(l.nodeType)?l:R(l)[0];f.isTouched=false;f.isMoved=false;f.activeIndex=0;f.centerIndex=0;f.activeLoaderIndex=0;f.activeLoopIndex=0;f.previousIndex=null;f.velocity=0;f.snapGrid=[];f.slidesGrid=[];f.imagesToLoad=[];f.imagesLoaded=0;f.wrapperLeft=0;f.wrapperRight=0;f.wrapperTop=0;f.wrapperBottom=0;f.isAndroid=navigator.userAgent.toLowerCase().indexOf("android")>=0;var z,J,ae,r,b,d;var G={eventTarget:"wrapper",mode:"horizontal",touchRatio:1,speed:300,freeMode:false,freeModeFluid:false,momentumRatio:1,momentumBounce:true,momentumBounceRatio:1,slidesPerView:1,slidesPerGroup:1,slidesPerViewFit:true,simulateTouch:true,followFinger:true,shortSwipes:true,longSwipesRatio:0.5,moveStartThreshold:false,onlyExternal:false,createPagination:true,pagination:false,paginationElement:"span",paginationClickable:false,paginationAsRange:true,resistance:true,scrollContainer:false,preventLinks:true,preventLinksPropagation:false,noSwiping:false,noSwipingClass:"swiper-no-swiping",initialSlide:0,keyboardControl:false,mousewheelControl:false,mousewheelControlForceToAxis:false,useCSS3Transforms:true,autoplay:false,autoplayDisableOnInteraction:true,autoplayStopOnLast:false,loop:false,loopAdditionalSlides:0,roundLengths:false,calculateHeight:false,cssWidthAndHeight:false,updateOnImagesReady:true,releaseFormElements:true,watchActiveIndex:false,visibilityFullFit:false,offsetPxBefore:0,offsetPxAfter:0,offsetSlidesBefore:0,offsetSlidesAfter:0,centeredSlides:false,queueStartCallbacks:false,queueEndCallbacks:false,autoResize:true,resizeReInit:false,DOMAnimation:true,loader:{slides:[],slidesHTMLType:"inner",surroundGroups:1,logic:"reload",loadAllSlides:false},slideElement:"div",slideClass:"swiper-slide",slideActiveClass:"swiper-slide-active",slideVisibleClass:"swiper-slide-visible",slideDuplicateClass:"swiper-slide-duplicate",wrapperClass:"swiper-wrapper",paginationElementClass:"swiper-pagination-switch",paginationActiveClass:"swiper-active-switch",paginationVisibleClass:"swiper-visible-switch"};a=a||{};for(var x in G){if(x in a&&typeof a[x]==="object"){for(var e in G[x]){if(!(e in a[x])){a[x][e]=G[x][e]}}}else{if(!(x in a)){a[x]=G[x]}}}f.params=a;if(a.scrollContainer){a.freeMode=true;a.freeModeFluid=true}if(a.loop){a.resistance="100%"}var D=a.mode==="horizontal";var v=["mousedown","mousemove","mouseup"];if(f.browser.ie10){v=["MSPointerDown","MSPointerMove","MSPointerUp"]}if(f.browser.ie11){v=["pointerdown","pointermove","pointerup"]}f.touchEvents={touchStart:f.support.touch||!a.simulateTouch?"touchstart":v[0],touchMove:f.support.touch||!a.simulateTouch?"touchmove":v[1],touchEnd:f.support.touch||!a.simulateTouch?"touchend":v[2]};for(var V=f.container.childNodes.length-1;V>=0;V--){if(f.container.childNodes[V].className){var s=f.container.childNodes[V].className.split(/\s+/);for(var S=0;S<s.length;S++){if(s[S]===a.wrapperClass){z=f.container.childNodes[V]}}}}f.wrapper=z;f._extendSwiperSlide=function(i){i.append=function(){if(a.loop){i.insertAfter(f.slides.length-f.loopedSlides)}else{f.wrapper.appendChild(i);f.reInit()}return i};i.prepend=function(){if(a.loop){f.wrapper.insertBefore(i,f.slides[f.loopedSlides]);f.removeLoopedSlides();f.calcSlides();f.createLoop()}else{f.wrapper.insertBefore(i,f.wrapper.firstChild)}f.reInit();return i};i.insertAfter=function(j){if(typeof j==="undefined"){return false}var p;if(a.loop){p=f.slides[j+1+f.loopedSlides];if(p){f.wrapper.insertBefore(i,p)}else{f.wrapper.appendChild(i)}f.removeLoopedSlides();f.calcSlides();f.createLoop()}else{p=f.slides[j+1];f.wrapper.insertBefore(i,p)}f.reInit();return i};i.clone=function(){return f._extendSwiperSlide(i.cloneNode(true))};i.remove=function(){f.wrapper.removeChild(i);f.reInit()};i.html=function(j){if(typeof j==="undefined"){return i.innerHTML}else{i.innerHTML=j;return i}};i.index=function(){var j;for(var p=f.slides.length-1;p>=0;p--){if(i===f.slides[p]){j=p}}return j};i.isActive=function(){if(i.index()===f.activeIndex){return true}else{return false}};if(!i.swiperSlideDataStorage){i.swiperSlideDataStorage={}}i.getData=function(j){return i.swiperSlideDataStorage[j]};i.setData=function(j,p){i.swiperSlideDataStorage[j]=p;return i};i.data=function(j,p){if(typeof p==="undefined"){return i.getAttribute("data-"+j)}else{i.setAttribute("data-"+j,p);return i}};i.getWidth=function(p,j){return f.h.getWidth(i,p,j)};i.getHeight=function(p,j){return f.h.getHeight(i,p,j)};i.getOffset=function(){return f.h.getOffset(i)};return i};f.calcSlides=function(ah){var aj=f.slides?f.slides.length:false;f.slides=[];f.displaySlides=[];for(var ai=0;ai<f.wrapper.childNodes.length;ai++){if(f.wrapper.childNodes[ai].className){var ak=f.wrapper.childNodes[ai].className;var p=ak.split(/\s+/);for(var ag=0;ag<p.length;ag++){if(p[ag]===a.slideClass){f.slides.push(f.wrapper.childNodes[ai])}}}}for(ai=f.slides.length-1;ai>=0;ai--){f._extendSwiperSlide(f.slides[ai])}if(aj===false){return}if(aj!==f.slides.length||ah){o();q();f.updateActiveSlide();if(f.params.pagination){f.createPagination()}f.callPlugins("numberOfSlidesChanged")}};f.createSlide=function(p,j,ag){j=j||f.params.slideClass;ag=ag||a.slideElement;var i=document.createElement(ag);i.innerHTML=p||"";i.className=j;return f._extendSwiperSlide(i)};f.appendSlide=function(j,i,p){if(!j){return}if(j.nodeType){return f._extendSwiperSlide(j).append()}else{return f.createSlide(j,i,p).append()}};f.prependSlide=function(j,i,p){if(!j){return}if(j.nodeType){return f._extendSwiperSlide(j).prepend()}else{return f.createSlide(j,i,p).prepend()}};f.insertSlideAfter=function(j,p,i,ag){if(typeof j==="undefined"){return false}if(p.nodeType){return f._extendSwiperSlide(p).insertAfter(j)}else{return f.createSlide(p,i,ag).insertAfter(j)}};f.removeSlide=function(i){if(f.slides[i]){if(a.loop){if(!f.slides[i+f.loopedSlides]){return false}f.slides[i+f.loopedSlides].remove();f.removeLoopedSlides();f.calcSlides();f.createLoop()}else{f.slides[i].remove()}return true}else{return false}};f.removeLastSlide=function(){if(f.slides.length>0){if(a.loop){f.slides[f.slides.length-1-f.loopedSlides].remove();f.removeLoopedSlides();f.calcSlides();f.createLoop()}else{f.slides[f.slides.length-1].remove()}return true}else{return false}};f.removeAllSlides=function(){for(var j=f.slides.length-1;j>=0;j--){f.slides[j].remove()}};f.getSlide=function(i){return f.slides[i]};f.getLastSlide=function(){return f.slides[f.slides.length-1]};f.getFirstSlide=function(){return f.slides[0]};f.activeSlide=function(){return f.slides[f.activeIndex]};f.fireCallback=function(){var p=arguments[0];if(Object.prototype.toString.call(p)==="[object Array]"){for(var j=0;j<p.length;j++){if(typeof p[j]==="function"){p[j](arguments[1],arguments[2],arguments[3],arguments[4],arguments[5])}}}else{if(Object.prototype.toString.call(p)==="[object String]"){if(a["on"+p]){f.fireCallback(a["on"+p])}}else{p(arguments[1],arguments[2],arguments[3],arguments[4],arguments[5])}}};function B(i){if(Object.prototype.toString.apply(i)==="[object Array]"){return true}return false}f.addCallback=function(ag,i){var p=this,j;if(p.params["on"+ag]){if(B(this.params["on"+ag])){return this.params["on"+ag].push(i)}else{if(typeof this.params["on"+ag]==="function"){j=this.params["on"+ag];this.params["on"+ag]=[];this.params["on"+ag].push(j);return this.params["on"+ag].push(i)}}}else{this.params["on"+ag]=[];return this.params["on"+ag].push(i)}};f.removeCallbacks=function(i){if(f.params["on"+i]){f.params["on"+i]=null}};var A=[];for(var ac in f.plugins){if(a[ac]){var P=f.plugins[ac](f,a[ac]);if(P){A.push(P)}}}f.callPlugins=function(ag,j){if(!j){j={}}for(var p=0;p<A.length;p++){if(ag in A[p]){A[p][ag](j)}}};if((f.browser.ie10||f.browser.ie11)&&!a.onlyExternal){f.wrapper.classList.add("swiper-wp8-"+(D?"horizontal":"vertical"))}if(a.freeMode){f.container.className+=" swiper-free-mode"}f.initialized=false;f.init=function(aj,ai){var an=f.h.getWidth(f.container,false,a.roundLengths);var aA=f.h.getHeight(f.container,false,a.roundLengths);if(an===f.width&&aA===f.height&&!aj){return}f.width=an;f.height=aA;var ao,aq,ax,ak,au,az;var aw;d=D?an:aA;var ap=f.wrapper;if(aj){f.calcSlides(ai)}if(a.slidesPerView==="auto"){var al=0;var ag=0;if(a.slidesOffset>0){ap.style.paddingLeft="";ap.style.paddingRight="";ap.style.paddingTop="";ap.style.paddingBottom=""}ap.style.width="";ap.style.height="";if(a.offsetPxBefore>0){if(D){f.wrapperLeft=a.offsetPxBefore}else{f.wrapperTop=a.offsetPxBefore}}if(a.offsetPxAfter>0){if(D){f.wrapperRight=a.offsetPxAfter}else{f.wrapperBottom=a.offsetPxAfter}}if(a.centeredSlides){if(D){f.wrapperLeft=(d-this.slides[0].getWidth(true,a.roundLengths))/2;f.wrapperRight=(d-f.slides[f.slides.length-1].getWidth(true,a.roundLengths))/2}else{f.wrapperTop=(d-f.slides[0].getHeight(true,a.roundLengths))/2;f.wrapperBottom=(d-f.slides[f.slides.length-1].getHeight(true,a.roundLengths))/2}}if(D){if(f.wrapperLeft>=0){ap.style.paddingLeft=f.wrapperLeft+"px"}if(f.wrapperRight>=0){ap.style.paddingRight=f.wrapperRight+"px"}}else{if(f.wrapperTop>=0){ap.style.paddingTop=f.wrapperTop+"px"}if(f.wrapperBottom>=0){ap.style.paddingBottom=f.wrapperBottom+"px"}}az=0;var ay=0;f.snapGrid=[];f.slidesGrid=[];ax=0;for(aw=0;aw<f.slides.length;aw++){ao=f.slides[aw].getWidth(true,a.roundLengths);aq=f.slides[aw].getHeight(true,a.roundLengths);if(a.calculateHeight){ax=Math.max(ax,aq)}var at=D?ao:aq;if(a.centeredSlides){var p=aw===f.slides.length-1?0:f.slides[aw+1].getWidth(true,a.roundLengths);var ah=aw===f.slides.length-1?0:f.slides[aw+1].getHeight(true,a.roundLengths);var am=D?p:ah;if(at>d){if(a.slidesPerViewFit){f.snapGrid.push(az+f.wrapperLeft);f.snapGrid.push(az+at-d+f.wrapperLeft)}else{for(var av=0;av<=Math.floor(at/(d+f.wrapperLeft));av++){if(av===0){f.snapGrid.push(az+f.wrapperLeft)}else{f.snapGrid.push(az+f.wrapperLeft+d*av)}}}f.slidesGrid.push(az+f.wrapperLeft)}else{f.snapGrid.push(ay);f.slidesGrid.push(ay)}ay+=at/2+am/2}else{if(at>d){if(a.slidesPerViewFit){f.snapGrid.push(az);f.snapGrid.push(az+at-d)}else{if(d!==0){for(var ar=0;ar<=Math.floor(at/d);ar++){f.snapGrid.push(az+d*ar)}}else{f.snapGrid.push(az)}}}else{f.snapGrid.push(az)}f.slidesGrid.push(az)}az+=at;al+=ao;ag+=aq}if(a.calculateHeight){f.height=ax}if(D){ae=al+f.wrapperRight+f.wrapperLeft;ap.style.width=(al)+"px";ap.style.height=(f.height)+"px"}else{ae=ag+f.wrapperTop+f.wrapperBottom;ap.style.width=(f.width)+"px";ap.style.height=(ag)+"px"}}else{if(a.scrollContainer){ap.style.width="";ap.style.height="";ak=f.slides[0].getWidth(true,a.roundLengths);au=f.slides[0].getHeight(true,a.roundLengths);ae=D?ak:au;ap.style.width=ak+"px";ap.style.height=au+"px";J=D?ak:au}else{if(a.calculateHeight){ax=0;au=0;if(!D){f.container.style.height=""}ap.style.height="";for(aw=0;aw<f.slides.length;aw++){f.slides[aw].style.height="";ax=Math.max(f.slides[aw].getHeight(true),ax);if(!D){au+=f.slides[aw].getHeight(true)}}aq=ax;f.height=aq;if(D){au=aq}else{d=aq;f.container.style.height=d+"px"}}else{aq=D?f.height:f.height/a.slidesPerView;if(a.roundLengths){aq=Math.round(aq)}au=D?f.height:f.slides.length*aq}ao=D?f.width/a.slidesPerView:f.width;if(a.roundLengths){ao=Math.round(ao)}ak=D?f.slides.length*ao:f.width;J=D?ao:aq;if(a.offsetSlidesBefore>0){if(D){f.wrapperLeft=J*a.offsetSlidesBefore}else{f.wrapperTop=J*a.offsetSlidesBefore}}if(a.offsetSlidesAfter>0){if(D){f.wrapperRight=J*a.offsetSlidesAfter}else{f.wrapperBottom=J*a.offsetSlidesAfter}}if(a.offsetPxBefore>0){if(D){f.wrapperLeft=a.offsetPxBefore}else{f.wrapperTop=a.offsetPxBefore}}if(a.offsetPxAfter>0){if(D){f.wrapperRight=a.offsetPxAfter}else{f.wrapperBottom=a.offsetPxAfter}}if(a.centeredSlides){if(D){f.wrapperLeft=(d-J)/2;f.wrapperRight=(d-J)/2}else{f.wrapperTop=(d-J)/2;f.wrapperBottom=(d-J)/2}}if(D){if(f.wrapperLeft>0){ap.style.paddingLeft=f.wrapperLeft+"px"}if(f.wrapperRight>0){ap.style.paddingRight=f.wrapperRight+"px"}}else{if(f.wrapperTop>0){ap.style.paddingTop=f.wrapperTop+"px"}if(f.wrapperBottom>0){ap.style.paddingBottom=f.wrapperBottom+"px"}}ae=D?ak+f.wrapperRight+f.wrapperLeft:au+f.wrapperTop+f.wrapperBottom;if(!a.cssWidthAndHeight){if(parseFloat(ak)>0){ap.style.width=ak+"px"}if(parseFloat(au)>0){ap.style.height=au+"px"}}az=0;f.snapGrid=[];f.slidesGrid=[];for(aw=0;aw<f.slides.length;aw++){f.snapGrid.push(az);f.slidesGrid.push(az);az+=J;if(!a.cssWidthAndHeight){if(parseFloat(ao)>0){f.slides[aw].style.width=ao+"px"}if(parseFloat(aq)>0){f.slides[aw].style.height=aq+"px"}}}}}if(!f.initialized){f.callPlugins("onFirstInit");if(a.onFirstInit){f.fireCallback(a.onFirstInit,f)}}else{f.callPlugins("onInit");if(a.onInit){f.fireCallback(a.onInit,f)}}f.initialized=true};f.reInit=function(i){f.init(true,i)};f.resizeFix=function(i){f.callPlugins("beforeResizeFix");f.init(a.resizeReInit||i);if(!a.freeMode){f.swipeTo((a.loop?f.activeLoopIndex:f.activeIndex),0,false);if(a.autoplay){if(f.support.transitions&&typeof K!=="undefined"){if(typeof K!=="undefined"){clearTimeout(K);K=undefined;f.startAutoplay()}}else{if(typeof X!=="undefined"){clearInterval(X);X=undefined;f.startAutoplay()}}}}else{if(f.getWrapperTranslate()<-ab()){f.setWrapperTransition(0);f.setWrapperTranslate(-ab())}}f.callPlugins("afterResizeFix")};function ab(){var i=(ae-d);if(a.freeMode){i=ae-d}if(a.slidesPerView>f.slides.length&&!a.centeredSlides){i=0}if(i<0){i=0}return i}function m(){var ai=f.h.addEventListener;var ah=a.eventTarget==="wrapper"?f.wrapper:f.container;if(!(f.browser.ie10||f.browser.ie11)){if(f.support.touch){ai(ah,"touchstart",U);ai(ah,"touchmove",aa);ai(ah,"touchend",N)}if(a.simulateTouch){ai(ah,"mousedown",U);ai(document,"mousemove",aa);ai(document,"mouseup",N)}}else{ai(ah,f.touchEvents.touchStart,U);ai(document,f.touchEvents.touchMove,aa);ai(document,f.touchEvents.touchEnd,N)}if(a.autoResize){ai(window,"resize",f.resizeFix)}q();f._wheelEvent=false;if(a.mousewheelControl){if(document.onmousewheel!==undefined){f._wheelEvent="mousewheel"}if(!f._wheelEvent){try{new WheelEvent("wheel");f._wheelEvent="wheel"}catch(ag){}}if(!f._wheelEvent){f._wheelEvent="DOMMouseScroll"}if(f._wheelEvent){ai(f.container,f._wheelEvent,c)}}function p(aj){var i=new Image();i.onload=function(){if(f&&f.imagesLoaded!==undefined){f.imagesLoaded++}if(f.imagesLoaded===f.imagesToLoad.length){f.reInit();if(a.onImagesReady){f.fireCallback(a.onImagesReady,f)}}};i.src=aj}if(a.keyboardControl){ai(document,"keydown",F)}if(a.updateOnImagesReady){f.imagesToLoad=R("img",f.container);for(var j=0;j<f.imagesToLoad.length;j++){p(f.imagesToLoad[j].getAttribute("src"))}}}f.destroy=function(){var i=f.h.removeEventListener;var j=a.eventTarget==="wrapper"?f.wrapper:f.container;if(!(f.browser.ie10||f.browser.ie11)){if(f.support.touch){i(j,"touchstart",U);i(j,"touchmove",aa);i(j,"touchend",N)}if(a.simulateTouch){i(j,"mousedown",U);i(document,"mousemove",aa);i(document,"mouseup",N)}}else{i(j,f.touchEvents.touchStart,U);i(document,f.touchEvents.touchMove,aa);i(document,f.touchEvents.touchEnd,N)}if(a.autoResize){i(window,"resize",f.resizeFix)}o();if(a.paginationClickable){C()}if(a.mousewheelControl&&f._wheelEvent){i(f.container,f._wheelEvent,c)}if(a.keyboardControl){i(document,"keydown",F)}if(a.autoplay){f.stopAutoplay()}f.callPlugins("onDestroy");f=null};function q(){var ah=f.h.addEventListener,ag;if(a.preventLinks){var j=R("a",f.container);for(ag=0;ag<j.length;ag++){ah(j[ag],"click",O)}}if(a.releaseFormElements){var p=R("input, textarea, select",f.container);for(ag=0;ag<p.length;ag++){ah(p[ag],f.touchEvents.touchStart,y,true)}}if(a.onSlideClick){for(ag=0;ag<f.slides.length;ag++){ah(f.slides[ag],"click",W)}}if(a.onSlideTouch){for(ag=0;ag<f.slides.length;ag++){ah(f.slides[ag],f.touchEvents.touchStart,k)}}}function o(){var ah=f.h.removeEventListener,ag;if(a.onSlideClick){for(ag=0;ag<f.slides.length;ag++){ah(f.slides[ag],"click",W)}}if(a.onSlideTouch){for(ag=0;ag<f.slides.length;ag++){ah(f.slides[ag],f.touchEvents.touchStart,k)}}if(a.releaseFormElements){var p=R("input, textarea, select",f.container);for(ag=0;ag<p.length;ag++){ah(p[ag],f.touchEvents.touchStart,y,true)}}if(a.preventLinks){var j=R("a",f.container);for(ag=0;ag<j.length;ag++){ah(j[ag],"click",O)}}}function F(am){var ak=am.keyCode||am.charCode;if(am.shiftKey||am.altKey||am.ctrlKey||am.metaKey){return}if(ak===37||ak===39||ak===38||ak===40){var an=false;var al=f.h.getOffset(f.container);var ai=f.h.windowScroll().left;var ag=f.h.windowScroll().top;var ah=f.h.windowWidth();var j=f.h.windowHeight();var p=[[al.left,al.top],[al.left+f.width,al.top],[al.left,al.top+f.height],[al.left+f.width,al.top+f.height]];for(var aj=0;aj<p.length;aj++){var ao=p[aj];if(ao[0]>=ai&&ao[0]<=ai+ah&&ao[1]>=ag&&ao[1]<=ag+j){an=true}}if(!an){return}}if(D){if(ak===37||ak===39){if(am.preventDefault){am.preventDefault()}else{am.returnValue=false}}if(ak===39){f.swipeNext()}if(ak===37){f.swipePrev()}}else{if(ak===38||ak===40){if(am.preventDefault){am.preventDefault()}else{am.returnValue=false}}if(ak===40){f.swipeNext()}if(ak===38){f.swipePrev()}}}f.disableKeyboardControl=function(){a.keyboardControl=false;f.h.removeEventListener(document,"keydown",F)};f.enableKeyboardControl=function(){a.keyboardControl=true;f.h.addEventListener(document,"keydown",F)};var T=(new Date()).getTime();function c(p){var j=f._wheelEvent;var ag=0;if(p.detail){ag=-p.detail}else{if(j==="mousewheel"){if(a.mousewheelControlForceToAxis){if(D){if(Math.abs(p.wheelDeltaX)>Math.abs(p.wheelDeltaY)){ag=p.wheelDeltaX}else{return}}else{if(Math.abs(p.wheelDeltaY)>Math.abs(p.wheelDeltaX)){ag=p.wheelDeltaY}else{return}}}else{ag=p.wheelDelta}}else{if(j==="DOMMouseScroll"){ag=-p.detail}else{if(j==="wheel"){if(a.mousewheelControlForceToAxis){if(D){if(Math.abs(p.deltaX)>Math.abs(p.deltaY)){ag=-p.deltaX}else{return}}else{if(Math.abs(p.deltaY)>Math.abs(p.deltaX)){ag=-p.deltaY}else{return}}}else{ag=Math.abs(p.deltaX)>Math.abs(p.deltaY)?-p.deltaX:-p.deltaY}}}}}if(!a.freeMode){if((new Date()).getTime()-T>60){if(ag<0){f.swipeNext()}else{f.swipePrev()}}T=(new Date()).getTime()}else{var i=f.getWrapperTranslate()+ag;if(i>0){i=0}if(i<-ab()){i=-ab()}f.setWrapperTransition(0);f.setWrapperTranslate(i);f.updateActiveSlide(i);if(i===0||i===-ab()){return}}if(a.autoplay){f.stopAutoplay(true)}if(p.preventDefault){p.preventDefault()}else{p.returnValue=false}return false}if(a.grabCursor){var g=f.container.style;g.cursor="move";g.cursor="grab";g.cursor="-moz-grab";g.cursor="-webkit-grab"}f.allowSlideClick=true;function W(i){if(f.allowSlideClick){E(i);f.fireCallback(a.onSlideClick,f,i)}}function k(i){E(i);f.fireCallback(a.onSlideTouch,f,i)}function E(j){if(!j.currentTarget){var i=j.srcElement;do{if(i.className.indexOf(a.slideClass)>-1){break}i=i.parentNode}while(i);f.clickedSlide=i}else{f.clickedSlide=j.currentTarget}f.clickedSlideIndex=f.slides.indexOf(f.clickedSlide);f.clickedSlideLoopIndex=f.clickedSlideIndex-(f.loopedSlides||0)}f.allowLinks=true;function O(i){if(!f.allowLinks){if(i.preventDefault){i.preventDefault()}else{i.returnValue=false}if(a.preventLinksPropagation&&"stopPropagation" in i){i.stopPropagation()}return false}}function y(i){if(i.stopPropagation){i.stopPropagation()}else{i.returnValue=false}return false}var w=false;var L;var ad=true;function U(p){if(a.preventLinks){f.allowLinks=true}if(f.isTouched||a.onlyExternal){return false}if(a.noSwiping&&(p.target||p.srcElement)&&I(p.target||p.srcElement)){return false}ad=false;f.isTouched=true;w=p.type==="touchstart";if(!w||p.targetTouches.length===1){f.callPlugins("onTouchStartBegin");if(!w&&!f.isAndroid){if(p.preventDefault){p.preventDefault()}else{p.returnValue=false}}var j=w?p.targetTouches[0].pageX:(p.pageX||p.clientX);var i=w?p.targetTouches[0].pageY:(p.pageY||p.clientY);f.touches.startX=f.touches.currentX=j;f.touches.startY=f.touches.currentY=i;f.touches.start=f.touches.current=D?j:i;f.setWrapperTransition(0);f.positions.start=f.positions.current=f.getWrapperTranslate();f.setWrapperTranslate(f.positions.start);f.times.start=(new Date()).getTime();b=undefined;if(a.moveStartThreshold>0){L=false}if(a.onTouchStart){f.fireCallback(a.onTouchStart,f)}f.callPlugins("onTouchStartEnd")}}var h,H;function aa(ah){if(!f.isTouched||a.onlyExternal){return}if(w&&ah.type==="mousemove"){return}var ag=w?ah.targetTouches[0].pageX:(ah.pageX||ah.clientX);var j=w?ah.targetTouches[0].pageY:(ah.pageY||ah.clientY);if(typeof b==="undefined"&&D){b=!!(b||Math.abs(j-f.touches.startY)>Math.abs(ag-f.touches.startX))}if(typeof b==="undefined"&&!D){b=!!(b||Math.abs(j-f.touches.startY)<Math.abs(ag-f.touches.startX))}if(b){f.isTouched=false;return}if(ah.assignedToSwiper){f.isTouched=false;return}ah.assignedToSwiper=true;if(a.preventLinks){f.allowLinks=false}if(a.onSlideClick){f.allowSlideClick=false}if(a.autoplay){f.stopAutoplay(true)}if(!w||ah.touches.length===1){if(!f.isMoved){f.callPlugins("onTouchMoveStart");if(a.loop){f.fixLoop();f.positions.start=f.getWrapperTranslate()}if(a.onTouchMoveStart){f.fireCallback(a.onTouchMoveStart,f)}}f.isMoved=true;if(ah.preventDefault){ah.preventDefault()}else{ah.returnValue=false}f.touches.current=D?ag:j;f.positions.current=(f.touches.current-f.touches.start)*a.touchRatio+f.positions.start;if(f.positions.current>0&&a.onResistanceBefore){f.fireCallback(a.onResistanceBefore,f,f.positions.current)}if(f.positions.current<-ab()&&a.onResistanceAfter){f.fireCallback(a.onResistanceAfter,f,Math.abs(f.positions.current+ab()))}if(a.resistance&&a.resistance!=="100%"){var p;if(f.positions.current>0){p=1-f.positions.current/d/2;if(p<0.5){f.positions.current=(d/2)}else{f.positions.current=f.positions.current*p}}if(f.positions.current<-ab()){var ai=(f.touches.current-f.touches.start)*a.touchRatio+(ab()+f.positions.start);p=(d+ai)/(d);var i=f.positions.current-ai*(1-p)/2;var aj=-ab()-d/2;if(i<aj||p<=0){f.positions.current=aj}else{f.positions.current=i}}}if(a.resistance&&a.resistance==="100%"){if(f.positions.current>0&&!(a.freeMode&&!a.freeModeFluid)){f.positions.current=0}if(f.positions.current<-ab()&&!(a.freeMode&&!a.freeModeFluid)){f.positions.current=-ab()}}if(!a.followFinger){return}if(!a.moveStartThreshold){f.setWrapperTranslate(f.positions.current)}else{if(Math.abs(f.touches.current-f.touches.start)>a.moveStartThreshold||L){if(!L){L=true;f.touches.start=f.touches.current;return}f.setWrapperTranslate(f.positions.current)}else{f.positions.current=f.positions.start}}if(a.freeMode||a.watchActiveIndex){f.updateActiveSlide(f.positions.current)}if(a.grabCursor){f.container.style.cursor="move";f.container.style.cursor="grabbing";f.container.style.cursor="-moz-grabbin";f.container.style.cursor="-webkit-grabbing"}if(!h){h=f.touches.current}if(!H){H=(new Date()).getTime()}f.velocity=(f.touches.current-h)/((new Date()).getTime()-H)/2;if(Math.abs(f.touches.current-h)<2){f.velocity=0}h=f.touches.current;H=(new Date()).getTime();f.callPlugins("onTouchMoveEnd");if(a.onTouchMove){f.fireCallback(a.onTouchMove,f)}return false}}function N(p){if(b){f.swipeReset()}if(a.onlyExternal||!f.isTouched){return}f.isTouched=false;if(a.grabCursor){f.container.style.cursor="move";f.container.style.cursor="grab";f.container.style.cursor="-moz-grab";f.container.style.cursor="-webkit-grab"}if(!f.positions.current&&f.positions.current!==0){f.positions.current=f.positions.start}if(a.followFinger){f.setWrapperTranslate(f.positions.current)}f.times.end=(new Date()).getTime();f.touches.diff=f.touches.current-f.touches.start;f.touches.abs=Math.abs(f.touches.diff);f.positions.diff=f.positions.current-f.positions.start;f.positions.abs=Math.abs(f.positions.diff);var aq=f.positions.diff;var au=f.positions.abs;var j=f.times.end-f.times.start;if(au<5&&(j)<300&&f.allowLinks===false){if(!a.freeMode&&au!==0){f.swipeReset()}if(a.preventLinks){f.allowLinks=true}if(a.onSlideClick){f.allowSlideClick=true}}setTimeout(function(){if(a.preventLinks){f.allowLinks=true}if(a.onSlideClick){f.allowSlideClick=true}},100);var am=ab();if(!f.isMoved&&a.freeMode){f.isMoved=false;if(a.onTouchEnd){f.fireCallback(a.onTouchEnd,f)}f.callPlugins("onTouchEnd");return}if(!f.isMoved||f.positions.current>0||f.positions.current<-am){f.swipeReset();if(a.onTouchEnd){f.fireCallback(a.onTouchEnd,f)}f.callPlugins("onTouchEnd");return}f.isMoved=false;if(a.freeMode){if(a.freeModeFluid){var an=1000*a.momentumRatio;var aj=f.velocity*an;var ai=f.positions.current+aj;var ah=false;var ao;var at=Math.abs(f.velocity)*20*a.momentumBounceRatio;if(ai<-am){if(a.momentumBounce&&f.support.transitions){if(ai+am<-at){ai=-am-at}ao=-am;ah=true;ad=true}else{ai=-am}}if(ai>0){if(a.momentumBounce&&f.support.transitions){if(ai>at){ai=at}ao=0;ah=true;ad=true}else{ai=0}}if(f.velocity!==0){an=Math.abs((ai-f.positions.current)/f.velocity)}f.setWrapperTranslate(ai);f.setWrapperTransition(an);if(a.momentumBounce&&ah){f.wrapperTransitionEnd(function(){if(!ad){return}if(a.onMomentumBounce){f.fireCallback(a.onMomentumBounce,f)}f.callPlugins("onMomentumBounce");f.setWrapperTranslate(ao);f.setWrapperTransition(300)})}f.updateActiveSlide(ai)}if(!a.freeModeFluid||j>=300){f.updateActiveSlide(f.positions.current)}if(a.onTouchEnd){f.fireCallback(a.onTouchEnd,f)}f.callPlugins("onTouchEnd");return}r=aq<0?"toNext":"toPrev";if(r==="toNext"&&(j<=300)){if(au<30||!a.shortSwipes){f.swipeReset()}else{f.swipeNext(true)}}if(r==="toPrev"&&(j<=300)){if(au<30||!a.shortSwipes){f.swipeReset()}else{f.swipePrev(true)}}var ar=0;if(a.slidesPerView==="auto"){var ag=Math.abs(f.getWrapperTranslate());var ap=0;var al;for(var ak=0;ak<f.slides.length;ak++){al=D?f.slides[ak].getWidth(true,a.roundLengths):f.slides[ak].getHeight(true,a.roundLengths);ap+=al;if(ap>ag){ar=al;break}}if(ar>d){ar=d}}else{ar=J*a.slidesPerView}if(r==="toNext"&&(j>300)){if(au>=ar*a.longSwipesRatio){f.swipeNext(true)}else{f.swipeReset()}}if(r==="toPrev"&&(j>300)){if(au>=ar*a.longSwipesRatio){f.swipePrev(true)}else{f.swipeReset()}}if(a.onTouchEnd){f.fireCallback(a.onTouchEnd,f)}f.callPlugins("onTouchEnd")}function I(j){var i=false;do{if(j.className.indexOf(a.noSwipingClass)>-1){i=true}j=j.parentElement}while(!i&&j.parentElement&&j.className.indexOf(a.wrapperClass)===-1);if(!i&&j.className.indexOf(a.wrapperClass)>-1&&j.className.indexOf(a.noSwipingClass)>-1){i=true}return i}function M(i,j){var p=document.createElement("div");var ag;p.innerHTML=j;ag=p.firstChild;ag.className+=" "+i;return ag.outerHTML}f.swipeNext=function(p){if(!p&&a.loop){f.fixLoop()}if(!p&&a.autoplay){f.stopAutoplay(true)}f.callPlugins("onSwipeNext");var ai=f.getWrapperTranslate();var ah=ai;if(a.slidesPerView==="auto"){for(var ag=0;ag<f.snapGrid.length;ag++){if(-ai>=f.snapGrid[ag]&&-ai<f.snapGrid[ag+1]){ah=-f.snapGrid[ag+1];break}}}else{var j=J*a.slidesPerGroup;ah=-(Math.floor(Math.abs(ai)/Math.floor(j))*j+j)}if(ah<-ab()){ah=-ab()}if(ah===ai){return false}n(ah,"next");return true};f.swipePrev=function(p){if(!p&&a.loop){f.fixLoop()}if(!p&&a.autoplay){f.stopAutoplay(true)}f.callPlugins("onSwipePrev");var ai=Math.ceil(f.getWrapperTranslate());var ah;if(a.slidesPerView==="auto"){ah=0;for(var ag=1;ag<f.snapGrid.length;ag++){if(-ai===f.snapGrid[ag]){ah=-f.snapGrid[ag-1];break}if(-ai>f.snapGrid[ag]&&-ai<f.snapGrid[ag+1]){ah=-f.snapGrid[ag];break}}}else{var j=J*a.slidesPerGroup;ah=-(Math.ceil(-ai/j)-1)*j}if(ah>0){ah=0}if(ah===ai){return false}n(ah,"prev");return true};f.swipeReset=function(){f.callPlugins("onSwipeReset");var ah=f.getWrapperTranslate();var j=J*a.slidesPerGroup;var ag;var ai=-ab();if(a.slidesPerView==="auto"){ag=0;for(var p=0;p<f.snapGrid.length;p++){if(-ah===f.snapGrid[p]){return}if(-ah>=f.snapGrid[p]&&-ah<f.snapGrid[p+1]){if(f.positions.diff>0){ag=-f.snapGrid[p+1]}else{ag=-f.snapGrid[p]}break}}if(-ah>=f.snapGrid[f.snapGrid.length-1]){ag=-f.snapGrid[f.snapGrid.length-1]}if(ah<=-ab()){ag=-ab()}}else{ag=ah<0?Math.round(ah/j)*j:0}if(a.scrollContainer){ag=ah<0?ah:0}if(ag<-ab()){ag=-ab()}if(a.scrollContainer&&(d>J)){ag=0}if(ag===ah){return false}n(ag,"reset");return true};f.swipeTo=function(i,ag,ah){i=parseInt(i,10);f.callPlugins("onSwipeTo",{index:i,speed:ag});if(a.loop){i=i+f.loopedSlides}var p=f.getWrapperTranslate();if(i>(f.slides.length-1)||i<0){return}var j;if(a.slidesPerView==="auto"){j=-f.slidesGrid[i]}else{j=-i*J}if(j<-ab()){j=-ab()}if(j===p){return false}ah=ah===false?false:true;n(j,"to",{index:i,speed:ag,runCallbacks:ah});return true};function n(ag,ah,am){var j=(ah==="to"&&am.speed>=0)?am.speed:a.speed;var aj=+new Date();function ai(){var an=+new Date();var ao=an-aj;p+=ak*ao/(1000/60);i=al==="toNext"?p>ag:p<ag;if(i){f.setWrapperTranslate(Math.round(p));f._DOMAnimating=true;window.setTimeout(function(){ai()},1000/60)}else{if(a.onSlideChangeEnd){if(ah==="to"){if(am.runCallbacks===true){f.fireCallback(a.onSlideChangeEnd,f)}}else{f.fireCallback(a.onSlideChangeEnd,f)}}f.setWrapperTranslate(ag);f._DOMAnimating=false}}if(f.support.transitions||!a.DOMAnimation){f.setWrapperTranslate(ag);f.setWrapperTransition(j)}else{var p=f.getWrapperTranslate();var ak=Math.ceil((ag-p)/j*(1000/60));var al=p>ag?"toNext":"toPrev";var i=al==="toNext"?p>ag:p<ag;if(f._DOMAnimating){return}ai()}f.updateActiveSlide(ag);if(a.onSlideNext&&ah==="next"){f.fireCallback(a.onSlideNext,f,ag)}if(a.onSlidePrev&&ah==="prev"){f.fireCallback(a.onSlidePrev,f,ag)}if(a.onSlideReset&&ah==="reset"){f.fireCallback(a.onSlideReset,f,ag)}if(ah==="next"||ah==="prev"||(ah==="to"&&am.runCallbacks===true)){af(ah)}}f._queueStartCallbacks=false;f._queueEndCallbacks=false;function af(i){f.callPlugins("onSlideChangeStart");if(a.onSlideChangeStart){if(a.queueStartCallbacks&&f.support.transitions){if(f._queueStartCallbacks){return}f._queueStartCallbacks=true;f.fireCallback(a.onSlideChangeStart,f,i);f.wrapperTransitionEnd(function(){f._queueStartCallbacks=false})}else{f.fireCallback(a.onSlideChangeStart,f,i)}}if(a.onSlideChangeEnd){if(f.support.transitions){if(a.queueEndCallbacks){if(f._queueEndCallbacks){return}f._queueEndCallbacks=true;f.wrapperTransitionEnd(function(j){f.fireCallback(a.onSlideChangeEnd,j,i)})}else{f.wrapperTransitionEnd(function(j){f.fireCallback(a.onSlideChangeEnd,j,i)})}}else{if(!a.DOMAnimation){setTimeout(function(){f.fireCallback(a.onSlideChangeEnd,f,i)},10)}}}}f.updateActiveSlide=function(aj){if(!f.initialized){return}if(f.slides.length===0){return}f.previousIndex=f.activeIndex;if(typeof aj==="undefined"){aj=f.getWrapperTranslate()}if(aj>0){aj=0}var ai;if(a.slidesPerView==="auto"){var am=0;f.activeIndex=f.slidesGrid.indexOf(-aj);if(f.activeIndex<0){for(ai=0;ai<f.slidesGrid.length-1;ai++){if(-aj>f.slidesGrid[ai]&&-aj<f.slidesGrid[ai+1]){break}}var ag=Math.abs(f.slidesGrid[ai]+aj);var p=Math.abs(f.slidesGrid[ai+1]+aj);if(ag<=p){f.activeIndex=ai}else{f.activeIndex=ai+1}}}else{f.activeIndex=Math[a.visibilityFullFit?"ceil":"round"](-aj/J)}if(f.activeIndex===f.slides.length){f.activeIndex=f.slides.length-1}if(f.activeIndex<0){f.activeIndex=0}if(!f.slides[f.activeIndex]){return}f.calcVisibleSlides(aj);if(f.support.classList){var ak;for(ai=0;ai<f.slides.length;ai++){ak=f.slides[ai];ak.classList.remove(a.slideActiveClass);if(f.visibleSlides.indexOf(ak)>=0){ak.classList.add(a.slideVisibleClass)}else{ak.classList.remove(a.slideVisibleClass)}}f.slides[f.activeIndex].classList.add(a.slideActiveClass)}else{var al=new RegExp("\\s*"+a.slideActiveClass);var j=new RegExp("\\s*"+a.slideVisibleClass);for(ai=0;ai<f.slides.length;ai++){f.slides[ai].className=f.slides[ai].className.replace(al,"").replace(j,"");if(f.visibleSlides.indexOf(f.slides[ai])>=0){f.slides[ai].className+=" "+a.slideVisibleClass}}f.slides[f.activeIndex].className+=" "+a.slideActiveClass}if(a.loop){var ah=f.loopedSlides;f.activeLoopIndex=f.activeIndex-ah;if(f.activeLoopIndex>=f.slides.length-ah*2){f.activeLoopIndex=f.slides.length-ah*2-f.activeLoopIndex}if(f.activeLoopIndex<0){f.activeLoopIndex=f.slides.length-ah*2+f.activeLoopIndex}if(f.activeLoopIndex<0){f.activeLoopIndex=0}}else{f.activeLoopIndex=f.activeIndex}if(a.pagination){f.updatePagination(aj)}};f.createPagination=function(p){if(a.paginationClickable&&f.paginationButtons){C()}f.paginationContainer=a.pagination.nodeType?a.pagination:R(a.pagination)[0];if(a.createPagination){var j="";var ai=f.slides.length;var ah=ai;if(a.loop){ah-=f.loopedSlides*2}for(var ag=0;ag<ah;ag++){j+="<"+a.paginationElement+' class="'+a.paginationElementClass+'"></'+a.paginationElement+">"}f.paginationContainer.innerHTML=j}f.paginationButtons=R("."+a.paginationElementClass,f.paginationContainer);if(!p){f.updatePagination()}f.callPlugins("onCreatePagination");if(a.paginationClickable){Y()}};function C(){var j=f.paginationButtons;if(j){for(var p=0;p<j.length;p++){f.h.removeEventListener(j[p],"click",u)}}}function Y(){var j=f.paginationButtons;if(j){for(var p=0;p<j.length;p++){f.h.addEventListener(j[p],"click",u)}}}function u(ai){var p;var ah=ai.target||ai.srcElement;var j=f.paginationButtons;for(var ag=0;ag<j.length;ag++){if(ah===j[ag]){p=ag}}f.swipeTo(p)}f.updatePagination=function(p){if(!a.pagination){return}if(f.slides.length<1){return}var ak=R("."+a.paginationActiveClass,f.paginationContainer);if(!ak){return}var ai=f.paginationButtons;if(ai.length===0){return}for(var aj=0;aj<ai.length;aj++){ai[aj].className=a.paginationElementClass}var am=a.loop?f.loopedSlides:0;if(a.paginationAsRange){if(!f.visibleSlides){f.calcVisibleSlides(p)}var ag=[];var ah;for(ah=0;ah<f.visibleSlides.length;ah++){var al=f.slides.indexOf(f.visibleSlides[ah])-am;if(a.loop&&al<0){al=f.slides.length-f.loopedSlides*2+al}if(a.loop&&al>=f.slides.length-f.loopedSlides*2){al=f.slides.length-f.loopedSlides*2-al;al=Math.abs(al)}ag.push(al)}for(ah=0;ah<ag.length;ah++){if(ai[ag[ah]]){ai[ag[ah]].className+=" "+a.paginationVisibleClass}}if(a.loop){if(ai[f.activeLoopIndex]!==undefined){ai[f.activeLoopIndex].className+=" "+a.paginationActiveClass}}else{ai[f.activeIndex].className+=" "+a.paginationActiveClass}}else{if(a.loop){if(ai[f.activeLoopIndex]){ai[f.activeLoopIndex].className+=" "+a.paginationActiveClass+" "+a.paginationVisibleClass}}else{ai[f.activeIndex].className+=" "+a.paginationActiveClass+" "+a.paginationVisibleClass}}};f.calcVisibleSlides=function(j){var ai=[];var ak=0,ah=0,aj=0;if(D&&f.wrapperLeft>0){j=j+f.wrapperLeft}if(!D&&f.wrapperTop>0){j=j+f.wrapperTop}for(var ag=0;ag<f.slides.length;ag++){ak+=ah;if(a.slidesPerView==="auto"){ah=D?f.h.getWidth(f.slides[ag],true,a.roundLengths):f.h.getHeight(f.slides[ag],true,a.roundLengths)}else{ah=J}aj=ak+ah;var p=false;if(a.visibilityFullFit){if(ak>=-j&&aj<=-j+d){p=true}if(ak<=-j&&aj>=-j+d){p=true}}else{if(aj>-j&&aj<=((-j+d))){p=true}if(ak>=-j&&ak<((-j+d))){p=true}if(ak<-j&&aj>((-j+d))){p=true}}if(p){ai.push(f.slides[ag])}}if(ai.length===0){ai=[f.slides[f.activeIndex]]}f.visibleSlides=ai};var K,X;f.startAutoplay=function(){if(f.support.transitions){if(typeof K!=="undefined"){return false}if(!a.autoplay){return}f.callPlugins("onAutoplayStart");if(a.onAutoplayStart){f.fireCallback(a.onAutoplayStart,f)}Z()}else{if(typeof X!=="undefined"){return false}if(!a.autoplay){return}f.callPlugins("onAutoplayStart");if(a.onAutoplayStart){f.fireCallback(a.onAutoplayStart,f)}X=setInterval(function(){if(a.loop){f.fixLoop();f.swipeNext(true)}else{if(!f.swipeNext(true)){if(!a.autoplayStopOnLast){f.swipeTo(0)}else{clearInterval(X);X=undefined}}}},a.autoplay)}};f.stopAutoplay=function(i){if(f.support.transitions){if(!K){return}if(K){clearTimeout(K)}K=undefined;if(i&&!a.autoplayDisableOnInteraction){f.wrapperTransitionEnd(function(){Z()})}f.callPlugins("onAutoplayStop");if(a.onAutoplayStop){f.fireCallback(a.onAutoplayStop,f)}}else{if(X){clearInterval(X)}X=undefined;f.callPlugins("onAutoplayStop");if(a.onAutoplayStop){f.fireCallback(a.onAutoplayStop,f)}}};function Z(){K=setTimeout(function(){if(a.loop){f.fixLoop();f.swipeNext(true)}else{if(!f.swipeNext(true)){if(!a.autoplayStopOnLast){f.swipeTo(0)}else{clearTimeout(K);K=undefined}}}f.wrapperTransitionEnd(function(){if(typeof K!=="undefined"){Z()}})},a.autoplay)}f.loopCreated=false;f.removeLoopedSlides=function(){if(f.loopCreated){for(var j=0;j<f.slides.length;j++){if(f.slides[j].getData("looped")===true){f.wrapper.removeChild(f.slides[j])}}}};f.createLoop=function(){if(f.slides.length===0){return}if(a.slidesPerView==="auto"){f.loopedSlides=a.loopedSlides||1}else{f.loopedSlides=a.slidesPerView+a.loopAdditionalSlides}if(f.loopedSlides>f.slides.length){f.loopedSlides=f.slides.length}var an="",ak="",aj;var ai="";var ao=f.slides.length;var ag=Math.floor(f.loopedSlides/ao);var am=f.loopedSlides%ao;for(aj=0;aj<(ag*ao);aj++){var ah=aj;if(aj>=ao){var al=Math.floor(aj/ao);ah=aj-(ao*al)}ai+=f.slides[ah].outerHTML}for(aj=0;aj<am;aj++){ak+=M(a.slideDuplicateClass,f.slides[aj].outerHTML)}for(aj=ao-am;aj<ao;aj++){an+=M(a.slideDuplicateClass,f.slides[aj].outerHTML)}var p=an+ai+z.innerHTML+ai+ak;z.innerHTML=p;f.loopCreated=true;f.calcSlides();for(aj=0;aj<f.slides.length;aj++){if(aj<f.loopedSlides||aj>=f.slides.length-f.loopedSlides){f.slides[aj].setData("looped",true)}}f.callPlugins("onCreateLoop")};f.fixLoop=function(){var i;if(f.activeIndex<f.loopedSlides){i=f.slides.length-f.loopedSlides*3+f.activeIndex;f.swipeTo(i,0,false)}else{if((a.slidesPerView==="auto"&&f.activeIndex>=f.loopedSlides*2)||(f.activeIndex>f.slides.length-a.slidesPerView*2)){i=-f.slides.length+f.activeIndex+f.loopedSlides;f.swipeTo(i,0,false)}}};f.loadSlides=function(){var ag="";f.activeLoaderIndex=0;var p=a.loader.slides;var ah=a.loader.loadAllSlides?p.length:a.slidesPerView*(1+a.loader.surroundGroups);for(var j=0;j<ah;j++){if(a.loader.slidesHTMLType==="outer"){ag+=p[j]}else{ag+="<"+a.slideElement+' class="'+a.slideClass+'" data-swiperindex="'+j+'">'+p[j]+"</"+a.slideElement+">"}}f.wrapper.innerHTML=ag;f.calcSlides(true);if(!a.loader.loadAllSlides){f.wrapperTransitionEnd(f.reloadSlides,true)}};f.reloadSlides=function(){var j=a.loader.slides;var am=parseInt(f.activeSlide().data("swiperindex"),10);if(am<0||am>j.length-1){return}f.activeLoaderIndex=am;var p=Math.max(0,am-a.slidesPerView*a.loader.surroundGroups);var ak=Math.min(am+a.slidesPerView*(1+a.loader.surroundGroups)-1,j.length-1);if(am>0){var ag=-J*(am-p);f.setWrapperTranslate(ag);f.setWrapperTransition(0)}var aj;if(a.loader.logic==="reload"){f.wrapper.innerHTML="";var an="";for(aj=p;aj<=ak;aj++){an+=a.loader.slidesHTMLType==="outer"?j[aj]:"<"+a.slideElement+' class="'+a.slideClass+'" data-swiperindex="'+aj+'">'+j[aj]+"</"+a.slideElement+">"}f.wrapper.innerHTML=an}else{var ah=1000;var ai=0;for(aj=0;aj<f.slides.length;aj++){var al=f.slides[aj].data("swiperindex");if(al<p||al>ak){f.wrapper.removeChild(f.slides[aj])}else{ah=Math.min(al,ah);ai=Math.max(al,ai)}}for(aj=p;aj<=ak;aj++){var ao;if(aj<ah){ao=document.createElement(a.slideElement);ao.className=a.slideClass;ao.setAttribute("data-swiperindex",aj);ao.innerHTML=j[aj];f.wrapper.insertBefore(ao,f.wrapper.firstChild)}if(aj>ai){ao=document.createElement(a.slideElement);ao.className=a.slideClass;ao.setAttribute("data-swiperindex",aj);ao.innerHTML=j[aj];f.wrapper.appendChild(ao)}}}f.reInit(true)};function Q(){f.calcSlides();if(a.loader.slides.length>0&&f.slides.length===0){f.loadSlides()}if(a.loop){f.createLoop()}f.init();m();if(a.pagination){f.createPagination(true)}if(a.loop||a.initialSlide>0){f.swipeTo(a.initialSlide,0,false)}else{f.updateActiveSlide(0)}if(a.autoplay){f.startAutoplay()}f.centerIndex=f.activeIndex;if(a.onSwiperCreated){f.fireCallback(a.onSwiperCreated,f)}f.callPlugins("onSwiperCreated")}Q()};Swiper.prototype={plugins:{},wrapperTransitionEnd:function(h,f){var b=this,e=b.wrapper,d=["webkitTransitionEnd","transitionend","oTransitionEnd","MSTransitionEnd","msTransitionEnd"],c;function g(){h(b);if(b.params.queueEndCallbacks){b._queueEndCallbacks=false}if(!f){for(c=0;c<d.length;c++){b.h.removeEventListener(e,d[c],g)}}}if(h){for(c=0;c<d.length;c++){b.h.addEventListener(e,d[c],g)}}},getWrapperTranslate:function(e){var d=this.wrapper,a,c,f,b;if(typeof e==="undefined"){e=this.params.mode==="horizontal"?"x":"y"}if(this.support.transforms&&this.params.useCSS3Transforms){f=window.getComputedStyle(d,null);if(window.WebKitCSSMatrix){b=new WebKitCSSMatrix(f.webkitTransform==="none"?"":f.webkitTransform)}else{b=f.MozTransform||f.OTransform||f.MsTransform||f.msTransform||f.transform||f.getPropertyValue("transform").replace("translate(","matrix(1, 0, 0, 1,");a=b.toString().split(",")}if(e==="x"){if(window.WebKitCSSMatrix){c=b.m41}else{if(a.length===16){c=parseFloat(a[12])}else{c=parseFloat(a[4])}}}if(e==="y"){if(window.WebKitCSSMatrix){c=b.m42}else{if(a.length===16){c=parseFloat(a[13])}else{c=parseFloat(a[5])}}}}else{if(e==="x"){c=parseFloat(d.style.left,10)||0}if(e==="y"){c=parseFloat(d.style.top,10)||0}}return c||0},setWrapperTranslate:function(a,f,e){var d=this.wrapper.style,b={x:0,y:0,z:0},c;if(arguments.length===3){b.x=a;b.y=f;b.z=e}else{if(typeof f==="undefined"){f=this.params.mode==="horizontal"?"x":"y"}b[f]=a}if(this.support.transforms&&this.params.useCSS3Transforms){c=this.support.transforms3d?"translate3d("+b.x+"px, "+b.y+"px, "+b.z+"px)":"translate("+b.x+"px, "+b.y+"px)";d.webkitTransform=d.MsTransform=d.msTransform=d.MozTransform=d.OTransform=d.transform=c}else{d.left=b.x+"px";d.top=b.y+"px"}this.callPlugins("onSetWrapperTransform",b);if(this.params.onSetWrapperTransform){this.fireCallback(this.params.onSetWrapperTransform,this,b)}},setWrapperTransition:function(a){var b=this.wrapper.style;b.webkitTransitionDuration=b.MsTransitionDuration=b.msTransitionDuration=b.MozTransitionDuration=b.OTransitionDuration=b.transitionDuration=(a/1000)+"s";this.callPlugins("onSetWrapperTransition",{duration:a});if(this.params.onSetWrapperTransition){this.fireCallback(this.params.onSetWrapperTransition,this,a)}},h:{getWidth:function(e,c,a){var d=window.getComputedStyle(e,null).getPropertyValue("width");var b=parseFloat(d);if(isNaN(b)||d.indexOf("%")>0){b=e.offsetWidth-parseFloat(window.getComputedStyle(e,null).getPropertyValue("padding-left"))-parseFloat(window.getComputedStyle(e,null).getPropertyValue("padding-right"))}if(c){b+=parseFloat(window.getComputedStyle(e,null).getPropertyValue("padding-left"))+parseFloat(window.getComputedStyle(e,null).getPropertyValue("padding-right"))}if(a){return Math.round(b)}else{return b}},getHeight:function(d,c,b){if(c){return d.offsetHeight}var a=window.getComputedStyle(d,null).getPropertyValue("height");var e=parseFloat(a);if(isNaN(e)||a.indexOf("%")>0){e=d.offsetHeight-parseFloat(window.getComputedStyle(d,null).getPropertyValue("padding-top"))-parseFloat(window.getComputedStyle(d,null).getPropertyValue("padding-bottom"))}if(c){e+=parseFloat(window.getComputedStyle(d,null).getPropertyValue("padding-top"))+parseFloat(window.getComputedStyle(d,null).getPropertyValue("padding-bottom"))}if(b){return Math.round(e)}else{return e}},getOffset:function(b){var c=b.getBoundingClientRect();var a=document.body;var g=b.clientTop||a.clientTop||0;var f=b.clientLeft||a.clientLeft||0;var d=window.pageYOffset||b.scrollTop;var e=window.pageXOffset||b.scrollLeft;if(document.documentElement&&!window.pageYOffset){d=document.documentElement.scrollTop;e=document.documentElement.scrollLeft}return{top:c.top+d-g,left:c.left+e-f}},windowWidth:function(){if(window.innerWidth){return window.innerWidth}else{if(document.documentElement&&document.documentElement.clientWidth){return document.documentElement.clientWidth}}},windowHeight:function(){if(window.innerHeight){return window.innerHeight}else{if(document.documentElement&&document.documentElement.clientHeight){return document.documentElement.clientHeight}}},windowScroll:function(){if(typeof pageYOffset!=="undefined"){return{left:window.pageXOffset,top:window.pageYOffset}}else{if(document.documentElement){return{left:document.documentElement.scrollLeft,top:document.documentElement.scrollTop}}}},addEventListener:function(b,c,d,a){if(typeof a==="undefined"){a=false}if(b.addEventListener){b.addEventListener(c,d,a)}else{if(b.attachEvent){b.attachEvent("on"+c,d)}}},removeEventListener:function(b,c,d,a){if(typeof a==="undefined"){a=false}if(b.removeEventListener){b.removeEventListener(c,d,a)}else{if(b.detachEvent){b.detachEvent("on"+c,d)}}}},setTransform:function(b,a){var c=b.style;c.webkitTransform=c.MsTransform=c.msTransform=c.MozTransform=c.OTransform=c.transform=a},setTranslate:function(b,d){var c=b.style;var e={x:d.x||0,y:d.y||0,z:d.z||0};var a=this.support.transforms3d?"translate3d("+(e.x)+"px,"+(e.y)+"px,"+(e.z)+"px)":"translate("+(e.x)+"px,"+(e.y)+"px)";c.webkitTransform=c.MsTransform=c.msTransform=c.MozTransform=c.OTransform=c.transform=a;if(!this.support.transforms){c.left=e.x+"px";c.top=e.y+"px"}},setTransition:function(a,b){var c=a.style;c.webkitTransitionDuration=c.MsTransitionDuration=c.msTransitionDuration=c.MozTransitionDuration=c.OTransitionDuration=c.transitionDuration=b+"ms"},support:{touch:(window.Modernizr&&Modernizr.touch===true)||(function(){return !!(("ontouchstart" in window)||window.DocumentTouch&&document instanceof DocumentTouch)})(),transforms3d:(window.Modernizr&&Modernizr.csstransforms3d===true)||(function(){var a=document.createElement("div").style;return("webkitPerspective" in a||"MozPerspective" in a||"OPerspective" in a||"MsPerspective" in a||"perspective" in a)})(),transforms:(window.Modernizr&&Modernizr.csstransforms===true)||(function(){var a=document.createElement("div").style;return("transform" in a||"WebkitTransform" in a||"MozTransform" in a||"msTransform" in a||"MsTransform" in a||"OTransform" in a)})(),transitions:(window.Modernizr&&Modernizr.csstransitions===true)||(function(){var a=document.createElement("div").style;return("transition" in a||"WebkitTransition" in a||"MozTransition" in a||"msTransition" in a||"MsTransition" in a||"OTransition" in a)})(),classList:(function(){var a=document.createElement("div").style;return"classList" in a})()},browser:{ie8:(function(){var c=-1;if(navigator.appName==="Microsoft Internet Explorer"){var a=navigator.userAgent;var b=new RegExp(/MSIE ([0-9]{1,}[\.0-9]{0,})/);if(b.exec(a)!==null){c=parseFloat(RegExp.$1)}}return c!==-1&&c<9})(),ie10:window.navigator.msPointerEnabled,ie11:window.navigator.pointerEnabled}};if(window.jQuery||window.Zepto){(function(a){a.fn.swiper=function(c){var b=new Swiper(a(this)[0],c);a(this).data("swiper",b);return b}})(window.jQuery||window.Zepto)}if(typeof(module)!=="undefined"){module.exports=Swiper}if(typeof define==="function"&&define.amd){define([],function(){return Swiper})};
   	/**
	 * jQuery.browser.mobile (http://detectmobilebrowser.com/)
	 *
	 * jQuery.browser.mobile will be true if the browser is a mobile device
	 *
	 **/
	(function(a){(jQuery.browser=jQuery.browser||{}).mobile=/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))})(navigator.userAgent||navigator.vendor||window.opera);

	
	// Animate scroll
	jQuery.easing.jswing=jQuery.easing.swing;jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(e,f,a,h,g){return jQuery.easing[jQuery.easing.def](e,f,a,h,g)},easeInQuad:function(e,f,a,h,g){return h*(f/=g)*f+a},easeOutQuad:function(e,f,a,h,g){return -h*(f/=g)*(f-2)+a},easeInOutQuad:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f+a}return -h/2*((--f)*(f-2)-1)+a},easeInCubic:function(e,f,a,h,g){return h*(f/=g)*f*f+a},easeOutCubic:function(e,f,a,h,g){return h*((f=f/g-1)*f*f+1)+a},easeInOutCubic:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f+a}return h/2*((f-=2)*f*f+2)+a},easeInQuart:function(e,f,a,h,g){return h*(f/=g)*f*f*f+a},easeOutQuart:function(e,f,a,h,g){return -h*((f=f/g-1)*f*f*f-1)+a},easeInOutQuart:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f+a}return -h/2*((f-=2)*f*f*f-2)+a},easeInQuint:function(e,f,a,h,g){return h*(f/=g)*f*f*f*f+a},easeOutQuint:function(e,f,a,h,g){return h*((f=f/g-1)*f*f*f*f+1)+a},easeInOutQuint:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f*f+a}return h/2*((f-=2)*f*f*f*f+2)+a},easeInSine:function(e,f,a,h,g){return -h*Math.cos(f/g*(Math.PI/2))+h+a},easeOutSine:function(e,f,a,h,g){return h*Math.sin(f/g*(Math.PI/2))+a},easeInOutSine:function(e,f,a,h,g){return -h/2*(Math.cos(Math.PI*f/g)-1)+a},easeInExpo:function(e,f,a,h,g){return(f==0)?a:h*Math.pow(2,10*(f/g-1))+a},easeOutExpo:function(e,f,a,h,g){return(f==g)?a+h:h*(-Math.pow(2,-10*f/g)+1)+a},easeInOutExpo:function(e,f,a,h,g){if(f==0){return a}if(f==g){return a+h}if((f/=g/2)<1){return h/2*Math.pow(2,10*(f-1))+a}return h/2*(-Math.pow(2,-10*--f)+2)+a},easeInCirc:function(e,f,a,h,g){return -h*(Math.sqrt(1-(f/=g)*f)-1)+a},easeOutCirc:function(e,f,a,h,g){return h*Math.sqrt(1-(f=f/g-1)*f)+a},easeInOutCirc:function(e,f,a,h,g){if((f/=g/2)<1){return -h/2*(Math.sqrt(1-f*f)-1)+a}return h/2*(Math.sqrt(1-(f-=2)*f)+1)+a},easeInElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return -(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e},easeOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return g*Math.pow(2,-10*h)*Math.sin((h*k-i)*(2*Math.PI)/j)+l+e},easeInOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k/2)==2){return e+l}if(!j){j=k*(0.3*1.5)}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}if(h<1){return -0.5*(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e}return g*Math.pow(2,-10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j)*0.5+l+e},easeInBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*(f/=h)*f*((g+1)*f-g)+a},easeOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*((f=f/h-1)*f*((g+1)*f+g)+1)+a},easeInOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}if((f/=h/2)<1){return i/2*(f*f*(((g*=(1.525))+1)*f-g))+a}return i/2*((f-=2)*f*(((g*=(1.525))+1)*f+g)+2)+a},easeInBounce:function(e,f,a,h,g){return h-jQuery.easing.easeOutBounce(e,g-f,0,h,g)+a},easeOutBounce:function(e,f,a,h,g){if((f/=g)<(1/2.75)){return h*(7.5625*f*f)+a}else{if(f<(2/2.75)){return h*(7.5625*(f-=(1.5/2.75))*f+0.75)+a}else{if(f<(2.5/2.75)){return h*(7.5625*(f-=(2.25/2.75))*f+0.9375)+a}else{return h*(7.5625*(f-=(2.625/2.75))*f+0.984375)+a}}}},easeInOutBounce:function(e,f,a,h,g){if(f<g/2){return jQuery.easing.easeInBounce(e,f*2,0,h,g)*0.5+a}return jQuery.easing.easeOutBounce(e,f*2-g,0,h,g)*0.5+h*0.5+a}});jQuery.fn.animatescroll=function(a){var b=jQuery.extend({},jQuery.fn.animatescroll.defaults,a);if(b.element=="html,body"){var c=this.offset().top;jQuery(b.element).stop().animate({scrollTop:c-b.padding},b.scrollSpeed,b.easing)}else{jQuery(b.element).stop().animate({scrollTop:this.offset().top-this.parent().offset().top+this.parent().scrollTop()-b.padding},b.scrollSpeed,b.easing)}};jQuery.fn.animatescroll.defaults={easing:"swing",scrollSpeed:800,padding:0,element:"html,body"};


	var psbInit = 0;
	var psbExist = 0;
	var psbOpen = 0;

	var glbSwiper;
	var glbSettings = undefined;
	var glbCallback = undefined;
	var isPushed=0;

	jQuery.ClosePumaSideBar = function(Animate){

		psbExist = 0;

		if(Animate == 0){

			jQuery("#PumaSideBar").remove();

			if(isPushed == 1 && glbSettings.position=="left"){
				jQuery("body").css("left","0px");
			}else{
				jQuery("body").css("right","0px");
			}

		}else{

			if(glbSettings.position == "left"){
				jQuery("#PumaSideBar").animate({
					left: "-220px"
				},200,function(){
					jQuery(this).remove();
				});

				
			}else{
				jQuery("#PumaSideBar").animate({
					right: "-220px"
				},200,function(){
					jQuery(this).remove();
				});				
			}

			jQuery("#PumaSideBar").attr("MouseOver","1");
			if(isPushed == 1){
				jQuery("body").animate({
					left: "0px"
				},200);
			}
				
		}

	}

	jQuery.PumaSideBar = function (settings,callback) 
    {
    	psbOpen = 0;

    	settings = jQuery.extend({
            position: "left",
            avatar: undefined,
            mainicon: "fa-rouble",
            label: "Puma SideBar",
            closeoutside: true,
            returnicon: "fa-arrow-left",
            returntext: "Return",
            movebody:true,
            items: []
        }, settings);

    	glbSettings = settings;

    	glbCallback = undefined;
    	glbCallback = callback;

    	if(psbExist == 1){
    		jQuery.ClosePumaSideBar(0);
    	}

    	var Pos = settings.position.toLowerCase();
    	if(Pos == "left")
    		Pos = "psbLeft";
    	else
    		Pos = "psbRight";

    	if(psbExist==0){
    		psbExist = 1;
	    	var PumaContent = "";

	    		PumaContent +=  '<div id="PumaSideBar" class="psbContainer '+ Pos +' psbThemeBlack" mouseover="1">';
	    		PumaContent +=   '<span class="fa fa-times psbClose"></span>';
				PumaContent +=  	'<div align="center" class="psbNoScroll">';

				if(settings.avatar != undefined)
					PumaContent +=  		'<img src="'+ settings.avatar +'" class="psbAv">';
				else
					PumaContent +=  		'<span class="psbMainIcon fa '+ settings.mainicon +' fa-5x"></span>';


				PumaContent +=  		'<span id="psbSubIcon" class="fa fa-laptop fa-5x"></span>';
				PumaContent +=  		'<span class="psbUserName">'+ settings.label +'</span>';
				PumaContent +=  	'</div>';
				PumaContent +=  	'<div class="psbSwiper-ContainerMain">';
				PumaContent +=  		'<div class="swiper-container psbSwiper-Container">';
				PumaContent +=  			'<div class="swiper-wrapper">';
				PumaContent +=  			'</div>';
				PumaContent +=  		'</div>';
				PumaContent +=      '</div>'
				PumaContent +=  '</div>';

			jQuery("body").append(PumaContent);

	    	var mySwiper = new Swiper('.swiper-container',{
	            mode: 'vertical',
	            loop: false,
	            freeMode: true,
	            freeModeFluid: true,
	            calculateHeight: true,
	            slidesPerView: 'auto',
	            visibilityFullFit: true,
	            mousewheelControl: true
	        });
						
	        glbSwiper = mySwiper;
    	}

    	var PumaSideBar = jQuery("#PumaSideBar");

		psbOpen = 1;

		if(settings.position == "left"){
			if(settings.movebody){
				isPushed = 1;
				jQuery("body").animate({
					left: "200px"
				},300, 'linear');
			}

			PumaSideBar.animate({
					left: "0px",
				},300, 'linear');
		}else{
			if(settings.movebody){
				isPushed = 1;
				jQuery("body").animate({
					right: "200px"
				},300, 'linear');
			}

			PumaSideBar.animate({
					right: "0px",
				},300, 'linear');
		}

 		clearTimeout(TempTimer);
 		TempTimer = setTimeout(function() {
 			jQuery("#PumaSideBar").attr("mouseover","0");
 		}, 300);

 		jQuery("#PumaSideBar").attr("mouseover");


    	// Load lvl1
    	Loadlvl1();


    	


        // Onetime Initialization
        if(psbInit == 0){
        	psbInit = 1;
	        
        	
		}//End Init
    }


// ================== Creates the first lvl of items
    	var TempTimer;

    	function Loadlvl1(){
    		glbSwiper.removeAllSlides();

    		for(i=0; i< glbSettings.items.length; i++){
    			var psbOption ="";

    			var fa     = glbSettings.items[i].fa;
    			var text   = glbSettings.items[i].text;
    			var childs = glbSettings.items[i].items; 
    			var link   = glbSettings.items[i].link;
				var target = glbSettings.items[i].target;
				var scroll = glbSettings.items[i].scroll;

    			if( fa == undefined)
    				fa = "";

    			if(text == undefined)
    				text = "Option";

    			if(childs != undefined && childs.length >0)
    				childs = childs.length;
    			else
    				childs = 0;

    			if(target !=undefined)
    				target = 'target="'+ target +'"';


    			if(link !=undefined)
    				psbOption += '<a class="psbLink" href="'+ link +'" ' + target + ">";

    			psbOption += '<div class="psbOption" fa="'+ fa +'" text="'+ text +'" childs ="'+ childs +'" lvl="1" array="'+ i +'" scroll="'+ scroll +'">';


				psbOption += 	'<span class="psbLabel"><span class="fa '+ fa +'"></span> '+ text +'</span>';

				if(childs>0)
					psbOption += 	'<span class="psbMorelbl"><span class="fa fa-angle-right"></span> </span>';
		
				psbOption += '</div>';

				if(link !=undefined)
    				psbOption += '</a>';

				var newSlide = glbSwiper.createSlide(psbOption);
                newSlide.append();
    		}

    		AjustMenu();
    		glbSwiper.reInit();
    		AnimateOptions();

    	}

    	// ================== Creates the first lvl of items
    	function Loadlvl2(Parent){
    		glbSwiper.removeAllSlides();

    		var SecondLine = glbSettings.items[Parent];

    		for(i=0; i< SecondLine.items.length; i++){
    			var psbOption ="";

    			var fa     = SecondLine.items[i].fa;
    			var text   = SecondLine.items[i].text;
    			var childs = SecondLine.items[i].items; 
    			var link   = SecondLine.items[i].link;
				var target = SecondLine.items[i].target;
				var scroll = SecondLine.items[i].scroll;

    			if( fa == undefined)
    				fa = "";

    			if(text == undefined)
    				text = "Option";

    			if(childs != undefined && childs.length >0)
    				childs = childs.length;
    			else
    				childs = 0;

    			if(target !=undefined)
    				target = 'target="'+ target +'"';


    			if(link !=undefined)
    				psbOption += '<a class="psbLink" href="'+ link +'" ' + target + ">";

    			psbOption += '<div class="psbOption" fa="'+ fa +'" text="'+ text +'" childs ="'+ childs +'" lvl="2" array="'+ Parent +'" subarray="'+ i +'" scroll="'+ scroll +'">';


				psbOption += 	'<span class="psbLabel"><span class="fa '+ fa +'"></span> '+ text +'</span>';

				if(childs>0)
					psbOption += 	'<span class="psbMorelbl"><span class="fa fa-angle-right"></span> </span>';
		
				psbOption += '</div>';

				if(link !=undefined)
    				psbOption += '</a>';

				var newSlide = glbSwiper.createSlide(psbOption);
                newSlide.append();
    		}


    		psbOption = '<div class="psbOption" lvl="0">';
			psbOption += '<span class="psbLabel"><span class="fa '+ glbSettings.returnicon +'"></span> '+ glbSettings.returntext +'</span>';
			var newSlide = glbSwiper.createSlide(psbOption);
                newSlide.prepend();

            AjustMenu();
    		glbSwiper.reInit();
    		AnimateOptions();

    	}

		// ================== Creates the first lvl of items
    	function Loadlvl3(Parent,Son){
    		glbSwiper.removeAllSlides();

    		var ThirdLine = glbSettings.items[Parent].items[Son];

    		for(i=0; i< ThirdLine.items.length; i++){
    			var psbOption ="";

    			var fa     = ThirdLine.items[i].fa;
    			var text   = ThirdLine.items[i].text;
    			var childs = ThirdLine.items[i].items; 
    			var link   = ThirdLine.items[i].link;
				var target = ThirdLine.items[i].target;
				var scroll = ThirdLine.items[i].scroll;

    			if( fa == undefined)
    				fa = "";

    			if(text == undefined)
    				text = "Option";

    			if(childs != undefined && childs.length >0)
    				childs = childs.length;
    			else
    				childs = 0;

    			if(target !=undefined)
    				target = 'target="'+ target +'"';


    			if(link !=undefined)
    				psbOption += '<a class="psbLink" href="'+ link +'" ' + target + ">";

    			psbOption += '<div class="psbOption" fa="'+ fa +'" text="'+ text +'" childs ="'+ childs +'" lvl="3" array="'+ Parent +'" subarray="'+ Son +'" subsubarray="'+ i +'" scroll="'+ scroll +'">';


				psbOption += 	'<span class="psbLabel"><span class="fa '+ fa +'"></span> '+ text +'</span>';

				if(childs>0)
					psbOption += 	'<span class="psbMorelbl"><span class="fa fa-angle-right"></span> </span>';
		
				psbOption += '</div>';

				if(link !=undefined)
    				psbOption += '</a>';

				var newSlide = glbSwiper.createSlide(psbOption);
                newSlide.append();
    		}


    		psbOption = '<div class="psbOption" lvl="0">';
			psbOption += '<span class="psbLabel"><span class="fa '+ glbSettings.returnicon +'"></span> '+ glbSettings.returntext +'</span>';
			var newSlide = glbSwiper.createSlide(psbOption);
                newSlide.prepend();

            AjustMenu();
    		glbSwiper.reInit();
    		AnimateOptions();

    	}


    	// ==================  Control the Animation
        var Timer;
        function AnimateOptions(){

            clearInterval(Timer);

            var Cuantos = jQuery(".psbOption").length;
            var Actual  = 0;

            Timer = setInterval(function(){

                if(Actual == Cuantos){
                    clearInterval(Timer);
                	jQuery(".psbMorelbl").addClass("animated fadeIn");
                }

                if(glbSettings.position == "left"){

	                if(jQuery(".psbOption").eq(Actual).parent().hasClass("swiper-slide"))
	                	jQuery(".psbOption").eq(Actual).parent().addClass("animated fadeInLeft fast");
	                else
	                	jQuery(".psbOption").eq(Actual).parent().parent().addClass("animated fadeInLeft fast");

                }else{
                	if(jQuery(".psbOption").eq(Actual).parent().hasClass("swiper-slide"))
	                	jQuery(".psbOption").eq(Actual).parent().addClass("animated fadeInRight fast");
	                else
	                	jQuery(".psbOption").eq(Actual).parent().parent().addClass("animated fadeInRight fast");
                }

                
                
                Actual +=1;
            },55);

            glbSwiper.swipeTo(0,10);

        }

        // Reload avatar
        function ReloadAvatar(){
        	jQuery("#psbSubIcon").hide();
        	
        	jQuery(".psbAv").fadeIn(250);
        	jQuery(".psbMainIcon").fadeIn(250);

        	jQuery(".psbUserName").text(glbSettings.label);
        }

        // Adapt the submenu options
        function AjustMenu(){

        	var WindowHeight = jQuery(window).height();
        	var SpaceNeeded = WindowHeight - 180;
        	jQuery(".psbSwiper-ContainerMain").css("height", SpaceNeeded+"px");
        	if(SpaceNeeded <0)
        		alert(SpaceNeeded);
        }

			jQuery(document).on("mouseenter","#PumaSideBar",function(){
        		jQuery(this).attr("mouseover","1");
        	});

        	jQuery(document).on("mouseover","#PumaSideBar",function(){
        		jQuery(this).attr("mouseover","1");
        	});

        	jQuery(document).on("mouseleave","#PumaSideBar",function(){
        		jQuery(this).attr("mouseover","0");
        	});

        	// Click or touch outside to close
        	var TempCheck;
        	jQuery(document).on("click",function(){

        		if(glbSettings == undefined)
        			return;

        		if(glbSettings.closeoutside){

	        		var MouseOver = jQuery("#PumaSideBar").attr("mouseover");

	        		if(MouseOver != 1){

	        			jQuery.ClosePumaSideBar(1);
	        			
	        			if(glbCallback!=undefined){

		        			if (typeof glbCallback == "function" && jQuery("#PumaSideBar").length>0 ) 
			                {   
			                    if(glbCallback)glbCallback(undefined,"Closing");
			                }
	        			}
	        		}

        		}

        	});

        	document.addEventListener('touchstart', function(e) {
			    
			    if(glbSettings.closeoutside){

				    // e.preventDefault();
				    var touch = e.touches[0];
				    
				    if(glbSettings.position=="left"){

					    if(touch.pageX>210){
					    	jQuery.ClosePumaSideBar(1);
					    	if(glbCallback!=undefined)
					    	if (typeof callback == "function" && jQuery("#PumaSideBar").length>0 ) 
			                {   
			                    if(glbCallback)glbCallback(undefined,"Closing");
			                }
					    }
				    }else{

				    	if(touch.pageX<108){
					    	jQuery.ClosePumaSideBar(1);
					    	if(glbCallback!=undefined)
					    	if (typeof callback == "function" && jQuery("#PumaSideBar").length>0 ) 
			                {   
			                    if(glbCallback)glbCallback(undefined,"Closing");
			                }
					    }
				    }

				    
			    }

			}, true);

		    // Click on a menuoption that contains childs
		    jQuery(document).on("click",".psbOption",function(){

		    	var fa          = jQuery(this).attr("fa");
				var text        = jQuery(this).attr("text");
				var childs      = jQuery(this).attr("childs");
				var lvl         = jQuery(this).attr("lvl");
				var array       = jQuery(this).attr("array");
				var subarray    = jQuery(this).attr("subarray")
				var subsubarray = jQuery(this).attr("subsubarray")
				var scroll      = jQuery(this).attr("scroll")
				// jQuery('#lblTitle').animatescroll();

				if(scroll != undefined && scroll != "undefined" && lvl !="0" ){
					jQuery('#' + scroll).animatescroll();
				}

				if(childs>0){

					jQuery("#psbSubIcon").removeClass().addClass("fa fa-5x "+ fa);
					jQuery(".psbUserName").text(text);

					jQuery(".psbAv").hide();
					jQuery(".psbMainIcon").hide();
					jQuery("#psbSubIcon").fadeIn(250);
				}



				if(lvl == "0"){
					Loadlvl1();
					ReloadAvatar();
					AjustMenu();
				}else if(lvl == "1" && childs > 0){
					Loadlvl2(array);
				}else if(lvl == "2" && childs >0){
					Loadlvl3(array, subarray);
				}else if(lvl != "0"){
	            	if (typeof glbCallback == "function") 
	                {   
	                	var OptionSelected;
	                	if(lvl == "1"){
	                		OptionSelected = text;
	                	}else if (lvl == "2"){
	                		OptionSelected = glbSettings.items[array].text + "."+glbSettings.items[array].items[subarray].text;
	                	}else if (lvl == "3"){
	                		OptionSelected = glbSettings.items[array].text + "."+glbSettings.items[array].items[subarray].text + "." + glbSettings.items[array].items[subarray].items[subsubarray].text;
	                	}

	                    if(glbCallback)glbCallback(OptionSelected,undefined);
	                }
				}

		    });
		
	        		
			jQuery(window).on("resize",".psbClose",function(){
				AjustMenu();
				glbSwiper.reInit();
			})

		    // Close menu on Cross click
			jQuery(document).on("click",".psbClose",function(){

				jQuery.ClosePumaSideBar(1);
				if (typeof glbCallback == "function") 
	                {   
	                    if(glbCallback)glbCallback(undefined,"Closing");
	                }

			});

})(jQuery);

