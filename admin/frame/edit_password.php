<?php
  define('DIRECT_VISIT_CHECK', 'IN_GUESTBOOK');
  require_once("../../inc/core.class.php");

  session_start();
  if (isset($_SESSION['userid'])) {
    $user = new User($_SESSION['userid']);
  } else {
    die("您未登录!! 请先登录!!");
  }

  $HintType = '';
  $HintMsg = '';

  if (!empty($_POST)) {
    $oldPassword = isset($_POST['oldPassword']) ? trim($_POST['oldPassword']) : '';
    $newPassword = isset($_POST['newPassword']) ? trim($_POST['newPassword']) : '';
    $newPasswordConfirm = isset($_POST['newPasswordConfirm']) ? trim($_POST['newPasswordConfirm']) : '';

    if (!$oldPassword) {
      $HintType = 'error';
      $HintMsg = '原密码不能为空！';
    } else if (!$newPassword) {
      $HintType = 'error';
      $HintMsg = '新密码不能为空！';
    } else if ($newPassword != $newPasswordConfirm) {
      $HintType = 'error';
      $HintMsg = '两次输入的新密码不一致！';
    } else {
      //检查原密码是否正确
      $oldPassword_md5 = md5($oldPassword);
      if ($oldPassword_md5 != $user->getPassword()) {
        $HintType = 'error';
        $HintMsg = '原密码不正确！';
      } else {
        //修改密码
        $user->setPassword($newPassword);
        $HintType = 'succeed';
        $HintMsg = '修改密码成功!';
      }
    }
  }

?>

<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>管理中心</title>

    <!-- Bootstrap -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim 和 Respond.js 是为了让 IE8 支持 HTML5 元素和媒体查询（media queries）功能 -->
    <!-- 警告：通过 file:// 协议（就是直接将 html 页面拖拽到浏览器中）访问页面时 Respond.js 不起作用 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    

      <div class="row" style="padding-top: 10px;padding-left: 20px;">

       <?php if ($HintType=="succeed") { ?>
        <!--成功的提示-->
        <div class="alert alert-success col-sm-6" role="alert">
          <?php echo $HintMsg; ?>
        </div>
       <?php } else if ($HintType=="error") { ?>
        <!--失败的提示-->
        <div class="alert alert-danger col-sm-6" role="alert">
          <?php echo $HintMsg; ?>
        </div>
        <?php } ?>

      </div>

      <div class="container-fluid col-sm-3" style="padding-top: 0px;">

        <div class="form-group">
          <h2>修改密码</h2>
        </div>
        
        <div class="form-group">
        <form action="" method="post">
      	  <div class="form-group">
      		<label for="oldPassword">原密码</label>
      		<input type="password" class="form-control" id="oldPassword" name="oldPassword" placeholder="请输入原密码">
      	  </div>
      	  <div class="form-group">
      		<label for="newPassword">新密码</label>
      		<input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="请输入新密码">
      	  </div>
      	 <div class="form-group">
          <label for="newPasswordConfirm">确认密码</label>
          <input type="password" class="form-control" id="newPasswordConfirm" name="newPasswordConfirm" placeholder="请再输入一次新密码">
          </div>
      	  <button type="submit" class="btn btn-default">提交</button>
  	    </form>
        </div>
      </div>
    

    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="../../js/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="../../js/bootstrap.min.js"></script>
  </body>
</html>

