<?php
namespace Boss\Controller;
use Think\Controller;
class DoAssetController extends Controller {
	// --------------------工具---------------------
    public function tool(){
        $this->display();
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