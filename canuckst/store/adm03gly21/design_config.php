<?PHP
require('includes/application_top.php');
$all_color=array("aliceblue"=>"aliceblue","antiquewhite"=>"antiquewhite","aqua"=>"aqua","aquamarine"=>"aquamarine","azure"=>"azure","beige"=>"beige","bisque"=>"bisque","black"=>"black","blanchedalmond"=>"blanchedalmond","blue"=>"blue","blueviolet"=>"blueviolet","brown"=>"brown","burlywood"=>"burlywood","cadetblue"=>"cadetblue","chartreuse"=>"chartreuse","chocolate"=>"chocolate","coral"=>"coral","cornflowerblue"=>"cornflowerblue","cornsilk"=>"cornsilk","crimson"=>"crimson","cyan"=>"cyan","darkblue"=>"darkblue","darkcyan"=>"darkcyan","darkgoldenrod"=>"darkgoldenrod","darkgray"=>"darkgray","darkgreen"=>"darkgreen","darkkhaki"=>"darkkhaki","darkmagenta"=>"darkmagenta","darkolivegreen"=>"darkolivegreen","darkorange"=>"darkorange","darkorchid"=>"darkorchid","darkred"=>"darkred","darksalmon"=>"darksalmon","darkseagreen"=>"darkseagreen","darkslateblue"=>"darkslateblue","darkslategray"=>"darkslategray","darkturquoise"=>"darkturquoise","darkviolet"=>"darkviolet","deeppink"=>"deeppink","deepskyblue"=>"deepskyblue","dimgray"=>"dimgray","dodgerblue"=>"dodgerblue","firebrick"=>"firebrick","floralwhite"=>"floralwhite","forestgreen"=>"forestgreen","Fuchsia"=>"Fuchsia","gainsboro"=>"gainsboro","ghostwhite"=>"ghostwhite","gold"=>"gold","goldenrod"=>"goldenrod","gray"=>"gray","green"=>"green","greenyellow"=>"greenyellow","honeydew"=>"honeydew","hotpink"=>"hotpink","indianred"=>"indianred","indigo"=>"indigo","ivory"=>"ivory","khaki"=>"khaki","lavender"=>"lavender","lavenderblush"=>"lavenderblush","lawngreen"=>"lawngreen","lemonchiffon"=>"lemonchiffon","lightblue"=>"lightblue","lightcoral"=>"lightcoral","lightcyan"=>"lightcyan","lightgoldenrodyellow"=>"lightgoldenrodyellow","lightgreen"=>"lightgreen","lightgrey"=>"lightgrey","lightpink"=>"lightpink","lightsalmon"=>"lightsalmon","lightseagreen"=>"lightseagreen","lightskyblue"=>"lightskyblue","lightslategray"=>"lightslategray","lightsteelblue"=>"lightsteelblue","lightyellow"=>"lightyellow","lime"=>"lime","limegreen"=>"limegreen","linen"=>"linen","magenta"=>"magenta","maroon"=>"maroon","mediumaquamarine"=>"mediumaquamarine","mediumblue"=>"mediumblue","mediumorchid"=>"mediumorchid","mediumpurple"=>"mediumpurple","mediumseagreen"=>"mediumseagreen","mediumslateblue"=>"mediumslateblue","mediumspringgreen"=>"mediumspringgreen","mediumturquoise"=>"mediumturquoise","mediumvioletred"=>"mediumvioletred","midnightblue"=>"midnightblue","mintcream"=>"mintcream","mistyrose"=>"mistyrose","moccasin"=>"moccasin","navajowhite"=>"navajowhite","navy"=>"navy","oldlace"=>"oldlace","olive"=>"olive","olivedrab"=>"olivedrab","orange"=>"orange","orangered"=>"orangered","orchid"=>"orchid","palegoldenrod"=>"palegoldenrod","palegreen"=>"palegreen","paleturquoise"=>"paleturquoise","palevioletred"=>"palevioletred","papayawhip"=>"papayawhip","peachpuff"=>"peachpuff","peru"=>"peru","pink"=>"pink","plum"=>"plum","powderblue"=>"powderblue","purple"=>"purple","red"=>"red","rosybrown"=>"rosybrown","royalblue"=>"royalblue","saddlebrown"=>"saddlebrown","salmon"=>"salmon","sandybrown"=>"sandybrown","seagreen"=>"seagreen","seashell"=>"seashell","sienna"=>"sienna","silver"=>"silver","skyblue"=>"skyblue","slateblue"=>"slateblue","slategray"=>"slategray","snow"=>"snow","springgreen"=>"springgreen","steelblue"=>"steelblue","tan"=>"tan","teal"=>"teal","thistle"=>"thistle","tomato"=>"tomato","turquoise"=>"turquoise","violet"=>"violet","wheat"=>"wheat","white"=>"white","whitesmoke"=>"whitesmoke","yellow"=>"yellow","yellowgreen"=>"yellowgreen");

