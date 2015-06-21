<?php
class Customerorderitems extends CI_Controller {
  private $title;
  private $trip;

  public function __construct()
  {
    parent::__construct();
    $this->load->library('template');
    $this->load->library('session');
    $this->load->library('pagination');
    $this->load->library('table');
    $this->load->library('form_validation');
    $this->load->library('upload');

    $this->load->helper(array('form', 'url'));
    $this->load->language('properties');

    $this->load->model('orders');
    $this->load->model('clients');
    $this->load->model('styles');
    $this->load->model('items');
    $this->load->model('stocks');
    $this->load->model('codes');
    $this->load->model('artworks');
    $this->load->model('feedbacks');

    $this->title = "Customer Order Items";
    $this->trip = '<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Customer';
    $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Orders';
    $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Items';
  }

  public function select($clientid = 0, $orderid = 0, $message = '')
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_owner_read($uf_orders) || $this->codes->role_order_owner_update($uf_orders)) {
        $data['clientid'] = $clientid;
        $data['orderid'] = $orderid;

        $order = $this->orders->select_order_by_id($orderid);
        $data['order'] = $order;
        $data['orderstatus_list'] = $this->codes->select_codes_by_codetype('OrderStatus');
        $data['orderstatus_options'] = 'id="colours" size="1" disabled="disabled"';

        $data['artworkstatus_list'] = $this->codes->select_codes_by_codetype('CustomerArtworkType');
        $data['artworkstatus_options'] = 'id="artworkstatus" size="1"';
        $data['artworksource_list'] = $this->codes->select_codes_by_codetype('ArtworkSource');
        $data['artworksource_options'] = 'id="artworksource" size="1"';

        $data['feedbacks'] = $this->feedbacks->select_feedback_by_order($orderid);

        $this->template->write_view('header', 'common/customer_header');
        $this->template->write('message', $message);
        $this->template->write('title', $this->title.' : '.$order->contact.' ('.$order->name.')');
        $this->template->write('trip', $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;'.$order->contact.' ('.$order->name.')');

        $data['stocks'] = $this->stocks->select_stocks_by_order($orderid);
        $data['artworks'] = $this->artworks->select_customer_artworks_by_order($orderid);
        
        
        $this->template->write_view('content', 'customer_order_items', $data);

        // Write to $content
        $this->template->write_view('footer', 'common/footer');
        // Render the template
        $this->template->render();
      }
      else {
        redirect('/accounts/index/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function form_update_order($clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $itemcode = '', $styleid = 0, $message = '')
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_owner_update($uf_orders)) {
        $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Add/Edit Items/Artworks';

        $order = $this->orders->select_order_by_id($orderid);
        $data['order'] = $order;

        if ($order->orderstatus != '0' && $order->orderstatus != '1' ) {
          $this->select($clientid, $orderid, INFO_UNAUTHORISED);
        } else {
          $data['clientid'] = $clientid;
          $data['orderid'] = $orderid;
          if ($clientid != 0) $data['client'] = $this->clients->select($clientid);
          $data['stocks_edit'] = $stocks_edit;
          $data['artworks_edit'] = $artworks_edit;

          $data['orderstatus_list'] = $this->codes->select_codes_by_codetype('OrderStatus');
          $data['orderstatus_options'] = 'id="colours" size="1" disabled="disabled"';

          $data['items_list'] = $this->items->select_code_items();
          $data['items_options'] = 'id="items" size="1" disabled="disabled"';

          $data['artworkstatus_list'] = $this->codes->select_codes_by_codetype('CustomerArtworkType');
          $data['artworkstatus_options'] = 'id="artworkstatus" size="1"';
          $data['artworksource_list'] = $this->codes->select_codes_by_codetype('ArtworkSource');
          $data['artworksource_options'] = 'id="artworksource" size="1"';


          if ($itemcode != '') {
            $data['itemcode'] = $itemcode;
            $data['styleid'] = '';
            $data['styles_list'] = $this->styles->select_code_styles($itemcode);
            $data['styles_options'] = 'id="items" size="1" onchange="this.form.submit();"';
          } else {
            $data['itemcode'] = '';
            $data['styleid'] = '';
          }

          if ($stocks_edit != 0 || $styleid != 0) {
            $data['frontlogopos_list'] = $this->codes->select_codes_by_codetype('FrontLogoPos');
            $data['frontlogopos_options'] = 'id="frontlogopos" size="1"';

            $data['frontnumpos_list'] = $this->codes->select_codes_by_codetype('FrontNumPos');
            $data['frontnumpos_options'] = 'id="frontnumpos" size="1"';

            $data['numsize_list'] = $this->codes->select_codes_by_codetype('NumSize');
            $data['numsize_options'] = 'size="4"';

            $data['artworkcolour_list'] = $this->codes->select_codes_by_codetype('ArtworkColour');
            $data['artworkcolour_options'] = 'size="1"';

            $data['rearlogopos_list'] = $this->codes->select_codes_by_codetype('RearLogoPos');
            $data['rearlogopos_options'] = 'id="rearlogopos" size="1"';

            $data['rearnumpos_list'] = $this->codes->select_codes_by_codetype('RearNumPos');
            $data['rearnumpos_options'] = 'id="rearnumpos" size="1"';

            $data['shortlogopos_list'] = $this->codes->select_codes_by_codetype('ShortLogoPos');
            $data['shortlogopos_options'] = 'id="shortlogopos" size="1"';

            $data['shortnumpos_list'] = $this->codes->select_codes_by_codetype('ShortNumPos');
            $data['shortnumpos_options'] = 'id="rearnumpos" size="1"';

            $data['items_edit_list'] = $this->items->select_code_items();
            $data['items_edit_options'] = 'id="items" size="1" onchange="this.form.submit();"';

            if ($stocks_edit != 0) {
              $stock = $this->stocks->select_stock_by_id($stocks_edit);
              $data['styles_edit_list'] = $this->styles->select_code_styles($stock->itemcode);
              $data['styles_edit_options'] = 'id="items" size="1" onchange="this.form.submit();"';
            }
            if ($styleid != 0) {
              $data['styleid'] = $styleid;
              $data['frontlogopos'] = '1';
              $data['frontnumpos'] = '1';

              $data['frontnumsize'] = '';

              $data['frontlogocolor'] = '';
              $data['frontlogotrimcolor'] = '';
              $data['frontnumcolor'] = '';
              $data['frontnumtrimcolor'] = '';

              $data['rearlogopos'] = '1';

              $data['rearnumpos'] = '1';

              $data['rearnumsize'] = '';
              $data['rearlogocolor'] = '';
              $data['rearlogotrimcolor'] = '';
              $data['rearnumcolor'] = '';
              $data['rearnumtrimcolor'] = '';


              $data['shortlogopos'] = '1';

              $data['shortnumpos'] = '1';
            }
          }

          $this->template->write_view('header', 'common/customer_header');
          $this->template->write('message', $message);
          $this->template->write('title', $this->title.' : '.$order->contact.' ('.$order->name.')');
          $this->template->write('trip', $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;'.$order->contact.' ('.$order->name.')');

          $data['order_stocks'] = $this->stocks->select_stocks_by_order($orderid);
          $data['order_artworks'] = $this->artworks->select_artworks_by_order($orderid);

          $this->template->write_view('content', 'customer_order_items_form', $data);

          // Write to $content
          $this->template->write_view('footer', 'common/footer');
          // Render the template
          $this->template->render();
        }
      } else {
        redirect('/accounts/index/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function form_insert_order($clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $itemcode = '', $styleid = 0, $message = '')
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');
  
      if ($this->codes->role_order_owner_update($uf_orders)) {
        $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Add/Edit Items/Artworks';
  
        $order = $this->orders->select_order_by_id($orderid);
        $data['order'] = $order;
  
        if ($order->orderstatus != '0' && $order->orderstatus != '1' ) {
          $this->select($clientid, $orderid, INFO_UNAUTHORISED);
        } else {
          $data['clientid'] = $clientid;
          $data['orderid'] = $orderid;
          if ($clientid != 0) $data['client'] = $this->clients->select($clientid);
          $data['stocks_edit'] = $stocks_edit;
          $data['artworks_edit'] = $artworks_edit;
  
          $data['orderstatus_list'] = $this->codes->select_codes_by_codetype('OrderStatus');
          $data['orderstatus_options'] = 'id="colours" size="1" disabled="disabled"';
  
          $data['items_list'] = $this->items->select_code_items();
          $data['items_options'] = 'id="items" size="1" disabled="disabled"';
  
          $data['artworkstatus_list'] = $this->codes->select_codes_by_codetype('CustomerArtworkType');
          $data['artworkstatus_options'] = 'id="artworkstatus" size="1"';
          $data['artworksource_list'] = $this->codes->select_codes_by_codetype('ArtworkSource');
          $data['artworksource_options'] = 'id="artworksource" size="1"';
  
  
          if ($itemcode != '') {
            $data['itemcode'] = $itemcode;
            $data['styleid'] = '';
            $data['styles_list'] = $this->styles->select_code_styles($itemcode);
            $data['styles_options'] = 'id="items" size="1" onchange="this.form.submit();"';
          } else {
            $data['itemcode'] = '';
            $data['styleid'] = '';
          }
  
          if ($stocks_edit != 0 || $styleid != 0) {
            $data['frontlogopos_list'] = $this->codes->select_codes_by_codetype('FrontLogoPos');
            $data['frontlogopos_options'] = 'id="frontlogopos" size="1"';
  
            $data['frontnumpos_list'] = $this->codes->select_codes_by_codetype('FrontNumPos');
            $data['frontnumpos_options'] = 'id="frontnumpos" size="1"';
  
            $data['numsize_list'] = $this->codes->select_codes_by_codetype('NumSize');
            $data['numsize_options'] = 'size="4"';
  
            $data['artworkcolour_list'] = $this->codes->select_codes_by_codetype('ArtworkColour');
            $data['artworkcolour_options'] = 'size="1"';
  
            $data['rearlogopos_list'] = $this->codes->select_codes_by_codetype('RearLogoPos');
            $data['rearlogopos_options'] = 'id="rearlogopos" size="1"';
  
            $data['rearnumpos_list'] = $this->codes->select_codes_by_codetype('RearNumPos');
            $data['rearnumpos_options'] = 'id="rearnumpos" size="1"';
  
            $data['shortlogopos_list'] = $this->codes->select_codes_by_codetype('ShortLogoPos');
            $data['shortlogopos_options'] = 'id="shortlogopos" size="1"';
  
            $data['shortnumpos_list'] = $this->codes->select_codes_by_codetype('ShortNumPos');
            $data['shortnumpos_options'] = 'id="rearnumpos" size="1"';
  
            $data['items_edit_list'] = $this->items->select_code_items();
            $data['items_edit_options'] = 'id="items" size="1" onchange="this.form.submit();"';
  
            if ($stocks_edit != 0) {
              $stock = $this->stocks->select_stock_by_id($stocks_edit);
              $data['styles_edit_list'] = $this->styles->select_code_styles($stock->itemcode);
              $data['styles_edit_options'] = 'id="items" size="1" onchange="this.form.submit();"';
            }
            if ($styleid != 0) {
              $data['styleid'] = $styleid;
              $data['frontlogopos'] = '1';
              $data['frontnumpos'] = '1';
  
              $data['frontnumsize'] = '';
  
              $data['frontlogocolor'] = '';
              $data['frontlogotrimcolor'] = '';
              $data['frontnumcolor'] = '';
              $data['frontnumtrimcolor'] = '';
  
              $data['rearlogopos'] = '1';
  
              $data['rearnumpos'] = '1';
  
              $data['rearnumsize'] = '';
              $data['rearlogocolor'] = '';
              $data['rearlogotrimcolor'] = '';
              $data['rearnumcolor'] = '';
              $data['rearnumtrimcolor'] = '';
  
  
              $data['shortlogopos'] = '1';
  
              $data['shortnumpos'] = '1';
            }
          }
  
          $this->template->write_view('header', 'common/customer_header');
          $this->template->write('message', $message);
          $this->template->write('title', $this->title.' : '.$order->contact.' ('.$order->name.')');
          $this->template->write('trip', $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;'.$order->contact.' ('.$order->name.')');
  
          $data['order_stocks'] = $this->stocks->select_stocks_by_order($orderid);
          $data['order_artworks'] = $this->artworks->select_artworks_by_order($orderid);
  
          $this->template->write_view('content', 'customer_order_items_form', $data);
  
          // Write to $content
          $this->template->write_view('footer', 'common/footer');
          // Render the template
          $this->template->render();
        }
      } else {
        redirect('/accounts/index/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }
  
  public function select_item($clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $itemcode = 0, $message = '')
  {
    $this->form_update_order($clientid,$orderid,$stocks_edit,$artworks_edit,$itemcode,0);
  }

  public function select_update_item($clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $items_start = 0, $message = '')
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_owner_update($uf_orders)) {
        $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Select Item';

        $data['items_start'] = $items_start;
        $data['clientid'] = $clientid;
        $data['orderid'] = $orderid;
        $data['client'] = $this->clients->select($clientid);
        $data['stocks_edit'] = $stocks_edit;
        $data['artworks_edit'] = $artworks_edit;

        $order = $this->orders->select_order_by_id($orderid);
        $data['order'] = $order;

        $this->template->write_view('header', 'common/customer_header');
        $this->template->write('message', $message);
        $this->template->write('title', $this->title.' : '.$order->contact.' ('.$order->name.')');
        $this->template->write('trip', $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;'.$order->contact.' ('.$order->name.')');
        if ($data['rows'] = $this->items->select_online_items()) {
          $config['base_url'] = base_url().'/customerorderitems/form_select_item/'.$clientid.'/'.$orderid.'/'.$stocks_edit.'/'.$artworks_edit.'/';
          $config['total_rows'] = $data['rows']->num_rows();
          $config['per_page'] = 10;
          $config['full_tag_open'] = '<p>';
          $config['full_tag_close'] = '</p>';
          $config["uri_segment"] = 7;
          $config['page_query_string'] = FALSE;
          $this->pagination->initialize($config);

          $table_settings=array(
                        'table_open' => '<table class="zebraTable" width="750">',
                        'heading_row_start' => '<tr class="rowEven">',
                        'row_start' => '<tr class="rowOdd">',
                        'row_alt_start' => '<tr class="rowEven">'
          );
          $this->table->set_template($table_settings); //apply the settings

          $this->table->set_heading('Item Code', 'Item Name', 'Online Store', 'Actions');

          $items_end = $items_start + 10;
          $r = 0;

          foreach($data['rows']->result() as $row)
          {
            if ($r >= $items_start && $r < $items_end) {
              $check_col = sprintf('<a href="%s/customerorderitems/check_item/%d/%s/%s" title="Check Item in Online Store Window" target=_store>Check this item in online store Window</a>'
              ,WEB_CONTEXT,$clientid,$orderid,$row->itemcode,WEB_CONTEXT);
              $select_col = sprintf('<a href="%s/customerorderitems/update_item/%d/%s/%d/%d/%s" title="Update With This Item"><img src="%s/images/buttons/button_sel_buy.gif" /></a>',
                WEB_CONTEXT, $clientid, $orderid, $stocks_edit, $artworks_edit, (string)$row->itemcode, WEB_CONTEXT);
              $this->table->add_row($row->itemcode, $row->itemname, $check_col, $select_col);
            }
            $r++;
          }
          $data['select_item_table'] = $this->table->generate();
        }

        $this->template->write_view('content', 'customer_order_items_selection', $data);

        // Write to $content
        $this->template->write_view('footer', 'common/footer');
        // Render the template
        $this->template->render();
      } else {
        redirect('/accounts/index/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function select_add_item($clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $items_start = 0, $message = '')
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');
  
      if ($this->codes->role_order_owner_update($uf_orders)) {
        $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Select Item';
  
        $data['items_start'] = $items_start;
        $data['clientid'] = $clientid;
        $data['orderid'] = $orderid;
        $data['client'] = $this->clients->select($clientid);
        $data['stocks_edit'] = $stocks_edit;
        $data['artworks_edit'] = $artworks_edit;
  
        $order = $this->orders->select_order_by_id($orderid);
        $data['order'] = $order;
  
        $this->template->write_view('header', 'common/customer_header');
        $this->template->write('message', $message);
        $this->template->write('title', $this->title.' : '.$order->contact.' ('.$order->name.')');
        $this->template->write('trip', $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;'.$order->contact.' ('.$order->name.')');
        if ($data['rows'] = $this->items->select_online_items()) {
          $config['base_url'] = base_url().'/customerorderitems/form_select_item/'.$clientid.'/'.$orderid.'/'.$stocks_edit.'/'.$artworks_edit.'/';
          $config['total_rows'] = $data['rows']->num_rows();
          $config['per_page'] = 10;
          $config['full_tag_open'] = '<p>';
          $config['full_tag_close'] = '</p>';
          $config["uri_segment"] = 7;
          $config['page_query_string'] = FALSE;
          $this->pagination->initialize($config);
  
          $table_settings=array(
              'table_open' => '<table class="zebraTable" width="750">',
              'heading_row_start' => '<tr class="rowEven">',
              'row_start' => '<tr class="rowOdd">',
              'row_alt_start' => '<tr class="rowEven">'
          );
          $this->table->set_template($table_settings); //apply the settings
  
          $this->table->set_heading('Item Code', 'Item Name', 'Online Store', 'Actions');
  
          $items_end = $items_start + 10;
          $r = 0;
  
          foreach($data['rows']->result() as $row)
          {
            if ($r >= $items_start && $r < $items_end) {
              $check_col = sprintf('<a href="%s/customerorderitems/check_item/%d/%s/%s" title="Check Item in Online Store Window" target=_store>Check this item in online store Window</a>'
                  ,WEB_CONTEXT,$clientid,$orderid,$row->itemcode,WEB_CONTEXT);
              $select_col = sprintf('<a href="%s/customerorderitems/select_add_item/%d/%s/%d/%d/%s" title="Select This Item"><img src="%s/images/buttons/button_sel_buy.gif" /></a>',
                   WEB_CONTEXT, $clientid, $orderid, $stocks_edit, $artworks_edit, (string)$row->itemcode, WEB_CONTEXT);
              $this->table->add_row($row->itemcode, $row->itemname, $check_col, $select_col);
            }
            $r++;
          }
          $data['select_item_table'] = $this->table->generate();
        }
  
        $this->template->write_view('content', 'customer_order_items_selection', $data);
  
        // Write to $content
        $this->template->write_view('footer', 'common/footer');
        // Render the template
        $this->template->render();
      } else {
        redirect('/accounts/index/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }
  
  public function form_update_item($clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $items_start = 0, $message = '')
  {
    $this->select_update_item($clientid,$orderid,$stocks_edit,$artworks_edit,$items_start);
  }
  public function form_add_item($clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $items_start = 0, $message = '')
  {
    $this->form_add_item($clientid,$orderid,$stocks_edit,$artworks_edit,$items_start);
  }

  public function select_update_style($clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $itemcode = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    //$this->form_validation->set_rules('itemcode', 'Itemcode', 'trim|required|min_length[3]|max_length[45]|xss_clean');
    $this->form_validation->set_rules('styleid', 'StyleId', 'trim|required|min_length[3]|max_length[45]|xss_clean');
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');
      if ($this->codes->role_order_owner_update($uf_orders)) {
        if ($this->form_validation->run() == TRUE)
        {
          $styleid = $this->input->get_post('styleid',TRUE);
          $this->form_update_order($clientid,$orderid,$stocks_edit,$artworks_edit,$itemcode,$styleid);
        } else {
          $this->form_update_order($clientid,$orderid,$stocks_edit,$artworks_edit,$itemcode,0,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/accounts/index/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function select_add_style($clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $itemcode = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    //$this->form_validation->set_rules('itemcode', 'Itemcode', 'trim|required|min_length[3]|max_length[45]|xss_clean');
    $this->form_validation->set_rules('styleid', 'StyleId', 'trim|required|min_length[3]|max_length[45]|xss_clean');
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');
      if ($this->codes->role_order_owner_update($uf_orders)) {
        if ($this->form_validation->run() == TRUE)
        {
          $styleid = $this->input->get_post('styleid',TRUE);
          $this->form_insert_order($clientid,$orderid,$stocks_edit,$artworks_edit,$itemcode,$styleid);
        } else {
          $this->form_insert_order($clientid,$orderid,$stocks_edit,$artworks_edit,$itemcode,0,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/accounts/index/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }
  
  public function update_customer($clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[8]|max_length[200]|xss_clean');
    $this->form_validation->set_rules('contact', 'Contact', 'trim|required|min_length[3]|max_length[50]|xss_clean');
    $this->form_validation->set_rules('email', 'Email', 'trim|email|max_length[100]|xss_clean');
    $this->form_validation->set_rules('telephone', 'Telephone', 'trim|required|min_length[12]|max_length[12]|callback_phone_check|xss_clean');
    $this->form_validation->set_rules('cellphone', 'Cellphone', 'trim|max_length[12]|callback_phone_check|xss_clean');
    //$this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[8]|max_length[100]|xss_clean');
    //$this->form_validation->set_rules('zipcode', 'Zipcode', 'trim|required|min_length[3]|max_length[45]|xss_clean');
    $this->form_validation->set_rules('shippingaddr', 'ShippingAddr', 'trim|required|min_length[8]|max_length[100]|xss_clean');
    $this->form_validation->set_rules('shippingzip', 'ShippingZip', 'trim|required|min_length[3]|max_length[45]|xss_clean');
    $this->form_validation->set_rules('orderdate', 'Requireddate', 'trim|required|date|xss_clean');
    $this->form_validation->set_rules('requireddate', 'Requireddate', 'trim|required|date|xss_clean');
    //$this->form_validation->set_rules('payment', 'Payment', 'trim|max_length[45]|xss_clean');
    //$this->form_validation->set_rules('expdate', 'Expdate', 'trim|max_length[45]|xss_clean');
    $this->form_validation->set_rules('comments', 'Comments', 'trim|max_length[200]|xss_clean');

    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_orders');
      if ($this->codes->role_order_owner_update($uf_uniforms)) {
        if ($this->form_validation->run() == TRUE)
        {
          $name = $this->input->get_post('name',TRUE);
          $contact = $this->input->get_post('contact',TRUE);
          $email = $this->input->get_post('email',TRUE);
          $telephone = $this->input->get_post('telephone',TRUE);
          $cellphone = $this->input->get_post('cellphone',TRUE);
          //$address = $this->input->get_post('address',TRUE);
          //$zipcode = $this->input->get_post('zipcode',TRUE);
          $shippingaddr = $this->input->get_post('shippingaddr',TRUE);
          $shippingzip = $this->input->get_post('shippingzip',TRUE);
          $orderdate = $this->input->get_post('orderdate',TRUE);
          $requireddate = $this->input->get_post('requireddate',TRUE);
          //$payment = $this->input->get_post('payment',TRUE);
          //$expdate = $this->input->get_post('expdate',TRUE);
          $comments = $this->input->get_post('comments',TRUE);
          $createdby = $this->session->userdata('uf_username');

          if ($this->orders->update_customer($orderid,$clientid,$name,$contact,$email,$telephone,$cellphone,$shippingaddr,$shippingzip,$orderdate,$requireddate,$comments,$createdby)) {
            $this->form_update_order($clientid,$orderid,$stocks_edit,$artworks_edit,'',0,INFO_SUCCESS);
          } else {
            $this->form_update_order($clientid,$orderid,$stocks_edit,$artworks_edit,'',0,INFO_UNCHANGED);
          }
        } else {
          $this->form_update_order($clientid,$orderid,$stocks_edit,$artworks_edit.'',0,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/accounts/index/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }

  }

  public function insert_stock($clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $itemcode = '', $styleid = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('description', 'Description', 'trim|max_length[250]|xss_clean');
    $this->form_validation->set_rules('frontlogopos', 'FrontLogoPos', 'trim|required|xss_clean');
    $this->form_validation->set_rules('frontlogoname', 'FrontLogoName', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('frontlogocolor', 'FrontLogoColor', 'trim|required|min_length[2]|max_length[50]|xss_clean');
    $this->form_validation->set_rules('frontlogotrimcolor', 'FrontLogoTrimColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('frontnumpos', 'FrontNumPos', 'trim|required|xss_clean');
    $this->form_validation->set_rules('frontnumsize', 'FrontNumSize', 'trim|required|xss_clean');
    $this->form_validation->set_rules('frontnumcolor', 'FrontNumColor', 'trim|required|min_length[2]|max_length[50]|xss_clean');
    $this->form_validation->set_rules('frontnumtrimcolor', 'FrontNumTrimColor', 'trim|max_length[50]|xss_clean');

    $this->form_validation->set_rules('rearlogopos', 'RearLogoPos', 'trim|required|xss_clean');
    $this->form_validation->set_rules('rearlogoname', 'RearLogoName', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('rearlogocolor', 'RearLogoColor', 'trim|required|min_length[2]|max_length[50]|xss_clean');
    $this->form_validation->set_rules('rearlogotrimcolor', 'RearLogoTrimColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('rearnumpos', 'RearNumPos', 'trim|required|xss_clean');
    $this->form_validation->set_rules('rearnumsize', 'RearNumSize', 'trim|required|xss_clean');
    $this->form_validation->set_rules('rearnumcolor', 'RearNumColor', 'trim|required|min_length[2]|max_length[50]|xss_clean');
    $this->form_validation->set_rules('rearnumtrimcolor', 'RearNumTrimColor', 'trim|max_length[50]|xss_clean');

    $this->form_validation->set_rules('shortlogopos', 'ShortLogoPos', 'trim|required|xss_clean');
    $this->form_validation->set_rules('shortnumpos', 'ShortNumPos', 'trim|required|xss_clean');

    $this->form_validation->set_rules('xsmall', 'Xsmall', 'trim|numeric|xss_clean');
    $this->form_validation->set_rules('xsmallnumbers', 'XsmallNumbers', 'trim|max_length[250]|xss_clean');

    $this->form_validation->set_rules('small', 'Small', 'trim|numeric|xss_clean');
    $this->form_validation->set_rules('smallnumbers', 'SmallNumbers', 'trim|max_length[250]|xss_clean');

    $this->form_validation->set_rules('medium', 'Medium', 'trim|numeric|xss_clean');
    $this->form_validation->set_rules('mediumnumbers', 'MediumNumbers', 'trim|max_length[250]|xss_clean');

    $this->form_validation->set_rules('large', 'Large', 'trim|numeric|xss_clean');
    $this->form_validation->set_rules('largenumbers', 'LargeNumbers', 'trim|max_length[250]|xss_clean');

    $this->form_validation->set_rules('xlarge', 'Xlarge', 'trim|numeric|xss_clean');
    $this->form_validation->set_rules('xlargenumbers', 'XlargeNumbers', 'trim|max_length[250]|xss_clean');

    $this->form_validation->set_rules('xxlarge', 'Xxlarge', 'trim|numeric|xss_clean');
    $this->form_validation->set_rules('xxlargenumbers', 'XxlargeNumbers', 'trim|max_length[250]|xss_clean');
    $this->form_validation->set_rules('comments', 'Comments', 'trim|max_length[250]|xss_clean');

    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_owner_update($uf_orders)) {
        if ($this->form_validation->run() == TRUE)
        {
          $description = $this->input->get_post('description',TRUE);

          $frontlogopos = $this->input->get_post('frontlogopos',TRUE);
          $frontlogoname = $this->input->get_post('frontlogoname',TRUE);
          $frontlogocolor = $this->input->get_post('frontlogocolor',TRUE);
          $frontlogotrimcolor = $this->input->get_post('frontlogotrimcolor',TRUE);
          $frontnumpos = $this->input->get_post('frontnumpos',TRUE);
          $frontnumsize = $this->input->get_post('frontnumsize',TRUE);
          $frontnumcolor = $this->input->get_post('frontnumcolor',TRUE);
          $frontnumtrimcolor = $this->input->get_post('frontnumtrimcolor',TRUE);

          $rearlogopos = $this->input->get_post('rearlogopos',TRUE);
          $rearlogoname = $this->input->get_post('rearlogoname',TRUE);
          $rearlogocolor = $this->input->get_post('rearlogocolor',TRUE);
          $rearlogotrimcolor = $this->input->get_post('rearlogotrimcolor',TRUE);
          $rearnumpos = $this->input->get_post('rearnumpos',TRUE);
          $rearnumsize = $this->input->get_post('rearnumsize',TRUE);
          $rearnumcolor = $this->input->get_post('rearnumcolor',TRUE);
          $rearnumtrimcolor = $this->input->get_post('rearnumtrimcolor',TRUE);

          $shortlogopos = $this->input->get_post('shortlogopos',TRUE);
          $shortnumpos = $this->input->get_post('shortnumpos',TRUE);

          $xsmallnumbers = $this->input->get_post('xsmallnumbers',TRUE);
          $smallnumbers = $this->input->get_post('smallnumbers',TRUE);
          $mediumnumbers = $this->input->get_post('mediumnumbers',TRUE);
          $largenumbers = $this->input->get_post('largenumbers',TRUE);
          $xlargenumbers = $this->input->get_post('xlargenumbers',TRUE);
          $xxlargenumbers = $this->input->get_post('xxlargenumbers',TRUE);

          $xsmall = $this->input->get_post('xsmall',TRUE) == ''? 0 : abs($this->input->get_post('xsmall',TRUE)) * -1;
          $small = $this->input->get_post('small',TRUE) == ''? 0 : abs($this->input->get_post('small',TRUE)) * -1;
          $medium = $this->input->get_post('medium',TRUE) == ''? 0 : abs($this->input->get_post('medium',TRUE)) * -1;
          $large = $this->input->get_post('large',TRUE) == ''? 0 : abs($this->input->get_post('large',TRUE)) * -1;
          $xlarge = $this->input->get_post('xlarge',TRUE) == ''? 0 : abs($this->input->get_post('xlarge',TRUE)) * -1;
          $xxlarge = $this->input->get_post('xxlarge',TRUE) == ''? 0 : abs($this->input->get_post('xxlarge',TRUE)) * -1;

          $code = 'Order';
          $comments = $this->input->get_post('comments',TRUE);

          if ($stockid = $this->stocks->insert_order_item($itemcode, $styleid, $code, $orderid, $description)) {
            $error = 'Info: ';
            if ($this->stocks->update_order_sizes($stockid, $orderid, $xsmall, $small, $medium, $large, $xlarge, $xxlarge, $comments)) {
              $error = $error.' sizes added ';
            } else {
              $error = $error.' sizes unchanged ';
            }
            if ($this->stocks->update_order_numbers($stockid, $orderid, $xsmallnumbers, $smallnumbers, $mediumnumbers, $largenumbers, $xlargenumbers, $xxlargenumbers)) {
              $error = $error.' numbers added ';
            } else {
              $error = $error.' numbers unchanged ';
            }
            if ($this->stocks->update_order_front($stockid, $orderid, $frontnumpos, $frontnumsize, $frontnumcolor, $frontnumtrimcolor, $frontlogopos, $frontlogoname, $frontlogocolor, $frontlogotrimcolor)) {
              $error = $error.' front added ';
            } else {
              $error = $error.' front unchanged ';
            }
            if ($this->stocks->update_order_rear($stockid, $orderid, $rearnumpos, $rearnumsize, $rearnumcolor, $rearnumtrimcolor, $rearlogopos, $rearlogoname, $rearlogocolor, $rearlogotrimcolor) ) {
              $error = $error.' back added ';
            } else {
              $error = $error.' back unchanged ';
            }
            if ($this->stocks->update_order_short($stockid, $orderid, $shortnumpos, $shortlogopos) ) {
              $error = $error.' short added ';
            } else {
              $error = $error.' short unchanged ';
            }
            $this->select($clientid,$orderid,$error);
          } else {
            $this->form_insert_order($clientid,$orderid,$stocks_edit,$artworks_edit,$itemcode,$styleid,INFO_UNCHANGED);
          }
        } else {
          $this->form_insert_order($clientid,$orderid,$stocks_edit,$artworks_edit,$itemcode,$styleid,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/accounts/index/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }

  }

  public function update_status($clientid = 0, $orderid = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('orderstatus', 'OrderStatus', 'trim|xss_clean');

    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_owner_update($uf_orders)) {

        $updcomment = '';
        $updby = $this->session->userdata('uf_username');
        $order = $this->orders->select_order_by_id($orderid);

        if ($order->orderstatus == '0') {
          $orderstatus = $this->codes->select_value_by_name('Canuckstuff Mockup', 'OrderStatus');
          $this->orders->update_status($orderid, $orderstatus, $updcomment, $updby);
          $this->select($clientid,$orderid,INFO_SUCCESS);

        } else if ($order->orderstatus == '1') {
          $orderstatus = $this->codes->select_value_by_name('Customer Confirmed', 'OrderStatus');

          $this->orders->update_status($orderid, $orderstatus, $updcomment, $updby);
          $this->select($clientid,$orderid,INFO_SUCCESS);

        } else {
          $this->select($clientid,$orderid,INFO_UNCHANGED);
        }
      } else {
        redirect('/accounts/index/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function insert_artwork($clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('artworksource', 'ArtworkSource', 'trim|xss_clean');
    $this->form_validation->set_rules('artworkstatus', 'ArtworkStatue', 'trim|xss_clean');
    $this->form_validation->set_rules('comment', 'Comment', 'trim|max_length[100]|xss_clean');

    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_owner_update($uf_orders)) {
        if ($this->form_validation->run() == TRUE)
        {
          $artworksource = $this->input->get_post('artworksource',TRUE);
          $artworkstatus = $this->input->get_post('artworkstatus',TRUE);
          $comment = $this->input->get_post('comment',TRUE);
          $uploadby = $this->session->userdata('uf_username');

          if ($artworkid = $this->artworks->insert_artwork($artworksource, $artworkstatus, $comment, $orderid, $stocks_edit, $uploadby)) {
            $config['upload_path'] = './artworks/';
            $config['allowed_types'] = 'gif|jpg|png|doc|pdf|xls';
            $config['overwrite'] = TRUE;
            $config['max_size'] = '0';
            $config['file_name'] = 'artwork-'.$orderid.'-'.$artworkid;

            $this->upload->initialize($config);
            if (!$this->upload->do_upload('filename'))
            {
              $upload = $this->upload->display_errors();
              $this->select($clientid,$orderid,$upload);
            }
            else
            {
              $upload = $this->upload->data();
              $this->artworks->update_filename($artworkid, $upload['file_name']);
              $this->select($clientid,$orderid,INFO_SUCCESS);
            }
          } else {
            $this->select($clientid,$orderid,INFO_UNCHANGED);
          }
        } else {
          $this->select($clientid,$orderid,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/accounts/index/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function update_item($clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $itemcode = '')
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');
      if ($this->codes->role_order_owner_update($uf_orders)) {
        $stockid = $stocks_edit;
        $stock = $this->stocks->select_stock_by_id($stocks_edit);
        if ($itemcode !== $stock->itemcode) {
          $this->stocks->update_order_item($stockid, $orderid, $itemcode, 0);
        }
        $this->form_update_order($clientid,$orderid,$stocks_edit,$artworks_edit,'',0,INFO_SUCCESS);
      } else {
        redirect('/accounts/index/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function update_stock($clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $itemcode = '', $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');

    //$this->form_validation->set_rules('itemcode', 'ItemCode', 'trim|required|max_length[45]|xss_clean');
    $this->form_validation->set_rules('styleid', 'StyleId', 'trim|xss_clean');

    $this->form_validation->set_rules('description', 'Description', 'trim|max_length[250]|xss_clean');

    $this->form_validation->set_rules('frontlogopos', 'FrontLogoPos', 'trim|required|xss_clean');
    $this->form_validation->set_rules('frontlogoname', 'FrontLogoName', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('frontlogocolor', 'FrontLogoColor', 'trim|required|min_length[2]|max_length[50]|xss_clean');
    $this->form_validation->set_rules('frontlogotrimcolor', 'FrontLogoTrimColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('frontnumpos', 'FrontNumPos', 'trim|required|xss_clean');
    $this->form_validation->set_rules('frontnumsize', 'FrontNumSize', 'trim|required|xss_clean');
    $this->form_validation->set_rules('frontnumcolor', 'FrontNumColor', 'trim|required|min_length[2]|max_length[50]|xss_clean');
    $this->form_validation->set_rules('frontnumtrimcolor', 'FrontNumTrimColor', 'trim|max_length[50]|xss_clean');

    $this->form_validation->set_rules('rearlogopos', 'RearLogoPos', 'trim|required|xss_clean');
    $this->form_validation->set_rules('rearlogoname', 'RearLogoName', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('rearlogocolor', 'RearLogoColor', 'trim|required|min_length[2]|max_length[50]|xss_clean');
    $this->form_validation->set_rules('rearlogotrimcolor', 'RearLogoTrimColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('rearnumpos', 'RearNumPos', 'trim|required|xss_clean');
    $this->form_validation->set_rules('rearnumsize', 'RearNumSize', 'trim|required|xss_clean');
    $this->form_validation->set_rules('rearnumcolor', 'RearNumColor', 'trim|required|min_length[2]|max_length[50]|xss_clean');
    $this->form_validation->set_rules('rearnumtrimcolor', 'RearNumTrimColor', 'trim|max_length[50]|xss_clean');

    $this->form_validation->set_rules('shortlogopos', 'ShortLogoPos', 'trim|required|xss_clean');
    $this->form_validation->set_rules('shortnumpos', 'ShortNumPos', 'trim|required|xss_clean');

    $this->form_validation->set_rules('xsmall', 'Xsmall', 'trim|numeric|xss_clean');
    $this->form_validation->set_rules('xsmallnumbers', 'XsmallNumbers', 'trim|max_length[250]|xss_clean');

    $this->form_validation->set_rules('small', 'Small', 'trim|numeric|xss_clean');
    $this->form_validation->set_rules('smallnumbers', 'SmallNumbers', 'trim|max_length[250]|xss_clean');

    $this->form_validation->set_rules('medium', 'Medium', 'trim|numeric|xss_clean');
    $this->form_validation->set_rules('mediumnumbers', 'MediumNumbers', 'trim|max_length[250]|xss_clean');

    $this->form_validation->set_rules('large', 'Large', 'trim|numeric|xss_clean');
    $this->form_validation->set_rules('largenumbers', 'LargeNumbers', 'trim|max_length[250]|xss_clean');

    $this->form_validation->set_rules('xlarge', 'Xlarge', 'trim|numeric|xss_clean');
    $this->form_validation->set_rules('xlargenumbers', 'XlargeNumbers', 'trim|max_length[250]|xss_clean');

    $this->form_validation->set_rules('xxlarge', 'Xxlarge', 'trim|numeric|xss_clean');
    $this->form_validation->set_rules('xxlargenumbers', 'XxlargeNumbers', 'trim|max_length[250]|xss_clean');
    $this->form_validation->set_rules('comments', 'Comments', 'trim|max_length[250]|xss_clean');

    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_owner_update($uf_orders)) {
        if ($this->form_validation->run() == TRUE)
        {
          //$itemcode = $this->input->get_post('itemcode',TRUE);
          $stockid = $stocks_edit;
          $stock = $this->stocks->select_stock_by_id($stocks_edit);

          $styleid = $this->input->get_post('styleid',TRUE);
          $description = $this->input->get_post('description',TRUE);
          $this->stocks->update_order_style($stockid, $orderid, $styleid, $description);

          $frontlogopos = $this->input->get_post('frontlogopos',TRUE);
          $frontlogoname = $this->input->get_post('frontlogoname',TRUE);
          $frontlogocolor = $this->input->get_post('frontlogocolor',TRUE);
          $frontlogotrimcolor = $this->input->get_post('frontlogotrimcolor',TRUE);
          $frontnumpos = $this->input->get_post('frontnumpos',TRUE);
          $frontnumsize = $this->input->get_post('frontnumsize',TRUE);
          $frontnumcolor = $this->input->get_post('frontnumcolor',TRUE);
          $frontnumtrimcolor = $this->input->get_post('frontnumtrimcolor',TRUE);

          $rearlogopos = $this->input->get_post('rearlogopos',TRUE);
          $rearlogoname = $this->input->get_post('rearlogoname',TRUE);
          $rearlogocolor = $this->input->get_post('rearlogocolor',TRUE);
          $rearlogotrimcolor = $this->input->get_post('rearlogotrimcolor',TRUE);
          $rearnumpos = $this->input->get_post('rearnumpos',TRUE);
          $rearnumsize = $this->input->get_post('rearnumsize',TRUE);
          $rearnumcolor = $this->input->get_post('rearnumcolor',TRUE);
          $rearnumtrimcolor = $this->input->get_post('rearnumtrimcolor',TRUE);

          $shortlogopos = $this->input->get_post('shortlogopos',TRUE);
          $shortnumpos = $this->input->get_post('shortnumpos',TRUE);

          $xsmallnumbers = $this->input->get_post('xsmallnumbers',TRUE);
          $smallnumbers = $this->input->get_post('smallnumbers',TRUE);
          $mediumnumbers = $this->input->get_post('mediumnumbers',TRUE);
          $largenumbers = $this->input->get_post('largenumbers',TRUE);
          $xlargenumbers = $this->input->get_post('xlargenumbers',TRUE);
          $xxlargenumbers = $this->input->get_post('xxlargenumbers',TRUE);

          $xsmall = $this->input->get_post('xsmall',TRUE) == ''? 0 : abs($this->input->get_post('xsmall',TRUE)) * -1;
          $small = $this->input->get_post('small',TRUE) == ''? 0 : abs($this->input->get_post('small',TRUE)) * -1;
          $medium = $this->input->get_post('medium',TRUE) == ''? 0 : abs($this->input->get_post('medium',TRUE)) * -1;
          $large = $this->input->get_post('large',TRUE) == ''? 0 : abs($this->input->get_post('large',TRUE)) * -1;
          $xlarge = $this->input->get_post('xlarge',TRUE) == ''? 0 : abs($this->input->get_post('xlarge',TRUE)) * -1;
          $xxlarge = $this->input->get_post('xxlarge',TRUE) == ''? 0 : abs($this->input->get_post('xxlarge',TRUE)) * -1;


          $comments = $this->input->get_post('comments',TRUE);

          $error = 'Info: ';
          if ($this->stocks->update_order_sizes($stockid, $orderid, $xsmall, $small, $medium, $large, $xlarge, $xxlarge, $comments)) {
            $error = $error.' sizes changed ';
          } else {
            $error = $error.' sizes unchanged ';
          }
          if ($this->stocks->update_order_numbers($stockid, $orderid, $xsmallnumbers, $smallnumbers, $mediumnumbers, $largenumbers, $xlargenumbers, $xxlargenumbers)) {
            $error = $error.' numbers changed ';
          } else {
            $error = $error.' numbers unchanged ';
          }
          if ($this->stocks->update_order_front($stockid, $orderid, $frontnumpos, $frontnumsize, $frontnumcolor, $frontnumtrimcolor, $frontlogopos, $frontlogoname, $frontlogocolor, $frontlogotrimcolor)) {
            $error = $error.' front changed ';
          } else {
            $error = $error.' front unchanged ';
          }
          if ($this->stocks->update_order_rear($stockid, $orderid, $rearnumpos, $rearnumsize, $rearnumcolor, $rearnumtrimcolor, $rearlogopos, $rearlogoname, $rearlogocolor, $rearlogotrimcolor) ) {
            $error = $error.' back changed ';
          } else {
            $error = $error.' back unchanged ';
          }
          if ($this->stocks->update_order_short($stockid, $orderid, $shortnumpos, $shortlogopos) ) {
            $error = $error.' short changed ';
          } else {
            $error = $error.' short unchanged ';
          }
          $this->form_update_order($clientid,$orderid,$stocks_edit,$artworks_edit,'',0,$error);
        } else {
          $this->form_update_order($clientid,$orderid,$stocks_edit,$artworks_edit,'',0,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/accounts/index/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }

  }

  public function update_artwork($clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('artworksource', 'ArtworkSource', 'trim|xss_clean');
    $this->form_validation->set_rules('artworkstatus', 'ArtworkStatue', 'trim|xss_clean');
    $this->form_validation->set_rules('comment', 'Comment', 'trim|max_length[100]|xss_clean');

    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_owner_update($uf_orders)) {
        if ($this->form_validation->run() == TRUE)
        {
          $artworksource = $this->input->get_post('artworksource',TRUE);
          $artworkstatus = $this->input->get_post('artworkstatus',TRUE);
          $comment = $this->input->get_post('comment',TRUE);
          $uploadby = $this->session->userdata('uf_username');
          $artworkid = $artworks_edit;

          $this->artworks->update_artwork($artworkid, $artworksource, $artworkstatus, $comment, $uploadby);
          $config['upload_path'] = './artworks/';
          $config['allowed_types'] = 'gif|jpg|png|doc|pdf|xls|docx';
          $config['overwrite'] = TRUE;
          $config['max_size'] = '0';
          $config['file_name'] = 'artwork-'.$orderid.'-'.$artworkid;

          $this->upload->initialize($config);
          if (!$this->upload->do_upload('filename'))
          {
            $upload = $this->upload->display_errors();
            $this->form_order($clientid,$orderid,$stocks_edit,0,'',0,$upload);
          }
          else
          {
            $upload = $this->upload->data();
            $this->artworks->update_filename($artworkid, $upload['file_name']);
            $this->form_update_order($clientid,$orderid,$stocks_edit,0,'',0,INFO_SUCCESS);
          }
        } else {
          $this->form_update_order($clientid,$orderid,$stocks_edit,$artworks_edit,'',0,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/accounts/index/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function check_item($clientid = 0, $orderid = 0, $itemcode = 0, $message = '')
  {
    $dbhost = 'mysql.oyou.com';
    $dbuser = 'canuckst';
    $dbpass = 'longxia1!';

    //$dbhost = 'localhost';
    //$dbuser = 'canuckst_store';
    //$dbpass = 'kvi0gFhve2F#';

    $conn = mysql_connect($dbhost, $dbuser, $dbpass);
    //$dbname = 'storevolleyball';
    $dbname = 'canuckst_store';

    mysql_select_db($dbname);

    $query = "select products_id from products where products_model = '".$itemcode."' LIMIT 1";
    if (($result = mysql_query($query))) {
      if ($row = mysql_fetch_array($result, MYSQL_ASSOC))
      {
        $pid = strval($row['products_id']);
        mysql_close();
        redirect('http://www.canuckstuff.com/store/index.php?main_page=product_info&products_id='.$pid, 'refresh');
      }
    }
    mysql_close();
    redirect('http://www.canuckstuff.com/store/index.php?main_page=product_info&products_id=0', 'refresh');

  }

  public function phone_check($phone)
  {
    if ($phone == '') return TRUE;
    if (preg_match("/[0-9]{3}[-]{1}[0-9]{3}[-]{1}[0-9]{4}/", $phone)) {
      return TRUE;
    }
    else
    {
      $this->form_validation->set_message('phone_check', 'The %s field must be ###-###-#### format');
      return FALSE;
    }
  }

  public function insert_feedback($clientid = 0, $orderid = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('feedback', 'Feedback', 'trim|required|min_length[2]|max_length[250]|xss_clean');

    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_owner_update($uf_orders)) {
        if ($this->form_validation->run() == TRUE)
        {
          $feedback = $this->input->get_post('feedback',TRUE);
          $createdby = $this->session->userdata('uf_username');

          if ($fbid = $this->feedbacks->insert_feedback($feedback, $orderid, $createdby)) {
            $this->select($clientid,$orderid,INFO_SUCCESS);
          } else {
            $this->select($clientid,$orderid,INFO_UNCHANGED);
          }
        } else {
          $this->select($clientid,$orderid,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/accounts/index/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }


  public function comments()
  {
    echo 'Customer Order Item and Artwork - maintenance.';
  }

}
?>
