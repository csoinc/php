#!/usr/bin/php

<?php


set_time_limit(0); // no time limit
ini_set("display_errors", "1");
error_reporting(E_ALL);


function getField($v)
{
  if (strpos($v, "\t") !== false)
  {
    return '"'.str_replace('"', "'", $v).'"';
  }
  return $v;
}

function load_users() {
  $dbhost = 'mysql.oyou.com';
  $dbuser = 'storeuniform';
  $dbpass = 'longxia1!';
  
  $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql uniform.');
  $dbname = 'storeuniform';
  mysql_select_db($dbname);
  
  
  $oconn=odbc_connect('inventory','canuckst_uniform','longxia1!') or die ('Error connecting to odbc inventory.');
  $osql="SELECT * FROM users";
  $ors=odbc_exec($oconn,$osql);

  while (odbc_fetch_row($ors))
  {
    $uid = strval(odbc_result($ors, 'uid'));
    $uname = mysql_real_escape_string(odbc_result($ors, 'uname'));
    $pwd = strval(odbc_result($ors, 'pwd'));
    $order = odbc_result($ors, 'order');
    $inventory = odbc_result($ors, 'inventory');
    $config = odbc_result($ors, 'config');
    $valid = odbc_result($ors, 'config');

    //echo 'uid:'.$uid."\t";
    //echo 'uname:'.$uname."\n";
    echo '.';
    $msql = "SELECT * FROM USERS WHERE UID = '".$uid."'";

    if (($result = mysql_query($msql))) {
      if (mysql_num_rows($result) == 1) {
        //update
        //echo 'mysql update users'."\n";
        $msql="UPDATE USERS SET USERID='".$uid."',PASSWORD='".$pwd."',ORDERS=".$order.",INVENTORY=".$inventory.",SYSTEMS=".$config.",USERNAME='".$uname."',STATUS=".$valid
        ." WHERE USERID = '".$uid."'";

        //echo $msql . "\n";
        if (!($result = mysql_query($msql))) {
          echo mysql_error();
        }
      } else {
        //insert
        //echo 'mysql insert users'."\n";
        $msql="INSERT INTO USERS (UID,PWD,UNIFORMORDER,INVENTORY,SYSCONFIG,UNAME,VALID) VALUES ('"
        .$uid."','".$pwd."',".$order.",".$inventory.",".$config.",'".$uname."',".$valid.")";

        //echo $msql . "\n";
        if (!($result = mysql_query($msql))) {
          echo mysql_error();
        }
      }
    } else {
      echo mysql_error();
    }
  }
  odbc_close($oconn);
  mysql_close($conn);
  
}

