<?php
/* 
	Functions added  for osc8837 template
	Author: Yan Huang
	Email:  stevenh76@gmail.com
*/

//
// split combined taxes into separated recorda
// the combined tax title have to be defined as following format: [rate1] [title1] + [rate2] [title2] + [][]....
// for example, the title like "5% GST #00000 + 8.5% PST(Ontario):", and the value is $13.50
// then the result will be
//		5% GST #00000: $5.00
//		8.5% PST(Ontario): $8.50
// for other non-tax items, simply return them in the array
function splitTax($title, $value)
{
//  print $title . ' ' . $value;
  if (strpos($title, '+')>0) {
  
  	$splitLineTitle = array();
	$splitLineTitle = explode('+', $title);
	
	$spliteLineValue = array();
	
	$TotalTax = 0;
	$n = sizeof($splitLineTitle);
    for ( $i = 0; $i < $n; $i++) {
		$t = trim($splitLineTitle[$i]);
    	if (substr_count($t,'%')<>1) 
		{
			$splitLineValue[$i] = 0;
		}
		else {				
			$t = substr($t, 0, strpos($t, '%'));
			$spliteLineValue[$i]= $t;
			$TotalTax +=$t;
		}	
	}
	
    for ( $i = 0; $i < $n; $i++) {
		$spliteLineValue[$i]= $value * $spliteLineValue[$i] / $TotalTax ;
//		print $spliteLineValue[$i] . 'b+++';
	}
	$splitTax = array_combine($splitLineTitle, $spliteLineValue);	
  }
  else {
	$splitTax = array($title => $value);
  }		
//print $splitLineTitle[0] . ' ' . $splitLineValue[0];
  return $splitTax;
}
  
// improved substr to limit the length of a string
function substrEx($str, $len) {
    return (strlen($str) <= $len) ? $str : (substr($str, 0, $len-1) . '&hellip;'); 
}
  
//get medium sized image name using base name
function getImageMedium($products_image) { 
  $products_image_extension = substr($products_image, strrpos($products_image, '.'));
  $products_image_base = ereg_replace($products_image_extension, '', $products_image);
  $products_image_medium = $products_image_base . IMAGE_SUFFIX_MEDIUM . $products_image_extension;

  // check for a medium image else use small
  if (!file_exists(DIR_WS_IMAGES . 'medium/' . $products_image_medium)) {
    $products_image_medium = DIR_WS_IMAGES . $products_image;
  } else {
    $products_image_medium = DIR_WS_IMAGES . 'medium/' . $products_image_medium;
  }
  return $products_image_medium;
}

//get large sized image name using base name
function getImageLarge($products_image) { 
  $products_image_extension = substr($products_image, strrpos($products_image, '.'));
  $products_image_base = ereg_replace($products_image_extension, '', $products_image);
  $products_image_medium = $products_image_base . IMAGE_SUFFIX_MEDIUM . $products_image_extension;
  $products_image_large = $products_image_base . IMAGE_SUFFIX_LARGE . $products_image_extension;

  // check for a large image else use medium else use small
  // check for a medium image else use small
  if (file_exists(DIR_WS_IMAGES . 'large/' . $products_image_large)) {
    $products_image_large = DIR_WS_IMAGES . 'large/' . $products_image_large;
  } elseif (file_exists(DIR_WS_IMAGES . 'medium/' . $products_image_medium)) {
    $products_image_large = DIR_WS_IMAGES . 'medium/' . $products_image_medium;
  } else {
    $products_image_large = DIR_WS_IMAGES . $products_image;
  }
  return $products_image_large;
}

/*
 * The HTML image wrapper function for main product image
 */
  function zen_image_with_id($src, $img_id, $alt = '', $width = '', $height = '', $parameters = '') {
    global $template_dir;

    // soft clean the alt tag
    $alt = zen_clean_html($alt);

    // use old method on template images
    if (strstr($src, 'includes/templates') or strstr($src, 'includes/languages') or PROPORTIONAL_IMAGES_STATUS == '0') {
      return zen_image_OLD($src, $alt, $width, $height, $parameters);
    }

//auto replace with defined missing image
    if ($src == DIR_WS_IMAGES and PRODUCTS_IMAGE_NO_IMAGE_STATUS == '1') {
      $src = DIR_WS_IMAGES . PRODUCTS_IMAGE_NO_IMAGE;
    }

    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

    // if not in current template switch to template_default
    if (!file_exists($src)) {
      $src = str_replace(DIR_WS_TEMPLATES . $template_dir, DIR_WS_TEMPLATES . 'template_default', $src);
    }

    // hook for handle_image() function such as Image Handler etc
    if (function_exists('handle_image')) {
      $newimg = handle_image($src, $alt, $width, $height, $parameters);
      list($src, $alt, $width, $height, $parameters) = $newimg; 
    }

    // Convert width/height to int for proper validation.
    // intval() used to support compatibility with plugins like image-handler
    $width = empty($width) ? $width : intval($width);
    $height = empty($height) ? $height : intval($height);

// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
    $image = '<img id="' . $img_id . '" src="' . zen_output_string($src) . '" alt="' . zen_output_string($alt) . '"';

    if (zen_not_null($alt)) {
      $image .= ' title=" ' . zen_output_string($alt) . ' "';
    }

    if ( ((CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height))) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && zen_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = $image_size[0] * $ratio;
        } elseif (zen_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = $image_size[1] * $ratio;
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }

