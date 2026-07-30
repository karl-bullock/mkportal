
var textarea;
var content;
document.write("<link href=\"mkportal/editor/bbeditor/styles.css\" rel=\"stylesheet\" type=\"text/css\">");

function tag_email(obj) {
    var emailAddress = prompt(text_enter_email, "");
    if (!emailAddress) {
        alert(error_no_email);
        return;
    }
    doAddTags("[EMAIL]"+emailAddress+"[/EMAIL]", "", obj);
}

function flash(obj)
{
	
    var FoundErrors = '';
    var enterURL   = prompt(text_enter_flash, "http://");

    var size = prompt(text_enter_size, "425,264");

    if (!enterURL) {
        FoundErrors += " " + error_no_url;
    }

    if (FoundErrors) {
        alert("Error!"+FoundErrors);
        return;
    }


	doAddTags("[flash="+size+"]"+enterURL+"[/flash]", "", obj);

}
function youtube(obj)
{   
	var YErrors = '';
    var youtubeURL   = prompt(text_enter_youtube, "http://");

    if (!youtubeURL) {
        YErrors += " " + error_no_url;
    }
    if (YErrors) {
        alert("Error!"+YErrors);
        return;
    }
	doAddTags("[youtube="+youtubeURL+"]", "", obj);

}
function music(obj)
{   
	var YErrors = '';
    var musicURL   = prompt(text_enter_music, "http://");

    if (!musicURL) {
        YErrors += " " + error_no_url;
    }
    if (YErrors) {
        alert("Error!"+YErrors);
        return;
    }
	doAddTags("[music="+musicURL+"]", "", obj);

}
function video(obj)
{   
	var YErrors = '';
    var videoURL   = prompt(text_enter_video, "http://");

    if (!videoURL) {
        YErrors += " " + error_no_url;
    }
    if (YErrors) {
        alert("Error!"+YErrors);
        return;
    }
	doAddTags("[video="+videoURL+"]", "", obj);

}
function doImage(obj)
{
textarea = document.getElementById(obj);
var url = prompt(text_enter_image,'http://');
var scrollTop = textarea.scrollTop;
var scrollLeft = textarea.scrollLeft;
if (!url) {
        alert(error_no_url);
        return;
    }
	if (document.selection) 
			{
				textarea.focus();
				var sel = document.selection.createRange();
				sel.text = '[img]' + url + '[/img]';
			}
   else 
    {
		var len = textarea.value.length;
	    var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		
        var sel = textarea.value.substring(start, end);
	    //alert(sel);
		var rep = '[img]' + url + '[/img]';
        textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
		
			
		textarea.scrollTop = scrollTop;
		textarea.scrollLeft = scrollLeft;
	}

}

function doURL(obj)
{
textarea = document.getElementById(obj);
var url = prompt(text_enter_url,'http://');
var scrollTop = textarea.scrollTop;
var scrollLeft = textarea.scrollLeft;
if (!url) {
        alert(error_no_url);
        return;
    }
	if (document.selection) 
			{
				textarea.focus();
				var sel = document.selection.createRange();
				
			if(sel.text==""){
					sel.text = '[url]'  + url + '[/url]';
					} else {
					sel.text = '[url=' + url + ']' + sel.text + '[/url]';
					}			

				//alert(sel.text);
				
			}
   else 
    {
		var len = textarea.value.length;
	    var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		
        var sel = textarea.value.substring(start, end);
		
		if(sel==""){
				var rep = '[url]' + url + '[/url]';
				} else
				{
				var rep = '[url=' + url + ']' + sel + '[/url]';
				}
	    //alert(sel);
		
        textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
		
			
		textarea.scrollTop = scrollTop;
		textarea.scrollLeft = scrollLeft;
	}
}

function doAddTags(tag1,tag2,obj)
{
textarea = document.getElementById(obj);
	// Code for IE
		if (document.selection) 
			{
				textarea.focus();
				var sel = document.selection.createRange();
				//alert(sel.text);
				sel.text = tag1 + sel.text + tag2;
			}
   else 
    {  // Code for Mozilla Firefox
		var len = textarea.value.length;
	    var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		
		
		var scrollTop = textarea.scrollTop;
		var scrollLeft = textarea.scrollLeft;

		
        var sel = textarea.value.substring(start, end);
	    //alert(sel);
		var rep = tag1 + sel + tag2;
        textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
		
		textarea.scrollTop = scrollTop;
		textarea.scrollLeft = scrollLeft;
		
		
	}
}

function doList(tag1,tag2,obj){
textarea = document.getElementById(obj);
// Code for IE
		if (document.selection) 
			{
				textarea.focus();
				var sel = document.selection.createRange();
				var list = sel.text.split('\n');
		
				for(i=0;i<list.length;i++) 
				{
				list[i] = '[*]' + list[i];
				}
				//alert(list.join("\n"));
				sel.text = tag1 + '\n' + list.join("\n") + '\n' + tag2;
			} else
			// Code for Firefox
			{

		var len = textarea.value.length;
	    var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		var i;
		
		var scrollTop = textarea.scrollTop;
		var scrollLeft = textarea.scrollLeft;

		
        var sel = textarea.value.substring(start, end);
	    //alert(sel);
		
		var list = sel.split('\n');
		
		for(i=0;i<list.length;i++) 
		{
		list[i] = '[*]' + list[i];
		}
		//alert(list.join("<br>"));
        
		
		var rep = tag1 + '\n' + list.join("\n") + '\n' +tag2;
		textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
		
		textarea.scrollTop = scrollTop;
		textarea.scrollLeft = scrollLeft;
 }
}
function RowsTextarea(n, w) {
	var inrows = document.getElementById(n);
	if (w < 1) {
		var rows = -5;
	} else {
		var rows = +5;
	}
	var outrows = inrows.rows + rows;
	if (outrows >= 5 && outrows < 50) {
		inrows.rows = outrows;
	}
	return false;
}