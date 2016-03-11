<?php
/**
 * 公共函数
 * */

/**
 * 数据签名认证
 * @param array  $data 被认证的数据
 * @return true/false
 * */
function data_auth_sign($data) {
	//数据类型检测
	if (!is_array($data)) {
		$data = (array)$data;
	}
	ksort($data);
	//排序
	$code = http_build_query($data);
	//url编码并生成query字符串
	$sign = sha1($code);
	//生成签名
	return $sign;
}

/**
 * 遍历删除目录和目录下所有文件
 * @param string $dir 路径
 * @return bool
 */
function del_dir($dir) {
	if (!is_dir($dir)) {
		return false;
	}
	$handle = opendir($dir);
	while (($file = readdir($handle)) !== false) {
		if ($file != "." && $file != "..") {
			is_dir("$dir/$file") ? del_dir("$dir/$file") : @unlink("$dir/$file");
		}
	}
	if (readdir($handle) == false) {
		closedir($handle);
		@rmdir($dir);
	}
}

/**
 * 获取文件或文件大小
 * @param string $directoty 路径
 * @return int
 */
function dir_size($directoty) {
	$dir_size = 0;
	if ($dir_handle = @opendir($directoty)) {
		while ($filename = readdir($dir_handle)) {
			$subFile = $directoty . DIRECTORY_SEPARATOR . $filename;
			if ($filename == '.' || $filename == '..') {
				continue;
			} elseif (is_dir($subFile)) {
				$dir_size += dir_size($subFile);
			} elseif (is_file($subFile)) {
				$dir_size += filesize($subFile);
			}
		}
		closedir($dir_handle);
	}
	return ($dir_size);
}

/**
 * 文件大小格式转换
 * @param int bytes
 * @return string
 * */
function sizeFormat($bytes,$type='size') {
	$kb = 1024;
	$mb = $kb * 1024;
	$gb = $mb * 1024;
	$tb = $gb * 1024;
	if (($bytes > 0) && ($bytes < $kb)) {
		$format = array('size'=>$bytes . ' B', 'level'=>1);
	} elseif (($bytes >= $kb) && ($bytes < $mb)) {
		$format = array('size'=>ceil($bytes / $kb) . ' KB', 'level'=>2);
	} elseif (($bytes >= $mb) && ($bytes < $gb)) {
		$format = array('size'=>ceil($bytes / $mb) . ' MB', 'level'=>3);
	} elseif (($bytes >= $gb) && ($bytes < $tb)) {
		$format = array('size'=>ceil($bytes / $gb) . ' GB', 'level'=>4);
	} elseif ($bytes >= $tb) {
		$format = array('size'=>ceil($bytes / $tb) . ' TB', 'level'=>5);
	} else {
		$format = array('size'=>'0 B', 'level'=>0);
	}
	if($type == 'size'){
		return $format['size'];
	}elseif($type == 'level'){
		return $format['level'];
	}
}

/**
 * 文件夹下文件列表
 * @param string $dir,string $root
 * @return array filelist
 * */
function listFile($dir, $ROOT){
    if ($handle = opendir($ROOT.$dir)){
        $output = array();
        $dir = $dir[strlen($dir) - 1] == '/' ? $dir:$dir.'/';
        while (false !== ($item = readdir($handle))){
            if ($item != "." && $item != ".."){
                $output[] = getFileInfo($dir.$item, $ROOT);
            }
        }
        closedir($handle);
        return $output;
    }else{
        return false;
    }
}

/**
 * 根据路径获取文件名
 * @param string $path 
 * @return string $filename 
 * */
function getFileName($path){
    $index = strrpos($path, '/');
    if($index || $index === 0) {
        return substr($path, $index + 1);
    } else {
        return $path;
    }
}

/**
 * 获取文件信息
 * @param string $path,string $root
 * @return array $info
 * */
function getFileInfo($path, $ROOT){
    $filepath = $ROOT.$path;
    $stat = stat($filepath);
    $info = array(
//        'hash' => substr(md5($filepath),8,16),
        'path' => is_dir($filepath) && substr($path, strlen($path) - 1) != '/' ? $path.'/':$path,
        'name' => getFileName($path),
        'isdir' => is_dir($filepath),
        'type' => filetype($filepath),
        'read' => is_readable($filepath),
        'write' => is_writable($filepath),
        'time' => filemtime($filepath),
//        'dev' => $stat['dev'],
//        'ino' => $stat['ino'],
        'mode' => decoct($stat['mode']),
//        'nlink' => $stat['nlink'],
//        'uid' => $stat['uid'],
//        'gid' => $stat['gid'],
//        'rdev' => $stat['rdev'],
        'size' => $stat['size'],
//        'atime' => $stat['atime'],
//        'mtime' => $stat['mtime'],
//        'ctime' => $stat['ctime'],
//        'blksize' => $stat['blksize'],
//        'blocks' => $stat['blocks']
    );
    return $info;
}

