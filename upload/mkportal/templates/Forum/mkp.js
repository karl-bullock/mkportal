
var agt = navigator.userAgent.toLowerCase();
var agt_ver = parseInt(navigator.appVersion);
var is_mozilla = (navigator.product == "Gecko");
var is_opera = (agt.indexOf("opera") != -1);
var is_konqueror = (agt.indexOf("konqueror") != -1);
var is_webtv = (agt.indexOf("webtv") != -1);
var is_ie = ((agt.indexOf("msie") != -1) && (!is_opera) && (!is_webtv));
var is_netscape = ((agt.indexOf("compatible") == -1) && (agt.indexOf("mozilla") != -1) && (!is_opera) && (!is_webtv));
var is_win = (agt.indexOf("win" != -1));
var is_mac = (agt.indexOf("mac") != -1);

var B_open = 0;
var I_open = 0;
var U_open = 0;
var S_open = 0;
var CENTER_open = 0;
var LEFT_open = 0;
var right_open = 0;
var QUOTE_open = 0;
var CODE_open = 0;
var HIDE_open = 0;
var HTML_open = 0;

var bbtags   = new Array();

function stacksize(thearray) {
    for (i = 0 ; i < thearray.length; i++ ) {
        if ( (thearray[i] == "") || (thearray[i] == null) || (thearray == 'undefined') ) {
            return i;
        }
    }
    return thearray.length;
}

function pushstack(thearray, newval) {
    arraysize = stacksize(thearray);
    thearray[arraysize] = newval;
}

function popstack(thearray) {
    arraysize = stacksize(thearray);
    theval = thearray[arraysize - 1];
    delete thearray[arraysize - 1];
    return theval;
}

function closeall() {
    if (bbtags[0]) {
        while (bbtags[0]) {
            tagRemove = popstack(bbtags)
            document.editor.ta.value += "[/" + tagRemove + "]";
            if ( (tagRemove != 'FONT') && (tagRemove != 'SIZE') && (tagRemove != 'COLOR') ) { 
                eval("document.editor." + tagRemove + ".value = ' " + tagRemove + " '");
                eval(tagRemove + "_open = 0");
            }
        }
    }
    document.editor.tagcount.value = 0;
    bbtags = new Array();
    document.editor.ta.focus();
}


function add_code(NewCode) {
    document.editor.ta.value += NewCode;
    document.editor.ta.focus();
}

function alterfont(theval, thetag) {
    if (theval == 0)
        return;
    if(doInsert("[" + thetag + "=" + theval + "]", "[/" + thetag + "]", true))
        pushstack(bbtags, thetag);
    document.editor.ffont.selectedIndex  = 0;
    document.editor.fsize.selectedIndex  = 0;
    document.editor.fcolor.selectedIndex = 0;
}

function simpletag(thetag) {
    var tagOpen = eval(thetag + "_open");

    if (tagOpen == 0) {
        if(doInsert("[" + thetag + "]", "[/" + thetag + "]", true))
        {
            eval(thetag + "_open = 1");
            eval("document.editor." + thetag + ".value += '*'");

            pushstack(bbtags, thetag);
        }
    }
    else {
        // Find the last occurance of the opened tag
        lastindex = 0;

        for (i = 0 ; i < bbtags.length; i++ )
        {
            if ( bbtags[i] == thetag )
            {
                lastindex = i;
            }
        }

        // Close all tags opened up to that tag was opened
        while (bbtags[lastindex])
        {
            tagRemove = popstack(bbtags);
            doInsert("[/" + tagRemove + "]", "", false);
            if ( (tagRemove != 'FONT') && (tagRemove != 'SIZE') && (tagRemove != 'COLOR') ) {
                eval("document.editor." + tagRemove + ".value = ' " + tagRemove + " '");
                eval(tagRemove + "_open = 0");
            }
        }
    }
}


function tag_list() {
    var listvalue = "init";
    var thelist = "";
    while ( (listvalue != "") && (listvalue != null) ) {
        listvalue = prompt(list_prompt, "");
        if ( (listvalue != "") && (listvalue != null) ) {
            thelist = thelist+"[*]"+listvalue+"\n";
        }
    }
    if ( thelist != "" ) {
        doInsert( "[LIST]\n" + thelist + "[/LIST]\n", "", false);
    }
}