/*
    if (zen_not_null($width) && zen_not_null($height) and file_exists($src)) {
//      $image .= ' width="' . zen_output_string($width) . '" height="' . zen_output_string($height) . '"';
// proportional images
      $image_size = @getimagesize($src);
      // fix division by zero error
      $ratio = ($image_size[0] != 0 ? $width / $image_size[0] : 1);
      if ($image_size[1]*$ratio > $height) {
        $ratio = $height / $image_size[1];
        $width = $image_size[0] * $ratio;
      } else {
        $height = $image_size[1] * $ratio;
      }
// only use proportional image when image is larger than proportional size
      if ($image_size[0] < $width and $image_size[1] < $height) {
        $image .= ' width="' . $image_size[0] . '" height="' . intval($image_size[1]) . '"';
      } else {
        $image .= ' width="' . round($width) . '" height="' . round($height) . '"';
      }
    } else {
       // override on missing image to allow for proportional and required/not required
      if (IMAGE_REQUIRED == 'false') {
        return false;
      } else {
        $image .= ' width="' . intval(SMALL_IMAGE_WIDTH) . '" height="' . intval(SMALL_IMAGE_HEIGHT) . '"';
      }
    }
*/
    // inject rollover class if one is defined. NOTE: This could end up with 2 "class" elements if $parameters contains "class" already.
    if (defined('IMAGE_ROLLOVER_CLASS') && IMAGE_ROLLOVER_CLASS != '') {
    	$parameters .= (zen_not_null($parameters) ? ' ' : '') . 'class="rollover"';
    }
    // add $parameters to the tag output
    if (zen_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= ' />';

    return $image;
  }

/*
 * The HTML image wrapper function for main product attribute image which can triger the main product image change when mouse over
 */
  function zen_image_for_attrib($src, $src_mouseover = '', $alt = '', $width = '', $height = '', $parameters = '') {
    global $template_dir;

    // soft clean the alt tag
    $alt = zen_clean_html($alt);

    // use old method on template images
    if (strstr($src, 'includes/templates') or strstr($src, 'includes/languages') or PROPORTIONAL_IMAGES_STATUS == '0') {
      return zen_image_OLD($src, $alt, $width, $height, $parameters);
    }

//auto replace with defined missing image
    if ($src == DIR_WS_IMAGES and PRODUCTS_IMAGE_NO_IMAGE_STATUS == '1') {
      $src = DIR_WS_IMAGES . PRODUCTS_IMAGE_NO_IMAGE;
    }

    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

    // if not in current template switch to template_default
    if (!file_exists($src)) {
      $src = str_replace(DIR_WS_TEMPLATES . $template_dir, DIR_WS_TEMPLATES . 'template_default', $src);
    }

    // hook for handle_image() function such as Image Handler etc
    if (function_exists('handle_image')) {
      $newimg = handle_image($src, $alt, $width, $height, $parameters);
      list($src, $alt, $width, $height, $parameters) = $newimg; 
    }

    // Convert width/height to int for proper validation.
    // intval() used to support compatibility with plugins like image-handler
    $width = empty($width) ? $width : intval($width);
    $height = empty($height) ? $height : intval($height);

// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
    $image = '<img src="' . zen_output_string($src) . '" alt="' . zen_output_string($alt) . '"';

    if (zen_not_null($alt . $src_mouseover)) {
      $image .= ' title=" ' . zen_output_string($alt);
	  if (zen_not_null($src_mouseover)) {
	  	$image .= ' Click to select and enlarge the attribute image.';
	  }	  
	  $image .= ' "';
    }

    if ( ((CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height))) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && zen_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = $image_size[0] * $ratio;
        } elseif (zen_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = $image_size[1] * $ratio;
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }


    if (zen_not_null($width) && zen_not_null($height) and file_exists($src)) {
//      $image .= ' width="' . zen_output_string($width) . '" height="' . zen_output_string($height) . '"';
// proportional images
      $image_size = @getimagesize($src);
      // fix division by zero error
      $ratio = ($image_size[0] != 0 ? $width / $image_size[0] : 1);
      if ($image_size[1]*$ratio > $height) {
        $ratio = $height / $image_size[1];
        $width = $image_size[0] * $ratio;
      } else {
        $height = $image_size[1] * $ratio;
      }
// only use proportional image when image is larger than proportional size
      if ($image_size[0] < $width and $image_size[1] < $height) {
        $image .= ' width="' . $image_size[0] . '" height="' . intval($image_size[1]) . '"';
      } else {
        $image .= ' width="' . round($width) . '" height="' . round($height) . '"';
      }
    } else {
       // override on missing image to allow for proportional and required/not required
      if (IMAGE_REQUIRED == 'false') {
        return false;
      } else {
        $image .= ' width="' . intval(SMALL_IMAGE_WIDTH) . '" height="' . intval(SMALL_IMAGE_HEIGHT) . '"';
      }
    }

    // inject rollover class if one is defined. NOTE: This could end up with 2 "class" elements if $parameters contains "class" already.
    if (defined('IMAGE_ROLLOVER_CLASS') && IMAGE_ROLLOVER_CLASS != '') {
    	$parameters .= (zen_not_null($parameters) ? ' ' : '') . 'class="rollover"';
    }
    // add $parameters to the tag output
    if (zen_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= ' />';

    return $image;
  }


?>