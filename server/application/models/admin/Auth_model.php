<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
   
*/
class Auth_model extends CI_Model {  

    public function __construct()
    {  
      $this->load->library('session');
      parent::__construct();
    }

    public function userinfo()
    {
       $userinfo = $this->session->userdata('admininfo');
       return $userinfo;
    }

    public function set_user($user)
    {
      $data = array(
        'uid'=>$user['uid'],
        'username'=>$user['username'],
        'name'=>$user['name'],
        'role'=>$user['role'],
        'status'=>$user['status'],
        'ip'=> $this->input->ip_address(),
        'ctime'=>time()
       );
      $this->session->set_userdata('admininfo',$data);
    }

   
    public function is_login()
    {
       $user = $this->userinfo();
       return $user?true:false; 
    }

}