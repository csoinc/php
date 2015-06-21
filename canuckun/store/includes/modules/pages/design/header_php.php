<?php
if (!$_SESSION['customer_id']) {//���½�ſɷ��ʸ�ҳ��,���¸�ajax������ʼ����д�����
  $_SESSION['navigation']->set_snapshot();
  //zen_redirect(zen_href_link(FILENAME_LOGIN, '', 'SSL'));//��ʱ���ﲻ�ù���
}
require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
$breadcrumb->add(NAVBAR_TITLE);
  
//��ʼ��bodyload
$onload="xajax_unset_design_session();xajax_show_catalog();";
if(zen_not_null($_GET['products_id'])) {
$onload.="xajax_show_products('".$_GET['products_id']."','".$_GET['project_id']."','".$_GET['cPath']."');";
}
//else
//$onload.="dodisplay('save_project');";
//$onload.="fudong();";

//unset the design session 
function unset_design_session() {
	$objresponse = new xajaxresponse();
	foreach(array_keys($_SESSION["front_team_name"]) as $div) { 	
		unset($_SESSION["front_team_name"][$div]);
	}
	foreach(array_keys($_SESSION["back_team_name"]) as $div) { 	
		unset($_SESSION['back_team_name'][$div]);
	}
	foreach(array_keys($_SESSION["sleeve_right_team_name"]) as $div) { 	
		unset($_SESSION['sleeve_right_team_name'][$div]);
	}
	foreach(array_keys($_SESSION["sleeve_left_team_name"]) as $div) { 	
		unset($_SESSION['sleeve_left_team_name'][$div]);
	}
	unset($_SESSION["front_team_name"]);
	unset($_SESSION["back_team_name"]);
	unset($_SESSION["sleeve_right_team_name"]);
	unset($_SESSION["sleeve_left_team_name"]);
//$objresponse->addAlert(sizeof($_SESSION['front_team_name']));
	return $objresponse->getxml();
}
$xajax->registerfunction("unset_design_session");

function show_catalog($parent_id=0,$grandpa_id=0) {//��ʾĿ¼
	global $db;
	$objresponse = new xajaxresponse();
	if (!$_SESSION['customer_id']) {
		$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
		//$objresponse->addRedirect(zen_href_link(FILENAME_LOGIN, '', 'SSL'));
		return $objresponse->getxml();
	}
	$show='';
	$objresponse->addassign("products", "innerHTML", "");//����ղ�Ʒ����
	$objresponse->addassign("catalog_".$grandpa_id, "innerHTML", "");//����ո�������
	$objresponse->addassign("design_image", "innerHTML", "");//����ղ�Ʒ�༭��
	$catalog_array=get_chindren_categories($parent_id);//ȡ����Ŀ¼����
	if(is_array($catalog_array)) {//���ΪĿ¼
		$catalog_array[]=array("id"=>"","text"=>TEXT_SELECT_CATALOG);//����Ĭ��ֵ
		$show=zen_draw_pull_down_menu("catalog_pull_".$parent_id,$catalog_array,'',"onchange=\"if(this.value!=''){xajax_show_catalog(this.value,'".$parent_id."');}\"");//��ʾ��������
		if($parent_id==0) {//�����һ��Ŀ¼
			$objresponse->addassign("catalog", "innerHTML", $show);//��ү����������
			$objresponse->addCreate("catalog", "div", "catalog_".$parent_id);//��үid�� ����������һ��������
		}else {
			$objresponse->addassign("catalog_".$grandpa_id, "innerHTML", $show);//��ү����������
			$objresponse->addCreate("catalog_".$grandpa_id, "div", "catalog_".$parent_id);//��үid�� ����������һ��������
		}
	} else {
		$products_array=get_products($parent_id);
		if(is_array($products_array)) {//����ǲ�Ʒ
		$show="<table><tr>";
			for($i=0;$i<sizeof($products_array);$i++) {
			$show.="<td>";
			$show.=zen_image(DIR_WS_IMAGES.$products_array[$i]['image'],$products_array[$i]['name'],50,50,"style='cursor:pointer' onclick=\"xajax_show_products(".$products_array[$i]['id'].");\"")."<br>";
			$show.=$products_array[$i]['name']."<br>";
			$show.=$products_array[$i]['model'];
			$show.="</td>";
			if(($i+1)%2==0)
			$show.="</tr><tr>";
			}
		$show.="</tr></table>";
		$objresponse->addassign("products","innerHTML",$show);
		}
	}
	if($show=='')
		$objresponse->addassign("products","innerHTML",TEXT_NO_DESIGN_PRODUCTS);
	return $objresponse->getxml();
}
$xajax->registerfunction("show_catalog");

//Display the product image in different browser
function bgWithBrowser($objresponse, $div_id, $image_name) {
	//If browser is Firefox
	if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']),"firefox"))
		$objresponse->addassign($div_id,"style.background-image","url(".HTTP_SERVER.DIR_WS_CATALOG.DIR_WS_IMAGES.$image_name.") no-repeat");
	else
	//If browser is IE
		$objresponse->addassign($div_id,"style.filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=image, src='".HTTP_SERVER.DIR_WS_CATALOG.DIR_WS_IMAGES.$image_name."')");
}

//Display the product by product ID
function show_products($products_id,$project_id=0) {//��Ʒ�༭��
	global $db,$color_options,$design_product_type,$shorts_position_array,$default_shorts_position,$jersey_front_position_array,$default_jersey_front_position,$jersey_back_position_array,$default_jersey_back_position;
	$objresponse = new xajaxresponse();
//$objresponse->addalert("show products");

	initialize_color_options();

	//customer has to login
	if (!$_SESSION['customer_id']) {
		$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
		return $objresponse->getxml();
	}
	//If there is project_id, get the design from DB
	if($project_id!=0)
		$objresponse->addscript("xajax_get_team_list('false');");
	$objresponse->addscript("xajax_get_exist_design('".$products_id."');");
 
     //short categaory id=113, uniform categaory id=65
	$design_product_type='jersey';
	$shorts_categories_id=113;
	$categories_query = "select * from ".DESIGN_PRODUCTS. " where design_products_type ='shorts'";
	$design_type=$db->Execute($categories_query);
	if ($design_type->RecordCount() > 0) {
		$shorts_categories_id=$design_type->fields['categories_id'];
	}
	$products_query = "select *, ca.parent_id
					 from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c,".TABLE_CATEGORIES." ca
					 where p.products_id = p2c.products_id
					 and p.products_status = '1'
					 and p.products_id = ".$products_id."
					 and p.products_id = p2c.products_id
					 and p2c.categories_id 	= ca.categories_id"; 	
	$parents_categories = $db->Execute($products_query);
	if ($parents_categories->RecordCount() > 0) {
	  while (!$parents_categories->EOF) {
		if ($parents_categories->fields['parent_id'] == $shorts_categories_id) 
			$design_product_type='shorts';
		$parents_categories->MoveNext();
	  }
	}

//	if($design_product_type=='jersey') {
//$objresponse->addAlert("jersey=".$design_product_type);
//		$objresponse->addscript("design_type='jersey';");
//	} else {
//$objresponse->addAlert("short=".$design_product_type);
//		$objresponse->addscript("design_type='shorts';");
//	}

	$objresponse->addassign("design_front","innerHTML","");//���ǰ�༭��
	$objresponse->addassign("design_back","innerHTML","");//��պ�༭��
	$objresponse->addassign("design_sleeve_left","innerHTML","");//��պ�༭��
	$objresponse->addassign("design_sleeve_right","innerHTML","");//��պ�༭��
	$objresponse->addscript("document.return_form.reset();");//�����Ʊ���
	$objresponse->addscript("products_id=".$products_id.";end_edit=false;start_edit=true;");//����js����
	$objresponse->addassign("design_front","style.display","block");//ǰ�༭����ʾ
	$objresponse->addassign("choose","style.display","none");//���༰��Ʒѡ������
	$objresponse->addassign("design_tool","style.display","block");//���߱༭����ʾ
	$objresponse->addassign("add_area_front","style.display","block");//ǰ��������ʾ
	$objresponse->addassign("save_project","style.display","none");//������ѡ�������
	
//$objresponse->addalert("design_product_type=". $design_product_type);//

	$design=$db->Execute("select * from ".DESIGN_PRODUCTS." where products_id=0");
	//if($design->RecordCount()>0) {
		//����Ĭ�ϱ�����
		//$image=$db->Execute("select * from ".DESIGN_PRODUCTS_IMAGES." where design_products_image_default=1 and products_id=".(int)$products_id." order by design_products_image_name");//��ѯ�Ѿ����ڵĶ̿�ͼƬ
		$image=$db->Execute("select * from ".DESIGN_PRODUCTS_IMAGES." where design_products_image_default=1 and design_products_name ='".$design_product_type."'");
		while(!$image->EOF) { 
			$div_id="design_list_front_".$image->fields['design_products_image_name'];//������ʱ����
			$objresponse->addCreate("design_list_front","div",$div_id);
			$div_id="design_list_back_".$image->fields['design_products_image_name'];//������ʱ����
			$objresponse->addCreate("design_list_back","div",$div_id);
			$objresponse->addscript("xajax_show_bg_list(".$products_id.",".$image->fields['design_products_image_id'].",'".$image->fields['design_products_image_name']."');");//����������б�
			//Front image
			if(file_exists(DIR_WS_IMAGES.$image->fields['design_products_image_front']) and $image->fields['design_products_image_front']!='') {
				$image_size=@getimagesize(DIR_WS_IMAGES.$image->fields['design_products_image_front']);
				$div_id="bgfront_".$image->fields['design_products_image_id'];//������ʱ����
				$objresponse->addCreate("design_front","div",$div_id);
				$objresponse->addassign($div_id,"style.position","absolute");
				$objresponse->addassign($div_id,"style.cursor","pointer");
				$objresponse->addassign($div_id,"title",$image->fields['design_products_image_name']);
				$objresponse->addassign($div_id,"style.width",$image_size[0]."px");
				$objresponse->addassign($div_id,"style.height",$image_size[1]."px");
				bgWithBrowser($objresponse, $div_id, $image->fields['design_products_image_front']);
				$objresponse->addscript('bg_height='.$image_size[1]);//�������
			}

			//Back image
			if($design_product_type=="jersey") {
				if(file_exists(DIR_WS_IMAGES.$image->fields['design_products_image_back']) and $image->fields['design_products_image_back']!='') {
					$image_size=@getimagesize(DIR_WS_IMAGES.$image->fields['design_products_image_back']);
					$div_id="bgback_".$image->fields['design_products_image_id'];//������ʱ����
					$objresponse->addCreate("design_back","div",$div_id);
					$objresponse->addassign($div_id,"style.position","absolute");
					//$objresponse->addassign($div_id,"style.cursor","pointer");
					$objresponse->addassign($div_id,"title",$image->fields['design_products_image_name']);
					$objresponse->addassign($div_id,"style.width",$image_size[0]."px");
					$objresponse->addassign($div_id,"style.height",$image_size[1]."px");
					bgWithBrowser($objresponse, $div_id, $image->fields['design_products_image_back']);
				}
				//sleeve image
				if(file_exists(DIR_WS_IMAGES.$image->fields['design_products_image_sleeve']) and $image->fields['design_products_image_sleeve']!='') {
					$image_size=@getimagesize(DIR_WS_IMAGES.$image->fields['design_products_image_sleeve']);
					$div_id="bgsleeve_left_".$image->fields['design_products_image_id'];//������ʱ����
					$objresponse->addCreate("design_sleeve_left","div",$div_id);
					$objresponse->addassign($div_id,"style.position","absolute");
					//$objresponse->addassign($div_id,"style.cursor","pointer");
					$objresponse->addassign($div_id,"title",$image->fields['design_products_image_name']);
					$objresponse->addassign($div_id,"style.width",$image_size[0]."px");
					$objresponse->addassign($div_id,"style.height",$image_size[1]."px");
					bgWithBrowser($objresponse, $div_id, $image->fields['design_products_image_sleeve']);

					$div_id="bgsleeve_right_".$image->fields['design_products_image_id'];//������ʱ����
					$objresponse->addCreate("design_sleeve_right","div",$div_id);
					$objresponse->addassign($div_id,"style.position","absolute");
					//$objresponse->addassign($div_id,"style.cursor","pointer");
					$objresponse->addassign($div_id,"title",$image->fields['design_products_image_name']);
					$objresponse->addassign($div_id,"style.width",$image_size[0]."px");
					$objresponse->addassign($div_id,"style.height",$image_size[1]."px");
					bgWithBrowser($objresponse, $div_id, $image->fields['design_products_image_sleeve']);
				
				}
			}
			$image->MoveNext();
		}
	//set the default customer email address
    $sql = "SELECT customers_id, customers_firstname, customers_lastname, customers_password, customers_email_address, customers_default_address_id 
             FROM " . TABLE_CUSTOMERS . " 
             WHERE customers_id = :customersID";
      
    $sql = $db->bindVars($sql, ':customersID', $_SESSION['customer_id'], 'integer');
    $check_customer = $db->Execute($sql);
    //$customer_email= $check_customer->fields['customers_email_address'];
    //$customer_name= $check_customer->fields['customers_firstname'] . ' ' . $check_customer->fields['customers_lastname'];
	$objresponse->addassign("client_email_address","value",$check_customer->fields['customers_email_address']);
	//����
	if($design_product_type=="shorts") {//�̿�
		$objresponse->addScript("design_type='shorts';");//��ֵjsֵ ��ʾ���������б�
		//$objresponse->addassign('f2p','style.display','');
		//$objresponse->addassign('f2t','style.display','');
		//λ��
		$objresponse->addassign('logo_position','outerHTML',zen_draw_pull_down_menu("logo_position",$shorts_position_array,$default_shorts_position,'rel="dropdown"'));
		$objresponse->addassign('front_team_name_position','outerHTML',zen_draw_pull_down_menu("front_team_name_position",$shorts_position_array,$default_shorts_position,'rel="dropdown"'));
		$objresponse->addassign('front_nums_position','outerHTML',zen_draw_pull_down_menu("front_nums_position",$shorts_position_array,$default_shorts_position,'rel="dropdown"'));
	} elseif($design_product_type=="jersey") {//����
		$objresponse->addScript("design_type='jersey';");//��ֵjsֵ ��ʾ���������б�
		//$objresponse->addassign('f2p','style.display','');
		//$objresponse->addassign('f2b','style.display','');
		//������༭��
		//create design back edit area
		createDesignArea($objresponse, "back", $design);
		//create design sleeve edit area
		createDesignArea($objresponse, "sleeve_left", $design);
		//create design sleeve edit area
		createDesignArea($objresponse, "sleeve_right", $design);
	}
	//front design
	createDesignArea($objresponse, "front", $design);
	if($project_id!=0)//���뷽��
		$objresponse->addscript("xajax_load_project('".$project_id."');");
	else {//���������Ƿ���������
		$objresponse->addscript("document.return_form.reset();");
	}


return $objresponse->getxml();
}
$xajax->registerfunction("show_products");

