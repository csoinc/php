<?php 
function xajaxtree_build_cat_prod($parent,$depth,$includeProducts,$maxdepth,$cattable)
{
 global $db;

 if (isset($_GET['id']) and $depth == 0)
 {
    $parent = substr($_GET['id'],3);
 }

  if ($maxdepth != 0)
  {
 $query = $db->Execute("Select cat.categories_id, categories_name from " . 
TABLE_CATEGORIES . " cat, " . TABLE_CATEGORIES_DESCRIPTION . " catdesc" . " WHERE cat.parent_id = " . $parent . " and catdesc.categories_id = cat.categories_id and catdesc.language_id = " . $_SESSION['languages_id'] . ' ORDER BY SORT_ORDER');
  
  while(!$query->EOF)
  {

  
   if ((ajaxtree_has_products($query->fields['categories_id']) == true and $includeProducts != 0)
      or ajaxtree_has_subcats($query->fields['categories_id']) == true) 
   { $child = 1;}
   else {$child = 0;}
 
    print('<item text="' . xmod_ajaxtree_zen_sanitize_string_xml($query->fields['categories_name']) . '" id="cid' . $query->fields['categories_id'] . '" im0="folderClosed.gif" im1="folderOpen.gif" child="' . $child . '"' . '>');
   if ($maxdepth > $depth or $maxdepth == 0)
   {
     xajaxtree_build_cat_prod($query->fields['categories_id'],$depth + 1,$includeProducts,$maxdepth,$cattable);
   }
      print('</item>');
   $query->MoveNext();
  }
}else
{
   if ($cattable != null)
  {
    foreach ($cattable as $key => $value)
    {
       if ($value['parent_id'] == $parent)
       {
           if ((ajaxtree_has_products($key) == true and $includeProducts != 0)
           or ajaxtree_has_subcats($key) == true) 
          { $child = 1;}
          else {$child = 0;}
        
         print('<item text="' . xmod_ajaxtree_zen_sanitize_string_xml($value['categories_name']) . '" id="cid' . $key . '" im0="folderClosed.gif" im1="folderOpen.gif" child="' . $child . '"' . '>');
   if ($maxdepth > $depth or $maxdepth == 0)
   {
     xajaxtree_build_cat_prod($key,$depth + 1,$includeProducts,$maxdepth,$cattable);
   }
      print('</item>');



        } //if parent_id       
    } //foreach
   } //if $cattable != null
} //else

 if ($includeProducts != 0)
   {
     $qryProducts = $db->Execute("SELECT ptc.products_id, products_name from " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc, " . TABLE_PRODUCTS_DESCRIPTION . " pd WHERE ptc.categories_id = " . $parent . " and pd.products_id = ptc.products_id");

   while(!$qryProducts->EOF)
  {
     print('<item text="'  . xmod_ajaxtree_zen_sanitize_string_xml($qryProducts->fields['products_name']) . '" id="pid' . $qryProducts->fields['products_id'] .'" />');
   $qryProducts->MoveNext();
  }
  }
 
}


  function xmod_ajaxtree_zen_sanitize_string_xml($string) {
   $string = stripslashes($string);
    $string = ereg_replace(' +', ' ', $string);
    $string=ereg_replace("'","&apos;", $string);
    $string=ereg_replace("\"","&quot;", $string);
    $string=ereg_replace("&","&amp;", $string);
    $string=ereg_replace("<","&lt;", $string);
    $string=ereg_replace(">","&gt;", $string);

    $string=ereg_replace("default.php","index.php",$string);
   // $string=ereg_replace("%","\%",$string);
  
    return $string;
  }


function ajaxtree_has_products($catid)
{
  global $db;

 $qryProd = $db->Execute('SELECT products_id from ' . TABLE_PRODUCTS_TO_CATEGORIES . ' WHERE categories_id = ' . $catid . ' LIMIT 1');

  if (!$qryProd->EOF)
  {
   return true;
  }

  return false; 
}

function ajaxtree_has_subcats($catid)
{
  global $db;

 $qryCats = $db->Execute('SELECT parent_id from ' . TABLE_CATEGORIES . ' WHERE parent_id = ' . $catid . '');

  if (!$qryCats->EOF)
  {
   return true;
  }

  return false; 
}
?>
