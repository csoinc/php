<?PHP
require('includes/application_top.php');

function logo_list() {
global $db;
$objresponse = new xajaxresponse();
$logo_list=$db->Execute("select * from ".DESIGN_LOGO." order by design_logo_type");
//$show='<span style="cursor:pointer" onClick="xajax_logo_list();">reload</span><br>';
//$show.=zen_draw_form("modify_logo_from","upload");
$show.="<table width='100%' cellspacing='0' cellpadding='2' border='0'><tr class='dataTableHeadingRow'><td class='dataTableHeadingContent'>Type</td><td class='dataTableHeadingContent'>Name</td><td class='dataTableHeadingContent'>Image</td><td class='dataTableHeadingContent' width='30px'>Acution</td></tr>";
	while(!$logo_list->EOF) {
	$show.="<tr class='dataTableRow'>";
	$show.="<td class='dataTableContent'>".$logo_list->fields['design_logo_type']."</td>";
	$show.="<td class='dataTableContent'>".$logo_list->fields['design_logo_name']."</td>";
	$show.="<td class='dataTableContent'>".zen_image(DIR_IMAGE_DESIGN.$logo_list->fields['design_logo_url'],$logo_list->fields['design_logo_name'],"",40)."</td>";
	$show.="<td class='dataTableContent'>"."<span style='cursor:pointer;' onclick=\"xajax_del_logo('".$logo_list->fields['design_logo_id']."');\">".zen_image("images/icon_delete.gif")."</span>"."</td>";
	$show.="</tr>";
	$logo_list->MoveNext();
	}
$show.="</table>";
//$show.="</form>";
$objresponse->addassign("logo_list","innerHTML",$show);
return $objresponse->getxml();
}
$xajax->registerfunction("logo_list");

function del_logo($id) {
global $db;
$objresponse = new xajaxresponse();
$db->Execute("delete from ".DESIGN_LOGO." where design_logo_id=".$id);
$objresponse->addscript("xajax_logo_list();");
return $objresponse->getxml();
}
$xajax->registerfunction("del_logo");

function insert_logo($date) {
global $db;
$objresponse = new xajaxresponse();
if(zen_not_null($date['logo_name']) and zen_not_null($date['logo_type']) and zen_not_null($date['upload_logo_url'])) {
$db->Execute("insert into ".DESIGN_LOGO." (design_logo_name,design_logo_type,design_logo_url) values ('".$date['logo_name']."','".$date['logo_type']."','".$date['upload_logo_url']."')");
$objresponse->addScript("xajax_logo_list();");
} else
$objresponse->addassign("upload_logo_err","innerHTML","upload fail");
return $objresponse->getxml();
}
$xajax->registerfunction("insert_logo");

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
<body onLoad="init();" >
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<!-- body_text //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
        <td class="pageHeading"><?php echo "Upload design products image"; ?></td>
        <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
      </tr>
</table><br>
<span style="cursor:pointer" onClick="if(document.getElementById('logo_list').innerHTML=='')xajax_logo_list();dodisplay('logo_list');">view logo</span>

<div id="logo">
	<div id="logo_list" style="display:none;"></div>

	<?php echo zen_draw_form("upload_logo_form","upload");?>
<br><hr>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo "Upload Art Logo"; ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
      </tr>
</table>
<label class="inputLabel">Art Logo Name:</label><?php echo zen_draw_input_field("logo_name","","size=100")?><br />
<label class="inputLabel">Art Logo Type:</label>
	<?php echo zen_draw_radio_field("logo_type","shorts",true).zen_image_button("shorts.gif");?>
	<?php echo zen_draw_radio_field("logo_type","jersey").zen_image_button("jersey.gif");?><br>
	<?php echo zen_draw_hidden_field("upload_logo_url")?><br>
	</form>
	<label class="inputLabel"></label><?php echo zen_draw_form("upload_logo_image_form","upload","","post",'target="upload_iframe" enctype="multipart/form-data"');?>
	<?php echo zen_draw_input_field("upload_logo","","onchange=\"document.upload_logo_image_form.submit();\"",false,"file");;?>
	</form>
	<br class="clearBoth"><br>
	<label class="inputLabel"></label><?php echo zen_image_submit("button_upload.gif","","onclick=\"xajax_insert_logo(xajax.getFormValues('upload_logo_form'));\"");?>
	<div id="upload_logo_err"></div>
<iframe name="upload_iframe" width="0" height="0" scrolling="no" style="display:none"></iframe>
<!-- body_text_eof //-->
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>