function tag_url() {
    var FoundErrors = '';
    var enterURL   = prompt(text_enter_url, "http://");
    var enterTITLE = prompt(text_enter_url_name, "My Webpage");

    if (!enterURL) {
        FoundErrors += " " + error_no_url;
    }
    if (!enterTITLE) {
        FoundErrors += " " + error_no_title;
    }
    if (FoundErrors) {
        alert("Error!"+FoundErrors);
        return;
    }
    doInsert("[URL="+enterURL+"]"+enterTITLE+"[/URL]", "", false);
}


function tag_image() {
    var FoundErrors = '';
    var enterURL   = prompt(text_enter_image, "http://");

    if (!enterURL) {
        FoundErrors += " " + error_no_url;
    }
    if (FoundErrors) {
        alert("Error!"+FoundErrors);
        return;
    }
    doInsert("[IMG]"+enterURL+"[/IMG]", "", false);
}

function tag_email() {
    var emailAddress = prompt(text_enter_email, "");
    if (!emailAddress) {
        alert(error_no_email);
        return;
    }
    doInsert("[EMAIL]"+emailAddress+"[/EMAIL]", "", false);
}

// Meo: fixed for mozilla in 1.3

function doInsert(ibTag, ibClsTag, isSingle) {
    var isClose = false;
    var obj_ta = document.editor.ta;
    if(is_ie && is_win && (agt_ver >= 4)) {
        if(obj_ta.isTextEdit){
            obj_ta.focus();
            var sel = document.selection;
            var rng = sel.createRange();
            rng.collapse;
            if((sel.type == "Text" || sel.type == "None") && rng != null){
                if(ibClsTag != "" && rng.text.length > 0)
                    ibTag += rng.text + ibClsTag;
                else if(isSingle)
                    isClose = true;
                rng.text = ibTag;
            }
        }
        else {
            if(isSingle)
                isClose = true;
            obj_ta.value += ibTag;
        }
    }
    else {
        
        if(is_mozilla && obj_ta.selectionEnd) {
            var length = obj_ta.textLength;
            var start = obj_ta.selectionStart;
            var end = obj_ta.selectionEnd;
			var sct = obj_ta.scrollTop;
            var head = obj_ta.value.substring(0,start);
            var rng = obj_ta.value.substring(start, end);
            var tail = obj_ta.value.substring(end, length);
            if( start != end ){
                if (ibClsTag != "" && length > 0)
                    ibTag += rng + ibClsTag;
                else if (isSingle)
                    isClose = true;
                rng = ibTag;
                obj_ta.value = head + rng + tail;
                start = start + rng.length;
            }
            else{
                if(isSingle)
                    isClose = true;
                obj_ta.value = head + ibTag + tail;
                start = start + ibTag.length;
            }
            obj_ta.selectionStart = start;
            obj_ta.selectionEnd = start;
			obj_ta.scrollTop = sct;
        }
        else {
            if(isSingle)
                isClose = true;
            obj_ta.value += ibTag;
        }
    }
    obj_ta.focus();
    return isClose;
}

function getCookie(name) {
  var dc = document.cookie;
  var prefix = name + "=";
  var begin = dc.indexOf("; " + prefix);
  if (begin == -1) {
    begin = dc.indexOf(prefix);
    if (begin != 0) return null;
  } else
    begin += 2;
  var end = document.cookie.indexOf(";", begin);
  if (end == -1)
    end = dc.length;
  return unescape(dc.substring(begin + prefix.length, end));
}

function getObj(name)
{
  if (document.getElementById)
  {
    if(document.getElementById(name))
      return document.getElementById(name);
    else
      return false;
  }
  else if (document.all)
  {
	if (document.all[name])
      return document.all[name];
    else
      return false;
  }
  else if (document.layers)
  {
    if (document.layers[name])
      return document.layers[name];
    else
      return false;
  }
}

