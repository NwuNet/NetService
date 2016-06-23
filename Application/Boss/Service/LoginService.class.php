<?php
namespace Boss\Service;
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
            'boss_user_id' => $userId
        );
		//session(array('name'=>$userId,'expire'=>3600));
        session('boss_user', $auth);
        session('boss_user_sign', data_auth_sign($auth));

		$log = D('Admin/SystemLog','Service');
		$data['module'] = 'Boss';
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
		$user  = session('boss_user');
		if(empty($user)){
			return FALSE;
		}else{
			return session('boss_user_sign') == data_auth_sign($user) ? $user['boss_user_id'] : FALSE;
		}
	}
	/**
	 * 注销当前用户 退出
	 * */
    public function logout(){
        session('boss_user', null);
        session('boss_user_sign', null);
		session('[destroy]');
		return TRUE;
    }
	/*
	 * 获取用户id
	 * */
	public function getuserId(){
		$user  = session('boss_user');
		return $user['boss_user_id'];
	}
	/*
	 * 获取用户信息
	 * */
	public function getuserInfo(){
		$userid = $this->getuserId();
		$bossuser = D('Admin/BossUserView');
		return $bossuser->where('id = %d',$userid)->find();
	}
}
