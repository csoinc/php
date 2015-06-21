#!/usr/bin/php 
#convert -colorspace rgb simple.pdf[0] -density 100 -sample 200x200 sample.jpg 
#convert -colorspace rgb -units PixelsPerInch -density 150x150 sample.pdf sample.jpg 
#convert sample.gif sample.jpg


<?php
define ("LOGOS_SOURCE_DIR", "/home/oyou/logos");
define ("PHOTOS_SOURCE_DIR", "/home/oyou/photoad");

define ("LOGOS_DEST_DIR", "/home/oyou/logosnew");
define ("PHOTOS_DEST_DIR", "/home/oyou/photoadnew");


$log_file = 'logos_gif_to_jpg_log.csv';

$logh = fopen($log_file, 'w') or exit("Unable to open log file!");
fwrite($logh, "logos_gif_to_jpg - ERROR\n");


function list_dir( $source_path = '.', $dest_path = '.', $log_h, $level = 0 ){

  $ignore = array( 'cgi-bin', '.', '..' );

  $dh = @opendir( $source_path );
   
  while( false !== ( $file = readdir( $dh ) ) ){
    if( !in_array( $file, $ignore ) ){
      $spaces = str_repeat( ' ', ( $level * 4 ) );
      if( is_dir( "$source_path/$file" ) ){
         
        //echo "Path $spaces $file \n";
        list_dir( "$source_path/$file", $dest_path, $log_h, ($level+1) );
         
      } else {
         
        //echo "$spaces $file \n";
        $index = strrpos($file, '.');
    
        $name = substr($file, 0, $index);
        $ext = substr($file, $index+1);
        echo "File $file\n";
        echo "Name $name\n";
        echo "Ext $ext\n";
        
        //$words = preg_split('/\./', $file, 3, PREG_SPLIT_NO_EMPTY);
        //echo print_r($words)."\n";
        
        //$name = $words[0];
        //$ext = $words[1];
        //$dest_file = $dest_path.'/'.strtolower($name).'.jpg'; //change filename to lower
        $dest_file = $dest_path.'/'.$name.'.jpg';
        
        if (strtolower($ext) !== 'jpg' && strtolower($ext) !== 'jpeg') {
          echo "Convert file $file to $name.jpg\n";
          $output = shell_exec("convert $source_path/$file $dest_file");
          if (isset($output)) fwrite($log_h, $output."\n");
        } else {
          echo "Copy file $file to $dest_path\n";
          //$output = shell_exec("cp $source_path/$file $dest_file");  //change filename to lower

          //turn on/off copy part bellow
          $output = shell_exec("cp $source_path/$file $dest_path/");
          if (isset($output)) fwrite($log_h, $output."\n");
          
        }
        
      }
       
    }
     
  }
   
  closedir( $dh );

}

list_dir(LOGOS_SOURCE_DIR, LOGOS_DEST_DIR, $logh);

fclose($logh);

?>
