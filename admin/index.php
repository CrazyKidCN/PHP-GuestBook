<?php
  define('DIRECT_VISIT_CHECK', 'IN_GUESTBOOK');
  require_once("../inc/core.class.php");

  session_start();
  if (isset($_SESSION['userid'])) {
    $user = new User($_SESSION['userid']);
  } else {
    header('Location: ../login.php');
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
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- admin面板自定义样式 -->
    <link href="../css/admin.css" rel="stylesheet">

    <!-- HTML5 shim 和 Respond.js 是为了让 IE8 支持 HTML5 元素和媒体查询（media queries）功能 -->
    <!-- 警告：通过 file:// 协议（就是直接将 html 页面拖拽到浏览器中）访问页面时 Respond.js 不起作用 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <!--顶部导航栏部分-->
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" title="logoTitle" href="#">管理中心</a>
           </div>
           <div class="collapse navbar-collapse">
               <ul class="nav navbar-nav navbar-right">
                   <li role="presentation">
                       <a href="#">当前用户：<span class="badge"><?php echo $user->getUsername(); ?></span></a>
                   </li>
				   <li>
				   <a href="../index.php">
                             <span class="glyphicon glyphicon-home"></span>返回主页
					</a>
					</li>
                   <li>
                       <a href="../logout.php">
                             <span class="glyphicon glyphicon-lock"></span>退出登录
						</a>
                    </li>
                </ul>
           </div>
        </div>      
    </nav>
    <!-- 中间主体内容部分 -->
    <div class="pageContainer">
         <!-- 左侧导航栏 -->
         <div class="pageSidebar">
             <ul class="nav nav-stacked nav-pills">
                 <li role="presentation">
                     <a href="frame/home.php" target="mainFrame" >管理中心首页</a>
                 </li>
                 <li role="presentation">
                     <a href="frame/edit_password.php" target="mainFrame" >修改密码</a>
                 </li>
				 <li role="presentation">
                     <a href="frame/edit_avatar.php" target="mainFrame" >修改头像</a>
                 </li>
               <?php if ($user->getLevel() > 0) { ?>
                 <li role="presentation">
                     <a href="frame/user_manage.php" target="mainFrame">用户管理</a>
                 </li>
                 <li role="presentation">
                     <a href="frame/comments_manage.php" target="mainFrame">留言管理</a>
                 </li>
               <?php } ?>
             </ul>
         </div>
         <!-- 左侧导航和正文内容的分隔线 -->
         <div class="splitter"></div>
         <!-- 正文内容部分 -->
         <div class="pageContent">
             <iframe src="frame/home.php" id="mainFrame" name="mainFrame" frameborder="0" width="100%"  height="100%" frameBorder="0"></iframe>
         </div>
     </div>
     <!-- 底部页脚部分 -->
     <div class="footer">
         <p class="text-center">
             2018 &copy; CrazyKid
         </p>
     </div>
    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="../js/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>