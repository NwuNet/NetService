<?php
namespace Admin\Service;
use Think\Model;
/**
 * 用户登录服务
 */

class LoginService{
	
	/**
	 * 用户登录
	 * */
    public function setlogin($userId)
    {
        //设置session sha1加密
        $auth = array(
            'admin_user_id' => $userId
        );
		//session(array('name'=>$userId,'expire'=>3600));
		//设置session过期时间
        session(array('name'=>'admin_user','expire'=>3600));
		session('admin_user',$auth);
        session(array('name'=>'admin_user_sign','expire'=>3600));
		session('admin_user_sign',data_auth_sign($auth));

		$log = D('Admin/SystemLog','Service');
		$data['module'] = 'Admin';
		$data['controller'] = 'LoginService';
		$data['user'] = $userId;
		$data['info'] = '登录';
		$data['status'] = 1;
		$log->add($data);
		return TRUE;
    }
	/**
	 * 判断session 用户id sha1加密
	 * */
	public function islogin(){
		$user  = session('admin_user');
		if(empty($user)){
			return FALSE;
		}else{
			return session('admin_user_sign') == data_auth_sign($user) ? $user['admin_user_id'] : FALSE;
		}
	}
	/**
	 * 注销当前用户 退出
	 * */
    public function logout(){
        session('admin_user', null);
        session('admin_user_sign', null);
		session('[destroy]');
		return TRUE;
    }
	/*
	 * 获取用户id
	 * */
	public function getuserId(){
		$user  = session('admin_user');
		return $user['admin_user_id'];
	}
	/*
	 * 获取用户信息
	 * */
	public function getuserInfo(){
		$userid = $this->getuserId();
		$bossuser = D('Admin/AdminUserView');
		return $bossuser->where('id = %d',$userid)->find();
	}
}
