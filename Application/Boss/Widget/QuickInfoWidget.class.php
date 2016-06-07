<?php
namespace Boss\Widget;
use Think\Controller;
class QuickInfoWidget extends Controller {
    public function message(){
//        $this->display('QuickInfo:message');
    }
	public function notice(){
//        $this->display('QuickInfo:notice');
    }
	public function task(){
        $loginService = D('Login','Service')->getuserInfo();//user
        $staffUser = M('ScheduleStaff');
        $attr = getdaykey(NOW_TIME);
        $staffname = $staffUser->where('area = "%s" and status = 1 and %s =1',$loginService['area'],$attr)->select();
        $staffRegister = M('StaffRegister');
        $staffreg = $staffRegister->where('time = "%s"',date('Y-m-d',NOW_TIME))->select();
        $reg_leave = count($staffname)-count($staffreg);
        $this->assign('reg_sum',$reg_leave);
        $reg_percent = round( (1-$reg_leave/count($staffname))*100 );
        $this->assign('reg_percent',$reg_percent);
        $this->assign('reg_color',getpercentcolor($reg_percent));
        $this->assign('reg_all',count($staffname));

        $staffVacation = M('StaffVacation');
        $staffvac = $staffVacation->where('status = 0')->select();
        $this->assign('vac_sum',count($staffvac));

        $staffDimission = M('Dimission');
        $staffdim = $staffDimission->where('status = 0')->select();
        $this->assign('dim_sum',count($staffdim));

        $homeApply = M('ApplyHome');
        $homeapp = $homeApply->where('a_status = 0')->select();
        $this->assign('app_sum',count($homeapp));

        $this->assign('task_sum',$reg_leave+count($staffvac)+count($staffdim)+count($homeapp));

        $this->display('QuickInfo:task');
    }
	public function self(){
		$this->display('QuickInfo:self');
	}
}