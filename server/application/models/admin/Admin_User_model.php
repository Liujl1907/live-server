<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
   
*/
class Admin_User_model extends CI_Model {  

    public function __construct()
    {  
    	$this->tbl = 'admin_info';
      $this->tbl_key = 'uid';  
      parent::__construct();    
    }

    function get($page = 1,$limit = 10)
    {
      $res = $this->db->from($this->tbl)
           ->select('uid,username,name,role')
           ->order_by('uid','desc')
           ->limit($limit,($page-1)*$limit)
           ->get()->result_array();
      return $res;
    }
    function detail($uid)
    {
       $res = $this->db->from($this->tbl)
        ->select('*')
        ->where('uid',$uid)
        ->limit(1)
        ->get()->result_array();
        return $res[0];
    }
    function count()
    {
        $res = $this->db->from($this->tbl)
          ->count_all_results();
       return $res;
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
                    )
        );
        $data = array(
             'username' => $user['username'],
             'name' => $user['name'],
             'password' => $user['password'],
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
                 'utime' =>  $now,
                 'ctime' => $now,
                 'role' => '0',
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

    public function has_user($username)
    {
        $results = $this->db->from($this->tbl)
                        ->select('uid')
                        ->where('username',$username)
                        ->get()->result_array();
        return count($results) > 0 ?true:false;
    }

    function update($user)
    { 
      $this->load->library('form_validation');
      $data = @array(
          "name"=>$user['name'],
          "password" =>$user['password'],
          "role" =>$user['role']
       );
      $uid = $user['uid'];
      $rules = array(
            'role' => array(
                    'field'=>'role',
                    'label'=>'权限组',
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
       );
       $udata = array();
       $urules = array();
       foreach ($data as $key => $value) {
           if($value)
           {
             $udata[$key] = $value;
             $urules[$key] = $rules[$key];
           }
       }
        $this->form_validation->set_data($udata);
        $this->form_validation->set_rules($urules);
        $res['code'] = $this->form_validation->run()?1:0;
        $res['msg'] = $this->form_validation->error_array();
        if($res['code'] == 1)
        { 
          $data = $this->form_validation->validation_data;
          $data['utime'] = time();
          $this->db->where('uid',$uid)
               ->update($this->tbl, $data);
          $sql = $this->db->affected_rows();
          if($sql>0)
          {
             $res = array(
            'code'=>1,
            'msg'=>array(
              array('*' => '更新成功') 
              ),
            'data'=>$data
            );
          }
          else
          {
           $res = array(
            'code'=>0,
            'msg'=>array(
              array('*' => '更新失败') 
              )
            ); 
          }
        }
        return $res;
    }

    function remove($uid)
    {
      $role = array('status'=>'-1','utime'=>time());
      $this->db->where('uid',$uid)
          ->update($this->tbl, $role);
      $sql = $this->db->affected_rows();
      if($sql>0)
      {
         $res = array(
        'code'=>1,
        'msg'=>array(
          array('*' => '删除成功') 
          )
        );
      }
      else
      {
       $res = array(
        'code'=>0,
        'msg'=>array(
          array('*' => '删除失败') 
          )
        ); 
      }
      return $res;
    }

    function activate($uid)
    {
      $role = array('status'=>'1','utime'=>time());
      $this->db->where('uid',$uid)
           ->update($this->tbl, $role);
      $sql = $this->db->affected_rows();
      if($sql>0)
      {
         $res = array(
        'code'=>1,
        'msg'=>array(
          array('*' => '删除成功') 
          )
        );
      }
      else
      {
       $res = array(
        'code'=>0,
        'msg'=>array(
          array('*' => '删除失败') 
          )
        ); 
      }
      return $res;
    }
   
  }