<?php
class Accounts extends CI_Controller {

  private $title;
  private $trip;

  public function __construct()
  {
    parent::__construct();
    $this->load->library('template');
    $this->load->library('session');
    $this->load->library('form_validation');
    $this->load->helper(array('form', 'url'));
    $this->load->language('properties');
    $this->load->model('users');
    $this->load->model('clients');
    $this->load->model('codes');
    $this->load->model('messages');

    $this->title = "Account";
    $this->trip = '<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Account';
  }

  public function index()
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if (($this->codes->role_order_read($uf_orders)) || ($this->codes->role_order_update($uf_orders))) {
        redirect('/uniformorders/', 'refresh');
      }

      if (($this->codes->role_stock_read($uf_stocks)) || ($this->codes->role_stock_update($uf_stocks))) {
        redirect('/uniforms/', 'refresh');
      }

      if (($this->codes->role_uniform_read($uf_uniforms)) || ($this->codes->role_uniform_update($uf_uniforms))) {
        redirect('/uniforms/', 'refresh');
      }

      redirect('/customerorders/', 'refresh');

    } else {
      $this->form_login();
    }
  }


  public function form_login($message = '')
  {
    $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Login';
    $this->template->write_view('header', 'common/header');
    $this->template->write('message', $message);
    $this->template->write('title', $this->title);
    $this->template->write('trip', $this->trip);
    $this->template->write_view('content', 'login_form');
    // Write to $content
    $this->template->write_view('footer', 'common/footer');
    // Render the template
    $this->template->render();
  }

  public function login()
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
    $this->form_validation->set_rules('password', 'Password', 'trim|required');
    if ($this->form_validation->run() == TRUE)
    {
      $data['email'] = $this->input->get_post('email',TRUE);
      $data['password'] = $this->input->get_post('password',TRUE);
      if ($data['row'] = $this->users->select_user_by_id($data['email'], $data['password'])) {
        $sessiondata = array(
              'uf_userid'=> $data['row']->userid,
              'uf_username'=> $data['row']->username,
              'uf_email' => $data['row']->email,
              'uf_orders' => $data['row']->orders,
          	  'uf_stocks' => $data['row']->stocks,
              'uf_uniforms' => $data['row']->uniforms,
              'uf_clientid' => $data['row']->clientid,
          	  'uf_logged_in' => TRUE
        );
        $this->session->set_userdata($sessiondata);
        $this->index();
      } else {
        $this->form_login(ERROR_USER_LOGIN);
      }
    } else {
      $this->form_login(ERROR_FORM_VALIDATION);
    }

  }

  public function logout()
  {
    $this->session->sess_destroy();
    $this->form_login(INFO_SUCCESS);
  }

  public function form_password($message = '')
  {
    $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Password';
    $this->template->write_view('header', 'common/header');
    $this->template->write('message', $message);
    $this->template->write('title', $this->title);
    $this->template->write('trip', $this->trip);
    $this->template->write_view('content', 'password_form');
    // Write to $content
    $this->template->write_view('footer', 'common/footer');
    // Render the template
    $this->template->render();
  }

  public function forgot_password()
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
    if ($this->form_validation->run() == TRUE)
    {
      $email = $this->input->get_post('email',TRUE);
      if ($password = $this->users->select_password_by_email($email)) {
        $subject = 'Message from http://uniforms.canuckstuff.com';
        $message = 'Dear Customer,<br><br>Your login name is <b>'.$email.'</b>&nbsp;';
        $message = $message.'and your password is <b>'.$password.'</b><br><br>';
        $message = $message.'Thanks,<br><b>Canuck Stuff Uniforms</b><br>';
        $message = $message.'Address: 2161 Midland Ave. Unit 2, Scarborough, ON, M1P 4T3<br>';
        $message = $message.'Telephone: (416)299-1704 or 1-800-968-9306<br>';
        $message = $message.'Fax: (416)609-9604 or 1-800-968-9456<br>';
        $message = $message.'Email: uniforms@canuckstuff.com<br>';

        $this->messages->send($email, $subject, $message);
        $this->form_login(INFO_SEND_PASSWORD);
      } else {
        $this->form_password(ERROR_EMAIL_NOTFOUND);
      }
    } else {
      $this->form_password(ERROR_FORM_VALIDATION);
    }
  }

  public function form_admin_profile($message = '')
  {
    $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Profile';
    if ($this->session->userdata('uf_logged_in')) {
      $this->template->write_view('header', 'common/admin_header');
      $this->template->write('message', $message);
      $this->template->write('title', $this->title);
      $this->template->write('trip', $this->trip);

      $data['username'] = $this->session->userdata('uf_username');

      $this->template->write_view('content', 'admin_profile_form', $data);
      // Write to $content
      $this->template->write_view('footer', 'common/footer');
      // Render the template
      $this->template->render();
    } else {
      $this->form_login();
    }
  }

  public function admin_profile()
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|max_length[50]|xss_clean');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[15]|xss_clean');
    $this->form_validation->set_rules('confirm_password', 'ConfirmPassword', 'trim|required|matches[password]');
    if ($this->session->userdata('uf_logged_in')) {
      if ($this->form_validation->run() == TRUE)
      {
        $email = $this->session->userdata('uf_email');
        $username = $this->input->get_post('username',TRUE);
        $password = $this->input->get_post('password',TRUE);
        if ($this->users->update_admin_profile($email, $username, $password)) {
          $this->session->sess_destroy();
          $this->form_login(INFO_SUCCESS);
        } else {
          $this->form_admin_profile(INFO_UNCHANGED);
        }
      } else {
        $this->form_admin_profile(ERROR_FORM_VALIDATION);
      }
    }
    else {
      $this->form_login();
    }
  }

  public function form_customer_profile($message = '')
  {
    $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Profile';
    if ($this->session->userdata('uf_logged_in')) {
      $data['username'] = $this->session->userdata('uf_username');
      $clientid = $this->session->userdata('uf_clientid');
      $data['client'] = $this->clients->select($clientid);

      $this->template->write_view('header', 'common/customer_header');
      $this->template->write('message', $message);
      $this->template->write('title', $this->title);
      $this->template->write('trip', $this->trip);

      $this->template->write_view('content', 'customer_profile_form', $data);
      // Write to $content
      $this->template->write_view('footer', 'common/footer');
      // Render the template
      $this->template->render();
    } else {
      $this->form_login();
    }
  }

  public function customer_profile($message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('contact', 'Contact', 'trim|required|min_length[3]|max_length[50]|xss_clean');
    $this->form_validation->set_rules('telephone', 'Telephone', 'trim|required|min_length[12]|max_length[12]|callback_phone_check|xss_clean');
    $this->form_validation->set_rules('cellphone', 'Contact', 'trim|max_length[15]|xss_clean');
    $this->form_validation->set_rules('fax', 'Fax', 'trim|max_length[15]|xss_clean');

    //$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
    //$this->form_validation->set_rules('confirm_email', 'ConfirmEmail', 'trim|required|matches[email]');

    $this->form_validation->set_rules('password', 'Password', 'trim|min_length[4]|max_length[15]|xss_clean');
    $this->form_validation->set_rules('confirm_password', 'ConfirmPassword', 'trim|matches[password]');

    $this->form_validation->set_rules('name', 'School', 'trim|max_length[100]|xss_clean');
    $this->form_validation->set_rules('team', 'Team', 'trim|max_length[100]|xss_clean');

    $this->form_validation->set_rules('street', 'Street', 'trim|required|min_length[5]|max_length[100]|xss_clean');
    $this->form_validation->set_rules('city', 'City', 'trim|required|min_length[2]|max_length[50]|xss_clean');
    $this->form_validation->set_rules('province', 'Province', 'trim|required|min_length[2]|max_length[40]|xss_clean');
    $this->form_validation->set_rules('zipcode', 'ZipCode', 'trim|required|min_length[3]|max_length[40]|xss_clean');

    if ($this->session->userdata('uf_logged_in')) {

      if ($this->form_validation->run() == TRUE)
      {
        $contact = $this->input->get_post('contact',TRUE);
        $telephone = $this->input->get_post('telephone',TRUE);
        $cellphone = $this->input->get_post('cellphone',TRUE);
        $fax = $this->input->get_post('fax',TRUE);
        //$email = $this->input->get_post('email',TRUE);
        $password = $this->input->get_post('password',TRUE);
        $name = $this->input->get_post('name',TRUE);
        $team = $this->input->get_post('team',TRUE);
        $street = $this->input->get_post('street',TRUE);
        $city = $this->input->get_post('city',TRUE);
        $province = $this->input->get_post('province',TRUE);
        $zipcode = $this->input->get_post('zipcode',TRUE);

        $email = $this->session->userdata('uf_email');
        $clientid = $this->session->userdata('uf_clientid');

        if ($this->clients->update($clientid,$name,$team,$contact,$email,$telephone,$cellphone,$fax,$street,$city,$province,$zipcode,'online')) {
          if (isset($password) && $password != '') {
            $this->users->update_customer_profile($email, $contact, $password);
          } else {
            $this->users->update_username($email, $contact);
          }
        }
        $this->form_customer_profile(INFO_SUCCESS);
      }
      else {
        $this->form_customer_profile(ERROR_FORM_VALIDATION);
      }
    }
    else {
      $this->form_login();
    }
  }

  public function form_register($message = '')
  {
    $this->trip = $this->trip.'&nbsp;<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Register';
    $this->template->write_view('header', 'common/header');
    $this->template->write('message', $message);
    $this->template->write('title', $this->title);
    $this->template->write('trip', $this->trip);

    $data['username'] = $this->session->userdata('uf_username');

    $this->template->write_view('content', 'customer_register_form', $data);
    // Write to $content
    $this->template->write_view('footer', 'common/footer');
    // Render the template
    $this->template->render();
  }

  public function register()
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('contact', 'Contact', 'trim|required|min_length[3]|max_length[50]|xss_clean');
    $this->form_validation->set_rules('telephone', 'Telephone', 'trim|required|min_length[12]|max_length[12]|callback_phone_check|xss_clean');
    $this->form_validation->set_rules('cellphone', 'Contact', 'trim|max_length[15]|xss_clean');
    $this->form_validation->set_rules('fax', 'Fax', 'trim|max_length[15]|xss_clean');

    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
    $this->form_validation->set_rules('confirm_email', 'ConfirmEmail', 'trim|required|matches[email]');

    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[15]|xss_clean');
    $this->form_validation->set_rules('confirm_password', 'ConfirmPassword', 'trim|required|matches[password]');

    $this->form_validation->set_rules('name', 'School', 'trim|max_length[100]|xss_clean');
    $this->form_validation->set_rules('team', 'Team', 'trim|max_length[100]|xss_clean');

    $this->form_validation->set_rules('street', 'Street', 'trim|required|min_length[5]|max_length[100]|xss_clean');
    $this->form_validation->set_rules('city', 'City', 'trim|required|min_length[2]|max_length[50]|xss_clean');
    $this->form_validation->set_rules('province', 'Province', 'trim|required|min_length[2]|max_length[40]|xss_clean');
    $this->form_validation->set_rules('zipcode', 'ZipCode', 'trim|required|min_length[3]|max_length[40]|xss_clean');

    if ($this->form_validation->run() == TRUE)
    {
      $contact = $this->input->get_post('contact',TRUE);
      $telephone = $this->input->get_post('telephone',TRUE);
      $cellphone = $this->input->get_post('cellphone',TRUE);
      $fax = $this->input->get_post('fax',TRUE);
      $email = $this->input->get_post('email',TRUE);
      $password = $this->input->get_post('password',TRUE);
      $name = $this->input->get_post('name',TRUE);
      $team = $this->input->get_post('team',TRUE);
      $street = $this->input->get_post('street',TRUE);
      $city = $this->input->get_post('city',TRUE);
      $province = $this->input->get_post('province',TRUE);
      $zipcode = $this->input->get_post('zipcode',TRUE);

      $error = false;

      if ($this->users->select_password_by_email($email)) {
        $this->form_register('Your email address was exist in our system');
      } else {
        if ($client = $this->clients->select_client_by_contact_telephone($contact, $telephone)) {
          $clientid = $client->clientid;
          $this->clients->update($clientid,$name,$team,$contact,$email,$telephone,$cellphone,$fax,$street,$city,$province,$zipcode,'online');
          //Create new user with this clientid
          $order_role = $this->codes->select_value_by_name('Owner Update', 'RoleOrder');
          if ($this->users->insert($password,$order_role,$contact,$email,$clientid)) $error = false;
          else $error = true;
        } else {
          //Create new client first
          if ($clientid = $this->clients->insert($name,$team,$contact,$email,$telephone,$cellphone,$fax,$street,$city,$province,$zipcode,'online')) {
            //Create new user with this clientid
            $order_role = $this->codes->select_value_by_name('Owner Update', 'RoleOrder');
            if ($this->users->insert($password,$order_role,$contact,$email,$clientid)) $error = false;
            else $error = true;
          } else {
            $error = true;
          }

        }
        if ($error) {
          $this->form_register(ERROR_ACCOUNT_CREATION);
        } else {
          $subject = 'Message from http://uniforms.canuckstuff.com';
          $message = 'Dear '.$contact.',<br><br>Welcome to Canuck Stuff Uniforms system:<br>Your login name is <b>'.$email.'</b>&nbsp;';
          $message = $message.'and your password is <b>'.$password.'</b><br><br>';
          $message = $message.'Thank you,<br><b>Canuck Stuff Uniforms</b><br>';
          $message = $message.'Address: 2161 Midland Ave. Unit 2, Scarborough, ON, M1P 4T3<br>';
          $message = $message.'Telephone: (416)299-1704 or 1-800-968-9306<br>';
          $message = $message.'Fax: (416)609-9604 or 1-800-968-9456<br>';
          $message = $message.'Email: uniforms@canuckstuff.com<br>';
          $this->messages->send($email, $subject, $message);
          $this->form_login(INFO_SUCCESS);
        }
      }
    } else {
      $this->form_register(ERROR_FORM_VALIDATION);
    }
  }

  public function phone_check($phone)
  {
    if (preg_match("/[0-9]{3}[-]{1}[0-9]{3}[-]{1}[0-9]{4}/", $phone)) {
      return TRUE;
    }
    else
    {
      $this->form_validation->set_message('phone_check', 'The %s field must be ###-###-#### format');
      return FALSE;
    }
  }

  public function comments()
  {
    echo 'Account - account and authentication.';
  }


}
?>
