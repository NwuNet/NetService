<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display();
    }
	// --------------------添加服务单---------------------
	public function servicecardadd(){
		$uname = I('post.name');
		$student_no = I('post.student_no');
		$phone = I('post.phone');
		$building = I('post.building');
		$room = I('post.room');
//		$description = I('post.description');
		$description = "null";
		$appointment_time = I('post.appointment_time');
		$area = I('post.area');

		if($uname==''||$student_no==''||$phone==''||$building==''||$room==''||$description==''||$appointment_time==''||$area==''){
			$this->ajaxReturn("数据为空");
		}
		$card = M('ServiceCard');
		$card->create();
		$card->dormitory = $building.'-'.$room ;
		$card->start  = date("Y-m-d H:i:s",NOW_TIME);
//		trace(getdayofweek($appointment_time));
		$card->appointment_time = date("Y-m-d",strtotime($appointment_time));
		$card->add();
		if($card){
						
			$User = D('Admin/User', 'Logic');	      
	            $data = array();       
	            $data['uname'] = $uname;
	            $data['password'] = $student_no;
	            $data['number'] = $student_no;
		        $data['phone'] = $phone;
	            $data['address'] = $building.'-'.$room ;
				$data['img'] = '/Images/User/default.png';
				$User -> homeadd($data);
	            				
			$this->ajaxReturn(true);
			
		}else{
			$this->ajaxReturn("添加失败");
		}
	}
	public function feedbackadd() {
	    $uname = I('post.uname');
		$student_no = I('post.student_no');
		$feedback = I('post.feedback');
		
		if($uname==''||$student_no==''||$feedback==''){
			$this->ajaxReturn("数据为空");
		}
		$Feedback = M('Feedback');
		$Feedback->create();
		$Feedback->time  = date("Y-m-d H:i:s",NOW_TIME);
	    if ($Feedback ->add()) {
	        $this -> ajaxReturn(TRUE);
	    } else {
	        $msg = "反馈提交失败！";
	        $this -> ajaxReturn($msg);
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