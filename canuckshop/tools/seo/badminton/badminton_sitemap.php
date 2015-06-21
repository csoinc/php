#!/usr/bin/php

<?php
set_time_limit(0); // no time limit
ini_set("display_errors", "1");
error_reporting(E_ALL);

$dbhost = 'localhost';
$dbuser = 'canuckst_store';
$dbpass = 'ymqpq1!';

$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
$dbname = 'canuckst_badminton';
mysql_select_db($dbname);

function get_categories_path($categories_id, $cPath) {
  
  $query = "select parent_id from categories where categories_id = ".$categories_id." LIMIT 1";
  if (!($result = mysql_query($query)))
  {
    exit(mysql_error());
  }
  if ($row = mysql_fetch_array($result, MYSQL_ASSOC))
  {
    $parent_id = strval($row['parent_id']);
    if ($parent_id != 0) {
      $cPath = $parent_id.'_'.$cPath;
      $cPath = get_categories_path($parent_id, $cPath);
    } 
  }   
  return $cPath;  
}

function get_categories_id($products_id) {

  $query = "select categories_id from products_to_categories where products_id = ".$products_id." LIMIT 1";
  if (!($result = mysql_query($query)))
  {
    exit(mysql_error());
  }
  if ($row = mysql_fetch_array($result, MYSQL_ASSOC))
  {
    $categories_id = strval($row['categories_id']);
    return $categories_id;
  }
  return FALSE;
}

function get_field($v)
{
	if (strpos($v, "\t") !== false)
	{
		return '"'.str_replace('"', "'", $v).'"';
	}
	return $v;
}

$file = '/home/canuckba/public_html/sitemap1.xml';
//$date = $argv[1];
$date = date('Y-m-d');

