/**********************************************************************************************
| Mkportal Ajax Boxes & Tips
| by Luponero aka Meo oct 2007
|
|----------------------------
| Based on Original Ideas:
| by DTHMLGoodies.com, Alf Magne Kalleland
| by Lokesh Dhakar - http://www.huddletogether.com
*************************************************************************************************/	


//Meo: Mkportal settings ----------------------------
var BalloonImg = MKAJAX_IMAGES_PATH + "freccia.gif";
var BalloonImg2 = MKAJAX_IMAGES_PATH + "frecciad.gif";
var LoadingImg = MKAJAX_IMAGES_PATH + "loadspin.gif";
var OverlayImg = MKAJAX_IMAGES_PATH + "overlay.png";
var CloseImg = MKAJAX_IMAGES_PATH + "closepl.gif";

//Meo: General settings
var popshowdelay = 800;
var tipshowdelay = 400;

// Meo: Inizialization
var objOverlay = false;
var objLightbox = false;
var ajax_tooltipObj = false;
var ajax_tooltipObj_iframe = false;
var ajax_tooltip_MSIE = false;
var ajax_tooltip_Myo = "";
if(navigator.userAgent.indexOf('MSIE')>=0)ajax_tooltip_MSIE=true;


/********************** Meo ShowPop ********************************/

function ajax_showPop(externalFile, delayno)
{
// Meo: Clear all the external calling ant timeouts delay
	ajaxPop_clear();

	if(!objOverlay)
	{
		var objBody = document.getElementsByTagName("body").item(0);
		objOverlay = document.createElement("div");
		objOverlay.setAttribute('id','mkoverlay');
		objOverlay.onclick = function () {ajax_hideLightbox(); return false;}
		objOverlay.style.position = 'absolute';
		objOverlay.style.display = 'none';
		objOverlay.style.top = '0';
		objOverlay.style.left = '0';
		objOverlay.style.zIndex = '90';
 		objOverlay.style.width = '100%';

		//Meo: This method seems to be very much faster than using a png background
		objOverlay.style.backgroundColor = '#111';
		objOverlay.style.opacity = '.80';
		objOverlay.style.filter = 'alpha(opacity=80)'; 
		objOverlay.style.MozOpacity = '0.8';
		objBody.insertBefore(objOverlay, objBody.firstChild);
		
		objLightbox = document.createElement("div");
		objLightbox.setAttribute('id','mklightbox');
		objLightbox.style.display = 'none';
		objLightbox.style.position = 'absolute';
		objLightbox.style.zIndex = '100';
		
		objLightbox.style.border = '1px solid #698490';
		objLightbox.style.backgroundColor = '#f1f6fb';
		objLightbox.style.padding = '4px';
		objLightbox.style.fontSize = '0.8em';

		objBody.insertBefore(objLightbox, objOverlay.nextSibling);

		var objLink = document.createElement("a");
		objLink.setAttribute('href','#');
		objLink.setAttribute('title','Click to close');
		objLink.onclick = function () {ajax_hideLightbox(); return false;}
		objLink.style.textAlign = 'right';
		objLightbox.appendChild(objLink);

		var objCloseButton = document.createElement("img");
		objCloseButton.src = CloseImg;
		objCloseButton.setAttribute('id','mkcloseButton');
		objCloseButton.style.position = 'absolute';
		objCloseButton.style.zIndex = '200';
		objLink.appendChild(objCloseButton);

		var objContent = document.createElement("div");
		objContent.setAttribute('id','mklightboxContent');
		objContent.style.overflow = 'auto';
		objLightbox.appendChild(objContent);


	}
		
	var arrayPageSize = MkUtilsLib.getPageSize();
	var arrayPageScroll = MkUtilsLib.getPageScroll();

	objOverlay.style.width = (arrayPageSize[0] + 'px');
	objOverlay.style.height = (arrayPageSize[1] + 'px');
			
//Meo: Reset runtime changed values
	document.getElementById('mklightboxContent').style.height = "";
	document.getElementById('mklightboxContent').style.width = "";
	objLightbox.style.width =  "";
	objLightbox.style.height = "";
	
// Meo: immediate or delay Calling?
	if (delayno != null){
		ajax_showPop2(externalFile);
	} else {
		delayshow=setTimeout("ajax_showPop2('" + externalFile + "')",popshowdelay);
	}

}

function ajax_showPop2(externalFile)
{
	mkportal_Spinner_Show();
	objOverlay.style.display = 'block';
	objLightbox.style.display = 'none';
	ajaxPopup_loadContent(externalFile);

}

