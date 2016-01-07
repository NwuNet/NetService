<?php
/**
 * 公共函数
 * */
 
//---------------------------------数据签名认证-------------------------------------
// in:		array  $data 被认证的数据
// out: 	return string       签名
function data_auth_sign($data) {
    //数据类型检测
    if(!is_array($data)){
        $data = (array)$data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}

