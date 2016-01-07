<?php
namespace Admin\Logic;
use Think\Model;
/**
 * 用户
 */

class UserLogic extends Model {

	/*
	 * 后台添加
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
	
	
}
