<?php
namespace Boss\Controller;
use Think\Controller;
class DataAssetController extends Controller {
    public function index(){
        $this->display();
    }
	public function _empty($name){
		echo "Not Found!";
    }
}