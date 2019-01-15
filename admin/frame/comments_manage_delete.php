<?php
	define('DIRECT_VISIT_CHECK', 'IN_GUESTBOOK');
	require_once("../../inc/core.class.php");

	session_start();

	if (!isset($_SESSION['userid'])) {
		die("您未登录!! 请先登录!!");
	}

	$user = new User($_SESSION['userid']);
	$cid = isset($_GET['cid']) ? trim($_GET['cid']) : '';
	if (empty($cid)) {
		die("缺少参数：cid!!!");
	}
	$comment = new Comment($cid);

	if ($comment->getOwner() != $user->getUserid() && $user->getLevel() != 1) {
		die("您无权访问此页面。");
	}

	$db = Database::getInstance();
	$db->Delete("comments", "id=".$cid);
	header("Location: ./comments_manage.php?hinttype=succeed&hintmsg=删除成功");  
?>