function load_clients() {
  $dbhost = 'mysql.oyou.com';
  $dbuser = 'storeuniform';
  $dbpass = 'longxia1!';

  $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql uniform.');
  $dbname = 'storeuniform';
  mysql_select_db($dbname);


  $oconn=odbc_connect('inventory','canuckst_uniform','longxia1!') or die ('Error connecting to odbc inventory.');
  $osql="SELECT * FROM clients";
  $ors=odbc_exec($oconn,$osql);

  while (odbc_fetch_row($ors))
  {
    $clientid = strval(odbc_result($ors, 'clientid'));
    $clientfrom = mysql_real_escape_string(odbc_result($ors, 'clientfrom'));
    $teamname = mysql_real_escape_string(odbc_result($ors, 'teamname'));
    $contactname = mysql_real_escape_string(odbc_result($ors, 'contactname'));
    $email = mysql_real_escape_string(odbc_result($ors, 'email'));
    $accountnum = mysql_real_escape_string(odbc_result($ors, 'accountnum'));
    $telephone = mysql_real_escape_string(odbc_result($ors, 'telephone'));
    $homephone = mysql_real_escape_string(odbc_result($ors, 'homephone'));
    $fax = mysql_real_escape_string(odbc_result($ors, 'fax'));
    $address = mysql_real_escape_string(odbc_result($ors, 'address'));
    $city = mysql_real_escape_string(odbc_result($ors, 'city'));
    $province = mysql_real_escape_string(odbc_result($ors, 'province'));
    $zipcode = mysql_real_escape_string(odbc_result($ors, 'zipcode'));
    $shippingaddr = mysql_real_escape_string(odbc_result($ors, 'shippingaddr'));
    $shippingzip = mysql_real_escape_string(odbc_result($ors, 'shippingzip'));
    
    //echo 'contactname:'.$contactname."\t";
    //echo 'telephone:'.$telephone."\n";
    echo '.';
    
    $msql = "SELECT * FROM CLIENTS WHERE CLIENTID = ".$clientid;

    if (($result = mysql_query($msql))) {
      if (mysql_num_rows($result) == 1) {
        //update
        //echo 'mysql update clients'."\n";
        $msql="UPDATE CLIENTS SET NAME='".$clientfrom."',TEAM='".$teamname."',CONTACT='".$contactname."',EMAIL='".$email."',ACCOUNT='".$accountnum
        ."',TELEPHONE='".$telephone."',CELLPHONE='".$homephone."',FAX='".$fax."',STREET='".$address."',CITY='".$city
        ."',PROVINCE='".$province."',ZIPCODE='".$zipcode."'"
        ." WHERE CLIENTID = ".$clientid;

        //echo $msql . "\n";
        if (!($result = mysql_query($msql))) {
          echo mysql_error();
        }
      } else {
        //insert
        //echo 'mysql insert clients'."\n";
        $msql="INSERT INTO CLIENTS (CLIENTID,NAME,TEAM,CONTACT,EMAIL,ACCOUNT,TELEPHONE,CELLPHONE,FAX,STREET,CITY,PROVINCE,ZIPCODE)"
        ." VALUES ('".$clientid."','".$clientfrom."','".$teamname."','".$contactname."','".$email."','".$accountnum."','".$telephone."','".$homephone."','".$fax
        ."','".$address."','".$city."','".$province."','".$zipcode."')";

        //echo $msql . "\n";
        if (!($result = mysql_query($msql))) {
          echo mysql_error();
        }
      }
    } else {
      echo mysql_error();
    }
  }
  odbc_close($oconn);
  mysql_close($conn);

}

