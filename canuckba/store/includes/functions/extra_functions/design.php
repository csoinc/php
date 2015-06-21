<?php

function zen_count_design_products_in_category($category_id) {
global $db;
$products_count = 0;
$products_query = "select count(*) as total
					 from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c,".DESIGN_PRODUCTS." dp
					 where p.products_id = p2c.products_id
					 and p.products_status = '1'
					 and p.products_id = dp.products_id
					 and p2c.categories_id = '" . (int)$category_id . "'";
$products = $db->Execute($products_query);
$products_count += $products->fields['total'];
$child_categories_query = "select categories_id
						   from " . TABLE_CATEGORIES . "
						   where parent_id = '" . (int)$category_id . "'";
$child_categories = $db->Execute($child_categories_query);
	if ($child_categories->RecordCount() > 0) {
	  while (!$child_categories->EOF) {
		$products_count += zen_count_products_in_category($child_categories->fields['categories_id'], $include_inactive);
		$child_categories->MoveNext();
	  }
	}
return $products_count;
}
function get_chindren_categories($parent_id=0,$pull_array='') {
global $db;
if(is_array($pull_array))
$categories_array[]=$pull_array;
$categories_query = "select c.categories_id, cd.categories_name
                             from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
                             where c.parent_id = ".(int)$parent_id."
                             and c.categories_id = cd.categories_id
                             and cd.language_id='" . (int)$_SESSION['languages_id'] . "'
                             and c.categories_status= 1
                             order by sort_order, cd.categories_name";
$categories = $db->Execute($categories_query);
	if($categories->RecordCount()>0) {
		while (!$categories->EOF)  {
		if(zen_count_design_products_in_category($categories->fields['categories_id'])>0)
		$categories_array[]=array("id"=>$categories->fields['categories_id'],"text"=>$categories->fields['categories_name']);
		$categories->MoveNext();
		}
	return $categories_array;
	}else {
	return false;
	}
}
function get_products($categories_id) {
global $db;
$products = $db->Execute("select p.products_id, pd.products_name, p.products_image,p.products_model
						from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd," .TABLE_PRODUCTS_TO_CATEGORIES." p2c,".DESIGN_PRODUCTS." dp
						where p.products_id = pd.products_id
						and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
						and p.products_id = p2c.products_id
						and p.products_id = dp.products_id
						and p2c.categories_id='".$categories_id."'
						order by pd.products_name");
	if($products->RecordCount()>0) {
		while (!$products->EOF) {
		//$display_price = zen_get_products_base_price($products->fields['products_id']);
		$products_array[]=array("id"=>$products->fields['products_id'],
					"name"=>$products->fields['products_name'],
					"image"=>$products->fields['products_image'],
					"model"=>$products->fields['products_model']);
		$products->MoveNext();
		}
	return $products_array;
	} else
	return false;
}
function check_file_type($file){
$all_file_type=array("png","jpg","gif","ai","eps");//文件格式
$file_type=file_type($file);
if(in_array($file_type,$all_file_type))
return true;
else
return false;
}
function check_image_type($file){
$all_file_type=array("png","jpg","gif");//文件格式
$file_type=file_type($file);
if(in_array($file_type,$all_file_type))
return true;
else
return false;
}
//文件格式
function file_type($file) {
$file_type=explode(".",$file);
$file_type=strtolower($file_type[count($file_type)-1]);
return $file_type;
}
function design_config($products_id) {
global $db;
$design=$db->Execute("select * from ".DESIGN_PRODUCTS." where products_id=".(int)$products_id);//查询可以定制的产品
return $design;
}
function font_list($side) {
$dir="font/";
	if ($dh = opendir($dir)) 
	{ 
	/*$show.=$dir."<br>";*/
		while (($file = readdir($dh)) !== false) 
		{ 
			if($file !='.' and $file !='..') 
				if(file_type($file)=="png")
				$show.="<div onclick='document.add_".$side."_team_name_form.".$side."_team_name_font.value=\"".str_replace(".png","",$file)."\";document.getElementById(\"".$side."_team_name_png\").innerHTML=\"".str_replace('"','\\"',zen_image($dir.$file,"",170))."\";display(\"".$side."_team_name_font_list\",\"none\");hide_dropdowns(\"out\");' style='cursor:pointer;'><img src=\"".$dir.$file."\"></div>\n";
				elseif(is_dir($dir.$file)) {
				$show.=mulu($dir.$file."/");
				}
		} 
	closedir($dh); 
	} 
return $show;
}

function shape_list($side) {
$dir="shape/";
	if ($dh = opendir($dir)) 
	{ 
	/*$show.=$dir."<br>";*/
		while (($file = readdir($dh)) !== false) 
		{ 
			if($file !='.' and $file !='..') 
				if(file_type($file)=="gif")
				$show.="<div onclick='document.add_".$side."_team_name_form.".$side."_team_name_shape.value=\"".$file."\";document.getElementById(\"".$side."_team_name_shape_png\").innerHTML=\"".str_replace('"','\\"',zen_image($dir.$file,"",170))."\";display(\"".$side."_team_name_shape_list\",\"none\");hide_dropdowns(\"out\");' style='cursor:pointer;'><img src=\"".$dir.$file."\"></div>\n";
				elseif(is_dir($dir.$file)) {
				$show.=mulu($dir.$file."/");
				}
		} 
	closedir($dh); 
	} 
return $show;
}
function text_to_image($string,$out_file,$color='#000000',$size=24,$font,$distort=0,$stroke_color='#000000',$strokewidth=0,$deflexion=0, $textShape='normal') {
@unlink($out_file);
$dos=DEFINE_IMAGEMAGICK_PATH."texteffect -x 0 -b none";//透明画板
$dos.=" -f ".$font;//字体
$dos.=" -p ".$size;//大小
if ($color == "transparent") //cavity clicked
	$dos.=" -c ".$color . " -s outline";//颜色
else
	$dos.=" -c ".$color;//颜色
if($strokewidth!=0) {//描边
	if($stroke_color=='')
	$stroke_color=$color;
	if($size<30)
	$strokewidth=1;
$dos.=" -o ".$stroke_color." -l ".$strokewidth;
}
if($deflexion!=0) {
$dos.=" -i ".$deflexion;
}

if (strlen(strstr($textShape,"bridge")) > 0) 
	$dos.=" -e concave-bottom -d 0.5 ";
else if (strlen(strstr($textShape,"pinch")) > 0) 
	$dos.=" -e concave -d 0.5 ";
else if (strlen(strstr($textShape,"valley")) > 0) 
	$dos.=" -e concave-top -d 0.5 ";
else if (strlen(strstr($textShape,"normal")) > 0) 
	$dos.=" -e normal";

if($distort<0) {
	$dos.=" -e arc-bottom -a ".abs($distort);
} else if($distort>0) {
	$dos.=" -e arc-top -a ".$distort;
}
$dos.=" -t \"".str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', $string), "\0..\37'\\")))."\"";//输入字 $deflexion为倾斜
$dos.=" ".$out_file;//输出文件
//echo "command=" .$dos;
exec($dos);
$image_size=@getimagesize($out_file);
return $image_size;
}
//keep the old copy to use convert directly
function text_to_image1($string,$out_file,$color='#000000',$size=24,$font,$distort=0,$stroke_color='#000000',$strokewidth=0,$deflexion=0) {
@unlink($out_file);
$dos=DEFINE_IMAGEMAGICK_PATH."convert -size 1100x800 xc:transparent";//透明画板
$dos.=" -font ".$font;//字体
$dos.=" -pointsize ".$size;//大小
$dos.=" -fill ".$color;//颜色
if($strokewidth!=0) {//描边
	if($stroke_color=='')
	$stroke_color=$color;
	if($size<30)
	$strokewidth=1;
$dos.=" -stroke ".$stroke_color." -strokewidth ".$strokewidth;
}
//$dos.=" -gravity center";//居中
$dos.=" -gravity west";//居中
//$dos.=" -annotate 0x".$deflexion."+0+0 \"".str_replace(array('"','\\','@','%'),array('\"','\\\\','\\@','\\%'),$string)."\"";//输入字 $deflexion为倾斜
$dos.=" -annotate 0x".$deflexion."+0+0 \"".str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', $string), "\0..\37'\\")))."\"";//输入字 $deflexion为倾斜
$dos.=" -trim +repage";//剪切空白
if($distort>0)//弧度
$dos.=" -virtual-pixel transparent -distort Arc ".$distort;
if($distort<0)
$dos.=" -virtual-pixel transparent -rotate 180 -distort Arc \"".abs($distort)." 180\"";
$dos.=" ".$out_file;//输出文件
//echo "command=" .$dos;
exec($dos);
$image_size=@getimagesize($out_file);
return $image_size;
}


function get_nums_price($array,$id) {
	for($i=0;$i<sizeof($array);$i++) {
		if($array[$i]['id']==$id) {
		$price=$array[$i]['price'];
		break;
		}
	}
return $price;
}

function get_product_attributs($products_id, $products_option_id) {
global $db;
	$attributes_query = "select popt.products_options_name, poval.products_options_values_id, poval.products_options_values_name
                                   from " . TABLE_PRODUCTS_OPTIONS . " popt,
                                        " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval,
                                        " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                   where pa.products_id = '" . (int)$products_id . "'
                                   and pa.options_id = '" . (int)$products_option_id . "'
                                   and pa.options_id = popt.products_options_id
                                   and pa.options_values_id = poval.products_options_values_id
                                   and poval.products_options_values_name NOT LIKE 'Select from below%'
                                   and popt.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                   and poval.language_id = '" . (int)$_SESSION['languages_id'] . "'
								   ORDER BY poval.products_options_values_sort_order";
								   
$design=$db->Execute($attributes_query);
return $design;
}

function get_product_option_id_by_name($option_name) {
global $db;
	$attributes_query = "select popt.products_options_id
                                   from " . TABLE_PRODUCTS_OPTIONS . " popt
                                   where popt.products_options_name LIKE '" . $option_name . "'
                                   and popt.language_id = '" . (int)$_SESSION['languages_id'] . "'";
								   
$options=$db->Execute($attributes_query);
if (!$options->EOF && $options->recordCount() > 0)
	return $options->fields['products_options_id'];
else
	return -1;
}

function get_product_price_by_id($product_id) {
global $db;
	$attributes_query = "select popt.products_price
                                   from " . TABLE_PRODUCTS . " popt
                                   where popt.products_id = " . $product_id; 
								   
$options=$db->Execute($attributes_query);
if (!$options->EOF && $options->recordCount() > 0)
	return $options->fields['products_price'];
else
	return 0;
}

function send_design_email($project_id, $project_name, $customer_id, $products_id) {
global $db;
 	//set the default customer email address
    $sql = "SELECT customers_id, customers_firstname, customers_lastname, customers_password, customers_email_address, customers_default_address_id 
             FROM " . TABLE_CUSTOMERS . " 
             WHERE customers_id = :customersID";
      
    $sql = $db->bindVars($sql, ':customersID', $customer_id, 'integer');
    $customer = $db->Execute($sql);

    //$sql = "SELECT * FROM " . DESIGN_PROJECT . " WHERE design_project_id = " .(int)$project_id;

	//$design=$db->Execute($sql);
	//$design=unserialize(base64_decode($design_project->fields["design_project_content"]));

     // lets start with the email confirmation
    // make an array to store the html version

    $html_msg=array();

    //intro area
    $email_order = EMAIL_TEXT_HEADER . EMAIL_TEXT_FROM . STORE_NAME . "\n\n" .  
    $customer->fields['customers_firstname'] . ' ' . $customer->fields['customers_lastname'] . "\n\n" ;
    EMAIL_THANKS_FOR_SHOPPING . "\n" . EMAIL_DETAILS_FOLLOW . "\n" .
    EMAIL_SEPARATOR . "\n" .
    EMAIL_TEXT_DESIGN_NAME . ' ' . $project_name . "\n" .
    EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n" .
    EMAIL_TEXT_INVOICE_URL . ' ' . zen_href_link('design', 'products_id='.$products_id.'&project_id=' . $project_id, 'SSL', false) . "\n\n";

    //EMAIL_TEXT_INVOICE_URL . ' ' . zen_href_link('design', 'order_id=' . $product_id, 'SSL', false) . "\n\n";
    $html_msg['EMAIL_TEXT_HEADER']     = EMAIL_TEXT_HEADER;
    $html_msg['EMAIL_TEXT_DESIGN_NAME']     = EMAIL_TEXT_DESIGN_NAME;
    $html_msg['EMAIL_TEXT_FROM']       = EMAIL_TEXT_FROM;
    $html_msg['INTRO_STORE_NAME']      = STORE_NAME;
    $html_msg['EMAIL_THANKS_FOR_SHOPPING'] = EMAIL_THANKS_FOR_SHOPPING;
    $html_msg['EMAIL_DETAILS_FOLLOW']  = EMAIL_DETAILS_FOLLOW;
    $html_msg['INTRO_ORDER_NUM_TITLE'] = EMAIL_TEXT_DESIGN_NAME;
    $html_msg['INTRO_ORDER_NUMBER']    = $project_name;
    $html_msg['INTRO_DATE_TITLE']      = EMAIL_TEXT_DATE_ORDERED;
    $html_msg['INTRO_DATE_ORDERED']    = strftime(DATE_FORMAT_LONG);
    $html_msg['INTRO_URL_TEXT']        = EMAIL_TEXT_INVOICE_URL_CLICK;
    $html_msg['INTRO_URL_VALUE']       = zen_href_link('design', 'products_id='.$products_id.'&project_id=' . $project_id, 'SSL', false);

    $html_msg['ORDER_COMMENTS'] = '';
	/*
    //products area
    $html_msg['PRODUCTS_TITLE'] = EMAIL_TEXT_PRODUCTS;

	//design table
	$products_design_html='<table border="1px" width="90%" cellspacing="0" cellpadding="3" style="border:1px solid #0066CC">';
	if(zen_not_null($design["html"]['front'])) {
		$products_design_html .= '	  <tr><td style="background-color:#ddd; font-size:1.8em; font-weight:bold;width:20px;" height="350px">front</td>';
		$products_design_html .= '	  <td width="300px" valign="top">';
		$products_design_html .= '<div id="design_front">'.$design["html"]['front'].'</div>';
		$products_design_html .= '	  </td></tr>';
	}

	if(zen_not_null($design['html']['back'])) {
		$products_design_html .= '	  <tr><td style="background-color:#ddd; font-size:1.8em; font-weight:bold;width:20px;" height="350px">back</td>';
		$products_design_html .= '	  <td width="300px" valign="top">';
		$products_design_html .= $design['html']['back'];
		$products_design_html .= '	  </td></tr>';
	}

	if(zen_not_null($design['html']['sleeve_left'])) {
		$products_design_html .= '	  <tr><td style="background-color:#ddd; font-size:1.8em; font-weight:bold;width:20px;" height="350px">sleeve_left</td>';
		$products_design_html .= '	  <td width="300px" valign="top">';
		$products_design_html .= $design['html']['sleeve_left'];
		$products_design_html .= '	  </td></tr>';
	}

	if(zen_not_null($design['html']['sleeve_right'])) {
		$products_design_html .= '	  <tr><td style="background-color:#ddd; font-size:1.8em; font-weight:bold;width:20px;" height="350px">sleeve_right</td>';
		$products_design_html .= '	  <td width="300px" valign="top">';
		$products_design_html .= $design['html']['sleeve_right'];
		$products_design_html .= '	  </td></tr>';
	}
	

    $products_design_html .= '	  </table>';
	  

    $html_msg['PRODUCTS_DESIGN_DETAILS']='<table class="product-details" border="0" width="100%" cellspacing="0" cellpadding="2">' . $products_design_html . '</table>';

*/	
    // include disclaimer
    $email_order .= "\n-----\n" . sprintf(EMAIL_DISCLAIMER, STORE_OWNER_EMAIL_ADDRESS) . "\n\n";
    // include copyright
    $email_order .= "\n-----\n" . EMAIL_FOOTER_COPYRIGHT . "\n\n";

    while (strstr($email_order, '&nbsp;')) $email_order = str_replace('&nbsp;', ' ', $email_order);

    $html_msg['EMAIL_FIRST_NAME'] = $customer->fields['customers_firstname'];
    $html_msg['EMAIL_LAST_NAME'] = $customer->fields['customers_lastname'];
    $html_msg['EXTRA_INFO'] = '';
    //$zco_notifier->notify('NOTIFY_ORDER_INVOICE_CONTENT_READY_TO_SEND', array('zf_insert_id' => $zf_insert_id, 'text_email' => $email_order, 'html_email' => $html_msg));
    zen_mail($customer->fields['customers_firstname'] . ' ' . $customer->fields['customers_lastname'], $customer->fields['customers_email_address'], EMAIL_TEXT_SUBJECT . $zf_insert_id, $email_order, STORE_NAME, EMAIL_FROM, $html_msg, 'design');

  }

?>