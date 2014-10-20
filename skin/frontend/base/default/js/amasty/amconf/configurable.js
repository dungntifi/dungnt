// extension Code

AmConfigurableData = Class.create();
AmConfigurableData.prototype = 
{
    textNotAvailable : "",
    
    mediaUrlMain : "",
    
    currentIsMain : "",
    
    optionProducts : null,
    
    optionDefault : new Array(),
    
    oneAttributeReload : false,

    isResetButton : false,
    
    initialize : function(optionProducts)
    {
        this.optionProducts = optionProducts;
    },
    //special for simple price
    reloadOptions : function()
    {
        if ('undefined' != typeof(spConfig) && spConfig.settings)
        {
            spConfig.settings.each(function(select){
                if (select.enable) {
                    spConfig.reloadOptionLabels(select);
                }    
            });    
        }
    },
     
    hasKey : function(key)
    {
        return ('undefined' != typeof(this.optionProducts[key]));
    },
    
    getData : function(key, param)
    {
        if (this.hasKey(key) && 'undefined' != typeof(this.optionProducts[key][param]))
        {
            return this.optionProducts[key][param];
        }
        return false;
    },
    
    saveDefault : function(param, data)
    {
        this.optionDefault['set'] = true;
        this.optionDefault[param] = data;
    },
    
    getDefault : function(param)
    {
        if ('undefined' != typeof(this.optionDefault[param]))
        {
            return this.optionDefault[param];
        }
        return false;
    }
}
// extension Code End

//class definition
if (typeof Product == 'undefined') {
    var Product = {};
}

//Define ConfigSimple, to prevent same methods overriding in configurable.js and configurableList.js

