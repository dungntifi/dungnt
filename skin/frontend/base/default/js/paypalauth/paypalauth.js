function logOutFromPaypal(){
    var Pixel = new Element('img', {src:"https://www.paypal.com/webapps/auth/logout", width:"1px", height:"1px"});
    url = $('paypalout').readAttribute('href');
    setTimeout(function(){ document.body.appendChild(Pixel);location.href = url},2000);
    return false;
}

function payPalPopUp(url){
    if (!url) {
        url = $('topPayPalIn').readAttribute('href');
    }
    mywindow = window.open (url, "_PPIdentityWindow_", "location=1, status=0, scrollbars=0, width=400, height=550");
    return false;
}
$j = jQuery;
var IWD=IWD||{};
;
IWD.Cart = {
		
		config: null,
		setLocationOriginal: null,
		removeShoppingCartUrl:null,
		
		init: function(){
		
			this.config = $j.parseJSON(this.config);
			$j('.add-before-paypal').click(function(e){
				e.preventDefault();
				var productForm = new VarienForm('product_addtocart_form');
				
				if( !productForm.validator.validate()){
					
					return;
				}	
					
				if (!IWD.Cart.isPaypalExpress($j(this).attr('href'))){
					productForm.submit();
					return;
				};		
				
				
				IWD.Cart.showDialog();
					
				var formData = $j('#product_addtocart_form').serializeArray();
				formData.push({name: "ajax", value: true});
					
				$j.ajax({
						 url: $j('#product_addtocart_form').prop('action'),
						 data: formData,
						 type:'post',
						 success: function(){
							 IWD.Cart.hideDialog();
							PAYPAL.apps.Checkout.startFlow($j('.add-before-paypal').attr('href'));
							 
						 },
						 async: false
				});
				
			});
		},

		isPaypalExpress: function(url){
			var re1='((?:[a-z][a-z]+))', re2='.*?', re3='(express)';
			var p = new RegExp(re1+re2+re3,["i"]);
			var m = p.exec(url);
			if (m != null){
		          return true;
			}
			return false;
		},
		
	
		showDialog: function(){				
			$j('.m-dialog').show();
		},
		
		hideDialog: function(){
			$j('.m-dialog').hide();
		},
};

$j(document).ready(function(){
	IWD.Cart.init();
});