//Create Graphic edit area and input area for front, back, and sleeve
function createDesignArea($objresponse,$side, $design) {//ȡ��������ɫ����
		//create design back edit area
		$design_div=$side."_edit_area";
		$objresponse->addCreate("design_".$side,"div",$design_div);
		$objresponse->addassign($design_div,"style.position","relative");
		$objresponse->addassign($design_div,"style.display","block");
		$objresponse->addassign($design_div,"style.top",$design->fields['design_products_top']."px");
		$objresponse->addassign($design_div,"style.left",$design->fields['design_products_left']."px");
		$objresponse->addEvent($design_div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($design_div,"onmouseout","this.style.border='';");
		$objresponse->addassign($design_div,"style.width",$design->fields['design_products_width']."px");
		$objresponse->addassign($design_div,"style.height",$design->fields['design_products_height']."px");
		//����ǰlogo
		$input_div=$side."_input";
		$objresponse->addCreate($design_div,"div",$input_div);
		$objresponse->addassign($input_div,"style.position","absolute");
		$objresponse->addassign($input_div,"style.width",$design->fields['design_products_width']."px");
		$objresponse->addassign($input_div,"style.height",$design->fields['design_products_height']."px");
		$objresponse->addassign($input_div,"style.clip","rect(0px,".$design->fields['design_products_width']."px,".$design->fields['design_products_height']."px,0px)");
		//������logo
		$create_div=$side."_logo";
		$objresponse->addCreate($input_div,"div",$create_div);
		$objresponse->addassign($create_div,"style.position","absolute");
		$objresponse->addassign($create_div,"style.left","0px");
		$objresponse->addassign($create_div,"style.top","10px");
		$objresponse->addassign($create_div,"style.cursor","move");
		$objresponse->addEvent($create_div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($create_div,"onmouseout","this.style.border=''");
		$objresponse->addScript("drag('".$create_div."');");
}

function get_exist_design($products_id) {//ȡ��������ɫ����
	global $db;
	$objresponse = new xajaxresponse();
	if (!$_SESSION['customer_id']) {
		$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
		return $objresponse->getxml();
	}
	$design_exist=$db->Execute("select de.*,dpi.design_products_name from ".DESIGN_EXIST." de,".DESIGN_PRODUCTS_IMAGES." dpi
						where de.design_products_image_id=dpi.design_products_image_id
						and de.products_id=".(int)$products_id." order by dpi.design_products_name");

	while(!$design_exist->EOF) {
		$team_id=$design_exist->fields['team_id'];
		$team_id_array[$team_id][]=$design_exist->fields["design_products_image_id"];
		$team_name_array[$team_id][]=$design_exist->fields["design_products_name"];
		$design_exist->MoveNext();
	}
	if(is_array($team_id_array) and sizeof($team_id_array)>1) {
		while(list($team_id,$team_array)=each($team_id_array)) {
		$show.="<span style='cursor:pointer;color:blue;' onclick=\"xajax_load_exist('".implode(",",$team_array)."',cp_$('design_front').innerHTML);\"><u>".implode("/",$team_name_array[$team_id])."</u></span>, ";
		//$exist_array[]=array("id"=>implode(",",$team_array[$i]),"text"=>implode(" and ",$team_name_array[$i]));
		}
		//$objresponse->addassign("design_default_exist","innerHTML",print_r($team_id_array,true));
		//zen_draw_pull_down_menu("choose_exist_option",$exist_array,'',"onchange=\"xajax_load_exist(this.value,cp_$('design_front').innerHTML);\"")
		$objresponse->addassign("design_default_exist","innerHTML",sprintf(TEXT_SELECT_STOCK_COLOR,$show));
	}
	return $objresponse->getxml();
}
$xajax->registerfunction("get_exist_design");

function load_exist($date,$html) {//����������ɫ����
	global $db;
	$objresponse = new xajaxresponse();
	if (!$_SESSION['customer_id']) {
		$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
		return $objresponse->getxml();
	}
	preg_match_all("/DIV\sid=bgfront_([0-9])\stitle=([a-z]+)/",$html,$bg_id);//ȡ��bg id
	$bg=array_combine($bg_id[2],$bg_id[1]);
	if(zen_not_null($date)) {
		$date=explode(",",$date);
		for($i=0;$i<sizeof($date);$i++) {
			$image=$db->Execute("select products_id,design_products_image_name from ".DESIGN_PRODUCTS_IMAGES." where design_products_image_id=".$date[$i]);
			if(!$image->EOF) {
				$objresponse->addScript("xajax_show_bg_list(".$image->fields['products_id'].",".$date[$i].",'".$image->fields['design_products_image_name']."','".$bg[$image->fields['design_products_image_name']]."');");
			}
		}
	}
	return $objresponse->getxml();
}
$xajax->registerfunction("load_exist");

//show background list of Front and Back
function show_bg_list($products_id,$image_id,$design_name,$default='') {//��Ʒ�����б�
global $db;
$objresponse = new xajaxresponse();
if (!$_SESSION['customer_id']) {
$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
return $objresponse->getxml();
}
$design_list=$db->Execute("select * from ".DESIGN_PRODUCTS_IMAGES." where  design_products_image_name='".$design_name."' and products_id=".$products_id);
	if($design_list->RecordCount()>0) {//����б�������ѡ�� 
		while(!$design_list->EOF) {
			$show_front.=zen_image(DIR_WS_IMAGES.$design_list->fields['design_products_image_front'],'',50,50,"style='cursor:pointer;' onclick=\"xajax_change_bg('".$image_id."','".$design_list->fields['design_products_image_id']."');\"".(($image_id==$design_list->fields['design_products_image_id'])?" border=1":""));
			$show_back.=zen_image(DIR_WS_IMAGES.$design_list->fields['design_products_image_back'],'',50,50,"style='cursor:pointer;' onclick=\"xajax_change_bg('".$image_id."','".$design_list->fields['design_products_image_id']."');\"".(($image_id==$design_list->fields['design_products_image_id'])?" border=1":""));
			$design_list->MoveNext();
		}
	$objresponse->addassign("design_list_front_".$design_name,"innerHTML",$show_front);//�������ʾ����
	$objresponse->addassign("design_list_back_".$design_name,"innerHTML",$show_back);//�������ʾ����
	if($default!='')
	$objresponse->addScript("xajax_change_bg('".$default."','".$image_id."');");
	}
return $objresponse->getxml();
}
$xajax->registerfunction("show_bg_list");

function change_bg($image_id,$design_id) {//�滻����
global $db;
$objresponse = new xajaxresponse();
if (!$_SESSION['customer_id']) {
$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
return $objresponse->getxml();
}
$design_list=$db->Execute("select * from ".DESIGN_PRODUCTS_IMAGES." where design_products_image_id=".$design_id);
	//ǰ
	$div_id="bgfront_".$image_id;//��id
	if(file_exists(DIR_WS_IMAGES.$design_list->fields['design_products_image_front'])) {
	$image_size=@getimagesize(DIR_WS_IMAGES.$design_list->fields['design_products_image_front']);
	$objresponse->addassign($div_id,"style.width",$image_size[0]."px");
	$objresponse->addassign($div_id,"style.height",$image_size[1]."px");
	if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']),"firefox"))
	$objresponse->addassign($div_id,"style.background-image","url(".HTTP_SERVER.DIR_WS_CATALOG.DIR_WS_IMAGES.$design_list->fields['design_products_image_front'].") no-repeat");
	$objresponse->addassign($div_id,"style.filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=image, src='".HTTP_SERVER.DIR_WS_CATALOG.DIR_WS_IMAGES.$design_list->fields['design_products_image_front']."')");
	$objresponse->addRemoveHandler($div_id, "onclick", "xajax_show_bg_list");
	$objresponse->addEvent($div_id,"onclick","xajax_show_bg_list(".$design_list->fields['products_id'].",".$design_id.",0,'".$design_list->fields['design_products_image_name']."');");
	$objresponse->addassign("bgfront_".$image_id,"id","bgfront_".$design_id);//������id
	}
	//��
	$div_id="bgback_".$image_id;
	if(file_exists(DIR_WS_IMAGES.$design_list->fields['design_products_image_back'])) {
	$image_size=@getimagesize(DIR_WS_IMAGES.$design_list->fields['design_products_image_back']);
	$objresponse->addassign($div_id,"style.width",$image_size[0]."px");
	$objresponse->addassign($div_id,"style.height",$image_size[1]."px");
	if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']),"firefox"))
	$objresponse->addassign($div_id,"style.background-image","url(".HTTP_SERVER.DIR_WS_CATALOG.DIR_WS_IMAGES.$design_list->fields['design_products_image_back'].") no-repeat");
	$objresponse->addassign($div_id,"style.filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=image, src='".HTTP_SERVER.DIR_WS_CATALOG.DIR_WS_IMAGES.$design_list->fields['design_products_image_back']."')");
	$objresponse->addRemoveHandler($div_id, "onclick", "xajax_show_bg_list");
	$objresponse->addEvent($div_id,"onclick","xajax_show_bg_list(".$design_list->fields['products_id'].",".$design_id.",0,'".$design_list->fields['design_products_image_name']."');");
	$objresponse->addassign("bgback_".$image_id,"id","bgback_".$design_id);
	}
$objresponse->addscript("xajax_show_bg_list(".$design_list->fields['products_id'].",".$design_id.",'".$design_list->fields['design_products_image_name']."');");
//$objresponse->addassign("design_list","style.display",'none');
$objresponse->addscript('get_bg_list("'.$design_list->fields['products_id'].'")');
return $objresponse->getxml();
}
$xajax->registerfunction("change_bg");

function alert_bg($date,$products_id) {
global $db;
$objresponse = new xajaxresponse();
if (!$_SESSION['customer_id']) {
$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
return $objresponse->getxml();
}
	if(is_array($date)) {
	$err=true;
	$team_id=$db->Execute("select * from ".DESIGN_EXIST." where products_id=".$products_id);
	while(!$team_id->EOF) {
	$team_array[$team_id->fields['team_id']][]=$team_id->fields['design_products_image_id'];
	$team_id->MoveNext();
	}
	while(list($k,$v)=each($team_array)) {
		$result=array_intersect($team_array[$k],$date);
		if(sizeof($result)==sizeof($date)) {
		$err=false;
		break;
		} else{
		$err=true;
		}
	}
		if($err) {
		$objresponse->addassign("bg_change_err","style.display",'');
		//$objresponse->addassign("bg_change_err","innerHTML",print_r($team_array[$i],true).print_r($date,true));
		}else
		$objresponse->addassign("bg_change_err","style.display",'none');
	}else
	$objresponse->addassign("bg_change_err","style.display",'none');
return $objresponse->getxml();
}
$xajax->registerfunction("alert_bg");
//---------Team Name Functions---------------//
//Insert_team_name
function insert_team_name($date,$side) {//���Ӷ���
//indicate the back, front or sleave
$work_side = $side;
//indicate the form value name
$side="front";
$objresponse = new xajaxresponse();
if (!$_SESSION['customer_id']) {
$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
return $objresponse->getxml();
}
if(zen_not_null($date[$side.'_insert_team_name_text'])) {
$image_name=DIR_IMAGE_TMP.time().rand(10000,99999).".png";
if(zen_not_null($date[$side.'_team_name_distort'])) {
if($date[$side.'_team_name_distort']=="concave")
$distort=-$date[$side.'_team_name_radian'];
if($date[$side.'_team_name_distort']=="convex")
$distort=$date[$side.'_team_name_radian'];
}
if($date[$side.'_team_name_cavity']==1) {//�п�����
$image_size=text_to_image((string)$date[$side.'_insert_team_name_text'],$image_name,'transparent',$date[$side.'_team_name_size'],"font/".$date[$side.'_team_name_font'],$distort,$date[$side.'_team_name_color'],2,$date[$side.'_team_name_italic'], $date[$side.'_team_name_shape']);
} else {
$image_size=text_to_image((string)$date[$side.'_insert_team_name_text'],$image_name,$date[$side.'_team_name_color'],$date[$side.'_team_name_size'],"font/".$date[$side.'_team_name_font'],$distort,"",$date[$side.'_team_name_blod'],$date[$side.'_team_name_italic'], $date[$side.'_team_name_shape']);
}

if(isset($_SESSION[$work_side."_team_name"])) {
	for($i=0;$i<sizeof($_SESSION[$work_side."_team_name"]);$i++) {
		$div_id = $work_side."_team_name_".$i;
		if (isset($_SESSION[$work_side."_team_name"][$div_id]) && ($_SESSION[$work_side."_team_name"][$div_id]!=null)) {
			unset($div_id);
		} else 
			break;
	}

}
if (!isset($div_id)) 
	$div_id=$work_side."_team_name_".sizeof($_SESSION[$work_side."_team_name"]);
//$objresponse->addAlert($div_id);

$objresponse->addCreate($work_side."_input","div",$div_id);
$objresponse->addassign($div_id,"style.position","absolute");
$objresponse->addassign($div_id,"style.left","0px");
$objresponse->addassign($div_id,"style.top","10px");
$objresponse->addassign($div_id,"style.cursor","move");
$objresponse->addEvent($div_id,"onmouseover","this.style.border='1px solid #fff'");
$objresponse->addEvent($div_id,"onmouseout","this.style.border='';");
$objresponse->addScript("drag('".$div_id."');");

//set the current working div_id
$objresponse->addassign('current_text_id',"value",$div_id);

$objresponse->addassign($div_id,"title",zen_output_string($date[$side.'_insert_team_name_text']));
if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']),"firefox"))
	$objresponse->addassign($div_id,"style.background-image","url(".HTTP_SERVER.DIR_WS_CATALOG.$image_name.") no-repeat");
else
	$objresponse->addassign($div_id,"style.filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=image, src='".HTTP_SERVER.DIR_WS_CATALOG.$image_name."')");
$objresponse->addassign($div_id,"style.width",($image_size[0]-2)."px");
$objresponse->addassign($div_id,"style.height",($image_size[1]-2)."px");
//front_team_name session array key=div_id, value an array of the form values;
if(isset($_SESSION[$work_side."_team_name"]))
    $_SESSION[$work_side."_team_name"][$div_id] =$date;
else {
	$_SESSION[$work_side."_team_name"] = array();
    $_SESSION[$work_side."_team_name"][$div_id] = $date;
	}
//$objresponse->addscript("document.return_form.".$side."_team_name.".value=\"".base64_encode(serialize($date))."\";");//���ر��� team_name='".$side.'_insert_team_name_text']."'&team_color='".$date[$side.'_team_name_color']."&team_size=".."&team_font=".."&team_distort=".."&team_blod=".."&team_italic=".."
//$objresponse->addscript("document.return_form.".$div_id.".value=\"".serialize($date)."\";");//���ر��� team_name='".$side.'_insert_team_name_text']."'&team_color='".$date[$side.'_team_name_color']."&team_size=".."&team_font=".."&team_distort=".."&team_blod=".."&team_italic=".."
//$objresponse->addalert(print_r($date));
$objresponse->addassign($div_id,"style.display",'');
$objresponse->addEvent($div_id,"onclick","display_text(this);");

$objresponse->addassign("team_name_auto_size","style.display",'block');
$objresponse->addscript('reset_position("'.$div_id.'");');
}
//$objresponse->addalert($objresponse->getxml());

return $objresponse->getxml();
}
$xajax->registerfunction("insert_team_name");

function update_team_name($date,$side,$zoom) {//���Ӷ���
//indicate the back, front or sleave
$work_side = $side;
//indicate the form value name
$side="front";
$objresponse = new xajaxresponse();
if (!$_SESSION['customer_id']) {
$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
return $objresponse->getxml();
}
if(zen_not_null($date[$side.'_insert_team_name_text'])) {
$image_name=DIR_IMAGE_TMP.time().rand(10000,99999).".png";
if(zen_not_null($date[$side.'_team_name_distort'])) {
if($date[$side.'_team_name_distort']=="concave")
$distort=-$date[$side.'_team_name_radian'];
if($date[$side.'_team_name_distort']=="convex")
$distort=$date[$side.'_team_name_radian'];
}
$date[$side.'_team_name_size'] = $date[$side.'_team_name_size']+ $zoom;
$objresponse->addassign($side.'_team_name_size',"value",$date[$side.'_team_name_size']);
if($date[$side.'_team_name_cavity']==1) {//�п�����
$image_size=text_to_image($date[$side.'_insert_team_name_text'],$image_name,'transparent',$date[$side.'_team_name_size'],"font/".$date[$side.'_team_name_font'],$distort,$date[$side.'_team_name_color'],2,$date[$side.'_team_name_italic'], $date[$side.'_team_name_shape']);
} else {
$image_size=text_to_image($date[$side.'_insert_team_name_text'],$image_name,$date[$side.'_team_name_color'],$date[$side.'_team_name_size']+1,"font/".$date[$side.'_team_name_font'],$distort,"",$date[$side.'_team_name_blod'],$date[$side.'_team_name_italic'], $date[$side.'_team_name_shape']);
}
$div_id=$date['current_text_id'];//����ǰlogo
$objresponse->addassign($div_id,"title",zen_output_string($date[$side.'_insert_team_name_text']));
if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']),"firefox"))
$objresponse->addassign($div_id,"style.background-image","url(".HTTP_SERVER.DIR_WS_CATALOG.$image_name.") no-repeat");
$objresponse->addassign($div_id,"style.filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=image, src='".HTTP_SERVER.DIR_WS_CATALOG.$image_name."')");
$objresponse->addassign($div_id,"style.width",($image_size[0]-2)."px");
$objresponse->addassign($div_id,"style.height",($image_size[1]-2)."px");
$objresponse->addscript("document.return_form.".$div_id.".value=\"".base64_encode(serialize($date))."\";");//���ر��� team_name='".$side.'_insert_team_name_text']."'&team_color='".$date[$side.'_team_name_color']."&team_size=".."&team_font=".."&team_distort=".."&team_blod=".."&team_italic=".."
$objresponse->addassign($div_id,"style.display",'');
$objresponse->addassign($div_id."_auto_size","style.display",'block');
$objresponse->addassign($div_id,"style.display",'');
$objresponse->addscript('reset_position("'.$div_id.'");');
//update the session var
$_SESSION[$work_side."_team_name"][$div_id] = $date;
}
return $objresponse->getxml();
}
$xajax->registerfunction("update_team_name");

//Set_team_name_form from session variable, exec when the text is clicked on the right design area
function set_team_name_form($div_id, $side) {
	$objresponse = new xajaxresponse();
	if(isset($_SESSION[$side."_team_name"][$div_id]))
		$date=$_SESSION[$side."_team_name"][$div_id];
		
		if(zen_not_null($date)) {
			//$objresponse->addassign("click_front_team_name","checked","checked");//checkboxѡ��
			$objresponse->addassign("add_front_team_name","style.display","");//���ӱ�����ʾ
			$objresponse->addassign("front_team_name_auto_size","style.display","");//������ť��ʾ

			$objresponse->addassign("front_insert_team_name_text","value",$date["front_insert_team_name_text"]);//��Ա��
			$objresponse->addassign("front_team_name_size","value",$date["front_team_name_size"]);//�����С ����ѡ��
			$objresponse->addassign("front_team_name_font","value",$date["front_team_name_font"]);//����������
			$objresponse->addassign("front_team_name_png","innerHTML",zen_image("font/".$date["front_team_name_font"].".png","",170));//ѡ�������б�
			$objresponse->addassign("front_team_name_shape","value",$date["front_team_name_shape"]);//����������
			$objresponse->addassign("front_team_name_shape_png","innerHTML",zen_image("shape/".$date["front_team_name_shape"],"",170));//ѡ�������б�
			
			//set current working div_id
			$objresponse->addassign('current_text_id',"value",$div_id);
			//�Ӵ�
			if(zen_not_null($date["front_team_name_blod"]))
				$objresponse->addassign("front_team_name_blod","checked","checked");
			else
				$objresponse->addassign("front_team_name_blod","checked","");
			//б��
			if(zen_not_null($date["front_team_name_italic"]))
				$objresponse->addassign("front_team_name_italic","checked","checked");
			else
				$objresponse->addassign("front_team_name_italic","checked","");
			//��͹
			if(zen_not_null($date["front_team_name_distort"])) {
				if($date["front_team_name_distort"]=='concave') 
					$objresponse->addassign("add_front_team_name_form","front_team_name_distort[1].checked","checked");
				else if($date["front_team_name_distort"]=='convex')
					$objresponse->addassign("add_front_team_name_form","front_team_name_distort[2].checked","checked");
				else
					$objresponse->addassign("add_front_team_name_form","front_team_name_distort[0].checked","checked");
			}
			$objresponse->addassign("front_team_name_radian","value",$date["front_team_name_radian"]);//��������
			$objresponse->addassign("front_team_name_color","value",$date["front_team_name_color"]);//������ɫ
			$objresponse->addassign("front_team_name_colorpad","style.backgroundColor",$date["front_team_name_color"]);//ȡɫ��	
		}
	$objresponse->addscript('toptabs.expandit(2);');
	return $objresponse->getxml();
}
$xajax->registerfunction("set_team_name_form");

//remove_team_name will clear the session cache
function remove_team_name($side, $div_id) {
	$objresponse = new xajaxresponse();
//$objresponse->addAlert($div_id);	
//$objresponse->addAlert(sizeof($_SESSION[$side."_team_name"]));	
	unset($_SESSION[$side."_team_name"][$div_id]);
//$objresponse->addAlert(sizeof($_SESSION[$side."_team_name"]));	
	return $objresponse->getxml();
}
$xajax->registerfunction("remove_team_name");

//Insert Team Number and Name
function insert_team_name_nums($date,$side) {//���Ӻ���
	$objresponse = new xajaxresponse();
	if (!$_SESSION['customer_id']) {
		$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
		return $objresponse->getxml();
	}
	if ($date['click_nums']) {
		//Create the div for team number
		$div_id=$side."_nums";
		$objresponse->addCreate($side."_input","div",$div_id);
		$objresponse->addassign($div_id,"style.position","absolute");
		$objresponse->addassign($create_div,"style.top","30px");
		$objresponse->addassign($create_div,"style.left","0px");
		$objresponse->addassign($div_id,"style.cursor","move");
		$objresponse->addEvent($div_id,"onmouseover","this.style.border='1px solid #fff'");
		$objresponse->addEvent($div_id,"onmouseout","this.style.border='';");
		$objresponse->addScript("drag('".$div_id."');");

		$image_name=DIR_IMAGE_TMP.time().rand(10000,99999).".png";
		$image_size=text_to_image('00',$image_name,$date['team_nums_color'],$date['team_nums_size'],'font/MACHINEN.TTF',0,$date['team_nums_out_color'],(zen_not_null($date['team_nums_out_color'])?3:''));//Ĭ������Ϊ00 Ĭ����߳ߴ�Ϊ3����
		if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']),"firefox"))
			$objresponse->addassign($div_id,"style.background-image","url(".HTTP_SERVER.DIR_WS_CATALOG.$image_name.") no-repeat");
		else 
			$objresponse->addassign($div_id,"style.filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=image, src='".HTTP_SERVER.DIR_WS_CATALOG.$image_name."')");
		$objresponse->addassign($div_id,"style.width",($image_size[0]-2)."px");
		$objresponse->addassign($div_id,"style.height",($image_size[1]-2)."px");
		$objresponse->addassign($div_id,"style.display",'');
		$objresponse->addEvent($div_id,"onclick","display_name_nums();");
		$objresponse->addscript("xajax_get_team_list('false');");
		$objresponse->addscript("document.return_form.".$div_id.".value=\"".base64_encode(serialize($date))."\";");//���ر�
		$objresponse->addscript('reset_position("'.$div_id.'");');
	}
	if ($date['click_name']) {
		$div_id=$side."_name";
		$objresponse->addCreate($side."_input","div",$div_id);
		$objresponse->addassign($div_id,"style.position","absolute");
		$objresponse->addassign($div_id,"style.left","0px");
		$objresponse->addassign($div_id,"style.top","80px");
		$objresponse->addassign($div_id,"style.cursor","move");
		$objresponse->addEvent($div_id,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div_id,"onmouseout","this.style.border=''");
		$objresponse->addScript("drag('".$div_id."');");
	
		$image_name=DIR_IMAGE_TMP.time().rand(10000,99999).".png";
		$image_size=text_to_image('NAME',$image_name,$date['name_color'],20,'font/MACHINEN.TTF');//Ĭ������ΪNAME
		if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']),"firefox"))
			$objresponse->addassign($div_id,"style.background-image","url(".HTTP_SERVER.DIR_WS_CATALOG.$image_name.") no-repeat");
		else
			$objresponse->addassign($div_id,"style.filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=image, src='".HTTP_SERVER.DIR_WS_CATALOG.$image_name."')");
		$objresponse->addassign($div_id,"style.width",($image_size[0]-2)."px");
		$objresponse->addassign($div_id,"style.height",($image_size[1]-2)."px");
		$objresponse->addscript("document.return_form.".$div_id.".value=\"".base64_encode(serialize($date))."\";");//���ر�
		$objresponse->addscript("xajax_get_team_list('true');");
		$objresponse->addassign($div_id,"style.display",'');
		$objresponse->addEvent($div_id,"onclick","display_name_nums();");
		$objresponse->addscript('reset_position("'.$div_id.'");');
	}
	return $objresponse->getxml();
}
$xajax->registerfunction("insert_team_name_nums");

//set_name_nums_form from hidden field variable, exec when the text is clicked on the right design area
function set_name_nums_form($nums, $name) {
	$objresponse = new xajaxresponse();

//$objresponse->addAlert('set_name_nums_form');
//$objresponse->addAlert($nums);
//$objresponse->addAlert($name);
		$objresponse->addassign("add_front_nums","style.display","");
	
		if(zen_not_null($nums)) {
			$form=unserialize(base64_decode($nums));
//$objresponse->addAlert($form['click_nums']);
			if ($form['click_nums'])
				$objresponse->addassign("click_nums","checked","checked");
			$objresponse->addassign("team_nums_color","value",$form['team_nums_color']);//������ɫ
			$objresponse->addassign("team_nums_colorpad","style.backgroundColor",$form['team_nums_color']);//ȡɫ��
			$objresponse->addassign("team_nums_out_color","value",$form['team_nums_out_color']);//���뻷����ɫ
			$objresponse->addassign("team_nums_out_colorpad","style.backgroundColor",$form['team_nums_out_color']);//ȡɫ��
			if(zen_not_null($form['team_nums_out_color']) and $form['team_nums_out_color']!='')//
				$objresponse->addassign("team_nums_out_colorpad","innerHTML",'&nbsp;&nbsp;&nbsp;&nbsp;');
			else
				$objresponse->addassign("team_nums_out_colorpad","innerHTML",'&nbsp;X&nbsp;');
			$objresponse->addassign("team_nums_color","value",$form['team_nums_color']);//������ɫ
			$objresponse->addassign("team_nums_size","value",$form["team_nums_size"]);
		} else {
			$objresponse->addassign("click_nums","checked","");
		}
		
		if(zen_not_null($name)) {
			$form=unserialize(base64_decode($name));
			if ($form['click_name'])
				$objresponse->addassign("click_name","checked","checked");
			$objresponse->addassign("name_color","value",$form['name_color']);//������ɫ
			$objresponse->addassign("name_colorpad","style.backgroundColor",$form['name_color']);//ȡɫ��
		} else {
			$objresponse->addassign("click_name","checked","");
		}		
	$objresponse->addscript('toptabs.expandit(3);');
//$objresponse->addAlert($objresponse->getxml());
	
	return $objresponse->getxml();
}
$xajax->registerfunction("set_name_nums_form");
//tobe removed(old function)
function insert_team_nums($date,$side) {//���Ӻ���
$objresponse = new xajaxresponse();
if (!$_SESSION['customer_id']) {
$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
return $objresponse->getxml();
}
$image_name=DIR_IMAGE_TMP.time().rand(10000,99999).".png";
$image_size=text_to_image('00',$image_name,$date['team_nums_color'],$date['team_nums_size'],'font/MACHINEN.TTF',0,$date['team_nums_out_color'],(zen_not_null($date['team_nums_out_color'])?3:''));//Ĭ������Ϊ00 Ĭ����߳ߴ�Ϊ3����
$div_id=$side."_nums";//����ǰlogo
if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']),"firefox"))
$objresponse->addassign($div_id,"style.background-image","url(".HTTP_SERVER.DIR_WS_CATALOG.$image_name.") no-repeat");
$objresponse->addassign($div_id,"style.filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=image, src='".HTTP_SERVER.DIR_WS_CATALOG.$image_name."')");
$objresponse->addassign($div_id,"style.width",($image_size[0]-2)."px");
$objresponse->addassign($div_id,"style.height",($image_size[1]-2)."px");
$objresponse->addassign($div_id,"style.display",'');
$objresponse->addscript("document.return_form.".$div_id.".value=\"".base64_encode(serialize($date))."\";");//���ر�
$objresponse->addscript('reset_position("'.$div_id.'");');
return $objresponse->getxml();
}
$xajax->registerfunction("insert_team_nums");


//tobe removed(old function)
function insert_back_name($date) {//���Ӻ�������
$objresponse = new xajaxresponse();
if (!$_SESSION['customer_id']) {
$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
return $objresponse->getxml();
}
$image_name=DIR_IMAGE_TMP.time().rand(10000,99999).".png";
$image_size=text_to_image('NAME',$image_name,$date['back_name_color'],20,'font/MACHINEN.TTF');//Ĭ������ΪNAME
$div_id="back_name";//����ǰlogo
if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']),"firefox"))
$objresponse->addassign($div_id,"style.background-image","url(".HTTP_SERVER.DIR_WS_CATALOG.$image_name.") no-repeat");
$objresponse->addassign($div_id,"style.filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=image, src='".HTTP_SERVER.DIR_WS_CATALOG.$image_name."')");
$objresponse->addassign($div_id,"style.width",($image_size[0]-2)."px");
$objresponse->addassign($div_id,"style.height",($image_size[1]-2)."px");
$objresponse->addscript("document.return_form.".$div_id.".value=\"".base64_encode(serialize($date))."\";");//���ر�
$objresponse->addscript("xajax_get_team_list('true');");
$objresponse->addassign($div_id,"style.display",'');
$objresponse->addscript('reset_position("'.$div_id.'");');
return $objresponse->getxml();
}
$xajax->registerfunction("insert_back_name");

function insert_logo($logo,$side) {//����logo
$objresponse = new xajaxresponse();
if (!$_SESSION['customer_id']) {
$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
return $objresponse->getxml();
}
$div_id=$side."_logo";//����ǰlogo
$objresponse->addassign($div_id,"innerHTML",zen_image($logo));
$objresponse->addscript("document.return_form.".$div_id.".value=\"".$logo."|".$side."|\""."+document.upload_logo_form.logo_colornum.value+'|'+document.upload_logo_form.logo_embroidery.checked");//���ر�
//$objresponse->addscript("alert(\"value=\" + document.return_form.".$div_id.".value)");//���ر�
$objresponse->addassign("logo_auto_size","style.display",'block');
$objresponse->addassign("logo_delete","style.display",'block');
$objresponse->addassign($div_id,"style.display",'');
$objresponse->addscript('reset_position("'.$div_id.'");');
return $objresponse->getxml();
}
$xajax->registerfunction("insert_logo");

function logo_list($design_type,$side) {//Ԥ��logo
global $db;
$objresponse = new xajaxresponse();
if (!$_SESSION['customer_id']) {
$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
return $objresponse->getxml();
}
$logo_list=$db->Execute("select * from ".DESIGN_LOGO." where design_logo_type='".$design_type."'");
if($logo_list->RecordCount()>0) {
$show.="<div style='cursor:pointer;font-size:18;' align='center' onclick=\"display('".$side."_logo_list','none');hide_dropdowns('out');\">Close</div>";
	while(!$logo_list->EOF) {
		$show.="<div style='cursor:pointer;' onclick=\"xajax_insert_logo('".DIR_WS_IMAGES.$logo_list->fields['design_logo_url']."','".$side."');display('".$side."_logo_list','none');hide_dropdowns('out');\">".zen_image(DIR_WS_IMAGES.$logo_list->fields['design_logo_url'],$logo_list->fields['design_logo_name'])."</div>";
	$logo_list->MoveNext();
	}
//$show.="</form>";
$objresponse->addassign($side."_logo_list","innerHTML",$show);
$objresponse->addassign($side."_logo_list","style.display","block");
}
return $objresponse->getxml();
}
$xajax->registerfunction("logo_list");

function initialize_color_options() {
global $color_options;
$size_option_id=get_product_option_id_by_name('Colour');
$size_attributs=get_product_attributs($_GET['products_id'], $size_option_id);
if ($size_attributs->RecordCount()>0) {
	$subindex=0;
        while (!$size_attributs->EOF) {
		$option=array('id'=>$size_attributs->fields['products_options_values_name'],
			      'text'=>$size_attributs->fields['products_options_values_name']);
		$color_options[$subindex]=$option;
        	$subindex++;
        	$size_attributs->MoveNext();
        }

}
}


function initialize_size_options() {
global $member_size;
//initialize the $member_size array
//TODO:get Size option id from DB
$size_option_id=get_product_option_id_by_name('size');
$size_attributs=get_product_attributs($_GET['products_id'], $size_option_id);
if ($size_attributs->RecordCount()>0) {
	$subindex=0;
        while (!$size_attributs->EOF) {
		$option=array('id'=>$size_attributs->fields['products_options_values_name'],
			      'text'=>$size_attributs->fields['products_options_values_name']);
		$member_size[$subindex]=$option;
        	$subindex++;
        	$size_attributs->MoveNext();
        }

}
}

function get_team_list($type) {//ȡ�ö����б�
global $member_size;
initialize_size_options();
$objresponse = new xajaxresponse();
if (!$_SESSION['customer_id']) {
$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
return $objresponse->getxml();
}
//$objresponse->addalert($type);
if(zen_not_null($type)) {
	if($type=='true') {//����
	$table.="<input type='button' id='button_add_member' value='".TEXT_MEMBER_ADD_MEMBER."' onClick=\"addRow.call(this,'team_member_table','".str_replace('"',"\\'",zen_draw_input_field("member_name[]"))."','".str_replace('"',"\\'",ereg_replace("\n","",zen_draw_pull_down_menu("member_size[]",$member_size)))."','".str_replace('"',"\\'",zen_draw_input_field("member_nums[]",'00','size=2 maxlength=2'))."','".str_replace('"',"\\'",zen_draw_input_field("member_qty[]",'1','size=2'))."');\">";
	$table.="<input type='button' value='".TEXT_MEMBER_REMOVE_LAST."' onClick=\"deleteRow('team_member_table');\">";
	$table.='<table id="team_member_table">
		  <tr>
		  <td>'.TEXT_MEMBER_NAME.'</td><td>'.TEXT_MEMBER_SIZE.'</td><td>'.TEXT_MEMBER_NBUMBER.'</td><td>'.TEXT_MEMBER_QTY.'</td>
		  </tr>
		  <tr>
		  <td>'.zen_draw_input_field("member_name[]").'</td><td>'. zen_draw_pull_down_menu("member_size[]",$member_size).'</td><td>'.zen_draw_input_field("member_nums[]",'00','size=2 maxlength=2').'</td><td>'.zen_draw_input_field("member_qty[]",'1','size=2').'</td>
		  </tr>
		</table>';
	} else {//��ͷ
	$table.="<input type='button' id='button_add_member' value='".TEXT_MEMBER_ADD_MEMBER."' onClick=\"addRow.call(this,'team_member_table','".str_replace('"',"\\'",zen_draw_input_field("member_name[]",'','disabled=disabled style="background:#ccc;"'))."','".str_replace('"',"\\'",ereg_replace("\n","",zen_draw_pull_down_menu("member_size[]",$member_size)))."','".str_replace('"',"\\'",zen_draw_input_field("member_nums[]",'00','size=2 maxlength=2'))."','".str_replace('"',"\\'",zen_draw_input_field("member_qty[]",'1','size=2'))."');\">";
	$table.="<input type='button' value='".TEXT_MEMBER_REMOVE_LAST."' onClick=\"deleteRow('team_member_table');\">";
	$table.='<table id="team_member_table">
		  <tr>
		  <td>'.TEXT_MEMBER_NAME.'</td><td>'.TEXT_MEMBER_SIZE.'</td><td>'.TEXT_MEMBER_NBUMBER.'</td><td>'.TEXT_MEMBER_QTY.'</td>
		  </tr>
		  <tr>
		  <td>'.zen_draw_input_field("member_name[]",'','disabled="disabled" style="background:#ccc;"').'</td><td>'. zen_draw_pull_down_menu("member_size[]",$member_size).'</td><td>'.zen_draw_input_field("member_nums[]",'00','size=2 maxlength=2').'</td><td>'.zen_draw_input_field("member_qty[]",'1','size=2').'</td>
		  </tr>
		</table>';
	}
//$objresponse->addalert($table);
	$objresponse->addassign('team_member','innerHTML',$table);//����
}
return $objresponse->getxml();
}
$xajax->registerfunction("get_team_list");

function load_project($design_project_id) {
	global $db,$member_size, $color_options;
	initialize_size_options();

	$objresponse = new xajaxresponse();
	if (!$_SESSION['customer_id']) {
		$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
		return $objresponse->getxml();
	}

	$project_query=$db->Execute("select design_project_name,design_project_content,products_id from ".DESIGN_PROJECT." where design_project_id='".$design_project_id."' and customers_id=".(int)$_SESSION['customer_id']);//��ѯ
	if($project_query->RecordCount()>0) {
		$project=unserialize(base64_decode($project_query->fields['design_project_content']));

		//��Ʊ�ע
		$objresponse->addassign("design_des","value",$project["design_des"]);
		//��Ա��ע
		$objresponse->addassign("team_des","value",$project["team_des"]);
		//������
		$objresponse->addassign("insert_project_name","value",$project_query->fields['design_project_name']);

		//Set return form values
		$objresponse->addassign("return_form","front_logo.value",$project["front_logo"]['logo']."|".$project["front_logo"]['logo_side']."|".$project["front_logo"]['color_num']."|".$project["front_logo"]['embroidery']);//
		$objresponse->addassign("return_form","front_team_name.value",base64_encode(serialize($project["front_team_name"])));
		$objresponse->addassign("return_form","front_nums.value",base64_encode(serialize($project["front_nums"])));
		$objresponse->addassign("return_form","front_name.value",base64_encode(serialize($project["front_name"])));
		$objresponse->addassign("return_form","back_logo.value",$project["back_logo"]['logo']."|".$project["back_logo"]['logo_side']."|".$project["back_logo"]['color_num']."|".$project["back_logo"]['embroidery']);
		$objresponse->addassign("return_form","back_team_name.value",base64_encode(serialize($project["back_team_name"])));
		$objresponse->addassign("return_form","back_nums.value",base64_encode(serialize($project["back_nums"])));
		$objresponse->addassign("return_form","back_name.value",base64_encode(serialize($project["back_name"])));
		$objresponse->addassign("return_form","sleeve_left_logo.value",$project["sleeve_left_logo"]['logo']."|".$project["sleeve_left_logo"]['logo_side']."|".$project["sleeve_left_logo"]['color_num']."|".$project["sleeve_left_logo"]['embroidery']);
		$objresponse->addassign("return_form","sleeve_left_team_name.value",base64_encode(serialize($project["sleeve_left_team_name"])));
		$objresponse->addassign("return_form","sleeve_left_nums.value",base64_encode(serialize($project["sleeve_left_nums"])));
		$objresponse->addassign("return_form","sleeve_left_name.value",base64_encode(serialize($project["sleeve_left_name"])));
		$objresponse->addassign("return_form","sleeve_right_logo.value",$project["sleeve_right_logo"]['logo']."|".$project["sleeve_right_logo"]['logo_side']."|".$project["sleeve_right_logo"]['color_num']."|".$project["sleeve_right_logo"]['embroidery']);
		$objresponse->addassign("return_form","sleeve_right_team_name.value",base64_encode(serialize($project["sleeve_right_team_name"])));
		$objresponse->addassign("return_form","sleeve_right_nums.value",base64_encode(serialize($project["sleeve_right_nums"])));
		$objresponse->addassign("return_form","sleeve_right_name.value",base64_encode(serialize($project["sleeve_right_name"])));
		$objresponse->addassign("return_form","color_options.value",$project["color_options"]);
		$objresponse->addassign("return_form","body_color.value",$project["body_color"]);
		$objresponse->addassign("return_form","side_color.value",$project["side_color"]);
		$objresponse->addassign("return_form","trim_color.value",$project["trim_color"]);

		if($project["front_logo"]['embroidery']=="true")
			$objresponse->addassign('logo_embroidery',"checked",'checked');

		$objresponse->addassign('logo_colornum',"value",$project["front_logo"]['color_num']);
		$objresponse->addassign('logo_side',"value",$project["front_logo"]['logo_side']);

		if($project["front_logo"]!='') {
			$objresponse->addassign("add_logo","style.display","");//���ӱ�����ʾ
			$objresponse->addassign("logo_auto_size","style.display","");//������ť��ʾ
			$objresponse->addassign("logo_delete","style.display","");//������ť��ʾ
		}
		if(zen_not_null($project["front_team_name"])) {
			$_SESSION['front_team_name']=$project["front_team_name"];
		}
		
		if(zen_not_null($project["front_nums"])) {
			$objresponse->addassign("click_nums","checked","checked");//checkboxѡ��
			$objresponse->addassign("add_front_nums","style.display","");//���ӱ�����ʾ
			$objresponse->addassign("team_nums_color","value",$project["front_nums"]['team_nums_color']);//������ɫ
			$objresponse->addassign("team_nums_colorpad","style.backgroundColor",$project["front_nums"]['team_nums_color']);//ȡɫ��
			$objresponse->addassign("team_nums_out_color","value",$project["front_nums"]['team_nums_out_color']);//���뻷����ɫ
			$objresponse->addassign("team_nums_out_colorpad","style.backgroundColor",$project["front_nums"]['team_nums_out_color']);//ȡɫ��
			if(zen_not_null($project["front_nums"]['team_nums_out_color']) and $project["front_nums"]['team_nums_out_color']!='')//
				$objresponse->addassign("team_nums_out_colorpad","innerHTML",'&nbsp;&nbsp;&nbsp;&nbsp;');
			else
				$objresponse->addassign("team_nums_out_colorpad","innerHTML",'&nbsp;X&nbsp;');
			$objresponse->addassign("team_nums_color","value",$project["front_nums"]['team_nums_color']);//������ɫ
			$objresponse->addassign("team_nums_size","value",$project["front_nums"]["team_nums_size"]);
		}

		if(zen_not_null($project["front_name"])) {
			$objresponse->addassign("click_name","style.display","");//���ӱ�����ʾ
			$objresponse->addassign("click_name","checked","checked");//checkboxѡ��
			$objresponse->addassign("name_color","value",$project["front_name"]['name_color']);//������ɫ
			$objresponse->addassign("name_colorpad","style.backgroundColor",$project["front_name"]['name_color']);//ȡɫ��
			//$objresponse->addscript("xajax_get_team_list('true');");//�����Ʊ���
		}

		if($project["back_logo"]!='') {
			$objresponse->addassign("add_logo","style.display","");//���ӱ�����ʾ
			$objresponse->addassign("logo_auto_size","style.display","");//������ť��ʾ
			$objresponse->addassign("logo_delete","style.display","");//������ť��ʾ
		}
		//back team name
		if(zen_not_null($project["back_team_name"])) {
			$_SESSION['back_team_name']=$project["back_team_name"];
		}

		if(zen_not_null($project["back_nums"])) {
			$objresponse->addassign("click_nums","checked","checked");//checkboxѡ��
			$objresponse->addassign("add_front_nums","style.display","");//���ӱ�����ʾ
			$objresponse->addassign("team_nums_color","value",$project["back_nums"]['team_nums_color']);//������ɫ
			$objresponse->addassign("team_nums_colorpad","style.backgroundColor",$project["back_nums"]['team_nums_color']);//ȡɫ��
			$objresponse->addassign("team_nums_out_color","value",$project["back_nums"]['team_nums_out_color']);//���뻷����ɫ
			$objresponse->addassign("team_nums_out_colorpad","style.backgroundColor",$project["back_nums"]['team_nums_out_color']);//ȡɫ��
			if(zen_not_null($project["back_nums"]['team_nums_out_color']) and $project["back_nums"]['team_nums_out_color']!='')//
				$objresponse->addassign("team_nums_out_colorpad","innerHTML",'&nbsp;&nbsp;&nbsp;&nbsp;');
			else
				$objresponse->addassign("team_nums_out_colorpad","innerHTML",'&nbsp;X&nbsp;');
			$objresponse->addassign("team_nums_color","value",$project["back_nums"]['team_nums_color']);//������ɫ
			$objresponse->addassign("team_nums_size","value",$project["back_nums"]["team_nums_size"]);
		}
		//��������
		if(zen_not_null($project["back_name"])) {
			$objresponse->addassign("click_name","checked","checked");//checkboxѡ��
			$objresponse->addassign("add_front_nums","style.display","");//���ӱ�����ʾ
			$objresponse->addassign("name_color","value",$project["back_name"]['name_color']);//������ɫ
			$objresponse->addassign("name_colorpad","style.backgroundColor",$project["back_name"]['name_color']);//ȡɫ��
			//$objresponse->addscript("xajax_get_team_list('true');");//�����Ʊ���
		}

		//Sleeve
		if($project["sleeve_left_logo"]!='') {
			$objresponse->addassign("add_logo","style.display","");//���ӱ�����ʾ
			$objresponse->addassign("logo_auto_size","style.display","");//������ť��ʾ
			$objresponse->addassign("logo_delete","style.display","");//������ť��ʾ
		}
		//sleeve team name
		if(zen_not_null($project["sleeve_left_team_name"])) {
			$_SESSION['sleeve_left_team_name']=$project["sleeve_left_team_name"];
		}
		if(zen_not_null($project["sleeve_right_team_name"])) {
			$_SESSION['sleeve_right_team_name']=$project["sleeve_right_team_name"];
		}

		if(zen_not_null($project["sleeve_left_nums"])) {
			$objresponse->addassign("click_nums","checked","checked");//checkboxѡ��
			$objresponse->addassign("add_front_nums","style.display","");//���ӱ�����ʾ
			$objresponse->addassign("team_nums_color","value",$project["sleeve_nums"]['team_nums_color']);//������ɫ
			$objresponse->addassign("team_nums_colorpad","style.backgroundColor",$project["sleeve_nums"]['team_nums_color']);//ȡɫ��
			$objresponse->addassign("team_nums_out_color","value",$project["sleeve_nums"]['team_nums_out_color']);//���뻷����ɫ
			$objresponse->addassign("team_nums_out_colorpad","style.backgroundColor",$project["sleeve_nums"]['team_nums_out_color']);//ȡɫ��
			if(zen_not_null($project["sleeve_nums"]['team_nums_out_color']) and $project["sleeve_nums"]['team_nums_out_color']!='')//
				$objresponse->addassign("team_nums_out_colorpad","innerHTML",'&nbsp;&nbsp;&nbsp;&nbsp;');
			else
				$objresponse->addassign("team_nums_out_colorpad","innerHTML",'&nbsp;X&nbsp;');
			$objresponse->addassign("team_nums_color","value",$project["sleeve_nums"]['team_nums_color']);//������ɫ
			$objresponse->addassign("team_nums_size","value",$project["sleeve_nums"]["team_nums_size"]);
		}
		if(zen_not_null($project["sleeve_name"])) {
			$objresponse->addassign("click_name","checked","checked");//checkboxѡ��
			$objresponse->addassign("add_front_nums","style.display","");//���ӱ�����ʾ
			$objresponse->addassign("name_color","value",$project["back_name"]['name_color']);//������ɫ
			$objresponse->addassign("name_colorpad","style.backgroundColor",$project["back_name"]['name_color']);//ȡɫ��
			//$objresponse->addscript("xajax_get_team_list('true');");//�����Ʊ���
		}

		//body and trim color
		if(zen_not_null($project["color_options"])) {
			$objresponse->addassign("color_options","value",$project['color_options']);
		}
		if(zen_not_null($project["body_color"])) {
			$objresponse->addassign("body_color","value",$project['body_color']);
			$objresponse->addassign("body_colorpad","style.backgroundColor",$project["body_color"]);
		}
		if(zen_not_null($project["side_color"])) {
			$objresponse->addassign("side_color","value",$project['side_color']);
			$objresponse->addassign("side_colorpad","style.backgroundColor",$project["side_color"]);
		}
		if(zen_not_null($project["trim_color"])) {
			$objresponse->addassign("trim_color","value",$project['trim_color']);//������ɫ
			$objresponse->addassign("trim_colorpad","style.backgroundColor",$project["trim_color"]);//ȡɫ��
		}
		
		//ǰhtml
		//$objresponse->addAlert($project['html']['front']);
		$objresponse->addassign("design_front","innerHTML",$project['html']['front']);
		//��html
		$objresponse->addassign("design_back","innerHTML",$project['html']['back']);
		$objresponse->addassign("design_sleeve_left","innerHTML",$project['html']['sleeve_left']);
		$objresponse->addassign("design_sleeve_right","innerHTML",$project['html']['sleeve_right']);

		//��Ա�б�
		$objresponse->addScript("deleteRow('team_member_table',true);");//��ն�Ա�б�
		if(is_array($project["member"])) {
			for($i=0;$i<sizeof($project["member"]);$i++) {
				if(zen_not_null($project["back_name"]) or zen_not_null($project["front_name"]) )
					$script="addRow.call(this,'team_member_table','".str_replace('"',"\\'",zen_draw_input_field("member_name[]",$project["member"][$i]["name"]))."','".str_replace('"',"\\'",ereg_replace("\n","",zen_draw_pull_down_menu("member_size[]",$member_size,$project["member"][$i]["size"])))."','".str_replace('"',"\\'",zen_draw_input_field("member_nums[]",$project["member"][$i]["nums"],'size=2 maxlength=2'))."','".str_replace('"','\\"',zen_draw_input_field("member_qty[]",$project["member"][$i]["qty"],'size=2'))."');";
				else
					$script="addRow.call(this,'team_member_table','".str_replace('"',"\\'",zen_draw_input_field("member_name[]",'','disabled=disabled style="background:#ccc;"'))."','".str_replace('"',"\\'",ereg_replace("\n","",zen_draw_pull_down_menu("member_size[]",$member_size,$project["member"][$i]["size"])))."','".str_replace('"',"\\'",zen_draw_input_field("member_nums[]",$project["member"][$i]["nums"],'size=2 maxlength=2'))."','".str_replace('"',"\\'",zen_draw_input_field("member_qty[]",$project["member"][$i]["qty"],'size=2'))."');";	
				$objresponse->addscript($script);
				//$objresponse->addalert($script);
			}
		}
		if((zen_not_null($project["back_name"]) or zen_not_null($project["front_name"]) ) and !is_array($project["member"]))
			$objresponse->addscript("xajax_get_team_list('true');");
		
		//���ز�eval add the click event javascript to the logo, name and etc
		$div="front_edit_area";
		$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div,"onmouseout","this.style.border='';");
		$div="front_logo";
		$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div,"onmouseout","this.style.border='';");
		$objresponse->addScript("drag('".$div."');");

		foreach(array_keys($project["front_team_name"]) as $div){
			//$objresponse->addAlert($div);
			$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
			$objresponse->addEvent($div,"onmouseout","this.style.border='';");
			$objresponse->addScript("drag('".$div."');");
			$objresponse->addEvent($div,"onclick","display_text(this);");
		}

		$div="front_nums";
		$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div,"onmouseout","this.style.border='';");
		$objresponse->addScript("drag('".$div."');");

		$div="front_name";
		$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div,"onmouseout","this.style.border='';");
		$objresponse->addScript("drag('".$div."');");
		$objresponse->addEvent($div,"onclick","display_name_nums();");

		$div="back_edit_area";
		$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div,"onmouseout","this.style.border='';");
		$div="back_logo";
		$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div,"onmouseout","this.style.border='';");
		$objresponse->addScript("drag('".$div."');");

		foreach(array_keys($project["back_team_name"]) as $div){
			//$objresponse->addAlert($div);
			$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
			$objresponse->addEvent($div,"onmouseout","this.style.border='';");
			$objresponse->addScript("drag('".$div."');");
			$objresponse->addEvent($div,"onclick","display_text(this);");
		}

		$div="back_nums";
		$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div,"onmouseout","this.style.border='';");
		$objresponse->addScript("drag('".$div."');");
		$div="back_name";
		$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div,"onmouseout","this.style.border='';");
		$objresponse->addScript("drag('".$div."');");
		$objresponse->addEvent($div,"onclick","display_name_nums();");

		//left sleeve
		$div="sleeve_left_edit_area";
		$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div,"onmouseout","this.style.border='';");
		$div="sleeve_left_logo";
		$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div,"onmouseout","this.style.border='';");
		$objresponse->addScript("drag('".$div."');");

		foreach(array_keys($project["sleeve_left_team_name"]) as $div){
			//$objresponse->addAlert($div);
			$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
			$objresponse->addEvent($div,"onmouseout","this.style.border='';");
			$objresponse->addScript("drag('".$div."');");
			$objresponse->addEvent($div,"onclick","display_text(this);");
		}

		$div="sleeve_left_nums";
		$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div,"onmouseout","this.style.border='';");
		$objresponse->addScript("drag('".$div."');");
		$div="sleeve_left_name";
		$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div,"onmouseout","this.style.border='';");
		$objresponse->addScript("drag('".$div."');");
		$objresponse->addEvent($div,"onclick","display_name_nums();");

		//sleeve
		$div="sleeve_right_edit_area";
		$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div,"onmouseout","this.style.border='';");
		$div="sleeve_right_logo";
		$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div,"onmouseout","this.style.border='';");
		$objresponse->addScript("drag('".$div."');");

		foreach(array_keys($project["sleeve_right_team_name"]) as $div){
			//$objresponse->addAlert($div);
			$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
			$objresponse->addEvent($div,"onmouseout","this.style.border='';");
			$objresponse->addScript("drag('".$div."');");
			$objresponse->addEvent($div,"onclick","display_text(this);");
		}

		$div="sleeve_right_nums";
		$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div,"onmouseout","this.style.border='';");
		$objresponse->addScript("drag('".$div."');");
		$div="sleeve_right_name";
		$objresponse->addEvent($div,"onmouseover","this.style.border='1px solid #ccc'");
		$objresponse->addEvent($div,"onmouseout","this.style.border='';");
		$objresponse->addScript("drag('".$div."');");
		$objresponse->addEvent($div,"onclick","display_name_nums();");
		
		
		//���ر���eva ljavascript:cp_$('bg_2').attachEvent("onclick", function(){ alert(1);});
		$objresponse->addScript("set_bg_eval();get_bg_list('".$project_query->fields['products_id']."');");
	}
	return $objresponse->getxml();
}
$xajax->registerfunction("load_project");