/**************************** CONFIGURABLE PRODUCT **************************/
Product.ConfigSingle = Class.create();
Product.ConfigSingle.prototype = {
    initialize: function(config){
        this.config     = config;
        this.taxConfig  = this.config.taxConfig;
        if (config.containerId) {
            this.settings   = $$('#' + config.containerId + ' ' + '.super-attribute-select');
        } else {
            this.settings   = $$('.super-attribute-select');
        }
        this.state      = new Hash();
        this.priceTemplate = new Template(this.config.template);
        this.prices     = config.prices;

        // Set default values from config
        if (config.defaultValues) {
            this.values = config.defaultValues;
        }

        // Overwrite defaults by url
        var separatorIndex = window.location.href.indexOf('#');
        if (separatorIndex != -1) {
            var paramsStr = window.location.href.substr(separatorIndex+1);
            var urlValues = paramsStr.toQueryParams();
            if (!this.values) {
                this.values = {};
            }
            for (var i in urlValues) {
                this.values[i] = urlValues[i];
            }
        }

        // Overwrite defaults by inputs values if needed
        if (config.inputsInitialized) {
            this.values = {};
            this.settings.each(function(element) {
                if (element.value) {
                    var attributeId = element.id.replace(/[a-z]*/, '');
                    this.values[attributeId] = element.value;
                }
            }.bind(this));
        }

        // Put events to check select reloads
        this.settings.each(function(element){
            Event.observe(element, 'change', this.configure.bind(this))
        }.bind(this));

        // fill state
        this.settings.each(function(element){
            var attributeId = element.id.replace(/[a-z]*/, '');
            if(attributeId && this.config.attributes[attributeId]) {
                element.config = this.config.attributes[attributeId];
                element.attributeId = attributeId;
                this.state[attributeId] = false;
            }
        }.bind(this))

        // Init settings dropdown
        var childSettings = [];
        for(var i=this.settings.length-1;i>=0;i--){
            var prevSetting = this.settings[i-1] ? this.settings[i-1] : false;
            var nextSetting = this.settings[i+1] ? this.settings[i+1] : false;
            if (i == 0){
                this.fillSelect(this.settings[i])
            } else {
                this.settings[i].disabled = true;
            }
            $(this.settings[i]).childSettings = childSettings.clone();
            $(this.settings[i]).prevSetting   = prevSetting;
            $(this.settings[i]).nextSetting   = nextSetting;
            childSettings.push(this.settings[i]);
        }

        // Set values to inputs
        this.configureForValues();
        document.observe("dom:loaded", this.configureForValues.bind(this));
    },

    configureForValues: function () {
        if (this.values) {
            this.settings.each(function(element){
                var attributeId = element.attributeId;
                element.value = (typeof(this.values[attributeId]) == 'undefined')? '' : this.values[attributeId];
                this.configureElement(element);
            }.bind(this));
        }
    },

    configure: function(event){
        var element = Event.element(event);
        this.configureElement(element);
    },

    configureElement : function(element) {
        this.reloadOptionLabels(element);
        if(element.value){
            this.state[element.config.id] = element.value;
            if(element.nextSetting){
                element.nextSetting.disabled = false;
                this.fillSelect(element.nextSetting);
                this.resetChildren(element.nextSetting);
            }
        }
        else {
            this.resetChildren(element);
        }
        this.reloadPrice();
    },

    reloadOptionLabels: function(element){
        var selectedPrice;
        if(element.options[element.selectedIndex].config && !this.config.stablePrices){
            selectedPrice = parseFloat(element.options[element.selectedIndex].config.price)
        }
        else{
            selectedPrice = 0;
        }
        for(var i=0;i<element.options.length;i++){
            if(element.options[i].config){
                element.options[i].text = this.getOptionLabel(element.options[i].config, element.options[i].config.price-selectedPrice);
            }
        }
    },

    resetChildren : function(element){
        if(element.childSettings) {
            for(var i=0;i<element.childSettings.length;i++){
                element.childSettings[i].selectedIndex = 0;
                element.childSettings[i].disabled = true;
                if(element.config){
                    this.state[element.config.id] = false;
                }
            }
        }
    },

    fillSelect: function(element){
        var attributeId = element.id.replace(/[a-z]*/, '');
        var options = this.getAttributeOptions(attributeId);
        this.clearSelect(element);
        element.options[0] = new Option('', '');
        element.options[0].innerHTML = this.config.chooseText;

        var prevConfig = false;
        if(element.prevSetting){
            prevConfig = element.prevSetting.options[element.prevSetting.selectedIndex];
        }

        if(options) {
            var index = 1;
            for(var i=0;i<options.length;i++){
                var allowedProducts = [];
                if(prevConfig) {
                    for(var j=0;j<options[i].products.length;j++){
                        if(prevConfig.config.allowedProducts
                            && prevConfig.config.allowedProducts.indexOf(options[i].products[j])>-1){
                            allowedProducts.push(options[i].products[j]);
                        }
                    }
                } else {
                    allowedProducts = options[i].products.clone();
                }

                if(allowedProducts.size()>0){
                    options[i].allowedProducts = allowedProducts;
                    element.options[index] = new Option(this.getOptionLabel(options[i], options[i].price), options[i].id);
                    if (typeof options[i].price != 'undefined') {
                        element.options[index].setAttribute('price', options[i].price);
                    }
                    element.options[index].config = options[i];
                    index++;
                }
            }
        }
    },

    getOptionLabel: function(option, price){
        var price = parseFloat(price);
        if (this.taxConfig.includeTax) {
            var tax = price / (100 + this.taxConfig.defaultTax) * this.taxConfig.defaultTax;
            var excl = price - tax;
            var incl = excl*(1+(this.taxConfig.currentTax/100));
        } else {
            var tax = price * (this.taxConfig.currentTax / 100);
            var excl = price;
            var incl = excl + tax;
        }

        if (this.taxConfig.showIncludeTax || this.taxConfig.showBothPrices) {
            price = incl;
        } else {
            price = excl;
        }

        var str = option.label;
        if(price){
            if (this.taxConfig.showBothPrices) {
                str+= ' ' + this.formatPrice(excl, true) + ' (' + this.formatPrice(price, true) + ' ' + this.taxConfig.inclTaxTitle + ')';
            } else {
                str+= ' ' + this.formatPrice(price, true);
            }
        }
        return str;
    },

    formatPrice: function(price, showSign){
        var str = '';
        price = parseFloat(price);
        if(showSign){
            if(price<0){
                str+= '-';
                price = -price;
            }
            else{
                str+= '+';
            }
        }

        var roundedPrice = (Math.round(price*100)/100).toString();

        if (this.prices && this.prices[roundedPrice]) {
            str+= this.prices[roundedPrice];
        }
        else {
            str+= this.priceTemplate.evaluate({price:price.toFixed(2)});
        }
        return str;
    },

    clearSelect: function(element){
        for(var i=element.options.length-1;i>=0;i--){
            element.remove(i);
        }
    },

    getAttributeOptions: function(attributeId){
        if(this.config.attributes[attributeId]){
            return this.config.attributes[attributeId].options;
        }
    },

    reloadPrice: function(){
        if (this.config.disablePriceReload) {
            return;
        }
        var price    = 0;
        var oldPrice = 0;
        for(var i=this.settings.length-1;i>=0;i--){
            var selected = this.settings[i].options[this.settings[i].selectedIndex];
            if(selected.config){
                price    += parseFloat(selected.config.price);
                oldPrice += parseFloat(selected.config.oldPrice);
            }
        }

        optionsPrice.changePrice('config', {'price': price, 'oldPrice': oldPrice});
        optionsPrice.reload();

        return price;

        if($('product-price-'+this.config.productId)){
            $('product-price-'+this.config.productId).innerHTML = price;
        }
        this.reloadOldPrice();
    },

    reloadOldPrice: function(){
        if (this.config.disablePriceReload) {
            return;
        }
        if ($('old-price-'+this.config.productId)) {

            var price = parseFloat(this.config.oldPrice);
            for(var i=this.settings.length-1;i>=0;i--){
                var selected = this.settings[i].options[this.settings[i].selectedIndex];
                if(selected.config){
                    price+= parseFloat(selected.config.price);
                }
            }
            if (price < 0)
                price = 0;
            price = this.formatPrice(price);

            if($('old-price-'+this.config.productId)){
                $('old-price-'+this.config.productId).innerHTML = price;
            }

        }
    }
}

