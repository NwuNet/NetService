<?php
namespace Boss\Controller;
use Think\Controller;
class DataAssetController extends BaseController {
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
			//--------------------------area-----------------------
			$Area = M('UserArea');
			$userarea = $Area -> select();
			$this -> assign('userarea', $userarea);

			$Service = M('ServiceCard');
			$Tool = M('AssetTool');
			$Model = new \Think\Model();

			//-------------------------graph bar line--------------------
			$map = array();
			$map['start'] = array('between', array(I('post.begin_time'), I('post.end_time')));
			$tmp = $Tool -> where($map) -> field('start') -> select();
			$timex = array();
			foreach ($tmp as $key => $item) {
				$str = date('Y-m-d', strtotime($item['start']));
				array_push($timex, $str);
			}
			//            $timey = array_count_values($timex);
			$timex = array_unique($timex);
			$this -> areax = $timex;
			//            $this->areay = $timey;
			//-------------------------graph area--------------------
			$areacount = array();
			$areadata = array();
			foreach ($userarea as $key => $item) {
				$map['start'] = array('between', array(I('post.begin_time'), I('post.end_time')));
				$map['area'] = $item['area'];
				$tmp = $Tool -> where($map) -> select();
				$areacount[$key] = count($tmp);

				foreach ($timex as $x) {
					$map['start'] = array('like', $x . '%');
					$tmp = $Tool -> where($map) -> select();
					$areadata[$key][$x] = count($tmp);
				}
			}
			$this -> areacount = $areacount;
			$this -> areadata = $areadata;

