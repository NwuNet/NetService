<?php
namespace Staff\Widget;
use Think\Controller;
class StaticWidget extends Controller {
    public function schedule(){
        $schStaff = M('ScheduleStaff');
        $loginService = D('Login','Service')->getuserInfo();//user
        $removestaff = $schStaff->where('status =1 and area = "%s"',$loginService['area'])->select();
        $this->assign('removestaff',$removestaff);
        $this->display('Static:schedule');
    }
}