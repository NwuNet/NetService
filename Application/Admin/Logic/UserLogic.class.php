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
		$data['level'] = 1;
		$user = D('User');
		$adminuser = D('AdminUser');
		if($user -> plus($data) ){
			$tid = $user->where('uname = "%s" and level = 1',$data['uname'])->field("uname,user_id")->find();
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
		$data['level'] = 1;
		$user = D('User');
		$adminuser = D('AdminUser');
		if($user -> edit($data) ){
			$tid = $user->where('uname = "%s" and level = 1',$data['uname'])->field("uname,user_id")->find();
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
		$data['level'] = 2;
		$user = D('User');
		$bossuser = D('BossUser');
		if($user->plus($data)){
			$tid = $user->where('uname = "%s" and level = 2',$data['uname'])->field("uname,user_id")->find();
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
		$data['level'] = 2;
		$user = D('User');
		$bossuser = D('BossUser');
		if($user -> edit($data) ){
			$tid = $user->where('uname = "%s" and level = 2',$data['uname'])->field("uname,user_id")->find();
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
		$data['level'] = 3;
		$user = D('Admin/User');
		$staffuser = D('Admin/StaffUser');
		if($user->plus($data)){
			$tid = $user->where('uname = "%s" and and level = 3',$data['uname'])->field("uname,user_id")->find();
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
		$data['level'] = 3;
		$user = D('Admin/User');
		$staffuser = D('Admin/StaffUser');
		if($user -> edit($data) ){
			$tid = $user->where('uname = "%s" and level = 3',$data['uname'])->field("uname,user_id")->find();
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
//		$data['level'] = 4;
//		$user = D('Admin/User');
		$homeuser = D('Admin/HomeUser');
		if($homeuser->plus($data)){
//			$tid = $user->where('uname = "%s" and level = 4',$data['uname'])->field("uname,user_id")->find();
//			$data['user_id'] = $tid['user_id'];
//			$homeuser->plus($data);
			return TRUE;
		}
		return false;
	}
	/**
	 * Home用户修改
	 * */
	public function homeedit($data){
//		$data['level'] = 4;
//		$user = D('Admin/User');
		$homeuser = D('Admin/HomeUser');
		if($homeuser -> edit($data) ){
//			$tid = $user->where('uname = "%s"  and level = 4',$data['uname'])->field("uname,user_id")->find();
//			$data['user_id'] = $tid['user_id'];
//			$homeuser->edit($data);
			return TRUE;
		}
		return FALSE;
	}
}
