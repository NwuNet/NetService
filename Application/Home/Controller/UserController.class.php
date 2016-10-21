<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends BaseController {
	public function index() {
		$homeuser = D('Login','Service')->getuserInfo();
		$Card = M('ServiceCard');
		$cardinfo = $Card->where('status=0 and name = "%s" and student_no ="%s" ',$homeuser['uname'],$homeuser['number'])->find();
		$dormitory = explode('-',$cardinfo['dormitory'] );
		$cardinfo['building'] = $dormitory[0];
		$cardinfo['room'] = $dormitory[1];		
		$this->assign("cardinfo",$cardinfo);

		$evaluate = M('ServiceEvaluate');
		$servicevaluate = $evaluate->where('servicecard_id = "%d" ',$cardinfo['id'])->select();
		$this->assign('evaluate',count($servicevaluate));
		$this->assign('servicevaluate',$servicevaluate);
		
		if($cardinfo){
			$repair = M('ServiceRepair');
			$servicerepair = $repair->where('state="维修" and servicecard_id = "%d"',$cardinfo['id'])->select();
			$this->assign("servicerepair",$servicerepair);
		}
		
		$questionlist = M("QuestionList");
		$questioninf = $questionlist->where()->select();
		$this->assign('questioninf',$questioninf);
		
		$this -> display();
	}
	// --------------------维修单信息变更---------------------
	public function servicecardedit(){
		$id = I('post.id');
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
		if($id=''||$uname==''||$student_no==''||$phone==''||$building==''||$question==''||$room==''||$description==''||$appointment_time==''||$area==''){
			$this->ajaxReturn("数据为空");
		}
		$card = M('ServiceCard');
		$card->create();
		$card->dormitory = $building.'-'.$room ;
		$card->appointment_time = date("Y-m-d",strtotime($appointment_time));
		if($card->save()){
			$this->ajaxReturn(true);
		}else{
			$info['msg'] = '修改失败';
			$this->ajaxReturn($info);
		}
	}
	// --------------------维修单信息删除---------------------
	public function servicecarddelete(){
		$id = I('post.id');
		$uname = I('post.name');
		$student_no = I('post.student_no');
		$phone = I('post.phone');
		$building = I('post.building');
		$room = I('post.room');
//		$description = I('post.description');
		$description = "null";
		$appointment_time = I('post.appointment_time');
		$area = I('post.area');
		if($id=''||$uname==''||$student_no==''||$phone==''||$building==''||$room==''||$description==''||$appointment_time==''||$area==''){
			$this->ajaxReturn("数据为空");
		}
		$card = M('ServiceCard');
		$card->create();
		$card->dormitory = $building.'-'.$room ;
		$card->appointment_time = date("Y-m-d",strtotime($appointment_time));
		$card->description = '删除服务单';
		$card->end = date("Y-m-d H:i:s",NOW_TIME);
		$card->status = 1;
		if($card->save()){
			$this->ajaxReturn(true);
		}else{
			$info['msg'] = '删除失败';
			$this->ajaxReturn($info);
		}
	}
    // --------------------维修单添加评价---------------------
	public function servicevaluateadd(){	
		$servicecard_id = I('post.servicecard_id');
		$evaluation = I('post.evaluation');
		if($evaluation==''){
			$this->ajaxReturn("数据为空");
		}
		$evaluate = M('ServiceEvaluate');
		$evaluate->create();		
		$evaluate->time  = date("Y-m-d H:i:s",NOW_TIME);
		$evaluate->add();
		if($evaluate){						
			$this->ajaxReturn(true);	
		}else{ 			        		       
			$this->ajaxReturn("提交失败");
		}
	}
    public function service() {
    	$homeuser = D('Login','Service')->getuserInfo();		
    	$Card = M('ServiceCard');
        $servicecardinfo = $Card->where('status = 1 and student_id = %d',$homeuser['id'])->select();
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
	// --------------------服务单信息---------------------
	public function servicecard($id){
		if($id!=''){
			$Card = M('ServiceCard');
			$repair = M('ServiceRepair');
			$cardinfo = $Card->where('id = %d',$id)->select();		
			$servicerepair = $repair->where('servicecard_id =%d',$id)->select();
			$this->assign("cardinfo",$cardinfo);
			$this->assign("servicerepair",$servicerepair);
			trace($servicerepair);
			$this->assign('id',$id);
			
			$staffUser = D('Admin/StaffUserView');
            $staffname = $staffUser->field('uname')->select();
            $this->assign('staffname',$staffname);//员工名称
            
            $evaluate = M('ServiceEvaluate');
			$servicevaluate = $evaluate->where('servicecard_id =%d',$id)->select();
			$this->assign('servicevaluate',$servicevaluate);
			$this->display();
		}
	}
	// --------------------申请兼职---------------------
	public function apply(){
		$applyrepply = D('ApplyView');
		$homeuser = D('Login','Service')->getuserInfo();
			
	/*	$state = $applyrepply->where('home_id=%d',$homeuser['id'])->select();
		$this->assign('state',$state);*/
		
		$apply = D('Boss/ApplyView');					
		$applyinfo = $apply->where('a_status=0 and home_id=%d',$homeuser['id'])->find();
		$this->assign('applyinfo',$applyinfo);
		$this->assign('id',$applyinfo['id']);
	//	$this->ajaxReturn($applyinfo);
	    $Position = M('PositionState');
	    $positionname = $Position->select();
		$this->assign('positionname',$positionname);
	
		$this->display();
	}
	// --------------------申请兼职结果---------------------
	public function applyreply(){
		$applyrepply = D('ApplyView');
		$homeuser = D('Login','Service')->getuserInfo();			
		$state = $applyrepply->where('home_id=%d',$homeuser['id'])->select();
		$this->assign('state',$state);				
	
		$this->display();
	}
	// --------------------兼职申请单添加--------------------
	public function applyadd(){
		$home_id = I('post.home_id');
		$start_time = I('post.start_time');
		$end_time = I('post.end_time');
		$specialty = I('post.specialty');
		$position = I('post.position');
		$intro = I('post.intro');
		if($home_id==''||$start_time==''||$end_time==''||$specialty==''||$intro==''||$position==''){
			$this->ajaxReturn("数据为空");
		}
		$Apply = M('ApplyHome');
		$Apply->create();
		$Apply->a_status=0;
		$Apply->time  = date("Y-m-d H:i:s",NOW_TIME);
		$Apply->add();
		if($Apply){
			$this->ajaxReturn(true);
		} else{
			$this->ajaxReturn("提交失败");
		}
	}
	// --------------------兼职申请单修改--------------------
	public function applyedit(){
		$apply_id = I('post.apply_id');
		$start_time = I('post.start_time');
		$end_time = I('post.end_time');
		$specialty = I('post.specialty');
		$position = I('post.position');
		$intro = I('post.intro');
		if($apply_id==''){
			$this->ajaxReturn("数据为空");
		}
		
		$Apply = M('ApplyHome');
		$Apply->create();
		$Apply->id = $apply_id;
		$Apply->start_time = $start_time;
		$Apply->end_time = $end_time;
		$Apply->specialty = $specialty;
		$Apply->position = $position;
		$Apply->intro = $intro;		
		$Apply->save();
		if($Apply){
			$this->ajaxReturn(true);
		} else{
			$this->ajaxReturn("修改失败");
		}
	}
	// --------------------兼职申请删除---------------------
	public function applydelete(){
		$id = I('post.id');		
		if($id=''){
			$this->ajaxReturn("数据为空");
		}
		$Apply = M('ApplyHome');
		$Apply->create();		
		if($Apply->delete()){
			$this->ajaxReturn(true);
		}else{
			$info['msg'] = '删除失败';
			$this->ajaxReturn($info);
		}
	}
	// --------------------建议反馈---------------------
	public function suggest(){
		$homeuser = D('Login','Service')->getuserInfo();
		$Suggest = M('Suggest');
		$suginfo = $Suggest->where('status=0 and uname = "%s" and student_no ="%s" ',$homeuser['uname'],$homeuser['number'])->find();			
		$this->assign("suginfo",$suginfo);		
		
		$this->display();
	}
    // --------------------建议信息变更---------------------
	public function suggestedit(){
		$id = I('post.id');
		$uname = I('post.uname');
		$student_no = I('post.student_no');
		$suggest = I('post.suggest');
		
		if($id=''||$uname==''||$student_no==''||$suggest==''){
			$this->ajaxReturn("数据为空");
		}
		$suggest = M('Suggest');
		$suggest->create();
		if($suggest->save()){
			$this->ajaxReturn(true);
		}else{
			$info['msg'] = '修改失败';
			$this->ajaxReturn($info);
		}
	}
	// --------------------建议信息添加---------------------
	public function suggestadd(){
		$uname = I('post.uname');
		$student_no = I('post.student_no');
		$suggest = I('post.suggest');
		if($suggest==''){
			$this->ajaxReturn("数据为空");
		}
		$homeuser = D('Login','Service')->getuserInfo();
		$Suggest = M('Suggest');
		$Suggest->create();
		$Suggest->status=0;
		$Suggest->time  = date("Y-m-d H:i:s",NOW_TIME);
		$Suggest->area = $homeuser['area'];
		$Suggest->add();
		if($Suggest){
			$this->ajaxReturn(true);
		} else{
			$this->ajaxReturn("提交失败");
		}
	}
	// --------------------建议信息删除---------------------
	public function suggestdelete(){
		$id = I('post.id');
		
		if($id=''){
			$this->ajaxReturn("数据为空");
		}
		$Suggest = M('Suggest');
		$Suggest->create();		
		if($Suggest->delete()){
			$this->ajaxReturn(true);
		}else{
			$info['msg'] = '删除失败';
			$this->ajaxReturn($info);
		}
	}
	public function suggestfeedback() {
		$homeuser = D('Login','Service')->getuserInfo();
		$Suggest = D('SuggestView');
		$suginfo = $Suggest->where('status=1 and uname = "%s" and student_no ="%s" ',$homeuser['uname'],$homeuser['number'])->select();			
		$this->assign("suginfo",$suginfo);
								
		$this->display();
	}	
	public function _empty($name) {
		echo "Not Found!";
	}

}
