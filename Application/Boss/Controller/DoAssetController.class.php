<?php
namespace Boss\Controller;
use Think\Controller;
class DoAssetController extends BaseController {
	// --------------------工具---------------------
    public function tool(){
    	$Tool = M('AssetTool');
//		$tooltable = $Tool -> select();
//		$this -> assign('table',$tooltable );//工具表
		$select = M('ToolSelect');
		$toolname = $select->field('name,count(name)')->group('name')->select();
		$this->assign('toolname',$toolname);//选项名
				
		$unit = M('AssetUnit');
        $assetunit = $unit->select();
        $this->assign('assetunit',$assetunit);//资产单位

		$toolstate = $Tool->field('names,unit,count(names) as number')->group('names,unit')->select();
		$this->assign('toolstate',$toolstate);//现有工具的数量

		$maxtime = $Tool->field('start')->order('start desc')->find();
		$m = month(strtotime($maxtime['start']));
		$tooltime = array();
		foreach ($m as $item) {
			$map['start'] = array('like',$item.'%');
			$toolshow = $Tool->where($map)->field('start,count(start) as number')->select();
			$toolshow[0]['start'] =$item;
			array_push($tooltime,$toolshow[0]);
		}
		$this->assign('tooltime',$tooltime);
        $this->display();
    }
	// --------------------工具选项---------------------
	public function toolselect(){
		$num = I('post.num');
		$name = I('post.name');
		$brand = I('post.brand');
		$model = I('post.model');
		if($num==1){
			$select = M('ToolSelect');
			$brand = $select -> where('name = "%s"',$name) ->field('brand,count(brand)')->group('brand')->select();
			$this->ajaxReturn($brand);
		}elseif($num==2){
			$select = M('ToolSelect');
			$model = $select -> where('name = "%s" and brand = "%s"',$name,$brand) ->field('model,count(model)')->group('model')->select();
			$this->ajaxReturn($model);
		}
	}
	public function tooltable() {
	    $Tool = M('AssetTool');
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
	            case 1 :$orderSql = " seq " . $order_dir;break;
	            case 2 :$orderSql = " names " . $order_dir;break;
	            case 3 :$orderSql = " brand " . $order_dir;break;
				case 4 :$orderSql = " model " . $order_dir;break;
	            case 5 :$orderSql = " unit " . $order_dir;break;
				case 6 :$orderSql = " start " . $order_dir;break;
	            default :$orderSql = '';
	        }
	    }
	
	    //搜索
	    $search = $_GET['search']['value'];//获取前台传过来的过滤条件
	    //分页
	    $start = $_GET['start'];//从多少开始
	    $length = $_GET['length'];//数据长度
	    //表的总记录数 必要
	    $recordsTotal = $Tool->count();
	
	    $map['id|seq|names|brand|model|unit|start']=array('like',"%".$search."%");
	    if(strlen($search)>0){
	        $recordsFiltered = count($Tool->where($map)->select());
	        $table = $Tool->where($map)->order($orderSql)->limit($start.','.$length)->select();
	    }else{
	        $recordsFiltered = $recordsTotal;
	        $table = $Tool->order($orderSql)->limit($start.','. $length)->select();
	    }
	
	    $infos = array();
	    foreach($table as $row){
	        $obj = array($row['id'],$row['seq'],$row['names'],$row['brand'],$row['model'],$row['unit'],$row['start']);
	        array_push($infos,$obj);
	    }
	
	    $this->ajaxReturn(array(
	        "draw" => intval($draw),
	        "recordsTotal" => intval($recordsTotal),
	        "recordsFiltered" => intval($recordsFiltered),
	        "data" => $infos
	    ));
	}
    
	public function tooladd() {
		$names = I('post.names');
		$brand = I('post.brand');
		$model = I('post.model');
		$number = I('post.number');
		$unit = I('post.unit');
		if($names==''||$brand==''||$model==''||$number==''||$unit==''){
			$this->ajaxReturn("数据为空");
		}elseif($names=='请选择'||$brand=='请选择'||$model=='请选择'){
			$this->ajaxReturn("请选择");
		}elseif($number<=0){
			$this->ajaxReturn("数量必须大于0");
		}

		preg_match_all("/./u", $names, $arr);//拆分汉字
		$arrayName = $arr[0];//赋值名称字符串
		$strname = getFirstChar($arrayName[0]);
		if(count($arrayName,0)>1){
			for($j=1;$j<count($arrayName,0);$j++){
				$strname = $strname.getFirstChar($arrayName[$j]);
			}
		}

		$Tool = M('AssetTool');
		for($i=0;$i<$number;$i++){
			$Tool->create();
			$Tool->seq = $strname.'-'.NOW_TIME.'-'.$i;
			$Tool->start = date("Y-m-d H:i:s",NOW_TIME);
			$Tool->add();
		}
		if($Tool){
				$this->ajaxReturn(true);
		}else{
			   $this->ajaxReturn("添加失败");
		}
	}
	// --------------------工具卡片---------------------
	public function toolcard($id){
		if($id!=''){
			$Tool = M('AssetTool');
			$state = M('ToolState');
			$content = M('AssetContent');
			$toolinfo = $Tool->where('id = %d',$id)->select();
			$toolstate = $state ->where('status = 1')->select();
			$toolcontent = $content->where('class = 1 and asset_id =%d',$id)->select();
			$this->assign("toolinfo",$toolinfo);
			$this->assign('toolstate',$toolstate);
			$this->assign('toolcontent',$toolcontent);
			trace($toolcontent);
			$this->assign('id',$id);
			$this->display();
		}
    }
	// --------------------工具卡片添加状态---------------------
	public function toolcardadd(){
		$asset_id = I('post.asset_id');
		$state = I('post.state');
		$class = I('post.class');
		$user = I('post.user');
		$actor = I('post.actor');
		$label = I('post.label');
		if($asset_id==''||$state==''||$class==''||$user==''||$actor==''||$label==''){
			$this->ajaxReturn("数据为空");
		}elseif($user=='请选择'||$state=='请选择'){
			$this->ajaxReturn("请选择");
		}
		$content = M('AssetContent');
		$content->create();
		$content->time  = date("Y-m-d H:i:s",NOW_TIME);
		$content->add();
		if($content){
			$this->ajaxReturn(true);
		}else{
			$this->ajaxReturn("添加失败");
		}
	}
	// --------------------耗材---------------------
	public function exhaust(){
        $Exhaust = M('AssetExhaust');
		$this -> assign('table', $Exhaust -> select());//耗材列表
		$select = M('ExhaustSelect');
		$exhaustname = $select->field('name,count(name)')->group('name')->select();
		$this->assign('exhaustname',$exhaustname);//耗材名
				
		$unit = M('AssetUnit');
        $assetunit = $unit->select();
        $this->assign('assetunit',$assetunit);//资产单位
		
		$exhauststate = $Exhaust ->field('names,unit,sum(names) as numberall ')->group('names,unit')->select();
		$this->assign('exhauststate',$exhauststate);//现有耗材的数量

//		$nowtime = strtotime()
//		$mintime = $Exhaust->field('start')->order('start')->find();
		$maxtime = $Exhaust->field('start')->order('start desc')->find();
		$m = month(strtotime($maxtime['start']));
		$exhausttime = array();
		foreach ($m as $item) {
			$map['start'] = array('like',$item.'%');
			$exhaustshow = $Exhaust->where($map)->field('start,count(start) as num')->select();
			$exhaustshow[0]['start'] =$item;
			array_push($exhausttime,$exhaustshow[0]);
		}
		$this->assign('exhausttime',$exhausttime);
		
        $this->display();
    }
	// --------------------耗材选项---------------------
	public function exhaustselect(){
		$num = I('post.num');
		$name = I('post.name');
		$brand = I('post.brand');
		$model = I('post.model');
		if($num==1){
			$select = M('ExhaustSelect');
			$brand = $select -> where('name = "%s"',$name) ->field('brand,count(brand)')->group('brand')->select();
			$this->ajaxReturn($brand);
		}elseif($num==2){
			$select = M('ExhaustSelect');
			$model = $select -> where('name = "%s" and brand = "%s"',$name,$brand) ->field('model,count(model)')->group('model')->select();
			$this->ajaxReturn($model);
		}
	}
	public function exhausttable() {
	    $Exhaust = M('AssetExhaust');
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
	            case 1 :$orderSql = " day " . $order_dir;break;
	            case 2 :$orderSql = " names " . $order_dir;break;
	            case 3 :$orderSql = " brand " . $order_dir;break;
				case 4 :$orderSql = " model " . $order_dir;break;
				case 5 :$orderSql = " number " . $order_dir;break;
	            case 6 :$orderSql = " unit " . $order_dir;break;
				case 7 :$orderSql = " start " . $order_dir;break;
	            default :$orderSql = '';
	        }
	    }
	
	    //搜索
	    $search = $_GET['search']['value'];//获取前台传过来的过滤条件
	    //分页
	    $start = $_GET['start'];//从多少开始
	    $length = $_GET['length'];//数据长度
	    //表的总记录数 必要
	    $recordsTotal = $Exhaust->count();
	
	    $map['id|day|names|brand|model|number|unit|start']=array('like',"%".$search."%");
	    if(strlen($search)>0){
	        $recordsFiltered = count($Exhaust->where($map)->select());
	        $table = $Exhaust->where($map)->order($orderSql)->limit($start.','.$length)->select();
	    }else{
	        $recordsFiltered = $recordsTotal;
	        $table = $Exhaust->order($orderSql)->limit($start.','. $length)->select();
	    }
	
	    $infos = array();
	    foreach($table as $row){
	        $obj = array($row['id'],$row['day'],$row['names'],$row['brand'],$row['model'],$row['number'],$row['unit'],$row['start']);
	        array_push($infos,$obj);
	    }
	
	    $this->ajaxReturn(array(
	        "draw" => intval($draw),
	        "recordsTotal" => intval($recordsTotal),
	        "recordsFiltered" => intval($recordsFiltered),
	        "data" => $infos
	    ));
	}
    
	public function exhaustadd() {
		$names = I('post.names');
		$brand = I('post.brand');
		$model = I('post.model');
		$number = I('post.number');
		$unit = I('post.unit');
		if($names==''||$brand==''||$model==''||$number==''||$unit==''){
			$this->ajaxReturn("数据为空");
		}elseif($names=='请选择'||$brand=='请选择'||$model=='请选择'){
			$this->ajaxReturn("请选择");
		}elseif($number<=0){
			$this->ajaxReturn("数量必须大于0");
		}		

		$Exhaust = M('AssetExhaust');		
		$Exhaust->create();
		$Exhaust->day = date("Y-m-d H:i:s",NOW_TIME);
		$Exhaust->start = date("Y-m-d H:i:s",NOW_TIME);
		$Exhaust->add();
		
/*		$assetcontent = M('AssetContent');
		if($Exhaust->add()){
		//	$tid = $Exhaust->where('day = "%s"',$data['day'])->field("day,id")->find();
			$data = array();
			$data['asset_id'] = $tid['id'];
			$data['state_id'] = '1';
			$data['num'] = $number;
			$data['time'] = date("Y-m-d H:i:s",NOW_TIME);
			$data['actor'] = 'kkk';  //$user.uname
			//$assetcontent->add($data);			
			if($assetcontent->add($data)){
				$this->ajaxReturn(true);
		    }else{
			   $this->ajaxReturn("添加失败");
		    }
		}
*/
		if($Exhaust){
				$this->ajaxReturn(true);
		}else{
			   $this->ajaxReturn("添加失败");
		}
		
	}
	// --------------------耗材卡片---------------------
	public function exhaustcard($id){
		if(!empty($id)){
	        $exhaust = M('AssetExhaust');
	        $table = $exhaust->where('id=%d',$id)->select();
	        $this->assign('id',$id);	
			$this->assign('day',$table[0]['day']);	       	
	        $this->assign('names',$table[0]['names']);
			$this->assign('brand',$table[0]['brand']);
	        $this->assign('model',$table[0]['model']);
	        $this->assign('number',$table[0]['number']);
	        $this->assign('unit',$table[0]['unit']);
	        $this->assign('start',$table[0]['start']);
	        
	        $this->display();
	    }
    }
	
	// --------------------设备---------------------
	public function device(){
        $this->display();
    }
	// --------------------证照---------------------
	public function paper(){
		$select = M('PaperSelect');
		$paperclass = $select->field('class,count(class)')->group('class')->select();
		$this->assign('paperclass',$paperclass);//选项名
        $this->display();
    }
	// --------------------证照选项---------------------
	public function paperselect(){
		$num = I('post.num');
		$class = I('post.class');
		if($num==1){
			$select = M('PaperSelect');
			$name = $select -> where('class = "%s"',$class) ->field('name,count(name)')->group('name')->select();
			$this->ajaxReturn($name);
		}
	}
	// --------------------其他---------------------
	public function other(){
        $Other = M('AssetOther');
		$this -> assign('table', $Other -> select());//其他列表
		$select = M('OtherSelect');
		$othername = $select->field('name,count(name)')->group('name')->select();
		$this->assign('othername',$othername);//其他名称
				
		$unit = M('AssetUnit');
        $assetunit = $unit->select();
        $this->assign('assetunit',$assetunit);//其他单位
		
		$otherstate = $Other ->field('names,unit,sum(names) as numberall ')->group('names,unit')->select();
		$this->assign('otherstate',$otherstate);//现有其他的数量

//		$nowtime = strtotime()
//		$mintime = $Other->field('start')->order('start')->find();
		$maxtime = $Other->field('start')->order('start desc')->find();
		$m = month(strtotime($maxtime['start']));
		$othertime = array();
		foreach ($m as $item) {
			$map['start'] = array('like',$item.'%');
			$othershow = $Other->where($map)->field('start,count(number) as num')->select();
			$othershow[0]['start'] =$item;
			array_push($othertime,$othershow[0]);
		}
		$this->assign('othertime',$othertime);
		
        $this->display();
    }