if ($file)
{
	echo $file."\n";

	$fh = fopen($file, 'w') or exit("Unable to open file!");
	fwrite($fh, "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n");
	fwrite($fh, "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n");
	fwrite($fh, "  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n");
	fwrite($fh, "  xsi:schemaLocation=\"http://www.google.com/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n");
	
	echo "write the main urls data.";
	fwrite($fh, "<url>\n");
	fwrite($fh, "  <loc>http://www.canuckbadminton.com/</loc>\n");
	fwrite($fh, "  <lastmod>{$date}</lastmod>\n");
	fwrite($fh, "  <changefreq>weekly</changefreq>\n");
	fwrite($fh, "  <priority>1.00</priority>\n");
	fwrite($fh, "</url>\n");
	fwrite($fh, "<url>\n");
	fwrite($fh, "  <loc>http://www.canuckbadminton.com/store/</loc>\n");
	fwrite($fh, "  <lastmod>{$date}</lastmod>\n");
	fwrite($fh, "  <changefreq>weekly</changefreq>\n");
	fwrite($fh, "  <priority>1.00</priority>\n");
	fwrite($fh, "</url>\n");
	
	fwrite($fh, "<url>\n");
	fwrite($fh, "  <loc>http://www.canuckbadminton.com/store/index.php?main_page=page&amp;id=15&amp;chapter=30</loc>\n");
	fwrite($fh, "  <lastmod>{$date}</lastmod>\n");
	fwrite($fh, "  <changefreq>weekly</changefreq>\n");
	fwrite($fh, "  <priority>0.50</priority>\n");
	fwrite($fh, "</url>\n");
	
	fwrite($fh, "<url>\n");
	fwrite($fh, "  <loc>http://www.canuckbadminton.com/store/index.php?main_page=page&amp;id=39</loc>\n");
	fwrite($fh, "  <lastmod>{$date}</lastmod>\n");
	fwrite($fh, "  <changefreq>weekly</changefreq>\n");
	fwrite($fh, "  <priority>0.50</priority>\n");
	fwrite($fh, "</url>\n");
	
	fwrite($fh, "<url>\n");
	fwrite($fh, "  <loc>http://www.canuckbadminton.com/store/index.php?main_page=contact_us</loc>\n");
	fwrite($fh, "  <lastmod>{$date}</lastmod>\n");
	fwrite($fh, "  <changefreq>weekly</changefreq>\n");
	fwrite($fh, "  <priority>0.50</priority>\n");
	fwrite($fh, "</url>\n");
	
	$query = "select * from categories c inner join categories_description d on c.categories_id = d.categories_id where c.categories_status = true and c.parent_id = 0";
	echo "write the categories data.";
	if (!($result = mysql_query($query)))
	{
		exit(mysql_error());
	}
	
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		echo ".";
		$id = strval($row['categories_id']);
	
		$name = utf8_encode($row['categories_name']);
		$name = str_replace(' ', '-', $name);
		$name = str_replace('&', '&amp;', $name);
	
		fwrite($fh, "<url>\n");
		fwrite($fh, "<loc>http://www.canuckbadminton.com/store/index.php?main_page=index&amp;cPath={$id}</loc>\n");
		fwrite($fh, "<lastmod>{$date}</lastmod>\n");
		fwrite($fh, "<changefreq>weekly</changefreq>\n");
		fwrite($fh, "<priority>1.00</priority>\n");
		fwrite($fh, "</url>\n");
	}
	
	$query = "select c1.categories_id cp1,c2.categories_id cp2,d.categories_name cpname from categories c1 "
	    . " left join categories c2 on c1.categories_id = c2.parent_id "
		. " inner join categories_description d on c2.categories_id = d.categories_id "
		. " where c1.categories_status = true and c1.parent_id = 0 and c2.categories_status = true "
		. " order by c1.categories_id,c2.categories_id";
	
	echo "write the sub-categories data.";
	if (!($result = mysql_query($query)))
	{
		exit(mysql_error());
	}
	
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		echo ".";
		$cp1 = strval($row['cp1']);
		$cp2 = strval($row['cp2']);
		
		$name = utf8_encode($row['cpname']);
		$name = str_replace(' ', '-', $name);
		$name = str_replace('&', '&amp;', $name);
	
		fwrite($fh, "<url>\n");
		fwrite($fh, "<loc>http://www.canuckbadminton.com/store/index.php?main_page=index&amp;cPath={$cp1}_{$cp2}</loc>\n");
		fwrite($fh, "<lastmod>{$date}</lastmod>\n");
		fwrite($fh, "<changefreq>weekly</changefreq>\n");
		fwrite($fh, "<priority>0.80</priority>\n");
		fwrite($fh, "</url>\n");
	}

	$query = "select c1.categories_id cp1,c2.categories_id cp2,c3.categories_id cp3,d.categories_name cpname from categories c1 "
	 	. " left join categories c2 on c1.categories_id = c2.parent_id left join categories c3 on c2.categories_id = c3.parent_id "
	 	. " inner join categories_description d on c3.categories_id = d.categories_id "
	 	. " where c1.categories_status = true and c1.parent_id = 0 and c2.categories_status = true and c3.categories_status = true "
	 	. " order by c1.categories_id,c2.categories_id,c3.categories_id"; 
	
	echo "write the sub-categories data.";
	if (!($result = mysql_query($query)))
	{
		exit(mysql_error());
	}
	
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		echo ".";
		$cp1 = strval($row['cp1']);
		$cp2 = strval($row['cp2']);
		$cp3 = strval($row['cp3']);
		
		$name = utf8_encode($row['cpname']);
		$name = str_replace(' ', '-', $name);
		$name = str_replace('&', '&amp;', $name);
	
		fwrite($fh, "<url>\n");
		fwrite($fh, "<loc>http://www.canuckbadminton.com/store/index.php?main_page=index&amp;cPath={$cp1}_{$cp2}_{$cp3}</loc>\n");
		fwrite($fh, "<lastmod>{$date}</lastmod>\n");
		fwrite($fh, "<changefreq>weekly</changefreq>\n");
		fwrite($fh, "<priority>0.80</priority>\n");
		fwrite($fh, "</url>\n");
	}
		
	$query = "select p.products_id,p.products_model,p.products_status,d.products_name from products p inner join products_description d on p.products_id = d.products_id where p.products_status = true order by p.products_id asc";
	echo "write the products data.";
	
	if (!($result = mysql_query($query)))
	{
		exit(mysql_error());
	}
	
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		echo ".";
		$id = strval($row['products_id']);

		$categories_id = get_categories_id($id);
	    $cats_path = get_categories_path($categories_id, $categories_id);
		
		$name = utf8_encode($row['products_name']);
		$name = str_replace(' ', '-', $name);
		$name = str_replace('&', '&amp;', $name);
		
		fwrite($fh, "<url>\n");
		fwrite($fh, "<loc>http://www.canuckbadminton.com/store/index.php?main_page=product_info&amp;cPath={$cats_path}&amp;products_id={$id}</loc>\n");
		fwrite($fh, "<lastmod>{$date}</lastmod>\n");
		fwrite($fh, "<changefreq>weekly</changefreq>\n");
		fwrite($fh, "<priority>0.50</priority>\n");
		fwrite($fh, "</url>\n");
	}

	fwrite($fh, "</urlset>\n");
	
	fclose($fh);
	mysql_close();
}
else
{
	echo "enter the name of the file to write the store data to.\n";
}
?>
