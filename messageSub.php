<?php
include_once('sys/config.php');

if (isset($_POST['submit']) && !empty($_POST['message']) && isset($_SESSION['username'])) {
	// 增加sqlwaf的防护
	$clean_message = sqlwaf(clean_input($_POST['message']));
	$query = "INSERT INTO comment(user_name,comment_text,pub_date) VALUES ('{$_SESSION['username']}','$clean_message',now())";
	mysql_query($query, $conn) or die(mysql_error());
	mysql_close($conn);
	header('Location: message.php');
}
else {
	echo "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1>
		<p>The requested URL ".$_SERVER['PHP_SELF']." was not found on this server.</p></body></html>";
}
?>
