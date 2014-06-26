// ms-general.js
function hide_id(id)
{
    if(document.getElementById(id))
	{
		document.getElementById(id).style.display ='none';
	}
}
function show_id(id)
{
    if(document.getElementById(id))
	{
    	document.getElementById(id).style.display ='';
	}
}
function redirect_to(add)
{
	window.location=add;
}

// ajax.js
function createXMLHttpRequest()
{
	if (window.ActiveXObject)
	{
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	else if (window.XMLHttpRequest)
	{
		xmlHttp = new XMLHttpRequest();
	}
}

// search.js

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
	var queryString = 'term='+idglb;//+'&csrf_test_name='+document.getElementsByName('csrf_test_name')[0].value;
	xmlHttp.open('POST', url, true);
	xmlHttp.onreadystatechange = validHandleStateChange_search;
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
	
	document.getElementById('search_input').className = class_name + ' ui-autocomplete-loading';
	
	xmlHttp.send(queryString);
}
function validHandleStateChange_search()
{
	if (xmlHttp.readyState == 4)
	{
		if (xmlHttp.status == 200)
		{
			validParseResults_search();
		}
		else
		{
			if (xmlHttp.status == 404)
			{
				hide_id('search_drop');
			}	
		}
	}
}
function validParseResults_search()
{
	document.getElementById('search_input').className = class_name;
	
	if (xmlHttp.responseText != '')
	{
		tmp_txt = xmlHttp.responseText;
		var arr_response = tmp_txt.split("<li");
		max_srch = arr_response.length-1;
		srch_itm = 0;
		show_id('search_drop');
		
		document.getElementById('search_drop').innerHTML = xmlHttp.responseText;
	}
	else
	{
		hide_id('search_drop');
	}
}

document.getElementById('search_drop').style.top = document.getElementById('search_input').offsetTop+document.getElementById('search_input').offsetHeight+'px';
//document.getElementById('search_drop').style.width = document.getElementById('search_input').offsetWidth+'px';
document.getElementById('search_drop').style.left = document.getElementById('search_input').offsetLeft-36+'px';

function mark_s(id)
{
	document.getElementById(id).style.backgroundColor = '#f66127';
	document.getElementById(id).style.color = '#FFFFFF';
}
function unmark_s(id)
{
	document.getElementById(id).style.backgroundColor = '#a9c3d9';
	document.getElementById(id).style.color = '#FFFFFF';
}
function select_s(id, submit)
{
	if (srch_itm > 0) document.getElementById('search_input').value = document.getElementById(id).getElementsByTagName('span')[0].innerHTML;
	
	str = document.getElementById(id).title;
    hide_id('search_drop');
	
    if (submit==1)
	{
		window.location='/'+str;
    }
}
function navList(action)
{
	if (srch_itm > 0 && srch_itm <= max_srch)
	{
		unmark_s('s'+srch_itm);
	}
	if (srch_itm == 0)
	{
		if (action == 'down') srch_itm++;
		else if (action == 'up') srch_itm = max_srch;
	}
	else if (srch_itm == max_srch)
	{
		if (action == 'down') srch_itm = 1;
		else if (action == 'up') srch_itm--;
	}
	else
	{
		if (action == 'down') srch_itm++;
		else if (action == 'up')
		{
			srch_itm--;
			if (srch_itm == 0) srch_itm = max_srch;
		}
	}
	
	if (srch_itm > 0 && srch_itm <= max_srch)
	{
		mark_s('s'+srch_itm);
	}
}
function getKeyCode(e)
{
	var code;
	if (!e) var e = window.event;
	if (e.keyCode) code = e.keyCode;
	return code;
}
document.getElementById('search_input').onkeypress = function(e)
{
	var key = getKeyCode(e);
	
	if (key == 13) // enter
	{
		select_s('s'+srch_itm, 1);
		return false;
	};	
};
document.getElementById('search_input').onkeyup = function(e)
{
	var key = getKeyCode(e);
	
	switch(key)
	{
		case 13: //enter
			break;			
		case 27: // esc
			hide_id('search_drop');
			break;				
		case 38: // up
			show_id('search_drop');
			navList("up");
			break;
		case 40: // down
			show_id('search_drop');
			navList("down");		
			break;
		default:
			if (document.getElementById('search_input').value)
			{
				doRequestUsingPOST_search();	
			}
			else
			{
				hide_id('search_drop');
			}
			break;
	};
};