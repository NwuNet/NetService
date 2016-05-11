<?php
namespace Admin\Model;
use Think\Model\ViewModel;
/**
 * 后台用户查询视图
 */

class HomeUserViewModel extends ViewModel {

	public $viewFields = array(
	'HomeUser' => array('id','user_id','number','phone','address','img'),
	'User' => array(
		'uname' => 'uname', 
		'password' => 'password',
		'begintime' => 'begintime',
		'status' => 'status',
		'_on' => 'HomeUser.user_id=User.user_id'), 
	    
	);

}
