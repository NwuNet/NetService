<?php
namespace Boss\Model;
use Think\Model\ViewModel;
/**
 * 用户离职查询视图
 */
class ServiceCardViewModel extends ViewModel {
	public $viewFields = array(
	  'ServiceCard' => array('id','name','student_no','dormitory','question','description','start','appointment_time','status','area','end'),
	   'ServiceCardRepair' => array(
	   'id' => 'repair_id',
	   'state' => 'state',
	   'operator' => 'operator',
	   'time' => 'time',
	   'breakinfo' => 'breakinfo',
	   'breaksubinfo' => 'breaksubinfo',
	   '_on' => 'ServiceCard.id = ServiceCardRepair.servicecard_id' ),
		    
	);

}
