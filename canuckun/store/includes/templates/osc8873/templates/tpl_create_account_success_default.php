<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=create-account_success.<br />
 * Displays confirmation that a new account has been created.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_create_account_success_default.php 2540 2005-12-11 07:55:22Z birdbrain $
 */
?>
<div id="createAcctSuccess">
<h1><?php echo HEADING_TITLE; ?></h1>
<!--
<table cellpadding="0" cellspacing="0">
                <tbody><tr><td height=2></td></tr><tr><td><img src="images/m22.gif" height="29" width="9"></td><td class="fe" bgcolor="#c6ed4e" width="506"> &nbsp; &nbsp; <?php echo HEADING_TITLE; ?></td><td><img src="images/m23.gif" height="29" width="10"></td></tr>
               </tbody>
</table>
-->

<div id="createAcctSuccessMainContent" class="content"><?php echo TEXT_ACCOUNT_CREATED; ?></div>

<div class="buttonRow forward"><?php echo '<a href="' . $origin_href . '">' . zen_image_button(BUTTON_IMAGE_CONTINUE, BUTTON_CONTINUE_ALT) . '</a>'; ?></div>
</div>