function load_orders() {
  $dbhost = 'mysql.oyou.com';
  $dbuser = 'storeuniform';
  $dbpass = 'longxia1!';

  $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql uniform.');
  $dbname = 'storeuniform';
  mysql_select_db($dbname);


  $oconn=odbc_connect('inventory','canuckst_uniform','longxia1!') or die ('Error connecting to odbc inventory.');
  $osql="SELECT * FROM orders";
  $ors=odbc_exec($oconn,$osql);

  while (odbc_fetch_row($ors))
  {
    //$orderkey = strval(odbc_result($ors, 'orderkey'));
    $orderid = strval(odbc_result($ors, 'orderid'));
    $contactname = mysql_real_escape_string(odbc_result($ors, 'contactname'));
    $accountnum = mysql_real_escape_string(odbc_result($ors, 'accountnum'));
    $telephone = mysql_real_escape_string(odbc_result($ors, 'telephone'));
    $clientfrom = mysql_real_escape_string(odbc_result($ors, 'clientfrom'));
    $address = mysql_real_escape_string(odbc_result($ors, 'address'));
    $zipcode = mysql_real_escape_string(odbc_result($ors, 'zipcode'));
    $shippingaddr = mysql_real_escape_string(odbc_result($ors, 'shippingaddr'));
    $shippingzip = mysql_real_escape_string(odbc_result($ors, 'shippingzip'));
    $shippedby = mysql_real_escape_string(odbc_result($ors, 'shippedby'));
    $shippingsn = mysql_real_escape_string(odbc_result($ors, 'shippingsn'));
    $orderdate = mysql_real_escape_string(odbc_result($ors, 'orderdate'))  == ""? '0000-00-00 00:00:00' : mysql_real_escape_string(odbc_result($ors, 'orderdate'));
    $requireddate = mysql_real_escape_string(odbc_result($ors, 'requireddate'))  == ""? '0000-00-00 00:00:00' : mysql_real_escape_string(odbc_result($ors, 'requireddate'));
    $orderstatus = strval(odbc_result($ors, 'orderstatus'));
    $lastupdatedate = mysql_real_escape_string(odbc_result($ors, 'lastupdatedate')) == ""? '0000-00-00 00:00:00' : mysql_real_escape_string(odbc_result($ors, 'lastupdatedate'));
    $updcomment = mysql_real_escape_string(odbc_result($ors, 'updcomment'));
    $payment = mysql_real_escape_string(odbc_result($ors, 'payment'));
    $expdate = mysql_real_escape_string(odbc_result($ors, 'expdate'));
    $paydate = mysql_real_escape_string(odbc_result($ors, 'paydate')) == ""? '0000-00-00 00:00:00' : mysql_real_escape_string(odbc_result($ors, 'paydate'));
    $comments = mysql_real_escape_string(odbc_result($ors, 'comments'));
    $itemcount = strval(odbc_result($ors, 'itemcount'));
    $itemqty = strval(odbc_result($ors, 'itemqty'));
    $createdby = mysql_real_escape_string(odbc_result($ors, 'createdby'));
    $updby = mysql_real_escape_string(odbc_result($ors, 'updby'));
    $revision = strval(odbc_result($ors, 'revision'));
    
    //echo 'orderdate:'.$orderdate."\t";
    //echo 'requireddate:'.$requireddate."\n";
    echo '.';

    $msql = "SELECT * FROM ORDERS WHERE ORDERID = ".$orderid;

    if (($result = mysql_query($msql))) {
      if (mysql_num_rows($result) == 1) {
        //update
        //echo 'mysql update orders'."\n";
        $msql="UPDATE ORDERS SET ORDERID=".$orderid.",CONTACT='".$contactname."',CLIENTID='".$accountnum."',TELEPHONE='".$telephone."',NAME='".$clientfrom
        ."',ADDRESS='".$address."',ZIPCODE='".$zipcode."',SHIPPINGADDR='".$shippingaddr."',SHIPPINGZIP='".$shippingzip."',SHIPPEDBY='".$shippedby
        ."',SHIPPINGSN='".$shippingsn."',ORDERDATE='".$orderdate."',REQUIREDDATE='".$requireddate."',ORDERSTATUS=".$orderstatus
        .",LASTUPDDATE='".$lastupdatedate."',UPDCOMMENT='".$updcomment."',PAYMENT='".$payment."',EXPDATE='".$expdate
        ."',PAYDATE='".$paydate."',COMMENTS='".$comments."',ITEMCOUNT=".$itemcount.",ITEMQTY=".$itemqty
        .",CREATEDBY='".$createdby."',UPDBY='".$updby."',REVISION=".$revision
        ." WHERE ORDERID = ".$orderid;

        //echo $msql . "\n";
        if (!($result = mysql_query($msql))) {
          echo mysql_error()."\n";
        }
      } else {
        //insert
        //echo 'mysql insert orders'."\n";
        $msql="INSERT INTO ORDERS (ORDERID,CONTACT,CLIENTID,TELEPHONE,NAME,ADDRESS,ZIPCODE,SHIPPINGADDR,SHIPPINGZIP,SHIPPEDBY,SHIPPINGSN,ORDERDATE"
        .",REQUIREDDATE,ORDERSTATUS,LASTUPDDATE,UPDCOMMENT,PAYMENT,EXPDATE,PAYDATE,COMMENTS,ITEMCOUNT,ITEMQTY,CREATEDBY,UPDBY,REVISION)"
        ." VALUES (".$orderid.",'".$contactname."','".$accountnum."','".$telephone."','".$clientfrom."','".$address."','".$zipcode."','".$shippingaddr
        ."','".$shippingzip."','".$shippedby."','".$shippingsn."','".$orderdate."','".$requireddate."',".$orderstatus.",'".$lastupdatedate."','".$updcomment
        ."','".$payment."','".$expdate."','".$paydate."','".$comments."',".$itemcount.",".$itemqty.",'".$createdby."','".$updby."',".$revision.")";

        //echo $msql . "\n";
        if (!($result = mysql_query($msql))) {
          echo mysql_error()."\n";
        }
      }
    } else {
      echo mysql_error();
    }
  }
  odbc_close($oconn);
  mysql_close($conn);

}