function ajax_hideLightbox()
{
	document.getElementById('mkoverlay').style.display = 'none';
	document.getElementById('mklightbox').style.display = 'none';
	mkportal_Spinner_Hide();
	if (typeof delayshow!="undefined")
		clearTimeout(delayshow);
}


/********************** Meo ShowToolTip ********************************/
function ajax_showTooltip(externalFile,inputObj,delayno)
{
	// Meo: Clear all the external calling ant timeouts delay
	ajaxPop_clear();
	 
	if(!ajax_tooltipObj)
	{
		ajax_tooltipObj = document.createElement('DIV');
		ajax_tooltipObj.style.position = 'absolute';
		ajax_tooltipObj.style.zIndex = '1000000';
		ajax_tooltipObj.style.textAlign = 'left';
		ajax_tooltipObj.id = 'ajax_tooltipObj';			
		document.body.appendChild(ajax_tooltipObj);

		var leftDiv = document.createElement('DIV');
		leftDiv.setAttribute('id','ajax_tooltip_arrow');
		leftDiv.style.backgroundImage = "url('" + BalloonImg + "')";
		leftDiv.style.width = '28px';
		leftDiv.style.position = 'absolute';
		leftDiv.style.left = '0px';
		leftDiv.style.top = '20px';
		leftDiv.style.zIndex = '1000005';
		leftDiv.style.height = '26px';
		leftDiv.style.backgroundRepeat = 'no-repeat';
		leftDiv.style.backgroundPosition = 'center left';
		ajax_tooltipObj.appendChild(leftDiv);
		
		
		var contentDiv = document.createElement('DIV');
		contentDiv.style.border = '1px solid #698490';
		contentDiv.style.left = '-20px';
		contentDiv.style.top = '45px';
		contentDiv.style.position = 'absolute';
		contentDiv.style.backgroundColor = '#f1f6fb';
		contentDiv.style.padding = '5px';
		contentDiv.style.fontSize = '0.8em';
		contentDiv.style.zIndex = '1000001';

		ajax_tooltipObj.appendChild(contentDiv);
		contentDiv.id = 'ajax_tooltip_content';
		
		if(ajax_tooltip_MSIE){
			ajax_tooltipObj_iframe = document.createElement('<IFRAME frameborder="0">');
			ajax_tooltipObj_iframe.style.position = 'absolute';
			ajax_tooltipObj_iframe.border='0';
			ajax_tooltipObj_iframe.frameborder=0;
			ajax_tooltipObj_iframe.style.backgroundColor='#FFF';
			ajax_tooltipObj_iframe.src = 'about:blank';
			contentDiv.appendChild(ajax_tooltipObj_iframe);
			ajax_tooltipObj_iframe.style.left = '0px';
			ajax_tooltipObj_iframe.style.top = '0px';
		}	
	}
	
	if(ajax_tooltip_MSIE){
		ajax_tooltipObj_iframe.style.width = ajax_tooltipObj.clientWidth + 'px';
		ajax_tooltipObj_iframe.style.height = ajax_tooltipObj.clientHeight + 'px';
	}

	ajax_tooltipObj.style.display='none';
	
	// Meo: delay function
	ajax_tooltip_Myo = inputObj;

	//Immediate or delay Calling?
	delayno = (typeof delayno == 'undefined') ? 0 : delayno;	
	if (delayno == 1) { //Help Popups
		ajax_tooltipObj.style.visibility = 'hidden';
		ajaxTooltip_loadContent(externalFile);

	} else {
		delayshow=setTimeout("ajaxTooltip_loadContent('" + externalFile + "')",tipshowdelay);
	}

}

function ajax_hideTooltip()
{
	if(ajax_tooltipObj)
	{	
	ajax_tooltipObj.style.display='none';
	if (typeof delayshow!="undefined")
		clearTimeout(delayshow);
	}
}

