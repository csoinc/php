<?php

class Feedbacks extends CI_Model {

  function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
  }

  function select_feedback_by_order($orderid)
  {
    $select_sql = "SELECT * FROM feedback";
    $select_sql = $select_sql." WHERE orderid = ".$orderid;
    $select_sql = $select_sql." ORDER BY fbid DESC";

    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      return $query;
    } else {
      return FALSE;
    }
  }
 
  function insert_feedback($feedback, $orderid, $createdby)
  {
    $feedbackid = $this->next_feedbackid();
    if ($feedbackid) {
      $insert_sql="INSERT INTO feedback (fbid,feedback,orderid,createdby,createddate) VALUES ("
      .$feedbackid.",'".$feedback."',".$orderid.",'".$createdby."',now())";
      $query = $this->db->query($insert_sql);
      if ($this->db->affected_rows() > 0) {
        return $feedbackid;
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }

  function next_feedbackid()
  {
    $select_sql="SELECT MAX(fbid)+1 AS next_id FROM feedback";
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      if (isset($row->next_id) && $row->next_id != '') 
        return $row->next_id;
      else 
        return 1;
    } else {
      return 1;
    }
  }

}
