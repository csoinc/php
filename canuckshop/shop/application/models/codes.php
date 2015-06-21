<?php

class Codes extends CI_Model {

  function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
  }

  function role_uniform_read($uniforms)
  {
    $select_sql = "SELECT codevalue FROM codes WHERE codetype = 'RoleUniform' AND codename = 'Admin Read'";
    $select_sql = $select_sql." AND codevalue = '".$uniforms."'";

    log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function role_uniform_update($uniforms)
  {
    $select_sql = "SELECT codevalue FROM codes WHERE codetype = 'RoleUniform' AND codename = 'Admin Update'";
    $select_sql = $select_sql." AND codevalue = '".$uniforms."'";

    log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function role_uniform_no($uniforms)
  {
    $select_sql = "SELECT codevalue FROM codes WHERE codetype = 'RoleUniform' AND codename = 'No'";
    $select_sql = $select_sql." AND codevalue = '".$uniforms."'";

    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function role_stock_read($stocks)
  {
    $select_sql = "SELECT codevalue FROM codes WHERE codetype = 'RoleStock' AND codename = 'Admin Read'";
    $select_sql = $select_sql." AND codevalue = '".$stocks."'";

    log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function role_stock_update($stocks)
  {
    $select_sql = "SELECT codevalue FROM codes WHERE codetype = 'RoleStock' AND codename = 'Admin Update'";
    $select_sql = $select_sql." AND codevalue = '".$stocks."'";

    log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function role_stock_no($stocks)
  {
    $select_sql = "SELECT codevalue FROM codes WHERE codetype = 'RoleStock' AND codename = 'No'";
    $select_sql = $select_sql." AND codevalue = '".$stocks."'";

    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function role_order_read($orders)
  {
    $select_sql = "SELECT codevalue FROM codes WHERE codetype = 'RoleOrder' AND codename = 'Admin Read'";
    $select_sql = $select_sql." AND codevalue = '".$orders."'";

    log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function role_order_update($orders)
  {
    $select_sql = "SELECT codevalue FROM codes WHERE codetype = 'RoleOrder' AND codename = 'Admin Update'";
    $select_sql = $select_sql." AND codevalue = '".$orders."'";

    log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function role_order_no($orders)
  {
    $select_sql = "SELECT codevalue FROM codes WHERE codetype = 'RoleOrder' AND codename = 'No'";
    $select_sql = $select_sql." AND codevalue = '".$orders."'";

    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function role_order_owner_read($orders)
  {
    $select_sql = "SELECT codevalue FROM codes WHERE codetype = 'RoleOrder' AND codename = 'Owner Read'";
    $select_sql = $select_sql." AND codevalue = '".$orders."'";

    log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function role_order_owner_update($orders)
  {
    $select_sql = "SELECT codevalue FROM codes WHERE codetype = 'RoleOrder' AND codename = 'Owner Update'";
    $select_sql = $select_sql." AND codevalue = '".$orders."'";

    log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function select_code_stock()
  {
    $select_sql = "SELECT codename, codevalue FROM codes";
    $select_sql = $select_sql." WHERE codetype = 'Stocks' AND status = '1'";
    $select_sql = $select_sql." ORDER BY codename";

    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    $codes = array();
    $codes[''] = 'Select below:';

    foreach($query->result() as $row)
    {
      $codes[$row->codename] = $row->codevalue;
    }
    return ($codes);

  }

  function select_role_stock()
  {
    $select_sql = "SELECT codename, codevalue FROM codes";
    $select_sql = $select_sql." WHERE codetype = 'RoleStock' AND status = '1'";
    $select_sql = $select_sql." ORDER BY codename";

    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    $codes = array();
    $codes[''] = 'Select below:';

    foreach($query->result() as $row)
    {
      $codes[$row->codename] = $row->codevalue;
    }
    return ($codes);

  }

  function select_role_order()
  {
    $select_sql = "SELECT codename, codevalue FROM codes";
    $select_sql = $select_sql." WHERE codetype = 'RoleOrder' AND status = '1'";
    $select_sql = $select_sql." ORDER BY codename";

    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    $codes = array();
    $codes[''] = 'Select below:';

    foreach($query->result() as $row)
    {
      $codes[$row->codename] = $row->codevalue;
    }
    return ($codes);

  }

  function select_role_uniform()
  {
    $select_sql = "SELECT codename, codevalue FROM codes";
    $select_sql = $select_sql." WHERE codetype = 'RoleUniform' AND status = '1'";
    $select_sql = $select_sql." ORDER BY codename";

    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    $codes = array();
    $codes[''] = 'Select below:';

    foreach($query->result() as $row)
    {
      $codes[$row->codename] = $row->codevalue;
    }
    return ($codes);

  }

  function select_search_code_order_status()
  {
    $select_sql = "SELECT codename, codevalue FROM codes";
    $select_sql = $select_sql." WHERE codetype = 'OrderStatus' AND status = 1";
    $select_sql = $select_sql." ORDER BY codeid";
  
    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    $codes = array();
    $codes[''] = 'Search All';
  
    foreach($query->result() as $row)
    {
      $codes[$row->codevalue] = $row->codename;
    }
    return ($codes);
  
  }
  
  function select_code_order_status()
  {
    $select_sql = "SELECT codename, codevalue FROM codes";
    $select_sql = $select_sql." WHERE codetype = 'OrderStatus' AND status = 1";
    $select_sql = $select_sql." ORDER BY codeid";

    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    $codes = array();
    //$codes[''] = 'Please Select';

    foreach($query->result() as $row)
    {
      $codes[$row->codevalue] = $row->codename;
    }
    return ($codes);

  }

  function select_codes_by_codetype($codetype)
  {
    $select_sql = "SELECT codename, codevalue FROM codes";
    $select_sql = $select_sql." WHERE status = 1 AND codetype = '".$codetype."'";
    $select_sql = $select_sql." ORDER BY codeid";
  
    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    $codes = array();
  
    foreach($query->result() as $row)
    {
      $codes[$row->codevalue] = $row->codename;
    }
    return ($codes);
  
  }
  
  function select_name_by_value($codevalue, $codetype)
  {
    $select_sql = "SELECT codename FROM codes";
    $select_sql = $select_sql." WHERE status = 1 AND codetype = '".$codetype."'";
    $select_sql = $select_sql." AND codevalue = '".$codevalue."'";
    $select_sql = $select_sql." LIMIT 1";

    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->codename;
    } else {
      return FALSE;
    }
  }

  function select_value_by_name($codename, $codetype)
  {
    $select_sql = "SELECT codevalue FROM codes";
    $select_sql = $select_sql." WHERE status = 1 AND codetype = '".$codetype."'";
    $select_sql = $select_sql." AND codename = '".$codename."'";
    $select_sql = $select_sql." LIMIT 1";
  
    //log_message('debug', $select_sql);
    $query = $this->db->query($select_sql);
    if ($query->num_rows() > 0)
    {
      $row = $query->row();
      return $row->codevalue;
    } else {
      return FALSE;
    }
  }
  
  function select_query_sort()
  {
    $codes = array();
    $codes['ASC'] = 'Sort Increasing';
    $codes['DESC'] = 'Sort Decreasing';
    return ($codes);
  }
  
  
}