/**
 * 将消息编码为json格式 
 * @param boolean $state,string $message, array $data
 * @return json $output
 * */
function getJson($state, $message, $data = null){
    $output = array();
    $output['state'] = $state;
    $output['message'] = $message;
    if($data) $output['data'] = $data;
    return json_encode($output);
}
/**
 * 将消息编码为数组
 * @param boolean $state,string $message, array $data
 * @return json $output
 * */
function getArray($state, $message, $data = null){
    $output = array();
    $output['state'] = $state;
    $output['message'] = $message;
    if($data) $output['data'] = $data;
    return $output;
}

/**
 * 按键值排序
 * @param array $arr,string $key,string $type
 * @return array $new_array
 * */
function array_sort($arr,$keys,$type='asc'){
    $keysvalue = $new_array = array();
    foreach ($arr as $k=>$v){
        $keysvalue[$k] = $v[$keys];
    }
    if($type == 'asc'){
        asort($keysvalue);
    }else{
        arsort($keysvalue);
    }
    reset($keysvalue);
    foreach ($keysvalue as $k=>$v){
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}

/**
 * @param string $dir
 * @return true/false
 * 遍历删除目录和目录下所有文件
 */
function removeDir($dirName) {
    if(! is_dir($dirName)) return false;
    $handle = @opendir($dirName);
    while(($file = @readdir($handle)) !== false) {
        if($file != '.' && $file != '..') {
            $dir = $dirName . '/' . $file;
            is_dir($dir) ? removeDir($dir) : @unlink($dir);
        }
    }
    closedir($handle);
    return rmdir($dirName) ;
}

/**
 * @param string $path,string $name
 * 下载文件
 * */
function downloadFile($path, $name)
{
    ob_start();
    $filename=$path;
    $name = $name ? $name:date("Ymd-H:i:m");
    header( "Content-type: application/octet-stream ");
    header( "Accept-Ranges: bytes ");
    header( "Content-Disposition: attachment; filename= $name" );
    $size=readfile($filename);
    header( "Accept-Length: " .$size);
}
/**
 * @param string $str
 * 获取第一个字符
 * */
function getFirstChar($s0){
    $firstchar_ord=ord(strtoupper($s0{0}));
    if (($firstchar_ord>=65 and $firstchar_ord<=91)or($firstchar_ord>=48 and $firstchar_ord<=57)) return $s0{0};
    $s=iconv("UTF-8","gb2312", $s0);
    $asc=ord($s{0})*256+ord($s{1})-65536;
    if($asc>=-20319 and $asc<=-20284)return "A";
    if($asc>=-20283 and $asc<=-19776)return "B";
    if($asc>=-19775 and $asc<=-19219)return "C";
    if($asc>=-19218 and $asc<=-18711)return "D";
    if($asc>=-18710 and $asc<=-18527)return "E";
    if($asc>=-18526 and $asc<=-18240)return "F";
    if($asc>=-18239 and $asc<=-17923)return "G";
    if($asc>=-17922 and $asc<=-17418)return "H";
    if($asc>=-17417 and $asc<=-16475)return "J";
    if($asc>=-16474 and $asc<=-16213)return "K";
    if($asc>=-16212 and $asc<=-15641)return "L";
    if($asc>=-15640 and $asc<=-15166)return "M";
    if($asc>=-15165 and $asc<=-14923)return "N";
    if($asc>=-14922 and $asc<=-14915)return "O";
    if($asc>=-14914 and $asc<=-14631)return "P";
    if($asc>=-14630 and $asc<=-14150)return "Q";
    if($asc>=-14149 and $asc<=-14091)return "R";
    if($asc>=-14090 and $asc<=-13319)return "S";
    if($asc>=-13318 and $asc<=-12839)return "T";
    if($asc>=-12838 and $asc<=-12557)return "W";
    if($asc>=-12556 and $asc<=-11848)return "X";
    if($asc>=-11847 and $asc<=-11056)return "Y";
    if($asc>=-11055 and $asc<=-10247)return "Z";
    return null;
}
