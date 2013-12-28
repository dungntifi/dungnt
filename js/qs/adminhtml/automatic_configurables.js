/**
 * Automatic Configurables Extension
 *
 * @category   QS
 * @package    QS_Quickconfigurables
 * @author     Quart-soft Magento Team <magento@quart-soft.com> 
 * @copyright  Copyright (c) 2010 Quart-soft Ltd http://quart-soft.com
 */

Product.Configurable.prototype.revalidateRowAfterReload = function(grid, row) {
	var checkbox = $(row).down('.checkbox');
	if (checkbox) {
		if (!checkbox.checked) {
			if (!this.checkAttributes(checkbox.linkAttributes)) {
				$(row).addClassName('invalid');
				checkbox.disable();
			} else {
				$(row).removeClassName('invalid');
				checkbox.enable();
			}
		} else {
			if (!this.checkAttributes(checkbox.linkAttributes)) {
				$(row).addClassName('invalid');
				checkbox.checked = false;
				this.updateValues();		
			} else {
				$(row).removeClassName('invalid');
				checkbox.enable();
				this.updateValues();		
			}			
		}
	}
};

Product.Configurable.prototype.quickCombinations = function() {
	this.initializeAdvicesForSimpleForm();
	$(this.idPrefix + 'combination_form').removeClassName('ignore-validate');
	var validationResult = $(this.idPrefix + 'combination_form').select('input','select','textarea').collect(
	   function(elm) {
			return Validation.validate(elm,{useTitle : false, onElementValidate : function(){}});
	   }
	).all();
	$(this.idPrefix + 'combination_form').addClassName('ignore-validate');

	if (!validationResult) {
		return;
	}
	
	new Effect.ScrollTo('super_product_links');

	var params = Form.serializeElements(
	   $(this.idPrefix + 'combination_form').select('input','select','textarea'),
	   true
	);
	params.form_key = FORM_KEY;
	params.attribute_values = this.attributes;
	$('messages').update();
	
	new Ajax.Request(this.createCombinationsUrl, {
		   parameters: params,
		   method:'post',
		   area: $(this.idPrefix + 'combination_form'),
		   onSuccess: this.startSimpleProductCreation.bind(this)
	});
};
	
Product.Configurable.prototype.startSimpleProductCreation = function(transport) {
	this.combinationsResponse = transport.responseText.evalJSON();
	this.simpleIterator = 0;
	this.createSimpleProduct().bind(this);
};

Product.Configurable.prototype.createSimpleProduct = function() {
	if(this.combinationsResponse.combinations.length == this.simpleIterator){
		return;
	}
	var params = this.combinationsResponse.combinations[this.simpleIterator];
	params.product_id = this.combinationsResponse.product_id;
	
	new Ajax.Request(this.createSimpleProductUrl, {
	   parameters: params,
	   method:'post',
	   onSuccess: this.quickCombinationComplete.bind(this)
	}).bind(this);
};
	
Product.Configurable.prototype.quickCombinationComplete = function(transport) {
	var result = transport.responseText.evalJSON();
	if (result.error) {
		if (result.error.fields) {
			$(this.idPrefix + 'simple_form').removeClassName(
					'ignore-validate');
			$H(result.error.fields)
					.each(
							function(pair) {
								$('simple_product_' + pair.key).value = pair.value;
								$('simple_product_' + pair.key + '_autogenerate').checked = false;
								toggleValueElements(
										$('simple_product_' + pair.key + '_autogenerate'),
										$('simple_product_' + pair.key + '_autogenerate').parentNode);
								Validation.ajaxError(
										$('simple_product_' + pair.key),
										result.error.message);
							});
			$(this.idPrefix + 'simple_form')
					.addClassName('ignore-validate');
		} else {
			if (result.error.message) {
				alert(result.error.message);
			} else {
				alert(result.error);
			}
		}
		this.simpleIterator++;
		this.createSimpleProduct().bind(this);
		return;
	} else if (result.messages) {
		$('messages').update(result.messages);
	}

	result.attributes.each( function(attribute) {
				var attr = this.getAttributeById(attribute.attribute_id);
				if (!this.getValueByIndex(attr, attribute.value_index)
						&& result.pricing
						&& result.pricing[attr.attribute_code]) {

					attribute.is_percent = result.pricing[attr.attribute_code].is_percent;
					attribute.pricing_value = (result.pricing[attr.attribute_code].value == null ? ''
							: result.pricing[attr.attribute_code].value);
				}
			}.bind(this));

	this.attributes.each( function(attribute) {
		if ($('simple_product_' + attribute.attribute_code)) {
			$('simple_product_' + attribute.attribute_code).value = '';
		}
	}.bind(this));

	result.product_ids.each( function(product_id) {
		this.links.set(product_id, result.attributes);
	}.bind(this));
	
	//this.links.set(result.product_id, result.attributes);
	this.updateGrid();
	this.updateValues();		
	this.grid.reload();
	this.grid.rows.each( function(row) {
		this.revalidateRowAfterReload(this.grid, row);
	}.bind(this));
	this.updateValues();		
	
	// Check if one more product should be created
	this.simpleIterator++;
	this.createSimpleProduct().bind(this);
};

	 