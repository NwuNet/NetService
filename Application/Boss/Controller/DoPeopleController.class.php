<?php
namespace Boss\Controller;
use Think\Controller;
class DoPeopleController extends BaseController {
	// --------------------考勤信息---------------------
    public function register(){
    	
		$staffUser = D('Admin/StaffUserView');
        $staffname = $staffUser->field('uname')->select();
        $this->assign('staffname',$staffname);//员工名称
        
    	$select = M('RegisterSelect');
		$registername = $select->field('name')->group('name')->select();
		$this->assign('registername',$registername);//考勤类别
		
		/*$Register = M('StaffRegister');
		$registertable = $Register -> select();
		$this -> assign('register1',$registertable );//服务单表*/
		
        $this->display();
    }
	// --------------------添加员工考勤信息---------------------
	public function registeradd(){
		$uname = I('post.uname');
		$time = I('post.time');
		$state = I('post.state');
		
		if($uname==''||$time==''||$state==''){
			$this->ajaxReturn("数据为空");
		}elseif($uname=='请选择'||$state=='请选择'){
			$this->ajaxReturn("请选择");
		}
		$register = M('StaffRegister');
		$register->create();
		$register->recordtime  = date("Y-m-d H:i:s",NOW_TIME);
		$register->add();
		if($register){
			$this->ajaxReturn(true);
		}else{
			$this->ajaxReturn("添加失败");
		}
	}
	public function registertable(){
		$start = I('post.start');
		$end = I('post.end');
		if($start==''||$end==''){
			$this->ajaxReturn("数据为空");
		}else{
			$Register = M('StaffRegister');
			$map['time']=array('between',array($start,$end));
			$data =$Register->where($map)->select();
			foreach($data as $key => $value){
				$returndata[$key]['title'] =$data[$key]['uname'];
				$returndata[$key]['start'] =$data[$key]['time'];
				$returndata[$key]['textColor'] ='#FFFFFF';
				switch ($data[$key]['state']) {
					case '正常'  :
						$returndata[$key]['backgroundColor'] = '#00a65a';
						break;
					case '请假'  :
						$returndata[$key]['backgroundColor'] = '#00a65a';
						break;
					case '矿工'  :
						$returndata[$key]['backgroundColor'] = '#00a65a';
						break;
					default:
						$returndata[$key]['backgroundColor'] = '#00a65a';
				}
			}
			$this->ajaxReturn($returndata);
		}
	}
    
	// --------------------招聘信息---------------------
    public function employ(){
        $this->display();
    }
	// --------------------绩效评分---------------------
    public function evaluate(){
        $this->display();
    }
	// --------------------绩效查询---------------------
    public function query(){
        $this->display();
    }
	// --------------------员工管理---------------------
    public function staff(){
    	
		$Staff = M('StaffVacation');
		$vacation = $Staff->select();
		$this->assign('vacation',$vacation);//
        $this->display();
    }
	
	// --------------------员工请假条审批---------------------
	public function vacationstatus(){
		$id = I('post.id');
		$operator = I('post.operator');
		$status = I('post.status');
				
		if($id==''||$status==''){
			$this->ajaxReturn("数据为空");
		}
		$Vacation = M('StaffVacation');
		$Vacation->create();
		if($status=="批准"){
			$status = 1;
		}else{
			$status = 0;
		} 
		
		$Vacation->approve_time  = date("Y-m-d H:i:s",NOW_TIME);
		$Vacation->save();
		if($Vacation){
			$this->ajaxReturn(true);
		}else{
			$this->ajaxReturn("审批失败");
		}
	}	
	
	// --------------------空操作---------------------
	public function _empty($name){
		echo "Not Found!";
    }
}