<?php
namespace Admin\Model;
use Think\Model\ViewModel;
/**
 * 后台用户查询视图
 */

class HomeUserViewModel extends ViewModel {

	public $viewFields = array(
	'HomeUser' => array('id','number','address'),
	'User' => array(
		'uname' => 'uname', 
		'begintime' => 'begintime',
		'status' => 'status',
		'_on' => 'HomeUser.user_id=User.user_id'), 
	    
	);

}
