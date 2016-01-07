<?php
namespace Boss\Controller;
use Think\Controller;
class SetReportController extends Controller {
    // --------------------资产---------------------
    public function asset(){
        $this->display();
    }
	// --------------------服务---------------------
    public function service(){
        $this->display();
    }
	// --------------------人事---------------------
    public function people(){
        $this->display();
    }
	// --------------------空操作---------------------
	public function _empty($name){
		echo "Not Found!";
    }
}