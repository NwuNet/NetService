<?php
namespace Boss\Controller;
use Think\Controller;
class DoAssetController extends BaseController {
	// --------------------工具---------------------
    public function tool(){
    	$Tool = M('AssetTool');
		$this -> assign('table', $Tool -> select());
		$select = M('ToolSelect');
		$toolname = $select->field('name,count(name)')->group('name')->select();
		$this->assign('toolname',$toolname);
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
		$this->assign('id',$id);
        $this->display();
    }
	// --------------------耗材---------------------
	public function exhaust(){
        $this->display();
    }
	// --------------------耗材卡片---------------------
	public function exhaustcard($id){
		$this->assign('id',$id);
        $this->display();
    }
	// --------------------设备---------------------
	public function device(){
        $this->display();
    }
	// --------------------证照---------------------
	public function paper(){
        $this->display();
    }
	// --------------------其他---------------------
	public function other(){
        $this->display();
    }
	// --------------------空操作---------------------
	public function _empty($name){
		echo "Not Found!";
    }
}