function load_stocks() {
  $dbhost = 'mysql.oyou.com';
  $dbuser = 'storeuniform';
  $dbpass = 'longxia1!';

  $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql uniform.');
  $dbname = 'storeuniform';
  mysql_select_db($dbname);


  $oconn=odbc_connect('inventory','canuckst_uniform','longxia1!') or die ('Error connecting to odbc inventory.');
  $osql="SELECT * FROM in_out";
  $ors=odbc_exec($oconn,$osql);

  while (odbc_fetch_row($ors))
  {
    $inoutsn = strval(odbc_result($ors, 'inoutsn'));
    $itemcode = mysql_real_escape_string(odbc_result($ors, 'itemcode'));
    $typesn = mysql_real_escape_string(odbc_result($ors, 'typesn'));
    $operation = mysql_real_escape_string(odbc_result($ors, 'operation'));
    $op_date = mysql_real_escape_string(odbc_result($ors, 'op_date')) == ""? '0000-00-00 00:00:00' : mysql_real_escape_string(odbc_result($ors, 'op_date'));
    $orderid = mysql_real_escape_string(odbc_result($ors, 'orderid'));
    $itemsninorder = mysql_real_escape_string(odbc_result($ors, 'itemsninorder'));
    $xxsmall = mysql_real_escape_string(odbc_result($ors, 'xxsmall'));
    $xxsmallnumbers = mysql_real_escape_string(odbc_result($ors, 'xxsmallnumbers'));
    $xsmall = mysql_real_escape_string(odbc_result($ors, 'xsmall'));
    $xsmallnumbers = mysql_real_escape_string(odbc_result($ors, 'xsmallnumbers'));
    $small = mysql_real_escape_string(odbc_result($ors, 'small'));
    $smallnumbers = mysql_real_escape_string(odbc_result($ors, 'smallnumbers'));
    $medium = mysql_real_escape_string(odbc_result($ors, 'medium'));
    $mediumnumbers = mysql_real_escape_string(odbc_result($ors, 'mediumnumbers'));
    $large = mysql_real_escape_string(odbc_result($ors, 'large'));
    $largernumbers = mysql_real_escape_string(odbc_result($ors, 'largenumbers'));
    $xlarge = mysql_real_escape_string(odbc_result($ors, 'xlarge'));
    $xlargenumbers = mysql_real_escape_string(odbc_result($ors, 'xlargenumbers'));
    $xxlarge = mysql_real_escape_string(odbc_result($ors, 'xxlarge'));
    $xxlargenumbers = mysql_real_escape_string(odbc_result($ors, 'xxlargenumbers'));
    $othersize = mysql_real_escape_string(odbc_result($ors, 'othersize'));
    $othernumbers = mysql_real_escape_string(odbc_result($ors, 'othernumbers'));
    $description = mysql_real_escape_string(odbc_result($ors, 'description'));
    $frontnumpos = mysql_real_escape_string(odbc_result($ors, 'frontnumpos'));
    $frontnumsize = mysql_real_escape_string(odbc_result($ors, 'frontnumsize'));
    $frontnumcolor = mysql_real_escape_string(odbc_result($ors, 'frontnumcolor'));
    $frontnumtrimcolor = mysql_real_escape_string(odbc_result($ors, 'frontnumtrimcolor'));
    $frontlogopos = mysql_real_escape_string(odbc_result($ors, 'frontlogopos'));
    $frontlogoname = mysql_real_escape_string(odbc_result($ors, 'frontlogoname'));
    $frontlogocolor = mysql_real_escape_string(odbc_result($ors, 'frontlogocolor'));
    $frontlogotrimcolor = mysql_real_escape_string(odbc_result($ors, 'frontlogotrimcolor'));
    $rearname = mysql_real_escape_string(odbc_result($ors, 'rearname'));
    $rearnumpos = mysql_real_escape_string(odbc_result($ors, 'rearnumpos'));
    $rearnumsize = mysql_real_escape_string(odbc_result($ors, 'rearnumsize'));
    $rearnumcolor = mysql_real_escape_string(odbc_result($ors, 'rearnumcolor'));
    $rearnumtrimcolor = mysql_real_escape_string(odbc_result($ors, 'rearnumtrimcolor'));
    $rearlogopos = mysql_real_escape_string(odbc_result($ors, 'rearlogopos'));
    $rearlogoname = mysql_real_escape_string(odbc_result($ors, 'rearlogoname'));
    $rearlogocolor = mysql_real_escape_string(odbc_result($ors, 'rearlogocolor'));
    $rearlogotrimcolor = mysql_real_escape_string(odbc_result($ors, 'rearlogotrimcolor'));
    $sidenumpos = mysql_real_escape_string(odbc_result($ors, 'rearnumpos'));
    $sidenumsize = mysql_real_escape_string(odbc_result($ors, 'rearnumsize'));
    $sidenumcolor = mysql_real_escape_string(odbc_result($ors, 'rearnumcolor'));
    $sidenumtrimcolor = mysql_real_escape_string(odbc_result($ors, 'rearnumtrimcolor'));
    $sidelogopos = mysql_real_escape_string(odbc_result($ors, 'rearlogopos'));
    $sidelogoname = mysql_real_escape_string(odbc_result($ors, 'rearlogoname'));
    $sidelogocolor = mysql_real_escape_string(odbc_result($ors, 'rearlogocolor'));
    $sidelogotrimcolor = mysql_real_escape_string(odbc_result($ors, 'rearlogotrimcolor'));
    $liberodesc = mysql_real_escape_string(odbc_result($ors, 'liberodesc'));
    
    
    echo '.';

    $msql = "SELECT * FROM STOCKS WHERE STOCKID = ".$inoutsn;

    if (($result = mysql_query($msql))) {
      if (mysql_num_rows($result) == 1) {
        //update
        //echo 'mysql update stocks'."\n";
        $msql="UPDATE STOCKS SET ITEMCODE='".$itemcode."',STYLEID=".$typesn.",CODE='".$operation."',STOCKDATE='".$op_date."',ORDERID=".$orderid.",SERIALNUM=".$itemsninorder
        .",XXSMALL=".$xxsmall.",XXSMALLNUMBERS='".$xxsmallnumbers."',XSMALL=".$xsmall.",XSMALLNUMBERS='".$xsmallnumbers."',SMALL=".$small.",SMALLNUMBERS='".$smallnumbers
        ."',MEDIUM=".$medium.",MEDIUMNUMBERS='".$mediumnumbers."',LARGE=".$large.",LARGENUMBERS='".$largernumbers."',XLARGE=".$xlarge.",XLARGENUMBERS='".$xlargenumbers
        ."',XXLARGE=".$xxlarge.",XXLARGENUMBERS='".$xxlargenumbers."',OTHERSIZE='".$othersize."',OTHERSIZENUMBERS='".$othernumbers."',DESCRIPTION='".$description
        ."',FRONTNUMPOS=".$frontnumpos.",FRONTNUMSIZE=".$frontnumsize.",FRONTNUMCOLOR='".$frontnumcolor."',FRONTNUMTRIMCOLOR='".$frontnumtrimcolor
        ."',FRONTLOGOPOS=".$frontlogopos.",FRONTLOGONAME='".$frontlogoname."',FRONTLOGOCOLOR='".$frontlogocolor."',FRONTLOGOTRIMCOLOR='".$frontlogotrimcolor
        ."',REARNUMPOS=".$rearnumpos.",REARNUMSIZE=".$rearnumsize.",REARNUMCOLOR='".$rearnumcolor."',REARNUMTRIMCOLOR='".$rearnumtrimcolor
        ."',REARLOGOPOS=".$rearlogopos.",REARLOGONAME='".$rearlogoname."',REARLOGOCOLOR='".$rearlogocolor."',REARLOGOTRIMCOLOR='".$rearlogotrimcolor
        ."',SIDENUMPOS=".$sidenumpos.",SIDENUMSIZE=".$sidenumsize.",SIDENUMCOLOR='".$sidenumcolor."',SIDENUMTRIMCOLOR='".$sidenumtrimcolor
        ."',SIDELOGOPOS=".$sidelogopos.",SIDELOGONAME='".$sidelogoname."',SIDELOGOCOLOR='".$sidelogocolor."',SIDELOGOTRIMCOLOR='".$sidelogotrimcolor
        ."',COMMENTS='".$liberodesc
        ."' WHERE STOCKID = ".$inoutsn;

        //echo $msql . "\n";
        if (!($result = mysql_query($msql))) {
          echo mysql_error()."\n";
        }
      } else {
        //insert
        //echo 'mysql insert stocks'."\n";
        $msql="INSERT INTO STOCKS (STOCKID,ITEMCODE,STYLEID,CODE,STOCKDATE,ORDERID,SERIALNUM"
        .",XXSMALL,XXSMALLNUMBERS,XSMALL,XSMALLNUMBERS,SMALL,SMALLNUMBERS,MEDIUM,MEDIUMNUMBERS,LARGE,LARGENUMBERS"
        .",XLARGE,XLARGENUMBERS,XXLARGE,XXLARGENUMBERS,OTHERSIZE,OTHERSIZENUMBERS,DESCRIPTION"
        .",FRONTNUMPOS,FRONTNUMSIZE,FRONTNUMCOLOR,FRONTNUMTRIMCOLOR,FRONTLOGOPOS,FRONTLOGONAME,FRONTLOGOCOLOR,FRONTLOGOTRIMCOLOR"
        .",REARNUMPOS,REARNUMSIZE,REARNUMCOLOR,REARNUMTRIMCOLOR,REARLOGOPOS,REARLOGONAME,REARLOGOCOLOR,REARLOGOTRIMCOLOR"
        .",SIDENUMPOS,SIDENUMSIZE,SIDENUMCOLOR,SIDENUMTRIMCOLOR,SIDELOGOPOS,SIDELOGONAME,SIDELOGOCOLOR,SIDELOGOTRIMCOLOR"
        .",COMMENTS)"
        ." VALUES (".$inoutsn.",'".$itemcode."',".$typesn.",'".$operation."','".$op_date."',".$orderid.",".$itemsninorder
        .",".$xxsmall.",'".$xxsmallnumbers."',".$xsmall.",'".$xsmallnumbers."',".$small.",'".$smallnumbers."',".$medium.",'".$mediumnumbers."',".$large.",'".$largernumbers
        ."',".$xlarge.",'".$xlargenumbers."',".$xxlarge.",'".$xxlargenumbers."','".$othersize."','".$othernumbers."','".$description
        ."',".$frontnumpos.",".$frontnumsize.",'".$frontnumcolor."','".$frontnumtrimcolor."',".$frontlogopos.",'".$frontlogoname."','".$frontlogocolor."','".$frontlogotrimcolor
        ."',".$rearnumpos.",".$rearnumsize.",'".$rearnumcolor."','".$rearnumtrimcolor."',".$rearlogopos.",'".$rearlogoname."','".$rearlogocolor."','".$rearlogotrimcolor
        ."',".$sidenumpos.",".$sidenumsize.",'".$sidenumcolor."','".$sidenumtrimcolor."',".$sidelogopos.",'".$sidelogoname."','".$sidelogocolor."','".$sidelogotrimcolor
        ."','".$liberodesc."')";

        //echo $msql . "\n";
        if (!($result = mysql_query($msql))) {
          echo mysql_error()."\n";
        }
      }
    } else {
      echo mysql_error();
    }
  }
  odbc_close($oconn);
  mysql_close($conn);

}

