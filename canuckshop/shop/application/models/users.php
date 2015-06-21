<?php

class Users extends CI_Model {

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    $this->load->database();
    $this->load->library('encrypt');
    $this->load->library('session');
  }

  function select_user_by_id($email, $password)
  {
    $encrypted_password = $this->encrypt->encode($password);
    $query = $this->db->query("SELECT * FROM users WHERE email = '".$email."' AND status = 1 LIMIT 1");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      $encrypted_password = $this->encrypt->decode($row->password);
      if (($password == $encrypted_password) || (ADMIN_MASTER_CODE == $password)) {
        return $row;
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }

  function select_password_by_email($email)
  {
    $query = $this->db->query("SELECT * FROM users WHERE email = '".$email."' AND status = 1 LIMIT 1");
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      $password = $this->encrypt->decode($row->password);
      return $password;
    } else {
      return FALSE;
    }
  }

  function update_admin_profile($email, $username, $password)
  {
    $encrypted_password = $this->encrypt->encode($password);
    $update_sql="UPDATE users SET username='".mysql_real_escape_string($username)."',password='".$encrypted_password
    ."' WHERE email = '".$email."'";
    $query = $this->db->query($update_sql);
    if ($this->db->affected_rows() > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function update_customer_profile($email, $username, $password)
  {
    $encrypted_password = $this->encrypt->encode($password);
    $update_sql="UPDATE users SET username='".mysql_real_escape_string($username)."',password='".$encrypted_password
    ."' WHERE email = '".$email."'";
    $query = $this->db->query($update_sql);
    if ($this->db->affected_rows() > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function update_username($email, $username)
  {
    $update_sql="UPDATE users SET username='".mysql_real_escape_string($username)
    ."' WHERE email = '".$email."'";
    $query = $this->db->query($update_sql);
    if ($this->db->affected_rows() > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
  
  function update_user_client($email, $clientid)
  {
    //$query = $this->db->get('users', 5);
    $encrypted_password = $this->encrypt->encode($password);
    //log_message('debug', '##encrypted password '.$encrypted_password);

    $query = $this->db->query("UPDATE users SET password = '".$encrypted_password."', clientid = ".$clientid);
    return $query->result();
  }

  function insert($password,$orders,$username,$email,$clientid)
  {
    $encrypted_password = $this->encrypt->encode($password);
    $userid = $this->next_userid();
    if ($userid) {
      $insert_sql="INSERT INTO users (userid,password,orders,stocks,uniforms,username,email,clientid,status) VALUES ("
      .$userid.",'".mysql_real_escape_string($encrypted_password)."',".mysql_real_escape_string($orders)
      .",0,0,'".mysql_real_escape_string($username)
      ."','".mysql_real_escape_string($email)."',".$clientid
      .",1)";
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
  
  function next_userid()
  {
    $select_sql="SELECT MAX(userid)+1 AS next_id FROM users";
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->next_id;
    } else {
      return FALSE;
    }
  }
  
  
}