function load_color() {
global $db,$all_color;
$objresponse = new xajaxresponse();
$choose_color=$db->Execute("select * from ".DESIGN_COLOR);
while(!$choose_color->EOF) {
$show_choose.='<span style="BACKGROUND: '.$choose_color->fields['design_color_value'].'; width:40px" alt="'.$choose_color->fields['design_color_value'].'" onclick="xajax_delete_color(this.style.background);">&nbsp;</span>';
if(in_array($choose_color->fields['design_color_value'],$all_color))
unset($all_color[$choose_color->fields['design_color_value']]);
$choose_color->MoveNext();
}
reset($all_color);
while(list($key,$value)=each($all_color)) {
$show_all.='<span style="BACKGROUND: '.$key.'; width:40px" alt="'.$key.'" onclick="document.all(\'choose_color\').value=this.style.background;">&nbsp;</span>';
}
$objresponse->addassign("all","innerHTML",$show_all);
$objresponse->addassign("choose","innerHTML",$show_choose);
return $objresponse->getxml();
}
$xajax->registerfunction("load_color");

function add_color($date) {
global $db;
$objresponse = new xajaxresponse();
$db->Execute("insert into ".DESIGN_COLOR." (design_color_value) value ('".$date['choose_color']."')");
$objresponse->addscript("xajax_load_color();");
return $objresponse->getxml();
}
$xajax->registerfunction("add_color");

function delete_color($color) {
global $db;
$objresponse = new xajaxresponse();
if(zen_not_null($color))
$db->Execute("delete from ".DESIGN_COLOR." where design_color_value='".$color."'");
$objresponse->addscript("xajax_load_color();");
return $objresponse->getxml();
}
$xajax->registerfunction("delete_color");

$xajax->processRequests();
?><!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script type="text/javascript">
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  }
  function display(id,p) {
if((p!="") && document.getElementById(id))
document.getElementById(id).style.display=p;
}
function dodisplay(id) {
	if(document.getElementById(id).style.display=='none')
	document.getElementById(id).style.display='block';
	else if(document.getElementById(id).style.display=='block')
	document.getElementById(id).style.display='none';
}
  // -->
</script>
<?php
$xajax->printJavascript(DIR_WS_INCLUDES);
?>
</head>
<body onLoad="init();xajax_load_color();" >
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<!-- body_text //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
        <td class="pageHeading"><?php echo "set config"; ?></td>
        <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
      </tr>
</table><br>

<!-- body_text_eof //-->

<?php echo zen_draw_form("add_color","design_config");?>
<input type="text" name="choose_color">
<input type="button" onClick="xajax_add_color(xajax.getFormValues(this.form.name))" value="Add">
</form>
<div style="width:60%" id="all"></div>
<br>
<br>
<br>
click to delete:
<div style="width:60%" id="choose"></div>


<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>