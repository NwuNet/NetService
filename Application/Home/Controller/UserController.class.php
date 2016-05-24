<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends BaseController {
	public function index() {
		$homeuser = D('Login','Service')->getuserInfo();
		$Card = M('ServiceCard');
		$cardinfo = $Card->where('status=0 and name = "%s" and student_no ="%s" ',$homeuser['uname'],$homeuser['number'])->select();
		$this->assign("cardinfo",$cardinfo);
		
		if($cardinfo){
			$repair = M('ServiceRepair');
		$servicerepair = $repair->where('state="维修" and servicecard_id = %d',$cardinfo[0]['id'])->select();
		$this->assign("servicerepair",$servicerepair);
		}
		
		$this -> display();
	}
    // --------------------维修单添加完成状态---------------------
	public function servicerepairadd(){
		$homeuser = D('Login','Service')->getuserInfo();
		$Card = M('ServiceCard');
		$cardinfo = $Card->where('status=0 and student_no = "%s" ',$homeuser['number'])->select();
		$servicecard_id = $cardinfo[0]['id'];
		$evaluation = I('post.evaluation');
		if($evaluation==''){
			$this->ajaxReturn("数据为空");
		}
		$repair = M('ServiceRepair');
		$repair->create();
		$repair->state='完成';
		$repair->servicecard_id=$servicecard_id;
		$repair->operator=$evaluation;
		$repair->time  = date("Y-m-d H:i:s",NOW_TIME);
		$repair->add();
		if($repair){						
				$Card = M('ServiceCard');
				$data = $Card->where('id = "%d"',$servicecard_id)->field("id")->find();
		        $Card->id = $data['id'];
		        $Card->status = '1' ;
		        $Card ->save();
				if($Card){
			        $this->ajaxReturn(true);
		        }else{
			        $this->ajaxReturn("提交失败");
				}
		
		}else if($repair){
			        $this->ajaxReturn(true);
		        } else{
			        $this->ajaxReturn("提交失败");
		        }
	}
    public function service() {
    	$homeuser = D('Login','Service')->getuserInfo();		
    	$Card = M('ServiceCard');
        $servicecardinfo = $Card->where('status = 1 and student_no = "%s"',$homeuser['number'])->select();
        $this -> assign('servicecardinfo',$servicecardinfo );//服务单表
    	$this -> display();
	}
	public function servicetable() {
		$Card = M('ServiceCard');
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
				case 1 :$orderSql = " name " . $order_dir;break;
				case 2 :$orderSql = " student_no " . $order_dir;break;
				case 3 :$orderSql = " dormitory " . $order_dir;break;
				case 4 :$orderSql = " phone " . $order_dir;break;
				case 5 :$orderSql = " description " . $order_dir;break;
				case 6 :$orderSql = " start " . $order_dir;break;
				case 7 :$orderSql = " appointment_time " . $order_dir;break;
				case 8 :$orderSql = " status " . $order_dir;break;
				default :$orderSql = '';
			}
		}
        
		$homeuser = D('Login','Service')->getuserInfo();
		$Card = M('ServiceCard');
		$cardinfo = $Card->where('status=1 and student_no = "%s" ',$homeuser['number'])->select();
		$servicecard_id = $cardinfo[0]['id'];
		//搜索
		$search = $_GET['search']['value'];//获取前台传过来的过滤条件
		//分页
		$start = $_GET['start'];//从多少开始
		$length = $_GET['length'];//数据长度
		//表的总记录数 必要
		$recordsTotal = $Card->where('student_no="%s"',$servicecard_id)->count();
        
		
		$map['id|name|dormitory|phone|description|start|appointment_time|status']=array('like',"%".$search."%");		
		$map['student_no']=$servicecard_id;
		if(strlen($search)>0){
			$recordsFiltered = count($Card->where($map)->select());
			$table = $Card->where($map)->order($orderSql)->limit($start.','.$length)->select();
		}else{
			$recordsFiltered = $recordsTotal;
			$table = $Card->order($orderSql)->limit($start.','. $length)->select();
		}

		$infos = array();
		foreach($table as $row){
			$obj = array($row['id'],$row['name'],$row['student_no'],$row['dormitory'],$row['phone'],$row['description'],$row['start'],$row['appointment_time'],$row['status']);
			array_push($infos,$obj);
		}

		$this->ajaxReturn(array(
			"draw" => intval($draw),
			"recordsTotal" => intval($recordsTotal),
			"recordsFiltered" => intval($recordsFiltered),
			"data" => $infos
		));
	}
	// --------------------申请兼职---------------------
	public function apply(){
		$this->display();
	}
	public function _empty($name) {
		echo "Not Found!";
	}

}
