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
						
			$User = D('Admin/User', 'Logic');	      
	            $data = array();       
	            $data['uname'] = $name;
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
	public function homeadd() {
	    $User = D('Admin/User', 'Logic');
	    if(I('post.repassword') != I('post.password')) $this -> ajaxReturn("确认密码失败");
	    $data = array();
	    $data['ip'] = get_client_ip();
		$data['img'] = '/Images/User/default.png';
	    $data['uname'] = I('post.uname');
	    $data['password'] = I('post.password');
	    $data['number'] = I('post.number');
		$data['phone'] = I('post.phone');
	    $data['address'] = I('post.address');
	    if ($User -> homeadd($data)) {
	        $this -> ajaxReturn(TRUE);
	    } else {
	        $msg = "用户名已存在或认证失败";
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