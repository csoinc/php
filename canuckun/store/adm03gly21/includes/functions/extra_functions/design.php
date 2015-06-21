<?php
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
$products = $db->Execute("select p.products_id, pd.products_name,dp.design_products_type
						from (" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd," .TABLE_PRODUCTS_TO_CATEGORIES." p2c)
						left join ".DESIGN_PRODUCTS." dp
						on p.products_id = dp.products_id
						where p.products_id = pd.products_id
						and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
						and p.products_id = p2c.products_id
						and p2c.categories_id='".$categories_id."'
						order by pd.products_name");
	if($products->RecordCount()>0) {
		while (!$products->EOF) {
		//$display_price = zen_get_products_base_price($products->fields['products_id']);
		$products_array[]=array("id"=>$products->fields['products_id'],
					"text"=>$products->fields['products_name'].(zen_not_null($products->fields['design_products_type'])?"*":""));
		$products->MoveNext();
		}
	return $products_array;
	} else
	return false;
}
?>