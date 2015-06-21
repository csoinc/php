<?php
/*
 $Id: header.php,v 1.42 2003/06/10 18:20:38 hpdl Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/
// Display all header alerts via messageStack:
if ($messageStack->size('header') > 0) {
  echo $messageStack->output('header');
}
?>

<TABLE CELLSPACING=0 CELLPADDING=0 WIDTH=1024 ALIGN=center>
	<TR>
		<TD HEIGHT=5></TD>
	</TR>
	<TR>
		<TD>
			<TABLE id="main_header" CELLSPACING=0 CELLPADDING=0>
				<TR>
					<TD height="65" width="844px">
					<A HREF=<?php echo zen_href_link('index'); ?> title="Canuck Volleyball Store"><IMG SRC="images/blank2x2.gif" WIDTH="400px" HEIGHT="65px" > </A>
					<A HREF="http://www.canuckbadminton.com/store/" title="Canuck Badminton Store"><IMG SRC="images/blank2x2.gif" WIDTH="190px" HEIGHT="65px" > </A>
					<A HREF="https://www.facebook.com/home.php?clk_loc=5#!/Canuckstuff?fref=ts" target="_canuckface" ><IMG SRC="/img/facebook_canuckstuff.jpg" WIDTH="120px" /></A>
					<A HREF="http://bit.ly/1aAE9EK" target="_canucktube" ><IMG SRC="/img/youtube_canuckstuff.jpg" width="110px" /></A>
					</TD>

					<td width="180px" id="cart">
						<div class="globalNav">
							<span id="orderline">1-416-299-1704</span>
						</div>
						<div class="globalNav">
							<span id="orderline">1-800-968-9306</span>
						</div>
						
						
<?php 
	if (STORE_STATUS == '0') { 
?>            
<a href=<?php echo zen_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'); ?> title="<?php echo $_SESSION['cart']->count_contents()?> item<?php echo ($_SESSION['cart']->count_contents() > 1?'s':'')?> in cart">
            <img src="images/cart.png" /> Shopping Cart (<?php echo $_SESSION['cart']->count_contents()?>) </A> 
<?php 
	}
?>
<!--bof RSS Feed -->
<!--<div id="RSSFeedLink" <?php // style="float:right; position:relative; margin:0px 8px;" ?> ><?php // echo rss_feed_link(RSS_ICON); ?></div> -->
<!--eof RSS Feed -->
             </td>
			</TR>
			<tr>
					<td height="30px" colspan="2" >
						<div class="globalNav">
							<dl class="nondropdown">
								<a href="/">Home</a>
							</dl>
							<dl class="separator">|
							</dl>
							<dl class="dropdown">
								<dt id="products-ddheader" onmouseover="ddMenu('products',1)"
									onmouseout="ddMenu('products',-1)">Products</dt>
								<dd id="products-ddcontent" onmouseover="cancelHide('products')"
									onmouseout="ddMenu('products',-1)">
									<ul>
										<li><a href="/store" class="underline">Volleyball</a></li>
										<li><a href="http://www.canuckbadminton.com/store" class="underline">Badminton</a></li>
									</ul>
								</dd>
							</dl>

							<dl class="separator">|
							</dl>
							<dl class="dropdown">
								<dt id="services-ddheader" onmouseover="ddMenu('services',1)"
									onmouseout="ddMenu('services',-1)">Services</dt>
								<dd id="services-ddcontent" onmouseover="cancelHide('services')"
									onmouseout="ddMenu('services',-1)">
									<ul>
										<li><a href="index.php?main_page=page&id=15&chapter=30">Printing
												Service</a></li>
										<li><a href="index.php?main_page=page&id=11">VIP Service</a></li>
									</ul>
								</dd>
							</dl>

							<dl class="separator">|
							</dl>
							<dl class="dropdown">
								<dt id="one-ddheader" onmouseover="ddMenu('one',1)"
									onmouseout="ddMenu('one',-1)">Community</dt>
								<dd id="one-ddcontent" onmouseover="cancelHide('one')"
									onmouseout="ddMenu('one',-1)">
									<ul>
										<li><a href="index.php?main_page=page&id=16&chapter=40"
											class="underline">Clubs</a></li>
										<li><a href="index.php?main_page=page&id=18&chapter=43"
											class="underline">Camps</a></li>
										<li><a href="index.php?main_page=page&id=19&chapter=46"
											class="underline">Leagues</a></li>
										<li><a href="index.php?main_page=page&id=17&chapter=50"
											class="underline">Tournaments</a></li>
									</ul>
								</dd>
							</dl>
							<dl class="separator">|
							</dl>
							<dl class="dropdown" width="100px">
								<dt id="two-ddheader" onmouseover="ddMenu('two',1)"
									onmouseout="ddMenu('two',-1)">Events</dt>
								<dd id="two-ddcontent" onmouseover="cancelHide('two')"
									onmouseout="ddMenu('two',-1)">
									<ul>
										<li><a href="index.php?main_page=page&id=29" class="underline">Current
												Events</a></li>
										<li><a href="index.php?main_page=page&id=29" class="underline">Past
												Events</a></li>
									</ul>
								</dd>
							</dl>
							<dl class="separator">|
							</dl>
							<dl class="nondropdown">
								<A HREF=<?php echo zen_href_link(FILENAME_CONTACT_US); ?>><?php echo BOX_INFORMATION_CONTACT; ?>
								</A>
							</dl>
							
						</div>


<span class="globalNav" id="account" style="float: right; width: 40%" >
<?php  //display account info
	  if ($_SESSION['customer_id']) { ?> <a href="<?php echo zen_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"
		CLASS=ml><?php echo HEADER_TITLE_MY_ACCOUNT; ?> </a>&nbsp;&nbsp; <a
		href="<?php echo zen_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>"
		CLASS=ml><?php echo HEADER_TITLE_LOGOFF; ?> </a>
					
<?php
      } else {
        if (STORE_STATUS == '0') {
?>
         <A HREF="<?php echo zen_href_link(FILENAME_CREATE_ACCOUNT); ?>" CLASS=ml><?php echo HEADER_TITLE_CREATE_ACCOUNT; ?></A> &nbsp;&nbsp;
         <a href="<?php echo zen_href_link(FILENAME_LOGIN, '', 'SSL'); ?>" CLASS=ml><?php echo HEADER_TITLE_LOGIN; ?></a>
<?php } } ?>
</span>

                </td>
				</tr>
			</TABLE>

		</TD>
	</TR>
	<TR>
		<TD HEIGHT=5></TD>
	</TR>
	<TR>
		<TD>