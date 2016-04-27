<?php
namespace Boss\Model;
use Think\Model\ViewModel;
/**
 * 用户离职查询视图
 */

class DimissionViewModel extends ViewModel {

/*	public $viewFields = array(
	'Dimission' => array('id','uname','position','start_time','end_time','reason','time','_type'=>'RIGHT'),
	'DimissionState' => array(		
		'approve' => 'approve',
		'dimission_time' => 'dimission_time',
		'time' => 'approve_time', 
		'_on' => 'Dimission.id = DimissionState.dimission_id'), 
	    
	);*/
	public $viewFields = array(
	  'DimissionState' => array('id','approve','dimission_time','time' ), 
	   'Dimission' => array(	   
	   'uname' => 'uname',
	   'position' => 'position',
	   'start_time' => 'start_time',
	   'end_time' => 'end_time',
	   'reason' => 'reason',
	   'time' => 'approve_time',
	   '_on' => 'Dimission.id = DimissionState.dimission_id' ),
	
	    
	);

}