function ColumnClose(currMenu) {
	Mclose = 'menucloseds';
	Mcontent= 'menucontents';
	if (currMenu == 'menudx') {
			Mclose = 'menuclosedr';
			Mcontent = 'menucontentr';
	}

  holder = getObj(currMenu)
  if( holder ){

    if (typeof(window.opera) == 'undefined'
        && typeof(holder.getAttribute) != 'undefined') {
        if (holder.getAttribute("className")) {
            holder.setAttribute("className", Mclose);
        } else {
            holder.setAttribute("class", Mclose);
        }
    }
    else {
        holder.setAttribute("class", Mclose);
    }

    obj = getObj(Mcontent);
    if(obj) obj.style.display = 'none';

    obj = getObj(Mclose);
    if(obj) obj.style.display = '';
  }
}
function ColumnOpen(currMenu) {
  	Mclose = 'menucloseds';
	Mcontent= 'menucontents';
	if (currMenu == 'menudx') {
			Mclose = 'menuclosedr';
			Mcontent = 'menucontentr';
	}
  holder = getObj(currMenu)
  if( holder ){


    if (typeof(window.opera) == 'undefined'
        && typeof(holder.getAttribute) != 'undefined') {
        if (holder.getAttribute("className")) {
            holder.setAttribute("className", currMenu);
        } else {
            holder.setAttribute("class", currMenu);
        }
    }
    else {
        holder.setAttribute("class", currMenu);
    }


    obj = getObj(Mcontent);
    if(obj) obj.style.display = '';

    obj = getObj(Mclose);
    if(obj) obj.style.display = 'none';
  }
}


function MemoPos(name, value) {
   var expire=new Date();
   expire=new Date(expire.getTime()+7776000000);
   document.cookie=  name + "=" +value + "; expires="+expire+"; path=/";

}

function GetPos() {
	var resultsx = getCookie('MKmenusx');
	var resultdx = getCookie('MKmenudx');
	//document.write(result);
	if (resultsx == 1)
	ColumnClose('menusx');
	if (resultdx == 1)
	ColumnClose('menudx');


}

// Meo: added in C 0.1.b
// General utility Functions called
var MkUtilsLib = {

	getPageScroll: function()
	{
		var yScroll;
		if(self.pageYOffset)
		{
			yScroll = self.pageYOffset;
		}
		else if(document.documentElement && document.documentElement.scrollTop) // Explorer 6
		{
			yScroll = document.documentElement.scrollTop;
		}
		else if(document.body) // all other Explorers
		{
			yScroll = document.body.scrollTop;
		}
		arrayPageScroll = new Array('',yScroll);
		return arrayPageScroll;
	},

	getPageSize: function()
	{
		var xScroll, yScroll;
	
	if (window.innerHeight && window.scrollMaxY) {	
		xScroll = document.body.scrollWidth;
		yScroll = window.innerHeight + window.scrollMaxY;
	} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
		xScroll = document.body.scrollWidth;
		yScroll = document.body.scrollHeight;
	} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
		xScroll = document.body.offsetWidth;
		yScroll = document.body.offsetHeight;
	}
	
	var windowWidth, windowHeight;
	if (self.innerHeight) {	// all except Explorer
		windowWidth = self.innerWidth;
		windowHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
		windowWidth = document.documentElement.clientWidth;
		windowHeight = document.documentElement.clientHeight;
	} else if (document.body) { // other Explorers
		windowWidth = document.body.clientWidth;
		windowHeight = document.body.clientHeight;
	}	
	
	// for small pages with total height less then height of the viewport
	if(yScroll < windowHeight){
		pageHeight = windowHeight;
	} else { 
		pageHeight = yScroll;
	}

	// for small pages with total width less then width of the viewport
	if(xScroll < windowWidth){	
		pageWidth = windowWidth;
	} else {
		pageWidth = xScroll;
	}

	arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight) 
	return arrayPageSize;
	}

}

// Ajax Spinner loading indicator
var objMkspinner = false;
function mkportal_Spinner_Show() {
	if(!objMkspinner) {
		var image = MKAJAX_IMAGES_PATH + "loadspin.gif";;
		var height = 180;
		var width = 180;
		objMkspinner = document.createElement("div");
		objMkspinner.style.position = "absolute";
		objMkspinner.style.zIndex = 1000;
		objMkspinner.style.textAlign = "center";
		objMkspinner.style.verticalAlign = "middle";
		objMkspinner.innerHTML = "<div style=\"text-align: center; border:2px solid #698490; padding: 6px; background: #FFF;\"><br /><img src=\"" + image + "\" border=\"\"><br /><br /><b>... Loading... </b><br /></div>";
		objMkspinner.style.width = width + "px";
		objMkspinner.style.height = height + "px";
		objMkspinner.style.display = 'none';
		objMkspinner.id = "mkspinner";
		var owner = document.getElementsByTagName("body").item(0);
		owner.insertBefore(objMkspinner, owner.firstChild);
	}
	var arrayPageSize = MkUtilsLib.getPageSize();
	var arrayPageScroll = MkUtilsLib.getPageScroll();
	var top = arrayPageScroll[1] + ((arrayPageSize[3] - 35 - 180) / 2);
	var left = ((arrayPageSize[0] - 20 - 180) / 2);
	objMkspinner.style.top = top + "px";
	objMkspinner.style.left = left + "px";
	objMkspinner.style.display = 'block';
}

