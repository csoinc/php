<?php
/**
 * boxes (tableBox) Class.
 *
 * @package classes
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: boxes.php 3039 2006-02-15 00:29:28Z wilt $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
/**
 * Deprecated Class
 *
 * @package classes
 */
class tableBox extends base {
  var $table_border = '0';
  var $table_width = '100%';
  var $table_cellspacing = '0';
  var $table_cellpadding = '2';
  var $table_parameters = '';
  var $table_row_parameters = '';
  var $table_data_parameters = '';

  // class constructor
  function tableBox($contents, $direct_output = false) {
    $tableBox_string = '<table border="' . zen_output_string($this->table_border) . '" width="' . zen_output_string($this->table_width) . '" cellspacing="' . zen_output_string($this->table_cellspacing) . '" cellpadding="' . zen_output_string($this->table_cellpadding) . '"';
    if (zen_not_null($this->table_parameters)) $tableBox_string .= ' ' . $this->table_parameters;
    $tableBox_string .= '>' . "\n";

    for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
      if (isset($contents[$i]['form']) && zen_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
      $tableBox_string .= '  <tr';
      if (zen_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
      if (isset($contents[$i]['params']) && zen_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
      $tableBox_string .= '>' . "\n";

      if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
        for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
          if (isset($contents[$i][$x]['text']) && zen_not_null($contents[$i][$x]['text'])) {
            $tableBox_string .= '    <td';
            if (isset($contents[$i][$x]['align']) && zen_not_null($contents[$i][$x]['align'])) $tableBox_string .= ' align="' . zen_output_string($contents[$i][$x]['align']) . '"';
            if (isset($contents[$i][$x]['params']) && zen_not_null($contents[$i][$x]['params'])) {
              $tableBox_string .= ' ' . $contents[$i][$x]['params'];
            } elseif (zen_not_null($this->table_data_parameters)) {
              $tableBox_string .= ' ' . $this->table_data_parameters;
            }
            $tableBox_string .= '>';
            if (isset($contents[$i][$x]['form']) && zen_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
            $tableBox_string .= $contents[$i][$x]['text'];
            if (isset($contents[$i][$x]['form']) && zen_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
            $tableBox_string .= '</td>' . "\n";
          }
        }
      } else {
        $tableBox_string .= '    <td';
        if (isset($contents[$i]['align']) && zen_not_null($contents[$i]['align'])) $tableBox_string .= ' align="' . zen_output_string($contents[$i]['align']) . '"';
        if (isset($contents[$i]['params']) && zen_not_null($contents[$i]['params'])) {
          $tableBox_string .= ' ' . $contents[$i]['params'];
        } elseif (zen_not_null($this->table_data_parameters)) {
          $tableBox_string .= ' ' . $this->table_data_parameters;
        }
        $tableBox_string .= '>' . $contents[$i]['text'] . '</td>' . "\n";
      }

      $tableBox_string .= '  </tr>' . "\n";
      if (isset($contents[$i]['form']) && zen_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
    }

    $tableBox_string .= '</table>' . "\n";

    if ($direct_output == true) echo $tableBox_string;

    return $tableBox_string;
  }
}

?>