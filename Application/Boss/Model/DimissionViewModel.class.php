<?php
namespace Boss\Model;
use Think\Model\ViewModel;
/**
 * 用户离职查询视图
 */
class DimissionViewModel extends ViewModel {
	public $viewFields = array(
	  'DimissionState' => array('approve','dimission_time','time'), 
	   'Dimission' => array(
	   'id' => 'id',   
	   'uname' => 'uname',
	   'position' => 'position',
	   'start_time' => 'start_time',
	   'end_time' => 'end_time',
	   'reason' => 'reason',
	   'time' => 'apply_time',
	   '_on' => 'DimissionState.dimission_id = Dimission.id' ),
		    
	);

}
