<?php
namespace Home\Service;
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
		if($userId<=0) return false;
        //设置session sha1加密
        $auth = array(
            'home_user_id' => $userId
        );
		//session(array('name'=>$userId,'expire'=>3600));
        session('home_user', $auth);
        session('home_user_sign', data_auth_sign($auth));
		return TRUE;
    }
	/**
	 * 判断session 用户id sha1加密
	 * */
	public function islogin(){
		$user  = session('home_user');
		if(empty($user)){
			return FALSE;
		}else{
			return session('home_user_sign') == data_auth_sign($user) ? $user['home_user_id'] : FALSE;
		}
	}
	/**
	 * 注销当前用户 退出
	 * */
    public function logout(){
        session('home_user', null);
        session('home_user_sign', null);
		session('[destroy]');
		return TRUE;
    }
	/*
	 * 获取用户id
	 * */
	public function getuserId(){
		$user  = session('home_user');
		return $user['home_user_id'];
	}
	/*
	 * 获取用户信息
	 * */
	public function getuserInfo(){
		$userid = $this->getuserId();
		$homeuser = M('HomeUser');
		return $homeuser->where('id = %d',$userid)->find();
	}
}
