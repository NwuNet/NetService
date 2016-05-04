<?php
namespace Staff\Widget;
use Think\Controller;
class StaticWidget extends Controller {
    public function schedule(){
        $schStaff = M('ScheduleStaff');
        $removestaff = $schStaff->where('status =1')->select();
        $this->assign('removestaff',$removestaff);
        $this->display('Static:schedule');
    }
}