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
       $userinfo = $this->session->userdata('userinfo');
       if( !($userinfo && isset($userinfo['uid'])) )
       {
          $userinfo = $this->guestinfo();
       }
       return $userinfo;
    }

    public function set_user($user)
    {
      $data = array(
        'uid'=>$user['uid'],
        'username'=>$user['username'],
        'name'=>$user['name'],
        'role'=>$user['role'],
        'level'=>$user['level'],
        'status'=>$user['status'],
        'ip'=> $this->input->ip_address(),
        'ctime'=>time()
       );
      $this->session->set_userdata('userinfo',$data);
    }

    public function guestinfo()
    {
       
       $gusetinfo = $this->session->userdata('guestinfo');
       if( !($gusetinfo && isset($gusetinfo['uid'])) )
       {
          $gusetinfo = $this->set_guest();
       }
       return $gusetinfo;
    }

    public function set_guest()
    { 
      $num = intval(substr(time() . rand(), -8));
      $data = array(
        'uid'=>$num,
        'name'=>'游客'.$num,
        'role'=>'-1',
        'level'=>'0',
        'status'=>'1',
        'ip'=> $this->input->ip_address(),
        'ctime'=>time()
       );
      $this->session->set_userdata('guestinfo',$data);
      return  $data;
    }
   
    public function is_login()
    {
       $user = $this->userinfo();
       return $user['role'] != '-1'?true:false; 
    }

    public function is_master()
    {
       $user = $this->userinfo();
       return $user['role'] == '1'?true:false; 
    }
}