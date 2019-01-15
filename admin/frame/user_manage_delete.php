<?php
	define('DIRECT_VISIT_CHECK', 'IN_GUESTBOOK');
	require_once("../../inc/core.class.php");

	session_start();

	if (!isset($_SESSION['userid'])) {
		die("您未登录!! 请先登录!!");
	}

	$user = new User($_SESSION['userid']);
	$deleteuid = isset($_GET['uid']) ? trim($_GET['uid']) : '';
	if (empty($deleteuid)) {
		die("缺少参数：uid!!!");
	}

	if ($user->getLevel() != 1) {
		die("您无权访问此页面。");
	}

	if ($user->getUserId() == $deleteuid) {
		header("Location: ./user_manage.php?hinttype=error&hintmsg=不能删除自己..");  
		die;
	}

	$db = Database::getInstance();
	$db->Delete("user", "id=".$deleteuid);
	header("Location: ./user_manage.php?hinttype=succeed&hintmsg=删除成功");  
?>