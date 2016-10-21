<?php
namespace Boss\Model;
use Think\Model\ViewModel;
/**
 * 用户离职查询视图
 */
class DimissionUserViewModel extends ViewModel {
	public $viewFields = array(
		'StaffUser' => array('area'),
	   'Dimission' => array(
	   'id' => 'id',   
	   'uname' => 'uname',
	   'position' => 'position',
	   'start_time' => 'start_time',
	   'end_time' => 'end_time',
	   'reason' => 'reason',
	   'time' => 'apply_time',
	   '_on' => 'StaffUser.user_id = Dimission.user_id' ),
		    
	);

}
