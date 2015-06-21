<?php
class Uniformorderitems extends CI_Controller {
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
    $this->load->model('styles');
    $this->load->model('stocks');
    $this->load->model('codes');
    $this->load->model('artworks');
    $this->load->model('feedbacks');

    $this->title = "Order Details";

    $this->trip = '<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Orders';
    $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Items';

  }

  public function select($orders_start = 0, $orderid = 0, $message = '')
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_order_read($uf_orders) || $this->codes->role_order_update($uf_orders)) {
        $data['orders_start'] = $orders_start;
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

        $this->template->write_view('content', 'order_items', $data);

        // Write to $content
        $this->template->write_view('footer', 'common/footer');
        // Render the template
        $this->template->render();
      }
      else {
        redirect('/uniformorders/select/'.$orders_start.'/'.$orderid.'/Unauthorised', 'refresh');
      }

    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function update_status($orders_start = 0, $orderid = 0, $message = '')
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
            //$this->select($orders_start,$orderid,INFO_SUCCESS);
            redirect('/uniformorders/select/'.$orders_start.'/'.$orderid.'/Success', 'refresh');
          } else {
            //$this->select($orders_start,$orderid,INFO_UNCHANGED);
            redirect('/uniformorders/select/'.$orders_start.'/'.$orderid.'/Unchanged', 'refresh');
          }
        } else {
          //$this->select($orders_start,$orderid,ERROR_FORM_VALIDATION);
          redirect('/uniformorders/select/'.$orders_start.'/'.$orderid.'/Failed', 'refresh');
        }
      } else {
        //$this->select($orders_start,$orderid,INFO_UNAUTHORISED);
        redirect('/uniformorders/select/'.$orders_start.'/'.$orderid.'/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function insert_artwork($orders_start = 0, $orderid = 0, $message = '')
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

          if ($artworkid = $this->artworks->insert_artwork($artworksource, $artworkstatus, $comment, $orderid, 0, $uploadby)) {
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
              $this->select($orders_start,$orderid,$upload);
            }
            else
            {
              $upload = $this->upload->data();
              $this->artworks->update_filename($artworkid, $upload['file_name']);
              $this->select($orders_start,$orderid,INFO_SUCCESS);
            }
          } else {
            $this->select($orders_start,$orderid,INFO_UNCHANGED);
          }
        } else {
          $this->select($orders_start,$orderid,ERROR_FORM_VALIDATION);
        }
      } else {
        $this->select($orders_start,$orderid,INFO_UNAUTHORISED);
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }

  }

  public function insert_feedback($orders_start = 0, $orderid = 0, $message = '')
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
            $this->select($orders_start,$orderid,INFO_SUCCESS);
          } else {
            $this->select($orders_start,$orderid,INFO_UNCHANGED);
          }
        } else {
          $this->select($orders_start,$orderid,ERROR_FORM_VALIDATION);
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
    echo 'Uniform Order Items - maintenance.';
  }

}
?>
