// JavaScript Document
function cp_$(id){
return document.getElementById(id);
}

window.onbeforeunload(){
	if((end_edit==false) && (start_edit==true))
    echo "if you fresh or close this page,your design will clear";
}
function display(id,p) {
if(cp_$(id))
cp_$(id).style.display=p;
}
function dodisplay(id) {
	if(cp_$(id).style.display=='none')
	cp_$(id).style.display='block';
	else if(cp_$(id).style.display=='block')
	cp_$(id).style.display='none';
}
function drag(o)  
{  

    if (typeof o == "string") o = cp_$(o);   
	var bg=o.parentNode; 
    o.onmousedown = function(a)  
    {  
        //this.style.cursor = "move";   
        var d=document;  
        if(!a)a= (event)?event:window.event;  
        var x = a.clientX+d.body.scrollLeft-o.offsetLeft;  
        var y = a.clientY+d.body.scrollTop-o.offsetTop;  
  
        d.ondragstart = "return false;"  
        d.onselectstart = "return false;"  
        d.onselect = "document.selection.empty();"  
                  
        if(o.setCapture)  
            o.setCapture();  
        else if(window.captureEvents)  
            window.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP);  

        d.onmousemove = function(a)  
        {  
            if(!a)a=(event)?event:window.event;
			if((a.clientX+document.body.scrollLeft-x)<(-o.offsetWidth/2))
			o.style.left =(-o.offsetWidth/2)+'px';
			else if((a.clientX+document.body.scrollLeft-x)>(bg.offsetWidth-o.offsetWidth/2))
			o.style.left =(bg.offsetWidth-o.offsetWidth/2)+'px';
			else
            o.style.left = (a.clientX+document.body.scrollLeft-x)+'px';
			if((a.clientY+document.body.scrollTop-y)<(-o.offsetHeight/2))
			o.style.top =(-o.offsetHeight/2)+'px';
			else if((a.clientY+document.body.scrollTop-y)>(bg.offsetHeight-o.offsetHeight/2))
			o.style.top =(bg.offsetHeight-o.offsetHeight/2)+'px';
			else
            o.style.top = (a.clientY+document.body.scrollTop-y)+'px';
        }  

        d.onmouseup = function()  
        {  
            if(o.releaseCapture)  
                o.releaseCapture();  
            else if(window.captureEvents)  
                window.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP);  
            d.onmousemove = null;  
            d.onmouseup = null;  
            d.ondragstart = null;  
            d.onselectstart = null;  
            d.onselect = null;  
            //o.style.cursor = "normal";  
        };  
    };   
}
function reset_position(o) {
	if (typeof o == "string") o = cp_$(o);   
	var bg=o.parentNode; 
	if(o.offsetLeft<(-o.offsetWidth/2))
	o.style.left =(-o.offsetWidth/2)+'px';
	else if(o.offsetLeft>(bg.offsetWidth-o.offsetWidth/2))
	o.style.left =(bg.offsetWidth-o.offsetWidth/2)+'px';
	if(o.offsetTop<(-o.offsetHeight/2))
	o.style.top =(-o.offsetHeight/2)+'px';
	else if(o.offsetTop>(bg.offsetHeight-o.offsetHeight/2))
	o.style.top =(bg.offsetHeight-o.offsetHeight/2)+'px';
}
function get_side() {
if(cp_$('design_front').style.display=='block')
return 'front';
else
return 'back';
}

////调色板
var cp_loaded = false;
var postioned = false;
var target_obj = false;
var field_obj =false;
function cp_load(){
var p = '';
p += '<div id="colorpad" style="position:absolute; z-index:10000;background:#fff;">';
p += '<div style="text-align:right; cursor:pointer;" onclick="hidecolorpad();">CLOSE</div>';

var colorTable='';
colorTable='<table border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse" bordercolor="000000" onclick="cp_pick(event.srcElement.style.backgroundColor)" style="cursor:hand;">'+color_tmp+'</table>';  
p += colorTable;
p += '</div>';
var container = document.createElement('div');
container.id = 'container';
container.innerHTML = p;
document.body.appendChild(container);
cp_loaded = true;
}