			//-----------------------------工具 ---------------------------
			$tooldata = array();
			foreach ($userarea as $key => $item) {
				$tmp = $Model -> query("SELECT 
                net_asset_tool.`names`,
                COUNT(net_asset_tool.`names`)as count
                FROM
                net_asset_tool
                where net_asset_tool.area='%s' 
                and net_asset_tool.start>='%s' and net_asset_tool.start<='%s'                  
                GROUP BY net_asset_tool.`names`,net_asset_tool.area
                ", $item['area'], I('post.begin_time'), I('post.end_time'));

				$tooldata[$key] = $tmp;
				//  $tooldata[$key] = $tmp;
			}

			$this -> tooldata = $tooldata;
			//   $this -> ajaxReturn($tooldata);
            //-----------------------------耗材 ---------------------------
			$exhaustdata = array();
			foreach ($userarea as $key => $item) {
				$tmp = $Model -> query("SELECT 
                net_asset_exhaust.`names`,
                sum(net_asset_exhaust.renumber)as count
                FROM
                net_asset_exhaust
                where net_asset_exhaust.area='%s' 
                and net_asset_exhaust.start>='%s' and net_asset_exhaust.start<='%s'                  
                GROUP BY net_asset_exhaust.area,net_asset_exhaust.`names`
                ", $item['area'], I('post.begin_time'), I('post.end_time'));

				$exhaustdata[$key] = $tmp;
				//  $tooldata[$key] = $tmp;
			}
			$this -> exhaustdata = $exhaustdata;
			
			//-----------------------------设备 ---------------------------
			$devicedata = array();
			foreach ($userarea as $key => $item) {
				$tmp = $Model -> query("SELECT 
                net_asset_device.`names`,
                count(net_asset_device.`names`)as count
                FROM
                net_asset_device
                where net_asset_device.area='%s' 
                and net_asset_device.start>='%s' and net_asset_device.start<='%s'                  
                GROUP BY net_asset_device.area,net_asset_device.`names`
                ", $item['area'], I('post.begin_time'), I('post.end_time'));

				$devicedata[$key] = $tmp;
				//  $tooldata[$key] = $tmp;
			}
			$this -> devicedata = $devicedata;
			
			//-----------------------------证照 ---------------------------
			$paperdata = array();
			foreach ($userarea as $key => $item) {
				$tmp = $Model -> query("SELECT 
                net_asset_paper.`name`,
                count(net_asset_paper.`name`)as count
                FROM
                net_asset_paper
                where net_asset_paper.area='%s' 
                and net_asset_paper.time>='%s' and net_asset_paper.time<='%s'                  
                GROUP BY net_asset_paper.area,net_asset_paper.`name`
                ", $item['area'], I('post.begin_time'), I('post.end_time'));

				$paperdata[$key] = $tmp;
				//  $tooldata[$key] = $tmp;
			}
			$this -> paperdata = $paperdata;
			
			//-----------------------------其他 ---------------------------
			$otherdata = array();
			foreach ($userarea as $key => $item) {
				$tmp = $Model -> query("SELECT 
                net_asset_other.`names`,
                count(net_asset_other.`names`)as count
                FROM
                net_asset_other
                where net_asset_other.campus='%s' 
                and net_asset_other.start>='%s' and net_asset_other.start<='%s'                  
                GROUP BY net_asset_other.campus,net_asset_other.`names`
                ", $item['area'], I('post.begin_time'), I('post.end_time'));

				$otherdata[$key] = $tmp;
				//  $tooldata[$key] = $tmp;
			}
			$this -> otherdata = $otherdata;
						
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
			$map['start'] = array('between', array($begin_time, $end_time));
			$map['area'] = $area;

			$Service = M('ServiceCard');
			$Repair = D('ServiceRepairView');
			$Model = new \Think\Model();

			$tmp = $Service -> where($map) -> field('start') -> select();
			$areacount = count($tmp);
			$timex = array();
			foreach ($tmp as $key => $item) {
				$str = date('Y-m-d', strtotime($item['start']));
				array_push($timex, $str);
			}
			$timex = array_unique($timex);
			$this -> areax = $timex;

			$areadata = array();
			foreach ($timex as $x) {
				$map['start'] = array('like', $x . '%');
				$tmp = $Service -> where($map) -> select();
				$areadata[$x] = count($tmp);
			}
			$this -> areacount = $areacount;
			$this -> areadata = $areadata;
			$this -> area = $area;
            			
			//-----------------------------Tool ---------------------------
			//-----------------------------工具 ---------------------------
			//$tooldata = array();
			$Toolstate = M('ToolState');
			$toolstate = $Toolstate->where('status=1')->select();
			
            foreach (array_reverse($toolstate) as $key => $item) {
            	$tmp = array();		
				$tmp = $Model -> query("SELECT 
				net_asset_content.id,
                net_asset_content.asset_id,
                net_asset_tool.`names`,
                net_asset_content.state,                
                net_asset_content.time,                
                net_asset_tool.`start`
                FROM
                net_asset_content ,
                net_asset_tool
                WHERE net_asset_tool.id=net_asset_content.asset_id AND net_asset_content.class=1  
                AND net_asset_tool.area='%s' AND net_asset_content.state='%s'
                and net_asset_tool.start>='%s' and net_asset_tool.start<='%s'           
                ", $area,$item['name'], I('post.begin_time'), I('post.end_time'));
				
				//$tooldata[$key][]  = $tmp[];
				//$this -> ajaxReturn($tmp);
				  $tooldata[$key] = $tmp;
			}   //      $this -> ajaxReturn($tooldata);
			//------------------Test--------------
			$Model = new \Think\Model();
			$tmp = array();
			$tmp = $Model -> query(
			"SELECT 
				net_asset_content.id,
                net_asset_content.asset_id,
                net_asset_tool.`names`,
                net_asset_content.state,                
                net_asset_content.time,                
                net_asset_tool.`start`
                FROM
                net_asset_content ,
                net_asset_tool
                WHERE net_asset_tool.id=net_asset_content.asset_id AND net_asset_content.class=1  
                AND net_asset_tool.area='%s' 
                and net_asset_tool.start>='%s' and net_asset_tool.start<='%s'                          
                ", $area, I('post.begin_time'), I('post.end_time'));
			
				$this -> ajaxReturn($tmp);
				
			//------------------Test--------------
			
			
            foreach ($tooldata as $key => $item) {
            	$len=count($item);
				for($i=0;$i<=$len;$i++){
					$assst_id = $item[$i]['assst_id'];
					
					if($tooldata[$key+1]['assst_id']==$assst_id && $key+1<=4){
							
						
					}
					
					//$tooldata[$key][$i]  = $tmp[$i];
					//$i=$i+1;
				}
            	$assst_id = $item[]
            	foreach ($tooldata as $key2 => $item2) {
            		if($item2['state']==$item['name'])
            		
            		;
            		
            		
            	
			    }
			}
			$this -> tooldata = $tooldata;
			//   $this -> ajaxReturn($tooldata);
			
			
			/*

			//-----------------------------故障大类------------------------------------
			$map['time'] = array('between', array($begin_time, $end_time));
			$map['area'] = $area;
			$breakinfo = $Repair -> field('breakinfo,count(breakinfo) as count') -> group('breakinfo') -> where($map) -> select();
			$this -> breakinfo = $breakinfo;
			$staffdo = $Repair -> field('operator,count(ServiceRepair.operator) as count') -> group('operator') -> where($map) -> select();
			$this -> staffdo = $staffdo;

			//------------------------------楼宇故障------------------------------------
			$tmp = $Service -> where($map) -> field('dormitory') -> select();
			$addressx = array();
			foreach ($tmp as $key => $item) {
				$str = explode('-', $item['dormitory']);
				trace($str);
				array_push($addressx, $str[0]);
			}
			$addressx = array_unique($addressx);
			$addressdata = array();
			foreach ($addressx as $x) {
				$map['dormitory'] = array('like', $x . '%');
				$tmp = $Service -> where($map) -> select();
				$addressdata[$x]['count'] = count($tmp);
				$addressdata[$x]['x'] = $x . '号楼';
			}
			//            $this->addressx = $addressx;
			$this -> addressdata = $addressdata;
			//            trace($addressdata);
			//            trace($addressx);
*/
			$this -> display();
		}
	}

	public function topdf() {

	}

	public function _empty($name) {
		echo "Not Found!";
	}

}
