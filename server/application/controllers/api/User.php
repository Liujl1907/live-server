<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    
    //登陆
	public function signin()
	{   
		$this->load->model('Auth_model');
		if($this->Auth_model->is_login() == true)
		{    
			$res= array('code'=>0,'msg'=>'请不要重复登陆');
             echo json_encode($res);
		     return;
		};
		$this->load->database();
		$this->load->model('User_model','user');
		$user = array(
             'username' => $this->input->post('username'),
             'password' => $this->input->post('password')
            );
		$res = $this->user->auth($user);
		echo json_encode($res);
		return;
	}

	//注册
	public function signup()
	{
		$this->load->library('session');
		$code = $this->input->post('code');
		$r_code = $this->session->userdata('signup_code');
		if(empty($code) || $code != $r_code){
          $res['code'] = 0;
          $res['msg'] = array('code'=>'验证码不正确');
        }
        else
        {
			$this->load->database();
			$this->load->model('User_model','user');
			$user = array(
	             'username' => $this->input->post('username'),
	             'password' => $this->input->post('password'),
	             'name' => $this->input->post('name'),
	             'phone' => $this->input->post('phone')
	            );
			$res = $this->user->create_user($user);
		}
		echo json_encode($res);
		return;
	}

	//注销
    public function signout()
	{
		$this->load->library('session');
		$this->session->unset_userdata('userinfo');
        $res['code'] = 1;
        $res['msg'] = '注销成功';
		echo json_encode($res);
		return;
	}
    
	//校验用户名
	public function hasuser()
	{
		$this->load->database();
		$this->load->model('User_model','user');
		$username = $this->input->post('username');

		if($this->user->has_user($username) == true)
		{
          $res['code'] = 0;
          $res['msg'] = array('code'=>'用户已存在');
		}
		else
		{
		  $res['code'] = 1;
          $res['msg'] = array('code'=>'可以注册');
		}
		echo json_encode($res);
		return;
	}

}
