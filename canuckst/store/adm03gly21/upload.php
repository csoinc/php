<?PHP
require('includes/application_top.php');
//文件格式检查
function check_file_type($file){
$all_file_type=array("png","jpg","gif");//文件格式
$file_type=file_type($file);
if(in_array($file_type,$all_file_type))
return true;
else
return false;
}
//文件格式
function file_type($file) {
$file_type=explode(".",$file);
$file_type=strtolower($file_type[count($file_type)-1]);
return $file_type;
}
echo "<script>";
if(zen_not_null($_FILES['upload_image_front']['tmp_name'])) {
	if(!zen_not_null($_FILES)) {
	echo "parent.document.getElementById('upload_err_front').innerHTML = 'please choose image';";
	}else {
	$upload_dir='../images/design/';//上传目录
	$file_name=time().rand(1,100).".".file_type($_FILES['upload_image_front']['name']);
	$new_file_name=$upload_dir.$file_name;//新文件名
		if(check_file_type($_FILES['upload_image_front']['name'])) {
			if(is_uploaded_file($_FILES['upload_image_front']['tmp_name']) and move_uploaded_file($_FILES['upload_image_front']['tmp_name'],$new_file_name)) {//上传
			echo "parent.document.upload_front_image_from.reset();";//表单重置
			echo "parent.document.add_image.add_image_front_url.value='design/".$file_name."';";//ajax插入图片
			echo "parent.document.getElementById('upload_err_front').innerHTML = 'upload successful,".zen_image($new_file_name,'',50,50)."';";
			}else {
			echo "parent.document.getElementById('upload_err_front').innerHTML = 'upload image fail';";
			}
		}else {
		echo "parent.document.getElementById('upload_err_front').innerHTML = 'you can only upload image(jpg jpeg gif png)';";
		}
	}
}elseif(zen_not_null($_FILES['upload_image_back']['tmp_name'])) {
	if(!zen_not_null($_FILES)) {
	echo "parent.document.getElementById('upload_err_back').innerHTML = 'please choose image';";
	}else {
	$upload_dir='../images/design/';//上传目录
	$file_name=time().rand(1,100).".".file_type($_FILES['upload_image_back']['name']);
	$new_file_name=$upload_dir.$file_name;//新文件名
		if(check_file_type($_FILES['upload_image_back']['name'])) {
			if(is_uploaded_file($_FILES['upload_image_back']['tmp_name']) and move_uploaded_file($_FILES['upload_image_back']['tmp_name'],$new_file_name)) {//上传
			echo "parent.document.upload_back_image_from.reset();";//表单重置
			echo "parent.document.add_image.add_image_back_url.value='design/".$file_name."';";//ajax插入图片
			echo "parent.document.getElementById('upload_err_back').innerHTML = 'upload successful,".zen_image($new_file_name,'',50,50)."';";
			}else {
			echo "parent.document.getElementById('upload_err_back').innerHTML = 'upload image fail';";
			}
		}else {
		echo "parent.document.getElementById('upload_err_back').innerHTML = 'you can only upload image(jpg jpeg gif png)';";
		}
	}
}elseif(zen_not_null($_FILES['upload_logo']['tmp_name'])) {
	if(!zen_not_null($_FILES)) {
	echo "parent.document.getElementById('upload_logo_err').innerHTML = 'please choose image';";
	}else {
	$upload_dir='../images/design/';//上传目录
	$file_name=time().rand(1,100).".".file_type($_FILES['upload_logo']['name']);
	$new_file_name=$upload_dir.$file_name;//新文件名
		if(check_file_type($_FILES['upload_logo']['name'])) {
			if(is_uploaded_file($_FILES['upload_logo']['tmp_name']) and move_uploaded_file($_FILES['upload_logo']['tmp_name'],$new_file_name)) {//上传
			echo "parent.document.upload_logo_image_form.reset();";//表单重置
			echo "parent.document.upload_logo_form.upload_logo_url.value='design/".$file_name."';";//ajax插入图片
			echo "parent.document.getElementById('upload_logo_err').innerHTML = 'upload successful,".zen_image($new_file_name,'',50,50)."';";
			}else {
			echo "parent.document.getElementById('upload_logo_err').innerHTML = 'upload image fail';";
			}
		}else {
		echo "parent.document.getElementById('upload_logo_err').innerHTML = 'you can only upload image(jpg jpeg gif png)';";
		}
	}
}else {
echo "alert('what');";
}



echo "</script>";




?>