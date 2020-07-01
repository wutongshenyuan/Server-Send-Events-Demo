<?php
date_default_timezone_set("Asia/Shanghai");
ini_set('max_execution_time', '0');// 不限制执行时间，否则会断掉重连
header('Content-Type: text/event-stream;charset=UTF-8');
header('X-Accel-Buffering: no');// 关闭加速缓冲 针对nginx
header('Cache-Control: no-cache');
header('Connection: keep-alive');

$i=0;
while(true){
	$i++;
	// 数据库处理逻辑，如果有新消息就发送，没有新消息就发注释,维持连接
	echo getMsg('test'.$i);
	echo getNote('这是注释'.$i);//注释在浏览器端是不显示的
	if(ob_get_level()>0){
		ob_flush(); //刷新php缓冲区
	}
	flush(); //是刷新WebServer缓冲区针对apache
	
	if(connection_aborted()){
		exit;
	}
	
	sleep(1);
}
// 获取一条消息
function getMsg($data,$retry=0,$id='',$event=''){
	$msg = [
		'data'=>$data,
	];
	if($retry){
		$msg['retry'] = $retry;
	}
	 if($id){
		 $msg['id'] = $id;
	 }
	 if($event){
		 $msg['event'] = $event;
	 }
	 $str = '';
	 foreach($msg as $k=>$v){
		 $str .= $k.':'.$v."\n";
	 }
	 return $str .= "\n\n";
}
// 获取一条注释
function getNote($content){
	return ':'.$content."\n\n";
}