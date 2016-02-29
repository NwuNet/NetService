<?php
namespace Admin\Model;
use Think\Model\ViewModel;
/**
 * Boss用户查询视图
 */

class BossUserViewModel extends ViewModel {

	public $viewFields = array(
	'BossUser' => array('id','user_id', 'ip','img'),
	'User' => array(
		'uname' => 'uname',
		'password' => 'password',
		'begintime' => 'begintime',
		'status' => 'status',
		'_on' => 'BossUser.user_id=User.user_id'), 
	    
	);

}
