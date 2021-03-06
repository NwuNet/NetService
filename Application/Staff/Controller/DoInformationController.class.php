<?php
namespace Staff\Controller;
use Think\Controller;
class DoInformationController extends BaseController {
	// --------------------信息反馈---------------------
    public function feedback(){
    	$loginService = D('Login','Service')->getuserInfo();
    	$Suggest = M('Suggest');		
		$suginfo = $Suggest->where('area = "%s" and status = 0',$loginService['area'])->select();			
		$this->assign("suginfo",$suginfo);
	   	
		$this->display();
    }
	// --------------------回复添加---------------------
	public function feedbackadd(){
		$suggest_id = I('post.suggest_id');
		$reply = I('post.reply');
		$operator = I('post.operator');
		if($suggest_id==''||$reply==''||$operator==''){
			$this->ajaxReturn("数据为空");
		}
		$feedback = M('SuggestFeedback');
		$feedback->create();
		$feedback->time  = date("Y-m-d H:i:s",NOW_TIME);
		$feedback->add();
		if($feedback){
			$Suggest = M('Suggest');	
			$Suggest->create();
			$Suggest->id = $suggest_id;			
			$Suggest->status=1;
			$Suggest->save();
			if($Suggest){
				$this->ajaxReturn(true);
			} else{
				$this->ajaxReturn("提交失败！");
		    }
			
		} else{
			$this->ajaxReturn("提交失败！");
		}
	}
	// --------------------历史信息反馈---------------------
    public function message(){
    	   	
		$this->display();
    }
	public function suggesttable() {
		$Suggest = D('Home/SuggestView');
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
				case 2 :$orderSql = " student_no " . $order_dir;break;
				case 3 :$orderSql = " suggest " . $order_dir;break;
				case 4 :$orderSql = " suggest_time " . $order_dir;break;
				case 5 :$orderSql = " reply " . $order_dir;break;
				case 6 :$orderSql = " operator " . $order_dir;break;
				case 7 :$orderSql = " time " . $order_dir;break;				
				default :$orderSql = '';
			}
		}
        		
		//搜索
		$search = $_GET['search']['value'];//获取前台传过来的过滤条件
		//分页
		$start = $_GET['start'];//从多少开始
		$length = $_GET['length'];//数据长度
		//表的总记录数 必要
		$recordsTotal = $Suggest->count();
        		
		$map['id|uname|student_no|suggest|suggest_time|reply|operator|time']=array('like',"%".$search."%");				
		if(strlen($search)>0){
			$recordsFiltered = count($Suggest->where($map)->select());
			$table = $Suggest->where($map)->order($orderSql)->limit($start.','.$length)->select();
		}else{
			$recordsFiltered = $recordsTotal;
			$table = $Suggest->order($orderSql)->limit($start.','. $length)->select();
		}

		$infos = array();
		foreach($table as $row){
			$obj = array($row['id'],$row['uname'],$row['student_no'],$row['suggest'],$row['suggest_time'],$row['reply'],$row['operator'],$row['time']);
			array_push($infos,$obj);
		}

		$this->ajaxReturn(array(
			"draw" => intval($draw),
			"recordsTotal" => intval($recordsTotal),
			"recordsFiltered" => intval($recordsFiltered),
			"data" => $infos
		));
	}    
}