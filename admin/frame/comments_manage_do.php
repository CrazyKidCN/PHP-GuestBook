<?php
  define('DIRECT_VISIT_CHECK', 'IN_GUESTBOOK');
  require_once("../../inc/core.class.php");

  $HintType = '';
  $HintMsg = '';
  $succeed = 0;

  session_start();
  if (isset($_SESSION['userid'])) {
    $user = new User($_SESSION['userid']);
    if ($user->getLevel() != 1) {
      die("您无权访问此页面。");
    }
  } else {
    die("您未登录!! 请先登录!!");
  }

  if (!isset($_POST['cid'])) {
    if (isset($_GET['id'])) {
     $comment = new Comment($_GET['id']);
     $author = new User($comment->getOwner());
    } else {
      die("缺少参数: id!!!");
    }
  } else {
    $comment = new Comment($_POST['cid']);
    $author = new User($_POST['owner']);
    $key = array();
    $value = array();

    $post_date = isset($_POST['post_date']) ? trim($_POST['post_date']) : '';

    if (isset($_POST['content'])) {
      if (get_magic_quotes_gpc()) {
        $content = stripslashes($_POST['content']); //删除由 addslashes() 函数添加的反斜杠。
      } else {
        $content = $_POST['content'];
      }
    }

    if (!empty($post_date)) {
      array_push($key, "date");
      array_push($value, $post_date);
      $comment->setDate($post_date);
    } else {
      $succeed = -1;
      $HintType = 'error';
      $HintMsg = '日期必填！';
    }

    if (!empty($content)) {
      array_push($key, "text");
      array_push($value, $content);
      $comment->setText($content);
    } else {
      $succeed = -1;
      $HintType = 'error';
      $HintMsg = '内容不能为空！';
    }


    if (count($key)>0 && count($value)>0 && $succeed != -1) {
      $db = Database::getInstance();
      $db->Update("comments", $key, $value, "id=".$_POST['cid']);
      $HintType = 'succeed';
      $HintMsg = '更新成功';
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
    <title>编辑用户信息</title>

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
    
  <h2 class="row" style="padding-top: 10px;padding-left: 20px;" >编辑用户信息</h2>

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

	<div class="row col-sm-6" style="padding-top: 10px;padding-left: 20px;">
    <form action="" method="post" enctype="multipart/form-data">
      <table class="table table-hover">
		    <div class="form-group">
          <label for="cid">文章编号</label>
          <input type="hidden" class="form-control" id="cid" name="cid"  value="<?php echo $comment->getId(); ?>" />
          <input type="text" class="form-control" disabled="true" value="<?php echo $comment->getId(); ?>" />
        </div>
        <div class="form-group">
          <label for="owner">发布者</label>
          <input type="hidden" class="form-control" id="owner" name="owner" value="<?php echo $comment->getOwner(); ?>" />
          <input type="text" class="form-control" disabled="true" value="<?php echo $author->getUsername(); ?>" />
        </div>
        <div class="form-group">
          <label for="post_date">发布日期</label>
          <input type="text" class="form-control" id="post_date" name="post_date" value="<?php echo $comment->getDate(); ?>" />
        </div>
       <div class="form-group">
          <label for="kindeditor">内容</label>
         <textarea type="text" class="form-control" id="kindeditor" name="content">
            <?php echo htmlspecialchars($comment->getText()); ?>
         </textarea>
        </div>
      </table>
      <button type="submit" class="btn btn-default">保存更改</button>
    </form>
  
    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="../../js/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="../../js/bootstrap.min.js"></script>

    <!-- HTML编辑器 -->
    <script charset="utf-8" src="../../js/editor/kindeditor-all-min.js"></script>
    <script charset="utf-8" src="../../js/editor/lang/zh-CN.js"></script>
    <script>
      KindEditor.ready(function(K) {
        window.editor = K.create('#kindeditor', {
          width : '700px',
          height : '300px'
        });
      });
    </script>
  </body>
</html>