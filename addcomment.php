<?php
  define('DIRECT_VISIT_CHECK', 'IN_GUESTBOOK');
  require_once("./inc/core.class.php");

  session_start();
  if (isset($_SESSION['userid'])) {
    $user = new User($_SESSION['userid']);
  } else {
    die("错误：未登录");
  }

  if (!isset($_POST['content']) || empty($_POST['content'])) {
  	die("错误：留言不能为空");
  }

  $key = array();
  $value = array();

  array_push($key, "owner");
  array_push($value, $user->getUserid());

  array_push($key, "date");
  array_push($value, date("Y-m-d H:i:s"));

  array_push($key, "text");
  array_push($value, $_POST['content']);


  $db = Database::getInstance();
  $db->Insert("comments", $key, $value);
  header("Location: ./index.php"); 
?>