/*
function set_bg_eval($products_id,$img_id,$side,$title) {//�������ر�������
$objresponse = new xajaxresponse();
if (!$_SESSION['customer_id']) {
$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
return $objresponse->getxml();
}
$div_id=(($side==0)?("bgfront_".$img_id):("bgback_".$img_id));
$objresponse->addEvent($div_id,"onclick","xajax_show_bg_list(".$products_id.",'".$img_id."',".$side.",'".$title."');");
$objresponse->addscript('get_bg_list("'.$products_id.'")');
return $objresponse->getxml();
}
$xajax->registerfunction("set_bg_eval");
*/


function input_cart($save_team=0) {//js������add_cart
$objresponse = new xajaxresponse();
if (!$_SESSION['customer_id']) {
$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
return $objresponse->getxml();
}
//$objresponse->addAlert("input_cart entry");
$script="var arg=new Array();
var form=cp_$('design_left').all.tags('FORM');
for(var i=0;i<form.length;i++) {
arg[i]=xajax.getFormValues(form[i].name);
}
arg[i]='<xjxquery><q>';
arg[i]+='products_id='+products_id;
arg[i]+='&type='+design_type;

arg[i]+='&front='+cp_$('design_front').innerHTML;
arg[i]+='&front_logo='+document.return_form.front_logo.value;
arg[i]+='&front_nums='+document.return_form.front_nums.value;
arg[i]+='&front_name='+document.return_form.front_name.value;
if(design_type=='jersey') {
arg[i]+='&back='+cp_$('design_back').innerHTML;
arg[i]+='&back_logo='+document.return_form.back_logo.value;
arg[i]+='&back_team_name='+document.return_form.back_team_name.value;
arg[i]+='&back_nums='+document.return_form.back_nums.value;
arg[i]+='&back_name='+document.return_form.back_name.value;
arg[i]+='&sleeve_left='+cp_$('design_sleeve_left').innerHTML;
arg[i]+='&sleeve_left_logo='+document.return_form.sleeve_left_logo.value;
arg[i]+='&sleeve_left_team_name='+document.return_form.sleeve_left_team_name.value;
arg[i]+='&sleeve_left_nums='+document.return_form.sleeve_left_nums.value;
arg[i]+='&sleeve_left_name='+document.return_form.sleeve_left_name.value;
arg[i]+='&sleeve_right='+cp_$('design_sleeve_right').innerHTML;
arg[i]+='&sleeve_right_logo='+document.return_form.sleeve_right_logo.value;
arg[i]+='&sleeve_right_team_name='+document.return_form.sleeve_right_team_name.value;
arg[i]+='&sleeve_right_nums='+document.return_form.sleeve_right_nums.value;
arg[i]+='&sleeve_right_name='+document.return_form.sleeve_right_name.value;
arg[i]+='&color_options='+document.return_form.color_options.value;
arg[i]+='&body_color='+document.return_form.body_color.value;
arg[i]+='&side_color='+document.return_form.side_color.value;
arg[i]+='&trim_color='+document.return_form.trim_color.value;
}
arg[i]+='</q></xjxquery>';";
if($save_team==1) {
	$script.="xajax_save_project.apply(this,arg);";
}else if($save_team==2){
	$script.="xajax_get_quote.apply(this,arg);";
}else {
	$script.="xajax_add_cart.apply(this,arg);";
}
//$objresponse->addAlert($script);
$objresponse->addScript($script);

return $objresponse->getxml();
}
$xajax->registerfunction("input_cart");

