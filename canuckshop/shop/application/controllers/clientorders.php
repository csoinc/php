<?php
class Clientorders extends CI_Controller {
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
    $this->load->helper(array('form', 'url'));
    $this->load->language('properties');

    $this->load->model('clients');
    $this->load->model('orders');
    $this->load->model('codes');

    $this->title = "Client Orders";
    $this->trip = '<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Clients';
    $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Orders';
  }

  public function select($clients_start = 0, $clientid = 0, $orders_edit = 0, $message = '')
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_update($uf_orders)) {
        $data['clients_start'] = $clients_start;
        $data['clientid'] = $clientid;
        $data['orders_edit'] = $orders_edit;

        $this->template->write_view('header', 'common/admin_header');
        $this->template->write('message', $message);
        $this->template->write('title', $this->title);
        $this->template->write('trip', $this->trip);

        if ($clientid == 0) redirect('/uniformclients/select/'.$clients_start.'/0/Search_clients', 'refresh');

        $client = $this->clients->select($clientid);

        if ($data['rows'] = $this->orders->select_orders_by_client($clientid, $client->contact, $client->account, $client->telephone, $client->email, 'DESC')) {

          $table_settings=array(
              'table_open' => '<table class="zebraTable" width="1000">',
              'heading_row_start' => '<tr class="rowEven">',
              'row_start' => '<tr class="rowOdd">',
              'row_alt_start' => '<tr class="rowEven">'
          );
          $this->table->set_template($table_settings); //apply the settings

          $this->table->set_heading('Order #', 'Date Needed', 'Date In', 'Client Info', 'Status', 'Actions');

          foreach($data['rows']->result() as $row)
          {
            $edit_col = sprintf('<a href="%s/clientorderitems/form_update_order/%d/%d/%s" title="Client Order - Edit"><img src="%s/images/buttons/small_edit.gif" /></a>'
                , WEB_CONTEXT, $clients_start, $clientid, (string)$row->orderid, WEB_CONTEXT);
            $orderitems_col = sprintf('<a href="%s/clientorderitems/select/%d/%d/%s" title="Client Order Items - View"><img src="%s/images/buttons/small_view.gif" /></a>'
                , WEB_CONTEXT, $clients_start, $clientid, (string)$row->orderid, WEB_CONTEXT);

            $repeat_col = '';
            
            if ($row->status == 'Shipped Out' || $row->status == 'Completed') {
              $repeat_col = sprintf('<a href="%s/clientorderitems/repeat_order/%d/%d/%s" title="Client Order - Repeat"><img src="%s/images/buttons/button_copy.png" width=45 /></a>'
                  , WEB_CONTEXT, $clients_start, $clientid, (string)$row->orderid, WEB_CONTEXT);
            } 

            $oid_col = sprintf('<a href="%s/clientorderitems/select/%d/%d/%s" title="Client Order Items - List">%s</a>', WEB_CONTEXT, $clients_start, $clientid, (string)$row->orderid, $row->orderid);
            $date_col = sprintf('<a href="%s/clientorderitems/select/%d/%d/%s" title="Client Order Items - List">%s</a>', WEB_CONTEXT, $clients_start, $clientid, (string)$row->orderid, substr($row->orderdate,0,10));
            $this->table->add_row($oid_col,substr($row->requireddate,0,10),$date_col,$row->name.'<br>'.$row->contact.'<br>'.$row->email.'<br>phone:'.$row->telephone.'<br>cell:'.$row->cellphone.'<br>'.
                $row->address.'<br>'.$row->zipcode,$row->status,$edit_col.'&nbsp;'.$orderitems_col.'&nbsp;'.$repeat_col);
          }
          $data['client_orders_table'] = $this->table->generate();
        }
        $this->template->write_view('content', 'client_orders', $data);

        // Write to $content
        $this->template->write_view('footer', 'common/footer');
        // Render the template
        $this->template->render();
      } else {
        redirect('/uniformclients/select/'.$clients_start.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function form_confirm_client($clients_start = 0, $clientid = 0, $message = '')
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_update($uf_orders)) {
        $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;New';

        $data['clients_start'] = $clients_start;
        $data['clientid'] = $clientid;
        $data['client'] = $this->clients->select($clientid);

        $this->template->write_view('header', 'common/admin_header');
        $this->template->write('message', $message);
        $this->template->write('title', $this->title);
        $this->template->write('trip', $this->trip);

        $this->template->write_view('content', 'client_order_form', $data);

        // Write to $content
        $this->template->write_view('footer', 'common/footer');
        // Render the template
        $this->template->render();
      } else {
        redirect('/uniformclients/select/'.$clients_start.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }

  }

  public function confirm_client($clients_start = 0, $clientid = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('name', 'Name', 'trim|max_length[200]|xss_clean');
    $this->form_validation->set_rules('contact', 'Contact', 'trim|required|min_length[3]|max_length[50]|xss_clean');
    //$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|max_length[100]|xss_clean');
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
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      $data['clients_start'] = $clients_start;
      $data['clientid'] = $clientid;

      if ($this->codes->role_order_update($uf_orders)) {
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

          if ($orderid = $this->orders->insert($clientid,$name,$contact,$email,$telephone,$cellphone,$address,$zipcode,$shippingaddr,$shippingzip,$orderdate,$requireddate,$payment,$expdate,$comments,$createdby)) {
            redirect('/clientorderitems/form_insert_order/'.$clients_start.'/'.$clientid.'/'.$orderid.'/0/0/Successful', 'refresh');
          } else {
            $this->form_confirm_client($clients_start,$clientid,INFO_UNCHANGED);
          }
        } else {
          $this->form_confirm_client($clients_start,$clientid,ERROR_FORM_VALIDATION);
        }
      } else {
        $this->form_confirm_client($clients_start,$clientid,INFO_UNAUTHORISED);
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function comments()
  {
    echo 'Client Orders - maintenance.';
  }

}
?>
