<?php
namespace Staff\Widget;
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
        $staffname = $staffUser->where('uname = "%s" and area = "%s" and status = 1 and %s =1',$loginService['cname'],$loginService['area'],$attr)->select();
        $staffRegister = M('StaffRegister');
        $staffreg = $staffRegister->where('uname = "%s" and time = "%s"',$loginService['cname'],date('Y-m-d',NOW_TIME))->select();

        $this->assign('reg_sum',count($staffname)-count($staffreg));
        $this->assign('reg_info',$staffreg);

        $serviceCard = M('ServiceCard');
        $servicecardinfo = $serviceCard->where('status = 0 and area = "%s"',$loginService['area'])->select();
        $this->assign('service_sum',count($servicecardinfo));
        $CardRepair = M('ServiceRepair');
        $repairsum = 0;
        foreach ($servicecardinfo as $key => $value){
            $repairsum += count($CardRepair->where('servicecard_id = %d and operator="%s"',$value['id'],$loginService['cname'])->select());
        }
        $service_leave = count($servicecardinfo) - $repairsum;
        $this->assign('service_leave',$service_leave);
        $ser_percent = round( (1-$repairsum/count($servicecardinfo))*100 );
        $this->assign('ser_percent',$ser_percent);
        $this->assign('ser_color',getpercentcolor($ser_percent));

        $Suggest = M('Suggest');
        $sug = $Suggest->where('status = 0 and area = "%s"',$loginService['area'])->select();
        $this->assign('sug_sum',count($sug));

        $this->assign('task_sum', $service_leave + count($staffname)-count($staffreg) +count($sug) );

        $this->display('QuickInfo:task');
    }
	public function self(){
		$this->display('QuickInfo:self');
	}
}