function save_project() {
	global $db;
	$objresponse = new xajaxresponse();
	if (!$_SESSION['customer_id']) {
		$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
		return $objresponse->getxml();
	}
//$objresponse->addAlert('before func_num_args');
//ȡ�ò���,������������������
$arg=func_num_args();
$arg_list = func_get_args();
for($i=0;$i<$arg;$i++) {
	while(list($key,$value)=each($arg_list[$i])) {
	$$key=$value;
	}
}
//$objresponse->addAlert("sabve_project");
//���������Ա
for($i=0;$i<sizeof($member_size);$i++) {//ѭ��
	if(zen_not_null($member_size[$i]) and zen_not_null($member_nums[$i]) and zen_not_null($member_qty[$i])) {//����Ա����,ȷ���������Ϊ��
	$design_array['member'][]=array("name"=>$member_name[$i],
									"size"=>$member_size[$i],
									"nums"=>$member_nums[$i],
									"qty"=>$member_qty[$i]);//ȡ��������cart��
	}
}
//$objresponse->addScript("xajax_get_team_list('".$type."');");//�����б�
//ִ�����ӹ��ﳵ����
if (isset($products_id) && is_numeric($products_id)) {
	if($front_logo!='') {
	$front_logo=explode("|",$front_logo);
	$design_array['front_logo']=array("logo"=>$front_logo[0],'logo_side'=>$front_logo[1], 'color_num'=>$front_logo[2],"embroidery"=>$front_logo[3]);

	}
//TODO
	//if($front_team_name!='')
	//$design_array['front_team_name']=unserialize(base64_decode($front_team_name));
	$design_array['front_team_name']=$_SESSION['front_team_name'];
//$objresponse->addAlert(sizeof($design_array['front_team_name']));

	if($front_nums!='')
		$design_array['front_nums']=unserialize(base64_decode($front_nums));
	if($front_name!="")
		$design_array['front_name']=unserialize(base64_decode($front_name));
	if($back_logo!='') {
		$back_logo=explode("|",$back_logo);
		$design_array['back_logo']=array("logo"=>$back_logo[0],'logo_side'=>$back_logo[1], 'color_num'=>$back_logo[2],"embroidery"=>$back_logo[3]);
	}
	//if($back_team_name!="")
	//$design_array['back_team_name']=unserialize(base64_decode($back_team_name));
	$design_array['back_team_name']=$_SESSION['back_team_name'];
	if($back_nums!="")
	$design_array['back_nums']=unserialize(base64_decode($back_nums));
	if($back_name!="")
		$design_array['back_name']=unserialize(base64_decode($back_name));

	//left sleeve
	if($sleeve_left_logo!='') {
		$sleeve_left_logo=explode("|",$sleeve_left_logo);
		$design_array['sleeve_left_logo']=array("logo"=>$sleeve_left_logo[0],'logo_side'=>$sleeve_left_logo[1], 'color_num'=>$sleeve_left_logo[2],"embroidery"=>$sleeve_left_logo[3]);
	}
//$objresponse->addAlert(sizeof($design_array['sleeve_left_team_name']));
	$design_array['sleeve_left_team_name']=$_SESSION['sleeve_left_team_name'];
	if($sleeve_nums!="")
	$design_array['sleeve_left_nums']=unserialize(base64_decode($sleeve_left_nums));
	if($sleeve_name!="")
		$design_array['sleeve_left_name']=unserialize(base64_decode($sleeve_left_name));

	//right sleeve
	if($sleeve_right_logo!='') {
		$sleeve_right_logo=explode("|",$sleeve_right_logo);
		$design_array['sleeve_right_logo']=array("logo"=>$sleeve_right_logo[0],'logo_side'=>$sleeve_right_logo[1], 'color_num'=>$sleeve_right_logo[2],"embroidery"=>$sleeve_right_logo[3]);
	}
//$objresponse->addAlert(sizeof($design_array['sleeve_right_team_name']));
	$design_array['sleeve_right_team_name']=$_SESSION['sleeve_right_team_name'];
	if($sleeve_nums!="")
	$design_array['sleeve_right_nums']=unserialize(base64_decode($sleeve_right_nums));
	if($sleeve_name!="")
		$design_array['sleeve_right_name']=unserialize(base64_decode($sleeve_right_name));
		
	//body and trim color

	if($color_options!="")
		$design_array['color_options']=$color_options;
	if($body_color!="")
		$design_array['body_color']=$body_color;
	if($side_color!="")
		$design_array['side_color']=$side_color;
	if($trim_color!="")
		$design_array['trim_color']=$trim_color;
		
	$design_array['design_des']=$design_des;
	$design_array['team_des']=$team_des;
	if($type=='shorts')
		$design_array['html']=array('front'=>$front);
	else 
		$design_array['html']=array('front'=>$front,'back'=>$back, 'sleeve_left'=>$sleeve_left, 'sleeve_right'=>$sleeve_right);
	
    //TODO send a Email to client and sales??
	//if(zen_not_null($member_size) and zen_not_null($member_nums) and zen_not_null($member_qty))
	$db->Execute("insert into ".DESIGN_PROJECT." (design_project_name,design_project_content,products_id,customers_id,project_time) values ('".$insert_project_name."','".base64_encode(serialize($design_array))."','".$products_id."','".$_SESSION['customer_id']."',now())");
	send_design_email($db->Insert_ID(), $insert_project_name, $_SESSION['customer_id'], $products_id);
//$objresponse->addAlert($project_id);
}
return $objresponse->getxml();
}
$xajax->registerfunction("save_project");

