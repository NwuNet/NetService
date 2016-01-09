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
function sizeFormat($bytes) {
	$kb = 1024;
	$mb = $kb * 1024;
	$gb = $mb * 1024;
	$tb = $gb * 1024;
	if (($bytes >= 0) && ($bytes < $kb)) {
		return $bytes . ' B';
	} elseif (($bytes >= $kb) && ($bytes < $mb)) {
		return ceil($bytes / $kb) . ' KB';
	} elseif (($bytes >= $mb) && ($bytes < $gb)) {
		return ceil($bytes / $mb) . ' MB';
	} elseif (($bytes >= $gb) && ($bytes < $tb)) {
		return ceil($bytes / $gb) . ' GB';
	} elseif ($bytes >= $tb) {
		return ceil($bytes / $tb) . ' TB';
	} else {
		return $bytes . ' B';
	}
}
