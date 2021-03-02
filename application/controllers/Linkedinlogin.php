<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'C:\xampp\htdocs\linkedinlogin\application\\vendor\autoload.php';
class Linkedinlogin extends CI_Controller {
  public function __construct() {
     parent::__construct();

     $this->load->model('linkedinlogin_model');
  }
  public function login($rurl=null)
  {
    $provider = new League\OAuth2\Client\Provider\LinkedIn([
      'clientId'      => '869k980eqb8r29',
      'clientSecret'  => 'MygfNaJRVlkKhzuY',
      'redirectUri'   => 'http://localhost/linkedin/linkedinlogin/login',
    ]);
    if (isset($_GET['code']))
    {
      $token = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);
      if(isset($token) && !empty($token))
      {
        $this->session->set_userdata('access_token', $token);
        $user = $provider->getResourceOwner($token);
        $current_datetime = date('Y-m-d H:i:s');

        if($this->linkedinlogin_model->Is_already_register($user->getId()))
        {
          //update data
          $user_data = array(
            'ln_id'      => $user->getId(),
            'first_name' => $user->getFirstName(),
            'last_name'  => $user->getLastName(),
            'name'       => $user->getFirstName().' '.$user->getLastName(),
            'email'      => $user->getEmail()
          );
          $this->linkedinlogin_model->Update_user_data($user_data, $user->getId());
        }
        else
        {
          //insert data
          $user_data = array(
            'id'         => $this->generator(15),
            'ln_id'      => $user->getId(),
            'first_name' => $user->getFirstName(),
            'last_name'  => $user->getLastName(),
            'name'       => $user->getFirstName().' '.$user->getLastName(),
            'email'      => $user->getEmail(),
            'created_at' => $current_datetime
          );
          $this->linkedinlogin_model->Insert_user_data($user_data);
        }
        $this->customer_session($user_data);
      }
    }
    $login_button = '';
    if(!$this->session->userdata('access_token'))
    {
      $authUrl = $provider->getAuthorizationUrl();
      $_SESSION['oauth2state'] = $provider->getState();
      header('Location: '.$authUrl);
      exit;
    }
    else
    {
      redirect(base_url());
    }  
  }

  // Create customer Session
  private function customer_session($result = [])
  {
    $key = md5(time());
    $key = str_replace("1", "z", $key);
    $key = str_replace("2", "J", $key);
    $key = str_replace("3", "y", $key);
    $key = str_replace("4", "R", $key);
    $key = str_replace("5", "Kd", $key);
    $key = str_replace("6", "jX", $key);
    $key = str_replace("7", "dH", $key);
    $key = str_replace("8", "p", $key);
    $key = str_replace("9", "Uf", $key);
    $key = str_replace("0", "eXnyiKFj", $key);
    $customer_sid_web = substr($key, rand(0, 3), rand(28, 32));
    
    // codeigniter session stored data      
    $user_data = array(
      'customer_sid_web' => $customer_sid_web,
      'ln_id'            => $result['ln_id'],
      'name'             => $result['name'],
      'first_name'       => $result['first_name'], 
      'last_name'        => $result['last_name'], 
      'email'            => $result['email'], 
    );
    $this->session->set_userdata($user_data);

    return TRUE;

  }

  //This function is used to Generate Key
  function generator($lenth)
  {
      $number = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "N", "M", "O", "P", "Q", "R", "S", "U", "V", "T", "W", "X", "Y", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

      for ($i = 0; $i < $lenth; $i++) {
          $rand_value = rand(0, 34);
          $rand_number = $number["$rand_value"];

          if (empty($con)) {
              $con = $rand_number;
          } else {
              $con = "$con" . "$rand_number";
          }
      }
      return $con;
  }

  function logout(){
    $this->session->sess_destroy();
  }
}