function showcolorpad(event, obj,field){
	hide_dropdowns('in');
if(!cp_loaded){ cp_load(); }//加载
var postion = cp_postion(event, obj);//定位
cp_$('colorpad').style.top     = (postion.y+document.documentElement.scrollTop-20) + 'px';
cp_$('colorpad').style.left = (postion.x+document.documentElement.scrollLeft-20 ) + 'px';
cp_$('colorpad').style.display = '';//显示
target_obj = obj;//记录需要改变的表单
field_obj = field;
}
function hidecolorpad(){
if(!cp_loaded){return false;}//加载
cp_$('colorpad').style.display = 'none';//隐藏
hide_dropdowns('out');
}
function cp_pick(color){
cp_$(field_obj).value = color;
target_obj.style.backgroundColor= color;
if(color=='')
target_obj.innerHTML='&nbsp;X&nbsp;';
else
target_obj.innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;';
hidecolorpad();
}
function cp_postion(event, obj){
var p = new Object();
p.x = event.clientX;
p.y = event.clientY;
return p;
}


///调色板结束
 function addRow(id){
  var _tab=cp_$(id);
  var _row=_tab.insertRow(-1);
  var _col1;
  for(var i=1;i<arguments.length;i++) {
	_col1=_row.insertCell(-1);
    _col1.innerHTML=arguments[i];
  }
 }
 function deleteRow(id,delete_all){
    var _tab=cp_$(id);
	var index=_tab.rows.length-1;
	if(delete_all==true) {
		for(var i=_tab.rows.length;i>1;i--) {
		_tab.deleteRow(i-1);
		}
	} else {
	if(index>1)
    _tab.deleteRow(index);
	}
}
function clear_design(obj,name) {
	var side=get_side();
	var div_id=cp_$(side+'_'+name);
	var edit_id='add_'+side+'_'+name;
	document.return_form.all[side+'_'+name].value='';
	if(obj.checked==true) {
		display(edit_id,'block');
	} else {
		if(name=='logo') {
			div_id.innerHTML='';
			cp_$(get_side()+'_logo_auto_size').style.display='none';
			document.all(side+'_logo_embroidery').checked='';
		} else if(name=='team_name') {
			div_id.style.backgroundImage='';
			div_id.style.filter='';
		} else if(name=='nums') {
			div_id.style.backgroundImage='';
			div_id.style.filter='';
		} else if(name=='name') {
			cp_$('back_name').style.backgroundImage='';
			cp_$('back_name').style.filter='';
			xajax_get_team_list('false');
		}
		display(edit_id,'none');
		div_id.style.display='none';
	}
}

