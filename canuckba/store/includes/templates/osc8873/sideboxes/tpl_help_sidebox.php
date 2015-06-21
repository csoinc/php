<?php
/**
 * help sidebox - allows the live help Craft Syntax sidebox to be added to other domains
 *
 * @package templateSystem
 * @copyright 2008 The-Test-Site.info
 * @copyright Portions Copyright 2003-2007 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: help_sidebox.php 2008-04-26 Monty $
 */

$content = '';
$content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent">';

// Replace the text and HTML tags between the apostophes on line 18.
// Use as many or as few lines using this model as you need for your custom content.
$content .= '' . TEXT_HELP_SIDEBOX . '';
$content .= '';

$content .= '</div>';
?>