/* global document */
/**
 * WidgetsServer.js 1.5.1
 *
 *  (c) 2013 JotForm Easiest Form Builder
 */
if (window.JCFServerCommon === undefined) {
  window.JCFServerCommon = {
    frames: {},
    func: {}
  }
} else {}

if (typeof JSON !== 'object') {
  JSON = {};
}

// IE console fix
if (!window.console) console = {
  log: function() {}
};


(function() {
  'use strict';

  function f(n) {
    // Format integers to have at least two digits.
    return n < 10 ? '0' + n : n;
  }

  if (typeof Date.prototype.toJSON !== 'function') {

    Date.prototype.toJSON = function() {

      return isFinite(this.valueOf()) ? this.getUTCFullYear() + '-' +
        f(this.getUTCMonth() + 1) + '-' +
        f(this.getUTCDate()) + 'T' +
        f(this.getUTCHours()) + ':' +
        f(this.getUTCMinutes()) + ':' +
        f(this.getUTCSeconds()) + 'Z' : null;
    };

    String.prototype.toJSON =
      Number.prototype.toJSON =
      Boolean.prototype.toJSON = function() {
        return this.valueOf();
      };
  }

  var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
    escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
    gap,
    indent,
    meta = { // table of character substitutions
      '\b': '\\b',
      '\t': '\\t',
      '\n': '\\n',
      '\f': '\\f',
      '\r': '\\r',
      '"': '\\"',
      '\\': '\\\\'
    },
    rep;


  function quote(string) {

    // If the string contains no control characters, no quote characters, and no
    // backslash characters, then we can safely slap some quotes around it.
    // Otherwise we must also replace the offending characters with safe escape
    // sequences.

    escapable.lastIndex = 0;
    return escapable.test(string) ? '"' + string.replace(escapable, function(a) {
      var c = meta[a];
      return typeof c === 'string' ? c : '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
    }) + '"' : '"' + string + '"';
  }


  function str(key, holder) {

    // Produce a string from holder[key].

    var i, // The loop counter.
      k, // The member key.
      v, // The member value.
      length,
      mind = gap,
      partial,
      value = holder[key];

    // If the value has a toJSON method, call it to obtain a replacement value.

    if (value && typeof value === 'object' &&
      typeof value.toJSON === 'function') {
      value = value.toJSON(key);
    }

    // If we were called with a replacer function, then call the replacer to
    // obtain a replacement value.

    if (typeof rep === 'function') {
      value = rep.call(holder, key, value);
    }

    // What happens next depends on the value's type.

    switch (typeof value) {
      case 'string':
        return quote(value);

      case 'number':

        // JSON numbers must be finite. Encode non-finite numbers as null.

        return isFinite(value) ? String(value) : 'null';

      case 'boolean':
      case 'null':

        // If the value is a boolean or null, convert it to a string. Note:
        // typeof null does not produce 'null'. The case is included here in
        // the remote chance that this gets fixed someday.

        return String(value);

        // If the type is 'object', we might be dealing with an object or an array or
        // null.

      case 'object':

        // Due to a specification blunder in ECMAScript, typeof null is 'object',
        // so watch out for that case.

        if (!value) {
          return 'null';
        }

        // Make an array to hold the partial results of stringifying this object value.

        gap += indent;
        partial = [];

        // Is the value an array?

        if (Object.prototype.toString.apply(value) === '[object Array]') {

          // The value is an array. Stringify every element. Use null as a placeholder
          // for non-JSON values.

          length = value.length;
          for (i = 0; i < length; i += 1) {
            partial[i] = str(i, value) || 'null';
          }

          // Join all of the elements together, separated with commas, and wrap them in
          // brackets.

          v = partial.length === 0 ? '[]' : gap ? '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']' : '[' + partial.join(',') + ']';
          gap = mind;
          return v;
        }

        // If the replacer is an array, use it to select the members to be stringified.

        if (rep && typeof rep === 'object') {
          length = rep.length;
          for (i = 0; i < length; i += 1) {
            if (typeof rep[i] === 'string') {
              k = rep[i];
              v = str(k, value);
              if (v) {
                partial.push(quote(k) + (gap ? ': ' : ':') + v);
              }
            }
          }
        } else {

          // Otherwise, iterate through all of the keys in the object.

          for (k in value) {
            if (Object.prototype.hasOwnProperty.call(value, k)) {
              v = str(k, value);
              if (v) {
                partial.push(quote(k) + (gap ? ': ' : ':') + v);
              }
            }
          }
        }

        // Join all of the member texts together, separated with commas,
        // and wrap them in braces.

        v = partial.length === 0 ? '{}' : gap ? '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}' : '{' + partial.join(',') + '}';
        gap = mind;
        return v;
    }
  }

  // If the JSON object does not yet have a stringify method, give it one.

  if (typeof JSON.stringify !== 'function') {
    JSON.stringify = function(value, replacer, space) {

      // The stringify method takes a value and an optional replacer, and an optional
      // space parameter, and returns a JSON text. The replacer can be a function
      // that can replace values, or an array of strings that will select the keys.
      // A default replacer method can be provided. Use of the space parameter can
      // produce text that is more easily readable.

      var i;
      gap = '';
      indent = '';

      // If the space parameter is a number, make an indent string containing that
      // many spaces.

      if (typeof space === 'number') {
        for (i = 0; i < space; i += 1) {
          indent += ' ';
        }

        // If the space parameter is a string, it will be used as the indent string.

      } else if (typeof space === 'string') {
        indent = space;
      }

      // If there is a replacer, it must be a function or an array.
      // Otherwise, throw an error.

      rep = replacer;
      if (replacer && typeof replacer !== 'function' &&
        (typeof replacer !== 'object' ||
          typeof replacer.length !== 'number')) {
        throw new Error('JSON.stringify');
      }

      // Make a fake root object containing our value under the key of ''.
      // Return the result of stringifying the value.

      return str('', {
        '': value
      });
    };
  }


  // If the JSON object does not yet have a parse method, give it one.

  if (typeof JSON.parse !== 'function') {
    JSON.parse = function(text, reviver) {

      // The parse method takes a text and an optional reviver function, and returns
      // a JavaScript value if the text is a valid JSON text.

      var j;

      function walk(holder, key) {

        // The walk method is used to recursively walk the resulting structure so
        // that modifications can be made.

        var k, v, value = holder[key];
        if (value && typeof value === 'object') {
          for (k in value) {
            if (Object.prototype.hasOwnProperty.call(value, k)) {
              v = walk(value, k);
              if (v !== undefined) {
                value[k] = v;
              } else {
                delete value[k];
              }
            }
          }
        }
        return reviver.call(holder, key, value);
      }


      // Parsing happens in four stages. In the first stage, we replace certain
      // Unicode characters with escape sequences. JavaScript handles many characters
      // incorrectly, either silently deleting them, or treating them as line endings.

      text = String(text);
      cx.lastIndex = 0;
      if (cx.test(text)) {
        text = text.replace(cx, function(a) {
          return '\\u' +
            ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
        });
      }

      // In the second stage, we run the text against regular expressions that look
      // for non-JSON patterns. We are especially concerned with '()' and 'new'
      // because they can cause invocation, and '=' because it can cause mutation.
      // But just to be safe, we want to reject all unexpected forms.

      // We split the second stage into 4 regexp operations in order to work around
      // crippling inefficiencies in IE's and Safari's regexp engines. First we
      // replace the JSON backslash pairs with '@' (a non-JSON character). Second, we
      // replace all simple value tokens with ']' characters. Third, we delete all
      // open brackets that follow a colon or comma or that begin the text. Finally,
      // we look to see that the remaining characters are only whitespace or ']' or
      // ',' or ':' or '{' or '}'. If that is so, then the text is safe for eval.

      if (/^[\],:{}\s]*$/
        .test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@')
          .replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
          .replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

        // In the third stage we use the eval function to compile the text into a
        // JavaScript structure. The '{' operator is subject to a syntactic ambiguity
        // in JavaScript: it can begin a block or an object literal. We wrap the text
        // in parens to eliminate the ambiguity.

        j = eval('(' + text + ')');

        // In the optional fourth stage, we recursively walk the new structure, passing
        // each name/value pair to a reviver function for possible transformation.

        return typeof reviver === 'function' ? walk({
          '': j
        }, '') : j;
      }

      // If the text is not JSON parseable, then a SyntaxError is thrown.

      throw new SyntaxError('JSON.parse');
    };
  }
}());