//class methods redeclare for popup block
Product.ConfigSingle.prototype.resetChildren = function(element){
    if(element.childSettings) {
        for(var i=0;i<element.childSettings.length;i++){
            element.childSettings[i].selectedIndex = 0;
            element.childSettings[i].disabled = true;
            if(element.config){
                this.state[element.config.id] = false;
            }
        }
    }
    // extension Code Begin
    this.processEmpty();
    // extension Code End
}

Product.ConfigSingle.prototype.fillSelect = function(element){
    var attributeId = element.id.replace(/[a-z]*/, '');
    var options = this.getAttributeOptions(attributeId);
    this.clearSelect(element);
    element.options[0] = new Option(this.config.chooseText, '');

    var prevConfig = false;
    if(element.prevSetting){
        prevConfig = element.prevSetting.options[element.prevSetting.selectedIndex];
    }

    if(options) {
        if ($('amconf-images-' + attributeId))
            {
                $('amconf-images-' + attributeId).parentNode.removeChild($('amconf-images-' + attributeId));
            }
            
       if (this.config.attributes[attributeId].use_image)
        {
            holder = element.up('.block-select').next('.block-color');
            holderDiv = document.createElement('div');
            holderDiv = $(holderDiv); // fix for IE
            holderDiv.addClassName('amconf-images-container');
            holderDiv.id = 'amconf-images-' + attributeId;
            holder.appendChild(holderDiv, element);
        }
        // extension Code End
        
        var sizeOptions = [];
        this.settings.each(function(select, ch) {
            if(select.config.code == 'size') {
                var sizeAttrId = parseInt(select.config.id);
                sizeOptions = select.config.options;
            }
        });
        
        var index = 1;      
        for(var i=0;i<options.length;i++){
            var allowedProducts = [];
            if(prevConfig) {
                for(var j=0;j<options[i].products.length;j++){
                    if(prevConfig.config.allowedProducts
                        && prevConfig.config.allowedProducts.indexOf(options[i].products[j])>-1){
                        allowedProducts.push(options[i].products[j]);
                    }
                }
            } else {
                allowedProducts = options[i].products.clone();
            }

            if(allowedProducts.size()>0)
            {
                // extension Code
                if (this.config.attributes[attributeId].use_image)
                {
                    var imgContainer = document.createElement('div');
                    imgContainer = $(imgContainer); // fix for IE
                    imgContainer.addClassName('amconf-image-container');
                    imgContainer.id = 'amconf-images-container-' + attributeId;
                    imgContainer.style.float = 'left';
                    holderDiv.appendChild(imgContainer);
            
                    var image = document.createElement('img');
                    image = $(image); // fix for IE
                    image.id = 'amconf-image-' + options[i].id;

                    var key = '';
                    if(typeof sizeOptions != 'undefined') {
                        for(var k=0;k<allowedProducts.length;k++){
                            for (var l=0;l<sizeOptions.length;l++){
                                if(sizeOptions[l].products[0] == allowedProducts[k] && confDataSingle.getData(options[i].id + ',' + sizeOptions[l].id, 'media_url')) {
                                    key = options[i].id + ',' + sizeOptions[l].id;
                                    break;
                                }
                            }
                            if(key.length) {
                                break;
                            }
                        }
                    }

                    image.src = confDataSingle.optionProducts[key].thumbnail;   
                    image.style.width = '59px';
                    image.addClassName('amconf-image');
                    image.alt = options[i].label;
                    image.title = options[i].label;
                    
                    if(showAttributeTitle != 0) image.style.marginBottom = '0px';
                    else image.style.marginBottom = '7px';
                    
                    image.observe('click', this.configureImage.bind(this));
                    
                    if('undefined' != typeof(buble)){
                         image.observe('mouseover', buble.showToolTip);
                         image.observe('mouseout', buble.hideToolTip);               
                    }
                    
                    imgContainer.appendChild(image);
                    
                    if(showAttributeTitle && showAttributeTitle != 0){ 
                        var amImgTitle = document.createElement('div');
                        amImgTitle = $(amImgTitle); // fix for IE
                        amImgTitle.addClassName('amconf-image-title');
                        amImgTitle.id = 'amconf-images-title-' + options[i].id;
                        amImgTitle.setStyle({
                            fontWeight : 600,
                            textAlign : 'center'
                        });
                        amImgTitle.innerHTML = options[i].label;  
                        imgContainer.appendChild(amImgTitle);     
                    }
                    image.onload = function(){
                        var optId = this.id.replace(/[a-z-]*/, '');
                        var maxW = this.getWidth();
                        if(optId) {
                            var title = $('amconf-images-title-' + optId);
                            if(title && title.getWidth() && title.getWidth() > maxW) {
                                maxW = title.getWidth();
                            }
                                
                        }
                        if(this.parentNode){
                            this.parentNode.style.width =   maxW + 'px'; 
                        }
                        if(this.parentNode.childElements()[1]){
                            this.parentNode.childElements()[1].style.width =   maxW + 'px'; 
                        }
                    };  
                }
                // extension Code End
                
                options[i].allowedProducts = allowedProducts;
                element.options[index] = new Option(this.getOptionLabel(options[i], options[i].price), options[i].id);    
                element.options[index].config = options[i];
                index++;
            }
        }
        if(this.config.attributes[attributeId].use_image) {
            var lastContainer = document.createElement('div');
            lastContainer = $(lastContainer); // fix for IE
            lastContainer.setStyle({clear : 'both'});
            holderDiv.appendChild(lastContainer);    
        }        
    }
}

