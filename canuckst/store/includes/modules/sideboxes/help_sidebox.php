<?php
/**
 * help sidebox - allows the live help Craft Syntax sidebox to be added to other domains
 *
 * @package templateSystem
 * @copyright 2008 The-Test-Site.info
  * @copyright Portions Copyright 2003-2008 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: help_sidebox.php 2008-04-26 Monty $
 */

  // test if box should display
  $show_help_sidebox = true;

  if ($show_help_sidebox == true) {
      require($template->get_template_dir('tpl_help_sidebox.php',DIR_WS_TEMPLATE, $current_page_base,'sideboxes'). '/tpl_help_sidebox.php');
      $title =  BOX_HEADING_HELP_SIDEBOX;
      $title_link = false;
      require($template->get_template_dir($column_box_default, DIR_WS_TEMPLATE, $current_page_base,'common') . '/' . $column_box_default);
 }
?>