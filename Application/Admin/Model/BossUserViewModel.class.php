<?php
namespace Admin\Model;
use Think\Model\ViewModel;
/**
 * 后台用户查询视图
 */

class BossUserViewModel extends ViewModel {

	public $viewFields = array(
	'BossUser' => array('id', 'ip'),
	'User' => array(
		'uname' => 'uname', 
		'begintime' => 'begintime',
		'status' => 'status',
		'_on' => 'BossUser.user_id=User.user_id'), 
	    
	);

}
