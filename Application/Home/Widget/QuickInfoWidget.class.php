<?php
namespace Home\Widget;
use Think\Controller;
class QuickInfoWidget extends Controller {
    public function message(){
//        $this->display('QuickInfo:message');
    }
	public function notice(){
//        $this->display('QuickInfo:notice');
    }
	public function task(){
//        $this->display('QuickInfo:task');
    }
	public function self(){
		$this->display('QuickInfo:self');
	}
}