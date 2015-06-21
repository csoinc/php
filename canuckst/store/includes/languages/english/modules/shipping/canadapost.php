<?php
/*
  $Id: canadapost.php,v 3.6 03/11/01

  Before use this class, you should open a Canada  
  Post Eparcel Account, and change the CPCIP to 
  your ID. Visit www.canadapost.ca for detail. 
   
  XML connection method with Canada Post. 

  OSC 2.2 MS2 version.
   
  Copyright (c) 2002,2003 Kelvin Zhang (kelvin@syngear.com)
  Modified by Kenneth Wang (kenneth@cqww.net), 2002.11.12
  3.6 for OSC 2.2 MS2 with LXWXH added by Tom St.Croix (management@betterthannature.com)

***********************************************************************************************
  Note: Find $readytoship= to change this option 
  $readytoship=1, this item will be shipped in its original box
  $readytoship=0 will use the Canada Post Shipping Profile to determine the right box to use
***********************************************************************************************

  Released under the GNU General Public License
*/

define('MODULE_SHIPPING_CANADAPOST_TEXT_TITLE', 'Canada Post');
define('MODULE_SHIPPING_CANADAPOST_TEXT_DESCRIPTION', 'Canada Post Parcel Service<p><strong>CPC Profile Information </strong>can be obtained at http://sellonline.canadapost.ca<br /><a href=http://sellonline.canadapost.ca/servlet/LogonServlet?Language=0 target="_blank">> Modify my profile <</a>');

define('MODULE_SHIPPING_PACKAGING_RESULTS', ' box(es) to be shipped');
//define('MODULE_SHIPPING_PACKAGING_RESULTS', ' box(es), total weight ');

define('MODULE_SHIPPING_CANADAPOST_CALC_ERROR','An unknown error occured with the Canada Post shipping calculations.');
define('MODULE_SHIPPING_CANADAPOST_ERROR_INFO','<br>If you prefer to use Canada Post as your shipping method, please contact the '.STORE_NAME.' via <a href="mailto:'.STORE_OWNER_EMAIL_ADDRESS.'"><u>Email</U></a>.');
define('MODULE_SHIPPING_CANADAPOST_COMM_ERROR','Cannot reach Canada Post Server. You may refresh this page (Press F5 on your keyboard) to try again.');

?>
