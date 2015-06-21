#!/usr/bin/php

<?php
define ("PRINTAD_DIR", "/home/Canpages/OYOU/printad");
define ("ADSERVER_DIR", "/home/workflow/AdServer/OPEN");

$log_file = 'printad_pdf_log.csv';

$logh = fopen($log_file, 'w') or exit("Unable to open log file!");
fwrite($logh, "printad_pdf NO - SOURCE PDF\n");

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
      //echo $output;
      fwrite($logh, $output."\n");
    }
    if (file_exists($source)) {
      $dest_path = $dest.'/';
      //echo "cp $source $dest_path";
      $output = shell_exec("cp $source $dest_path");
      //echo $output;
      fwrite($logh, $output."\n");
      
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
