<?php
namespace Staff\Service;
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
            'staff_user_id' => $userId
        );
		//session(array('name'=>$userId,'expire'=>3600));
        session('staff_user', $auth);
        session('staff_user_sign', data_auth_sign($auth));

		$log = D('Admin/SystemLog','Service');
		$data['module'] = 'Staff';
		$data['controller'] = 'LoginService';
		$data['user'] = $userId;
		$data['info'] = '登录';
		$data['status'] = 1;
		return TRUE;
    }
	/**
	 * 判断session 用户id sha1加密
	 * */
	public function islogin(){
		$user  = session('staff_user');
		if(empty($user)){
			return FALSE;
		}else{
			return session('staff_user_sign') == data_auth_sign($user) ? $user['staff_user_id'] : FALSE;
		}
	}
	/**
	 * 注销当前用户 退出
	 * */
    public function logout(){
        session('staff_user', null);
        session('staff_user_sign', null);
		session('[destroy]');
		return TRUE;
    }
	/*
	 * 获取用户id
	 * */
	public function getuserId(){
		$user  = session('staff_user');
		return $user['staff_user_id'];
	}
	/*
	 * 获取用户信息
	 * */
	public function getuserInfo(){
		$userid = $this->getuserId();
		$staffuser = D('Admin/StaffUserView');
		return $staffuser->where('id = %d',$userid)->find();
	}
}
