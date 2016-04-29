<?php
namespace Staff\Model;
use Think\Model\ViewModel;
/**
 * 维修服务单查询视图
 */
class ServiceViewModel extends ViewModel {
	public $viewFields = array(
	   'ServiceRepair' => array('id','state','operator','time','state'), 
	   'ServiceCard' => array(	   
	   'name' => 'name',	   
	   'student_no' => 'student_no',
	   'dormitory' => 'dormitory',
	   'question' => 'question',
	   'description' => 'description',
	   'appointment_time' => 'appointment_time',	   
	   '_on' => 'ServiceRepair.servicecard_id = ServiceCard.id ' ),
		    
	);

}