function load_items() {
  $dbhost = 'mysql.oyou.com';
  $dbuser = 'storeuniform';
  $dbpass = 'longxia1!';

  $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql uniform.');
  $dbname = 'storeuniform';
  mysql_select_db($dbname);


  $oconn=odbc_connect('inventory','canuckst_uniform','longxia1!') or die ('Error connecting to odbc inventory.');
  $osql="SELECT * FROM items";
  $ors=odbc_exec($oconn,$osql);

  while (odbc_fetch_row($ors))
  {
    $itemcode = strval(odbc_result($ors, 'itemcode'));
    $itemname = mysql_real_escape_string(odbc_result($ors, 'itemname'));
    $itemsn = strval(odbc_result($ors, 'itemsn'));

    echo '.';
    $msql = "SELECT * FROM ITEMS WHERE ITEMID = ".$itemsn;

    if (($result = mysql_query($msql))) {
      if (mysql_num_rows($result) == 1) {
        //update
        //echo 'mysql update items'."\n";
        $msql="UPDATE ITEMS SET ITEMCODE='".$itemcode."',ITEMNAME='".$itemname
        ."' WHERE ITEMID = ".$itemsn;

        //echo $msql . "\n";
        if (!($result = mysql_query($msql))) {
          echo mysql_error();
        }
      } else {
        //insert
        //echo 'mysql insert items'."\n";
        $msql="INSERT INTO ITEMS (ITEMID,ITEMCODE,ITEMNAME) VALUES ("
        .$itemsn.",'".$itemcode."','".$itemname."')";

        //echo $msql . "\n";
        if (!($result = mysql_query($msql))) {
          echo mysql_error();
        }
      }
    } else {
      echo mysql_error();
    }
  }
  odbc_close($oconn);
  mysql_close($conn);

}

