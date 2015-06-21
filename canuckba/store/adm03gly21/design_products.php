<?PHP
require('includes/application_top.php');
require_once('FirePHPCore/fb.PHP');
//FB::send();

function show_catalog($parent_id=0,$grandpa_id=0) {
global $db;
$objresponse = new xajaxresponse();
$show='';
Fb::log("log message in show_catalog");
$objresponse->addassign("products", "innerHTML", "");//先清空产品内容
$objresponse->addassign("catalog_".$grandpa_id, "innerHTML", "");//先清空父辈内容
$objresponse->addassign("design_image", "innerHTML", "");//先清空产品编辑区
$catalog_array=get_chindren_categories($parent_id);//取得子目录数组
	if(is_array($catalog_array)) {//如果为目录
	$catalog_array[]=array("id"=>"","text"=>"select categories");//添加默认值
fb($catalog_array);
	$show=zen_draw_pull_down_menu("catalog_pull_".$parent_id,$catalog_array,'',"onchange=\"if(this.value!=''){xajax_show_catalog(this.value,'".$parent_id."');}\"");//显示下拉表单
		if($parent_id==0) {//如果是一级目录
		$objresponse->addassign("catalog", "innerHTML", $show);//在爷下添加下拉
		$objresponse->addCreate("catalog", "div", "catalog_".$parent_id);//在爷id下 下拉后添加一个父内容
		}else {
		$objresponse->addassign("catalog_".$grandpa_id, "innerHTML", $show);//在爷下添加下拉
		$objresponse->addCreate("catalog_".$grandpa_id, "div", "catalog_".$parent_id);//在爷id下 下拉后添加一个父内容
		}
	} else {
		$products_array=get_products($parent_id);
		if(is_array($products_array)) {//如果是产品
		$products_array[]=array("id"=>"","text"=>"select products");//添加默认值
		$show=zen_draw_pull_down_menu("products_list",$products_array,'',"onchange=\"if(this.value!=''){xajax_show_products(this.value);}\"");//显示下拉产品表单
		$objresponse->addassign("products", "innerHTML", $show);//在爷下添加下拉
		}
	}
if($show=='')
$objresponse->addassign("products","innerHTML","no direction or products in this direction");
return $objresponse->getxml();
}
$xajax->registerfunction("show_catalog");