Product.ConfigSingle.prototype.configureElement = function(element) 
{
    // extension Code
    optionId = element.value;
    if ($('amconf-image-' + optionId))
    {
        this.selectImage($('amconf-image-' + optionId));
    } else 
    {
        attributeId = element.id.replace(/[a-z-]*/, '');
        if ($('amconf-images-' + attributeId))
        {
        $('amconf-images-' + attributeId).childElements().each(function(child){
             if(child.childElements()[0])
                child.childElements()[0].removeClassName('amconf-image-selected');
        });
        }
    }
    // extension Code End
    
    this.reloadOptionLabels(element);
    if(element.value){
        this.state[element.config.id] = element.value;
        if(element.nextSetting){
            element.nextSetting.disabled = false;
            this.fillSelect(element.nextSetting);
            this.resetChildren(element.nextSetting);
        }
    }
    else {
        // extension Code
        if(element.childSettings) {
            for(var i=0;i<element.childSettings.length;i++){
                attributeId = element.childSettings[i].id.replace(/[a-z-]*/, '');
                if ($('amconf-images-' + attributeId))
                {
                    $('amconf-images-' + attributeId).parentNode.removeChild($('amconf-images-' + attributeId));
                }
            }
        }
        // extension Code End
        
        this.resetChildren(element);
        
        // extension Code
        if (this.settings[0].hasClassName('no-display'))
        {
            this.processEmpty();
        }
        // extension Code End
    }
    
    // extension Code
    var key = '';
    this.settings.each(function(select, ch){
        // will check if we need to reload product information when the first attribute selected
        if (parseInt(select.value))
        {
            key += select.value + ',';   
        }
    });
    if (typeof confDataSingle != 'undefined') {
        confDataSingle.isResetButton = false;    
    }
    key = key.substr(0, key.length - 1);
    this.updateData(key);
    
    if (typeof confDataSingle != 'undefined' && confDataSingle.useSimplePrice == "1")
    {
        // replace price values with the selected simple product price
        this.reloadSimplePrice(key);
    }
    else
    {
        // default behaviour
        this.reloadPrice();
    }
    
    // for compatibility with custom stock status extension:
    if ('undefined' != typeof(stStatus) && 'function' == typeof(stStatus.onConfigure))
    {
    var key = '';
        this.settings.each(function(select, ch){
                if (parseInt(select.value) || (!select.value && (!select.options[1] || !select.options[1].value))){
                key += select.value + ',';   
            }
        else {
             key += select.options[1].value + ','; 
        }
        });
    key = key.substr(0, key.length - 1);
        stStatus.onConfigure(key, this.settings);
    }
    //Amasty code for Automatically select attributes that have one single value
    if(('undefined' != typeof(amConfAutoSelectAttribute) && amConfAutoSelectAttribute) ||('undefined' != typeof(amStAutoSelectAttribute) && amStAutoSelectAttribute)){
        var nextSet = element.nextSetting;
        if(nextSet && nextSet.options.length == 2 && !nextSet.options[1].selected && element && !element.options[0].selected){
            nextSet.options[1].selected = true;
            this.configureElement(nextSet);
        } 
    }
    if('undefined' != typeof(preorderState))
        preorderState.update()


    var label = "";
    element.config.options.each(function(option){
        if(option.id == element.value) label = option.label;
    });
    if(label) label = " - " + label;
    var parent = element.parentNode.parentNode.previousElementSibling;
    if( typeof(parent) != 'undefined' && parent.nodeName == "DT" && (conteiner = parent.select("label")[0])) {
        if( tmp = conteiner.select('span.amconf-label')[0]){
            tmp.innerHTML = label;
        }
        else{
            var tmp = document.createElement('span');
            tmp.addClassName('amconf-label');
            conteiner.appendChild(tmp);
            tmp.innerHTML = label;
        }           
    }
    // extension Code End
}

