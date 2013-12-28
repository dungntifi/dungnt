
	// MAIN FUNCTION - if ajaxhulpunchEnabled is found - it will automatically run this
	function runPunch(){
	    
	    // Check's what is enabled and sets all of the objects and then returns them
		if($('ajaxholepunchEnabled') != undefined){
			checkEnabled('/warp/warp/hole/enabled');
		}
	    
	}
        
    function runCart(cart, b){
        $$(cart)[0].update('My Cart ('+b+')');
    }
    
    function runWelcome(welcome, b){
           $$(welcome)[0].update(b);
    }
    
    function runLogOut(loginlink, logoutlink, logoutcss){
        $$(logoutcss)[0].update('Log Out');
        $$('a[href$="'+loginlink+'"]')[0].setAttribute('href', logoutlink);
    }
        
    function runCompareTotal(total){
        $$('span:contains("Compare Products")')[0].update('Compare Products <small>('+total+')</small>');
    }
    
    function runCompareNotEmpty(clearUrl, items, compareUrl){
        $$('.block-compare .empty')[0].update().insert({before: '<ol id="compare-items"></ol>'});;
        for(var key in items){
            if(items.hasOwnProperty(key)){
                $('compare-items').insert('<li class="item odd"><input class="compare-item-id" type="hidden" value="16"><a class="btn-remove" onclick="return confirm(\'Are you sure you would like to remove this item from the compare products?\');" title="Remove This Item" href="'+items[key].removeUrl+'">Remove This Item</a><p class="product-name"><a href="'+items[key].productUrl+'">'+items[key].requestPath+'</a></p></li>');
            }
        }
        $$('.block-compare .empty')[0].insert({after: 
        	'<div class="actions">'+
        	'<a onclick="return confirm(\'Are you sure you would like to remove all products from your comparison?\');" href="'+clearUrl+'">Clear All</a>'+
        	'<button class="button" onclick="popWin(\''+compareUrl+'\',\'compare\',\'top:0,left:0,width=820,height=600,resizable=yes,scrollbars=yes\')" title="Compare" type="button">'+
        	'<span><span>Compare</span></span></button></div>'
        	});
    }
    
    function runCartSidebar(cart){
    	$$('div.block-cart p.empty')[0].update().insert(
    			'<div class="summary"><p class="amount">There is <a href="'+cart.cartUrl+'">'+cart.cartQty+' item(s)</a> in your cart.</p>'+
    			'<p class="subtotal">Cart Subtotal: $'+cart.cartSubtotal+'</p>'+
    			'</div>'+
    			'<div class="actions">'+
    			'<button class="button" onclick="setLocation(\'checkout/onepage/\')" title="checkout" type="button">'+
    				'<span><span>Checkout</span></span>'+
    			'</button>'+
    			'</div>'
    		);
    }
    
    function checkEnabled(apiUrl){
    	
    	// First we need to check if local storage is supported
    	if(supports_html5_storage() == true){
	        var test = new Ajax.Request(apiUrl, {
	        		method: 'get',
	        		onSuccess:function(transport){
	                clearEnabled();
	                setEnabled(transport.responseText.evalJSON());                
	                runEnabled();
	        }});
    	}
    }
    
    // CLEARS OUT ALL LOCAL STORAGE
    function clearEnabled(){
        localStorage.clear();
    }
    
    // SETS THE LOCAL STORAGE
    function setEnabled(b){
        localStorage.setItem('compareEnabled',b.compare);
        localStorage.setItem('cartEnabled',b.cart);
        localStorage.setItem('pollEnabled',b.poll);
        localStorage.setItem('topLinksEnabled',b.toplinks);
        localStorage.setItem('cartTotal',b.toplinkshp.cartTotal);
        localStorage.setItem('carthp', JSON.stringify(b.carthp));
        localStorage.setItem('welcome', b.toplinkshp.welcome);
        localStorage.setItem('loggedin',b.toplinkshp.loggedin);
        localStorage.setItem('topLinksCss',JSON.stringify(b.toplinkshp.topLinksCss));
        localStorage.setItem('comparehp',JSON.stringify(b.comparehp));
    }
    
    function runEnabled(){
    	
        // this will run each enabled
        if(localStorage.getItem('topLinksEnabled') == 1){
            
            var tlc = JSON.parse(localStorage.getItem('topLinksCss'));
            
            if(localStorage.getItem('loggedin') == 1){
            	// SETS THE LOGOUT LINK AND THE LOGOUT TEXT
                runLogOut(tlc.loginlink, tlc.logoutlink, tlc.logoutcss); // fixed
            }
            // SETS THE CUSTOM WELCOME MESSAGE FOR YOUR STORE
            runWelcome(tlc.welcomecss, localStorage.getItem('welcome')); // fixed
            
            if(localStorage.getItem('cartTotal') >= 1){
            	// IF ITEMS IN CART, ADDS THOSE TO THE TOP LINK
                runCart(tlc.cartcss, localStorage.getItem('cartTotal')); // fixed and cleaned up
            }
        }
        
        if(localStorage.getItem('compareEnabled') == 1){
            
            var com = JSON.parse(localStorage.getItem('comparehp'));
            if(com.compareTotal >= 1){
                runCompareTotal(com.compareTotal);
                runCompareNotEmpty(com.clearUrl,com.products, com.compareUrl);
            }
            
        }
        
        if(localStorage.getItem('cartEnabled') == 1){
            var carthp = JSON.parse(localStorage.getItem('carthp'));
            if(carthp.cartQty >= 1){
                runCartSidebar(carthp); // fixed
            }
        }
    }
    
    // THIS CHECKS IF HTML5 LOCALSTORAGE IS SUPPORTED
    function supports_html5_storage() {
      try {
        return true;
      } catch (e) {
        return false;
      }
    }