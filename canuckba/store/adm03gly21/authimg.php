<?php
//FileName:authimg.php
//Descr��ption:
//Creater:alvar
//Createtime:2006-5-4
//Lastmodtime:
session_start();
?>
<?php
//������֤��ͼƬ
Header("Content-type: image/PNG");
srand((double)microtime()*1000000);//����һ������������ֵ����ӣ��Է���������������ɵ�ʹ��
//session_start();//�����������session��
$_SESSION['authnum']="";
$im = imagecreate(55,20) or die("Cant's initialize new GD image stream!");  //�ƶ�ͼƬ������С
$red = ImageColorAllocate($im, 255,0,0); //�趨������ɫ
$white = ImageColorAllocate($im, 255,255,255);
$gray = ImageColorAllocate($im, 200,200,200);
//imagefill($im,0,0,$gray); //����������䷨���趨��0,0��
imagefill($im,0,0,$white);//ed
//�������ֺ���ĸ��ϵ���֤�뷽��
$ychar="2346789ABCDEFGHJKMNPQRTUVWXYZ";
for($i=0;$i<4;$i++){
  $randnum=rand(0, strlen($ychar)-1);
  $authnum.=$ychar[$randnum]."";//ed ����һ���ո�
}
//while(($authnum=rand()%100000)<10000); //�����������λ��
//����λ������֤�����ͼƬ
$_SESSION['authnum']=$authnum;
//int imagestring(resource image,int font,int x,int y,string s, int col)
imagestring($im, 5, 10, 3, $authnum, $red);
//��col��ɫ���ַ���s����image�������ͼ���x��y���괦��ͼ������Ͻ�Ϊ0,0����
//��� font �� 1��2��3��4 �� 5����ʹ����������

for($i=0;$i<400;$i++){ //����������� {
$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
// imagesetpixel($im, rand()%90 , rand()%30 , $randcolor);
imagesetpixel($im, rand()%90 , rand()%30 , $gray);
}
ImagePNG($im);
ImageDestroy($im);
?>