function show_products($products_id) {//产品编辑区
global $db;
Fb::log("log message in show_products");
$objresponse = new xajaxresponse();
$design=$db->Execute("select * from ".DESIGN_PRODUCTS." where products_id=".(int)$products_id);//查询可以定制的产品
if($design->RecordCount()>0) {
$show.="Warning:if you do anything,it will delete all customers' project<br>";
$show.=zen_draw_form("change_type","design_products");
$show.="<label class='inputLabel'></label>";
$show.=zen_draw_radio_field("type","shorts",($design->fields['design_products_type']=="shorts")).zen_image_button("shorts.gif");
$show.=zen_draw_radio_field("type","jersey",($design->fields['design_products_type']=="jersey")).zen_image_button("jersey.gif")."<br>";
$show.="<label class='inputLabel'>";
$show.="image width:</label>".zen_draw_input_field("design_width",$design->fields['design_products_width'])."px<br/>";
$show.="<label class='inputLabel'>";
$show.="image height:</label>".zen_draw_input_field("design_height",$design->fields['design_products_height'])."px<br>";
$show.="<label class='inputLabel'>";
$show.="design area away</label><br /><label class='inputLabel'>from top:</label>".zen_draw_input_field("design_top",$design->fields['design_products_top'])."px<br />";
$show.="<label class='inputLabel'>";
$show.="design area </label><br/><label class='inputLabel'>away from left:</label>".zen_draw_input_field("design_left",$design->fields['design_products_left'])."px<br><br>";
$show.="<label class='inputLabel'></label>";
$show.=zen_image_button("button_update.gif","","style='cursor:pointer;' onclick=\"xajax_save_type(xajax.getFormValues('change_type'),'".$products_id."','update')\"");
$show.="</form><hr>";
	//存在组初始化
	$design_exist=$db->Execute("select * from ".DESIGN_EXIST." where products_id=".(int)$products_id." order by team_id");
	$team_id='';
	$team_i=0;
	while(!$design_exist->EOF) {
	if($team_id=='') {
	$team_id=$design_exist->fields['team_id'];
	$team_i=0;
	$team_id_array[0]=$team_id;
	} elseif($team_id!=$design_exist->fields['team_id']) {
	$team_i++;
	$team_id=$design_exist->fields['team_id'];
	$team_id_array[$team_i]=$team_id;
	}
	$team_array[$team_i][]=$design_exist->fields["design_products_image_id"];
	
	$design_exist->MoveNext();
	}
	$show.=zen_draw_form("add_image","design_products",  'action=insert', 'post','enctype="multipart/form-data"');

	$image=$db->Execute("select * from ".DESIGN_PRODUCTS_IMAGES." where products_id=".(int)$products_id." order by design_products_image_name");//查询已经存在的图片
	if($image->RecordCount()>0) {
	$show.="<table border=1>";
	$show.="<tr><td>name</td><td>image</td><td>operate</td><td>set team</td>";
	$show.="<td colspan=".sizeof($team_array).">design team</td>";
	$show.="</tr>";
		while(!$image->EOF) {
			$show.="<tr>";
			$show.="<td><label class='inputLabel'>";
			$show.=($image->cursor+1).".".$image->fields['design_products_image_name']."----".$image->fields['design_products_name'] ."</label></td>";
			$show.="<td>front:".zen_image(DIR_IMAGE_DESIGN.$image->fields['design_products_image_front'],$image->fields['design_products_image_name'],50,50);
			if($design->fields['design_products_type']=="jersey")
			$show.="back:".zen_image(DIR_IMAGE_DESIGN.$image->fields['design_products_image_back'],$image->fields['design_products_image_name'],50,50);
			$show.="</td>";
			$show.="<td>".zen_draw_radio_field($image->fields['design_products_image_name'],$image->fields['design_products_image_id'],($image->fields['design_products_image_default']==1),"","onclick=\"if(this.checked){xajax_set_default(this.value,this.name);}\"")."Default";
			$show.=zen_image_button("button_delete.gif","","style='cursor:pointer;' onclick=\"xajax_delete_image(".$image->fields['design_products_image_id'].")\"")."<td>".zen_draw_radio_field("setteam_".$image->fields['design_products_image_name'],$image->fields['design_products_image_id'])."</td>";//显示删除按钮
			if(is_array($team_array)) {
			reset($team_array);
				for($i=0;$i<sizeof($team_array);$i++) {
				if(is_array($team_array[$i]) and in_array($image->fields['design_products_image_id'],$team_array[$i]))
				$show.="<td>&radic;</td>";
				else
				$show.="<td>&Chi;</td>";
				}
			}
			$show.="</tr>";
			if(!isset($name_array[$image->fields['design_products_image_name']])) {
			$name_array[$image->fields['design_products_image_name']]=array('id'=>$image->fields['design_products_image_name'],'text'=>$image->fields['design_products_image_name']);
			$exist_name_array[]=array('id'=>$image->fields['design_products_image_name'],'text'=>$image->fields['design_products_image_name']);
			}
		$image->MoveNext();
		}
	$show.="<tr><td colspan=3>&nbsp;</td><td style='cursor:pointer;' onclick=\"xajax_set_team(xajax.getFormValues('add_image'),'".$products_id."')\">add team</td>";
	if(is_array($team_id_array)) {
		for($i=0;$i<sizeof($team_id_array);$i++) {
		$show.="<td><span style='cursor:pointer;' onclick=\"xajax_del_design_exist('".$team_id_array[$i]."','".$products_id."');\">delete</span></td>";
		}
	}	
	$show.="</tr></table>";
	}
	$show.="<br><br>";
	$show.=zen_draw_hidden_field("add_image_front_url");//隐藏上传图片域
	$show.=zen_draw_hidden_field("add_image_back_url");//隐藏上传图片域
	$show.="<label class='inputLabel'>";
	$show.="Side Name:</label>".zen_draw_input_field("add_image_name","","size=60")."<br />";
	$show.="<label class='inputLabel'></label>";
	$exist_name_array[]=array('id'=>"",'text'=>"select exist name");
	$show.="or<br /><label class='inputLabel'></label>".zen_draw_pull_down_menu("add_image_exist_name",$exist_name_array,"","onchange=\"if(this.value!=''){document.add_image.add_image_name.value=this.value}\"");
	$show.="<br><label class='inputLabel'>";
	$show.="Des Name:</label>".zen_draw_input_field("add_design_name","","size=60")."(for check order)<br />";
	$show.="</form><br>";
	//$show.="<br>";
	//上传框架 前面
	$show.=zen_draw_form("upload_front_image_from","upload","","post",'target="upload_iframe" enctype="multipart/form-data"');
	$show.="<label class='inputLabel'>";
	$show.="upload front image:</label>".zen_draw_input_field("upload_image_front","","onchange=\"document.upload_front_image_from.submit();\"",false,"file");
	//$show.="upload front image:</label>".zen_draw_input_field("upload_image_front","","",false,"file");
	$show.="</form>";
	$show.="<div id='upload_err_front'></div>";
	$show.="<label class='inputLabel'></label><br />";
	if($design->fields['design_products_type']=="jersey") { //后面
	$show.=zen_draw_form("upload_back_image_from","upload","","post",'target="upload_iframe" enctype="multipart/form-data"');
	$show.="<label class='inputLabel'>";
	$show.="upload back image:</label>".zen_draw_input_field("upload_image_back","","onchange=\"document.upload_back_image_from.submit();\"",false,"file");
	//$show.="upload back image:</label>".zen_draw_input_field("upload_image_back","","",false,"file");
	$show.="</form>";
	$show.="<div id='upload_err_back'></div>";
	}
	$show.="<label class='inputLabel'></label><br />";
	$show.=zen_image_button("button_insert.gif","","style='cursor:pointer;' onclick=\"xajax_insert_image(xajax.getFormValues('add_image'),'".$products_id."')\"");
	$show.="</form><br>";
} else {//没有设置该产品为定制产品
$show.="you didn't set this products,first you must set type of this products<br>";
$show.=zen_draw_form("add_type","design_products");
$show.=zen_draw_radio_field("type","shorts").zen_image_button("shorts.gif");
$show.=zen_draw_radio_field("type","jersey").zen_image_button("jersey.gif")."<br>";
$show.="image width:".zen_draw_input_field("design_width",300)."px ";
$show.="image height:".zen_draw_input_field("design_height",300)."px<br>";
$show.="design area away from top:".zen_draw_input_field("design_top",50)."px";
$show.="design area away from left:".zen_draw_input_field("design_left",50)."px<br>";
$show.=zen_image_button("button_save.gif","","style='cursor:pointer;' onclick=\"xajax_save_type(xajax.getFormValues('add_type'),'".$products_id."','add')\"");
}
$objresponse->addassign("design_image","innerHTML",$show);
//$objresponse->addassign("aaa","value",$show);
return $objresponse->getxml();
}
$xajax->registerfunction("show_products");

