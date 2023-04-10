<?php
include_once('../sys/config.php');

if (!empty($_GET['passwd']) && !empty($_GET['o_passwd'])) {
	$clean_passwd = clean_input($_GET['passwd']);
	$clean_opasswd = clean_input($_GET['o_passwd']);
	// 校验旧密码
	$query_p = "select * from users where user_pass=SHA('$clean_opasswd')";
	$res=mysqli_query($conn, $query_p);
    if (mysqli_num_rows($res) != 1) {
        die("update error!");
    }
	$query = "UPDATE users SET user_pass = SHA('$clean_passwd') WHERE user_id = '{$_SESSION['user_id']}'";
	mysql_query($query, $conn) or die("update error!");
	mysql_close($conn);
	header('Location: edit.php');
}
else {
	not_find($_SERVER['PHP_SELF']);
}
?>