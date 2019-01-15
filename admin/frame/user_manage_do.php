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

  if (!isset($_POST['userid'])) {
    if (isset($_GET['uid'])) {
      $uid = trim($_GET['uid']);
      $User2 = new User($uid);
    } else {
      die("缺少参数: uid!!!");
    }
  } else {
    $uid = isset($_POST['userid']) ? trim($_POST['userid']) : '';
    $uname = isset($_POST['username']) ? trim($_POST['username']) : '';
    $upassword = isset($_POST['password']) ? trim($_POST['password']) : '';
    $ucpassword = isset($_POST['cpassword']) ? trim($_POST['cpassword']) : '';
    $ulevel = isset($_POST['userlevel']) ? trim($_POST['userlevel']) : '';

    //uid的判断
    if (empty($uid)) {
      die("出错：$uid为空！");
    }

    //获取被修改的用户原来的信息
    $User2 = new User($uid);
    $key = array();
    $value = array();

    //username是否做出了修改？
    if (!empty($uname) && $uname != $User2->getUsername()) {
      //检查用户名是否已存在
      $db = Database::getInstance();
      $Result = $db->Select("*", null, "user", "username='$uname'", true);
      if ($Result){
        $HintType = 'error';
        $HintMsg = '用户名已存在！';
        $succeed = -1;
      }
      array_push($key, "username");
      array_push($value, $uname);
    }

    //是否要修改密码？
    if (!empty($upassword)) {
      if ($upassword == $ucpassword) {
        array_push($key, "password");
        array_push($value, md5($upassword));
      } else {
        $HintType = 'error';
        $HintMsg = '两次输入的密码不一致';
        $succeed = -1;
      }
    }

    //是否要修改用户等级？
    if (!empty($ulevel) && $ulevel != $User2->getLevel()) {
      array_push($key, "level");
      array_push($value, $ulevel);
    }

    if ($succeed != -1)
      $succeed = 1;

    //是否修改了头像？
    if (!empty($_FILES)) {
    //判断文件上传是否出错
    if($_FILES["inputAvatarFile"]["error"]) {
        $HintType = 'error';
        switch ($_FILES["inputAvatarFile"]["error"]) {
          case 1: {
            $HintMsg = '上传的文件超过了 php.ini 中 upload_max_filesize选项限制的值';
            break;
          }
          case 2: {
            $HintMsg = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';
            break;
          }
          case 3: {
            $HintMsg = '文件只有部分被上传';
            break;
          }
          case 4: {
            $HintMsg = '没有文件被上传';
            break;
          }
          case 5: {
            $HintMsg = '未知错误。(ERROR Code:5)';
            break;
          }
          case 6: {
            $HintMsg = '找不到临时文件夹';
            break;
          }
          case 7: {
            $HintMsg = '文件写入失败';
            break;
          }
          default: {
            $HintMsg = '未知错误。(ERROR Code:'.$_FILES["inputAvatarFile"]["error"].')';
            break;
          }
        }
      } else {
        if($_FILES["inputAvatarFile"]["type"]=="image/jpeg" && $_FILES["inputAvatarFile"]["size"]<1024*10000)//限定jpg格式和大小1m
        {
            $savePath = "../../img/avatar/".$User2->getUserid().".jpg";  
            if (move_uploaded_file($_FILES["inputAvatarFile"]["tmp_name"],$savePath)) {
              //$user->setAvatar($user->getUserid().".jpg");
              array_push($key, "avatar");
              array_push($value, $User2->getUserid().".jpg");
              $succeed = 2;
            } else {
              $HintType = 'error';
              $HintMsg = '头像上传失败..';
              $succeed = -1;
            }
        } else {
          $HintType = 'error';
          $HintMsg = '上传文件格式不是jpg, 或者上传的文件超过1M';
          $succeed = -1;
        }
      }
     
    }

    if (count($key)>0 && count($value)>0 && $succeed != -1) {
      $db = Database::getInstance();
      $db->Update("user", $key, $value, "id=".$uid);
      if ($succeed==2) {
        $HintType = 'succeed';
        $HintMsg = '用户头像上传成功!';
      } else if ($succeed==1){
        $HintType = 'succeed';
        $HintMsg = '修改用户信息成功';
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
          <label for="userid">用户ID</label>
          <input type="hidden" class="form-control" id="userid" name="userid"  value="<?php echo $User2->getUserid(); ?>" />
          <input type="text" class="form-control" disabled="true" value="<?php echo $User2->getUserid(); ?>" />
        </div>
        <div class="form-group">
          <label for="username">用户名</label>
          <input type="text" class="form-control" id="username" name="username" value="<?php echo $User2->getUsername(); ?>" />
        </div>
        <div class="form-group">
          <label for="password">密码</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="留空则不更改" />
        </div>
        <div class="form-group">
          <label for="cpassword">确认密码</label>
          <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="留空则不更改" />
        </div>
        <div class="form-group">
          <label for="userlevel">等级</label>
          <select class="form-control" id="userlevel" name="userlevel">
            <option value="0" >普通用户</option>
            <option value="1" <?php echo $User2->getLevel()>0?'selected':''; ?>>管理员</option>
          </select>
        </div>
      </table>
      <button type="submit" class="btn btn-default">提交</button>
    </form>

    <hr />

    <div class="form-group">
          <h2>修改用户头像</h2>
        </div>

        <div class="form-group">
          <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label for="inputAvatarFile">头像文件</label>
              <input type="hidden" class="form-control" id="userid" name="userid"  value="<?php echo $User2->getUserid(); ?>" />
              <input type="file" id="inputAvatarFile" name="inputAvatarFile">
              <p class="help-block">请选择头像文件,格式限定为jpg,且大小不超过1M</p>
            </div>
            <button type="submit" class="btn btn-default">上传头像</button>
          </form>
        </div>

        <div class="row" style="padding-top: 100px;padding-left: 20px;">
        当前头像: <img src="../../img/avatar/<?php echo $User2->getAvatar()."?r=".rand(1,999999); ?>" height="150px" width="150px" />
      </div>
  </div>

  
  
    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="../../js/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="../../js/bootstrap.min.js"></script>
  </body>
</html>

