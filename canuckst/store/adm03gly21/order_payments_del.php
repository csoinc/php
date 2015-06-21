<?php
/*
//////////////////////////////////////////////////////////
//  ORDER PAYMENTS                                      //
//                                                      //
//  By Yan Huang 				                        //
//                                                      //
//  Powered by Zen-Cart (www.zen-cart.com)              //
//  Portions Copyright (c) 2009 The Zen-Cart Team       //
//                                                      //
//  Released under the GNU General Public License       //
//  available at www.zen-cart.com/license/2_0.txt       //
//  or see "license.txt" in the downloaded zip          //
//////////////////////////////////////////////////////////
//  DESCRIPTION:   This file generates a pop-up window  //
//  that is used to enter and edit payment information  //
//  for a given order.                                  //
//////////////////////////////////////////////////////////
// $Id: order_payments.php 36 2009-4-5 $
*/

  require('includes/application_top.php');
  require_once(DIR_WS_CLASSES . 'yan_order.php');
  global $db;

  $oID = $_GET['oID'];
  $payment_mode = $_GET['payment_mode'];
  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $so = new yan_order($oID);

  // the following "if" clause actually inputs data into the DB
  if ($_GET['process'] == '1') {
    switch ($action) {
		
      // removes requested payment data from the database (not recoverable!)
      case 'delete':
        $affected_rows = 0;
        switch ($payment_mode) {
          case 'payment':
            $so->delete_payment($_GET['payment_id']);
            $affected_rows++;
/*
            // handle the refunds, if any
            if ($_GET['refund_action']) {
              for ($a = 0; $a < sizeof($so->refund); $a++) {
                if ($so->refund[$a]['payment'] == $_GET['payment_id']) {
                  switch ($_GET['refund_action']) {
                    case 'keep':
                      $so->update_refund($so->refund[$a]['index'], 0);
                      $affected_rows++;
                    break;

                    case 'move':
                      $so->update_refund($so->refund[$a]['index'], $_GET['new_payment_id']);
                      $affected_rows++;
                    break;

                    case 'drop':
                      $so->delete_refund($so->refund[$a]['index']);
                      $affected_rows++;
                    break;
                  }
                }
              }
            }  // END if ($_GET['refund_action'])
*/
          break;  // END case 'payment'

/*
          case 'purchase_order':
            $so->delete_purchase_order($_GET['purchase_order_id']);
            $affected_rows++;

            // handle the payments, if any
            if ($_GET['payment_action']) {
              for ($a = 0; $a < sizeof($so->po_payment); $a++) {
                if ($so->po_payment[$a]['assigned_po'] == $_GET['purchase_order_id']) {
                  switch ($_GET['payment_action']) {
                    case 'keep':
                      $so->update_payment($so->po_payment[$a]['index'], 0);
                      $affected_rows++;
                    break;

                    case 'move':
                      $so->update_payment($so->po_payment[$a]['index'], $_GET['new_po_id']);
                      $affected_rows++;
                    break;

                    case 'drop':
                      $so->delete_payment($so->po_payment[$a]['index']);
                      $affected_rows++;
                    break;
                  }
                }
              }
            }  // END if ($_GET['payment_action'])
          break;  // END case 'purchase_order'


          case 'refund':
            $so->delete_refund($_GET['refund_id']);
            $affected_rows++;
          break;  // END case 'refund'
*/
		}  // END switch ($payment_mode)

        zen_redirect(zen_href_link(FILENAME_ORDER_PAYMENTS_DEL, 'oID=' . $so->oID . '&affected_rows=' . $affected_rows . '&action=delete_confirm', 'NONSSL'));
      break;  // END case 'delete'

    }  // END switch ($action)

  }  // END if ($process)

  // the "else" handles displaying & gathering data to/from the user
  else {
    //_TODO code to customize the TITLE goes here
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/super_stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/general.js"></script>
<script language="javascript" src="includes/formval.js"></script>
<script language="JavaScript" type="text/javascript">
  <!--
  function returnParent() {
    window.opener.location.reload(true);
    window.opener.focus();
    self.close();
  }


// Only script specific to this form goes here.
// General-purpose routines are in a separate file.
  function validateOnSubmit() {
    var elem;
    var errs=0;
    // execute all element validations in reverse order, so focus gets
    // set to the first one in error.
    if (!validateTelnr  (document.forms.demo.telnr, 'inf_telnr', true)) errs += 1;
    if (!validateAge    (document.forms.demo.age,   'inf_age',  false)) errs += 1;
    if (!validateEmail  (document.forms.demo.email, 'inf_email', true)) errs += 1;
    if (!validatePresent(document.forms.demo.from,  'inf_from'))        errs += 1;

    if (errs > 1)  alert('There are fields which need correction before sending');
    if (errs == 1) alert('There is a field which needs correction before sending');

    return (errs==0);
  };

  //-->
</script>
</head>
<body onLoad="self.focus()">
<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
<tr><td align="center"><table border="0" cellspacing="0" cellpadding="2">
<?php
    switch ($action) {
      case 'delete':

        $index = $_GET['index'];
        echo '  ' . zen_draw_form('delete', FILENAME_ORDER_PAYMENTS_DEL, '', 'get', '', true) . NL;
        echo '    ' . zen_draw_hidden_field('action', $action) . NL;
        echo '    ' . zen_draw_hidden_field('process', 1) . NL;
        echo '    ' . zen_draw_hidden_field('payment_mode', $payment_mode) . NL;
        echo '    ' . zen_draw_hidden_field('oID', $so->oID) . NL;

        switch ($payment_mode) {
          case 'payment':
            echo '    ' . zen_draw_hidden_field('payment_id', $index) . NL;

           // check for attached refunds
            $refund_exists = false;
/*             $refund_count = 0;
            for ($a = 0; $a < sizeof($so->refund); $a++) {
              if ($so->refund[$a]['payment'] == $index) {
                $refund_exists = true;
                $refund_count++;
              }
            }
*/			
?>
    <tr>
      <td colspan="2" align="center" class="pageHeading"><?php echo HEADER_DELETE_PAYMENT; ?></td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="main"><strong><?php echo HEADER_ORDER_ID . $so->oID . '<br />' .  HEADER_PAYMENT_UID . $index; ?></strong></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
    </tr>
<?php
            if ($refund_exists) {
              $payment_array = $so->build_payment_array();
              // zen_draw_radio_field($name, $value = '', $checked = false, $compare = '')
?>
    <tr class="dataTableHeadingRow">
      <td colspan="2" align="left" class="dataTableHeadingContent"><?php echo sprintf(TEXT_REFUND_ACTION, $refund_count); ?></td>
    </tr>
    <tr>
      <td colspan="2" align="left" class="main"><?php echo zen_draw_radio_field('refund_action', 'keep', false) . REFUND_ACTION_KEEP; ?></td>
    </tr>
    <tr>
      <td colspan="2" align="left" class="main"><?php echo zen_draw_radio_field('refund_action', 'move', false) . REFUND_ACTION_MOVE . zen_draw_pull_down_menu('new_payment_id', $payment_array, '', ''); ?></td>
    </tr>
    <tr>
      <td colspan="2" align="left" class="main"><?php echo zen_draw_radio_field('refund_action', 'drop', false) . REFUND_ACTION_DROP; ?></td>
    </tr>
<?php
            }
?>
    <tr>
      <td colspan="2" align="center"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
    </tr>
    <tr class="warningBox">
      <td colspan="2" align="center" class="warningText"><?php echo WARN_DELETE_PAYMENT; ?>
<?php
          break;

          case 'purchase_order':
            echo '    ' . zen_draw_hidden_field('purchase_order_id', $index) . NL;

            // check for attached payments, if any
            $payment_exists = false;
            $payment_count = 0;
            for ($a = 0; $a < sizeof($so->po_payment); $a++) {
              if ($so->po_payment[$a]['assigned_po'] == $index) {
                $payment_exists = true;
                $payment_count++;
              }
            }
?>
    <tr>
      <td colspan="2" align="center" class="pageHeading"><?php echo HEADER_DELETE_PO; ?></td>
    </tr>
    <tr>
      <td align="center" colspan="2" class="main"><strong><?php echo HEADER_ORDER_ID . $so->oID . '<br />' . HEADER_PO_UID . $index; ?></strong></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
    </tr>
<?php
            if ($payment_exists) {
              $po_array = $so->build_po_array();
?>
    <tr class="dataTableHeadingRow">
      <td colspan="2" align="left" class="dataTableHeadingContent"><?php echo sprintf(TEXT_PAYMENT_ACTION, $payment_count); ?></td>
    </tr>
    <tr>
      <td colspan="2" align="left" class="main"><?php echo zen_draw_radio_field('payment_action', 'keep', false) . PAYMENT_ACTION_KEEP; ?></td>
    </tr>
    <tr>
      <td colspan="2" align="left" class="main"><?php echo zen_draw_radio_field('payment_action', 'move', false) . PAYMENT_ACTION_MOVE . zen_draw_pull_down_menu('new_po_id', $po_array, '', ''); ?></td>
    </tr>
    <tr>
      <td colspan="2" align="left" class="main"><?php echo zen_draw_radio_field('payment_action', 'drop', false) . PAYMENT_ACTION_DROP; ?></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
    </tr>
<?php
            }
?>
    <tr class="warningBox">
      <td colspan="2" align="center" class="warningText"><?php echo WARN_DELETE_PO; ?>
<?php
          break;

          case 'refund':
            echo '    ' . zen_draw_hidden_field('refund_id', $index) . NL;
?>
    <tr>
      <td colspan="2" align="center" class="pageHeading"><?php echo HEADER_DELETE_REFUND; ?></td>
    </tr>
    <tr>
      <td align="center" colspan="2" class="main"><strong><?php echo HEADER_ORDER_ID . $so->oID . '<br />' .  HEADER_REFUND_UID . $index; ?></strong></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
    </tr>
    <tr class="warningBox">
      <td colspan="2" align="center" class="warningText"><?php echo WARN_DELETE_REFUND; ?>
<?php
          break;
        }  // END switch ($payment_mode)
?>
      <p><input type="button" value="<?php echo BUTTON_CANCEL; ?>" onClick="returnParent()">
      <input type="submit" value="<?php echo BUTTON_SUBMIT; ?>" onClick="document.delete.submit();this.disabled=true"></td>
    </tr>
  </form>
  </table></td>
<?php
      break;  // END case 'delete':


      case 'delete_confirm':
        $affected_rows = $_GET['affected_rows'];
?>
    <tr>
      <td colspan="2" align="center" class="pageHeading"><?php echo HEADER_DELETE_CONFIRM; ?></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><?php echo zen_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="main"><?php echo sprintf(TEXT_DELETE_CONFIRM, $affected_rows); ?></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><?php echo zen_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
    </tr>
    <tr>
      <td class="main" colspan="2" align="center"><input type="button" value="<?php echo BUTTON_DELETE_CONFIRM; ?>" onClick="returnParent()"></td>
    </tr>
<?php
      break;  // END case 'delete_confirm'

      case 'confirm':
        $index = $_GET['index'];

        switch ($payment_mode) {
          case 'payment':
            $payment_info = $db->Execute("select p.*, po.po_number
                                          from " . TABLE_YO_PAYMENTS . " p
                                          left join " . TABLE_YO_PURCHASE_ORDERS . " po
                                          on p.purchase_order_id = po.purchase_order_id
                                          where p.payment_id = '" . $index . "' limit 1");
?>
    <tr>
      <td colspan="2" align="center" class="pageHeading"><?php echo HEADER_CONFIRM_PAYMENT; ?></td>
    </tr>
    <tr>
      <td align="left" class="main"><strong><?php echo HEADER_ORDER_ID . $so->oID; ?></strong></td>
      <td align="right" class="main"><strong><?php echo HEADER_PAYMENT_UID . $index; ?></strong></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
    </tr>
    <tr>
      <td class="main" width="50%" align="right"><?php echo TEXT_PAYMENT_TYPE; ?></td>
      <td class="main" width="50%" align="left"><strong><?php echo $so->full_type($payment_info->fields['payment_type']); ?></strong></td>
    </tr>
    <tr>
      <td class="main" width="50%" align="right"><?php echo TEXT_PAYMENT_NAME; ?></td>
      <td class="main" width="50%" align="left"><strong><?php echo $payment_info->fields['payment_name']; ?></strong></td>
    </tr>
    <tr>
      <td class="main" width="50%" align="right"><?php echo TEXT_PAYMENT_NUMBER; ?></td>
      <td class="main" width="50%" align="left"><strong><?php echo $payment_info->fields['payment_number']; ?></strong></td>
    </tr>
    <tr>
      <td class="main" width="50%" align="right"><?php echo TEXT_PAYMENT_AMOUNT; ?></td>
      <td class="main" width="50%" align="left"><strong><?php echo $payment_info->fields['payment_amount']; ?></strong></td>
    </tr>
<!--    <tr>
      <td class="main" width="50%" align="right"><?php echo TEXT_ATTACHED_PO; ?></td>
      <td class="main" width="50%" align="left"><strong><?php echo ($payment_info->fields['purchase_order_id'] == 0 ? TEXT_NONE : $payment_info->fields['po_number']); ?></strong></td>
    </tr>
-->
<?php
          break;

          case 'purchase_order':
            $po = $db->Execute("select po_number from " . TABLE_YO_PURCHASE_ORDERS . " where purchase_order_id = '" . $index . "' limit 1");
?>
    <tr>
      <td colspan="2" align="center" class="pageHeading"><?php echo HEADER_CONFIRM_PO; ?></td>
    </tr>
    <tr>
      <td align="center" colspan="2" class="main"><strong><?php echo HEADER_ORDER_ID . $so->oID . '<br />' . HEADER_PO_UID . $index; ?></strong></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
    </tr>
    <tr>
      <td class="main" width="50%" align="right"><?php echo TEXT_PO_NUMBER; ?></td>
      <td class="main" width="50%"><strong><?php echo $po->fields['po_number']; ?></strong></td>
    </tr>
<?php
          break;

          case 'refund':
            $refund = $db->Execute("select r.*, p.payment_number
                                    from " . TABLE_YO_REFUNDS . " r
                                    left join " . TABLE_YO_PAYMENTS . " p on p.payment_id = r.payment_id
                                    where refund_id = '" . $index . "' limit 1");
?>
    <tr>
      <td colspan="2" align="center" class="pageHeading"><?php echo HEADER_CONFIRM_REFUND; ?></td>
    </tr>
    <tr>
      <td align="left" class="main"><strong><?php echo HEADER_ORDER_ID . $so->oID; ?></strong></td>
      <td align="right" class="main"><strong><?php echo HEADER_REFUND_UID . $index; ?></strong></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
    </tr>
    <tr>
      <td class="main" width="50%" align="right"><?php echo TEXT_ATTACHED_PAYMENT; ?></td>
      <td class="main" width="50%"><strong><?php echo $refund->fields['payment_number']; ?></strong></td>
    </tr>
    <tr>
      <td class="main" width="50%" align="right"><?php echo TEXT_REFUND_NUMBER; ?></td>
      <td class="main" width="50%"><strong><?php echo $refund->fields['refund_number']; ?></strong></td>
    </tr>
    <tr>
      <td class="main" width="50%" align="right"><?php echo TEXT_REFUND_NAME; ?></td>
      <td class="main" width="50%"><strong><?php echo $refund->fields['refund_name']; ?></strong></td>
    </tr>
    <tr>
      <td class="main" width="50%" align="right"><?php echo TEXT_REFUND_AMOUNT; ?></td>
      <td class="main" width="50%"><strong><?php echo $refund->fields['refund_amount']; ?></strong></td>
    </tr>
    <tr>
      <td class="main" width="50%" align="right"><?php echo TEXT_REFUND_TYPE; ?></td>
      <td class="main" width="50%"><strong><?php echo $so->full_type($refund->fields['refund_type']); ?></strong></td>
    </tr>
 <?php
          break;
        }  // END switch ($payment_mode)
?>
    <tr>
      <td colspan="2" align="center"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
    </tr>
    <tr>
      <td colspan="2"><table border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td class="main"><input type="button" value="<?php echo BUTTON_SAVE_CLOSE; ?>" onClick="this.disabled=true; returnParent();"></td>
          <td class="main"><?php echo '<input type="button" value="' . BUTTON_MODIFY . '" onclick="this.disabled=true; window.location.href=\'' . zen_href_link(FILENAME_ORDER_PAYMENTS, 'oID=' . $so->oID . '&payment_mode=' . $payment_mode . '&index=' . $index . '&action=update', 'NONSSL') . '\'">'; ?></td>
          <td class="main"><?php echo '<input type="button" value="' . BUTTON_ADD_NEW . '" onclick="this.disabled=true; window.location.href=\'' . zen_href_link(FILENAME_ORDER_PAYMENTS, 'oID=' . $so->oID . '&payment_mode=' . $payment_mode . '&action=add', 'NONSSL') . '\'">'; ?></td>
        </tr>
    </tr>
  </table></td>
<?php
      break;  // END case 'confirm'

    }  // END switch ($action)

  }  // END else
?>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>