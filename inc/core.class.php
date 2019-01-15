<?php

/**
 * @author CrazyKid
 */


if(!defined('DIRECT_VISIT_CHECK')) {
	exit('Access Denied');
}

class Database {

	private static $_instance; //储存对象
	private $link; //数据库连接标识

	private $db_host = 'localhost';
	private $db_port = 3306;

	private $db_user = 'root';
	private $db_pwd = '123456';

	private $db_dbname = 'php_homework';

	//构造方法
	private function __construct() {
        $this->_connect();
    }

    //连接方法
    private function _connect() {
    	$PHP = substr(PHP_VERSION, 0, 1);
    	if ($PHP < 7){
        	$this->link = mysql_connect($this->db_host.":".$this->db_port, $this->db_user, $this->db_pwd);
        	mysql_query("set names 'UTF-8'");
		mysql_select_db($this->db_dbname, $this->link);
    	}
      	else{
      		$this->link = mysqli_connect($this->db_host.":".$this->db_port, $this->db_user, $this->db_pwd, $this->db_dbname);
			mysqli_query($this->link, "set names 'UTF-8'");
      	}

		if (!$this->link)
			$this->err('Failed to connect to database!!');
    }

    //防止被克隆
    private function __clone(){

    }

    //获取对象
    public static function getInstance(){
       if (!self::$_instance instanceof self)
			self::$_instance = new self();
		return self::$_instance;
    }
	
	/** 
	 * 请求一条mysql
	 * @param string $sql 执行的语句
	 * @param bool $fetchfirst 是否只返回第一条结果
	 *
	 * @return 查询结果 (false为无结果)
	 */
	public function Query($sql, $fetchfirst=false)
	{
		$PHP = substr(PHP_VERSION, 0, 1);
		$resultset = array();

    	if ($PHP < 7){
			$result = mysql_query($sql) or $this->err($sql);
			if (is_bool($result)) 
				return $result;

			while($row = mysql_fetch_array($result)) {
				if ($fetchfirst) return $row;
				array_push($resultset, $row);
			}
		} else {
			$result = mysqli_query($this->link, $sql) or $this->err($sql);
			if (is_bool($result)) 
				return $result;

			while($row = mysqli_fetch_array($result)) {
				if ($fetchfirst) return $row;
				array_push($resultset, $row);
			}
		}
		return $resultset;
	}


	/** 
	 * 插入一条数据
	 * @param string $tablename 表名
	 * @param array() $key 键
	 * @param array() $value 值
	 *
	 * @return bool true 成功
	 * @otherwise 失败
	 */
	public function Insert($tablename, $key, $value)
	{
		//$sql = "INSERT INTO `user` (`username`,`password`) values ('$username','$password_md5')";

		if (is_array($key))
			$key2 = join("`,`", $key);
		else
			$key2 = $key;

		if (is_array($value))
			$value2 = join("','", $value);
		else
			$value2 = $value;

		$sql = "INSERT INTO `".$tablename."` (`".$key2."`) VALUES ('".$value2."')";

		$this->Query($sql);

		return true;
	}

	/** 
	 * 选择数据
	 * @param array() $toSelect 要选择的内容
	 * @param array() $alias 别名
	 * @param string $tablename 表名
	 * @param string $condition 条件,为null表示没有
	 * @param bool $fetchfirst 是否只返回第一条结果
	 *
	 * @return bool true 成功
	 * @otherwise 失败
	 */
	public function Select($toSelect, $alias, $tablename, $condition, $fetchfirst=false)
	{
		if (is_array($toSelect) && is_array($alias) && count($alias)>1 && count($alias)==count($toSelect)) {
			for ($i=0; $i<count($alias); $i++) {
				$toSelect[$i] .= " as '".$alias[$i]."'"; 
			}
		}

		if (is_array($toSelect))
			$toSelect2 = join(",", $toSelect);
		else
			$toSelect2 = $toSelect;

		$sql = "SELECT ".$toSelect2." FROM ".$tablename;

		if ($condition)
			$sql .= " WHERE ".$condition;

		$result = $this->Query($sql, $fetchfirst);
		return $result;
	}

