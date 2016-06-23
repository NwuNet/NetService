<?php
namespace Boss\Model;
use Think\Model\ViewModel;
/**
 * 用户离职查询视图
 */
class ServiceRepairViewModel extends ViewModel {
	public $viewFields = array(
	   'ServiceRepair' => array(
		   'servicecard_id' ,
		   'state',
		   'operator' ,
		   'time' ,
		   'breakinfo' ,
		   'breaksubinfo'
	   ),
		'ServiceCard' => array(
			'id',
			'name',
			'student_no',
			'dormitory',
			'question',
			'description',
			'start',
			'appointment_time',
			'status',
			'area',
			'end',
			'student_id',
			'_on' => ' ServiceRepair.servicecard_id = ServiceCard.id'
		),
	);

}
