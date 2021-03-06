<?php
namespace Admin\Model;
use Think\Model\ViewModel;
/**
 * 后台用户查询视图
 */

class AdminUserViewModel extends ViewModel {

	public $viewFields = array(
	'AdminUser' => array('id', 'user_id', 'ip', 'img'),
	'User' => array(
		'uname' => 'uname',
		'password' => 'password',
		'begintime' => 'begintime',
		'status' => 'status',
		'_on' => 'AdminUser.user_id=User.user_id'), 
	);

}
