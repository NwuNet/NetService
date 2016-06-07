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
		if($appointment_time == ''){
			$info['msg'] = '请选择预约时间';
			$info['status'] = false;
			$this->ajaxReturn($info);
		}
		if(strtotime('-1 day')>strtotime($appointment_time)){
			$info['msg'] = '预约时间不正确';
			$info['status'] = false;
			$this->ajaxReturn($info);
		}
		if($uname==''||$student_no==''||$phone==''||$building==''||$room==''||$description==''||$appointment_time==''||$area==''){
			$this->ajaxReturn("数据为空");
		}
		//-------------------------------------添加学生到学生表--------------------------------------
		$homeuser = M('HomeUser');
		$user = $homeuser->where('uname="%s" and number="%s"',$uname,$student_no)->find();
		if(count($user)==0){
			$UserLogic = D('Admin/User', 'Logic');
			$data = array();
			$data['uname'] = $uname;
			$data['password'] = $student_no;
			$data['number'] = $student_no;
			$data['phone'] = $phone;
			$data['address'] = $building.'-'.$room ;
			$data['img'] = '/Images/User/default.png';
			$data['area'] = $area;
			$UserLogic -> homeadd($data);
			$user = $homeuser->where('uname="%s" and number="%s"',$uname,$student_no)->find();
		}
		//------------------------------------添加服务单到服务单表------------------------------------------
		$card = M('ServiceCard');
		$onecard = $card->where('name="%s" and student_no="%s" and status =0',$uname,$student_no)->find();
		if(count($onecard)>0){
			$info['msg'] = '您的上一个服务单还未处理完成，请处理';
			$info['status'] = true;
			D('Login','Service')->setlogin($user['id']);
			$this->ajaxReturn($info);
		}
		$card->create();
		$card->dormitory = $building.'-'.$room ;
		$card->start  = date("Y-m-d H:i:s",NOW_TIME);
//		trace(getdayofweek($appointment_time));
		$card->appointment_time = date("Y-m-d",strtotime($appointment_time));
		$card->sutdent_id = $user['id'];
		if($card->add()){
			$setlogin = D('Login','Service')->setlogin($user['id']);
			$this->ajaxReturn($setlogin);
		}else{
			$info['msg'] = '您的上一个服务单还未处理完成，请处理';
			$this->ajaxReturn($info);
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
		$staffUser = D('Admin/StaffUserView');
		$changan = $staffUser->where('status = 1 and area = "长安校区"')->order('cname')->select();
		$taibai = $staffUser->where('status = 1 and area = "太白校区"')->order('cname')->select();
		$taoyuan = $staffUser->where('status = 1 and area = "桃园校区"')->order('cname')->select();
		$this->assign('changan',$changan);
		$this->assign('taibai',$taibai);
		$this->assign('taoyuan',$taoyuan);
        $this->display();
    }
}