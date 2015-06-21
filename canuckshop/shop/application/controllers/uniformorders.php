<?php
class Uniformorders extends CI_Controller {
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

    $this->load->model('orders');
    $this->load->model('codes');

    $this->title = "Uniform Orders";
    $this->trip = '<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Orders';
  }

  public function index($message = 'List') {
    redirect('/uniformorders/select/0/0/'.$message, 'refresh');
  }

  public function search($orders_start = 0, $message = 'Search')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('orders_what', 'What', 'trim|xss_clean');
    $this->form_validation->set_rules('select_orders_status', 'Status', 'trim|xss_clean');
    $this->form_validation->set_rules('select_query_sort', 'Sort', 'trim|xss_clean');
    $this->form_validation->set_rules('orders_fromdate', 'FromDate', 'trim|xss_clean');
    $this->form_validation->set_rules('orders_todate', 'ToDate', 'trim|xss_clean');
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');
      if ($this->codes->role_order_read($uf_orders) || $this->codes->role_order_update($uf_orders)) {
        if ($this->form_validation->run() == TRUE)
        {
          $orders_what = $this->input->get_post('orders_what',TRUE);
          $orders_status = $this->input->get_post('select_orders_status',TRUE);
          $query_sort = $this->input->get_post('select_query_sort',TRUE);
          $orders_fromdate = $this->input->get_post('orders_fromdate',TRUE);
          $orders_todate = $this->input->get_post('orders_todate',TRUE);
          $this->session->set_userdata('orders_what', $orders_what);
          $this->session->set_userdata('orders_status', $orders_status);
          $this->session->set_userdata('query_sort', $query_sort);
          $this->session->set_userdata('orders_fromdate', $orders_fromdate);
          $this->session->set_userdata('orders_todate', $orders_todate);
          $this->select(0,0,INFO_SUCCESS);
        } else {
          $this->select(0,0,ERROR_FORM_VALIDATION);
        }
      } else {
        $this->select(0,0,INFO_UNAUTHORISED);
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }

  }

  public function select($orders_start = 0, $orders_edit = 0, $message = '')
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_read($uf_orders) || $this->codes->role_order_update($uf_orders)) {
        $orders_what = $this->session->userdata('orders_what');
        $orders_status = $this->session->userdata('orders_status');
        $query_sort = $this->session->userdata('query_sort');
        $orders_fromdate = $this->session->userdata('orders_fromdate');
        $orders_todate = $this->session->userdata('orders_todate');
        
        if (!isset($orders_status) || $orders_status == '') $orders_status = '';

        $data['orders_start'] = $orders_start;
        $data['orders_what'] = $orders_what;
        $data['orders_status'] = $orders_status;
        $data['orders_edit'] = $orders_edit;
        $data['query_sort'] = $query_sort;
        $data['orders_fromdate'] = $orders_fromdate;
        $data['orders_todate'] = $orders_todate;
        
        if ($message != '') {
          $message = '<img src="'.WEB_CONTEXT.'/images/icons/icon_info.gif" />&nbsp;&nbsp'.$message;
          if ($orders_start != 0) $message = $message.'_list_ start_'.$orders_start;
        } else {
          $message = '<img src="'.WEB_CONTEXT.'/images/icons/icon_info.gif" />&nbsp;&nbspList_start_'.$orders_start;
        }

        $this->template->write_view('header', 'common/admin_header');
        $this->template->write('message', urldecode($message));
        $this->template->write('title', $this->title);
        $this->template->write('trip', $this->trip);


        $data['orders_status_list'] = $this->codes->select_search_code_order_status();
        $data['orders_status_options'] = 'id="orders_status" size="1" onchange="this.form.submit()"';

        $data['query_sort_list'] = $this->codes->select_query_sort();
        $data['query_sort_options'] = 'id="query_sort" size="1" onchange="this.form.submit()"';
        
        if ($data['rows'] = $this->orders->search_within_date($orders_what, $orders_status, $query_sort, $orders_fromdate, $orders_todate)) {

          $config['base_url'] = base_url().'/uniformorders/select/';
          $config['total_rows'] = $data['rows']->num_rows();
          $config['per_page'] = '10';
          $config['full_tag_open'] = '<p>';
          $config['full_tag_close'] = '</p>';
          $this->pagination->initialize($config);
          $table_settings=array(
                        'table_open' => '<table class="zebraTable" width="1000">',
                        'heading_row_start' => '<tr class="rowEven">',
                        'row_start' => '<tr class="rowOdd">',
                        'row_alt_start' => '<tr class="rowEven">'
          );
          $this->table->set_template($table_settings); //apply the settings

          $this->table->set_heading('Date Needed', 'Date In', 'Client Info', 'Items', 'Actions');

          $orders_end = $orders_start + 10;
          $r = 0;

          foreach($data['rows']->result() as $row)
          {
            if ($r >= $orders_start && $r < $orders_end) {
              $edit_col = sprintf('<a href="%s/clientorderitems/form_update_order/%d/0/%s" title="Order - Edit"><img src="%s/images/buttons/small_edit.gif" /></a>'
              , WEB_CONTEXT, $orders_start, (string)$row->orderid, WEB_CONTEXT);
              $orderitems_col = sprintf('<a href="%s/uniformorderitems/select/%d/%s" title="Order Items - List"><img src="%s/images/buttons/small_view.gif" /></a>'
              , WEB_CONTEXT, $orders_start, $row->orderid, WEB_CONTEXT);
              $oid_col = sprintf('<a href="%s/uniformorderitems/select/%d/%s" title="Order Items - List">%s</a>', WEB_CONTEXT, $orders_start, (string)$row->orderid, $row->orderid);
              
              $client_col = $row->name.'<br/>'.$row->contact.'<br/>'.$row->email.'<br/>phone:'.$row->telephone.'<br/>cell:'.$row->cellphone;
              
              $items_col = $row->itemcodes.'<br/>'.$row->colornames.'<br/>'.$row->subtotals;
              
              $date_col = sprintf('<a href="%s/uniformorderitems/select/%d/%s" title="Order Items - List">%s</a>', WEB_CONTEXT, $orders_start, (string)$row->orderid, substr($row->orderdate,0,10));
              $this->table->add_row(substr($row->requireddate,0,10),$date_col,$client_col,$items_col,$edit_col.'&nbsp;&nbsp;'.$orderitems_col);
            }
            $r++;
          }
          $data['orders_table'] = $this->table->generate();
        }
        $data['totalrow'] = $this->orders->search_within_date_total($orders_what, $orders_status, $query_sort, $orders_fromdate, $orders_todate);
        $this->template->write_view('content', 'orders', $data);

        // Write to $content
        $this->template->write_view('footer', 'common/footer');
        // Render the template
        $this->template->render();
      }

    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function comments()
  {
    echo 'Uniform Orders - maintenance.';
  }


}
?>
