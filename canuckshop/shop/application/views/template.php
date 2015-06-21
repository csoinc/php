<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?= $title ?></title>
<link type="image/x-icon" href="<? echo WEB_CONTEXT; ?>/images/canuckvb.ico" rel="icon"  />
<link type="text/css" href="<? echo WEB_CONTEXT; ?>/css/ui-lightness/jquery-ui-1.8.19.custom.css" rel="Stylesheet" />	
<link type="text/css" href="<? echo WEB_CONTEXT; ?>/css/default.css" rel="stylesheet" />
<link type="text/css" href="<? echo WEB_CONTEXT; ?>/css/dropdown.css" rel="stylesheet" />
<link type="text/css" href="<? echo WEB_CONTEXT; ?>/css/module.css" rel="stylesheet" />
<link type="text/css" media="print" href="<? echo WEB_CONTEXT; ?>/css/printing.css" rel="stylesheet" />

<script type="text/javascript" src="<? echo WEB_CONTEXT; ?>/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<? echo WEB_CONTEXT; ?>/js/jquery-ui-1.8.19.custom.min.js"></script>
<script type="text/javascript" src="<? echo WEB_CONTEXT; ?>/js/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="<? echo WEB_CONTEXT; ?>/js/jquery.jec-1.3.4.js"></script>
<script type="text/javascript" src="<? echo WEB_CONTEXT; ?>/js/dropdown.js"></script>
<script type="text/javascript" src="<? echo WEB_CONTEXT; ?>/js/printing.js"></script>
<script type="text/javascript" src="<? echo WEB_CONTEXT; ?>/js/module.js"></script>

</head>
<body>
	<div id="mainWrapper">
		<div id="header" class='noprint'>
		    <?= $header ?>
		</div>
		<div id="trip" class="noprint">
		    <span style="color:#6060E0;font-style:italic;font-size:0.9em;font-weight:bold;"><?= $trip ?></span>
		</div>
		<div id="message" class='noprint'>
		    <span style="color:#60E060;font-style:italic;font-size:0.8em;font-weight:normal;"><?= $message ?></span>
		</div>
		<div>	
			<table class="uniformTable">
				<tr>
					<td>
						<div id="content">
							<div>
							<?= $content ?>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</div>
		<div id="footer" class="noprint">
		  <?= $footer ?>
		</div>
	</div>
</body>
</html>