if (window.getIframeWindow === undefined) {
  function getIframeWindow(iframe_object) {
    var doc;
    if (iframe_object.contentWindow) {
      return iframe_object.contentWindow;
    }
    if (iframe_object.window) {
      return iframe_object.window;
    }
    if (!doc && iframe_object.contentDocument) {
      doc = iframe_object.contentDocument;
    }
    if (!doc && iframe_object.document) {
      doc = iframe_object.document;
    }
    if (doc && doc.defaultView) {
      return doc.defaultView;
    }
    if (doc && doc.parentWindow) {
      return doc.parentWindow;
    }
    return undefined;
  }
}

/*
    we use this function to extract background color of form
 */
if (window.getStyle === undefined) {
  function getStyle(el, styleProp) {
    var value, defaultView = (el.ownerDocument || document).defaultView;
    // W3C standard way:
    if (defaultView && defaultView.getComputedStyle) {
      // sanitize property name to css notation
      // (hypen separated words eg. font-Size)
      styleProp = styleProp.replace(/([A-Z])/g, "-$1").toLowerCase();
      return defaultView.getComputedStyle(el, null).getPropertyValue(styleProp);
    } else if (el.currentStyle) { // IE
      // sanitize property name to camelCase
      styleProp = styleProp.replace(/\-(\w)/g, function(str, letter) {
        return letter.toUpperCase();
      });
      value = el.currentStyle[styleProp];
      // convert other units to pixels on IE
      if (/^\d+(em|pt|%|ex)?$/i.test(value)) {
        return (function(value) {
          var oldLeft = el.style.left,
            oldRsLeft = el.runtimeStyle.left;
          el.runtimeStyle.left = el.currentStyle.left;
          el.style.left = value || 0;
          value = el.style.pixelLeft + "px";
          el.style.left = oldLeft;
          el.runtimeStyle.left = oldRsLeft;
          return value;
        })(value);
      }
      return value;
    }
  }
}

