<?php
namespace Boss\Controller;
use Think\Controller;
class DataPeopleController extends BaseController {
	public function index() {
		$Area = M('UserArea');
		$userarea = $Area -> select();
		$this -> assign('userarea', $userarea);
		$this -> display();
	}

	public function report() {
		if (I('post.begin_time') == '' || I('post.end_time') == '') {
			echo "<h1>" . I('post.begin_time') . I('post.end_time') . "数据不正确</h1>";
		} else {
			$this -> begin_time = I('post.begin_time');
			$this -> end_time = I('post.end_time');
			$begin_time = I('post.begin_time');
			$end_time = I('post.end_time');
			//--------------------------area-----------------------
			$Area = M('UserArea');
			$userarea = $Area -> select();
			$this -> assign('userarea', $userarea);

			$Staff = D('Admin/StaffUserView');
			$Vacation = M('StaffVacation');
			$Model = new \Think\Model();

			$time_begin = date('Y-m-d 00:00:00', strtotime($begin_time));
			$time_end = date('Y-m-d 23:59:59', strtotime($end_time));

			//-------------------------Staff graph area--------------------
			$areacount = array();
			foreach ($userarea as $key => $item) {
				$map['begintime'] = array('elt', $time_end);
				$map['status'] = 1;
				$map['area'] = $item['area'];
				$map['level'] = 3;
				$tmp = $Staff -> where($map) -> select();
				$areacount[$key] = count($tmp);

			}

			$this -> areacount = $areacount;
			$areastaff = array();
			foreach ($userarea as $key => $item) {
				$tmp = $Model -> query("SELECT 
                net_staff_user.id,
                net_staff_user.user_id,
                net_staff_user.area,
                net_staff_user.job
                FROM
                net_user ,
                net_staff_user
                WHERE net_user.user_id=net_staff_user.user_id AND net_user.`status`=1
                and net_staff_user.area='%s' 
                and net_user.begintime<='%s'                            
                ", $item['area'], $time_end);

				$areastaff[$key] = $tmp;
				//  $tooldata[$key] = $tmp;
			}
			$PositionState = M('PositionState');
			$position = $PositionState -> where('status=1') -> select();
			$this -> position = $position;
			foreach ($areastaff as $key => $item) {

				$staff = $item;
				foreach ($position as $key2 => $item2) {
					$count = 0;
					foreach ($staff as $key3 => $item3) {
						if ($item2['name'] == $item3['job']) {
							$count = $count + 1;
							$areastaffdata[$key][$key2]['count'] = $count;
						}
					}
					if ($count == 0) {
						$areastaffdata[$key][$key2]['count'] = 0;
					}
				}
			}
			foreach ($areastaffdata as $key => $item) {
				$areastaffdata2[0][$key]['count'] = $item[0]['count'];
				$areastaffdata2[1][$key]['count'] = $item[1]['count'];
				$areastaffdata2[2][$key]['count'] = $item[2]['count'];
			}

			$this -> areastaffdata = $areastaffdata2;
//			$this -> ajaxReturn($areastaffdata);

			//---------------Vacation done or not done -----------------		
			$vacationdoneornot = array();
			foreach ($userarea as $key => $item) {		
				$tmp = $Model -> query("SELECT 
				net_staff_user.area,
                net_staff_vacation.`status`,
                count(net_staff_vacation.`status`)as count
                FROM
                net_user ,
                net_staff_user ,
                net_staff_vacation
                where net_user.user_id=net_staff_user.user_id and net_user.uname=net_staff_vacation.uname and net_staff_user.area='%s' 
                and net_staff_vacation.start_time>='%s' and net_staff_vacation.time<='%s'
                GROUP BY net_staff_user.area,net_staff_vacation.`status`
                ", $item['area'], $time_begin, $time_end);
				if ($tmp[0]['count'] == '') {
					$tmp[0]['count'] = 0;
				}
				if ($tmp[1]['count'] == '') {
					$tmp[1]['count'] = 0;
				}
				$vacationdoneornot[$key] = $tmp;
			}
			$this -> vacationdoneornot = $vacationdoneornot;

			//-----------------------------Dimission done or not done ---------------------------
			$dimissiondoneornot = array();
			foreach ($userarea as $key => $item) {
				$tmp = $Model -> query("SELECT 
                net_staff_user.area,
                net_dimission.`status`,
                count(net_dimission.`status`)as count
                FROM
                net_user ,
                net_staff_user ,
                net_dimission
                where net_user.user_id=net_staff_user.user_id and net_user.uname=net_dimission.uname and net_staff_user.area='%s' 
                and net_dimission.time>='%s' and net_dimission.time<='%s'
                GROUP BY net_staff_user.area,net_dimission.`status`
                ", $item['area'], $time_begin, $time_end);
				if ($tmp[0]['count'] == '') {
					$tmp[0]['count'] = 0;
				}
				if ($tmp[1]['count'] == '') {
					$tmp[1]['count'] = 0;
				}
				$dimissiondoneornot[$key] = $tmp;
			}
			$this -> dimissiondoneornot = $dimissiondoneornot;
			//-----------------------------Apply done or not done ---------------------------
			$applydoneornot = array();
			foreach ($userarea as $key => $item) {
				$tmp = $Model -> query("SELECT 
                net_home_user.area,
                net_apply_home.a_status,
                COUNT(net_apply_home.a_status)AS count
                FROM
                net_home_user ,
                net_apply_home
                where net_apply_home.home_id=net_home_user.id and net_home_user.area='%s' 
                and net_apply_home.time>='%s' and net_apply_home.time<='%s'
                GROUP BY net_home_user.area,net_apply_home.a_status
                ", $item['area'], $time_begin, $time_end);
				if ($tmp[0]['count'] == '') {
					$tmp[0]['count'] = 0;
				}
				if ($tmp[1]['count'] == '') {
					$tmp[1]['count'] = 0;
				}
				$applydoneornot[$key] = $tmp;
			}
			$this -> applydoneornot = $applydoneornot;
			//-----------------------------Register  ---------------------------
			$RegisterSelect = M('RegisterSelect');
			$registerarea = $RegisterSelect -> select();
			$this -> assign('registerarea', $registerarea);

			$Register = M('StaffRegister');

			$map = array();
			$map['time'] = array('between', array($time_begin, $time_end));
			$tmp = $Register -> where($map) -> field('time') -> select();
			$timex = array();
			foreach ($tmp as $key => $item) {
				$str = date('Y-m-d', strtotime($item['time']));
				array_push($timex, $str);
			}
			$timex = array_unique($timex);
			$this -> areax = $timex;

			//-----------------------------长安校区
			$registercount = array();
			$registerdata = array();
			foreach ($registerarea as $key => $item) {			
				$tmp = $Model -> query("SELECT                
                count(net_staff_register.state)as count
                FROM
                net_staff_register ,
                net_staff_user ,
                net_user
                where net_user.user_id=net_staff_user.user_id and net_user.uname=net_staff_register.uname and net_staff_user.area='长安校区' 
                and net_staff_register.state='%s' and net_staff_register.time>='%s' and net_staff_register.time<='%s' 
                GROUP BY net_staff_register.state
                ", $item['name'], $time_begin, $time_end);
				$registercount[$key] = $tmp[0]['count'];				
			}
			// $this->ajaxReturn($registercount);
			$this -> registercount = $registercount;	
			//-----------------------------太白校区
			$registercount2 = array();
			$registerdata = array();
			foreach ($registerarea as $key => $item) {
				$tmp = $Model -> query("SELECT                
                count(net_staff_register.state)as count
                FROM
                net_staff_register ,
                net_staff_user ,
                net_user
                where net_user.user_id=net_staff_user.user_id and net_user.uname=net_staff_register.uname and net_staff_user.area='太白校区' 
                and net_staff_register.state='%s' and net_staff_register.time>='%s' and net_staff_register.time<='%s' 
                GROUP BY net_staff_register.state
                ", $item['name'], $time_begin, $time_end);
				$registercount2[$key] = $tmp[0]['count'];			
			}
			// $this->ajaxReturn($registercount);
			$this -> registercount2 = $registercount2;

			//-----------------------------桃园校区
			$registercount3 = array();
			$registerdata3 = array();
			foreach ($registerarea as $key => $item) {				
				$tmp = $Model -> query("SELECT                
                count(net_staff_register.state)as count
                FROM
                net_staff_register ,
                net_staff_user ,
                net_user
                where net_user.user_id=net_staff_user.user_id and net_user.uname=net_staff_register.uname and net_staff_user.area='桃园校区' 
                and net_staff_register.state='%s' and net_staff_register.time>='%s' and net_staff_register.time<='%s' 
                GROUP BY net_staff_register.state
                ", $item['name'], $time_begin, $time_end);
				$registercount3[$key] = $tmp[0]['count'];				
			}
			$this -> registercount3 = $registercount3;
			//   $this->registerdata3 = $registerdata3;
			$this -> display();
		}
	}

	public function areareport() {
		if (I('post.begin_time') == '' || I('post.end_time') == '' || I('post.area') == '') {
			echo "<h1>" . I('post.begin_time') . I('post.end_time') . "数据不正确</h1>";
		} else {
			$begin_time = I('post.begin_time');
			$end_time = I('post.end_time');
			$this -> begin_time = $begin_time;
			$this -> end_time = $end_time;
			$area = I('post.area');
			$this -> area = $area;

			$PositionState = M('PositionState');
			$position = $PositionState -> where('status=1') -> select();
			$this -> assign('positionarea', $position);

			$Staff = D('Admin/StaffUserView');
			$Model = new \Think\Model();

			$time_begin = date('Y-m-d 00:00:00', strtotime($begin_time));
			$time_end = date('Y-m-d 23:59:59', strtotime($end_time));

			//---------------以岗位为组，获取每组人数------------------
			$areacount = array();
			$map['begintime'] = array('elt', $time_end);
			$map['status'] = 1;
			$map['area'] = $area;
			$map['level'] = 3;
			$tmpareastaff = $Staff -> where($map) -> select();
			foreach ($position as $key => $item) {
				$count = 0;
				foreach ($tmpareastaff as $key2 => $item2) {
					if ($item2['job'] == $item['name']) {
						$count = $count + 1;
						$areacount[$key] = $count;
					}
				}
				if ($count == 0) {
					$areacount[$key] = $count;
				}
			}
			$this -> areacount = $areacount;
			//-----------按时间统计个岗位人数-----------------
			$areastaff = array();

			$tmpareastaff1 = $Model -> query("SELECT 
                net_staff_user.id,
                net_staff_user.user_id,
                net_staff_user.area,
                net_staff_user.job
                FROM
                net_user ,
                net_staff_user
                WHERE net_user.user_id=net_staff_user.user_id AND net_user.`status`=1
                and net_staff_user.area='%s' 
                and net_user.begintime<='%s'                            
                ", $area, $time_begin);
			$tmpareastaff2 = $Model -> query("SELECT 
                net_staff_user.id,
                net_staff_user.user_id,
                net_staff_user.area,
                net_staff_user.job
                FROM
                net_user ,
                net_staff_user
                WHERE net_user.user_id=net_staff_user.user_id AND net_user.`status`=1
                and net_staff_user.area='%s' 
                and net_user.begintime>='%s' and net_user.begintime<='%s'                            
                ", $area, $time_begin, $time_end);
            $tmpareastaff3 = $Model -> query("SELECT 
                net_dimission.id,
                net_staff_user.user_id,
                net_staff_user.area,
                net_staff_user.job
                FROM
                net_dimission ,
                net_dimission_state,
                net_staff_user
                WHERE net_dimission.user_id=net_staff_user.user_id and net_dimission.id=net_dimission_state.dimission_id
                and net_staff_user.area='%s' 
                and net_dimission_state.dimission_time>='%s' and net_dimission_state.dimission_time<='%s'                            
                ", $area, $time_begin, $time_end);

		//		$this->ajaxReturn($tmpareastaff3);
			foreach ($position as $key => $item) {
				$count = 0;
				foreach ($tmpareastaff1 as $key2 => $item2) {
					if ($item['name'] == $item2['job']) {
						$count = $count + 1;
						$areastaff1[$key]['count'] = $count;
					}
				}
				if ($count == 0) {
					$areastaff1[$key]['count'] = 0;
				}
			}
			foreach ($position as $key => $item) {
				$count = 0;
				foreach ($tmpareastaff2 as $key2 => $item2) {
					if ($item['name'] == $item2['job']) {
						$count = $count + 1;
						$areastaff2[$key]['count'] = $count;
					}
				}
				if ($count == 0) {
					$areastaff2[$key]['count'] = 0;
				}
			}
			foreach ($position as $key => $item) {
				$count = 0;
				foreach ($tmpareastaff3 as $key2 => $item2) {
					if ($item['name'] == $item2['job']) {
						$count = $count + 1;
						$areastaff3[$key]['count'] = $count;
					}
				}
				if ($count == 0) {
					$areastaff3[$key]['count'] = 0;
				}
			}
			
			$areastaff = array();
			$areastaff[0] = $areastaff1;
			$areastaff[1] = $areastaff2;
			$areastaff[2] = $areastaff3;
			

			foreach ($areastaffdata as $key => $item) {
				$areastaffdata2[0][$key]['count'] = $item[0]['count'];
				$areastaffdata2[1][$key]['count'] = $item[1]['count'];
				$areastaffdata2[2][$key]['count'] = $item[2]['count'];
			}

			$this -> areastaffdata = $areastaff;
			//	$this->ajaxReturn($areastaff);

			$vacationstaff = D("Admin/StaffUserView") -> where("area='%s'", $area) -> group('uname') -> field('uname') -> select();
			//	$this->ajaxReturn($vacationstaff);
			$this -> vacationstaff = $vacationstaff;
			//-------------------------vacation--------------------
			$vacationcount = array();
			$vacationdata = array();
			foreach ($vacationstaff as $key => $item) {
				$tmp = $Model -> query("SELECT 
                net_staff_vacation.uname,              
                count(net_staff_vacation.uname)as count
                FROM
                net_user ,
                net_staff_user ,
                net_staff_vacation
                where net_user.user_id=net_staff_user.user_id and net_user.uname=net_staff_vacation.uname 
                  and net_staff_user.area='%s' 
                  and net_staff_vacation.time>='%s' and net_staff_vacation.time<='%s' 
                  and net_staff_vacation.uname='%s'
                GROUP BY net_staff_user.area,net_staff_vacation.uname
                ", $area, $time_begin, $time_end, $item['uname']);
				if ($tmp[0]['count'] != '') {
					$vacationcount[$key]['uname'] = $tmp[0]['uname'];
					$vacationdata[$key]['uname'] = $tmp[0]['uname'];
					$vacationdata[$key]['count'] = $tmp[0]['count'];
				}
			}
			$this -> vacationcount = $vacationcount;
			// $this->ajaxReturn($vacationcount);
			$this -> vacationdata = $vacationdata;
			//---------------考勤----------------------
			$RegisterSelect = M('RegisterSelect');
			$registerarea = $RegisterSelect -> select();
			$this -> assign('registerarea', $registerarea);

			$registerareax = array();
			foreach ($registerarea as $key => $item) {
				$registerareax[$key] = $item['name'];
			}
			$this -> assign('registerareax', $registerareax);
			//	$this->ajaxReturn($areax);
			$registerdata = array();
			$tmpregister = $Model -> query("SELECT 
                net_staff_register.id,
                net_staff_register.state,
                
                net_staff_register.uname,
                net_staff_register.state
                FROM
                net_staff_register ,
                net_staff_user ,
                net_user

                where net_user.user_id=net_staff_user.user_id and net_user.uname=net_staff_register.uname 
                  and net_staff_user.area='%s' 
                  and net_staff_register.time>='%s' and net_staff_register.time<='%s' 
                ", $area, $time_begin, $time_end);

			$map['begintime'] = array('elt', $time_end);
			$map['status'] = 1;
			$map['area'] = $area;
			$Staffname = D('Admin/StaffUserView') -> field('uname') -> where($map) -> select();
			$this -> staffname = $Staffname;
			foreach ($Staffname as $key => $item) {
				$count = 0;
				$registerdata[$key]['uname'] = $item['uname'];
				foreach ($tmpregister as $key2 => $item2) {
					if ($item2['uname'] == $item['uname']) {
						$count = $count + 1;
						$registerdata[$key]['count'] = $count;
					}
				}
				if ($count == 0) {
					$registerdata[$key]['count'] = $count;
				}

			}
			$this -> registerdata = $registerdata;
			//$this -> ajaxReturn($registerdata);
			$registerdata2 = array();
			foreach ($Staffname as $key => $item) {
				foreach ($registerarea as $key2 => $item2) {
					$count = 0;

					foreach ($tmpregister as $key3 => $item3) {
						if (($item['uname'] == $item3['uname']) && ($item2['name'] == $item3['state'])) {
							$count = $count + 1;
							$registerdata2[$key][$key2]['count'] = $count;
						}
					}
					if ($count == 0) {
						$registerdata2[$key][$key2]['count'] = $count;
					}
				}

			}

			foreach ($registerdata2 as $key => $item) {
				$register[0][$key]['count'] = $item[0]['count'];
				$register[1][$key]['count'] = $item[1]['count'];
				$register[2][$key]['count'] = $item[2]['count'];
			}
			$this -> registerdata2 = $register;
			//			$this -> ajaxReturn($register);

			//////////////////////////////////////////////////////

			$this -> display();
		}
	}

	public function topdf() {

	}

	public function _empty($name) {
		echo "Not Found!";
	}

}
