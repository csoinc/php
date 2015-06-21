<?php   
require('includes/application_top.php');
global $db;

header("Content-type:text/xml"); print("<?xml version=\"1.0\"?>");

 if (isset($_GET['id']))
 {
    $treeparent = $_GET['id'];
 }else
 { $treeparent = 0;}

print('<tree id="' . $treeparent . '">');
if (!isset($_GET['id']))
{
//print('<item text="Catalog" id="CAT0" im0="folderClosed.gif" im1="folderOpen.gif" child="1"  open="1">');
print('<item text="Catalog" id="cid0" im0="folderClosed.gif" im1="folderOpen.gif" child="1"  open="1">');
}
if (!isset($_GET['parent']))
{ $parent = 0; }else { $parent = $_GET['parent']; }

if (!isset($_GET['includeProducts']))
{ $includeProducts = 0; }else { $includeProducts = $_GET['includeProducts']; }

if (!isset($_GET['maxdepth']))
{ $maxDepth = 0; }else { $maxDepth = $_GET['maxdepth']; }

$cattable = null;
 if ($maxDepth ==0)
 {
  $catQuery=$db->execute("Select cat.categories_id, cat.parent_id, catdesc.categories_name FROM " . TABLE_CATEGORIES . " cat, " . TABLE_CATEGORIES_DESCRIPTION . " catdesc WHERE cat.categories_id = catdesc.categories_id and catdesc.language_id = " . $_SESSION['languages_id'] );
   
   while (!$catQuery->EOF)
   {
       $cattable[$catQuery->fields['categories_id']] = Array(
         "parent_id" => $catQuery->fields['parent_id'],
         "categories_name" => $catQuery->fields['categories_name']
       );
     $catQuery->MoveNext();
   }
 
  }

  xajaxtree_build_cat_prod($parent,0,$includeProducts,$maxDepth,$cattable); 
 
  if (!isset($_GET['id']))
  {
    print('</item>');
  }
print('</tree>');
?>
