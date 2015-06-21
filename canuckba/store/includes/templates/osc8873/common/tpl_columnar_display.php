<?php
/**
 * Common Template - tpl_columnar_display.php
 *
 * This file is used for generating tabular output where needed, based on the supplied array of table-cell contents.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_columnar_display.php 3157 2006-03-10 23:24:22Z drbyte $
 */

?>
<?php
  if ($title) {
  ?>
<table cellpadding="0" cellspacing="0" class="main_content_header">
                <tbody><tr><td class="hd_left"></td><td class="hd_center"> &nbsp; &nbsp; <?php echo $title; ?></td><td class="hd_right"></td></tr>
               </tbody>
</table>

<?php
 }
 ?>
<?php
if (is_array($list_box_contents) > 0 ) {
echo '<table border="0" width="100%" cellspacing="0" cellpadding="0" class="main_content_body">';
 for($row=0;$row<sizeof($list_box_contents);$row++) {
    $params = "";
    echo '<tr>';
    //if (isset($list_box_contents[$row]['params'])) $params .= ' ' . $list_box_contents[$row]['params'];
    for($col=0;$col<sizeof($list_box_contents[$row]);$col++) {
      $r_params = "";
      if (isset($list_box_contents[$row][$col]['params'])) $r_params .= ' ' . (string)$list_box_contents[$row][$col]['params'];
      if (isset($list_box_contents[$row][$col]['text'])) {
         echo '<td ' . $r_params . '>' . $list_box_contents[$row][$col]['text'] .  '</td>'; 
      }
    }
    echo '</tr>';
	if ($row < sizeof($list_box_contents)-1 )  echo '<tr class="body_hr"><td colspan="' . sizeof($list_box_contents[$row]) . '"></td></tr>';	
  }
echo '</table><table class="main_content_bottom"><tr><td></td></tr></table>';
}
?> 
