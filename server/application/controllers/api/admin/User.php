<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    

	//输出用户列表
	public function get($page = 1)
	{  
		// $this->output->enable_profiler(TRUE);
		$page = $this->input->get('page');
		if (!is_numeric($page)) {
			exit('input err');
		}
		$this->load->database();
		$this->load->model('admin/User_model','user');
		$res['list'] = $this->user->get($page);
		$res['now'] = $page;
		$res['count'] = $this->user->count();
		$res['page'] =  ceil($res['count']/10);
		echo json_encode($res);
		return;
	}

}