function add_cart() {
global $db,$nums_size;
$objresponse = new xajaxresponse();
if (!$_SESSION['customer_id']) {
$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
return $objresponse->getxml();
}
$arg=func_num_args();
$arg_list = func_get_args();
for($i=0;$i<$arg;$i++) {
	while(list($key,$value)=each($arg_list[$i])) {
	$$key=$value;
	}
}
$member_num=0;
for($i=0;$i<sizeof($member_size);$i++) {
	if(zen_not_null($member_size[$i]) and zen_not_null($member_nums[$i]) and zen_not_null($member_qty[$i])) {
		$design_array['member'][]=array("name"=>$member_name[$i],
										"size"=>$member_size[$i],
										"nums"=>$member_nums[$i],
										"qty"=>$member_qty[$i]);
		$member_num+=$member_qty[$i];
	}
}

if (isset($products_id) && is_numeric($products_id)) {

	if($front_logo!='') {
	$front_logo=explode("|",$front_logo);
	$design_array['front_logo']=array("logo"=>$front_logo[0],'logo_side'=>$front_logo[1], 'color_num'=>$front_logo[2],"embroidery"=>$front_logo[3]);

	}
//TODO
	//if($front_team_name!='')
	//$design_array['front_team_name']=unserialize(base64_decode($front_team_name));
	$design_array['front_team_name']=$_SESSION['front_team_name'];
//$objresponse->addAlert(sizeof($design_array['front_team_name']));

	if($front_nums!='')
		$design_array['front_nums']=unserialize(base64_decode($front_nums));
	if($front_name!="")
		$design_array['front_name']=unserialize(base64_decode($front_name));
	if($back_logo!='') {
		$back_logo=explode("|",$back_logo);
		$design_array['back_logo']=array("logo"=>$back_logo[0],'logo_side'=>$back_logo[1], 'color_num'=>$back_logo[2],"embroidery"=>$back_logo[3]);
	}
	//if($back_team_name!="")
	//$design_array['back_team_name']=unserialize(base64_decode($back_team_name));
	$design_array['back_team_name']=$_SESSION['back_team_name'];
	if($back_nums!="")
	$design_array['back_nums']=unserialize(base64_decode($back_nums));
	if($back_name!="")
		$design_array['back_name']=unserialize(base64_decode($back_name));

	//left sleeve
	if($sleeve_left_logo!='') {
		$sleeve_left_logo=explode("|",$sleeve_left_logo);
		$design_array['sleeve_left_logo']=array("logo"=>$sleeve_left_logo[0],'logo_side'=>$sleeve_left_logo[1], 'color_num'=>$sleeve_left_logo[2],"embroidery"=>$sleeve_left_logo[3]);
	}
//$objresponse->addAlert(sizeof($design_array['sleeve_left_team_name']));
	$design_array['sleeve_left_team_name']=$_SESSION['sleeve_left_team_name'];
	if($sleeve_nums!="")
	$design_array['sleeve_left_nums']=unserialize(base64_decode($sleeve_left_nums));
	if($sleeve_name!="")
		$design_array['sleeve_left_name']=unserialize(base64_decode($sleeve_left_name));

	//right sleeve
	if($sleeve_right_logo!='') {
		$sleeve_right_logo=explode("|",$sleeve_right_logo);
		$design_array['sleeve_right_logo']=array("logo"=>$sleeve_right_logo[0],'logo_side'=>$sleeve_right_logo[1], 'color_num'=>$sleeve_right_logo[2],"embroidery"=>$sleeve_right_logo[3]);
	}
	$design_array['sleeve_right_team_name']=$_SESSION['sleeve_right_team_name'];
	if($sleeve_nums!="")
	$design_array['sleeve_right_nums']=unserialize(base64_decode($sleeve_right_nums));
	if($sleeve_name!="")
		$design_array['sleeve_right_name']=unserialize(base64_decode($sleeve_right_name));
	
	//body and trim color
	if($color_options!="")
		$design_array['color_options']=$color_options;
	if($body_color!="")
		$design_array['body_color']=$body_color;
	if($side_color!="")
		$design_array['side_color']=$side_color;
	if($trim_color!="")
		$design_array['trim_color']=$trim_color;
		
	$design_array['design_des']=$design_des;
	$design_array['team_des']=$team_des;

$design_array['price']=$_SESSION['aa'];
	if($type=='shorts')
	$design_array['html']=array('front'=>"<div id='design_front'>".$front."</div>");
	else 
	$design_array['html']=array('front'=>"<div id='design_front'>".$front."</div>",'back'=>"<div id='design_back'>".$back."</div>", 'sleeve_left'=>"<div id='design_sleeve_left'>".$sleeve_left."</div>", 'sleeve_right'=>"<div id='design_sleeve_right'>".$sleeve_right."</div>");
$objresponse->addScript("end_edit=true;");
//bof add cart
$adjust_max= 'false';
$cart_quantity=$member_num;
$add_max = zen_get_products_quantity_order_max($products_id);
$cart_qty = $_SESSION['cart']->in_cart_mixed($products_id);
$new_qty = $cart_quantity;
$new_qty = $_SESSION['cart']->adjust_quantity($new_qty, $products_id, 'shopping_cart');
	if (($add_max == 1 and $cart_qty == 1)) {
	$new_qty = 0;
	$adjust_max= 'true';
	} else {
		if (($new_qty + $cart_qty > $add_max) and $add_max != 0) {
		$adjust_max= 'true';
		$new_qty = $add_max - $cart_qty;
		}
	}

	
	if ((zen_get_products_quantity_order_max($products_id) == 1 and $_SESSION['cart']->in_cart_mixed($products_id) == 1)) {
		$objresponse->addScript("alert('Please click team list tab and add item in the cart first.');");
	// do not add
	} else {
		if(zen_not_null($member_size) and zen_not_null($member_nums) and zen_not_null($member_qty))
			$_SESSION['cart']->add_cart($products_id,($_SESSION['cart']->get_quantity($products_id)+$new_qty),'',true,$design_array);
		else
			$objresponse->addScript("alert('Please click team list tab and add item in the cart first.');");
		
	//$objresponse->addalert($design_array['price']);
	}
//if add to the cart, go to shopping cart location
	if(zen_not_null($member_size) and zen_not_null($member_nums) and zen_not_null($member_qty))
		$objresponse->addScript("window.location ='".zen_href_link(FILENAME_SHOPPING_CART)."'");
}

return $objresponse->getxml();
}
$xajax->registerfunction("add_cart");

