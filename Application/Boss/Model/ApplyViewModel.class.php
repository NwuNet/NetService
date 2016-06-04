<?php
namespace Boss\Model;
use Think\Model\ViewModel;
/**
 * 用户兼职申请查询视图
 */

class ApplyViewModel extends ViewModel {

	public $viewFields = array(
	'HomeUser' => array('uname','number','address','phone','img','area','yuanxi','zhuanye'),
	'ApplyHome' => array(
	    'id' => 'id', 
	    'position' => 'position',
		'specialty' => 'specialty', 
		'intro' => 'intro',
		'start_time' => 'start_time',
		'end_time' => 'end_time',
		'time' => 'time',
		'a_status' => 'a_status',
		'_on' => 'ApplyHome.home_id=HomeUser.id'), 	
	    
	);

}
