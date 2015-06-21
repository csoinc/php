<?php
class Clientorderitems extends CI_Controller {
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

    $this->title = "Client Order Items";
    $this->trip = '<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Clients';
    $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Orders';
    $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Items';
  }

  public function select($clients_start = 0, $clientid = 0, $orderid = 0, $message = '')
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_read($uf_orders) || $this->codes->role_order_update($uf_orders)) {
        $data['clients_start'] = $clients_start;
        $data['clientid'] = $clientid;
        $data['orderid'] = $orderid;

        $order = $this->orders->select_order_by_id($orderid);
        $data['order'] = $order;
        $data['orderstatus_list'] = $this->codes->select_codes_by_codetype('OrderStatus');
        $data['orderstatus_options'] = 'id="colours" size="1"';

        $data['artworkstatus_list'] = $this->codes->select_codes_by_codetype('CanuckArtworkType');
        $data['artworkstatus_options'] = 'id="artworkstatus" size="1"';
        $data['artworksource_list'] = $this->codes->select_codes_by_codetype('ArtworkSource');
        $data['artworksource_options'] = 'id="artworksource" size="1"';

        $data['order_artworks'] = $this->artworks->select_artworks_by_order($orderid);
        $data['feedbacks'] = $this->feedbacks->select_feedback_by_order($orderid);

        $this->template->write_view('header', 'common/admin_header');
        $this->template->write('message', $message);
        $this->template->write('title', $this->title.' : '.$order->contact.' ('.$order->name.')');
        $this->template->write('trip', $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;'.$order->contact.' ('.$order->name.')');

        $data['stocks'] = $this->stocks->select_stocks_by_order($orderid);

        // Write to $content
        $this->template->write_view('content', 'client_order_items', $data);

        $this->template->write_view('footer', 'common/footer');
        // Render the template
        $this->template->render();
      }
      else {
        redirect('/clientorders/select/'.$clients_start.'/'.$clientid.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function form_update_order($clients_start = 0, $clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $message = '')
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_update($uf_orders)) {
        $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Edit/Add Items/Artworks';

        $data['clients_start'] = $clients_start;
        $data['clientid'] = $clientid;
        $data['orderid'] = $orderid;
        if ($clientid != 0) $data['client'] = $this->clients->select($clientid);
        $data['artworks_edit'] = $artworks_edit;
        $data['stocks_edit'] = $stocks_edit;

        $data['stocks'] = $this->stocks->select_stocks_by_order($orderid);
        if ($stocks_edit != 0) {
          $stock = $this->stocks->select_stock_by_id($stocks_edit);
          $data['stocks_edit'] = $stocks_edit;

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
          $data['shortnumpos_options'] = 'id="shortnumpos" size="1"';

          $data['items_list'] = $this->items->select_code_items();
          $data['items_options'] = 'id="items" size="1" onchange="this.form.submit();"';

          $data['styles_list'] = $this->styles->select_code_styles($stock->itemcode);
          $data['styles_options'] = 'id="items" size="1" onchange="this.form.submit();"';

          $data['style_total'] = $this->stocks->select_styles_total($stock->itemcode, $stock->styleid);
        
        }

        $data['artworkstatus_list'] = $this->codes->select_codes_by_codetype('CanuckArtworkType');
        $data['artworkstatus_options'] = 'id="artworkstatus" size="1"';
        $data['artworksource_list'] = $this->codes->select_codes_by_codetype('ArtworkSource');
        $data['artworksource_options'] = 'id="artworksource" size="1"';

        $order = $this->orders->select_order_by_id($orderid);
        $data['order'] = $order;

        $data['items_list'] = $this->items->select_code_items();
        $data['items_options'] = 'id="items" size="1" onchange="this.form.submit();"';
        $data['itemcode'] = '';
        $data['styleid'] = '';

        $this->template->write_view('header', 'common/admin_header');
        $this->template->write('message', $message);
        $this->template->write('title', $this->title.' : '.$order->contact.' ('.$order->name.')');
        $this->template->write('trip', $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;'.$order->contact.' ('.$order->name.')');


        $data['order_artworks'] = $this->artworks->select_artworks_by_order($orderid);

        $this->template->write_view('content', 'client_order_update_items_form', $data);

        // Write to $content
        $this->template->write_view('footer', 'common/footer');
        // Render the template
        $this->template->render();
      } else {
        redirect('/clientorders/select/'.$clients_start.'/'.$clientid.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function form_insert_order($clients_start = 0, $clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $message = '')
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_update($uf_orders)) {
        $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Edit/Add Items/Artworks';

        $data['clients_start'] = $clients_start;
        $data['clientid'] = $clientid;
        $data['orderid'] = $orderid;
        if ($clientid != 0) $data['client'] = $this->clients->select($clientid);
        $data['artworks_edit'] = $artworks_edit;
        $data['stocks_edit'] = $stocks_edit;

        $data['stocks'] = $this->stocks->select_stocks_by_order($orderid);

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
        $data['shortnumpos_options'] = 'id="shortnumpos" size="1"';

        $data['items_list'] = $this->items->select_code_items();
        $data['items_options'] = 'id="items" size="1" onchange="this.form.submit();"';

        $data['artworkstatus_list'] = $this->codes->select_codes_by_codetype('CanuckArtworkType');
        $data['artworkstatus_options'] = 'id="artworkstatus" size="1"';
        $data['artworksource_list'] = $this->codes->select_codes_by_codetype('ArtworkSource');
        $data['artworksource_options'] = 'id="artworksource" size="1"';

        $order = $this->orders->select_order_by_id($orderid);
        $data['order'] = $order;

        $data['items_list'] = $this->items->select_code_items();
        $data['items_options'] = 'id="items" size="1" onchange="this.form.submit();"';
        $data['itemcode'] = '';
        $data['styleid'] = '';

        $this->template->write_view('header', 'common/admin_header');
        $this->template->write('message', $message);
        $this->template->write('title', $this->title.' : '.$order->contact.' ('.$order->name.')');
        $this->template->write('trip', $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;'.$order->contact.' ('.$order->name.')');


        $data['order_artworks'] = $this->artworks->select_artworks_by_order($orderid);

        $this->template->write_view('content', 'client_order_insert_items_form', $data);

        // Write to $content
        $this->template->write_view('footer', 'common/footer');
        // Render the template
        $this->template->render();
      } else {
        redirect('/clientorders/select/'.$clients_start.'/'.$clientid.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function select_update_item($clients_start = 0, $clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('itemcode', 'ItemCode', 'trim|required|min_length[3]|max_length[12]|xss_clean');

    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_update($uf_orders)) {
        $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Add New Item';

        $data['clients_start'] = $clients_start;
        $data['clientid'] = $clientid;
        $data['orderid'] = $orderid;
        $data['client'] = $this->clients->select($clientid);
        $data['stocks_edit'] = $stocks_edit;
        $data['artworks_edit'] = $artworks_edit;

        $order = $this->orders->select_order_by_id($orderid);
        $data['order'] = $order;

        $data['items_list'] = $this->items->select_code_items();
        $data['items_options'] = 'id="items" size="1" onchange="this.form.submit();"';

        if ($this->form_validation->run() == TRUE)
        {
          $itemcode = $this->input->get_post('itemcode',TRUE);
          $data['itemcode'] = $itemcode;

          $data['styleid'] = '';
          $data['styles_list'] = $this->styles->select_code_styles($itemcode);
          $data['styles_options'] = 'id="items" size="1" onchange="this.form.submit();"';

          $this->template->write_view('header', 'common/admin_header');
          $this->template->write('message', $message);
          $this->template->write('title', $this->title.' : '.$order->contact.' ('.$order->name.')');
          $this->template->write('trip', $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;'.$order->contact.' ('.$order->name.')');

          $data['stocks'] = $this->stocks->select_stocks_by_order($orderid);
          $data['order_artworks'] = $this->artworks->select_artworks_by_order($orderid);
          $data['artworkstatus_list'] = $this->codes->select_codes_by_codetype('CanuckArtworkType');
          $data['artworkstatus_options'] = 'id="artworkstatus" size="1"';
          $data['artworksource_list'] = $this->codes->select_codes_by_codetype('ArtworkSource');
          $data['artworksource_options'] = 'id="artworksource" size="1"';

          $this->template->write_view('content', 'client_order_update_items_form', $data);

          // Write to $content
          $this->template->write_view('footer', 'common/footer');
          // Render the template
          $this->template->render();
        } else {
          $this->form_update_order($clients_start,$clientid,$orderid,$stocks_edit,$artworks_edit,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/clientorders/select/'.$clients_start.'/'.$clientid.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function select_insert_item($clients_start = 0, $clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('itemcode', 'ItemCode', 'trim|required|min_length[3]|max_length[12]|xss_clean');

    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_update($uf_orders)) {
        $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Add New Item';

        $data['clients_start'] = $clients_start;
        $data['clientid'] = $clientid;
        $data['orderid'] = $orderid;
        $data['client'] = $this->clients->select($clientid);
        $data['stocks_edit'] = $stocks_edit;
        $data['artworks_edit'] = $artworks_edit;

        $order = $this->orders->select_order_by_id($orderid);
        $data['order'] = $order;

        $data['items_list'] = $this->items->select_code_items();
        $data['items_options'] = 'id="items" size="1" onchange="this.form.submit();"';

        if ($this->form_validation->run() == TRUE)
        {
          $itemcode = $this->input->get_post('itemcode',TRUE);
          $data['itemcode'] = $itemcode;

          $data['styleid'] = '';
          $data['styles_list'] = $this->styles->select_code_styles($itemcode);
          $data['styles_options'] = 'id="items" size="1" onchange="this.form.submit();"';

          $this->template->write_view('header', 'common/admin_header');
          $this->template->write('message', $message);
          $this->template->write('title', $this->title.' : '.$order->contact.' ('.$order->name.')');
          $this->template->write('trip', $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;'.$order->contact.' ('.$order->name.')');

          $data['stocks'] = $this->stocks->select_stocks_by_order($orderid);
          $data['order_artworks'] = $this->artworks->select_artworks_by_order($orderid);
          $data['artworkstatus_list'] = $this->codes->select_codes_by_codetype('CanuckArtworkType');
          $data['artworkstatus_options'] = 'id="artworkstatus" size="1"';
          $data['artworksource_list'] = $this->codes->select_codes_by_codetype('ArtworkSource');
          $data['artworksource_options'] = 'id="artworksource" size="1"';

          $this->template->write_view('content', 'client_order_insert_items_form', $data);

          // Write to $content
          $this->template->write_view('footer', 'common/footer');
          // Render the template
          $this->template->render();
        } else {
          $this->form_insert_order($clients_start,$clientid,$orderid,0,0,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/clientorders/select/'.$clients_start.'/'.$clientid.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function select_update_style($clients_start = 0, $clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $itemcode = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('styleid', 'StyleId', 'trim|required|min_length[1]|max_length[45]|xss_clean');

    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_update($uf_orders)) {
        $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Add New Item';

        $data['clients_start'] = $clients_start;
        $data['clientid'] = $clientid;
        $data['orderid'] = $orderid;
        $data['client'] = $this->clients->select($clientid);

        $order = $this->orders->select_order_by_id($orderid);
        $data['order'] = $order;

        $data['items_list'] = $this->items->select_code_items();
        $data['items_options'] = 'id="items" size="1" onchange="this.form.submit();"';
        $data['itemcode'] = $itemcode;
        $data['stocks_edit'] = $stocks_edit;
        $data['artworks_edit'] = $artworks_edit;

        if ($this->form_validation->run() == TRUE)
        {
          $styleid = $this->input->get_post('styleid',TRUE);
          $data['styleid'] = $styleid;

          $data['styles_list'] = $this->styles->select_code_styles($itemcode);
          $data['styles_options'] = 'id="items" size="1" onchange="this.form.submit();"';
          $data['style_total'] = $this->styles->select_styles_total($itemcode, $styleid);
          
          $data['frontlogopos'] = '1';
          $data['frontlogopos_list'] = $this->codes->select_codes_by_codetype('FrontLogoPos');
          $data['frontlogopos_options'] = 'id="frontlogopos" size="1"';

          $data['frontnumpos'] = '1';
          $data['frontnumpos_list'] = $this->codes->select_codes_by_codetype('FrontNumPos');
          $data['frontnumpos_options'] = 'id="frontnumpos" size="1"';

          $data['frontnumsize'] = '';
          $data['numsize_list'] = $this->codes->select_codes_by_codetype('NumSize');
          $data['numsize_options'] = 'size="4"';

          $data['artworkcolour_list'] = $this->codes->select_codes_by_codetype('ArtworkColour');
          $data['artworkcolour_options'] = 'size="1"';

          $data['frontlogocolor'] = '';
          $data['frontlogotrimcolor'] = '';
          $data['frontnumcolor'] = '';
          $data['frontnumtrimcolor'] = '';

          $data['rearlogopos'] = '1';
          $data['rearlogopos_list'] = $this->codes->select_codes_by_codetype('RearLogoPos');
          $data['rearlogopos_options'] = 'id="rearlogopos" size="1"';

          $data['rearnumpos'] = '1';
          $data['rearnumpos_list'] = $this->codes->select_codes_by_codetype('RearNumPos');
          $data['rearnumpos_options'] = 'id="rearnumpos" size="1"';

          $data['rearnumsize'] = '';
          $data['rearlogocolor'] = '';
          $data['rearlogotrimcolor'] = '';
          $data['rearnumcolor'] = '';
          $data['rearnumtrimcolor'] = '';


          $data['shortlogopos'] = '1';
          $data['shortlogopos_list'] = $this->codes->select_codes_by_codetype('ShortLogoPos');
          $data['shortlogopos_options'] = 'id="shortlogopos" size="1"';

          $data['shortnumpos'] = '1';
          $data['shortnumpos_list'] = $this->codes->select_codes_by_codetype('ShortNumPos');
          $data['shortnumpos_options'] = 'id="shortnumpos" size="1"';

          $this->template->write_view('header', 'common/admin_header');
          $this->template->write('message', $message);
          $this->template->write('title', $this->title.' : '.$order->contact.' ('.$order->name.')');
          $this->template->write('trip', $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;'.$order->contact.' ('.$order->name.')');

          $data['stocks'] = $this->stocks->select_stocks_by_order($orderid);
          $data['order_artworks'] = $this->artworks->select_artworks_by_order($orderid);
          $data['artworkstatus_list'] = $this->codes->select_codes_by_codetype('CanuckArtworkType');
          $data['artworkstatus_options'] = 'id="artworkstatus" size="1"';
          $data['artworksource_list'] = $this->codes->select_codes_by_codetype('ArtworkSource');
          $data['artworksource_options'] = 'id="artworksource" size="1"';

          $this->template->write_view('content', 'client_order_update_items_form', $data);

          // Write to $content
          $this->template->write_view('footer', 'common/footer');
          // Render the template
          $this->template->render();
        } else {
          $this->form_update_order($clients_start,$clientid,$orderid,$stocks_edit,$artworks_edit,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/clientorders/select/'.$clients_start.'/'.$clientid.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function select_insert_style($clients_start = 0, $clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $itemcode = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('styleid', 'StyleId', 'trim|required|min_length[1]|max_length[45]|xss_clean');

    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_update($uf_orders)) {
        $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Add New Item';

        $data['clients_start'] = $clients_start;
        $data['clientid'] = $clientid;
        $data['orderid'] = $orderid;
        $data['client'] = $this->clients->select($clientid);

        $order = $this->orders->select_order_by_id($orderid);
        $data['order'] = $order;

        $data['items_list'] = $this->items->select_code_items();
        $data['items_options'] = 'id="items" size="1" onchange="this.form.submit();"';
        $data['itemcode'] = $itemcode;
        $data['stocks_edit'] = $stocks_edit;
        $data['artworks_edit'] = $artworks_edit;

        if ($this->form_validation->run() == TRUE)
        {
          $styleid = $this->input->get_post('styleid',TRUE);
          $data['styleid'] = $styleid;

          $data['styles_list'] = $this->styles->select_code_styles($itemcode);
          $data['styles_options'] = 'id="items" size="1" onchange="this.form.submit();"';
          $data['style_total'] = $this->stocks->select_styles_total($itemcode, $styleid);
          
          $data['frontlogopos'] = '1';
          $data['frontlogopos_list'] = $this->codes->select_codes_by_codetype('FrontLogoPos');
          $data['frontlogopos_options'] = 'id="frontlogopos" size="1"';

          $data['frontnumpos'] = '1';
          $data['frontnumpos_list'] = $this->codes->select_codes_by_codetype('FrontNumPos');
          $data['frontnumpos_options'] = 'id="frontnumpos" size="1"';

          $data['frontnumsize'] = '';
          $data['numsize_list'] = $this->codes->select_codes_by_codetype('NumSize');
          $data['numsize_options'] = 'size="4"';

          $data['artworkcolour_list'] = $this->codes->select_codes_by_codetype('ArtworkColour');
          $data['artworkcolour_options'] = 'size="1"';
          $data['frontlogocolor'] = '';
          $data['frontlogotrimcolor'] = '';
          $data['frontnumcolor'] = '';
          $data['frontnumtrimcolor'] = '';

          $data['rearlogopos'] = '1';
          $data['rearlogopos_list'] = $this->codes->select_codes_by_codetype('RearLogoPos');
          $data['rearlogopos_options'] = 'id="rearlogopos" size="1"';

          $data['rearnumpos'] = '1';
          $data['rearnumpos_list'] = $this->codes->select_codes_by_codetype('RearNumPos');
          $data['rearnumpos_options'] = 'id="rearnumpos" size="1"';

          $data['rearnumsize'] = '';
          $data['rearlogocolor'] = '';
          $data['rearlogotrimcolor'] = '';
          $data['rearnumcolor'] = '';
          $data['rearnumtrimcolor'] = '';


          $data['shortlogopos'] = '1';
          $data['shortlogopos_list'] = $this->codes->select_codes_by_codetype('ShortLogoPos');
          $data['shortlogopos_options'] = 'id="shortlogopos" size="1"';

          $data['shortnumpos'] = '1';
          $data['shortnumpos_list'] = $this->codes->select_codes_by_codetype('ShortNumPos');
          $data['shortnumpos_options'] = 'id="shortnumpos" size="1"';
          $data['shortnumsize'] = '';
          $data['shortlogocolor'] = '';
          $data['shortlogotrimcolor'] = '';
          $data['shortnumcolor'] = '';
          $data['shortnumtrimcolor'] = '';
          
          $this->template->write_view('header', 'common/admin_header');
          $this->template->write('message', $message);
          $this->template->write('title', $this->title.' : '.$order->contact.' ('.$order->name.')');
          $this->template->write('trip', $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;'.$order->contact.' ('.$order->name.')');

          $data['stocks'] = $this->stocks->select_stocks_by_order($orderid);
          $data['order_artworks'] = $this->artworks->select_artworks_by_order($orderid);
          $data['artworkstatus_list'] = $this->codes->select_codes_by_codetype('CanuckArtworkType');
          $data['artworkstatus_options'] = 'id="artworkstatus" size="1"';
          $data['artworksource_list'] = $this->codes->select_codes_by_codetype('ArtworkSource');
          $data['artworksource_options'] = 'id="artworksource" size="1"';

          $this->template->write_view('content', 'client_order_insert_items_form', $data);

          // Write to $content
          $this->template->write_view('footer', 'common/footer');
          // Render the template
          $this->template->render();
        } else {
          $this->form_insert_order($clients_start,$clientid,$orderid,0,0,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/clientorders/select/'.$clients_start.'/'.$clientid.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function update_client($clients_start = 0, $clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('name', 'Name', 'trim|max_length[200]|xss_clean');
    $this->form_validation->set_rules('contact', 'Contact', 'trim|required|min_length[3]|max_length[50]|xss_clean');
    $this->form_validation->set_rules('email', 'Email', 'trim|max_length[100]|xss_clean');
    $this->form_validation->set_rules('telephone', 'Telephone', 'trim|required|min_length[8]|max_length[45]|xss_clean');
    $this->form_validation->set_rules('cellphone', 'Cellphone', 'trim|max_length[45]|xss_clean');
    $this->form_validation->set_rules('address', 'Address', 'trim|max_length[100]|xss_clean');
    $this->form_validation->set_rules('zipcode', 'Zipcode', 'trim|max_length[45]|xss_clean');
    $this->form_validation->set_rules('shippingaddr', 'ShippingAddr', 'trim|max_length[100]|xss_clean');
    $this->form_validation->set_rules('shippingzip', 'ShippingZip', 'trim|max_length[45]|xss_clean');
    $this->form_validation->set_rules('orderdate', 'Requireddate', 'trim|required|date|xss_clean');
    $this->form_validation->set_rules('requireddate', 'Requireddate', 'trim|required|date|xss_clean');
    $this->form_validation->set_rules('payment', 'Payment', 'trim|max_length[45]|xss_clean');
    $this->form_validation->set_rules('expdate', 'Expdate', 'trim|max_length[45]|xss_clean');
    $this->form_validation->set_rules('comments', 'Comments', 'trim|max_length[200]|xss_clean');


    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_orders');
      if ($this->codes->role_order_update($uf_uniforms)) {
        if ($this->form_validation->run() == TRUE)
        {
          $name = $this->input->get_post('name',TRUE);
          $contact = $this->input->get_post('contact',TRUE);
          $email = $this->input->get_post('email',TRUE);
          $telephone = $this->input->get_post('telephone',TRUE);
          $cellphone = $this->input->get_post('cellphone',TRUE);
          $address = $this->input->get_post('address',TRUE);
          $zipcode = $this->input->get_post('zipcode',TRUE);
          $shippingaddr = $this->input->get_post('shippingaddr',TRUE);
          $shippingzip = $this->input->get_post('shippingzip',TRUE);
          $orderdate = $this->input->get_post('orderdate',TRUE);
          $requireddate = $this->input->get_post('requireddate',TRUE);
          $payment = $this->input->get_post('payment',TRUE);
          $expdate = $this->input->get_post('expdate',TRUE);
          $comments = $this->input->get_post('comments',TRUE);
          $createdby = $this->session->userdata('uf_username');

          if ($this->orders->update_client($orderid,$clientid,$name,$contact,$email,$telephone,$cellphone,$address,$zipcode,$shippingaddr,$shippingzip,$orderdate,$requireddate,$payment,$expdate,$comments,$createdby)) {
            $this->form_update_order($clients_start,$clientid,$orderid,$stocks_edit,$artworks_edit,INFO_SUCCESS);
          } else {
            $this->form_update_order($clients_start,$clientid,$orderid,$stocks_edit,$artworks_edit,INFO_UNCHANGED);
          }
        } else {
          $this->form_update_order($clients_start,$clientid,$orderid,$stocks_edit,$artworks_edit,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/clientorders/select/'.$clients_start.'/'.$clientid.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }

  }

  public function insert_item($clients_start = 0, $clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $itemcode = '', $styleid = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('description', 'Description', 'trim|max_length[250]|xss_clean');
    $this->form_validation->set_rules('frontlogopos', 'FrontLogoPos', 'trim|required|xss_clean');
    $this->form_validation->set_rules('frontlogoname', 'FrontLogoName', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('frontlogocolor', 'FrontLogoColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('frontlogotrimcolor', 'FrontLogoTrimColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('frontnumpos', 'FrontNumPos', 'trim|xss_clean');
    $this->form_validation->set_rules('frontnumsize', 'FrontNumSize', 'trim|xss_clean');
    $this->form_validation->set_rules('frontnumcolor', 'FrontNumColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('frontnumtrimcolor', 'FrontNumTrimColor', 'trim|max_length[50]|xss_clean');

    $this->form_validation->set_rules('rearlogopos', 'RearLogoPos', 'trim|xss_clean');
    $this->form_validation->set_rules('rearlogoname', 'RearLogoName', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('rearlogocolor', 'RearLogoColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('rearlogotrimcolor', 'RearLogoTrimColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('rearnumpos', 'RearNumPos', 'trim|xss_clean');
    $this->form_validation->set_rules('rearnumsize', 'RearNumSize', 'trim|xss_clean');
    $this->form_validation->set_rules('rearnumcolor', 'RearNumColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('rearnumtrimcolor', 'RearNumTrimColor', 'trim|max_length[50]|xss_clean');

    $this->form_validation->set_rules('shortlogopos', 'ShortLogoPos', 'trim|xss_clean');
    $this->form_validation->set_rules('shortlogocolor', 'ShortLogoColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('shortlogotrimcolor', 'ShortLogoTrimColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('shortnumpos', 'ShortNumPos', 'trim|xss_clean');
    $this->form_validation->set_rules('shortnumsize', 'ShortNumSize', 'trim|xss_clean');
    $this->form_validation->set_rules('shortnumcolor', 'ShortNumColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('shortnumtrimcolor', 'ShortNumTrimColor', 'trim|max_length[50]|xss_clean');

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

      if ($this->codes->role_order_update($uf_orders)) {
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
          $shortlogoname = $this->input->get_post('shortlogoname',TRUE);
          $shortlogocolor = $this->input->get_post('shortlogocolor',TRUE);
          $shortlogotrimcolor = $this->input->get_post('shortlogotrimcolor',TRUE);
          $shortnumpos = $this->input->get_post('shortnumpos',TRUE);
          $shortnumsize = $this->input->get_post('shortnumsize',TRUE);
          $shortnumcolor = $this->input->get_post('shortnumcolor',TRUE);
          $shortnumtrimcolor = $this->input->get_post('shortnumtrimcolor',TRUE);

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
            $error = '';
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
            if ($this->stocks->update_order_short($stockid, $orderid, $shortnumpos, $shortnumsize, $shortnumcolor, $shortnumtrimcolor, $shortlogopos, $shortlogoname, $shortlogocolor, $shortlogotrimcolor) ) {
              $error = $error.' shorts added ';
            } else {
              $error = $error.' shorts unchanged ';
            }
            $this->form_update_order($clients_start,$clientid,$orderid,0,0,$error);
          } else {
            $this->form_update_order($clients_start,$clientid,$orderid,0,0,INFO_UNCHANGED);
          }
        } else {
          $this->form_update_order($clients_start,$clientid,$orderid,0,0,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/clientorders/select/'.$clients_start.'/'.$clientid.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function repeat_order($clients_start = 0, $clientid = 0, $base_orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $message = '')
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_update($uf_orders)) {
        if ($order = $this->orders->select_order_by_id($base_orderid)) {
          //duplicated order record
          $name = $order->name;
          $contact = $order->contact;
          $email = $order->email;
          $telephone = $order->telephone;
          $cellphone = $order->cellphone;
          $address = $order->address;
          $zipcode = $order->zipcode;
          $shippingaddr = $order->shippingaddr;
          $shippingzip = $order->shippingzip;
          //$orderdate = $order->orderdate;
          $orderdate = date("Y-m-d");
          //$requireddate = $order->requireddate;
          $requireddate = date("Y-m-d");
          $payment = $order->payment;
          $expdate = $order->expdate;
          $comments = $order->comments;

          $createdby = $this->session->userdata('uf_username');

          $orderid = $this->orders->insert($clientid,$name,$contact,$email,$telephone,$cellphone,$address,$zipcode,$shippingaddr,$shippingzip,$orderdate,$requireddate,$payment,$expdate,$comments,$createdby);

          //duplicated stock records
          $error = '';
          if ($stocks = $this->stocks->select_stocks_by_order($base_orderid)) {
            foreach($stocks->result() as $stock) {

              $itemcode = $stock->itemcode;
              $styleid = $stock->styleid;

              $description = $stock->description;

              $frontlogopos = $stock->frontlogopos;
              $frontlogoname = $stock->frontlogoname;
              $frontlogocolor = $stock->frontlogocolor;
              $frontlogotrimcolor = $stock->frontlogotrimcolor;
              $frontnumpos = $stock->frontnumpos;
              $frontnumsize = $stock->frontnumsize;
              $frontnumcolor = $stock->frontnumcolor;
              $frontnumtrimcolor = $stock->frontnumtrimcolor;

              $rearlogopos = $stock->rearlogopos;
              $rearlogoname = $stock->rearlogoname;
              $rearlogocolor = $stock->rearlogocolor;
              $rearlogotrimcolor = $stock->rearlogotrimcolor;
              $rearnumpos = $stock->rearnumpos;
              $rearnumsize = $stock->rearnumsize;
              $rearnumcolor = $stock->rearnumcolor;
              $rearnumtrimcolor = $stock->rearnumtrimcolor;

              $shortlogopos = $stock->sidelogopos;
              $shortlogoname = $stock->sidelogoname;
              $shortlogocolor = $stock->sidelogocolor;
              $shortlogotrimcolor = $stock->sidelogotrimcolor;
              $shortnumpos = $stock->sidenumpos;
              $shortnumsize = $stock->sidenumsize;
              $shortnumcolor = $stock->sidenumcolor;
              $shortnumtrimcolor = $stock->sidenumtrimcolor;

              $xsmallnumbers = $stock->xsmallnumbers;
              $smallnumbers = $stock->smallnumbers;
              $mediumnumbers = $stock->mediumnumbers;
              $largenumbers = $stock->largenumbers;
              $xlargenumbers = $stock->xlargenumbers;
              $xxlargenumbers = $stock->xxlargenumbers;

              //$xsmall = $stock->xsmall;
              //$small = $stock->small;
              //$medium = $stock->medium;
              //$large = $stock->large;
              //$xlarge = $stock->xlarge;
              //$xxlarge = $stock->xxlarge;
              
              //repeat order initial = 0 as Frank requested
              $xsmall = 0;
              $small = 0;
              $medium = 0;
              $large = 0;
              $xlarge = 0;
              $xxlarge = 0;
              
              $code = 'Order';
              $comments = $stock->comments;

              if ($stockid = $this->stocks->insert_order_item($itemcode, $styleid, $code, $orderid, $description)) {
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
                if ($this->stocks->update_order_short($stockid, $orderid, $shortnumpos, $shortnumsize, $shortnumcolor, $shortnumtrimcolor, $shortlogopos, $shortlogoname, $shortlogocolor, $shortlogotrimcolor) ) {
                  $error = $error.' shorts added ';
                } else {
                  $error = $error.' shorts unchanged ';
                }

              }
            }
          }
          //duplicated artwork records
          if ($artworks = $this->artworks->select_artworks_by_order($base_orderid)) {
            foreach($artworks->result() as $artwork) {
              $artworksource = $artwork->artworksource;
              $artworkstatus = $artwork->artworkstatus;
              $filename = $artwork->filename;
              $comment = $artwork->comment;
              $uploadby = $this->session->userdata('uf_username');
              $artworkid = $this->artworks->insert_artwork($artworksource, $artworkstatus, $comment, $orderid, $stockid, $uploadby);
              $this->artworks->update_filename($artworkid, $filename);
            }
            $error = $error.' artwork added ';
          }
        }
        $this->form_update_order($clients_start,$clientid,$orderid,0,0,$error);
      } else {
        redirect('/clientorders/select/'.$clients_start.'/'.$clientid.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function update_status($clients_start = 0, $clientid = 0, $orderid = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('orderstatus', 'OrderStatus', 'trim|xss_clean');
    $this->form_validation->set_rules('updcomment', 'UpdComment', 'trim|max_length[100]|xss_clean');

    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_update($uf_orders)) {
        if ($this->form_validation->run() == TRUE)
        {
          $orderstatus = $this->input->get_post('orderstatus',TRUE);
          $updcomment = $this->input->get_post('updcomment',TRUE);
          $updby = $this->session->userdata('uf_username');

          if ($this->orders->update_status($orderid, $orderstatus, $updcomment, $updby)) {
            $this->select($clients_start,$clientid,$orderid,INFO_SUCCESS);
          } else {
            $this->select($clients_start,$clientid,$orderid,INFO_UNCHANGED);
          }
        } else {
          $this->select($clients_start,$clientid,$orderid,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/clientorders/select/'.$clients_start.'/'.$clientid.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function insert_artwork($clients_start = 0, $clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('artworksource', 'ArtworkSource', 'trim|xss_clean');
    $this->form_validation->set_rules('artworkstatus', 'ArtworkStatue', 'trim|xss_clean');
    $this->form_validation->set_rules('comment', 'Comment', 'trim|max_length[100]|xss_clean');

    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_update($uf_orders)) {
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
            //$config['max_width'] = '1024';
            //$config['max_height'] = '768';
            $config['file_name'] = 'artwork-'.$orderid.'-'.$artworkid;

            $this->upload->initialize($config);
            if (!$this->upload->do_upload('filename'))
            {
              $upload = $this->upload->display_errors();
              $this->form_update_order($clients_start,$clientid,$orderid,0,0,$upload);
            }
            else
            {
              $upload = $this->upload->data();
              $this->artworks->update_filename($artworkid, $upload['file_name']);
              $this->form_update_order($clients_start,$clientid,$orderid,0,0,INFO_SUCCESS);
            }
          } else {
            $this->select($clients_start,$clientid,$orderid,INFO_UNCHANGED);
            $this->form_update_order($clients_start,$clientid,$orderid,0,0,INFO_UNCHANGED);
          }
        } else {
          $this->form_update_order($clients_start,$clientid,$orderid,0,0,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/clientorders/select/'.$clients_start.'/'.$clientid.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function update_item($clients_start = 0, $clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('itemcode', 'ItemCode', 'trim|required|max_length[45]|xss_clean');
    $this->form_validation->set_rules('styleid', 'StyleId', 'trim|xss_clean');

    $this->form_validation->set_rules('description', 'Description', 'trim|max_length[250]|xss_clean');

    $this->form_validation->set_rules('frontlogopos', 'FrontLogoPos', 'trim|xss_clean');
    $this->form_validation->set_rules('frontlogoname', 'FrontLogoName', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('frontlogocolor', 'FrontLogoColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('frontlogotrimcolor', 'FrontLogoTrimColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('frontnumpos', 'FrontNumPos', 'trim|xss_clean');
    $this->form_validation->set_rules('frontnumsize', 'FrontNumSize', 'trim|xss_clean');
    $this->form_validation->set_rules('frontnumcolor', 'FrontNumColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('frontnumtrimcolor', 'FrontNumTrimColor', 'trim|max_length[50]|xss_clean');

    $this->form_validation->set_rules('rearlogopos', 'RearLogoPos', 'trim|xss_clean');
    $this->form_validation->set_rules('rearlogoname', 'RearLogoName', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('rearlogocolor', 'RearLogoColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('rearlogotrimcolor', 'RearLogoTrimColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('rearnumpos', 'RearNumPos', 'trim|xss_clean');
    $this->form_validation->set_rules('rearnumsize', 'RearNumSize', 'trim|xss_clean');
    $this->form_validation->set_rules('rearnumcolor', 'RearNumColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('rearnumtrimcolor', 'RearNumTrimColor', 'trim|max_length[50]|xss_clean');

    $this->form_validation->set_rules('shortlogopos', 'ShortLogoPos', 'trim|xss_clean');
    $this->form_validation->set_rules('shortlogocolor', 'ShortLogoColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('shortlogotrimcolor', 'ShortLogoTrimColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('shortnumpos', 'ShortNumPos', 'trim|xss_clean');
    $this->form_validation->set_rules('shortnumsize', 'ShortNumSize', 'trim|xss_clean');
    $this->form_validation->set_rules('shortnumcolor', 'ShortNumColor', 'trim|max_length[50]|xss_clean');
    $this->form_validation->set_rules('shortnumtrimcolor', 'ShortNumTrimColor', 'trim|max_length[50]|xss_clean');

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

      if ($this->codes->role_order_update($uf_orders)) {
        if ($this->form_validation->run() == TRUE)
        {
          $itemcode = $this->input->get_post('itemcode',TRUE);
          //$order = $this->orders->select_order_by_id($orderid);
          $stockid = $stocks_edit;
          $stock = $this->stocks->select_stock_by_id($stocks_edit);

          if ($itemcode !== $stock->itemcode) {
            $this->stocks->update_order_item($stockid, $orderid, $itemcode, 0);
          } 
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
          $shortlogoname = $this->input->get_post('shortlogoname',TRUE);
          $shortlogocolor = $this->input->get_post('shortlogocolor',TRUE);
          $shortlogotrimcolor = $this->input->get_post('shortlogotrimcolor',TRUE);
          $shortnumpos = $this->input->get_post('shortnumpos',TRUE);
          $shortnumsize = $this->input->get_post('shortnumsize',TRUE);
          $shortnumcolor = $this->input->get_post('shortnumcolor',TRUE);
          $shortnumtrimcolor = $this->input->get_post('shortnumtrimcolor',TRUE);

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

          $error = '';
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
          if ($this->stocks->update_order_short($stockid, $orderid, $shortnumpos, $shortnumsize, $shortnumcolor, $shortnumtrimcolor, $shortlogopos, $shortlogoname, $shortlogocolor, $shortlogotrimcolor) ) {
            $error = $error.' shorts changed ';
          } else {
            $error = $error.' short unchanged ';
          }
          $this->form_update_order($clients_start,$clientid,$orderid,$stocks_edit,0,$error);
        } else {
          $this->form_update_order($clients_start,$clientid,$orderid,$stocks_edit,$artworks_edit,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/clientorders/select/'.$clients_start.'/'.$clientid.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }

  }

  public function update_artwork($clients_start = 0, $clientid = 0, $orderid = 0, $stocks_edit = 0, $artworks_edit = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('artworksource', 'ArtworkSource', 'trim|xss_clean');
    $this->form_validation->set_rules('artworkstatus', 'ArtworkStatue', 'trim|xss_clean');
    $this->form_validation->set_rules('comment', 'Comment', 'trim|max_length[100]|xss_clean');

    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_update($uf_orders)) {
        if ($this->form_validation->run() == TRUE)
        {
          $artworksource = $this->input->get_post('artworksource',TRUE);
          $artworkstatus = $this->input->get_post('artworkstatus',TRUE);
          $comment = $this->input->get_post('comment',TRUE);
          $uploadby = $this->session->userdata('uf_username');
          $artworkid = $artworks_edit;

          $this->artworks->update_artwork($artworkid, $artworksource, $artworkstatus, $comment, $uploadby);
          $config['upload_path'] = './artworks/';
          $config['allowed_types'] = 'gif|jpg|png|doc|pdf|xls';
          $config['overwrite'] = TRUE;
          $config['max_size'] = '0';
          //$config['max_width'] = '1024';
          //$config['max_height'] = '768';
          $config['file_name'] = 'artwork-'.$orderid.'-'.$artworkid;

          $this->upload->initialize($config);
          if (!$this->upload->do_upload('filename'))
          {
            $upload = $this->upload->display_errors();
            $this->form_update_order($clients_start,$clientid,$orderid,$stocks_edit,$artworks_edit,$upload);
          }
          else
          {
            $upload = $this->upload->data();
            $this->artworks->update_filename($artworkid, $upload['file_name']);
            $this->form_update_order($clients_start,$clientid,$orderid,$stocks_edit,$artworks_edit,INFO_SUCCESS);
          }
        } else {
          $this->form_update_order($clients_start,$clientid,$orderid,$stocks_edit,$artworks_edit,ERROR_FORM_VALIDATION);
        }
      } else {
        redirect('/clientorders/select/'.$clients_start.'/'.$clientid.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function insert_feedback($clients_start = 0, $clientid = 0, $orderid = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('feedback', 'Feedback', 'trim|required|min_length[2]|max_length[250]|xss_clean');

    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_update($uf_orders)) {
        if ($this->form_validation->run() == TRUE)
        {
          $feedback = $this->input->get_post('feedback',TRUE);
          $createdby = $this->session->userdata('uf_username');

          if ($fbid = $this->feedbacks->insert_feedback($feedback, $orderid, $createdby)) {
            $this->select($clients_start,$clientid,$orderid,INFO_SUCCESS);
          } else {
            $this->select($clients_start,$clientid,$orderid,INFO_UNCHANGED);
          }
        } else {
          $this->select($clients_start,$clientid,$orderid,ERROR_FORM_VALIDATION);
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
    echo 'Client Order Item and Artwork - maintenance.';
  }

}
?>
