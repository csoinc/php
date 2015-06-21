#!/usr/bin/php

#convert -colorspace rgb simple.pdf[0] -density 100 -sample 200x200 sample.jpg

#convert -colorspace rgb -units PixelsPerInch -density 150x150 sample.pdf  sample.jpg

#convert sample.gif sample.jpg


<?php
define ("PRINTAD_DIR", "/home/Canpages/OYOU/printad");
define ("ADSERVER_DIR", "/home/workflow/AdServer/OPEN");

$log_file = 'displayad_log.txt';

$logh = fopen($log_file, 'w') or exit("Unable to open log file!");
fwrite($logh, "NO PDF FILE\n");

$handle = @fopen("displayad.csv", "r");
if ($handle) {
  while (($buffer = fgets($handle, 4096)) !== false) {
    //echo $buffer;
    $columns = preg_split('/,/', $buffer, 5, PREG_SPLIT_NO_EMPTY);
    //echo print_r($columns)."\n";

    if ($columns[2] == 'FILE_URL') continue;

    //echo $columns[2]."\n";

    $words = preg_split('/\//', $columns[2], 5, PREG_SPLIT_NO_EMPTY);

    $book = $words[3];
    //echo print_r($words)."\n";

    $chars = preg_split('/\.s/', $words[4], 3, PREG_SPLIT_NO_EMPTY);

    $file = $chars[0];

    //echo print_r($chars)."\n";

    $source = ADSERVER_DIR.'/'.$book.'/'.$file.'.PDF';
    $dest = PRINTAD_DIR.'/'.$book;

    if (!file_exists($dest)) {
      //echo "mkdir $dest";
      $output = shell_exec("mkdir $dest");
      echo $output;
    }
    if (file_exists($source)) {
      $dest_path = $dest.'/';
      //echo "cp $source $dest_path";
      $output = shell_exec("cp $source $dest_path");
      echo $output;
    } else {
      fwrite($logh, $columns[2]."\n");
    }

  }
  if (!feof($handle)) {
    echo "Error: unexpected fgets() fail\n";
  }
  fclose($handle);
}
fclose($logh);

?>
