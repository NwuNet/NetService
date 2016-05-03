<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display();
    }
	// --------------------添加服务单---------------------
	public function servicecardadd(){
		$name = I('post.name');
		$student_no = I('post.student_no');
		$phone = I('post.phone');
		$building = I('post.building');
		$room = I('post.room');
		$description = I('post.description');
		$appointment_time = I('post.appointment_time');

		if($name==''||$student_no==''||$phone==''||$building==''||$room==''||$description==''||$appointment_time==''){
			$this->ajaxReturn("数据为空");
		}
		$card = M('ServiceCard');
		$card->create();
		$card->dormitory = $building.'-'.$room ;
		$card->start  = date("Y-m-d H:i:s",NOW_TIME);
		trace(getdayofweek($appointment_time));
		$card->appointment_time = date("Y-m-d",strtotime(getdayofweek($appointment_time)));
		$card->add();
		if($card){
			$this->ajaxReturn(true);
		}else{
			$this->ajaxReturn("添加失败");
		}
	}
	public function download(){
        $this->display();
    }
	public function apply(){
        $this->display();
    }
	public function service(){
        $this->display();
    }
	public function staff(){
        $this->display();
    }
}