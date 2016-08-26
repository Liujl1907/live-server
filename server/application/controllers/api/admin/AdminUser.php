<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminUser extends CI_Controller {
    

	//输出用户列表
	public function get($page = 1)
	{  
		// $this->output->enable_profiler(TRUE);
		$this->load->database();
		$this->load->model('admin/Admin_User_model','user');
		$res['list'] = $this->user->get();
		$res['now'] = $page;
		$res['count'] = $this->user->count();
		$res['page'] =  ceil($res['count']/10);
		echo json_encode($res);
		return;
	}

}
