<?php
namespace Admin\Model;
use Think\Model\ViewModel;
/**
 * 后台用户查询视图
 */

class StaffUserViewModel extends ViewModel {

	public $viewFields = array(
	'StaffUser' => array('id','user_id','number','address','phone','img'),
	'User' => array(
		'uname' => 'uname', 
		'password' => 'password',
		'begintime' => 'begintime',
		'status' => 'status',
		'_on' => 'StaffUser.user_id=User.user_id'), 
	    
	);

}