Product.ConfigSingle.prototype.configureForValues =  function () {
        if (this.values) {
            this.settings.each(function(element){
                var attributeId = element.attributeId;
                element.value = (typeof(this.values[attributeId]) == 'undefined')? '' : this.values[attributeId];
                this.configureElement(element);
            }.bind(this));
        }
        //Amasty code for Automatically select attributes that have one single value
        if(('undefined' != typeof(amConfAutoSelectAttribute) && amConfAutoSelectAttribute) ||('undefined' != typeof(amStAutoSelectAttribute) && amStAutoSelectAttribute)){
            var select  = this.settings[0];
            if(select && select.options.length == 2 && !select.options[1].selected){
                select.options[1].selected = true;
                this.configureElement(select);
            }
        }
}
    
// these are new methods introduced by the extension
// extension Code
Product.ConfigSingle.prototype.configureImage = function(event){
    var element = Event.element(event);
    attributeId = element.parentNode.id.replace(/[a-z-]*/, '');
    optionId = element.id.replace(/[a-z-]*/, '');
    
    this.selectImage(element);
    
    $('attribute' + attributeId).value = optionId;
    this.configureElement($('attribute' + attributeId));
}

Product.ConfigSingle.prototype.selectImage = function(element)
{
    attributeId = element.parentNode.id.replace(/[a-z-]*/, '');
    $('amconf-images-' + attributeId).childElements().each(function(child){
        if(child.childElements()[0])
            child.childElements()[0].removeClassName('amconf-image-selected');
    });
    element.addClassName('amconf-image-selected');
}

