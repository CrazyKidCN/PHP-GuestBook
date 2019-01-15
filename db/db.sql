/*
SQLyog Ultimate v8.32 
MySQL - 5.5.5-10.1.33-MariaDB : Database - php_homework
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`php_homework` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `php_homework`;

/*Table structure for table `comments` */

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章编号',
  `owner` int(11) NOT NULL COMMENT '发布者',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '发布日期',
  `text` text COLLATE utf8_unicode_ci COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `comments` */

insert  into `comments`(`id`,`owner`,`date`,`text`) values (1,8,'2019-01-03 15:01:49','ç¬¬ä¸€æ¡ç•™è¨€~~<img src=\"http://localhost/phphomework/js/editor/plugins/emoticons/images/13.gif\" border=\"0\" alt=\"\" />&nbsp;<strong>æµ‹è¯•</strong><span style=\"background-color:#E53333;\">æµ‹è¯•dasdsadsadasdasdda</span>');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` char(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `password` char(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `level` int(11) NOT NULL DEFAULT '0' COMMENT '等级',
  `avatar` char(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '头像',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`password`,`level`,`avatar`) values (6,'CrazyKid3','96e79218965eb72c92a549dd5a330112',0,'6.jpg'),(8,'CrazyKid','96e79218965eb72c92a549dd5a330112',1,'8.jpg'),(9,'test','96e79218965eb72c92a549dd5a330112',0,'9.jpg'),(10,'test123','e10adc3949ba59abbe56e057f20f883e',1,'10.jpg'),(12,'test1231312312','96e79218965eb72c92a549dd5a330112',0,NULL),(13,'testafeafef','0b4e7a0e5fe84ad35fb5f95b9ceeac79',0,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
