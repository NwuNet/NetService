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
    /**
	 * Boss用户修改
	 * */
	public function bossedit($data){
		$user = D('User');
		$bossuser = D('BossUser');
		if($user -> edit($data) ){
			$tid = $user->where('uname = "%s"',$data['uname'])->field("uname,user_id")->find();
			$data['user_id'] = $tid['user_id'];
//			$bossuser->edit($data);
			return TRUE;
		}
		return FALSE;
	}
	/**
	 * Staff用户添加
	 */
	public function staffadd($data){
		$user = D('Admin/User');
		$staffuser = D('Admin/StaffUser');
		if($user->plus($data)){
			$tid = $user->where('uname = "%s"',$data['uname'])->field("uname,user_id")->find();
			$data['user_id'] = $tid['user_id'];
			$staffuser->plus($data);
			return TRUE;
		}
		return false;
	}
	/**
	 * Staff用户修改
	 * */
	public function staffedit($data){
		$user = D('Admin/User');
		$staffuser = D('Admin/StaffUser');
		if($user -> edit($data) ){
			$tid = $user->where('uname = "%s"',$data['uname'])->field("uname,user_id")->find();
			$data['user_id'] = $tid['user_id'];
			$staffuser->edit($data);
			return TRUE;
		}
		return FALSE;
	}
	/**
	 * Home用户添加
	 */
	public function homeadd($data){
		$user = D('Admin/User');
		$homeuser = D('Admin/HomeUser');
		if($user->plus($data)){
			$tid = $user->where('uname = "%s"',$data['uname'])->field("uname,user_id")->find();
			$data['user_id'] = $tid['user_id'];
			$homeuser->plus($data);
			return TRUE;
		}
		return false;
	}
	/**
	 * Home用户修改
	 * */
	public function homeedit($data){
		$user = D('Admin/User');
		$homeuser = D('Admin/HomeUser');
		if($user -> edit($data) ){
			$tid = $user->where('uname = "%s"',$data['uname'])->field("uname,user_id")->find();
			$data['user_id'] = $tid['user_id'];
			$homeuser->edit($data);
			return TRUE;
		}
		return FALSE;
	}
}