Product.ConfigSingle.prototype.processEmpty = function()
{
    $$('.super-attribute-select').each(function(select) {
        var attributeId = select.id.replace(/[a-z]*/, '');
        if (select.disabled)
        {
            if ($('amconf-images-' + attributeId))
            {
                $('amconf-images-' + attributeId).parentNode.removeChild($('amconf-images-' + attributeId));
            }
            holder = select.parentNode;
            holderDiv = document.createElement('div');
            holderDiv.addClassName('amconf-images-container');
            holderDiv.id = 'amconf-images-' + attributeId;
            if ('undefined' != typeof(confDataSingle))
            {
                holderDiv.innerHTML = confDataSingle.textNotAvailable;
            } else 
            {
                holderDiv.innerHTML = "";
            }
            holder.insertBefore(holderDiv, select);
        } else if (!select.disabled && !$(select).hasClassName("no-display")) {
            var element = $(select.parentNode).select('#amconf-images-' + attributeId)[0];
            if (typeof confDataSingle != 'undefined' && typeof element != 'undefined' && element.innerHTML == confDataSingle.textNotAvailable){
                element.parentNode.removeChild(element);
            }
        }
    }.bind(this));
}

Product.ConfigSingle.prototype.clearConfig = function()
{
    this.settings[0].value = "";
    if (typeof confDataSingle != 'undefined')
        confDataSingle.isResetButton = true;
    this.configureElement(this.settings[0]);
    $$('span.amconf-label').each(function (el){
        el.remove();
    })
    return false;
}

