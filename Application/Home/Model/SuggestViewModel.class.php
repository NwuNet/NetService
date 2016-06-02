<?php
namespace Home\Model;
use Think\Model\ViewModel;
/**
 * 建议回复查询视图
 */

class SuggestViewModel extends ViewModel {

	public $viewFields = array(
	'SuggestFeedback' => array('reply','operator','time'), 
	   'Suggest' => array(	
	   'id' => 'id',   
	   'uname' => 'uname',	   
	   'student_no' => 'student_no',
	   'suggest' => 'suggest',
	   'time' => 'suggest_time',	     
	   '_on' => 'Suggest.id = SuggestFeedback.suggest_id ' ),
	    
	);

}
