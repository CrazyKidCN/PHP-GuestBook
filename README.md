# PHP-GuestBook
PHP 课程设计 - 留言板

### 简介
一个用原生 PHP 写的简易留言板，使用面向对象思想开发。

### 在线Demo
https://project.crazykid.moe/php-guestbook/ 账号: CrazyKid 密码:111111

### 实现的功能
#### 前台：
- 用户注册、用户登录、注销
- 验证码 (用户注册时)
- 留言功能
- 留言显示功能 (含作者头像、作者名称、留言时间等基本信息)

#### 后台(普通用户)：
- 修改密码
- 头像上传

#### 后台(管理员)
- 用户管理 (修改任意用户的用户名、密码、等级、头像，或者删除用户) (包含分页功能)
- 留言管理 (修改任意留言的发布日期、留言内容，或者删除留言) (包含分页功能)

### 涉及知识点或技术要点
- 使用 Bootstrap 构建前台和后台界面 (DIV+CSS布局)
- MySQL的增删改查
- 使用 模态框 实现了前台的留言界面
- 使用 模态框+Javascript (JQuery) 实现了删除用户、删除留言时的动态信息提示框
- 使用 [kindeditor](http://kindeditor.net/demo.php) 作为留言的在线编辑器
- 封装了数据库类(单例模式)、用户类及留言类，并主要使用其中的方法实现功能，体现面向对象思想

### 程序截图
![image](https://github.com/CrazyKidCN/PHP-GuestBook/blob/master/example-img/8.jpg)
![image](https://github.com/CrazyKidCN/PHP-GuestBook/blob/master/example-img/9.jpg)
![image](https://github.com/CrazyKidCN/PHP-GuestBook/blob/master/example-img/1.jpg)
![image](https://github.com/CrazyKidCN/PHP-GuestBook/blob/master/example-img/2.jpg)
![image](https://github.com/CrazyKidCN/PHP-GuestBook/blob/master/example-img/3.jpg)
![image](https://github.com/CrazyKidCN/PHP-GuestBook/blob/master/example-img/4.jpg)
![image](https://github.com/CrazyKidCN/PHP-GuestBook/blob/master/example-img/5.jpg)
![image](https://github.com/CrazyKidCN/PHP-GuestBook/blob/master/example-img/6.jpg)
![image](https://github.com/CrazyKidCN/PHP-GuestBook/blob/master/example-img/7.jpg)
![image](https://github.com/CrazyKidCN/PHP-GuestBook/blob/master/example-img/10.jpg)
![image](https://github.com/CrazyKidCN/PHP-GuestBook/blob/master/example-img/11.jpg)
