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
        $staffreg = $staffRegister->where('time = "%s" and area = "%s"',date('Y-m-d',NOW_TIME),$loginService['area'])->select();
        $reg_leave = count($staffname)-count($staffreg);
        $this->assign('reg_sum',$reg_leave);
        $reg_percent = round( (1-$reg_leave/count($staffname))*100 );
        $this->assign('reg_percent',$reg_percent);
        $this->assign('reg_color',getpercentcolor($reg_percent));
        $this->assign('reg_all',count($staffname));

        $staffVacation = D('StaffVacationUserView');
        $staffvac = $staffVacation->where('status = 0 and area = "%s"',$loginService['area'])->select();
        $this->assign('vac_sum',count($staffvac));

        $staffDimission = D('DimissionUserView');
        $staffdim = $staffDimission->where('status = 0 and area = "%s"',$loginService['area'])->select();
        $this->assign('dim_sum',count($staffdim));

        $homeApply = D('ApplyView');
        $homeapp = $homeApply->where('a_status = 0 and area = "%s"',$loginService['area'])->select();
        $this->assign('app_sum',count($homeapp));

        $this->assign('task_sum',$reg_leave+count($staffvac)+count($staffdim)+count($homeapp));

        $this->display('QuickInfo:task');
    }
	public function self(){
		$this->display('QuickInfo:self');
	}
}