function set_team($date,$products_id) {
global $db;
$objresponse = new xajaxresponse();
$rand=rand(100000,9999999);
	while(list($k,$v)=each($date)) {
		if(strstr($k,"setteam_")) {
		$sql_array['design_products_image_id']=$v;
		$sql_array['team_id']=$rand;
		$sql_array['products_id']=$products_id;
		zen_db_perform(DESIGN_EXIST, $sql_array);
		}
	}
$objresponse->addScript("xajax_show_products(".$products_id.");");
return $objresponse->getxml();
}
$xajax->registerfunction("set_team");

function del_design_exist($team_id,$products_id) {
global $db;
$objresponse = new xajaxresponse();
$db->Execute("delete from ".DESIGN_EXIST." where team_id='".$team_id."' and products_id=".$products_id);
$objresponse->addScript("xajax_show_products(".$products_id.");");
return $objresponse->getxml();
}
$xajax->registerfunction("del_design_exist");

function delete_image($image_id) {//删除图片
global $db;
$objresponse = new xajaxresponse();
$image=$db->Execute("select design_products_image_front,design_products_image_back,products_id from ".DESIGN_PRODUCTS_IMAGES." where design_products_image_id=".(int)$image_id);
if(file_exists(DIR_IMAGE_DESIGN.$image->fields['design_products_image_front']) and $image->fields['design_products_image_front']!='')
unlink(DIR_IMAGE_DESIGN.$image->fields['design_products_image_front']);
if(file_exists(DIR_IMAGE_DESIGN.$image->fields['design_products_image_back']) and $image->fields['design_products_image_back']!='')
unlink(DIR_IMAGE_DESIGN.$image->fields['design_products_image_back']);
$db->Execute("delete from ".DESIGN_PRODUCTS_IMAGES." where design_products_image_id=".(int)$image_id);
$db->Execute("delete from ".DESIGN_PROJECT." where products_id=".$image->fields['products_id']);//清空方案
$objresponse->addScript("xajax_show_products(".$image->fields['products_id'].");");
return $objresponse->getxml();
}
$xajax->registerfunction("delete_image");

