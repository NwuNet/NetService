<?php
namespace Boss\Model;
use Think\Model\ViewModel;
/**
 * 用户请假查询视图
 */
class StaffVacationUserViewModel extends ViewModel {
	public $viewFields = array(
	'StaffUser' => array('area'),
	'StaffVacation' => array(
	    'id'=> 'id',
		'uname'=> 'uname',
        'start_time'=> 'start_time',
        'end_time'=> 'end_time',
        'reason'=> 'reason',
        'time' => 'apply_time',				 
		'_on' => 'StaffUser.user_id=StaffVacation.user_id'),
	    
	);

}
