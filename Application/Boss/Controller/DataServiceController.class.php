<?php
namespace Boss\Controller;
use Think\Controller;
class DataServiceController extends Controller {
    public function index(){
        $this->display();
    }
	public function _empty($name){
		echo "Not Found!";
    }
}