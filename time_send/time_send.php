<?php
require "config.php";
function check_file_time($fp, $time)
{
	$ret = false;
	if(!$fp){
		return false;
	}
	$file_status = fstat($fp);
	if((time() - $file_status['mtime']) < $time){
		$ret = true;
	}
	return $ret;
}
foreach($config as $entity){
	//check file is need to send
	//if(time() - filemtime($entity['path']) > 60)continue;
	$fp = fopen($entity['path'], 'r');
	if(!$fp){
		echo 'file key:' . $entity['key'] . 'error file:'.$entity['path'].PHP_EOL;
		continue;
	}
	$count = 0;
	$max_count = 5;
	while(!check_file_time($fp, 10)){
		$count++;
		if($count > $max_count)break;
		sleep(1);
	}
	if($count > $max_count){
		fclose($fp);
		continue;
	}
	$file_content = fread($fp, $max_read);
	$file_content = iconv('GBK','UTF-8',$file_content);
	fclose($fp);
	//echo "file time:" . filemtime($entity['path']). PHP_EOL;
	$file_content = str_replace("\r\n", "<br/>", $file_content);
	//echo $file_content.PHP_EOL;
	$entity['text'] = $file_content;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $send_url);
	curl_setopt($curl, CURLOPT_TIMEOUT, $connect_timeout);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_HEADER, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $entity);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	$web_return = curl_exec($curl);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);
	file_put_contents($log_file_prefix.date('Y-m-d').'.txt', "send time:[".date('Y-m-d H:i:s')."] key:[".$entity['key']."],status:$status\r\n", FILE_APPEND);
}