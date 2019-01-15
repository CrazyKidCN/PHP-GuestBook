<?php
    $HintType = '';
    $HintMsg = '';

    session_start();
    //已登录直接跳到主页
    if (isset($_SESSION["userid"])) {
      header('Location: index.php');
      exit();
    }

    if (!empty($_POST)) {
        define('DIRECT_VISIT_CHECK', 'IN_GUESTBOOK');
        require_once("./inc/core.class.php");


       $username = isset($_POST['username']) ? trim($_POST['username']) : '';
       $password = isset($_POST['password']) ? trim($_POST['password']) : '';
       $password_comfirm = isset($_POST['password_comfirm']) ? trim($_POST['password_comfirm']) : '';
       $captcha = isset($_POST['captcha']) ? trim($_POST['captcha']) : '';


       if (!$captcha || strtolower($captcha)!=$_SESSION['authnum_session']){
          $HintType = 'error';
          $HintMsg = '验证码不正确';
       } else if (!$username) {
          $HintType = 'error';
          $HintMsg = '请输入用户名';
       } else if (!$password) {
          $HintType = 'error';
          $HintMsg = '请输入密码';
       } else if ($password != $password_comfirm){
         $HintType = 'error';
            $HintMsg = '两次输入的密码不一致';
       } else {
          //检查用户名是否已存在
          $db = Database::getInstance();
          $Result = $db->Select("*", null, "user", "username='$username'", true);



          if ($Result){
            $HintType = 'error';
            $HintMsg = '用户名已存在！';
          }

          //用户名不存在，可以注册
          else {
            $password_md5 = md5($password);
            $db->Insert("user", array("username", "password"), array($username, $password_md5));
            
            $HintType = 'succeed';
            $HintMsg = '注册成功~ <a href="login.php">去登录>></a>';
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
    <title>用户注册</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">


    <!-- HTML5 shim 和 Respond.js 是为了让 IE8 支持 HTML5 元素和媒体查询（media queries）功能 -->
    <!-- 警告：通过 file:// 协议（就是直接将 html 页面拖拽到浏览器中）访问页面时 Respond.js 不起作用 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <!-- 让表格往下一点..... -->
    <div style="margin-top: 5%"></div>
    
    <form class="form-horizontal" action="" method="post">

      <div class="form-group">

        <?php if ($HintType=="succeed") { ?>
        <!--注册成功的提示-->
        <div class="alert alert-success col-sm-offset-4 col-sm-4 fade in" role="alert">
          <?php echo $HintMsg; ?>
          <!--关闭按钮-->
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
       <?php } else if ($HintType=="error") { ?>
        <!--注册失败的提示-->
        <div class="alert alert-danger col-sm-offset-4 col-sm-4 fade in" role="alert">
          <?php echo $HintMsg; ?>
          <!--关闭按钮-->
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <?php } ?>

      </div>

      <div class="form-group">
        <h2 class="col-sm-offset-4 col-sm-4">用户注册</h2>
      </div>
      
      <div class="form-group">
        <label for="inputUsername" class="col-sm-4 control-label">用户名</label>
        <div class="col-sm-3">
          <input type="text" class="form-control" id="inputUsername" name="username" placeholder="请输入用户名">
        </div>
      </div>
      <div class="form-group">
        <label for="inputPassword" class="col-sm-4 control-label">密码</label>
        <div class="col-sm-3">
          <input type="password" class="form-control" id="inputPassword" name="password" placeholder="请输入密码">
        </div>
      </div>
      <div class="form-group">
        <label for="inputPasswordConfirm" class="col-sm-4 control-label">确认密码</label>
        <div class="col-sm-3">
          <input type="password" class="form-control" id="inputPasswordConfirm" name="password_comfirm" placeholder="请再输入一次密码">
        </div>
      </div>
      <div class="form-group">
        <label for="inputCaptcha" class="col-sm-4 control-label">验证码</label>
        <div class="col-sm-3">
          <input type="text" class="form-control" id="inputCaptcha" name="captcha" placeholder="请输入验证码">
        </div>
        <img id="captcha_pic" title="点击刷新" src="./inc/getCaptcha.php" align="absbottom" onclick="this.src='./inc/getCaptcha.php?'+Math.random();"></img>
      </div>
      

      <div class="form-group">
        <div class="col-sm-offset-4 col-sm-3">
          <button type="submit" class="btn btn-primary">注册</button>
          <a href="login.php" class="btn btn-info">已有账号？去登录>></a>
          <!-- <button type="submit" class="btn btn-info">已有账号？去登录>></button> -->
        </div>
      </div>
    </form>
   


    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>