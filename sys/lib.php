<?php

date_default_timezone_set('UTC');

// 这里对所有接收的超全局变量进行sec处理，sec定义在下面
if( !get_magic_quotes_gpc() ) {
	$_GET = sec ( $_GET );
	$_POST = sec ( $_POST );
	$_COOKIE = sec ( $_COOKIE ); 
}
$_SERVER = sec ( $_SERVER );
$_FILES = sec ( $_FILES );

// sec这里是一个引用，取的是$array的地址
// 当一个地址被传入sec后，判断
// 如果是一个数组，则按键值对方式遍历，将每个键的值再传入sec
// 如果是字符串，则对' " \ null进行转义
// 如果是数字或数字字符串，则进行取整和进制转换，这里不带参数默认转为10进制，结果为字符串
// 最终的结果是，输入的数组中的所有键值对中的值，都会变成经过addslashes的字符串

// php中的引用，直接操作地址上的值，传入函数时将指针压入栈
// 不带引用时，传入函数时，会在堆上创建新的变量
function sec( &$array ) {
	if ( is_array( $array ) ) {
		foreach ( $array as $k => $v ) {
			$array [$k] = sec ( $v );
		}
	} else if ( is_string( $array ) ) {
		$array = addslashes( $array );
	} else if ( is_numeric( $array ) ) {
		$array = intval( $array );
	}
	return $array;
}

// str_ireplace会将大小写转换为小写
function sqlwaf( $str ) {
	$str = str_ireplace( "and", "sqlwaf", $str );
	$str = str_ireplace( "or", "sqlwaf", $str );
	$str = str_ireplace( "from", "sqlwaf", $str );
	$str = str_ireplace( "execute", "sqlwaf", $str );
	$str = str_ireplace( "update", "sqlwaf", $str );
	$str = str_ireplace( "count", "sqlwaf", $str );
	$str = str_ireplace( "chr", "sqlwaf", $str );
	$str = str_ireplace( "mid", "sqlwaf", $str );
	$str = str_ireplace( "char", "sqlwaf", $str );
	$str = str_ireplace( "union", "sqlwaf", $str );
	$str = str_ireplace( "select", "sqlwaf", $str );
	$str = str_ireplace( "delete", "sqlwaf", $str );
	$str = str_ireplace( "insert", "sqlwaf", $str );
	$str = str_ireplace( "limit", "sqlwaf", $str );
	$str = str_ireplace( "concat", "sqlwaf", $str );
	$str = str_ireplace( "\\", "\\\\", $str );
	$str = str_ireplace( "&&", "sqlwaf", $str );
	$str = str_ireplace( "||", "sqlwaf", $str );
	$str = str_ireplace( "&", "sqlwaf", $str );
	$str = str_ireplace( "|", "sqlwaf", $str );
	$str = str_ireplace( "'", "sqlwaf", $str );
	$str = str_ireplace( "%", "\%", $str );
	$str = str_ireplace( "_", "\_", $str );
	return $str;
}

function xsswaf($str){
	$str = str_ireplace("javascript","xsswaf",$str);
	$str = str_ireplace("script","xsswaf",$str);
	$str = str_ireplace("on","xsswaf",$str);
	$str = str_ireplace("click","xsswaf",$str);
	$str = str_ireplace("error","xsswaf",$str);
	$str = str_ireplace("load","xsswaf",$str);
	$str = str_ireplace("mouseover","xsswaf",$str);
	$str = str_ireplace("svg","xsswaf",$str);
	$str = str_ireplace("img","xsswaf",$str);
	$str = str_ireplace("src","xsswaf",$str);
	$str = str_ireplace("<","xsswaf",$str);
	$str = str_ireplace(">","xsswaf",$str);
	$str = str_ireplace("'","xsswaf",$str);
	$str = str_ireplace("\"","xsswaf",$str);
	$str = str_ireplace("document","xsswaf",$str);
	$str = str_ireplace("cookie","xsswaf",$str);
	$str = str_ireplace("alert","xsswaf",$str);
	$str = htmlspecialchars($str);
}

function get_client_ip(){
	if ($_SERVER["HTTP_CLIENT_IP"] && strcasecmp($_SERVER["HTTP_CLIENT_IP"], "unknown")){
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}else if ($_SERVER["HTTP_X_FORWARDED_FOR"] && strcasecmp($_SERVER["HTTP_X_FORWARDED_FOR"], "unknown")){
		$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}else if ($_SERVER["REMOTE_ADDR"] && strcasecmp($_SERVER["REMOTE_ADDR"], "unknown")){
		$ip = $_SERVER["REMOTE_ADDR"];
	}else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){
		$ip = $_SERVER['REMOTE_ADDR'];
	}else{
		$ip = "unknown";
	}
	return($ip);
}

function clean_input( $dirty ) {
	return mysql_real_escape_string( stripslashes( $dirty ) );
}

function is_pic( $file_name ) {
	$extend =explode( "." , $file_name );
	$va=count( $extend )-1;
	if ( $extend[$va]=='jpg' || $extend[$va]=='jpeg' || $extend[$va]=='png' ) {
		return 1;
	}
	else
		return 0;
}

// 文件名预处理
function clean_img($file_name){
	while(substr($file_name,-1)=='.'){
		$file_name = deldot($file_name);//删除文件名末尾的点
	}
	$file_ext = strrchr($file_name, '.');
	$file_name = str_ireplace($file_ext,'',$file_name);//删除后缀
	$file_ext = strtolower($file_ext); //后缀转换为小写
	$file_ext = str_ireplace('::$DATA', '', $file_ext);//后缀去除字符串::$DATA
	$file_ext = trim($file_ext); //后缀首尾去空
	return $file_name.$file_ext;
	}

// 注册用户名校验，只允许数字和字母 
function user_name_check($username){
	$namelen = strlen($username);
	for($i=0;$i<$namelen;$i++){
		switch (ord($username[$i])){
			case ord($username[$i])>=48 && ord($username[$i])<=57:
				continue;
			case ord($username[$i])>=65 && ord($username[$i])<=90:
				continue;
			case ord($username[$i])>=97 && ord($username[$i])<=122:
				continue;
			default:
				return false;
		}
	}
	return true;
}

function not_find( $page ) {
	echo "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1>
		<p>The requested URL ".$page." was not found on this server.</p></body></html>";
}
?>