function save_type($date,$products_id,$action) {//保存产品为定制产品
global $db;
$objresponse = new xajaxresponse();
if($action=='add') {
$db->Execute("insert into ".DESIGN_PRODUCTS." (design_products_type,design_products_top,design_products_left,design_products_width,design_products_height,products_id) values ('".$date['type']."','".$date["design_top"]."','".$date["design_left"]."','".$date["design_width"]."','".$date["design_height"]."','".$products_id."')");
$objresponse->addScript("xajax_show_products(".$products_id.");");
} 
if($action=='update') {
$db->Execute("update ".DESIGN_PRODUCTS." set design_products_type='".$date['type']."',design_products_top='".$date["design_top"]."',design_products_left='".$date["design_left"]."',design_products_width='".$date["design_width"]."',design_products_height='".$date["design_height"]."' where products_id=".$products_id);
}
return $objresponse->getxml();
}
$xajax->registerfunction("save_type");

function insert_image($date,$products_id) {//插入图片
global $db;
$objresponse = new xajaxresponse();
Fb::log("log message in insert_image");
fb($date['add_image_front_url'], 'add_image_front_url');
fb($date['add_image_back_url'], 'add_image_back_url');

$db->Execute("insert into ".DESIGN_PRODUCTS_IMAGES." (design_products_image_front,design_products_image_back,design_products_image_name,products_id,design_products_name) values ('".$_SESSION['front_image_name']."','".$_SESSION['back_image_name']."','".trim($date['add_image_name'])."','".$products_id."','".trim($date['add_design_name'])."')");
//unset the session vars
unset($_SESSION['front_image_name']); 
unset($_SESSION['back_image_name']); 
//$db->Execute("insert into ".DESIGN_PRODUCTS_IMAGES." (design_products_image_front,design_products_image_back,design_products_image_name,products_id,design_products_name) values ('".$front_image_name."','".$back_image_name."','".trim($date['add_image_name'])."','".$products_id."','".trim($date['add_design_name'])."')");
$db->Execute("delete from ".DESIGN_PROJECT." where products_id=".$products_id);//清空方案
$objresponse->addScript("xajax_show_products(".$products_id.");");
return $objresponse->getxml();
}
$xajax->registerfunction("insert_image");
function set_default($id,$name) {
global $db;
$objresponse = new xajaxresponse();
$products_id=$db->Execute("select products_id from ".DESIGN_PRODUCTS_IMAGES." where design_products_image_id=".$id);
$db->Execute("update ".DESIGN_PRODUCTS_IMAGES." set design_products_image_default=0 where design_products_image_name='".$name."' and products_id=".$products_id->fields['products_id']);
$db->Execute("update ".DESIGN_PRODUCTS_IMAGES." set design_products_image_default=1 where design_products_image_name='".$name."' and design_products_image_id=".$id);
return $objresponse->getxml();
}
$xajax->registerfunction("set_default");

function upload_image($file) {
Fb::log("log message in upload_image");
fb($file, 'file');
	$upload_dir='../images/design/';
	$file_name=time().rand(1,100).".".file_type($file);
	$new_file_name=$upload_dir.$file_name;
fb($new_file_name, 'new_file_name');
	
	$image = new upload('image');
	$image -> set_destination($upload_dir);
	$image -> set_filename($file_name);
	
	$image_name='';
    if ($image->parse() && $image->save()) {
Fb::log("upload OK");
	
        $image_name = $upload_dir . $image->filename;
    }
	else {
Fb::log("upload failed!");
	}
fb($image_name, 'image_name');
	return $image_name;
}

function file_type($file) {
$file_type=explode(".",$file);
$file_type=strtolower($file_type[count($file_type)-1]);
return $file_type;
}
function check_file_type($file){
$all_file_type=array("png","jpg","gif");//文件格式
$file_type=file_type($file);
if(in_array($file_type,$all_file_type))
return true;
else
return false;
}
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
<body onLoad="init();xajax_show_catalog();" >
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
<div id="catalog"></div>
<div id="products"></div>
<div id="design_image"></div>

<iframe name="upload_iframe" width="0" height="0" scrolling="no" style="display:none"></iframe>
<!-- body_text_eof //-->
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>