//postMessage utility function
if (typeof XD === 'undefined') {
  var XD = function() {
    var interval_id,
      last_hash,
      cache_bust = 1,
      attached_callback,
      window = this;
    return {
      postMessage: function(message, target_url, target) {
        if (!target_url) {
          return;
        }

        target = target || parent; // default to parent
        if (window.postMessage) {

          // the browser supports window.postMessage, so call it with a targetOrigin
          // set appropriately, based on the target_url parameter.
          if (!("postMessage" in target)) {
            //we have a problem, update target
            target = getIframeWindow(target);
          }

          target.postMessage(message, target_url.replace(/([^:]+:\/\/[^\/]+).*/, '$1'));
        } else if (target_url) {
          // the browser does not support window.postMessage, so use the window.location.hash fragment hack
          target.location = target_url.replace(/#.*$/, '') + '#' + (+new Date) + (cache_bust++) + '&' + message;
        }
      },
      receiveMessage: function(callback, source_origin) {
        // browser supports window.postMessage
        if (window.postMessage) {
          // bind the callback to the actual event associated with window.postMessage
          if (callback) {
            attached_callback = function(e) {
              if ((typeof source_origin === 'string' && e.origin !== source_origin) || (Object.prototype.toString.call(source_origin) === "[object Function]" && source_origin(e.origin) === !1)) {
                return !1;
              }
              callback(e);
            };
          }
          if (window['addEventListener']) {
            window[callback ? 'addEventListener' : 'removeEventListener']('message', attached_callback, !1);
          } else {
            window[callback ? 'attachEvent' : 'detachEvent']('onmessage', attached_callback);
          }
        } else {
          // a polling loop is started & callback is called whenever the location.hash changes
          interval_id && clearInterval(interval_id);
          interval_id = null;
          if (callback) {
            interval_id = setInterval(function() {
              var hash = document.location.hash,
                re = /^#?\d+&/;
              if (hash !== last_hash && re.test(hash)) {
                last_hash = hash;
                callback({
                  data: hash.replace(re, '')
                });
              }
            }, 100);
          }
        }
      }
    };
  }();
}

/*
    function runs when widget iframe loaded
    look at onload attribute of related widget iframe
    it is passing iframe id
 */
function widgetFrameLoaded(id) {

  //iframe DOM object
  var frameObj = document.getElementById("customFieldFrame_" + id);

  // flag to show or hide console logs
  var enableLog = false;

  // if the iframe not full rendered don't do anything at it first
  if (!frameObj.hasClassName('custom-field-frame-rendered')) {
    enableLog && console.log('Not rendered yet for', id);
    return;
  }

  // if oEmbed, ignore
  if (isOembedWidget(id)) {
    return;
  }

  //register widget to JCFServerCommon object
  //useful while debugging forms with widgets
  JCFServerCommon.frames[id] = {};
  JCFServerCommon.frames[id].obj = frameObj;
  var src = frameObj.src;

  JCFServerCommon.frames[id].src = src;
  JCFServerCommon.submitFrames = [];

  //to determine whether submit message passed form submit or next page
  var nextPage = true;
  //to determine which section is open actually
  var section = false;

  //we are changing iframe src dynamically
  //at first load iframe does not have src attribute that is why it behaves like it has form's URL
  //to prevent dublicate events if it is form url return
  // if(src.match('/form/')) {
  //     console.log("returning from here");
  //     return;
  // }

  var referer = src;
  //form DOM object
  var thisForm = (JotForm.forms[0] == undefined || typeof JotForm.forms[0] == "undefined") ? $($$('.jotform-form')[0].id) : JotForm.forms[0];

  //detect IE version
  function getIEVersion() {
    var match = navigator.userAgent.match(/(?:MSIE |Trident\/.*; rv:)(\d+)/);
    return match ? parseInt(match[1]) : undefined;
  }

  // check if the form is valid and no errors
  function isTheFormValidated() {
    return (JotForm.validateAll() && $$('.form-line-error').length === 0);
  }

  // check a valid json string
  function IsValidJsonString(str) {
    try {
      JSON.parse(str);
    } catch (e) {
      return false;
    }
    return true;
  }

  // check if oEmbed widget
  function isOembedWidget(id) {
    var isOembed = false;
    var iframeElement = document.getElementById('customFieldFrame_' + id);
    if (iframeElement.hasAttribute('data-type')) {
      var type = iframeElement.readAttribute('data-type');
      if (type === 'oembed') {
        isOembed = true;
      }
    }

    return isOembed;
  }

  //send message to widget iframe
  function sendMessage(msg, id, to) {

    var ref = referer;
    if (to !== undefined) { //to option is necesaary for messaging between widgets and themes page
      ref = to;
    }

    var iframeElement = document.getElementById('customFieldFrame_' + id);

    // if no iframe do not send postmessage
    if (iframeElement === null) {
      return;
    }

    if (navigator.userAgent.indexOf("Firefox") != -1) {
      XD.postMessage(msg, ref, getIframeWindow(iframeElement));
    } else {
      if (getIEVersion() !== undefined) { //if IE
        XD.postMessage(msg, ref, iframeElement);
      } else {
        XD.postMessage(msg, ref, getIframeWindow(iframeElement));
      }
    }
  }

  window.sendMessage2Widget = sendMessage;

  //function that gets the widget settings from data-settings attribute of the iframe
  function getWidgetSettings() {
    var el = document.getElementById('widget_settings_' + id);
    return (el) ? el.value : null;
  }

  // function to check if a widget is required
  function isWidgetRequired(id) {
    var classNames = document.getElementById('id_' + id).className;
    return classNames.indexOf('jf-required') > -1;
  }

  //send message to widget iframe and change data-status to ready
  function sendReadyMessage(id) {

    var background = navigator.userAgent.indexOf("Firefox") != -1 ? window.getComputedStyle(document.querySelector('.form-all'), null).getPropertyValue("background-color") : getStyle(document.querySelector('.form-all'), "background");
    var fontFamily = navigator.userAgent.indexOf("Firefox") != -1 ? window.getComputedStyle(document.querySelector('.form-all'), null).getPropertyValue("font-family") : getStyle(document.querySelector('.form-all'), "font-family");
    //send ready message to widget
    //including background-color, formId, questionID and value if it is edit mode (through submissions page)

    var msg = {
      type: "ready",
      qid: id + "",
      formID: document.getElementsByName('formID')[0].value,
      required: isWidgetRequired(id),
      background: background,
      fontFamily: fontFamily
    };

    // if settings not null, include it
    var _settings = getWidgetSettings();
    if (_settings && decodeURIComponent(_settings) !== '[]') {
      msg.settings = _settings;
    }

    // data-value attribute is set if form is in editMode.
    var wframe = document.getElementById('customFieldFrame_' + id);
    // include initial width and height of the widget
    var fwidth = wframe.readAttribute('data-width') || wframe.style.width;
    var fheight = wframe.readAttribute('data-height') || wframe.style.height;
    msg.width = parseInt(fwidth);
    msg.height = parseInt(fheight);

    // helper function
    function _sendReadyMessage(id, msg) {
      // put a custom class when ready
      $(document.getElementById('customFieldFrame_' + id)).addClassName('frame-ready');

      // send ready message
      sendMessage(JSON.stringify(msg), id);
    }

    // make sure we get the data first before sending ready message, only when msg.value undefined
    var isEditMode = (!!~window.location.href.indexOf("/edit/") || !!~window.location.href.indexOf("inlineEdit"));
    if (isEditMode) {
      // if edit mode do some polling with timeout
      // interval number for each check in ms
      var interval = 50;
      // lets give the check interval a timeout in ms
      var timeout = 5000;
      // will determine the timeout value
      var currentTime = 0;

      var editCheckInterval = setInterval(function() {
        // clear interval when data-value attribute is set on the iframe
        // that means the 'getSubmissionResults' request has now the question data
        if (wframe.hasAttribute('data-value') || (currentTime >= timeout)) {
          // clear interval
          clearInterval(editCheckInterval);

          // renew value, whether its empty
          msg.value = wframe.getAttribute("data-value");

          // send message
          enableLog && console.log('Ready message sent in', currentTime, msg);
          _sendReadyMessage(id, msg);
        }

        currentTime += interval;
      }, interval);
    } else {
      // set value
      msg.value = wframe.getAttribute("data-value");

      // send message
      enableLog && console.log('Sending normal ready message', msg);
      _sendReadyMessage(id, msg);
    }
  }

  // expose ready message function
  window.JCFServerCommon.frames[id]['sendReadyMessage'] = sendReadyMessage;

  //bind receive message event
  //a message comes from form
  XD.receiveMessage(function(message) {

    // don't parse some unknown text from third party api of widgets like google recapthca
    if (!IsValidJsonString(message.data)) {
      return;
    }

    //parse message
    var data = JSON.parse(message.data);

    // filter out events from other frames which cause form hang up
    // specially if there are multiple widgets on 1 form
    if (parseInt(id) !== parseInt(data.qid)) {
      return;
    }

    //sendSubmit
    if (data.type === "submit") {
      enableLog && console.log('widget submit', document.getElementById("input_" + data.qid));
      // make sure thats its not an oEmbed widget
      // oEmbed widgets has no hidden input to read
      if (document.getElementById("input_" + data.qid)) {
        if (typeof data.value === 'number') {
          data.value = data.value + "";
        }
        var required = $(document.getElementById("input_" + data.qid)).hasClassName('widget-required') || $(document.getElementById("input_" + data.qid)).hasClassName('validate[required]');
        var input_id_elem = document.getElementById("input_" + data.qid);

        // if the element/question was set to required, we do some necessary validation to display and error or not
        if (required) {
          if ((data.hasOwnProperty('valid') && data.valid === false) && JotForm.isVisible(document.getElementById("input_" + data.qid))) {
            input_id_elem.value = "";

            // put a custom error msg if necessary, only if error object is present
            var req_errormsg = JotForm.texts.required || "This field is required";
            if (typeof data.error !== 'undefined' && data.error !== false) {
              req_errormsg = (data.error.hasOwnProperty('msg')) ? data.error.msg : req_errormsg;
            }

            JotForm.errored(input_id_elem, req_errormsg);
            //return;
          } else {
            JotForm.corrected(input_id_elem);
            if (data.value !== undefined) {
              input_id_elem.value = data.value;
            } else {
              input_id_elem.value = "";
            }
          }
        } else {

          // if not required and value property has a value set
          // make it as the hidden input value
          if (typeof data !== 'undefined') {
            if ('value' in data) {
              input_id_elem.value = data.value;
            } else if (input_id_elem.hasClassName('widget-static')) {
              input_id_elem.value = '';
              input_id_elem.removeAttribute('name');
            }
          }
        }

        //remove value if field is hidden
        if (input_id_elem && input_id_elem.up(".form-line") && input_id_elem.up(".form-line").hasClassName("form-field-hidden")) {
          input_id_elem.value = '';
          input_id_elem.removeAttribute('name');
        }

        // flag the iframe widget already submitted
        if (JCFServerCommon.submitFrames.indexOf(parseInt(data.qid)) < 0) {
          JCFServerCommon.submitFrames.push(parseInt(data.qid));
        }
      }

      // check for widget required/errors and prevent submission
      var allInputs = $$('.widget-required, .widget-errored');
      var sendSubmit = true;
      for (var i = 0; i < allInputs.length; i++) {
        if (allInputs[i].value.length === 0 && JotForm.isVisible(allInputs[i])) {
          sendSubmit = false;
        }
      }
      //if widget is made required by condition
      // var requiredByConditionInputs = document.getElementsByClassName("form-widget validate[required]");
      // console.log("requiredByConditionInputs". requiredByConditionInputs.length);

      // for(var i=0; i<requiredByConditionInputs.length; i++) {
      //     if(requiredByConditionInputs[i].value.length === 0 && JotForm.isVisible(requiredByConditionInputs[i])) {
      //         sendSubmit = false;
      //     }
      // }

      if (!nextPage) {
        enableLog && console.log('next page', nextPage);
        if (isTheFormValidated() && sendSubmit) {
          enableLog && console.log('sendSubmit', nextPage, sendSubmit);
          var tf = (JotForm.forms[0] == undefined || typeof JotForm.forms[0] == "undefined") ? $($$('.jotform-form')[0].id) : JotForm.forms[0];
          // Don't submit if form has Stripe, Braintree, Square. The form submits automatically after credit card is tokenized
          var isEditMode = (!!~window.location.href.indexOf("/edit/")) || window.location.search.match('inlineEdit');
          var autoSubmit = ["stripe", "braintree", "square"];
          if (!(JotForm.payment && autoSubmit.indexOf(JotForm.payment) > -1 && JotForm.isPaymentSelected() && JotForm.paymentTotal > 0 && !isEditMode)) {
            // we will submit the form if all widgets already submitted
            // because some widgets need to do their own processes before sending submit
            // check if all frames submitted a message, thats the time we fire a form submit
            if ($$('.custom-field-frame[data-type="iframe"]').length === JCFServerCommon.submitFrames.length) {
              enableLog && console.log('All frames submitted', JCFServerCommon.submitFrames);
              _submitLast.submit(tf, 50); //submit form with 50 ms delay, submitting only the last call
            } else {
              enableLog && console.log('Not all frames submitted', JCFServerCommon.submitFrames);
            }
          }
        }
      } else {

        // var proceedSection = true;
        // section.select(".widget-required").each(function(inp) {
        //     if (inp.value.length === 0) {
        //         proceedSection = false;
        //     }
        // });

        // //@diki
        // //validate current section
        // var sectionValidated = true;
        // section.select('*[class*="validate"]').each(function(inp) {
        //     if (inp.validateInput === undefined) {
        //         return; /* continue; */
        //     }
        //     if (!( !! inp.validateInput && inp.validateInput())) {
        //         sectionValidated = JotForm.hasHiddenValidationConflicts(inp);
        //     }
        // });

        // if (proceedSection && sectionValidated) {
        //     if (window.parent && window.parent != window) {
        //         window.parent.postMessage('scrollIntoView', '*');
        //     }
        //     if (JotForm.nextPage) {
        //         JotForm.backStack.push(section.hide()); // Hide current
        //         JotForm.currentSection = JotForm.nextPage.show();
        //         //Emre: to prevent page to jump to the top (55389)
        //         if (typeof $this !== "undefined" && !$this.noJump) {
        //             JotForm.currentSection.scrollIntoView(true);
        //         }
        //         JotForm.enableDisableButtonsInMultiForms();
        //     } else if (section.next()) { // If there is a next page
        //         if(section.select(".widget-required").length > 0) {
        //             JotForm.backStack.push(section.hide()); // Hide current
        //             // This code will be replaced with condition selector
        //             JotForm.currentSection = section.next().show();
        //             JotForm.enableDisableButtonsInMultiForms();
        //         }
        //     }
        //     JotForm.nextPage = false;
        // }
      }
    }

    //sendData
    if (data.type === "data") {
      document.getElementById("input_" + data.qid).value = data.value;
      JotForm.triggerWidgetCondition(data.qid);
      JotForm.triggerWidgetCalculation(data.qid);
    }

    //show/hide form errors
    if (data.type === "errors") {
      var inputElem = document.getElementById("input_" + data.qid);
      // check the action
      if (data.action === 'show') {
        // show error
        if (JotForm.isVisible(inputElem)) {
          JotForm.corrected(inputElem);
          inputElem.value = '';
          inputElem.addClassName('widget-errored');
          JotForm.errored(inputElem, data.msg);

          if ('resetForm' in data && data.resetForm === true) {
            JotForm.enableButtons();
          }
        }
      } else if (data.action === 'hide') {
        // hide error
        inputElem.removeClassName('widget-errored');
        JotForm.corrected(inputElem);
      }
    }

    //make a question/widget required
    if (data.type === 'required') {
      var inputElem = document.getElementById("input_" + data.qid);
      // check the action
      if (data.action === 'set' && !inputElem.hasClassName('widget-required')) {
        inputElem.addClassName('widget-required');
      } else if (data.action === 'unset') {
        inputElem.removeClassName('widget-required');
        JotForm.corrected(inputElem);
      }
    }

    //requestFrameSize
    if (data.type === "size") {
      var width = data.width,
        height = data.height;

      if (width !== undefined && width !== null) {
        if (width === 0 || width === "0") {
          width = "auto";
        } else {
          width = width + "px";
        }
        document.getElementById('customFieldFrame_' + data.qid).style.width = width;
      }
      if (height !== undefined && height !== null) {
        document.getElementById('customFieldFrame_' + data.qid).style.height = height + "px";
        //for IE8 : also update height of li element
        if (getIEVersion() !== undefined) {
          document.getElementById('cid_' + data.qid).style.height = height + "px";
        }
      }
      JotForm.iframeHeightCaller();
    }

    //replaceWidget
    if (data.type === "replace") {
      var inputType = data.inputType,
        isMobile = data.isMobile;

      var parentDiv = $("customFieldFrame_" + data.qid).up(),
        inputName = $("input_" + data.qid).readAttribute("name");

      //remove frame
      $("customFieldFrame_" + data.qid).remove();
      $("input_" + data.qid).up().remove();
      var newInput = "";
      switch (inputType) {
        case "control_fileupload":
          var tf = (JotForm.forms[0] == undefined || typeof JotForm.forms[0] == "undefined") ? $($$('.jotform-form')[0].id) : JotForm.forms[0];
          tf.setAttribute('enctype', 'multipart/form-data');

          if (!isMobile) {
            newInput = '<input class="form-upload validate[upload]" type="file" id="input_' + data.qid +
              '" name="' + inputName + '" file-accept="pdf, doc, docx, xls, xlsx, csv, txt, rtf, html, zip, mp3, wma, mpg, flv, avi, jpg, jpeg, png, gif"' +
              'file-maxsize="10240">';
          }
          // console.log("widget is mobile", widget.isMobile);
          parentDiv.insert(newInput);
          break;
        case "control_textbox":
          newInput = '<input class="form-textbox" type="text" data-type-"input-textbox" id="input_' + data.qid +
            '" name="' + inputName + '">';
          parentDiv.insert(newInput);
          break;
        case "control_textarea":
          newInput = '<textarea class="form-textarea" type="text" id="input_' + data.qid +
            '" name="' + inputName + '" cols="40" rows="6"></textarea>';
          parentDiv.insert(newInput);
          break;
        default:
          break;
      }
    }

  }, frameObj.src.replace(/([^:]+:\/\/[^\/]+).*/, '$1'));

  // immediately send the settings to widget
  enableLog && console.log('sending settings', getWidgetSettings(), (new Date()).getTime());
  sendMessage(JSON.stringify({
    type: "settings",
    settings: getWidgetSettings()
  }), id);

  // immediately send the form strings to widget
  enableLog && console.log('sending form strings', JotForm.texts, (new Date()).getTime());
  sendMessage(JSON.stringify({
    type: "formstrings",
    formTexts: JotForm.texts
  }), id);

  //if widget is not visible do not send ready message (it may be on next page)
  //instead send ready message on next page click
  var widgetSection = JotForm.getSection(frameObj);

  if (frameObj && JotForm.isVisible(widgetSection) && JotForm.isVisible(frameObj) && typeof frameObj.up('.form-section-closed') === 'undefined') {
    sendReadyMessage(id);
  }

  //on form submit
  Event.observe(thisForm, 'submit', function(e) {
    if (document.getElementById('customFieldFrame_' + id) === null) {
      return;
    }
    Event.stop(e);
    nextPage = false;
    sendMessage(JSON.stringify({
      type: "submit",
      qid: id + ""
    }), id + "");
  });

  //on pagebreak click
  $$(".form-pagebreak-next").each(function(b, i) {

    $(b).observe('click', function(e) {
      //get the section where the button is clicked
      //this is to identify what section we were previously in
      //so that we only need to send the submit message to that previous widget from the previous page
      section = this.up('.form-section');
      nextPage = true;
      if (section.select("#customFieldFrame_" + id).length > 0) {
        enableLog && console.log('Sending submit message for iframe id', id, "from section", this.up('.form-section'), "and iframe", frameObj);
        sendMessage(JSON.stringify({
          type: "submit",
          qid: id + ""
        }), id + "");
        Event.stop(e);
      }

      //send ready message to the next widget of the page only if the section is fully visible
      //we need to get the actual frameobj and section of the next widget on the next page
      //for us to send the ready for that widget
      var checkIntevarl = setInterval(function() {

        // get the actual widget attach to this event
        frameObj = document.getElementById("customFieldFrame_" + id);
        if (frameObj) {
          // get its form section
          section = $(frameObj).up('.form-section');

          if (JotForm.isVisible(section) && JotForm.isVisible(frameObj)) {
            // if frame and section is visible
            // throw ready message to every widget that was inside of it
            clearInterval(checkIntevarl);
            enableLog && console.log('Sending ready message for iframe id', id, "from section", section);
            sendReadyMessage(id);
          } else {
            enableLog && console.log('Section and frameObj not visible', section, frameObj);
            clearInterval(checkIntevarl);
          }
        } else {
          // missing iframe - it was replace by a normal question field
          // usually happens on ie8 browsers - for widgets that using JFCustomWidget.replaceWidget method
          clearInterval(checkIntevarl);
        }
      }, 100);
    });
  });

  // observer clear custom event from hidden inputs, then notify client for it
  if ($('input_' + id)) {
    $('input_' + id).observe('widget:clear', function(e) {
      var id = e.memo.qid;
      sendMessage(JSON.stringify({
        type: "widgetclear",
        qid: id
      }), id);
    });
  }
}
/*
    This simple object ensures that no matter how many
    submit orders given to a form within a x ms timeframe
    only the last call will be executed and calls after that will be
    discarded.
*/
var _submitLast = {
  t: null,
  submitted: false,
  submit: function(form, delay) {
    if (this.submitted === true) {
      return;
    }
    if (this.t !== null) {
      clearTimeout(this.t);
    }
    this.t = setTimeout(function() {
      this.submitted = true;
      form.submit();
      JotForm.disableButtons();
    }, delay);
  }
};
