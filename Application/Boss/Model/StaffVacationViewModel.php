<?php
namespace Boss\Model;
use Think\Model\ViewModel;
/**
 * 用户请假查询视图
 */

class StaffVacationViewModel extends ViewModel {

	public $viewFields = array(
	'StaffVacation' => array('id','uname','start_time','end_time','reason','time'),
	'VacationState' => array(			
		'approve' => 'approve',
		'operator' => 'operator',
	//	'approve_time' => 'time', 
		'_on' => 'VacationState.vacation_id=StaffVacation.id'), 
	    
	);

}
