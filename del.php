<?php
	include('./config.php');
	$del_sql = "delete from images where id={$_GET['id']}";
	$db->query($del_sql);
	echo $db->error;
	header("location://amberandbrice.com/workspace");