//get_quote will calculate the customized price and total price, and generate the design_array

function get_quote() {//���ӵ����ﳵ
global $db,$nums_size;
$objresponse = new xajaxresponse();
if (!$_SESSION['customer_id']) {
$objresponse->addScript("window.location ='".zen_href_link(FILENAME_LOGIN, '', 'SSL')."';");
return $objresponse->getxml();
}
//ȡ�ò���,������������������
$arg=func_num_args();
$arg_list = func_get_args();
for($i=0;$i<$arg;$i++) {
	while(list($key,$value)=each($arg_list[$i])) {
	$$key=$value;
	}
}
$member_num=0;
for($i=0;$i<sizeof($member_size);$i++) {//ѭ��
	if(zen_not_null($member_size[$i]) and zen_not_null($member_nums[$i]) and zen_not_null($member_qty[$i])) {//����Ա����,ȷ���������Ϊ��
		$design_array['member'][]=array("name"=>$member_name[$i],
										"size"=>$member_size[$i],
										"nums"=>$member_nums[$i],
										"qty"=>$member_qty[$i]);//ȡ��������cart��
		$member_num+=$member_qty[$i];
	}
}
//$objresponse->addScript("xajax_get_team_list('".$type."');");//�����б�
//ִ�����ӹ��ﳵ����
$table='<table id="quote summery" border="1">';
if (isset($products_id) && is_numeric($products_id)) {
	$total_price=0;
	if($front_logo!='' and $front_logo!='Tjs=' and $front_logo!='|||') {
		$front_logo_price=0;
		priceLogo($front_logo, $front_logo_price,$total_price, "front");
		$table.='<tr><td>'.TEXT_DESIGN_FRONT_LOGO.'</td><td>'.$total_price.'</td></tr>';
	}
	//TODO ask Ching if the price is per line of text??
//$objresponse->addAlert(sizeof($_SESSION['front_team_name']));
//$objresponse->addAlert(sizeof($_SESSION['back_team_name']));
	if(sizeof($_SESSION['front_team_name']) > 0) {
		$design_array['front_team_name']=$_SESSION['front_team_name'];
		$total_price+=DESIGN_CONFIG_TEAM_NAME_PRICE;
		$table.='<tr><td>'.TEXT_DESIGN_FRONT_TEXT.'</td><td>'.DESIGN_CONFIG_TEAM_NAME_PRICE.'</td></tr>';
	}
	if($front_nums!='' and $front_nums!='Tjs=') {
		$design_array['front_nums']=unserialize(base64_decode($front_nums));
		$num_price=0;
		if(zen_not_null($design_array['front_nums']['team_nums_out_color']) and $design_array['front_nums']['team_nums_out_color']!='') {
			$num_price=get_nums_price($nums_size,$design_array['front_nums']['team_nums_size'])*2;
		} else {
			$num_price+=get_nums_price($nums_size,$design_array['front_nums']['team_nums_size']);
		}
		$total_price+=$num_price;
		$table.='<tr><td>'.TEXT_DESIGN_FRONT_NUMBER.'</td><td>'.$num_price.'</td></tr>';
	}
	if($front_name!="" and $front_name!='Tjs=') {
		$design_array['front_name']=unserialize(base64_decode($front_name));
		$total_price+=DESIGN_CONFIG_NAME_PRICE;
		$table.='<tr><td>'.TEXT_DESIGN_FRONT_NAME.'</td><td>'.DESIGN_CONFIG_NAME_PRICE.'</td></tr>';
	}

	if($back_logo!="" and $back_logo!='Tjs=' and $back_logo!='|||') {
		$back_logo_price=0;
		priceLogo($back_logo, $back_logo_price, $total_price, "back");
		$table.='<tr><td>'.TEXT_DESIGN_BACK_LOGO.'</td><td>'.$back_logo_price.'</td></tr>';
	}
	//TODO ask Ching if the price is per line of text??
	if(sizeof($_SESSION['back_team_name']) > 0) {
		$design_array['back_team_name']=$_SESSION['back_team_name'];
		$total_price+=DESIGN_CONFIG_TEAM_NAME_PRICE;
		$table.='<tr><td>'.TEXT_DESIGN_BACK_TEXT.'</td><td>'.DESIGN_CONFIG_TEAM_NAME_PRICE.'</td></tr>';
	}
	if($back_nums!="" and $back_nums!='Tjs=') {
		$design_array['back_nums']=unserialize(base64_decode($back_nums));
		$num_price=0;
		if(zen_not_null($design_array['back_nums']['back_team_nums_out_color']) and $design_array['back_nums']['back_team_nums_out_color']!='')
			$num_price+=get_nums_price($nums_size,$design_array['back_nums']['team_nums_size'])*2;
		else
			$num_price+=get_nums_price($nums_size,$design_array['back_nums']['team_nums_size']);
			
		$total_price+=$num_price;
		$table.='<tr><td>'.TEXT_DESIGN_BACK_NUMBER.'</td><td>'.$num_price.'</td></tr>';
	}
	if($back_name!="" and $back_name!='Tjs=') {
		$design_array['back_name']=unserialize(base64_decode($back_name));
		$total_price+=DESIGN_CONFIG_NAME_PRICE;
		$table.='<tr><td>'.TEXT_DESIGN_BACK_NAME.'</td><td>'.DESIGN_CONFIG_NAME_PRICE.'</td></tr>';
	}
	
	//GET THE SLEEVE LEFT PRICE
	if($sleeve_left_logo!="" and $sleeve_left_logo!='Tjs=' and $sleeve_left_logo!='|||') {
		$sleeve_logo_price=0;
		priceLogo($sleeve_left_logo, $sleeve_logo_price, $total_price, "back");
		$table.='<tr><td>'.TEXT_DESIGN_SLEEVE_LEFT_LOGO.'</td><td>'.$sleeve_logo_price.'</td></tr>';
	}
	if(sizeof($_SESSION['sleeve_left_team_name']) > 0) {
		$design_array['sleeve_left_team_name']=$_SESSION['sleeve_left_team_name'];
		$total_price+=DESIGN_CONFIG_TEAM_NAME_PRICE;
		$table.='<tr><td>'.TEXT_DESIGN_SLEEVE_LEFT_TEXT.'</td><td>'.DESIGN_CONFIG_TEAM_NAME_PRICE.'</td></tr>';
	}
	if($sleeve_left_nums!="" and $sleeve_left_nums!='Tjs=') {
		$design_array['sleeve_left_nums']=unserialize(base64_decode($sleeve_left_nums));
		$num_price=0;
		if(zen_not_null($design_array['sleeve_left_nums']['sleeve_team_nums_out_color']) and $design_array['sleeve_left_nums']['sleeve_team_nums_out_color']!='')
			$num_price+=get_nums_price($nums_size,$design_array['sleeve_left_nums']['team_nums_size'])*2;
		else
			$num_price+=get_nums_price($nums_size,$design_array['sleeve_left_nums']['team_nums_size']);
			
		$total_price+=$num_price;
		$table.='<tr><td>'.TEXT_DESIGN_SLEEVE_LEFT_NUMBER.'</td><td>'.$num_price.'</td></tr>';
	}
	if($sleeve_left_name!="" and $sleeve_left_name!='Tjs=') {
		$design_array['sleeve_left_name']=unserialize(base64_decode($sleeve_left_name));
		$total_price+=DESIGN_CONFIG_NAME_PRICE;
		$table.='<tr><td>'.TEXT_DESIGN_SLEEVE_LEFT_NAME.'</td><td>'.DESIGN_CONFIG_NAME_PRICE.'</td></tr>';
	}

	//GET THE SLEEVE RIGHT PRICE
	if($sleeve_right_logo!="" and $sleeve_right_logo!='Tjs=' and $sleeve_right_logo!='|||') {
		$sleeve_logo_price=0;
		priceLogo($sleeve_right_logo, $sleeve_logo_price, $total_price, "back");
		$table.='<tr><td>'.TEXT_DESIGN_SLEEVE_RIGHT_LOGO.'</td><td>'.$sleeve_logo_price.'</td></tr>';
	}
	if(sizeof($_SESSION['sleeve_right_team_name']) > 0) {
		$design_array['sleeve_right_team_name']=$_SESSION['sleeve_right_team_name'];
		$total_price+=DESIGN_CONFIG_TEAM_NAME_PRICE;
		$table.='<tr><td>'.TEXT_DESIGN_SLEEVE_RIGHT_TEXT.'</td><td>'.DESIGN_CONFIG_TEAM_NAME_PRICE.'</td></tr>';
	}
	if($sleeve_right_nums!="" and $sleeve_right_nums!='Tjs=') {
		$design_array['sleeve_right_nums']=unserialize(base64_decode($sleeve_right_nums));
		$num_price=0;
		if(zen_not_null($design_array['sleeve_right_nums']['sleeve_team_nums_out_color']) and $design_array['sleeve_right_nums']['sleeve_team_nums_out_color']!='')
			$num_price+=get_nums_price($nums_size,$design_array['sleeve_right_nums']['team_nums_size'])*2;
		else
			$num_price+=get_nums_price($nums_size,$design_array['sleeve_right_nums']['team_nums_size']);
			
		$total_price+=$num_price;
		$table.='<tr><td>'.TEXT_DESIGN_SLEEVE_RIGHT_NUMBER.'</td><td>'.$num_price.'</td></tr>';
	}
	if($sleeve_right_name!="" and $sleeve_right_name!='Tjs=') {
		$design_array['sleeve_right_name']=unserialize(base64_decode($sleeve_right_name));
		$total_price+=DESIGN_CONFIG_NAME_PRICE;
		$table.='<tr><td>'.TEXT_DESIGN_SLEEVE_RIGHT_NAME.'</td><td>'.DESIGN_CONFIG_NAME_PRICE.'</td></tr>';
	}
	
	$product_price=get_product_price_by_id($products_id);
	$table.='<tr><td>'.TEXT_DESIGN_PROD_PRICE.'</td><td>'.$product_price.'</td></tr>';
	$table.='<tr><td>'.TEXT_DESIGN_PROD_CUSTOMIZE_PRICE.'</td><td>'.$total_price.'</td></tr>';
	$total=$product_price+$total_price;
	$table.='<tr><td>'.TEXT_DESIGN_PROD_TOTAL_PRICE.'</td><td>'.$total.'</td></tr>';
	$table.='</table>';
	
	//$objresponse->addAlert($table);
	$objresponse->addassign("quote_summary","innerHTML",$table);
	
	$design_array['price']=$total_price;//����۸�
	$_SESSION['aa']=$design_array['price'];
	$design_array['design_des']=$design_des;
	$design_array['team_des']=$team_des;
	if($type=='shorts')
		$design_array['html']=array('front'=>"<div id='design_front'>".$front."</div>");
	else 
		$design_array['html']=array('front'=>"<div id='design_front'>".$front."</div>",'back'=>"<div id='design_back'>".$back."</div>",'sleeve_left'=>"<div id='design_sleeve_left'>".$sleeve_left."</div>",'sleeve_right'=>"<div id='design_sleeve_right'>".$sleeve_right."</div>");
	$objresponse->addScript("end_edit=true;");//�༭������js���� �Ա������ת����ʾ�뿪
	
	//add design array to session for add_cart use
	$_SESSION['design_array']=$design_array;
}

return $objresponse->getxml();
}
$xajax->registerfunction("get_quote");

//Price the logo
function priceLogo($front_logo,&$front_logo_price,&$total_price, $side) {
		$front_logo_price=0;
		$front_logo=explode("|",$front_logo);
		$design_array[$side.'_logo']=array("logo"=>$front_logo[0],'logo_side'=>$front_logo[1],'color_num'=>$front_logo[2],"embroidery"=>$front_logo[3]);
		//if(strstr($front_logo[1],DIR_IMAGE_TMP))
		$front_logo_price+=DESIGN_CONFIG_LOGO_PRICE;
		if($front_logo[3]=='true')
			$front_logo_price+=DESIGN_CONFIG_EMBROIDERING_PRICE;
		if($front_logo[2]>0)
			$total_price+=($front_logo_price*$front_logo[2]);
		else
			$total_price+=$front_logo_price;
}
$xajax->registerfunction("priceLogo");

$xajax->processrequests();
?>