function load_itemtypes() {
  $dbhost = 'mysql.oyou.com';
  $dbuser = 'storeuniform';
  $dbpass = 'longxia1!';

  $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql uniform.');
  $dbname = 'storeuniform';
  mysql_select_db($dbname);


  $oconn=odbc_connect('inventory','canuckst_uniform','longxia1!') or die ('Error connecting to odbc inventory.');
  $osql="SELECT * FROM itemtypes";
  $ors=odbc_exec($oconn,$osql);

  while (odbc_fetch_row($ors))
  {
    $itemcode = strval(odbc_result($ors, 'itemcode'));
    $typecode = strval(odbc_result($ors, 'typecode'));
    $typename = strval(odbc_result($ors, 'typename'));
    $typepic = mysql_real_escape_string(odbc_result($ors, 'typepic'));
    $typesn = strval(odbc_result($ors, 'typesn'));

    echo '.';
    $msql = "SELECT * FROM STYLES WHERE STYLEID = ".$typesn;

    if (($result = mysql_query($msql))) {
      if (mysql_num_rows($result) == 1) {
        //update
        //echo 'mysql update items'."\n";
        $msql="UPDATE STYLES SET ITEMCODE='".$itemcode."',COLORID='".$typecode."',COLORNAME='".$typename."',FRONTIMAGE='".$typepic
        ."' WHERE STYLEID = ".$typesn;

        //echo $msql . "\n";
        if (!($result = mysql_query($msql))) {
          echo mysql_error();
        }
      } else {
        //insert
        //echo 'mysql insert items'."\n";
        $msql="INSERT INTO STYLES (STYLEID,ITEMCODE,COLORID,COLORNAME,FRONTIMAGE) VALUES ("
        .$typesn.",'".$itemcode."','".$typecode."','".$typename."','".$typepic."')";

        //echo $msql . "\n";
        if (!($result = mysql_query($msql))) {
          echo mysql_error();
        }
      }
    } else {
      echo mysql_error();
    }
  }
  odbc_close($oconn);
  mysql_close($conn);

}

