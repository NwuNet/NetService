<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
		$log = D('Admin/SystemLog','Service');
		$log->addhome();
		$this->assign('day_sum',$log->dayhome());
		$this->assign('all_sum',$log->allhome());
		
		$questionlist = M("QuestionList");
		$questioninf = $questionlist->where()->select();
		$this->assign('questioninf',$questioninf);
		
        $this->display();
    }
	// --------------------添加服务单---------------------
	public function servicecardadd(){
		$uname = I('post.name');
		$student_no = I('post.student_no');
		$phone = I('post.phone');
		$building = I('post.building');
		$room = I('post.room');
		$question = I('post.question');
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
		if(date("w",$appointment_time)==0||date("w",$appointment_time)==6){
			$info['msg'] = '无法预约在周末';
			$info['status'] = false;
			$this->ajaxReturn($info);
		}
		$card = M('ServiceCard');
		$homeUser = D('Login','Service')->getuserInfo();
		$areaquantity = M("ServiceQuantity");
        $aquantity = $areaquantity->where('area = "%s"',$bossUser['area'])->find();
        $quantity = $aquantity['quantity']; 
		$cardcount = $card->where('area="%s" and appointment_time="%s" and status =0',$area,$appointment_time)->find();
		if(count($cardcount)>$quantity){
			$info['msg'] = '所选预约时间预约服务已满，请重新选择预约时间';
			$info['status'] = false;
			$this->ajaxReturn($info);
		}
		if($uname==''||$student_no==''||$phone==''||$building==''||$room==''||$question=''||$description==''||$appointment_time==''||$area==''){
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
//		$card->question = $question;
		$card->start  = date("Y-m-d H:i:s",NOW_TIME);
//		trace(getdayofweek($appointment_time));
		$card->appointment_time = date("Y-m-d",strtotime($appointment_time));
		$card->student_id = $user['id'];
		if($card->add()){
			$setlogin = D('Login','Service')->setlogin($user['id']);
			$this->ajaxReturn($setlogin);
		}else{
			$info['msg'] = '添加失败';
			$info['status'] = false;
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
		$root = 'Public/Files/Home/';
		$list = array(
			'0' => $root.'V2.0.1.pdf',
			'1' => $root.'StudentNetwork.pdf',
			'2' => $root.'Apple.pdf',
			'3' => $root.'Wiring.pdf',
			'4' => $root.'VLAN-study.pdf',
			'5' => $root.'Windows-console.pdf',
			'6' => $root.'Network-exchange.pdf',
			'7' => $root.'DG2015Setup-1197E.exe',
			'8' => $root.'USB-DB9.exe',
			'9' => $root.'chrome-and.apk',
			'10' => $root.'chrome-win.exe'
		);
		$files = array();
		foreach ($list as $key => $item){
			$tmp = stat($item);
			$files[$key]['size'] = sizeFormat($tmp['size']);
		}
//		trace($list);
		$this->assign('files',$files);
		$this->assign('lists',$list);
		$log = D('Admin/SystemLog','Service');
		$this->assign('day_sum',$log->dayhome());
		$this->assign('all_sum',$log->allhome());
        $this->display();
    }
	public function apply(){
        $this->display();
    }
	public function service(){
		$log = D('Admin/SystemLog','Service');
		$this->assign('day_sum',$log->dayhome());
		$this->assign('all_sum',$log->allhome());
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
		$log = D('Admin/SystemLog','Service');
		$this->assign('day_sum',$log->dayhome());
		$this->assign('all_sum',$log->allhome());
        $this->display();
    }
}