Product.ConfigSingle.prototype.updateData = function(key)
{
    var imageClassName = '.product-img-box';
    if(!$$(imageClassName)[0]) var imageClassName = '.img-box';
    if(!$$(imageClassName)[0]) var imageClassName = '.product-img-column';
    if ('undefined' == typeof(confDataSingle))
    {
        return false;
    }
    if (confDataSingle.hasKey(key))
    {
        // getting values of selected configuration
        if (confDataSingle.getData(key, 'name'))
        {
            $$('.product-name h1').each(function(container){
                if (!confDataSingle.getDefault('name'))
                {
                    confDataSingle.saveDefault('name', container.innerHTML);
                }
                container.innerHTML = confDataSingle.getData(key, 'name');
            }.bind(this));
        }
        if (confDataSingle.getData(key, 'short_description'))
        {
            $$('.short-description div').each(function(container){
                if (!confDataSingle.getDefault('short_description'))
                {
                    confDataSingle.saveDefault('short_description', container.innerHTML);
                }
                container.innerHTML = confDataSingle.getData(key, 'short_description');
            }.bind(this));
        }
        if (confDataSingle.getData(key, 'description'))
        {
            $$('.box-description div').each(function(container){
                if (!confDataSingle.getDefault('description'))
                {
                    confDataSingle.saveDefault('description', container.innerHTML);
                }
                container.innerHTML = confDataSingle.getData(key, 'description');
            }.bind(this));
        }
        if (confDataSingle.getData(key, 'media_url'))
        {
            // should reload images
            tmpContainer = $$(imageClassName)[0];
            new Ajax.Updater(tmpContainer, confDataSingle.getData(key, 'media_url'), {
                evalScripts: true,
                onSuccess: function(transport)
                {
                     
                },
                onCreate: function()
                {
                    confDataSingle.saveDefault('media', tmpContainer.innerHTML);
                    confDataSingle.currentIsMain = false;  
                },
                onComplete: function()
                {
                    if('undefined' != typeof(AmZoomerObj)) {
                        if($$('.zoomContainer')[0]) $$('.zoomContainer')[0].remove();
                            AmZoomerObj.loadZoom();
                    }
                    jQuery('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
                }
            });
        } else if (confDataSingle.getData(key, 'noimg_url'))
        {
            noImgInserted = false;
            $$(imageClassName + ' img').each(function(img){
                if (!noImgInserted)
                {
                    img.src = confDataSingle.getData(key, 'noimg_url');
                    $(img).stopObserving('click');
                    $(img).stopObserving('mouseover');
                    $(img).stopObserving('mousemove');
                    $(img).stopObserving('mouseout');
                    noImgInserted = true;
                }
            });
        }
    } else 
    {
        // setting values of default product
        if (true == confDataSingle.getDefault('set'))
        {
            if (confDataSingle.getDefault('name'))
            {
                $$('.product-name h1').each(function(container){
                    container.innerHTML = confDataSingle.getDefault('name');
                }.bind(this));
            }
            if (confDataSingle.getDefault('short_description'))
            {
                $$('.short-description div').each(function(container){
                    container.innerHTML = confDataSingle.getDefault('short_description');
                }.bind(this));
            }
            if (confDataSingle.getDefault('description'))
            {
                $$('.box-description div').each(function(container){
                    container.innerHTML = confDataSingle.getDefault('description');
                }.bind(this));
            }
            if (confDataSingle.getDefault('media') && !confDataSingle.currentIsMain)
            {
                var tmpContainer = $$(imageClassName)[0];
                new Ajax.Updater(tmpContainer, confDataSingle.mediaUrlMain, {
                    evalScripts: true,
                    onSuccess: function(transport) {
                        confDataSingle.saveDefault('media', tmpContainer.innerHTML);
                        confDataSingle.currentIsMain = true;
                    },
                    onComplete: function()
                    {
                        if('undefined' != typeof(AmZoomerObj)) {
                            if($$('.zoomContainer')[0]) $$('.zoomContainer')[0].remove();
                            AmZoomerObj.loadZoom();
                        }
                        jQuery('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
                    }
                });
            }
        }
    }
}

//start code for reload simple price

Product.ConfigSingle.prototype.reloadSimplePrice = function(key)
{
     if ('undefined' == typeof(confDataSingle))
    {
        return false;
    }
    
    var container;
    var result = false;
    if (confDataSingle.hasKey(key))
    {
        // convert div.price-box into price info container
        // top price box
        if (confDataSingle.getData(key, 'price_html'))
        {
            $$('.product-shop .price-box').each(function(container)
            {
                if (!confDataSingle.getDefault('price_html'))
                {
                    confDataSingle.saveDefault('price_html', container.innerHTML);
                }
                container.addClassName('amconf_price_container');
            }.bind(this));


             $$('.product-shop .tax-details').each(function(container)
                {
                    container.remove();
        }.bind(this));
    $$('.product-shop .tier-prices').each(function(container)
                {
                    container.remove();
        }.bind(this));
   
            $$('.amconf_price_container').each(function(container)
            {
                container.outerHTML = confDataSingle.getData(key, 'price_html');  
            }.bind(this));        
        }
        
        // bottom price box
        if (confDataSingle.getData(key, 'price_clone_html'))
        {
            $$('.product-options-bottom .price-box').each(function(container)
            {
                if (!confDataSingle.getDefault('price_clone_html'))
                {
                    confDataSingle.saveDefault('price_clone_html', container.innerHTML);
                }
                container.addClassName('amconf_price_clone_container');
            }.bind(this));
            
            $$('.amconf_price_clone_container').each(function(container)
            {
        container.outerHTML = confDataSingle.getData(key, 'price_clone_html');    
        }.bind(this));

        }
        
        // function return value
        if (confDataSingle.getData(key, 'price'))
        {
            result = confDataSingle.getData(key, 'price');
        }
    } 
    else 
    {
        // setting values of default product
        if (true == confDataSingle.getDefault('set'))
        {
            // restore price info containers into default price-boxes
            if (confDataSingle.getDefault('price_html'))
            {
                $$('.product-shop .price-box').each(function(container)
                {
                    container.addClassName('amconf_price_container');
                }.bind(this));
        $$('.product-shop .tier-prices').each(function(container)
                {
                    container.remove();
            }.bind(this));
                          
                $$('.amconf_price_container').each(function(container)
                {
                    container.innerHTML  = confDataSingle.getDefault('price_html');
                    container.removeClassName('amconf_price_container');    
                }.bind(this));
            }
            
            if (confDataSingle.getDefault('price_clone_html'))
            {
                $$('.product-options-bottom .price-box').each(function(container)
                {
                    container.addClassName('amconf_price_clone_container');
                }.bind(this));

                $$('.amconf_price_clone_container').each(function(container){
                    container.innerHTML = confDataSingle.getDefault('price_clone_html');
                    container.removeClassName('amconf_price_clone_container');  
                }.bind(this));
                
            }
            
            // function return value
            if (confDataSingle.getDefault('price'))
            {
                result = confDataSingle.getDefault('price');
            }
        }
    }
    
    return result; // actually the return value is never used
}




Product.ConfigSingle.prototype.getOptionLabel = function(option, price){
    var price = parseFloat(price);
    if (this.taxConfig.includeTax) {
        var tax = price / (100 + this.taxConfig.defaultTax) * this.taxConfig.defaultTax;
        var excl = price - tax;
        var incl = excl*(1+(this.taxConfig.currentTax/100));
    } else {
        var tax = price * (this.taxConfig.currentTax / 100);
         var excl = price;
         var incl = excl + tax;
    }
    if (this.taxConfig.showIncludeTax || this.taxConfig.showBothPrices) {
        price = incl;
    } else {
        price = excl;
    }
    var str = option.label;
    if(price){
        if('undefined' != typeof(confDataSingle) && confDataSingle.useSimplePrice == "1" && confDataSingle['optionProducts'] && confDataSingle['optionProducts'][option.id] && confDataSingle['optionProducts'][option.id]['price']) {
            str+= ' ' + this.formatPrice(confDataSingle['optionProducts'][option.id]['price'], true);
            pos = str.indexOf("+");
            str = str.substr(0, pos) + str.substr(pos + 1, str.length);
        }
        else {
            if (this.taxConfig.showBothPrices) {
                str+= ' ' + this.formatPrice(excl, true) + ' (' + this.formatPrice(price, true) + ' ' + this.taxConfig.inclTaxTitle + ')';
            } else {
                str+= ' ' + this.formatPrice(price, true);
            }
        }
    }
    else {
        if('undefined' != typeof(confDataSingle) && confDataSingle.useSimplePrice == "1" && confDataSingle['optionProducts'] && confDataSingle['optionProducts'][option.id] && confDataSingle['optionProducts'][option.id]['price']) {
            str+= ' ' + this.formatPrice(confDataSingle['optionProducts'][option.id]['price'], true);
            pos = str.indexOf("+");
            str = str.substr(0, pos) + str.substr(pos + 1, str.length);
        }    
    }
    return str;
}

Event.observe(window, 'load', function(){
    if ('undefined' != typeof(confDataSingle) && confDataSingle.useSimplePrice == "1")
    {
        confDataSingle.reloadOptions();
    }
});
// extension Code End