function load_colors() {
  $dbhost = 'mysql.oyou.com';
  $dbuser = 'storeuniform';
  $dbpass = 'longxia1!';

  $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql uniform.');
  $dbname = 'storeuniform';
  mysql_select_db($dbname);


  $oconn=odbc_connect('inventory','canuckst_uniform','longxia1!') or die ('Error connecting to odbc inventory.');
  $osql="SELECT * FROM colors";
  $ors=odbc_exec($oconn,$osql);

  while (odbc_fetch_row($ors))
  {
    $colorid = strval(odbc_result($ors, 'colorid'));
    $colorname = mysql_real_escape_string(odbc_result($ors, 'colorname'));

    echo '.';
    $msql = "SELECT * FROM COLORS WHERE COLORID = ".$colorid;

    if (($result = mysql_query($msql))) {
      if (mysql_num_rows($result) == 1) {
        //update
        //echo 'mysql update colors'."\n";
        $msql="UPDATE COLORS SET COLORNAME='".$colorname
        ."' WHERE COLORID = ".$colorid;

        //echo $msql . "\n";
        if (!($result = mysql_query($msql))) {
          echo mysql_error();
        }
      } else {
        //insert
        //echo 'mysql insert colors'."\n";
        $msql="INSERT INTO COLORS (COLORID,COLORNAME) VALUES ("
        .$colorid.",'".$colorname."')";

        //echo $msql . "\n";
        if (!($result = mysql_query($msql))) {
          echo mysql_error();
        }
      }
    } else {
      echo mysql_error();
    }
  }
  odbc_close($oconn);
  mysql_close($conn);

}

