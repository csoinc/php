<?php

class Attributes extends CI_Model {

  function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
  }

  function select_color()
  {
    $select_sql = "SELECT attname, attvalue FROM attributes";
    $select_sql = $select_sql." WHERE atttype = 'Colour' AND status = '1'";
    $select_sql = $select_sql." ORDER BY attname";

    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    $colors = array();
    $colors[''] = 'Select colour below:';
    
    foreach($query->result() as $row)
    {
      $colors[$row->attvalue] = $row->attname;
    }
    return ($colors);

  }

  function select_color_by_name($attname)
  {
    $select_sql = "SELECT attid, attname, attvalue FROM attributes";
    $select_sql = $select_sql." WHERE atttype = 'Colour' AND status = '1'";
    $select_sql = $select_sql." AND attname ='".ucwords(strtolower($attname))."' LIMIT 1";
    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->attid;
    } else {
      return FALSE;
    }
  }



  function insert_color($attname)
  {
    $attid = $this->next_attid();
    if ($attid) {
      $insert_sql="INSERT INTO attributes (attid,attname,attvalue,atttype,status) VALUES ("
      .$attid.",'".mysql_real_escape_string(ucwords(strtolower($attname)))."','"
      .mysql_real_escape_string(ucwords(strtolower($attname)))
      ."','Colour','1')";
      $query = $this->db->query($insert_sql);
      if ($this->db->affected_rows() > 0) {
        return TRUE;
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }

  function next_attid()
  {
    $select_sql="SELECT max(attid)+1 as next_id FROM attributes";
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->next_id;
    } else {
      return FALSE;
    }
  }

  function update_color($attid, $attname)
  {
    $update_sql="UPDATE attributes SET attname='".mysql_real_escape_string(ucwords(strtolower($attname)))
    ."',attvalue='".mysql_real_escape_string(ucwords(strtolower($attname)))
    ."' WHERE attid = ".$attid;
    $query = $this->db->query($update_sql);
    if ($this->db->affected_rows() > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  /**
   *
   * Enter description here ...
   * @param unknown_type $attid
   * @param unknown_type $status
   * @return boolean
   * @deprecated
   */
  function status($attid, $status)
  {
    $update_sql="UPDATE attibutes SET status='".mysql_real_escape_string($status)
    ."' WHERE attid = ".$attid;
    $query = $this->db->query($update_sql);
    if ($this->db->affected_rows() > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

}
