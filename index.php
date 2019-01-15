<?php
  define('DIRECT_VISIT_CHECK', 'IN_GUESTBOOK');
  require_once("./inc/core.class.php");

  session_start();
  if (isset($_SESSION['userid'])) {
    $isLogin = true;
    $user = new User($_SESSION['userid']);
  } else {
    $isLogin = false;
  }

  $db = Database::getInstance();
  $comments = $db->Select("*", NULL, "comments", NULL, false);
?>

<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>留言板</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
 
    <!-- 主页的自定义css -->
    <link href="css/index.css" rel="stylesheet">

    <!-- HTML5 shim 和 Respond.js 是为了让 IE8 支持 HTML5 元素和媒体查询（media queries）功能 -->
    <!-- 警告：通过 file:// 协议（就是直接将 html 页面拖拽到浏览器中）访问页面时 Respond.js 不起作用 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  
    <nav class="navbar navbar-default" style="margin-bottom: 0px;">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">CrazyKid's Guestbook</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">首页 <span class="sr-only">(current)</span></a></li>
            <!--<li><a href="#">文章页</a></li>-->
          </ul>
          
          <ul class="nav navbar-nav navbar-right">
		  <?php if (!$isLogin) { ?>
            <li><a href="login.php">登录</a></li>
            <li><a href="register.php">注册</a></li>
      <?php } else {    ?>
            <li><a href="#">欢迎您, <?php echo $user->getUsername(); ?></a></li>
            <li><a href="admin/index.php">管理中心</a></li>
            <li><a href="logout.php">登出</a></li>
      <?php } ?>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>


    <div class="jumbotron" style="background-image: url(img/bg.jpg); background-repeat: no-repeat;background-size:100% 100%;">
      <div class="container">
          <h1>Welcome to by guestbook!</h1>
          <p>欢迎来到我的留言板!</p>
          <p>
            <?php if ($isLogin) { ?>
            <a class="btn btn-primary btn-lg" role="button" data-toggle="modal" data-target="#addComment">写留言</a>
            <?php } else { ?>
            <a class="btn btn-primary btn-lg" role="button" href="login.php">写留言</a>
            <?php } ?>
          </p>
       </div>
    </div>

    <div class="container" >
      <?php for ($i=0; $i<count($comments); $i++) { 
          $author = new User($comments[$i]['owner']);

        ?>

      <div class="row">
        <!-- 评论块 -->
        <div class="comment-box col-md-3 col-sm-6 col-xs-12">
          <!-- 作者块 -->
          <div class="author-box">
            <!-- 作者头像 -->
            <div id="avatar">
              <img src="./img/avatar/<?php echo $author->getAvatar()."?r=".rand(1,999999); ?>" height="150px" width="150px" />
            </div>
            <!-- 作者信息 -->
            <div id="info">
              <p>用户名：<?php echo $author->getUsername(); ?></p>
              <p>等级：<?php echo $author->getLevel()>0?'<font color="red"><b>管理员</b></font>':'普通成员'; ?></p>
            </div>
          </div>

          <!-- 内容块 -->
          <div class="content-box">
            <!-- 留言时间 -->
            <div id="date">
              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 留言编号: <?php echo $comments[$i]['id']; ?>
              &nbsp;&nbsp;&nbsp;
              <span class="glyphicon glyphicon-time" aria-hidden="true"></span> 留言时间: <?php echo $comments[$i]['date']; ?>
            </div>
            <!-- 留言内容 -->
            <div id="content">
              <?php echo $comments[$i]['text']; ?>
            </div>
          </div>
        </div>
      </div>
      <hr />
      <?php } ?>
    </div>

    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="./js/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="./js/bootstrap.min.js"></script>



    <!-- 页脚 -->
    <footer class="footer">
       <div class="container">
          2018 &copy; CrazyKid's Guestbook
       </div>
    </footer>

     <!-- 写留言弹窗 -->
    <div class="modal fade" id="addComment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <form action="addcomment.php" method="post" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">写留言</h4>
            </div>
            <div class="modal-body">
              <textarea type="text" class="form-control" id="kindeditor" name="content" placeholder="写点什么吧......">
                 
              </textarea>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
              <button type="submit" class="btn btn-primary">提交</button>
            </div>
        </form>
        </div>
      </div>
    </div>

    <!-- HTML编辑器 -->
    <script charset="utf-8" src="./js/editor/kindeditor-all-min.js"></script>
    <script charset="utf-8" src="./js/editor/lang/zh-CN.js"></script>
    <script>
      KindEditor.ready(function(K) {
        window.editor = K.create('#kindeditor', {
          width : '850px',
          height : '300px'
        });
      });
    </script>

  </body>
</html>