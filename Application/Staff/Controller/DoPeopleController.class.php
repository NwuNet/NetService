<?php
namespace Staff\Controller;
use Think\Controller;
class DoPeopleController extends BaseController {
	// --------------------考勤信息---------------------
    public function register(){
    								
        $this->display();
    }
	// --------------------员工考勤表---------------------
	public function registertable(){
		$start = I('post.start');
		$end = I('post.end');		
		if($start==''||$end==''){
			$this->ajaxReturn("数据为空");
		}else{
			$Register = M('StaffRegister');	
			$map['time']=array('between',array($start,$end));
			$user = D('Login','Service')->getuserInfo();
			$map['uname']=$user['uname'];//筛选当前员工
			$data =$Register->where($map)->select();
			foreach($data as $key => $value){
				$returndata[$key]['title'] =$data[$key]['uname'].':'.$data[$key]['state'];
				$returndata[$key]['start'] =$data[$key]['time'];
				$returndata[$key]['textColor'] ='#FFFFFF';
				switch ($data[$key]['state']) {
					case '正常'  :
						$returndata[$key]['backgroundColor'] = '#00a65a';
						break;
					case '请假'  :
						$returndata[$key]['backgroundColor'] = '#f39c12';
						break;
					case '旷工'  :
						$returndata[$key]['backgroundColor'] = '#f56954';
						break;
					default:
						$returndata[$key]['backgroundColor'] = '#605ca8';
				}
			}
			$this->ajaxReturn($returndata);
		}
	}
  	
	// --------------------绩效查询---------------------
    public function query(){
        $this->display();
    }
	
	// --------------------添加员工请假条信息---------------------
	public function vacationadd(){
		$uname = I('post.uname');
		$start_time = I('post.start_time');
		$end_time = I('post.end_time');
		$reason = I('post.reason');
		
		if($reason==''||$start_time==''||$end_time==''){
			$this->ajaxReturn("数据为空");
		}elseif($uname=='请选择'){
			$this->ajaxReturn("请选择");
		}
		$Vacation = M('StaffVacation');
		$Vacation->create();
		$Vacation->status = 0;
		$Vacation->time  = date("Y-m-d H:i:s",NOW_TIME);
		$Vacation->add();
		if($Vacation){
			$this->ajaxReturn(true);
		}else{
			$this->ajaxReturn("添加失败");
		}
	}	
	
	// --------------------员工请假---------------------
    public function vacation(){
    	$staffuser = D('Login','Service')->getuserInfo();
    	$Vacation = D('Boss/StaffVacationView');
		$state = $Vacation->where('uname="%s"',$staffuser['cname'])->select();
		$this->assign('state',$state);
		
		$Dstate = M('StaffVacation');
        $vacation = $Dstate->where('status=0 and uname="%s"',$staffuser['cname'])->select();
        $this->assign('vacation',$vacation);
        $this->display();
    }
	
	// --------------------员工离职申请---------------------
    public function dimission(){
    	$staffuser = D('Login','Service')->getuserInfo();
    	$Dimission = D('Boss/DimissionView');
		$state = $Dimission->where('uname="%s"',$staffuser['uname'])->select();
		$this->assign('state',$state);
	//	$this->ajaxReturn($user.uname);
    	$Dstate = M('Dimission');
        $dimission = $Dstate->where('status=0 and uname="%s"',$staffuser['uname'])->select();
        $this->assign('dimission',$dimission);
        $this->display();
    }
	// --------------------添加员工离职申请信息---------------------
	public function dimissionadd(){
		$uname = I('post.uname');
		$position = I('post.position');
		$start_time = I('post.start_time');
		$end_time = I('post.end_time');
		$reason = I('post.reason');
		
		if($reason==''||$start_time==''||$end_time==''){
			$this->ajaxReturn("数据为空");
		}elseif($uname=='请选择'||$position=='请选择'){
			$this->ajaxReturn("请选择");
		}
		$Dimission = M('Dimission');
		$Dimission->create();
		$Dimission->status = 0;
		$Dimission->time  = date("Y-m-d H:i:s",NOW_TIME);
		$Dimission->add();
		if($Dimission){
			$this->ajaxReturn(true);
		}else{
			$this->ajaxReturn("添加失败");
		}
	}	
	// --------------------空操作---------------------
	public function _empty($name){
		echo "Not Found!";
    }
}