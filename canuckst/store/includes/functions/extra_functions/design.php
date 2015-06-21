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

function text_to_image($string,$out_file,$color='#000000',$size=24,$font,$distort=0,$stroke_color='#000000',$strokewidth=0,$deflexion=0) {
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
$dos.=" -gravity center";//居中
$dos.=" -annotate 0x".$deflexion."+0+0 \"".str_replace(array('"','\\','@','%'),array('\"','\\\\','\\@','\\%'),$string)."\"";//输入字 $deflexion为倾斜
$dos.=" -trim +repage";//剪切空白
if($distort>0)//弧度
$dos.=" -virtual-pixel transparent -distort Arc ".$distort;
if($distort<0)
$dos.=" -virtual-pixel transparent -rotate 180 -distort Arc \"".abs($distort)." 180\"";
$dos.=" ".$out_file;//输出文件
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
?>