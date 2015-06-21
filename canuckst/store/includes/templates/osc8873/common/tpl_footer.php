<?php
/*
  $Id: footer.php,v 1.26 2003/02/10 22:30:54 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
   </TD></TR>
   <TR><TD>  
        <TABLE CELLSPACING=0 CELLPADDING=0 id="main_footer">
         <TD><?php echo FOOTER_TEXT_BODY ?> <A HREF=<?php echo zen_href_link(FILENAME_CONDITIONS); ?> ><?php echo BOX_INFORMATION_CONDITIONS; ?></A> 
         | <A HREF=<?php echo zen_href_link(FILENAME_SITE_MAP); ?> ><?php echo BOX_INFORMATION_SITE_MAP; ?></A>  
         </TD></TR>
        </TABLE>
   </TD></TR>
  </table>
  
<?php if (ZEN_CONFIG_USER_TRACKING == 'true') { zen_update_user_tracking(); } ?>