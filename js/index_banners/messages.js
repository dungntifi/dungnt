var __LANG__ = "en";
var a = location.pathname.split("/"); 
if ( a.length > 2 && a[1] == "fr" ) 
{ 
	__LANG__ = "fr"; 
}

/** The Messages object constructor. */
Messages = function () { };
Messages.getMessageEn = function(section) 
{
	switch(section)
	{
		case 'shoppingBag_specifyEmail':
			return 'Please specify email...';
		case 'shoppingBag_specifyPass':
			return 'Please specify password ...';
		case 'productPreview_selectSize':
			return '<div style="color:red">Please select size</div>';
		case 'productPreview_sizeSoldout':
			return '<div style="color:red">Sorry this product is sold out</div>';
		case 'productPreview_addMoreItem':
			return 'ADD ONE MORE TO BAG';
		case 'productPreview_oneItemAddedToBag':
			return 'Item added to shopping bag.';
		case 'productPreview_greaterThanOneItemAddedToBag':
			return 'Items added to shopping bag.';
		
	}
};
Messages.getMessageFr = function(section) 
{
	switch(section)
	{
		case 'shoppingBag_specifyEmail':
			return 'Veuillez entrer votre courriel...';
		case 'shoppingBag_specifyPass':
			return 'Veuillez entrer votre mot de passe ...';
		case 'productPreview_selectSize':
			return '<div style="color:red">Veuillez Choisir Taille </div>';
		case 'productPreview_sizeSoldout':
			return '<div style="color:red">Veuillez nous excuser ce produit est en rupture de stock</div>';
		case 'productPreview_addMoreItem':
			return 'Ajouter un autre produit au panier';
		case 'productPreview_oneItemAddedToBag':
			return 'Produit a &eacute;t&eacute; ajout&eacute;.';
		case 'productPreview_greaterThanOneItemAddedToBag':
			return 'PRODUITS AJOUTÉS AU PANIER.';
	}
};


Messages.getMessage = function(section) 
{
	switch(__LANG__)
	{
		case 'en':
			return Messages.getMessageEn(section)
			break;
		case 'fr':
			return Messages.getMessageFr(section)
			break;
	}
};

//alert(Messages.getMessage('1','shoppingBag_specifyPass'))