	/** 
	 * 更新数据
	 * @param string $tablename 表名
	 * @param array() $key 键
	 * @param array() $value 值
	 * @param string $condition 条件,为null表示没有
	 *
	 * @return bool true 成功
	 * @otherwise 失败
	 */
	public function Update($tablename, $key, $value, $condition)
	{
		if (is_array($tablename) && count($tablename)>1)
			$tablename2 = join(",", $tablename);
		else
			$tablename2 = $tablename;

		if (is_array($key) && is_array($value) ) {
			$setArray = array();
			for ($i=0; $i<count($key); $i++) {
				array_push($setArray, $key[$i]."='".$value[$i]."'");
			}
			$set = join(",", $setArray);
		} else {
			$set = $key."='".$value."'";
		}

		$sql = "UPDATE ".$tablename2." SET ".$set;

		if ($condition)
			$sql .= " WHERE ".$condition;

		$result = $this->Query($sql);
		return $result;
	}

	/** 
	 * 删除数据
	 * @param string $tablename 表名
	 * @param string $condition 条件,为null表示没有
	 *
	 * @return bool true 成功
	 * @otherwise 失败
	 */
	public function Delete($tablename, $condition)
	{
		$sql = "DELETE FROM ".$tablename;
		if ($condition) {
			$sql .= " WHERE ".$condition;
		}
		$result = $this->Query($sql);
		return $result;
	}

	//错误信息输出
	private function err($message){
		echo "[Database Class] ERROR: ".$message;
		die();
	}
}

class User {

	private $userid; //用户id
	private $username; //用户名
	private $password_md5; //md5加密后的密码
	private $level; //用户等级 0=普通用户 1=管理员 2=超级管理员
	private $avatar; //头像地址

	//构造方法
	public function __construct($userid) {
        $db = Database::getInstance();
        $Result = $db->Select("*", null, "user", "id='$userid'", true);
        if (!$Result){
        	$this->err("__construct(): 实例化User对象时出错,没有找到用户");
        }

        $this->userid = $Result['id'];
        $this->username = $Result['username'];
        $this->password_md5 = $Result['password'];
        $this->level = $Result['level'];
        $this->avatar = $Result['avatar'];
    }

    //Get方法
    public function getUserid() {
    	return $this->userid;
    }
    public function getUsername() {
    	return $this->username;
    }
    public function getPassword() {
    	return $this->password_md5;
    }
    public function getLevel() {
    	return $this->level;
    }
    public function getAvatar() {
    	if (!$this->avatar) {
    		return "default.jpg";
    	}
    	return $this->avatar;
    }

    //Set方法
    public function setPassword($newPassword) {
    	$db = Database::getInstance();
    	$Result = $db->Update("user", "password", md5($newPassword), "id=".$this->userid);
    	if ($Result)
    		$this->password_md5 = md5($newPassword);
    	else
    		$this->err("setPassword(): 修改用户密码时出错");
    }
    public function setAvatar($newAvatar) {
    	$db = Database::getInstance();
    	$Result = $db->Update("user", "avatar", $newAvatar, "id=".$this->userid);
    	if ($Result)
    		$this->avatar = $newAvatar;
    	else
    		$this->err("setAvatar(): 修改用户头像时出错");
    }

	//错误信息输出
	private function err($message){
		echo "[User Class] ERROR: ".$message;
		die();
	}
}

class Comment {
	private $cid; //文章编号
	private $owner; //发布者
	private $cdate; //发布日期
	private $text; //发布内容

	//构造方法
	public function __construct($cid) {
        $db = Database::getInstance();
        $Result = $db->Select("*", null, "comments", "id='$cid'", true);
        if (!$Result){
        	$this->err("__construct(): 实例化comment对象时出错,没有找到用户");
        }

        $this->cid = $Result['id'];
        $this->owner = $Result['owner'];
        $this->cdate = $Result['date'];
        $this->text = $Result['text'];
    }

    //Get方法
    public function getId() {
    	return $this->cid;
    }
    public function getOwner() {
    	return $this->owner;
    }
    public function getDate() {
    	return $this->cdate;
    }
    public function getText() {
    	return $this->text;
    }

    //Set方法
    public function setDate($date) {
    	$this->cdate = $date;
    }
    public function setText($text) {
    	$this->text = $text;
    }

    //错误信息输出
	private function err($message){
		echo "[User Class] ERROR: ".$message;
		die();
	}
}

?>
