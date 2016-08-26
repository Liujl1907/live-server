<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminUser extends CI_Controller {
    

	//输出用户列表
	public function get()
	{  
		// $this->output->enable_profiler(TRUE);
		$page = $this->input->get('page');
		if (!is_numeric($page)) {
			exit('input err');
		}
		$this->load->database();
		$this->load->model('admin/Admin_User_model','user');
		$res['list'] = $this->user->get($page);
		$res['now'] = $page;
		$res['count'] = $this->user->count();
		$res['page'] =  ceil($res['count']/10);
		echo json_encode($res);
		return;
	}

    public function detail()
	{  
		// $this->output->enable_profiler(TRUE);
		$uid = $this->input->get('uid');
		if (!is_numeric($uid)) {
			exit('input err');
		}
		$this->load->database();
		$this->load->model('admin/Admin_User_model','user');
		$res = $this->user->detail($uid);
		echo json_encode($res);
		return;
	}
}
