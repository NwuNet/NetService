<?php
namespace Boss\Model;
use Think\Model\ViewModel;
/**
 * 用户兼职审批查询视图
 */

class ApplyReplyViewModel extends ViewModel {

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
	'ApplyReply' => array(
		'reply' => 'reply', 
		'operator' => 'operator',
		'time' => 'reply_time',
		'_on' => 'ApplyReply.apply_id=ApplyHome.id'), 
	
	    
	);

}