function mkportal_Spinner_Hide() {
 	document.getElementById('mkspinner').style.display = 'none';
}

// Ajax Core Engine
function MKP_ajax(url, options) {	

	var mka_postData = options.postBody || '';
	var mka_method = options.method || 'post';
	var mka_Complete = options.onComplete || null;
	var mka_update = options.update || null;
	var mka_sendReq = Mka_getXmlHttpRequestObject();

	function Mka_returnOut() {

		if (mka_sendReq.readyState == 4 && mka_sendReq.status == 200) {
			if (mka_Complete) 
				setTimeout(function(){mka_Complete(mka_sendReq);}, 10);
			if (mka_update)
				setTimeout(function(){mka_update.innerHTML = mka_sendReq.responseText;}, 10);
			mka_sendReq.onreadystatechange = function(){};
		}

	}

	function Mka_start(url){

		mka_sendReq.open(mka_method, url, true);
		mka_sendReq.onreadystatechange = Mka_returnOut;
		if (mka_method == 'post') {
			mka_sendReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			if (mka_sendReq.overrideMimeType) mka_sendReq.setRequestHeader('Connection', 'close');
		}
		mka_sendReq.send(mka_postData);

	}
	function Mka_getXmlHttpRequestObject() {
		if(window.XMLHttpRequest) {	return new XMLHttpRequest(); }
		else if(window.ActiveXObject) {
			try { req = new ActiveXObject('Msxml2.XMLHTTP.4.0'); } catch(e) { try {	req = new ActiveXObject('Microsoft.XMLHTTP'); } catch(e) {req = false; }} return req; }
		else {	return false; }
	}
	
	Mka_start(url);
};
function SwitchMenu(obj) {
	if (document.getElementById) {
		var el = document.getElementById(obj);
		var ar = document.getElementById("cont").getElementsByTagName("div");
		if (el.style.display == "none") {
			for (var i=0; i<ar.length; i++) {
				ar[i].style.display = "none";
			}
			el.style.display = "block";
		} else {
			el.style.display = "none";
		}
	}
}
function Switch_InstEd()  {
	var curstate = document.getElementById('instchateditor').style.display;
	if (curstate == 'none') {
		document.getElementById('instchatcontent').style.display = 'none';
		document.getElementById('instchateditor').style.display = '';
		document.editor2.ta2.focus();
	} else {
        	document.getElementById('instchateditor').style.display = 'none';
		document.getElementById('instchatcontent').style.display = '';
    	}
}
function ajaxchatSave() {
	//MK_Spinner = new MKSpinner();
	mkportal_Spinner_Show();
	var url =  MKAJAX_SITEPATH + 'index.php?ind=urlobox&op=ajax_save';
        message =document.getElementById('ta2').value;
        if(message == "")
        {
            return false;
        }
        postData = "value="+encodeURIComponent(message).replace(/\+/g, "%2B");
      new MKP_ajax(url, {method: 'post', postBody: postData, onComplete: function(request) { AjaxchatSaveComplete(request); }});
    }
function AjaxchatSaveComplete(request) {
        if(request.responseText.match(/<error>(.*)<\/error>/)) {
            message = request.responseText.match(/<error>(.*)<\/error>/);
            if(!message[1]) {
                message[1] = "An unknown error occurred.";
            }
            alert("There was an error performing the update."+message[1]);
        }
        else if(request.responseText)
        {
            document.getElementById('instchat_2').innerHTML = request.responseText;
             document.getElementById('ta2').value = "";
        }
	mkportal_Spinner_Hide();
}

