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
		if(count($register->where('uname = "%s" and time ="%s"',$uname,$time)->find())>0){
			$this->ajaxReturn("该员工当天考勤已存在");
		}else{
			$register->create();
			$register->recordtime  = date("Y-m-d H:i:s",NOW_TIME);
			$register->add();
			if($register){
				$this->ajaxReturn(true);
			}else{
				$this->ajaxReturn("添加失败");
			}
		}
	}
	// --------------------添加员工考勤信息---------------------
	public function registerdel(){
		$str = I('post.uname');
		$arr = explode(':',$str);
		$uname = $arr[0];
		$time = I('post.time');
		$state = $arr[1];
		if($uname==''||$time==''||$state==''){
			$this->ajaxReturn("数据为空");
		}elseif($uname=='请选择'||$state=='请选择'){
			$this->ajaxReturn("请选择");
		}
		$register = M('StaffRegister');
		$deldata = $register->where('uname = "%s" and time ="%s" and state ="%s"',$uname,$time)->find();
		if(count($deldata)>0){
			$register->where($deldata)->delete();
			$this->ajaxReturn(true);
		}else{
			$this->ajaxReturn("删除失败");
		}
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
		$vacation = $Staff->where('status=0')->select();
		$this->assign('vacation',$vacation);//
        $this->display();
    }
	
	// --------------------员工请假条审批---------------------
	public function vacationstatus(){
		$vacation_id = I('post.id');
		$operator = I('post.operator');
		$approve = I('post.approve');
				
		if($vacation_id==''||$approve==''){
			$this->ajaxReturn("数据为空");
		}
				
		$Vacation = M('StaffVacation');
		$Vacation->create();
		$Vacation->id=$vacation_id;
		$Vacation->status=1;
		
		$State = M('VacationState');
		$State->create();
		$State->time  = date("Y-m-d H:i:s",NOW_TIME);
		$State->add();
		if($State){
			$Vacation->save();
			if($Vacation){
				$this->ajaxReturn(true);
		    }else{
			$this->ajaxReturn("审批失败");
		    }			
		}else{
			$this->ajaxReturn("审批失败");
		}
	}	
	public function vacationstatetable() {
	    $vacationState = D('StaffVacationView');
	//	$vacationState = M('StaffVacation');
	    //获取Datatables发送的参数 必要
	    $draw = I('get.draw');//这个值作者会直接返回给前台
	
	    //排序
	    $order_column = I('get.order')['0']['column'];//那一列排序，从0开始
	    $order_dir = I('get.order')['0']['dir'];//ase desc 升序或者降序
	    //拼接排序sql
	    $orderSql = "";
	    if (isset($order_column)) {
	        $i = intval($order_column);
	        switch($i) {
	            case 0 :$orderSql = " id " . $order_dir;break;
	            case 1 :$orderSql = " uname " . $order_dir;break;
	            case 2 :$orderSql = " start_time " . $order_dir;break;
				case 3 :$orderSql = " end_time " . $order_dir;break;
				case 4 :$orderSql = " reason " . $order_dir;break;
				case 5 :$orderSql = " time " . $order_dir;break;
			/*	case 6 :$orderSql = " approve " . $order_dir;break;
	            case 7 :$orderSql = " approve_time " . $order_dir;break;*/
	            default :$orderSql = '';
	        }
	    }
	
	    //搜索
	    $search = $_GET['search']['value'];//获取前台传过来的过滤条件
	    //分页
	    $start = $_GET['start'];//从多少开始
	    $length = $_GET['length'];//数据长度
	    //表的总记录数 必要
	    $recordsTotal = $vacationState->count();
	
	    $map['id|uname|start_time|end_time|reason|time']=array('like',"%".$search."%");//|approve|approve_time
	    if(strlen($search)>0){
	        $recordsFiltered = count($vacationState->where($map)->select());
	        $table = $vacationState->where($map)->order($orderSql)->limit($start.','.$length)->select();
	    }else{
	        $recordsFiltered = $recordsTotal;
	        $table = $vacationState->order($orderSql)->limit($start.','.$length)->select();
	    }
	
	    $infos = array();
	    foreach($table as $row){
	        $obj = array($row['id'],$row['uname'],$row['start_time'],$row['end_time'],$row['reason'],$row['time']);//,$row['approve'],$row['approve_time']
	        array_push($infos,$obj);
	    }
	
	    $this->ajaxReturn(array(
	        "draw" => intval($draw),
	        "recordsTotal" => intval($recordsTotal),
	        "recordsFiltered" => intval($recordsFiltered),
	        "data" => $infos
	    ));
	}
	// --------------------空操作---------------------
	public function _empty($name){
		echo "Not Found!";
    }
}