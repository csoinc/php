#!/usr/bin/php

<?php
/**
 * @author Owen Ou
 * @version 1.0
 */

class Promotion
{

    function Promotion() {
      
    }
    
	function get_promotion_price($price)
	{
	    if (!isset($price)) return 0;
		$price = trim($price);
		$promotion_price = $price;
		if (preg_match("/.99/", $price)) {
		  $promotion_price = $price * 0.80;
		} else if (preg_match("/.98/", $price)) {
		  $promotion_price = $price * 0.85;
		} else if (preg_match("/.97/", $price)) {
		  $promotion_price = $price * 0.90;
		} else if (preg_match("/.96/", $price)) {
		  $promotion_price = $price * 0.95;
		}
		return number_format($promotion_price, 2, '.', '');
	}
}

$price = 129.99;

$promotion_price = Promotion::get_promotion_price($price);

echo 'Price: ' . $price . " ";
echo 'Promotion price: ' . $promotion_price . "\n";

$price = 129.98;

$promotion_price = Promotion::get_promotion_price($price);

echo 'Price: ' . $price . " ";
echo 'Promotion price: ' . $promotion_price . "\n";

$price = 129.97;

$promotion_price = Promotion::get_promotion_price($price);

echo 'Price: ' . $price . " ";
echo 'Promotion price: ' . $promotion_price . "\n";

$price = 129.96;

$promotion_price = Promotion::get_promotion_price($price);

echo 'Price: ' . $price . " ";
echo 'Promotion price: ' . $promotion_price . "\n";


?>