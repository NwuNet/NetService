<?php
namespace Admin\Logic;
use Think\Model;
/**
 * 用户
 */

class UserLogic extends Model {

	/*
	 * Admin用户添加
	 * */
	public function adminadd($data) {
		$user = D('User');
		$adminuser = D('AdminUser');
		if($user -> plus($data) ){
			$tid = $user->where('uname = "%s"',$data['uname'])->field("uname,user_id")->find();
			$data['user_id'] = $tid['user_id'];
			$adminuser->plus($data);
			return TRUE;
		}
		return FALSE;
	}
	/**
	 * Admin用户修改
	 * */
	public function adminedit($data){
		$user = D('User');
		$adminuser = D('AdminUser');
		if($user -> edit($data) ){
			$tid = $user->where('uname = "%s"',$data['uname'])->field("uname,user_id")->find();
			$data['user_id'] = $tid['user_id'];
//			$adminuser->edit($data);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Boss用户添加
	 */
	public function bossadd($data){
		$user = D('User');
		$bossuser = D('BossUser');
		if($user->plus($data)){
			$tid = $user->where('uname = "%s"',$data['uname'])->field("uname,user_id")->find();
			$data['user_id'] = $tid['user_id'];
			$bossuser->plus($data);
			return TRUE;
		}
		return false;
	}
}