function ajax_positionTooltip(inputObj)
{

//Meo: Reset runtime changed values
	ajax_tooltipObj.style.left = '';
	ajax_tooltipObj.style.top = '';
	document.getElementById('ajax_tooltip_arrow').style.backgroundImage = "url('" + BalloonImg + "')";
	document.getElementById('ajax_tooltip_arrow').style.left = '0px';
	document.getElementById('ajax_tooltip_arrow').style.top = '20px';
	document.getElementById('ajax_tooltip_content').style.width = "";
	ajax_tooltipObj.style.width = "";
	ajax_tooltipObj.style.height = "";

	var leftPos = (ajaxTooltip_getLeftPos(inputObj) + inputObj.offsetWidth);
	var topPos = ajaxTooltip_getTopPos(inputObj);
	var myw = document.getElementById('ajax_tooltip_content').offsetWidth;
	if (myw < 1) myw = 150;
	var myh = document.getElementById('ajax_tooltip_content').offsetHeight;
	if (myh < 1) myh = 150;

// Meo: outscreen correction x
	var arrayPageSize = MkUtilsLib.getPageSize();
	var arrayPageScroll = MkUtilsLib.getPageScroll();
	var maxw = arrayPageSize[0];
	if ((myw + leftPos) > maxw) {
		var mydiff = (myw + leftPos) - maxw;
		var arrowdiff = 10;
		if (ajax_tooltip_MSIE == true) arrowdiff = 20;
		leftPos = leftPos - (mydiff +5);
		document.getElementById('ajax_tooltip_arrow').style.left = (mydiff - arrowdiff) +'px';
	}
// Meo: outscreen correction y
	var maxy = ((arrayPageScroll[1] + arrayPageSize[3]) -5);
	if ((topPos + myh + 45) > maxy) {
		topPos = topPos - (myh +67);
		document.getElementById('ajax_tooltip_arrow').style.backgroundImage = "url('" + BalloonImg2 + "')";
		document.getElementById('ajax_tooltip_arrow').style.top = myh + 44 + "px";
	}

	ajax_tooltipObj.style.left = leftPos + 'px';
	ajax_tooltipObj.style.top = topPos + 'px';

}


function ajaxTooltip_getTopPos(inputObj)
{		
  	var returnValue = inputObj.offsetTop;
  	while((inputObj = inputObj.offsetParent) != null){
  		if(inputObj.tagName!='HTML')returnValue += inputObj.offsetTop;
  	}
  	return returnValue;
}

function ajaxTooltip_getLeftPos(inputObj)
{
  	var returnValue = inputObj.offsetLeft;
  	while((inputObj = inputObj.offsetParent) != null){
  		if(inputObj.tagName!='HTML')returnValue += inputObj.offsetLeft;
  	}
  	return returnValue;
}

// Meo: clear the timeouts
function ajaxPop_clear(){

	if (typeof delayshow!="undefined")
		clearTimeout(delayshow);
}


/*
**********************MEO AJAX INTEGRATION******************************
*/

//----------Meo: Popup Box -------------
function ajaxPopup_loadContent(url)
{
	new MKP_ajax(url, {method: 'get',  onComplete: function(request) { ajaxPopup_showContent(request); }});	
}

function ajaxPopup_showContent(request)
{
	mkportal_Spinner_Hide();
	objLightbox.style.display = 'block';
	objLightbox.style.visibility='hidden';
	
	document.getElementById('mklightboxContent').innerHTML = request.responseText;
	var arrayPageSize = MkUtilsLib.getPageSize();
	var arrayPageScroll = MkUtilsLib.getPageScroll();
	var myw = document.getElementById('mklightboxContent').offsetWidth;
	var myh = document.getElementById('mklightboxContent').offsetHeight;


	if (myh > arrayPageSize[3]) {
		myh = myh -170;
		document.getElementById('mklightboxContent').style.height = myh + "px";
		objLightbox.style.height = myh + 'px';		
	}

	var lightboxTop = arrayPageScroll[1] + ((arrayPageSize[3] - 20 - myh) / 2);
	var lightboxLeft = ((arrayPageSize[0] - 20 - myw) / 2);

	objLightbox.style.width =  myw + 'px';
	objLightbox.style.top = (lightboxTop < 0) ? "0px" : lightboxTop + "px";
	objLightbox.style.left = (lightboxLeft < 0) ? "0px" : lightboxLeft + "px";

	document.getElementById('mkcloseButton').style.top = "8px";	
	document.getElementById('mkcloseButton').style.left = (myw -28) + "px";
	objLightbox.style.visibility='visible';

}

//-------------Meo: ToolTip---------------
function ajaxTooltip_loadContent(url)
{
	ajax_tooltipObj.style.display='block';
	document.getElementById('ajax_tooltip_content').innerHTML = "<div style=\"text-align: center; border:1px solid #698490; padding: 6px; background: #FFF; color: #666;\"><br /><img src=\"" + LoadingImg + "\" border=\"\"><br /><br /><b>...Loading...</b><br /></div>";
	ajax_positionTooltip(ajax_tooltip_Myo);
	new MKP_ajax(url, {method: 'get',  onComplete: function(request) { ajaxTooltip_showContent(request); }});	
	
}

function ajaxTooltip_showContent(request)
{
	document.getElementById('ajax_tooltip_content').innerHTML = request.responseText;
	ajax_tooltipObj.style.visibility='hidden';
	ajax_positionTooltip(ajax_tooltip_Myo);
	ajax_tooltipObj.style.visibility='visible';
}


