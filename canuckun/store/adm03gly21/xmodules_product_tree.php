<?php
/*!!FJM - Jim_Miller@ZenCartConsulting.com*/

?>
<link rel="stylesheet" type="text/css" href="includes/dhtmlXPTTree.css">
<script language="javascript" src="includes/dhtmlXPTCommon.js"></script>
<script language="javascript" src="includes/dhtmlXPTTree.js"></script>
<script language="javascript" src="includes/xmodules_product_tree.js"></script>
<script language="javascript" src="includes/product_tree_slider.js"></script>


<!-- <a onmousedown="toggleProductTreeDiv('ShowProductTree','HideProductTree');" href="javascript:;">Select Product</a> -->


<!-- <div style="display:none" id="ShowProductTree"> -->

<tr><td><table id="myMenu" class="nav" width="150"><tr><td>
<div id="prod_cat_tree_tree" class="prod_cat_tree"> </div>
<!-- <div id="prod_cat_tree_tree" style="width:400px;height:250px;overflow:auto;"></div> -->

<script>
  tree=new dhtmlXTreeObject("prod_cat_tree_tree","50%","50%",0);
  tree.setImagePath("pt_imgs/");
  tree.setOnClickHandler(onNodeSelectProd_Cat_tree);
  tree.setXMLAutoLoading("pt_productcategoriesxml.php?maxdepth=1&includeProducts=1");
  //tree.loadXML("productcategoriesxml.php?parent=0&includeProducts=1&maxdepth=1");
tree.loadXML("pt_productcategoriesxml.php?parent=0&includeProducts=1&maxdepth=1",function(){tree.openAllItems(1);});

  //tree.loadOpenStates();
  //tree.attachEvent("onOpenEnd",function(){
// tree.saveOpenStates();
//});

</script>
<br />
<div id="xmodules_detail_span"  class="xmodules_detail_span"></div>
</td></tr></table></td></tr>
<!-- </div> -->



<!-- <div style="display:block;" id="HideProductTree"></div> -->