function load_operations() {
  $dbhost = 'mysql.oyou.com';
  $dbuser = 'storeuniform';
  $dbpass = 'longxia1!';

  $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql uniform.');
  $dbname = 'storeuniform';
  mysql_select_db($dbname);


  $oconn=odbc_connect('inventory','canuckst_uniform','longxia1!') or die ('Error connecting to odbc inventory.');
  $osql="SELECT * FROM operations";
  $ors=odbc_exec($oconn,$osql);

  while (odbc_fetch_row($ors))
  {
    $opsn = strval(odbc_result($ors, 'opsn'));
    $opname = mysql_real_escape_string(odbc_result($ors, 'opname'));
    $sign = strval(odbc_result($ors, 'sign'));

    echo '.';
    $msql = "SELECT * FROM OPERATIONS WHERE OPERATIONID = ".$opsn;

    if (($result = mysql_query($msql))) {
      if (mysql_num_rows($result) == 1) {
        //update
        //echo 'mysql update operations'."\n";
        $msql="UPDATE OPERATIONS SET OPERATIONNAME='".$opname."',SIGN=".$sign
        ." WHERE OPERATIONID = ".$opsn;

        //echo $msql . "\n";
        if (!($result = mysql_query($msql))) {
          echo mysql_error();
        }
      } else {
        //insert
        //echo 'mysql insert operations'."\n";
        $msql="INSERT INTO OPERATIONS (OPERATIONID,OPERATIONNAME,SIGN) VALUES ("
        .$opsn.",'".$opname."',".$sign.")";

        //echo $msql . "\n";
        if (!($result = mysql_query($msql))) {
          echo mysql_error();
        }
      }
    } else {
      echo mysql_error();
    }
  }
  odbc_close($oconn);
  mysql_close($conn);

}


//load tables
echo "\nload clients table\n";

load_clients();

echo "\nload orders table\n";

load_orders();

echo "\nload stocks table\n"; 
load_stocks();

echo "\nload items table\n";
load_items();

echo "\nload styles table\n";
load_itemtypes();

?>
