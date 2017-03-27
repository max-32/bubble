/* functions.js */

/**
 * Function logs content of the parameter to console
 *
 * @param <mixed> message - What to log
 */
function log(message) {
	console.log(message);
}

/**
 * Function logs content of the parameter to console when debug mode is on
 *
 * @param <mixed> message - What to log
 */
function deb(message) {
	if (window.Undone && Undone.debug) log(message);
}

/**
 * Fill string with given symbols
 *
 * @param <string> pad - What syms to use for padding
 * @param <string> str - What string to pad
 *
 * @return <string>
 */
function pad(pad, str, padLeft) {
	if (typeof str === 'undefined') 
		return pad;
	if (padLeft) {
		return (pad + str).slice(-pad.length);
	} else {
		return (str + pad).substring(0, pad.length);
	}
}

/**
 * Function generates a random string for use in unique IDs, etc
 *
 * @param <int> n - The length of the string
 */
function randomString(n) {
    if (! n) {
        n = 5;
    }

    var text = '';
    var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    for (var i=0; i < n; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }

    return text;
}

/**
 * Function generates a random integer for use in unique IDs, etc
 *
 * @return <int>
 */
function randomInteger(min, max) {
  return Math.random() * (max - min) + min;
}


/**
 * Function replaces "\n" to "<br>"
 *
 * @param <string> str - String raplacement to be performed in
 */
function nl2br(str) {
	return str.replace(/([^>])\n+/g, '$1<br>');
}

/**
 * Function escapes string from xss.
 * Turns html to text.
 *
 * @param <string> str - String of text
 */
function e(str) {
	var div = document.createElement('div');
	var text = document.createTextNode(str);
	div.appendChild(text);
	return div.innerHTML;
}

/**
 * Function check if it's parameter is empty
 *
 * @see php empty()
 *
 * @param <mixed> mixedVar - Value to be checked
 */
function empty(mixedVar) {
	return (	mixedVar === "" ||
				mixedVar === 0 ||
				mixedVar === "0" ||
				mixedVar === null ||
				mixedVar === false ||
				(is_array(mixedVar) && mixedVar.length === 0) ||
				(typeof (mixedVar === 'object') && empty_object(mixedVar))
	);
}

/**
 * Function check if it's parameter is a function
 *
 * @param <mixed> variable - Value to be checked
 */
function is_function(variable) {
	if (typeof variable === 'function') {
		return true;
	}
	return false;
}

/**
 * Function check if given object is empty
 *
 * @param <object> object - Object to be checked
 */
function empty_object(object) {
    var i = 0;
    for (var key in object) {
        ++i;
    }
    return ! Boolean(i);
}

/**
 * Function check if it's parameter is an array
 *
 * @see php is_array()
 *
 * @param <mixed> mixedVar - Value to be checked
 */
function is_array(mixedVar) {
	return (mixedVar instanceof Array);
}

/**
 * Function check if it's parameter is an integer
 *
 * @see php is_int()
 *
 * @param <mixed> mixedVar - Value to be checked
 */
function is_int(mixedVar) {
    return Number(mixedVar) === mixedVar && mixedVar % 1 === 0;
}

/**
 * Function check if it's parameter is an float (double)
 *
 * @see php is_float()
 *
 * @param <mixed> mixedVar - Value to be checked
 */
function is_float(mixedVar){
    return Number(mixedVar) === mixedVar && mixedVar % 1 !== 0;
}

/**
 * Function check if it's parameter is a scalar value
 *
 * @see php is_scalar()
 *
 * @param <mixed> mixedVar - Value to be checked
 */
function is_scalar(mixedVar) {
  return (/boolean|number|string/).test(typeof mixedVar);
}

/**
 * Function check if it's parameter is an object
 *
 * @see php is_object()
 *
 * @param <mixed> mixed_var - Value to be checked
 */
function is_object(mixed_var) {
	// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   improved by: Legaev Andrey
	// +   improved by: Michael White (http://crestidg.com)
	if (mixed_var instanceof Array) {
		return false;
	} else {
		return (mixed_var !== null) && (typeof( mixed_var ) == 'object');
	}
}


/**
 * @see php preg_replace()
 *
 * @param <regExp> regExp - Regular expression
 * @param <scalar> replacement - Value to be replaced with
 * @param <string> subject - Where to replace
 *
 * @return <array>
 */
function preg_replace(regExp, replacement, subject) {
	//	/(?:\r\n?|\n){2,}/
	return subject.replace(regExp, replacement);
}

/**
 * Function escapes string, replaces more than 2 "\r\n" to "<br><br>"
 *
 * @param <string> string - String to be precessed
 *
 * @return <string>
 */
function escape(string) {
	return nl2br(preg_replace(/(?:\r\n?|\n){2,}/, '<br><br>', e(string)));
}

/**
 * Function complete array with sorted numbers
 *
 *	@example [1,4,6] -> [1,2,3,4,5,6]
 *
 * @return <array>
 */
function completeSortedArray(array) {
	var arrayResult = [];
	var array = getUnique(array);
		array = array.sort(function(a, b) {
			return a - b;
		});

	for (var i = 0; i < array.length; i++) {
		if (undefined !== array[i+1]) {
			var nextValue = array[i+1];
			var currValue = array[i];

			if (currValue == nextValue) {
				arrayResult.push(currValue);
			} else {
				arrayResult.push(currValue);
				for (var d = currValue + 1; d < nextValue; d++) {
					arrayResult.push(d);
				}
			}
		}
	};
	arrayResult.push(array[array.length-1]);
	return arrayResult;
}

