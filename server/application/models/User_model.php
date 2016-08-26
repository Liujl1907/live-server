<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {  

    public function __construct()
    {  
       $this->tbl = 'user_info';
       $this->tbl_key = 'uid';  
       parent::__construct(); 
    }
    
    public function create_user($user)
    {   
        $this->load->library('form_validation');
        $rules = array(
            'username' => array(
                    'field'=>'username',
                    'label'=>'用户名',
                    'rules'=>'trim|required',
                    'errors' => array('required' => '{field}不能为空')
            ),
            'name' => array(
                    'field'=>'name',
                    'label'=>'昵称',
                    'rules'=>'trim|required',
                    'errors' => array('required' => '{field}不能为空')
            ),
            'password' => array(
                    'field'=>'password',
                    'label'=>'密码',
                    'rules'=>'trim|required|min_length[5]|md5',
                    'errors' => array('required' => '{field}不能为空', 'min_length' => '{field}不能低于{param}位数') 
                    ),
            'phone' => array(
                    'field'=>'phone',
                    'label'=>'手机号',
                    'rules'=>'trim|required|valid_mobile',
                    'errors' => array('required' => '{field}不能为空', 'valid_mobile' => '请输入正确的{field}') 
                    )
        );
        $data = array(
             'username' => $user['username'],
             'name' => $user['name'],
             'password' => $user['password'],
             'phone' => $user['phone']
            );
        $this->form_validation->set_data($data);
        $this->form_validation->set_rules($rules);
        $res['code'] = $this->form_validation->run()?1:0;
        $res['msg'] = $this->form_validation->error_array();
        if($res['code'] == 1)
        { 
            //进行数据库校验
            $res['code'] = '0';
            $data = $this->form_validation->validation_data;
            if($this->has_user($data['username']) == true)
            {
               $res['msg'] = array('username'=>'用户名已存在');
            }
            else
            {  
               $now =  time();
               $user = array(
                 'username' => $data['username'],
                 'name' => $data['name'],
                 'password' =>$data['password'] ,
                 'phone' => $data['phone'],
                 'utime' =>  $now,
                 'ctime' => $now,
                 'role' => '0',
                 'level' => '0',
                 'status' => '1',
                 'ip' => ip2long($this->input->ip_address())
                );
               if($this->db->insert($this->tbl, $user) > 0)
                {  
                   $res['code'] = 1;
                   $res['msg'] =array('*'=>'创建成功');
                }
            }
        }
        
        return $res;
    }

    public function auth($user)
    {   
        //数据格式校验
        $this->load->library('form_validation');
        $rules = array(
            'username' => array(
                    'field'=>'username',
                    'label'=>'用户名',
                    'rules'=>'trim|required',
                    'errors' => array('required' => '{field}不能为空')
            ),
            'password' => array(
                    'field'=>'password',
                    'label'=>'密码',
                    'rules'=>'trim|required|min_length[5]|md5',
                    'errors' => array('required' => '{field}不能为空', 'min_length' => '{field}不能低于{param}位数') 
                    )
        );
        $data = array(
             'username' => $user['username'],
             'password' => $user['password']
            );
        $this->form_validation->set_data($data);
        $this->form_validation->set_rules($rules);
        $res['code'] = $this->form_validation->run()?1:0;
        $res['msg'] = $this->form_validation->error_array();
        if($res['code'] == 1)
        {   
            //进行数据库校验
            $res['code'] = '0';
            $data = $this->form_validation->validation_data;
            $user = array(
               'username' => $data['username'],
               'password' => $data['password']
            );
            $results = $this->db->from($this->tbl)
                        ->select('uid,username,password,name,role,level,status')
                        ->where('username',$user['username'])
                        ->get()->result_array();
            if(count($results) > 0)
            {  
                $result = $results[0];
                if(count($results)>1)
                {
                    $res['msg'] = array('*'=>'数据异常');
                }
                else if($result['password'] != $user['password'])
                {   
                    $res['msg'] = array('password'=>'密码不正确');
                }
                else
                {
                    $this->load->model('Auth_model');
                    $this->Auth_model->set_user($result);
                    $res['code'] = '1'; 
                    $res['msg'] =array('*'=>'认证通过');
                }
              
            }
            else
            {  
               $res['msg'] = array('username'=>'用户名不存在');
            }
        }
        return $res;
    }

    public function has_user($username)
    {
        $results = $this->db->from($this->tbl)
                        ->select('uid')
                        ->where('username',$username)
                        ->get()->result_array();
        return count($results) > 0 ?true:false;
    }
}