function ajaxchatSubmit() {
	ajaxchatSave();
	Switch_InstEd();
	return false;
}
function CreateRequest()
{
	var Request = false;
	if (window.XMLHttpRequest)
	{
		Request = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		Request = new ActiveXObject("Microsoft.XMLHTTP");
	
		if (!Request)
		{
			HRequest = new ActiveXObject("Msxml2.XMLHTTP");
		}
	}
 
	if (!Request)
	{
		alert("Eroor XMLHttpRequest");
	}
	
	return Request;
}
function SendRequest(r_method, r_path, r_args, r_handler)
{
	mkportal_Spinner_Show();
	var Request = CreateRequest();
	if (!Request)
	{
		return;
	}
	Request.onreadystatechange = function()
	{
		if (Request.readyState == 4)
		{
			r_handler(Request);
			objMkspinner.style.display = 'none';
			
		}
	}
	if (r_method.toLowerCase() == "get" && r_args.length > 0)
	r_path += "?" + r_args;
	Request.open(r_method, r_path, true);
	
	if (r_method.toLowerCase() == "post")
	{
		Request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=utf-8");
		Request.send(r_args);
	}
	else
	{

		Request.send(null);

	}
}


function refresh_online()
{
	var url =  MKAJAX_SITEPATH + 'index.php?ind=rajax&op=refresh_online';
	var Handler = function(Request)
	{
		sendglob("onlines").innerHTML = Request.responseText;
	}
	SendRequest("post",url,"",Handler);
}

function sendglob(elementid)
{ 
	return document.getElementById(elementid);
	
}

function sendRecommend()
{   
	var url =  MKAJAX_SITEPATH + 'index.php?ind=recommend&op=send_mail';
    var Handler = function(Request)
    {
       $("conta").innerHTML = Request.responseText;
    }
    r_args = "";
    form = document.getElementById("forms");
    for(i=0; i<form.elements.length; i++)
    {
        r_args += "&" +form.elements[i].name + "=" + form.elements[i].value
    }
    SendRequest("post",url,r_args,Handler);
  
}
function sendContact()
{   
	var url =  MKAJAX_SITEPATH + 'index.php?ind=contact&op=send_mail';
    var Handler = function(Request)
    {
       $("contact").innerHTML = Request.responseText;
    }
    r_args = "";
    form = document.getElementById("forms");
    for(i=0; i<form.elements.length; i++)
    {
        r_args += "&" +form.elements[i].name + "=" + form.elements[i].value
    }
    SendRequest("post",url,r_args,Handler);
  
}
function sendcomentnews()
{   
	var url =  MKAJAX_SITEPATH + 'index.php?ind=news&op=ajax_comment';
    var Handler = function(Request)
    {
       sendglob("comments").innerHTML = Request.responseText;
    }
    r_args = "";
    form = document.getElementById("editor");
    for(i=0; i<form.elements.length; i++)
    {
        r_args += "&" +form.elements[i].name + "=" + form.elements[i].value
    }
    SendRequest("post",url,r_args,Handler);
  
}
function rate(rating,id,modname)
{   
	var url =  MKAJAX_SITEPATH + 'index.php?ind=rajax&op=rating_process';
	var params = "id="+id+"&rating="+rating+"&modname="+modname;
    var Handler = function(Request)
    {
       sendglob('loading_'+id).innerHTML = Request.responseText;
    }
    
    SendRequest("post",url,params,Handler);
  
}
function sendcomentreviews()
{   
	var url =  MKAJAX_SITEPATH + 'index.php?ind=reviews&op=ajax_comment';
    var Handler = function(Request)
    {
       sendglob("commentsr").innerHTML = Request.responseText;
    }
    r_args = "";
    form = document.getElementById("editor");
    for(i=0; i<form.elements.length; i++)
    {
        r_args += "&" +form.elements[i].name + "=" + form.elements[i].value
    }
    SendRequest("post",url,r_args,Handler);
  
}
function sendvoting()
{   
	var url =  MKAJAX_SITEPATH + 'index.php?ind=poll&op=ajax_voting';
    var Handler = function(Request)
    {
       sendglob("pollresult").innerHTML = Request.responseText;
    }
    r_args = "";
    form = document.getElementById("voting");
    for(i=0; i<form.elements.length; i++)
    {
        r_args += "&" +form.elements[i].name + "=" + form.elements[i].value
    }
    for(i = 0; i < form.elements['var'].length; i++)
    {
        if (form.elements['var'][i].checked)
            r_args += "&" +form.elements['var'][i].name + "=" + form.elements['var'][i].value
    } 
    SendRequest("post",url,r_args,Handler);
  
}