// --------------------其他选项---------------------
	public function otherselect(){
		$num = I('post.num');
		$name = I('post.name');
		$campus = I('post.campus');
		$room = I('post.room');
		if($num==1){
			$select = M('OtherSelect');
			$campus = $select -> where('name = "%s"',$name) ->field('campus,count(campus)')->group('campus')->select();
			$this->ajaxReturn($campus);
		}elseif($num==2){
			$select = M('OtherSelect');
			$room = $select -> where('name = "%s" and campus = "%s"',$name,$campus) ->field('room,count(room)')->group('room')->select();
			$this->ajaxReturn($room);
		}
	}
	public function othertable() {
	    $Other = M('AssetOther');
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
	            case 1 :$orderSql = " seq " . $order_dir;break;
	            case 2 :$orderSql = " names " . $order_dir;break;
	            case 3 :$orderSql = " campus " . $order_dir;break;
				case 4 :$orderSql = " room " . $order_dir;break;
	            case 5 :$orderSql = " unit " . $order_dir;break;
				case 6 :$orderSql = " start " . $order_dir;break;
	            default :$orderSql = '';
	        }
	    }
	
	    //搜索
	    $search = $_GET['search']['value'];//获取前台传过来的过滤条件
	    //分页
	    $start = $_GET['start'];//从多少开始
	    $length = $_GET['length'];//数据长度
	    //表的总记录数 必要
	    $recordsTotal = $Other->count();
	
	    $map['id|seq|names|campus|room|unit|start']=array('like',"%".$search."%");
	    if(strlen($search)>0){
	        $recordsFiltered = count($Other->where($map)->select());
	        $table = $Other->where($map)->order($orderSql)->limit($start.','.$length)->select();
	    }else{
	        $recordsFiltered = $recordsTotal;
	        $table = $Other->order($orderSql)->limit($start.','. $length)->select();
	    }
	
	    $infos = array();
	    foreach($table as $row){
	        $obj = array($row['id'],$row['seq'],$row['names'],$row['campus'],$row['room'],$row['unit'],$row['start']);
	        array_push($infos,$obj);
	    }
	
	    $this->ajaxReturn(array(
	        "draw" => intval($draw),
	        "recordsTotal" => intval($recordsTotal),
	        "recordsFiltered" => intval($recordsFiltered),
	        "data" => $infos
	    ));
	}
    
	public function otheradd() {
		$names = I('post.names');
		$campus = I('post.campus');
		$room = I('post.room');
		$number = I('post.number');
		$unit = I('post.unit');
		if($names==''||$campus==''||$room==''||$number==''||$unit==''){
			$this->ajaxReturn("数据为空");
		}elseif($names=='请选择'||$campus=='请选择'||$room=='请选择'){
			$this->ajaxReturn("请选择");
		}elseif($number<=0){
			$this->ajaxReturn("数量必须大于0");
		}		

        preg_match_all("/./u", $names, $arr);//拆分汉字
		$arrayName = $arr[0];//赋值名称字符串
		$strname = getFirstChar($arrayName[0]);
		if(count($arrayName,0)>1){
			for($j=1;$j<count($arrayName,0);$j++){
				$strname = $strname.getFirstChar($arrayName[$j]);
			}
		}

		$Other = M('AssetOther');
		for($i=0;$i<$number;$i++){
			$Other->create();
			$Other->seq = $strname.'-'.NOW_TIME.'-'.$i;
			$Other->start = date("Y-m-d H:i:s",NOW_TIME);
			$Other->number ='1';
			$Other->add();		
		}
        				
/*		$assetcontent = M('AssetContent');
		if($Other->add()){
		//	$tid = $Other->where('day = "%s"',$data['day'])->field("day,id")->find();
			$data = array();
			$data['asset_id'] = $tid['id'];
			$data['state_id'] = '1';
			$data['num'] = $number;
			$data['time'] = date("Y-m-d H:i:s",NOW_TIME);
			$data['actor'] = 'kkk';  //$user.uname
			//$assetcontent->add($data);			
			if($assetcontent->add($data)){
				$this->ajaxReturn(true);
		    }else{
			   $this->ajaxReturn("添加失败");
		    }
		}
*/
		if($Other){
				$this->ajaxReturn(true);
		}else{
			   $this->ajaxReturn("添加失败");
		}
		
	}
	// --------------------其他卡片---------------------
	public function othercard($id){
		if(!empty($id)){
	        $Other = M('AssetOther');
	        $table = $Other->where('id=%d',$id)->select();
	        $this->assign('id',$id);	
			$this->assign('seq',$table[0]['seq']);	       	
	        $this->assign('names',$table[0]['names']);
			$this->assign('campus',$table[0]['campus']);
	        $this->assign('room',$table[0]['room']);
	        $this->assign('number',$table[0]['number']);
	        $this->assign('unit',$table[0]['unit']);
	        $this->assign('start',$table[0]['start']);
	        
	        $this->display();
	    }
    }
	
	// --------------------空操作---------------------
	public function _empty($name){
		echo "Not Found!";
    }
}