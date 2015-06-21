<?php
/**
 * Common Template - tpl_box_default_left.php
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_box_default_left.php 2975 2006-02-05 19:33:51Z birdbrain $
 */

// choose box images based on box position
  if ($title_link) {
    $title = '<a href="' . zen_href_link($title_link) . '">' . $title . BOX_HEADING_LINKS . '</a>';
  }
  $sidebox_class = '';
  if ($title == BOX_HEADING_CATEGORIES) {
	$sidebox_class ='_category';
  } else if ($title == '<label>'.BOX_HEADING_SEARCH.'</label>') {
	$sidebox_class ='_search';
  }	else {
    $sidebox_class ='_category';
  } 
?>
<!--// bof: <?php echo $box_id;?> //-->
<TABLE CELLSPACING=0 CELLPADDING=0 class="sidebox_left">
  <TR><TD HEIGHT=5></TD></TR>
  <TR><TD WIDTH="280" VALIGN=top>
<table cellpadding="0" cellspacing="0" align="center" class="box_header<?php echo $sidebox_class; ?>">
<tbody>
<tr><td class="hd_left"></td>
<td class="hd_center" ><?php echo $title; ?></td>
<td class="hd_right"></td></tr>
</tbody></table>
  <TABLE CELLSPACING=0 CELLPADDING=0 class="box_body<?php echo $sidebox_class; ?>">
    <tr><td>
      <div class="box_wrapper">
   	  <?php echo $content; ?>
    </div></td></tr>
    <TR class="box_bottom<?php echo $sidebox_class; ?>"><TD HEIGHT=17></TD></TR>
  </TABLE>
              
  </TD></TR>
  <TR><TD HEIGHT=5></TD></TR>        
</TABLE>
<!--// eof: <?php echo $box_id; ?> //-->

