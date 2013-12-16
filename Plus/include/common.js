/******************************************************************************
  HKLCF Discuz! Plus - Common Modules for Discuz! Plus
  Modified by: HKLCF.COM (http://hklcf.com)
*******************************************************************************/

tPopWait=20;
showPopStep=20;
popOpacity=80;

sPop=null;
curShow=null;
tFadeOut=null;
tFadeIn=null;
tFadeWaiting=null;

document.write("<style type='text/css'id='defaultPopStyle'>");
document.write(".cPopText { font-family: Verdana, Tahoma; background-color: #FFFFCC; border: 1px #000000 solid; font-size: 11px; padding-right: 4px; padding-left: 4px; height: 20px; padding-top: 2px; padding-bottom: 2px; filter: Alpha(Opacity=0)}");

document.write("</style>");
document.write("<div id='popLayer' style='position:absolute;z-index:1000;' class='cPopText'></div>");


function showPopupText(){
	var o=event.srcElement;
	MouseX=event.x;
	MouseY=event.y;
	if(o.alt!=null && o.alt!="") { o.pop=o.alt;o.alt="" }
        if(o.title!=null && o.title!=""){ o.pop=o.title;o.title="" }
        if(o.pop) { o.pop=o.pop.replace("\n","<br>"); o.pop=o.pop.replace("\n","<br>"); }
	if(o.pop!=sPop) {
		sPop=o.pop;
		clearTimeout(curShow);
		clearTimeout(tFadeOut);
		clearTimeout(tFadeIn);
		clearTimeout(tFadeWaiting);	
		if(sPop==null || sPop=="") {
			popLayer.innerHTML="";
			popLayer.style.filter="Alpha()";
			popLayer.filters.Alpha.opacity=0;	
		} else {
			if(o.dyclass!=null) popStyle=o.dyclass 
			else popStyle="cPopText";
			curShow=setTimeout("showIt()",tPopWait);
		}
	}
}

function showIt() {
	popLayer.className=popStyle;
	popLayer.innerHTML=sPop;
	popWidth=popLayer.clientWidth;
	popHeight=popLayer.clientHeight;
	if(MouseX+12+popWidth>document.body.clientWidth) popLeftAdjust=-popWidth-24
		else popLeftAdjust=0;
	if(MouseY+12+popHeight>document.body.clientHeight) popTopAdjust=-popHeight-24
		else popTopAdjust=0;
	popLayer.style.left=MouseX+12+document.body.scrollLeft+popLeftAdjust;
	popLayer.style.top=MouseY+12+document.body.scrollTop+popTopAdjust;
	popLayer.style.filter="Alpha(Opacity=0)";
	fadeOut();
}

function fadeOut(){
	if(popLayer.filters.Alpha.opacity<popOpacity) {
		popLayer.filters.Alpha.opacity+=showPopStep;
		tFadeOut=setTimeout("fadeOut()",1);
	}
}

function ctlent(obj) {
	if((event.ctrlKey && window.event.keyCode == 13) || (event.altKey && window.event.keyCode == 83)) {
		//if(validate(this.document.input)) 
		this.document.input.submit();
	}
}

function checkall(form) {
	for(var i = 0;i < form.elements.length; i++) {
		var e = form.elements[i];
		if (e.name != 'chkall') {
			e.checked = form.chkall.checked;
		}
	}
}

function findobj(n, d) {
	var p,i,x; if(!d) d=document;
	if((p=n.indexOf("?"))>0 && parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document;
		n=n.substring(0,p);
	}
	if(!(x=d[n])&&d.all) {
		x=d.all[n];
	}
	for(i=0;!x && i<d.forms.length;i++) {
		x=d.forms[i][n];
	}
	for(i=0;!x && d.layers&&i>d.layers.length;i++) {
		x=MM_findObj(n,d.layers[i].document);
	}
	return x;
}

function copycode(obj) {
	var rng = document.body.createTextRange();
	rng.moveToElementText(obj);
	rng.scrollIntoView();
	rng.select();
	rng.execCommand("Copy");
	rng.collapse(false);
}

function toggle_collapse(objname, image, path) {
	obj = findobj(objname);
	imgobj = findobj(image);
	collapsed = getcookie("discuz_collapse");
	cookie_start = collapsed ? collapsed.indexOf(objname) : -1;
	cookie_end = cookie_start + objname.length + 1;

	if(obj.style.display == "none") {
		obj.style.display = "";
		imgobj.src = path + "cat_close.gif";
		if(cookie_start != -1) collapsed = collapsed.substring(0, cookie_start) + collapsed.substring(cookie_end, collapsed.length);
	} else {
		obj.style.display = "none";
		imgobj.src = path + "cat_open.gif";
		if(cookie_start == -1) collapsed = collapsed + objname + " ";
	}

	expires = new Date();
	expires.setTime(expires.getTime() + (collapsed ? 86400 * 30 : -(86400 * 30 * 1000)));
	document.cookie = "discuz_collapse=" + escape(collapsed) + "; expires=" + expires.toGMTString() + "; path=/";
}

function getcookie(name) {
	var cookie_start = document.cookie.indexOf(name);
	var cookie_end = document.cookie.indexOf(";", cookie_start);
	return cookie_start == -1 ? '' : unescape(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
}

document.onmouseover=showPopupText;

<!-- 
var h;
var l;
var t;
var isvisible;
function HideMenu() 
{
var mX;
var mY;
var vDiv;
	if (isvisible == true)
{
		vDiv = document.all("menuDiv");
		mX = window.event.clientX + document.body.scrollLeft;
		mY = window.event.clientY + document.body.scrollTop;
	if ((mX < parseInt(vDiv.style.left)) || (mX > parseInt(vDiv.style.left)+vDiv.offsetWidth) || (mY < parseInt(vDiv.style.top)-h) || (mY > parseInt(vDiv.style.top)+vDiv.offsetHeight)){
		vDiv.style.visibility = "hidden";
			isvisible = false;
		}
	}
}

function ShowMenu(vMnuCode) {
	vSrc = window.event.srcElement;
	vMnuCode = "<DIV style='PADDING-RIGHT: 6px; FILTER: shadow(color=#698CC3,direction=120); PADDING-BOTTOM: 6px;width:98px'><table border=0 cellspacing=1 cellpadding=4 bgcolor=#698CC3 style='line-height:18px' width='98' align='left'><tr bgcolor='#FFFFFF'><td width=14 background=images/common/menubg.gif>&nbsp;</td><td>" + vMnuCode + "</td></tr></table></div>";
	h = vSrc.offsetHeight + 0;
	l = vSrc.offsetLeft + 0;
	t = vSrc.offsetTop + h;
	vParent = vSrc.offsetParent;
	while (vParent.tagName.toUpperCase() != "BODY")
	{
	l += vParent.offsetLeft;
	t += vParent.offsetTop;
	vParent = vParent.offsetParent;
	}

	menuDiv.innerHTML = vMnuCode;
	menuDiv.style.top = t;
	menuDiv.style.left = l;
	menuDiv.style.visibility = "visible";
	isvisible = true;

}
//-->