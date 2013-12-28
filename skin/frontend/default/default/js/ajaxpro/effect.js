/* <!-- AjaxPro --> */
AjaxPro.effect = function(){

    var _config = {
        location : 'center',
        opacity  : 0.95,
        overlay  : 0.7,
        isMobile : false,
        duration : 0.5
    };
    function __getPosition() {
        var scrollOffsets = document.viewport.getScrollOffsets(),
            viewportSize  = document.viewport.getDimensions(),
            left          = viewportSize.width / 2,
            top;

        if ('undefined' === typeof viewportSize.height) { // mobile fix
            top = scrollOffsets.top + 100;
            left = scrollOffsets.left + left;
        } else {
            left = scrollOffsets.left + left;
            switch (_config.location) {
                case 'top':
                    top = scrollOffsets.top + 10;
                    break;
                case 'bottom':
                    top = scrollOffsets.top + viewportSize.height - 150;
                    break;
                case 'center':
                default:
                    top = scrollOffsets.top + viewportSize.height / 2 - 150;
                    break;
            }
        }
        return {
            top : top + 'px',
            left: left + 'px'
        };
    }

    var spinner = function() {
        var _id;
        function _getElement() {
            return $(_id);
        }
        return {
            setElementId: function(id) {
                _id = id;
                return this;
            },
            hide: function(){
                _getElement().hide();
                return this;
            },
            show: function(){
                this.hide();
                _getElement().setStyle(__getPosition()).show();
                return this;
            }
        }
    }(), overlay = function(){
        var _id;
        function _getElement() {
            return $(_id);
        }
        return {
            setElementId: function(id) {
                _id = id;
                return this;
            },
            show: function(){
                var el = _getElement();
                if (!el) {
                    return this;
                }
                if (_config.isMobile) {
                    this.scrollOverlayIntervalId = setInterval(function() {
                        var scrollOffsets = document.viewport.getScrollOffsets();
                        el.setStyle({
                            top      : scrollOffsets.top + 'px',
                            left     : scrollOffsets.left + 'px',
                            position : 'absolute'
                        });
                    }, 100);
                }

                el.setOpacity(0);
                el.setStyle({display: 'block'});
                el.fade({
                    duration: _config.duration,
                    from: 0,
                    to: _config.overlay
                });
                return this;
            },
            hide: function(){
                var el = _getElement();
                if (!el) {
                    return this;
                }
                el.fade({
                    duration: _config.duration,
                    from: _config.overlay,
                    to: 0
                });
//                el.hide();
                if (this.scrollOverlayIntervalId) {
                    clearInterval(this.scrollOverlayIntervalId);
                }
                return this;
            }
        }
    }(), opacity = function(){
        var _selector;
        return {
            setSelector: function(selector){
                _selector = selector;
                return this;
            },
            show: function(opacity){
                opacity = opacity || _config.opacity;
                $$(_selector).each(function(element){
                    element.fade({
                        duration: _config.duration,
                        from: 1,
                        to: opacity
                    });
                });
                return this;
            },
            hide: function(opacity){
                opacity = opacity || _config.opacity;
                $$(_selector).each(function(element){
                    element.fade({
                        duration: _config.duration,
                        from: opacity,
                        to: 1
                    });
                });
                return this;
            }
        };
    }(), message = function(){
        var _id, _messages = [];
        function _getElement() {
            return $(_id);
        }
        return {
            setElementId: function(id){
                _id = id;
                return this;
            },
            setMessages: function(messages){
                _messages = messages;
                return this;
            },
            show: function(){

                var element = $(_id);
                if (!element) {
                    return;
                }

                element.setStyle(__getPosition());

                var message = '', i;

                // ie dont love <ul> in <p> etc.
                /*if (Prototype.Browser.IE) {
                    for(var code in _messages) {
                        var temp = _messages[code];
                        i = 0;
                        while (temp[i]) {
                            message += temp[i];
                            i++;
                        }
                    }
                } else {*/
                message += '<ul>';
                for(var code in _messages) {
                    var temp = _messages[code], length = temp.length;
                    for(i = length; i--;) {
                        message += '<li class="' + code + '">' +
                            temp[i] +
                        '</li>';
                    }
                }
                message += '</ul>';
//                }
                element.select('.ajaxpro-message').invoke('update', message);

            //  element.removeClassName('no-display')
                element.setStyle('');
//                element.fade({duration: 1, from: 1, to: _config.opacity});
                element.setOpacity(_config.opacity);

                var _eschide = function (e){
                    if (27 !== e.keyCode) {
                        return;
                    }
                    _hide();
                }

                var _lostFocusHide = function(e) {
                    if (!Event.element(e).up('.ajaxpro-form')) {
                        _hide();
                    }
                }
                var buttons = element.select('.ajaxpro-button');

                var _hide = function(){
                    element.hide();

                    document.stopObserving('keyup', _eschide);
                    document.stopObserving('click', _lostFocusHide);
                    buttons.each(function(el){
                        el.stopObserving('click', _hide);
                    });

                    AjaxPro.fire('message:hide');
                }
                buttons.each(function(el){
                    el.observe('click', _hide);
                });
                document.observe('keyup', _eschide);
                document.observe('click', _lostFocusHide);

                element.show();
            }
        }
    }();

    return {
        init: function(config) {
            Object.extend(_config, config);
        },
        spinner : spinner,
        overlay : overlay,
        opacity : opacity,
        message : message
}}();
