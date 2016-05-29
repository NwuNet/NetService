<?php
namespace Boss\Controller;
use Think\Controller;
class DoServiceController extends BaseController {
	// --------------------服务派单---------------------
    public function send(){
    	$Card = M('ServiceCard');
		$cardtable = $Card -> select();
		$this -> assign('table',$cardtable );//服务单表
		
		$cardstate = $Card ->field('name,count(id) as number ')->group('id')->select();
		$this->assign('cardstate',$cardstate);//现有服务单的数量

		$maxtime = $Card->field('start')->order('start desc')->find();
		$m = month(strtotime($maxtime['start']));
		$cardtime = array();
		foreach ($m as $item) {
			$map['start'] = array('like',$item.'%');
			$cardshow = $Card->where($map)->field('start,count(start) as num')->select();
			$cardshow[0]['start'] =$item;
			array_push($cardtime,$cardshow[0]);
		}
		$this->assign('cardtime',$cardtime);
		
		
		$servicecardinfo = $Card->where('status = 0')->select();
		$this -> assign('servicecardinfo',$servicecardinfo );//服务单表

		$staff =D('Admin/StaffUserView')->where('status = 1')->field('uname')->select();
		$this->assign('staff',$staff);

        $this->display();
    }
	// --------------------显示服务单---------------------
	public function cardtable() {
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
    // --------------------服务单信息---------------------
	public function servicecard($id){
		if($id!=''){
			$Card = M('ServiceCard');
//			$state = M('ServiceCard');
			$repair = M('ServiceRepair');
			$cardinfo = $Card->where('id = %d',$id)->select();
//			$cardstate = $state ->where('status = 1')->select();
			$servicerepair = $repair->where('servicecard_id =%d',$id)->select();
			$this->assign("cardinfo",$cardinfo);
//			$this->assign('cardstate',$cardstate);
			$this->assign("servicerepair",$servicerepair);
			trace($servicerepair);
			$this->assign('id',$id);
			$this->display();
		}
    }
	// --------------------维修单添加状态---------------------
	public function servicerepairadd(){
		$servicecard_id = I('post.servicecard_id');
		$state = I('post.state');
		$operator = I('post.operator');
		if($servicecard_id==''||$state==''||$operator==''){
			$this->ajaxReturn("数据为空");
		}elseif($operator=='请选择'||$state=='请选择'){
			$this->ajaxReturn("请选择");
		}
		$repair = M('ServiceRepair');
		$repair->create();
		$repair->time  = date("Y-m-d H:i:s",NOW_TIME);
		$repair->add();
		if($state=='完成' && $repair){						
				$Card = M('ServiceCard');
				$data = $Card->where('id = "%d"',$servicecard_id)->field("id")->find();
		        $Card->id = $data['id'];
		        $Card->status = '1' ;
		        $Card ->save();
				if($Card){
			        $this->ajaxReturn(true);
		        }else{
			        $this->ajaxReturn("添加失败");
				}
		
		}else if($repair){
			        $this->ajaxReturn(true);
		        } else{
			        $this->ajaxReturn("添加失败");
		        }
	}
	// --------------------服务查询---------------------
    public function query(){
        $Card = M('ServiceCard');
		$cardtable = $Card -> select();
		$this -> assign('table',$cardtable );//服务单表
		
		/*未完成服务单数量*/
		
		$maxtime = $Card->field('start')->order('start desc')->find();	
		$m = month(strtotime($maxtime['start']));
		$cardtime1 = array();
		foreach ($m as $item) {
			 
			$map['start'] = array('like',$item.'%');
			$cardshow = $Card->where($map)->field('start,count(start) as num')->select();
			$cardshow[0]['start'] =$item;
			array_push($cardtime1,$cardshow[0]);
		}
		$this->assign('cardtime1',$cardtime1);
		
		/*已完成服务单数量*/
		$cardstate2 = $Card -> where('status = 1') ->field('name,count(id) as number ')->group('id')->select();
		$this->assign('cardstate1',$cardstate1);//现有服务单的数量

		$maxtime = $Card->field('start')->order('start desc')->find();
		$m = month(strtotime($maxtime['start']));
		$cardtime2 = array();
		foreach ($m as $item) {
			$map['start'] = array('like',$item.'%');
			$cardshow = $Card->where($map)->field('start,count(start) as num')->select();
			$cardshow[0]['start'] =$item;
			array_push($cardtime2,$cardshow[0]);
		}
		$this->assign('cardtime2',$cardtime2);
		
        $this->display();
    }
	// --------------------故障类别与描述---------------------
	public function breakinfo(){
		$breakinfo = M('BreakInfo');
		$rootbreak = $breakinfo->where('level = 1 and parent = 0')->order('label')->select();
		$this->assign('rootbreak',$rootbreak);
		$this->display();
	}
	public function breakadd(){
		$id = I('post.id');
		$name = I('post.name');
		$level = I('post.level');
		$parent = I('post.parent');
		$description = I('post.description');

		if($name == ''||$description == ''){
			$this->ajaxReturn('数据为空');
		}
		$breakinfo = M('BreakInfo');
		if($level ==''||$parent == ''){//修改，保存
			$breakinfo->create();
			$state = $breakinfo->save();
			if($state){
				$this->ajaxReturn("修改成功");
			}else{
				$this->ajaxReturn("修改失败");
			}
		}
		if($id=''||empty($id)){
			$breakinfo->create();
//			$breakinfo->name = $name;
//			$breakinfo->level = $level;
//			$breakinfo->parent = $parent;
//			$breakinfo->description = $description;
			$state = $breakinfo->add();
			if($state){
				$this->ajaxReturn("添加成功");
			}else{
				$this->ajaxReturn("添加失败");
			}
		}else{
			$breakinfo->create();
			$state = $breakinfo->save();
			if($state){
				$this->ajaxReturn("修改成功");
			}else{
				$this->ajaxReturn("修改失败");
			}
		}
	}
	// --------------------空操作---------------------
	public function breakgetsub(){
		$parent = I('post.parent');
		if($parent==''||empty($parent)){
			return "Not Found";
		}else{
			$breakinfo = M('BreakInfo');
			$data = $breakinfo->where('parent = %d',$parent)->field('id,name,parent,description,label')->select();
			$this->ajaxReturn($data);
		}
	}
	// --------------------空操作---------------------
	public function _empty($name){
		echo "Not Found!";
    }
}