function check_design(t) {
var ret='';
var total=0;
if(cp_$('front_logo').innerHTML!='') total+=25;
if((cp_$('front_team_name').style.backgroundImage!='') || (cp_$('front_team_name').style.filter!='')) total+=6;
if((cp_$('front_nums').style.backgroundImage!='') || (cp_$('front_nums').style.filter!='')) total+=2;
if(design_type=='jersey') {//后面且只有上衣才有
	if((cp_$('back_logo').innerHTML!='') && (document.back_upload_logo_form.back_upload_logo.value!='')) total+=25;
	if((cp_$('back_team_name').style.backgroundImage!='') || (cp_$('back_team_name').style.filter!='')) total+=6;
	if((cp_$('back_nums').style.backgroundImage!='') || (cp_$('back_nums').style.filter!='')) total+=2;
	if(cp_$('back_name')!='null') {
	if((cp_$('back_name').style.backgroundImage!='') || (cp_$('back_name').style.filter!='')) total+=6;
	}
}
return total;
}
function display_side(side) {
	display('add_cart','none');
	hidecolorpad();
	if(side=='products') {
		display('choose','block');
		display('design_tool','none');
		display('set_team','none');
		display('design_front','none');
		display('design_back','none');
		display('set_project','none');
		display('front_logo_auto_size','none');
		display('back_logo_auto_size','none');
		display('design_list','none');
		display('bg_change_err','none');
		display('b2f','none');
		display('b2t','none');
		display('t2f','none');
		display('t2b','none');
		display('f2t','none');
		display('f2p','none');
		display('f2b','none');
	} else if(side=='team') {
		display('set_team','block');
		display('design_tool','none');
		display('choose','none');
		
		display('b2f','none');
		display('b2t','none');
		
		if(design_type=='jersey') {
		display('t2b','');
		display('t2f','none');
		} else {
		display('t2f','');
		display('t2b','none');
		}
		display('f2t','none');
		display('f2p','none');
		display('f2b','none');
		display('add_cart','');
	}else{
	if(side=='front')
	var other_side='back';
	else
	var other_side='front';
	display('design_'+side,'block');//显示区当前显示
	display('design_'+other_side,'none');//显示区反面不显示
	display('add_area_'+other_side,'none');//反面编辑区不显示
	display('add_area_'+side,'block');//当前编辑区显示
	display(side+'_edit_nav','block');//当前导航显示
	display(side+'_no_nav','block');//当前导航显示
	display(other_side+'_edit_nav','none');//反面导航不显示
	display(other_side+'_no_nav','none');//反面导航不显示
	display('design_tool','block');//显示工具
	display('set_team','none');//队员不显
	display('choose','none');//产品选择不选
		if(side=='front') {//上衣
			display('b2f','none');
			display('b2t','none');
			display('t2f','none');
			display('t2b','none');
			if(design_type=='jersey') {
			display('f2b','');
			display('f2t','none');
			} else {
			display('f2t','');
			display('f2b','none');
			}
			display('f2p','');
			display('design_list_front','');
			display('design_list_back','none');
		} else {
			display('b2f','');
			display('b2t','');
			display('t2f','none');
			display('t2b','none');
			display('f2t','none');
			display('f2p','none');
			display('f2b','none');
			display('design_list_front','none');
			display('design_list_back','');
		}
	}
}
function choose_nav(obj) {
cp_$('current').id='temp_current';
obj.id='current';
cp_$('temp_current').id='';
}
function hide_dropdowns(what){
    if (window.navigator.userAgent.indexOf('MSIE 6.0') != -1)
    if (what=="in") {
      var anchors = document.getElementsByTagName("select");
      for (var i=0; i<anchors.length; i++) {
        var anchor = anchors[i];
        if (anchor.getAttribute("rel")=="dropdown") {
          anchor.style.position="relative";
          anchor.style.top="0px";
          anchor.style.left="-2000px";
        }
      }
    } else {
      var anchors = document.getElementsByTagName("select");
      for (var i=0; i<anchors.length; i++) {
        var anchor = anchors[i];
        if (anchor.getAttribute("rel")=="dropdown") {
          anchor.style.position="relative";
          anchor.style.top="0px";
          anchor.style.left="0px";
        }
      }
    }
  }
function logo_size(a) {
	if(cp_$(get_side()+'_logo')) {
		var logo=cp_$(get_side()+'_logo').childNodes[0];
		switch(a) {
			case 'b':
			logo.width=logo.width*(1+0.05);
			logo.height=logo.height*(1+0.05);
			break;
			case 's':
			logo.width=logo.width*(1-0.05);
			logo.height=logo.height*(1-0.05);
			break;
			case 'r':
			var img=new Image();
			img.src=logo.src;
			logo.width=img.width;
			logo.height=img.height;
			break;
		}
	}
}
function change_side() {
if(get_side()=='front')	
display_side('back');
else
display_side('front');
}

function fudong() {
	var loadingTop=150;//cp_$('xajax_loading').offsetTop
	document.documentElement.onscroll=function(){
	var ell=cp_$('xajax_loading');
	ell.style.top=(document.documentElement.scrollTop+loadingTop)+"px";
	}
}

function set_bg_eval() {
	
	for(var i=0;i<cp_$('design_front').childNodes.length;i++) {
	var thisdiv=cp_$('design_front').childNodes[i];
	var t=thisdiv.id.split('_');
	if(t[0]=='bgfront')
	xajax_show_bg_list(products_id,t[1],thisdiv.title);
	}
}

function get_bg_list(products_id) {
	var bg_array=new Array();
	var j=0;
	for(var i=0;i<cp_$('design_front').childNodes.length;i++) {
	var thisdiv=cp_$('design_front').childNodes[i];
	var t=thisdiv.id.split('_');
		if(t[0]=='bgfront') {
			bg_array[j]=t[1];
			j++;
		}
	}
	xajax_alert_bg(bg_array,products_id);
}


var products_id='';
var design_type='';
var start_edit=false;
var end_edit=false;
var bg_height=100;