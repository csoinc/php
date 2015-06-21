<?php
  // A71-BackUp 1.5 (15.01.2008) By Alessandro Marinuzzi [Alecos]
  // -> Copyright © 2007-2008 · Alessandro Marinuzzi [Alecos] <--
  //
  //  AUTHOR:
  //  ¯¯¯¯¯¯¯
  //  Alecos ->  Fullname: Alessandro Marinuzzi
  //             Address:  Via Torre Pilo n.14 /c
  //             City:     Palermo
  //             ZipCode:  90151
  //             Country:  Italy
  //
  //  E-Mail:    alecos@alecos.it
  //  Web:       http://www.alecos.it/
  //  A71-BackUp is FreeWare! A PayPal donation is welcome ! For making a donation
  //  visit my web site: http://www.alecos.it/
  //
  ###############################################################
  ############## Do not change the following code! ##############
  ###############################################################
  require('includes/application_top.php');
      if ( ($action == 'backup') ) {
//echo 'backup';
        
  //$tar_file = $_SERVER['HTTP_HOST'].'.tar';  
  $tar_file = str_replace('www.', "",$_SERVER['HTTP_HOST']) .'.tar';
  $web_root = $_SERVER['DOCUMENT_ROOT'];
  chdir("$web_root");
  
  ini_set('memory_limit', '-1');
  ini_set('register_globals', 0);
  ini_set('max_execution_time', 0);

  Class Tar_Archive {
    var $tar_file;
    var $fp;
    function Tar_Archive($tar_file) {
      $this->tar_file = $tar_file;
      $this->fp = fopen($this->tar_file, "wb");
      $tree = $this->build_tree();
      $this->process_tree($tree);
      fputs($this->fp, pack("a512", ""));
      fclose($this->fp);
      ignore_user_abort(true);
      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Cache-Control: private",false);
      header("Content-Type: application/octet-stream");
      header("Content-Disposition: attachment; filename=".basename($tar_file).";");
      header("Content-Transfer-Encoding: binary");
      header("Content-Length: ".filesize($tar_file));
      readfile($tar_file);
      unlink($tar_file);
    }
    function build_tree($dir = '.') {
      $handle = opendir($dir);
      while(false !== ($readdir = readdir($handle))) {
        if ($readdir != '.' && $readdir != '..') {
          $path = $dir.'/'.$readdir;
          if (!is_link($path)) {
            if (is_file($path)) {
              $output[] = substr($path, 2, strlen($path));
            } elseif (is_dir($path)) {
              $output[] = substr($path, 2, strlen($path)).'/';
              $output = array_merge($output, $this->build_tree($path));
            }
          }
        }
      }
      closedir($handle);
      return $output;
    }
    function process_tree($tree) {
      foreach($tree as $pathfile) {
        if (substr($pathfile, -1, 1) == '/') {
          fputs($this->fp, $this->build_header($pathfile));
        } elseif ($pathfile != $this->tar_file) {
          $filesize = filesize($pathfile);
          $block_len = 512*ceil($filesize/512)-$filesize;
          fputs($this->fp, $this->build_header($pathfile));
          fputs($this->fp, file_get_contents($pathfile));
          fputs($this->fp, pack("a".$block_len, ""));
        }
      }
      return true;
    }
    function build_header($pathfile) {
      if (strlen($pathfile) > 99) die('Error');
        $info = stat($pathfile);
        if (is_dir($pathfile)) $info[7] = 0;
          $header = pack("a100a8a8a8a12A12a8a1a100a255", $pathfile, sprintf("%6s ", decoct($info[2])), sprintf("%6s ", decoct($info[4])), sprintf("%6s ", decoct($info[5])), sprintf("%11s ",decoct($info[7])), sprintf("%11s", decoct($info[9])), sprintf("%8s", " "), (is_dir($pathfile) ? "5" : "0"), "", "");
          clearstatcache();
          $checksum = 0;
          for ($i = 0; $i < 512; $i++) {
            $checksum += ord(substr($header,$i,1));
          }
          $checksum_data = pack("a8", sprintf("%6s ", decoct($checksum)));
          for ($i = 0, $j = 148; $i < 7; $i++, $j++) {
            $header[$j] = $checksum_data[$i];
          } 
          return $header;
    }
  }
  $tar = & new Tar_Archive("$tar_file");
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript">
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  }
  // -->
</script>
</head>
<body onLoad="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
<?php if (ENABLE_SSL_ADMIN != 'true') {  // display security warning about downloads if not SSL ?>
          <tr>
            <td class="main"><?php echo WARNING_NOT_SECURE_FOR_DOWNLOADS; ?></td>
            <td class="main" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
<?php } ?>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TITLE; echo $_SERVER['HTTP_HOST'];?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_FILE_DATE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_FILE_SIZE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>


              <tr>
                <td align="right" class="smallText" colspan="4">
                    <?php echo '<a href="' . zen_href_link(FILENAME_BACKUP_MYSITE, 'action=backup')  .'">' .
                                   zen_image_button('button_backup.gif', IMAGE_BACKUP) . '</a>'?>                       
                           
                </td>
              </tr>


            </table></td>

          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>