<?php
namespace Boss\Controller;
use Think\Controller;
class DoServiceController extends BaseController {
	// --------------------服务派单---------------------
    public function send(){
        $this->display();
    }
	// --------------------服务单信息---------------------
    public function servicecard($id){
		$this->assign('id',$id);
        $this->display();
    }
	// --------------------服务查询---------------------
    public function query(){
        $this->display();
    }
	// --------------------空操作---------------------
	public function _empty($name){
		echo "Not Found!";
    }
}