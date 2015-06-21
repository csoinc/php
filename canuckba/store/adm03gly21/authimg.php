<?php
//FileName:authimg.php
//Descrīption:
//Creater:alvar
//Createtime:2006-5-4
//Lastmodtime:
session_start();
?>
<?php
//生成验证码图片
Header("Content-type: image/PNG");
srand((double)microtime()*1000000);//播下一个生成随机数字的种子，以方便下面随机数生成的使用
//session_start();//将随机数存入session中
$_SESSION['authnum']="";
$im = imagecreate(55,20) or die("Cant's initialize new GD image stream!");  //制定图片背景大小
$red = ImageColorAllocate($im, 255,0,0); //设定三种颜色
$white = ImageColorAllocate($im, 255,255,255);
$gray = ImageColorAllocate($im, 200,200,200);
//imagefill($im,0,0,$gray); //采用区域填充法，设定（0,0）
imagefill($im,0,0,$white);//ed
//生成数字和字母混合的验证码方法
$ychar="2346789ABCDEFGHJKMNPQRTUVWXYZ";
for($i=0;$i<4;$i++){
  $randnum=rand(0, strlen($ychar)-1);
  $authnum.=$ychar[$randnum]."";//ed 加入一个空格
}
//while(($authnum=rand()%100000)<10000); //生成随机的四位数
//将四位整数验证码绘入图片
$_SESSION['authnum']=$authnum;
//int imagestring(resource image,int font,int x,int y,string s, int col)
imagestring($im, 5, 10, 3, $authnum, $red);
//用col颜色将字符串s画到image所代表的图像的x，y座标处（图像的左上角为0,0）。
//如果 font 是 1，2，3，4 或 5，则使用内置字体

for($i=0;$i<400;$i++){ //加入干扰象素 {
$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
// imagesetpixel($im, rand()%90 , rand()%30 , $randcolor);
imagesetpixel($im, rand()%90 , rand()%30 , $gray);
}
ImagePNG($im);
ImageDestroy($im);
?>