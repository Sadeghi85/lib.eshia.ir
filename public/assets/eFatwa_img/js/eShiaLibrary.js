
function hide_id(id)
{
    if(document.getElementById(id))	{
		document.getElementById(id).style.display ='none';
	}
}

function show_id(id)
{
    if(document.getElementById(id))	{
    	document.getElementById(id).style.display ='';
	}
}

function redirect_to(add)
{
	window.location=add;
}

function createXMLHttpRequest()
{
	if (window.XMLHttpRequest) {
		xmlHttp = new XMLHttpRequest();
	}
}

var srch_itm;
var max_srch;
var class_name;
class_name = document.getElementById('search_input').className;

function doRequestUsingPOST_search()
{
	idglb= document.getElementById('search_input').value;
	
	// if (idglb.length < 3)
	// {
		// hide_id('search_drop');
		// return false;
	// }
	
	createXMLHttpRequest();
	var url = '/ajax/search/'+ new Date().getTime();
	var queryString = 'query='+idglb;
	xmlHttp.open('POST', url, true);
	xmlHttp.onreadystatechange = validHandleStateChange_search;
	// Set header so the called script knows that it's an XMLHttpRequest
	xmlHttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
	
	document.getElementById('search_input').className = class_name + ' ui-autocomplete-loading';
	
	xmlHttp.send(queryString);
}

function validHandleStateChange_search()
{
	if (xmlHttp.readyState == 4) {
		if (xmlHttp.status == 200) {
			validParseResults_search();
		} else {
			if (xmlHttp.status == 404) {
				hide_id('search_drop');
			}
		}
	}
}

function validParseResults_search()
{
	document.getElementById('search_input').className = class_name;
	
	if (xmlHttp.responseText != '') {
		tmp_txt = xmlHttp.responseText;
		var arr_response = tmp_txt.split("<li");
		max_srch = arr_response.length-1;
		srch_itm = 0;
		show_id('search_drop');
		
		document.getElementById('search_drop').innerHTML = xmlHttp.responseText;
	} else {
		hide_id('search_drop');
	}
}

document.getElementById('search_drop').style.top = document.getElementById('search_input').offsetTop+document.getElementById('search_input').offsetHeight+'px';
//document.getElementById('search_drop').style.width = document.getElementById('search_input').offsetWidth+'px';
document.getElementById('search_drop').style.left = (document.getElementById('search_input').offsetLeft-36)+'px';

function setStyle(element, value)
{
	// Specific old IE
	if ( document.all ) {
		element.style.setAttribute('cssText', value);
	
	// Modern browser
	} else {
		element.setAttribute('style', value);
	}
}

function mark_s(id)
{
	setStyle(document.getElementById(id), 'background-color: #f66127; color: #ffffff;');
	
	var hilights = document.getElementById(id).querySelectorAll('.hilight_suggestion');
	
	for (var i=0; i<hilights.length; i++) {
		setStyle(hilights[i], 'color: #ffffff;');
	}
}

function unmark_s(id)
{
	setStyle(document.getElementById(id), 'background-color: #a9c3d9; color: #ffffff;');
	
	var hilights = document.getElementById(id).querySelectorAll('.hilight_suggestion');
	
	for (var i=0; i<hilights.length; i++) {
		setStyle(hilights[i], 'color: #f66127;');
	}
}

function select_s(id, submit)
{
	span = document.getElementById(id).getElementsByTagName('span')[0];
	if (srch_itm > 0) document.getElementById('search_input').value = (span.innerText || span.textContent);
	
	str = document.getElementById(id).title;
    hide_id('search_drop');
	
    if (submit == 1) window.location='/'+str;
}

function navList(action)
{
	if (srch_itm > 0 && srch_itm <= max_srch) unmark_s('s'+srch_itm);
	
	if (srch_itm == 0) {
		if (action == 'down') srch_itm++;
		else if (action == 'up') srch_itm = max_srch;
	} else if (srch_itm == max_srch) {
		if (action == 'down') srch_itm = 1;
		else if (action == 'up') srch_itm--;
	} else {
		if (action == 'down') srch_itm++;
		else if (action == 'up') {
			srch_itm--;
			if (srch_itm == 0) srch_itm = max_srch;
		}
	}
	
	if (srch_itm > 0 && srch_itm <= max_srch) mark_s('s'+srch_itm);
}

function getKeyCode(e)
{
	var code;
	if (!e) var e = window.event;
	if (e.keyCode) code = e.keyCode;
	
	return code;
}

document.getElementById('search_input').onkeypress = function(e) {
	var key = getKeyCode(e);
	
	// enter
	if (key == 13) {
		select_s('s'+srch_itm, 1);
		
		return false;
	}	
};

document.getElementById('search_input').onkeyup = function(e) {
	var key = getKeyCode(e);
	
	switch(key) {
		//enter
		case 13:
			break;
		// esc
		case 27:
			hide_id('search_drop');
			break;
		// up
		case 38:
			show_id('search_drop');
			navList("up");
			break;
		// down
		case 40:
			show_id('search_drop');
			navList("down");
			break;
		default:
			if (document.getElementById('search_input').value) {
				doRequestUsingPOST_search();
			} else {
				hide_id('search_drop');
			}
			break;
	}
};
