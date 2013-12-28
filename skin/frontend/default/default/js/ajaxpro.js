/* <!-- AjaxPro --> */

var AjaxPro = function () {

var _config = {
    handles : [],
    baseUrl : ''
};
var _comments = [];

function _getComments() {
   //get all comments
    var elements = document.getElementsByTagName('*');
    var comments = [];
    for(var i in elements) {
        if (elements[i] && elements[i].childNodes) {
            var nodes = elements[i].childNodes;
        } else {
            continue;
        }
        for (var j in nodes) {
            var node = nodes[j];
            if (node && 8 == node.nodeType) {
                comments.push(node);
            }
        }
    }
    _comments = comments;
}
function _replaceBlock(blockName, html) {

    if (Prototype.Browser.IE) {
        html = "&zwnj;" + html; // IE8 bugfix
    }

    function __getCommentNode(id) {
        for(var i in _comments) {
            if (_comments[i] && _comments[i].data == " " + id + " ") {
                var node = _comments[i];
                delete _comments[i];
                return node;
            }
        }
        return false;
    }

    function __replace(start, end) {
        var node = start;
        var parent = start.parentNode;
        do {
            var remove = node;
            node = node.nextSibling;
            parent.removeChild(remove);
        } while (node && node != end);

//        try {
//            var range = document.createRange();
//            var frag = range.createContextualFragment(html);
//            parent.replaceChild(frag, end);
//        } catch(err) {
            var tempdiv = document.createElement('div');
            tempdiv.innerHTML = html;
            node = tempdiv.firstChild;
            var next = node.nextSibling;
            while (node) {
                parent.insertBefore(node, end);
                node = next;
                next = node ? node.nextSibling : undefined;
            }
            parent.removeChild(end);
//        }
    }

    var start, end;
    while ((start = __getCommentNode('ajaxpro_' + blockName + '_start')) &&
        (end = __getCommentNode('ajaxpro_' + blockName + '_end'))) {

        __replace(start, end);
    }
}

return {
    version: '2.0',
    init: function(config) {

        Object.extend(_config, config);

        // ie9 fix
        Event.stop = Event.stop.wrap(function(callOriginal, event) {
            if (typeof event != 'undefined') {
                event.preventDefault ? event.preventDefault() : event.returnValue = false;
            }
            return callOriginal(event);
        });

        AjaxPro.fire('addObservers');

        Ajax.Responders.register({
            onComplete: function() {
                AjaxPro.fire.delay(1, 'addObservers');
            }
        });

    },
    /**
     * url, method, parameters
     *
     * request function
     */
    request: function (request) {
        var baseUrl = _config.baseUrl
            .replace('http://', '')
            .replace('https://', ''),
        fires = request.url.replace('http://', '').replace('https://', '')
            .replace(baseUrl, '')
            .split('/')
            .splice(0, 3);

        if (0 == window.location.protocol.indexOf('https')) {
            request.url = request.url.replace('http:', 'https:');
        } else if (0 == window.location.protocol.indexOf('http')) {
            request.url = request.url.replace('https:', 'http:');
        }
        request.method = request.method || 'post',
        request.parameters = Object.extend(
            {'handles[]': _config.handles, ajaxpro: 1, in_cart: 1}, request.parameters || {}
        );

        new Ajax.Request(request.url, {
            parameters: request.parameters,
            method: request.method,
            onLoading: function() {
                fires.unshift('onLoading');
                AjaxPro.fire(fires, request);
            },

            onComplete: function(transport) {
                var response = transport.responseJSON;
                if (!response) {
		   response = transport.responseText.evalJSON();
		}
                request.response = response;
                if (!response || 200 != transport.status || !response.status) {
                    fires.unshift('onFailure');
                    AjaxPro.fire(fires, request);
                    return;
                }
                /////////////////////
                _getComments();
                for (var block in response.layout) {
                    var content = response.layout[block];
                    _replaceBlock(block, content);

                    content.extractScripts().map(function(script) {
                        return window.eval(script);
                    });
                }
                ///////////////
                fires.unshift('onComplete');
                AjaxPro.fire(fires, request);
            }
        });
    },
    fire: function(eventName, memo) {
        var events = [];
        if ('string' == typeof eventName) {
            events.push(eventName);
        } else {
            events = eventName.slice();
            eventName.shift();
        }
        while(events.length) {
            Element.fire(document, 'AjaxPro:' + events.join(':'), memo);
            events.pop();
        }
    },
    observe: function(eventName, handler) {
//        handler = handler || function(e) {debugger; console.log(e);};
        Element.observe(document, 'AjaxPro:' + eventName, handler);
    }
    }
}();