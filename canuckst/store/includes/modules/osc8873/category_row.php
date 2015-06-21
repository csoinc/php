<?php
/**
 * index category_row.php
 *
 * Prepares the content for displaying a category's sub-category listing in grid format.  
 * Once the data is prepared, it calls the standard tpl_list_box_content template for display.
 *
 * @package page
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: category_row.php 3012 2006-02-11 16:34:02Z wilt $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
$title = '';
$num_categories = $categories->RecordCount();

$row = 0;
$col = 0;
$list_box_contents = '';
/*
if ($num_categories < MAX_DISPLAY_CATEGORIES_PER_ROW || MAX_DISPLAY_CATEGORIES_PER_ROW == 0) {
  $col_width = floor(100/$num_categories);
} else {
  $col_width = floor(100/MAX_DISPLAY_CATEGORIES_PER_ROW);
}
*/ //to keep the alignment right
  $col_width = floor(100/MAX_DISPLAY_CATEGORIES_PER_ROW);

while (!$categories->EOF) {
  if (!$categories->fields['categories_image']) !$categories->fields['categories_image'] = 'pixel_trans.gif';
  $cPath_new = zen_get_path($categories->fields['categories_id']);

  // strip out 0_ from top level cats
  $cPath_new = str_replace('=0_', '=', $cPath_new);

  //    $categories->fields['products_name'] = zen_get_products_name($categories->fields['products_id']);

  $list_box_contents[$row][$col] = array('params' => 'align="center" style="width:' . $col_width . '%;"',
  'text' => '
  		<dl class="list_cell">
        <dt>
  		<a href="' . zen_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . zen_image(DIR_WS_IMAGES . $categories->fields['categories_image'], $categories->fields['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . 
		'</dt><dt class="title_category">' . $categories->fields['categories_name'] . '</a></dt></dl>'
		);

  $col ++;
  if ($col > (MAX_DISPLAY_CATEGORIES_PER_ROW -1)) {
    $col = 0;
    $row ++;
  }
  $categories->MoveNext();
}

  //to append enough cells for alignment
  while ( $col > 0 && $col < (MAX_DISPLAY_CATEGORIES_PER_ROW)) {
		$list_box_contents[$row][$col] = array('params' =>'align="center" width="' . $col_width . '%;" ',
    	'text' => '&nbsp;');
	    $col ++;
  }


?>
