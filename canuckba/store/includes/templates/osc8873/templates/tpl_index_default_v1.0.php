<?php
/**
 * Page Template
 *
 * Main index page<br />
 * Displays greetings, welcome text (define-page content), and various centerboxes depending on switch settings in Admin<br />
 * Centerboxes are called as necessary
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_index_default.php 3254 2006-03-25 17:34:04Z ajeh $
 */
?>
<table>
<tr><td>
<?php if (SHOW_CUSTOMER_GREETING == '1') { ?>
<h2 class="greeting"><?php echo zen_customer_greeting(); ?></h2>
<?php } ?>

<!-- deprecated - to use uncomment this section
<?php if (TEXT_MAIN) { ?>
<div id="" class="content"><?php echo TEXT_MAIN; ?></div>
<?php } ?>-->

<!-- deprecated - to use uncomment this section
<?php if (TEXT_INFORMATION) { ?>
<div id="" class="content"><?php echo TEXT_INFORMATION; ?></div>
<?php } ?>-->

<?php if (DEFINE_MAIN_PAGE_STATUS >= '1' and DEFINE_MAIN_PAGE_STATUS <= '2') { ?>
<?php
/**
 * get the Define Main Page Text
 */
?>
<div id="indexDefaultMainContent" class="content"><?php require($define_page); ?></div>
<?php } ?>

<?php
  $show_display_category = $db->Execute(SQL_SHOW_PRODUCT_INFO_MAIN);
  while (!$show_display_category->EOF) {
?>

<?php if ($show_display_category->fields['configuration_key'] == 'SHOW_PRODUCT_INFO_MAIN_FEATURED_PRODUCTS') { ?>
<?php 
/**
 * display the Featured Products Center Box
 */
?>
<?php require($template->get_template_dir('tpl_modules_featured_products.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_featured_products.php'); ?>
<?php } ?>
<?php if ($show_display_category->fields['configuration_key'] == 'SHOW_PRODUCT_INFO_MAIN_SPECIALS_PRODUCTS') { 

?>
<?php
/**
 * display the Special Products Center Box
 */
?>
<?php require($template->get_template_dir('tpl_modules_specials_default.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_specials_default.php'); ?>
<?php } ?>

<?php if ($show_display_category->fields['configuration_key'] == 'SHOW_PRODUCT_INFO_MAIN_NEW_PRODUCTS') { ?>
<?php
/**
 * display the New Products Center Box
 */
?>
<?php require($template->get_template_dir('tpl_modules_whats_new.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_whats_new.php'); ?>
<?php } ?>

<?php if ($show_display_category->fields['configuration_key'] == 'SHOW_PRODUCT_INFO_MAIN_UPCOMING') { ?>
<?php
/**
 * display the Upcoming Products Center Box
 */
?>
<?php require($template->get_template_dir('tpl_modules_upcoming_products.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_upcoming_products.php'); ?>
<?php //include(DIR_WS_MODULES . zen_get_module_directory(FILENAME_UPCOMING_PRODUCTS)); ?>
<?php } ?>

<?php
  $show_display_category->MoveNext();
} // !EOF
?>

</td></tr>

<tr><td align="center">
<script type="text/javascript" src="/store/xml/swfobject.js"></script>
<script type="text/javascript">
	swfobject.registerObject("myId", "9.0.0", "/store/images/cs_ad.swf");
</script>
        
<object id="myId" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="555" height="300" vspace="100">
	<param name="movie" value="/store/images/cs_ad.swf" />
    <!--[if !IE]>-->
	<object type="application/x-shockwave-flash" data="/store/images/cs_ad.swf" width="555" height="300">
	<!--<![endif]-->
	<div>
		<h1>Alternative content</h1>
		<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
	</div>
	<!--[if !IE]>-->
	</object>
	<!--<![endif]-->
</object>


TODO: Items changed

</td></tr>

</table>