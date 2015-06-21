function onNodeSelectProd_Cat_tree(nodeId)
{ 
 
  if (nodeId.substring(0,3) == 'pid')
  {
        ///document.getElementById('prod_cat_tree_tree').inn";

       //Get Product Copy Input
      xmlHttp = GetXmlHttpObject();
      if (xmlHttp==null)
      {
  	alert("Your browser does not support AJAX!");
  	return;
      }
      
      xmlHttp.onreadystatechange=stateChangedCopyProduct;
      
      
      
     
     var product_id_array_full = nodeId.split("_"); // Get 'cid' + category ID
      var product_id_array_number = product_id_array_full[0].split("d");
      var product_id = product_id_array_number[1]; // Get category ID
    
      var current_url = document.location.href; // current full URL
      var current_url_array = current_url.split("/"); // Split URL by "/" into array
      var size_url_array = current_url_array.length; // Get array length
      var current_page_with_get = current_url_array[size_url_array-1]; // Get current page/file, this will include any GET variables
      var current_page_array = current_page_with_get.split("?"); // To remove any GET variables at end of page/file name
      var current_page = current_page_array[0]; // Current page/file name
         
     
      
     
     if (current_page == "qty_time_stats.php") {
        
        document.date_range_form.ajax_pid.value=product_id;
     }
   
      xmlHttp.send(null);

  }
  
  /***** bof Added for Category Tree - PB *****/
   if (nodeId.substring(0,3) == 'cid')
  {
  
   xmlHttp = GetXmlHttpObject();
      if (xmlHttp==null){
  	alert("Your browser does not support AJAX!");
  	return;
      }
      
      
       xmlHttp.onreadystatechange=stateChangedCopyProduct;
       
       
     var category_id_array_full = nodeId.split("_"); // Get 'cid' + category ID
      var category_id_array_number = category_id_array_full[0].split("d");
      var category_id = category_id_array_number[1]; // Get category ID
    
      var current_url = document.location.href; // current full URL
      var current_url_array = current_url.split("/"); // Split URL by "/" into array
      var size_url_array = current_url_array.length; // Get array length
      var current_page_with_get = current_url_array[size_url_array-1]; // Get current page/file, this will include any GET variables
      var current_page_array = current_page_with_get.split("?"); // To remove any GET variables at end of page/file name
      var current_page = current_page_array[0]; // Current page/file name
    
     // bof Categories/Products Jump
     if (current_page == "qty_time_stats.php") {
      
        document.date_range_form.ajax_cid.value=category_id;
      
     }
     
  }
  /***** eof Added for Category Tree - PB *****/
  
}




function stateChangedCopyProduct()
{
 if (xmlHttp.readyState==4)
 {
  document.getElementById('prod_cat_tree_tree').innerHTML=xmlHttp.responseText;
 }
} //statechanged()

function GetXmlHttpObject()
{
var xmlHttp=null;
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
return xmlHttp;
}
