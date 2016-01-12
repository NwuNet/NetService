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
            'user_id' => $userId,
        );
		session(array('name'=>$userId,'expire'=>3600));
        session('admin_user', $auth);
        session('admin_user_sign', data_auth_sign($auth));
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
			return session('admin_user_sign') == data_auth_sign($user) ? $user['user_id'] : FALSE;
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
}
