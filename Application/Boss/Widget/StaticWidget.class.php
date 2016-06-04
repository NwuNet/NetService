<?php
namespace Boss\Widget;
use Think\Controller;
class StaticWidget extends Controller {
    public function schedule(){
        $loginService = D('Login','Service')->getuserInfo();//user
        $schStaff = M('ScheduleStaff');
        $removestaff = $schStaff->where('status =1 and area = "%s"',$loginService['area'])->select();
        $this->assign('removestaff',$removestaff);
        $this->display('Static:schedule');
    }
}