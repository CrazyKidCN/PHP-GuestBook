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

      if (!$username){
         $HintType = 'error';
         $HintMsg = '请填写用户名';
      } else if (!$password) {
         $HintType = 'error';
         $HintMsg = '请填写密码';
      } else {
        $password_md5 = md5($password);

        $db = Database::getInstance();
        $Result = $db->Select("*", null, "user", "username='$username' and password='$password_md5'", true);
          if ($Result){
            //登录成功设置Session... 然后跳到主页
            $_SESSION["userid"] = $Result['id'];
            header('Location: index.php');
            exit();
          } else {
            $HintType = 'error';
            $HintMsg = '用户名或密码不正确!';
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
    <title>用户登录</title>

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



    <form class="form-horizontal" action="" method="post" >

      <div class="form-group">
        <?php if ($HintType=="error") { ?>
        <!--登录失败的提示-->
        <div class="alert alert-danger col-sm-offset-4 col-sm-4 fade in" role="alert">
          <?php echo $HintMsg; ?>
          <!--关闭按钮-->
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <?php } ?>
      </div>

      <div class="form-group">
        <h2 class="col-sm-offset-4 col-sm-4">用户登录</h2>
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
        <div class="col-sm-offset-4 col-sm-3">
          <button type="submit" class="btn btn-primary">登录</button>
          <a href="register.php" class="btn btn-info">没有账号？去注册>></a>
        </div>
      </div>
    </form>
   


    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>