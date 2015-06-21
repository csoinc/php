<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=account.<br />
 * Displays previous orders and options to change various Customer Account settings
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_account_default.php 4086 2006-08-07 02:06:18Z ajeh $
 */
?>

<div class="centerColumn" id="accountDefault">

<h1 id="accountDefaultHeading"><?php echo HEADING_TITLE; ?></h1>
<?php if ($messageStack->size('account') > 0) echo $messageStack->output('account'); ?>

<?php
    if (zen_count_customer_orders() > 0) {
  ?>
<p class="forward"><?php echo '<a href="' . zen_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . OVERVIEW_SHOW_ALL_ORDERS . '</a>'; ?></p>
<br class="clearBoth" />
<table width="100%" border="0" cellpadding="0" cellspacing="0" id="prevOrders">
<caption><h2><?php echo OVERVIEW_PREVIOUS_ORDERS; ?></h2></caption>
    <tr class="tableHeading">
    <th scope="col"><?php echo TABLE_HEADING_DATE; ?></th>
    <th scope="col"><?php echo TABLE_HEADING_ORDER_NUMBER; ?></th>
    <th scope="col"><?php echo TABLE_HEADING_SHIPPED_TO; ?></th>
    <th scope="col"><?php echo TABLE_HEADING_STATUS; ?></th>
    <th scope="col"><?php echo TABLE_HEADING_TOTAL; ?></th>
    <th scope="col"><?php echo TABLE_HEADING_VIEW; ?></th>
  </tr>
<?php
  foreach($ordersArray as $orders) {
?>
  <tr>
    <td width="70px"><?php echo zen_date_short($orders['date_purchased']); ?></td>
    <td width="30px"><?php echo TEXT_NUMBER_SYMBOL . $orders['orders_id']; ?></td>
    <td><address><?php echo zen_output_string_protected($orders['order_name']) . '<br />' . $orders['order_country']; ?></address></td>
    <td width="70px"><?php echo $orders['orders_status_name']; ?></td>
    <td width="70px" align="right"><?php echo $orders['order_total']; ?></td>
    <td align="right"><?php echo '<a href="' . zen_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'], 'SSL') . '"> ' . zen_image_button(BUTTON_IMAGE_VIEW_SMALL, BUTTON_VIEW_SMALL_ALT) . '</a>'; ?></td>
  </tr>
<?php
  }
?>
</table>
<?php
  }
?>
<br class="clearBoth" />
<?php
//方案列表
if(zen_not_null($_GET['del_project_id']))
$db->Execute("delete from ".DESIGN_PROJECT." where design_project_id='".$_GET['del_project_id']."'");
$project_list=$db->Execute("select dp.*,pd.products_name
					from ".DESIGN_PROJECT." dp," .TABLE_PRODUCTS . " p,".TABLE_PRODUCTS_DESCRIPTION." pd
					where dp.products_id=p.products_id
					and pd.products_id=p.products_id
					and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
					and dp.customers_id=".(int)$_SESSION['customer_id']."
					order by p.products_id
					");
if($project_list->RecordCount()>0){
	?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" id="prevJects">
<caption><h2><?php echo OVERVIEW_PREVIOUS_PROJECTS; ?></h2></caption>
    <tr class="tableHeading">
    <th scope="col"><?php echo TABLE_HEADING_DATE; ?></th>
	<th scope="col"><?php echo TEXT_DESIGN_ACCOUNT_PRODUCT_NAME; ?></th>
    <th scope="col"><?php echo TEXT_DESIGN_ACCOUNT_PROJECT_NAME; ?></th>
    <th scope="col"><?php echo TEXT_DESIGN_ACCOUNT_LOADING; ?></th>
  </tr>
	<?php while(!$project_list->EOF) { ?>
<tr>
	<td width="150px"><?php echo $project_list->fields['project_time']?></td>
	<td width="150px"><?php echo $project_list->fields['products_name'];?></td>
	<td width="150px"><?php echo $project_list->fields['design_project_name'];?></td>
	<td align="right"><a href="<?php echo zen_href_link("design","products_id=".$project_list->fields['products_id']."&project_id=".$project_list->fields['design_project_id'])?>"><?php echo zen_image_button("button_load_project.gif");?></a>
	<a href="<?php echo zen_href_link(FILENAME_ACCOUNT,"del_project_id=".$project_list->fields['design_project_id'])?>"><?php echo zen_image_button("button_delete_project.gif");?></a></td>
	</tr>
	<?php $project_list->MoveNext();} ?>
</table><br class="clearBoth" />
<?php } //方案结束?>
<div id="accountLinksWrapper" class="back">
<h2><?php echo MY_ACCOUNT_TITLE; ?></h2>
<ul id="myAccountGen" class="list">
<li><?php echo ' <a href="' . zen_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . MY_ACCOUNT_INFORMATION . '</a>'; ?></li>
<li><?php echo ' <a href="' . zen_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . MY_ACCOUNT_ADDRESS_BOOK . '</a>'; ?></li>
<li><?php echo ' <a href="' . zen_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL') . '">' . MY_ACCOUNT_PASSWORD . '</a>'; ?></li>
</ul>


<?php
  if (SHOW_NEWSLETTER_UNSUBSCRIBE_LINK !='false' or CUSTOMERS_PRODUCTS_NOTIFICATION_STATUS !='0') {
?>
<h2><?php echo EMAIL_NOTIFICATIONS_TITLE; ?></h2>
<ul id="myAccountNotify" class="list">
<?php
  if (SHOW_NEWSLETTER_UNSUBSCRIBE_LINK=='true') {
?>
<li><?php echo ' <a href="' . zen_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') . '">' . EMAIL_NOTIFICATIONS_NEWSLETTERS . '</a>'; ?></li>
<?php } //endif newsletter unsubscribe ?>
<?php
  if (CUSTOMERS_PRODUCTS_NOTIFICATION_STATUS == '1') {
?>
<li><?php echo ' <a href="' . zen_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL') . '">' . EMAIL_NOTIFICATIONS_PRODUCTS . '</a>'; ?></li>

<?php } //endif product notification ?>
</ul>

<?php } // endif don't show unsubscribe or notification ?>
</div>

<?php
// only show when there is a GV balance
  if ($customer_has_gv_balance ) {
?>
<div id="sendSpendWrapper">
<?php require($template->get_template_dir('tpl_modules_send_or_spend.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_send_or_spend.php'); ?>
</div>
<?php
  }
?>
<br class="clearBoth" />
</div>