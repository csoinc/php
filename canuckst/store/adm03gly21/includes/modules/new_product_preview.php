<?php
/**
 * @package admin
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: new_product_preview.php 3009 2006-02-11 15:41:10Z wilt $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

// copy image only if modified
        if (!isset($_GET['read']) || $_GET['read'] == 'only') {
          $products_image = new upload('products_image');
          $products_image->set_destination(DIR_FS_CATALOG_IMAGES . $_POST['img_dir']);
          if ($products_image->parse() && $products_image->save($_POST['overwrite'])) {
            $products_image_name = $_POST['img_dir'] . $products_image->filename;
          } else {
            $products_image_name = (isset($_POST['products_previous_image']) ? $_POST['products_previous_image'] : '');
          }

          $products_image_medium = new upload('products_image_medium');
          $products_image_medium->set_destination(DIR_FS_CATALOG_IMAGES . 'medium/' . $_POST['img_dir'] );
          if ($products_image_medium->parse() && $products_image_medium->save($_POST['overwrite'])) {
            $products_image_medium_name = 'medium/' . $_POST['img_dir'] . $products_image_medium->filename;
          } else {
            //$products_image_medium_name = (isset($_POST['products_previous_image']) ? $_POST['products_previous_image'] : '');
          }
          
          $products_image_large = new upload('products_image_large');
          $products_image_large->set_destination(DIR_FS_CATALOG_IMAGES .  'large/' . $_POST['img_dir']);
          if ($products_image_large->parse() && $products_image_large->save($_POST['overwrite'])) {
            $products_image_large_name = 'large/'. $_POST['img_dir'] . $products_image_large->filename;
          } else {
            //$products_image_name = (isset($_POST['products_previous_image']) ? $_POST['products_previous_image'] : '');
          }
        }
?>