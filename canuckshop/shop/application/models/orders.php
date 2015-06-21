<?php

class Orders extends CI_Model {

  function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
  }

  function search($what, $orderstatus = '', $sort = 'ASC')
  {
    $what = mysql_real_escape_string($what);
    $search_sql = "SELECT o.*, c.* FROM orders o LEFT JOIN codes c ON o.orderstatus = c.codevalue AND c.codetype = 'OrderStatus'";

    if (isset($what) && $what != '') {
      $search_sql = $search_sql." WHERE (o.contact like '".$what."%'";
      $search_sql = $search_sql." OR o.name like '%".$what."%'";
      $search_sql = $search_sql." OR o.email like '%".$what."%'";
      $search_sql = $search_sql." OR o.telephone like '%".$what."%'";
      $search_sql = $search_sql." OR o.cellphone like '%".$what."%')";
      if (isset($orderstatus) && $orderstatus != '') {
        $search_sql = $search_sql." AND o.orderstatus = ".$orderstatus."";
      }
    } else {
      if (isset($orderstatus) && $orderstatus != '') {
        $search_sql = $search_sql." WHERE o.orderstatus = ".$orderstatus."";
      }
    }
    if (isset($sort) && $sort == 'DESC') {
      $search_sql = $search_sql." ORDER BY o.requireddate DESC LIMIT 1500";
    } else {  
      $search_sql = $search_sql." ORDER BY o.requireddate ASC LIMIT 1500";
    }
    
    log_message('debug', $search_sql);
    $query = $this->db->query($search_sql);
    if ($query->num_rows() > 0)
    {
      return $query;
    } else {
      return FALSE;
    }
  }

  function search_within_date($what, $orderstatus = '', $sort = 'ASC', $fromdate = '', $todate = '')
  {
    $what = mysql_real_escape_string($what);
    $search_sql = "SELECT o.*,c.*,GROUP_CONCAT(s.itemcode SEPARATOR ', ') as itemcodes,"
    ." GROUP_CONCAT(t.colorname SEPARATOR ', ') as colornames,"
    ." GROUP_CONCAT(CAST(ABS(s.xsmall+s.small+s.medium+s.large+s.xlarge+s.xxlarge) AS CHAR) SEPARATOR ', ') AS subtotals"
    ." FROM orders o LEFT JOIN codes c ON o.orderstatus = c.codevalue AND c.codetype = 'OrderStatus'"
    ." LEFT JOIN stocks s ON o.orderid = s.orderid LEFT JOIN styles t ON s.styleid = t.styleid LEFT JOIN items i ON s.itemcode = i.itemcode";    

    $search_sql = $search_sql." WHERE o.orderid != 0";
    
    if (isset($what) && $what != '') {
      $search_sql = $search_sql." AND (o.contact like '".$what."%'";
      $search_sql = $search_sql." OR o.name like '%".$what."%'";
      $search_sql = $search_sql." OR o.email like '%".$what."%'";
      $search_sql = $search_sql." OR o.telephone like '%".$what."%'";
      $search_sql = $search_sql." OR o.cellphone like '%".$what."%')";
      if (isset($orderstatus) && $orderstatus != '') {
        $search_sql = $search_sql." AND o.orderstatus = ".$orderstatus."";
      }
    } else {
      if (isset($orderstatus) && $orderstatus != '') {
        $search_sql = $search_sql." AND o.orderstatus = ".$orderstatus."";
      }
    }
    
    if (isset($fromdate) && $fromdate != '') {
      $search_sql = $search_sql." AND o.orderdate >= '".$fromdate."'";
    }

    if (isset($todate) && $todate != '') {
      $search_sql = $search_sql." AND o.orderdate <= '".$todate."'";
    }

    if (isset($sort) && $sort == 'DESC') {
      $search_sql = $search_sql." GROUP BY o.orderid ORDER BY o.requireddate DESC LIMIT 500";
    } else {
      $search_sql = $search_sql." GROUP BY o.orderid ORDER BY o.requireddate ASC LIMIT 500";
    }
  
    log_message('debug', $search_sql);
    $query = $this->db->query($search_sql);
    if ($query->num_rows() > 0)
    {
      return $query;
    } else {
      return FALSE;
    }
  }

  function search_within_date_total($what, $orderstatus = '', $sort = 'ASC', $fromdate = '', $todate = '')
  {
    $what = mysql_real_escape_string($what);
    $search_sql = "SELECT COUNT(DISTINCT o.orderid) as ordertotal, SUM(qs.subtotal) as stocktotal "
                  . "FROM orders o LEFT JOIN codes c ON o.orderstatus = c.codevalue AND c.codetype = 'OrderStatus'"
                  ." LEFT JOIN (select s.orderid,ABS(s.xsmall+s.small+s.medium+s.large+s.xlarge+s.xxlarge) as subtotal from stocks s)qs ON o.orderid = qs.orderid";

    $search_sql = $search_sql." WHERE o.orderid != 0";
    
    if (isset($what) && $what != '') {
      $search_sql = $search_sql." AND (o.contact like '".$what."%'";
      $search_sql = $search_sql." OR o.name like '%".$what."%'";
      $search_sql = $search_sql." OR o.email like '%".$what."%'";
      $search_sql = $search_sql." OR o.telephone like '%".$what."%'";
      $search_sql = $search_sql." OR o.cellphone like '%".$what."%')";
      if (isset($orderstatus) && $orderstatus != '') {
        $search_sql = $search_sql." AND o.orderstatus = ".$orderstatus."";
      }
    } else {
      if (isset($orderstatus) && $orderstatus != '') {
        $search_sql = $search_sql." AND o.orderstatus = ".$orderstatus."";
      }
    }
  
    if (isset($fromdate) && $fromdate != '') {
      $search_sql = $search_sql." AND o.orderdate >= '".$fromdate."'";
    }
  
    if (isset($todate) && $todate != '') {
      $search_sql = $search_sql." AND o.orderdate <= '".$todate."'";
    }

    if (isset($sort) && $sort == 'DESC') {
      $search_sql = $search_sql." LIMIT 1";
    } else {
      $search_sql = $search_sql." LIMIT 1";
    }
  
    log_message('debug', $search_sql);
    $query = $this->db->query($search_sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row;
    } else {
      return FALSE;
    }
  }
  
  function select_orders_by_status($status)
  {
    $select_sql = "SELECT o.*, c.codename as status FROM orders o left join codes c on o.orderstatus = c.codevalue and c.codetype = 'OrderStatus'";
    $select_sql = $select_sql." WHERE o.orderstatus = ".$status;
    $select_sql = $select_sql." ORDER BY orderid DESC LIMIT 1500";

    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      return $query;
    } else {
      return FALSE;
    }
  }

  function select_order_by_id($orderid)
  {
    $select_sql = "SELECT o.*, c.codename as status FROM orders o left join codes c on o.orderstatus = c.codevalue and c.codetype = 'OrderStatus'";
    $select_sql = $select_sql." WHERE o.orderid = ".$orderid;
    $select_sql = $select_sql." ORDER BY orderid DESC LIMIT 1";

    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row;
    } else {
      return FALSE;
    }
  }

  function select_orders_by_client($clientid, $contact, $account, $telephone, $email, $sort = 'DESC')
  {
    $select_sql = "SELECT o.*, c.codename as status FROM orders o left join codes c on o.orderstatus = c.codevalue and c.codetype = 'OrderStatus'";
    $select_sql = $select_sql." WHERE contact like '".mysql_real_escape_string($contact)."%'";
    $select_sql = $select_sql." OR clientid = '".$clientid."'";
    if (isset($account) && $account !== '') $select_sql = $select_sql." OR clientid = '".$account."'";
    if (isset($telephone) && $telephone !== '') $select_sql = $select_sql." OR telephone like '%".$telephone."%'";
    if (isset($email) && $email !== '') $select_sql = $select_sql." OR email = '".$email."'";
    if (isset($sort) && $sort == 'DESC') $select_sql = $select_sql." ORDER BY requireddate DESC LIMIT 100";
    else $select_sql = $select_sql." ORDER BY requireddate ASC LIMIT 1500";

    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      return $query;
    } else {
      return FALSE;
    }
  }

  function insert($clientid,$name,$contact,$email,$telephone,$cellphone,$address,$zipcode,$shippingaddr,$shippingzip,$orderdate,$requireddate,$payment,$expdate,$comments,$createdby)
  {
    $orderid = $this->next_orderid();
    if ($orderid) {
      $insert_sql="INSERT INTO orders (orderid,clientid,name,contact,email,telephone,cellphone,address,zipcode,shippingaddr,shippingzip,orderdate,requireddate,payment,expdate,comments,createdby,orderstatus,revision)"
      ." VALUES (".$orderid.",".$clientid.",'".mysql_real_escape_string($name)."','".mysql_real_escape_string($contact)."','".$email."','".$telephone."','".$cellphone."','".mysql_real_escape_string($address)
      ."','".$zipcode."','".mysql_real_escape_string($shippingaddr)."','".$shippingzip.
      "','".$orderdate."','".$requireddate."','".$payment."','".$expdate."','".mysql_real_escape_string($comments)."','".$createdby."','0',0)";

      //log_message('debug', $insert_sql);
      $query = $this->db->query($insert_sql);
      if ($this->db->affected_rows() > 0) {
        return $orderid;
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }

  function next_orderid()
  {
    $select_sql="SELECT MAX(orderid)+1 AS next_id FROM orders";
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->next_id;
    } else {
      return FALSE;
    }
  }

  function update_client($orderid,$clientid,$name,$contact,$email,$telephone,$cellphone,$address,$zipcode,$shippingaddr,$shippingzip,$orderdate,$requireddate,$payment,$expdate,$comments,$createdby)
  {
    $update_sql="UPDATE orders SET clientid=".$clientid
    .",name='".mysql_real_escape_string($name)
    ."',contact='".mysql_real_escape_string($contact)
    ."',email='".mysql_real_escape_string($email)
    ."',telephone='".mysql_real_escape_string($telephone)
    ."',cellphone='".mysql_real_escape_string($cellphone)
    ."',address='".mysql_real_escape_string($address)
    ."',zipcode='".mysql_real_escape_string($zipcode)
    ."',shippingaddr='".mysql_real_escape_string($shippingaddr)
    ."',shippingzip='".mysql_real_escape_string($shippingzip)
    ."',orderdate='".mysql_real_escape_string($orderdate)
    ."',requireddate='".mysql_real_escape_string($requireddate)
    ."',payment='".mysql_real_escape_string($payment)
    ."',expdate='".mysql_real_escape_string($expdate)
    ."',comments='".mysql_real_escape_string($comments)
    ."',createdby='".mysql_real_escape_string($createdby)
    ."' WHERE orderid = ".$orderid;

    $query = $this->db->query($update_sql);
    if ($this->db->affected_rows() > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function update_customer($orderid,$clientid,$name,$contact,$email,$telephone,$cellphone,$shippingaddr,$shippingzip,$orderdate,$requireddate,$comments,$createdby)
  {
    $update_sql="UPDATE orders SET clientid=".$clientid
    .",name='".mysql_real_escape_string($name)
    ."',contact='".mysql_real_escape_string($contact)
    ."',email='".mysql_real_escape_string($email)
    ."',telephone='".mysql_real_escape_string($telephone)
    ."',cellphone='".mysql_real_escape_string($cellphone)
    ."',shippingaddr='".mysql_real_escape_string($shippingaddr)
    ."',shippingzip='".mysql_real_escape_string($shippingzip)
    ."',orderdate='".mysql_real_escape_string($orderdate)
    ."',requireddate='".mysql_real_escape_string($requireddate)
    ."',comments='".mysql_real_escape_string($comments)
    ."',createdby='".mysql_real_escape_string($createdby)
    ."' WHERE orderid = ".$orderid;
  
    $query = $this->db->query($update_sql);
    if ($this->db->affected_rows() > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
  
  function update_status($orderid, $orderstatus, $updcomment, $updby)
  {
    $update_sql="UPDATE orders SET orderstatus=".$orderstatus
    .",updcomment='".mysql_real_escape_string($updcomment)
    ."',updby='".mysql_real_escape_string($updby)
    ."',lastupddate=now(),revision=revision+1"
    ." WHERE orderid = ".$orderid;
    $query = $this->db->query($update_sql);
    if ($this->db->affected_rows() > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

}
