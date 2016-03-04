<?php
namespace Boss\Controller;
use Think\Controller;
class DoAssetController extends BaseController {
	// --------------------工具---------------------
    public function tool(){
    	$Tool = D('ToolView');
		$this -> assign('table', $Tool -> select());
        $this->display();
    }
	public function tooltable() {
	    $Tool = D('ToolView');
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
	            case 0 :$orderSql = " asset_id " . $order_dir;break;
	            case 1 :$orderSql = " name " . $order_dir;break;
	            case 2 :$orderSql = " brand " . $order_dir;break;
	            case 3 :$orderSql = " model " . $order_dir;break;
				case 4 :$orderSql = " price " . 

$order_dir;break;
	            case 5 :$orderSql = " unit " . $order_dir;break;
	            case 6 :$orderSql = " ifborrow " . $order_dir;break;
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
	
	    $map['asset_id|name|brand|model|price|unit|ifborrow']=array('like',"%".$search."%");
	    if(strlen($search)>0){
	        $recordsFiltered = count($Tool->where($map)->select());
	        $table = $Tool->where($map)->order($orderSql)->limit($start.','.$length)->select();
	    }else{
	        $recordsFiltered = $recordsTotal;
	        $table = $Tool->order($orderSql)->limit($start.','. $length)->select();
	    }
	
	    $infos = array();
	    foreach($table as $row){
	        $obj = array($row['asset_id'],$row['name'],$row['brand'],$row['model'],$row['price'],$row['unit'],$row['ifborrow']);
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
	 /*   $Tool = M('Tool');	    
	    $data = array();	    
	    $data['img'] = '/Images/User/default.png';
	    $data['name'] = I('post.name');
	    $data['brand'] = I('post.brand');
		$data['model'] = I('post.model');
	    $data['price'] = I('post.price');
	    $data['unit'] = I('post.unit');
		$data['operator'] = '胡';
	    $data['date'] = '';
	    if ($Tool -> save($data)) {
	        $this -> ajaxReturn(TRUE);
	    } else {
	        $msg = "保存失败";
	        $this -> ajaxReturn(FALSE);
	    }
	  * 
	  */
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