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
		
		$Register = M('StaffRegister');
		$registertable = $Register -> select();
		$this -> assign('register1',$registertable );//服务单表
		
        $this->display();
    }
	// --------------------添加员工考勤信息---------------------
	public function registeradd(){
		$name = I('post.name');
		$time = I('post.time');
		$state = I('post.state');
		
		if($name==''||$time==''||$state==''){
			$this->ajaxReturn("数据为空");
		}elseif($name=='请选择'||$state=='请选择'){
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
	    $Register = M('StaffRegister');
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
	            case 2 :$orderSql = " time " . $order_dir;break;
	            case 3 :$orderSql = " state " . $order_dir;break;				
	            default :$orderSql = '';
	        }
	    }
	
	    //搜索
	    $search = $_GET['search']['value'];//获取前台传过来的过滤条件
	    //分页
	    $start = $_GET['start'];//从多少开始
	    $length = $_GET['length'];//数据长度
	    //表的总记录数 必要
	    $recordsTotal = $Register->count();
	
	    $map['id|name|time|state']=array('like',"%".$search."%");
	    if(strlen($search)>0){
	        $recordsFiltered = count($Register->where($map)->select());
	        $table = $Register->where($map)->order($orderSql)->limit($start.','.$length)->select();
	    }else{
	        $recordsFiltered = $recordsTotal;
	        $table = $Register->order($orderSql)->limit($start.','. $length)->select();
	    }
	
	    $infos = array();
	    foreach($table as $row){
	        $obj = array($row['id'],$row['name'],$row['time'],$row['state']);
	        array_push($infos,$obj);
	    }
	
	    $this->ajaxReturn(array(
	        "draw" => intval($draw),
	        "recordsTotal" => intval($recordsTotal),
	        "recordsFiltered" => intval($recordsFiltered),
	        "data" => $infos
	    ));
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
        $this->display();
    }
	// --------------------空操作---------------------
	public function _empty($name){
		echo "Not Found!";
    }
}