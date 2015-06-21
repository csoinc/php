<?php
/*
//////////////////////////////////////////////////////////
//  SUPER ORDERS                                        //
//                                                      //
//  By Frank Koehl (PM: BlindSide)                      //
//                                                      //
//  Powered by Zen-Cart (www.zen-cart.com)              //
//  Portions Copyright (c) 2005 The Zen-Cart Team       //
//                                                      //
//  Released under the GNU General Public License       //
//  available at www.zen-cart.com/license/2_0.txt       //
//  or see "license.txt" in the downloaded zip          //
//////////////////////////////////////////////////////////
//  DESCRIPTION:   Contains all the general defines     //
//  necessary for the Super Orders system to operate    //
//  properly.                                           //
//                                                      //
//  You should not have to edit anything in this file.  //
//                                                      //
//////////////////////////////////////////////////////////
// $Id: super_orders_defines.php 25 2006-02-03 18:55:56Z BlindSide $
*/

// Core files
/*
define('FILENAME_SUPER_EDIT', 'super_edit');
define('FILENAME_SUPER_ORDERS', 'super_orders');
define('FILENAME_SUPER_DATA_SHEET', 'super_data_sheet');
define('FILENAME_SUPER_INVOICE', 'super_invoice');
define('FILENAME_SUPER_PACKINGSLIP', 'super_packingslip');
define('FILENAME_SUPER_SHIPPING_LABEL', 'super_shipping_label');
*/
define('FILENAME_ORDER_PAYMENTS', 'order_payments');
define('FILENAME_ORDER_PAYMENTS_DEL', 'order_payments_del');
//define('FILENAME_ORDERS_PAYMENT_TYPES', 'orders_payment_types');

// Reports
//define('FILENAME_SUPER_REPORT_AWAIT_PAY', 'super_report_await_pay');
//define('FILENAME_SUPER_REPORT_CASH', 'super_report_cash');

// Batch Systems
//define('FILENAME_SUPER_BATCH_STATUS', 'super_batch_status');
//define('FILENAME_SUPER_BATCH_FORMS', 'super_batch_forms');

// Boxes
define('BOX_CUSTOMERS_SUPER_ORDERS', 'Super Orders');
define('BOX_CUSTOMERS_SUPER_BATCH_STATUS', 'Batch Status Update');
define('BOX_CUSTOMERS_SUPER_BATCH_FORMS', 'Batch Form Print');
define('BOX_REPORTS_SUPER_REPORT_AWAIT_PAY', 'Orders Awaiting Payment');
define('BOX_REPORTS_SUPER_REPORT_CASH', 'Cash Report');
define('BOX_REPORTS_SUPER_PAYMENT_TYPES', 'Payment Types');

define('BUTTON_DEL_PAYMENT', 'Delete');
define('BUTTON_EDIT_PAYMENT', 'Edit');

define('TABLE_HEADING_CARD_EXPIRY', 'Exp.');
define('TABLE_HEADING_DATE_MODIFIED', 'Modified');
define('TEXT_CARD_EXPIRY', 'Expiry Date(mmyy):');


// Table names
define('TABLE_YO_PURCHASE_ORDERS', DB_PREFIX . 'orders_purchase_orders');
define('TABLE_YO_PAYMENTS', DB_PREFIX . 'orders_payments');
define('TABLE_YO_PAYMENT_TYPES', DB_PREFIX . 'orders_payment_types');
define('TABLE_YO_REFUNDS', DB_PREFIX . 'orders_refunds');
define('TABLE_CUSTOMERS_ADMIN_NOTES', DB_PREFIX . 'customers_admin_notes');


// DO NOT EDIT!
define('SUPER_ORDERS_VERSION', '2.0');
// DO NOT EDIT!
?>