<?php
echo $xajax->printJavascript(DIR_WS_INCLUDES);
?>
<script>
var member_size='<?php echo ereg_replace("\n","",zen_draw_pull_down_menu("member_size[]",$member_size));?>';
xajax.loadingFunction=function(){ display('xajax_loading','block')};
xajax.doneLoadingFunction =function(){ display('xajax_loading','none')};
var color_tmp='';
color_tmp=color_tmp+'<tr height=12>';
<?php
$choose_color=$db->Execute("select * from ".DESIGN_COLOR);
while(!$choose_color->EOF) {
?>
color_tmp=color_tmp+'<td width=17 style="background-color:<?php echo $choose_color->fields["design_color_value"];?>" title="<?php echo $choose_color->fields["design_color_value"];?>"></td>';
<?php
if(($choose_color->cursor+1)%6==0)
echo "color_tmp=color_tmp+'</tr><tr height=12>';\n";
$choose_color->MoveNext();
}
if($choose_color->RecordCount()>0 and $choose_color->RecordCount()%6!=0) {
	for($i=0;$i<(6-$choose_color->RecordCount()%6);$i++) {
?>
color_tmp=color_tmp+'<td width=17>&nbsp;X&nbsp;</td>';
<?php
	}
}
?>
color_tmp=color_tmp+'</tr>';
//alert(color_tmp);
</script>