/**
 * Function detects if it is used mobile device
 *
 * @return <boolean>
 */
function isMobile() {
	var mobile = false;
	if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
    	|| /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {
		mobile = true;
	} else {
		mobile = false;
	}

	return mobile;
}

/**
 * 
 *
 * @param 
 * @param 
 *
 * @return <mixed>
 */
function enableCss(href, root) {
	var current = null;
	
	if (current = checkCss(href, root)) {
		if ($(current).prop('disabled') == true) {
			$(current).prop('disabled', false);
			
			return current;
		}
	}

	return false;
}

function enableCssCollection(collection) {
	var current = null;
	var returnCollection = [];

	$.each(collection, function(index, object) {
		if (current = enableCss(object.href, object.root ? object.root : '')) {
			returnCollection.push(current);
		}
	});

	return returnCollection;
}

/**
 * Remove css file
 *
 * @param params [href] - link to css file
 * @param params [appendNode] - what node append to css file
 *
 * @return <void>
 */
function disableCss(href, root) {
	var current = null;
	
	if (current = checkCss(href, root)) {
		$(current).attr('disabled', 'disabled');
		
		return current;
	}

	return false;
}

function disableCssCollection(collection) {
	var current = null;
	var returnCollection = [];

	$.each(collection, function(index, object) {
		if (current = disableCss(object.href, object.root ? object.root : '')) {
			returnCollection.push(current);
		}
	});

	return returnCollection;
}

/**
 * Load css file
 *
 * @param params [href] - link to css file
 *
 * @return <void>
 */
function loadCss(href) {
	var appendNode = 'head';

	$("<link>", {
		rel: "stylesheet",
		type: "text/css",
		href: href
	})
	.appendTo(appendNode);
}

/**
 * Load css file
 *
 * @param collection - array of objects [{href: 'xxx'}]
 *
 * @return <void>
 */
function loadCssCollection(collection) {
	$.each(collection, function(index, object) {
		loadCss(object.href);
	});
}

/**
 * Check css file if loaded
 *
 * @param href - link to css file
 * @param root - root prefix, if used absolute path
 *
 * @return <mixed>
 */
function checkCss(href, root) {
	href = $.trim(href);
	root = root ? $.trim(root) : '';

	var result = $("link[href='" + removeEndSlashes(root) + '/' + removeFirstSlashes(href) + "']");

	if (result.size() > 0) {
		return result[0];
	}

	// O_o
	if (result.size() > 1) {
		throw new Error('Unexpected behavior.');
	}

	return false;
}

/**
 * Check css files array if loaded any
 *
 * @param collection - collection of objects [{href: "aa", root: ""}]
 *
 * @return <array>
 */
function checkCssCollection(collection) {
	var current = null;
	var returnCollection = [];

	$.each(collection, function(index, object) {
		if (current = checkCss(object.href, object.root ? object.root : '')) {
			returnCollection.push(current);
		}
	});

	return returnCollection;
}


/**
 * Remove end slashes
 *
 * @param string - String
 *
 * @return <string>
 */
function removeEndSlashes(string) {
	return string.replace(/\/+$/g, '').toString();
}

/**
 * Remove first slashes
 *
 * @param string - String
 *
 * @return <string>
 */
function removeFirstSlashes(string) {
	return string.replace(/^\/+/g, '').toString();
}

/**
 * Remove first and end slashes
 *
 * @param string - String
 *
 * @return <string>
 */
function removeSlashes(string) {
	var string = removeFirstSlashes(string);
		string = removeEndSlashes(string);

	return string;
}




function throttle(func, ms) {

  var isThrottled = false,
    savedArgs,
    savedThis;

  function wrapper() {

    if (isThrottled) { // (2)
      savedArgs = arguments;
      savedThis = this;
      return;
    }

    func.apply(this, arguments); // (1)

    isThrottled = true;

    setTimeout(function() {
      isThrottled = false; // (3)
      if (savedArgs) {
        wrapper.apply(savedThis, savedArgs);
        savedArgs = savedThis = null;
      }
    }, ms);
  }

  return wrapper;
}

function debounce(func, wait, immediate) {
	var timeout;
	return function() {
		var context = this, args = arguments;
		var later = function() {
			timeout = null;
			if (!immediate) func.apply(context, args);
		};
		var callNow = immediate && !timeout;
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
		if (callNow) func.apply(context, args);
	};
}

/**
 * Function searches for a specified value within an array
 *
 * @return <boolean>
 */
function inArray(needle, haystack, strict) {
	var found = false, key, strict = !!strict;

	for (key in haystack) {
		if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
			found = true;
			break;
		}
	}

	return found;
}

/**
 * Function escapes string, replaces more than 2 "\r\n" to "<br><br>"
 *
 * @deprecated. Use function "escape" instead.
 *
 * @return <string>
 */

//	String.prototype.escape = function() {
//		return nl2br(preg_replace(/(?:\r\n?|\n){2,}/, '<br><br>', e(this)));
//	}