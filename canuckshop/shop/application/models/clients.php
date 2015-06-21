<?php

class Clients extends CI_Model {

  function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
  }

  function search($what)
  {
    $what = mysql_real_escape_string($what);
    $search_sql = "SELECT * FROM clients";
    if (isset($what)) {
      $search_sql = $search_sql." WHERE name like '".$what."%'";
      $search_sql = $search_sql." OR team like '%".$what."%'";
      $search_sql = $search_sql." OR contact like '%".$what."%'";
      $search_sql = $search_sql." OR email like '%".$what."%'";
      $search_sql = $search_sql." OR telephone like '%".$what."%'";
      $search_sql = $search_sql." OR cellphone like '%".$what."%'";
    }
    $search_sql = $search_sql." ORDER BY clientid DESC LIMIT 1000";

    //log_message('debug', $search_sql);
    $query = $this->db->query($search_sql);
    if ($query->num_rows() > 0)
    {
      return $query;
    } else {
      return FALSE;
    }
  }

  function select($clientid)
  {
    $select_sql = "SELECT * FROM clients";
    $select_sql = $select_sql." WHERE clientid = ".$clientid." LIMIT 1";

    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row;
    } else {
      return FALSE;
    }
  }

  function select_client_by_contact_telephone($contact, $telephone)
  {
    $select_sql = "SELECT * FROM clients";
    $select_sql = $select_sql." WHERE LOWER(contact) = '".strtolower($contact)."' AND telephone = '".$telephone."' LIMIT 1";
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row;
    } else {
      $select_sql = "SELECT * FROM clients";
      $select_sql = $select_sql." WHERE telephone = '".$telephone."' LIMIT 1";
      $query = $this->db->query($select_sql);
      if ($query->num_rows() > 0)
      {
        $row = $query->row();
        return $row;
      } else {
        return FALSE;
      }
    }
  }

  function insert($name,$team,$contact,$email,$telephone,$cellphone,$fax,$street,$city,$province,$zipcode,$account = '')
  {
    $clientid = $this->next_clientid();
    if ($clientid) {
      $insert_sql="INSERT INTO clients (clientid,name,team,contact,email,account,telephone,cellphone,fax,street,city,province,zipcode,status) VALUES ("
      .$clientid
      .",'".mysql_real_escape_string($name)
      ."','".mysql_real_escape_string($team)
      ."','".mysql_real_escape_string($contact)
      ."','".mysql_real_escape_string($email)
      ."','".$account
      ."','".mysql_real_escape_string($telephone)
      ."','".mysql_real_escape_string($cellphone)
      ."','".mysql_real_escape_string($fax)
      ."','".mysql_real_escape_string($street)
      ."','".mysql_real_escape_string($city)
      ."','".mysql_real_escape_string($province)
      ."','".mysql_real_escape_string($zipcode)
      ."',1)";
      $query = $this->db->query($insert_sql);
      if ($this->db->affected_rows() > 0) {
        return $clientid;
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }

  function next_clientid()
  {
    $select_sql="SELECT MAX(clientid)+1 AS next_id FROM clients";
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->next_id;
    } else {
      return FALSE;
    }
  }

  function update($clientid,$name,$team,$contact,$email,$telephone,$cellphone,$fax,$street,$city,$province,$zipcode,$account)
  {
    $update_sql="UPDATE clients SET name='".mysql_real_escape_string($name)
    ."',team='".mysql_real_escape_string($team)
    ."',contact='".mysql_real_escape_string($contact)
    ."',email='".mysql_real_escape_string($email)
    ."',account='".mysql_real_escape_string($account)
    ."',telephone='".mysql_real_escape_string($telephone)
    ."',cellphone='".mysql_real_escape_string($cellphone)
    ."',fax='".mysql_real_escape_string($fax)
    ."',street='".mysql_real_escape_string($street)
    ."',city='".mysql_real_escape_string($city)
    ."',province='".mysql_real_escape_string($province)
    ."',zipcode='".mysql_real_escape_string($zipcode)
    ."' WHERE clientid = ".$clientid;
    $query = $this->db->query($update_sql);
    if ($this->db->affected_rows() > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function update_client($clientid,$name,$team,$contact,$email,$telephone,$cellphone,$fax,$street,$city,$province,$zipcode)
  {
    $update_sql="UPDATE clients SET name='".mysql_real_escape_string($name)
    ."',team='".mysql_real_escape_string($team)
    ."',contact='".mysql_real_escape_string($contact)
    ."',email='".mysql_real_escape_string($email)
    ."',telephone='".mysql_real_escape_string($telephone)
    ."',cellphone='".mysql_real_escape_string($cellphone)
    ."',fax='".mysql_real_escape_string($fax)
    ."',street='".mysql_real_escape_string($street)
    ."',city='".mysql_real_escape_string($city)
    ."',province='".mysql_real_escape_string($province)
    ."',zipcode='".mysql_real_escape_string($zipcode)
    ."' WHERE clientid = ".$clientid;
    $query = $this->db->query($update_sql);
    if ($this->db->affected_rows() > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
  
  function status($clientid, $status)
  {
    $update_sql="UPDATE clients SET status=".mysql_real_escape_string($status)
    ." WHERE clientid = ".$clientid;
    $query = $this->db->query($update_sql);
    if ($this->db->affected_rows() > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

}
