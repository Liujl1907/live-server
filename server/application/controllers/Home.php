<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->output->enable_profiler(TRUE);
		$this->load->database();
		$this->load->library('session');
		// $this->load->model('Chat_model','chat');
		// $data = $this->chat->getAll();	
		// var_dump($data);
		$this->load->model('admin/Admin_User_model','user');
		// for ($i=0; $i < 10; $i++) { 
		// 	$user = array(
  //            'username' => 'chen1',
  //            'password' => '123456',
  //            'name' =>'十大海归',
  //           );
		//  $res = $this->user->create_user($user);
		// }
		
		 // $res = $this->user->auth($user);
		 var_dump($res);
	}

}
