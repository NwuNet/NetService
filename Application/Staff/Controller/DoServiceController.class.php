<?php
namespace Staff\Controller;
use Think\Controller;
class DoServiceController extends BaseController {
	// --------------------通讯录信息---------------------
    public function servicequery(){
    	
		$this->display();
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

		//搜索
		$search = $_GET['search']['value'];//获取前台传过来的过滤条件
		//分页
		$start = $_GET['start'];//从多少开始
		$length = $_GET['length'];//数据长度
		//表的总记录数 必要
		$recordsTotal = $Card->count();

		$map['id|name|student_no|dormitory|phone|description|start|appointment_time|status']=array('like',"%".$search."%");
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
	// --------------------空操作---------------------
	public function _empty($name){
		echo "Not Found!";
    }
}