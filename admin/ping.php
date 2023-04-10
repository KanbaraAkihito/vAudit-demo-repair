<?php 
include_once('../sys/config.php');

if (isset($_SESSION['admin'])) {
	include_once('../header.php');

	
?>
<div class="span10">
	<div id="content">
		<div class="page-header">
			<h4>Ping</h4>
			<hr>
			<form name="ping" action="" method="post">
				<input type="text" name="target" size="30" class="form-control">
				<input type="submit" value="Ping" name="submit" class="btn btn-primary">
			</form>
			<?php
			// 该脚本包含了config.php,config.php又包含了lib.php
			// 在lib.php中定义了sec函数，对所有接收的超全局变量进行了预处理
			// 但没有对shell命令进行过滤，可以使用 || && | & ；等执行额外指令
			if( isset( $_POST[ 'submit' ] ) ) {
				$target = $_POST[ 'target' ];
				if(preg_match('/^((\d{2}\.)|(1\d{2}\.)|(2[0-4]\d\.)|(25[0-5]\.)|\d\.){3}((1\d{2})|(2[0-4]\d)|(25[0-5])|(\d{2})|(\d))$/',$target)){
					if (stristr(php_uname('s'), 'Windows NT')) { 
						$cmd = 'ping ' . $target;
					} else { 
						$cmd = 'ping -c 3 ' . $target;
					}
					$res = shell_exec( $cmd );
					echo "<br /><pre>$cmd\r\n".iconv('GB2312', 'UTF-8',$res)."</pre>";
				}else{
					die('error');
				}
			}
			?>
		</div>
	</div>
</div>


<a href="manage.php">返回</a>
	<?php 
	require_once('../footer.php');
	}
else {
	not_find($_SERVER['PHP_SELF']);
}
 ?>
