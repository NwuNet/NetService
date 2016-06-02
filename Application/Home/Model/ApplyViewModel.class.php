<?php
namespace Home\Model;
use Think\Model\ViewModel;
/**
 * 兼职申请查询视图
 */

class ApplyViewModel extends ViewModel {
	public $viewFields = array(
	'ApplyHome' => array('id','home_id','specialty','intro','start_time','end_time','time'),
	'ApplyReply' => array(
		'reply' => 'reply', 
		'operator' => 'operator',
		'time' => 'reply_time',
		'_on' => 'ApplyReply.apply_id=ApplyHome.id'), 
	    
	);

}
