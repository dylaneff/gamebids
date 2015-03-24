-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 22, 2015 at 11:14 PM
-- Server version: 5.5.41-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `auction_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE IF NOT EXISTS `bids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `listing_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`id`, `listing_id`, `username`, `amount`, `time`) VALUES
(19, 32, 'dylan', 2003, '2015-03-20 13:13:25'),
(23, 24, 'dylan', 400, '2015-03-21 13:01:02'),
(24, 24, 'dylan', 500, '2015-03-21 13:01:22'),
(26, 30, 'dylan', 400, '2015-03-21 15:25:42'),
(27, 30, 'dylan', 410, '2015-03-21 15:25:53'),
(28, 33, 'dylan', 70, '2015-03-21 15:34:25'),
(32, 24, 'dylan', 505, '2015-03-21 16:42:02'),
(33, 29, 'dylan', 78, '2015-03-21 17:36:34'),
(34, 29, 'dylan', 80, '2015-03-21 17:36:59'),
(35, 29, 'dylan', 500, '2015-03-21 17:37:13'),
(36, 29, 'dylan', 10000, '2015-03-21 17:37:25'),
(37, 27, 'dylan', 52, '2015-03-22 14:18:09'),
(38, 23, 'dylan', 10, '2015-03-22 21:20:13'),
(39, 17, 'Nessa', 20, '2015-03-22 23:03:07');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `permissions` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `permissions`) VALUES
(1, 'Standard User', ''),
(2, 'Adminstrator', '{"admin": 1}');

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE IF NOT EXISTS `listings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` varchar(11) NOT NULL,
  `title` varchar(80) NOT NULL,
  `description` varchar(250) NOT NULL,
  `category` varchar(30) NOT NULL,
  `start_price` decimal(10,0) NOT NULL,
  `reserve_price` decimal(10,0) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `picture` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

--
-- Dumping data for table `listings`
--

INSERT INTO `listings` (`id`, `owner_id`, `title`, `description`, `category`, `start_price`, `reserve_price`, `start_time`, `end_time`, `picture`, `active`) VALUES
(17, '2', 'Majora''s Mask N64', 'Mint condition copy of Majora''s Mask for the N64. Includes Box And Manual.', 'games', 10, 70, '2015-03-17 20:45:02', '2015-03-24 20:45:02', 'images/listings/71dcee07ea.jpg', 1),
(18, '2', 'Xbox One!!!!!!', 'This is a brand new Xbox one. Does not include Snoop Dogg.', 'consoles', 300, 0, '2015-03-17 20:48:40', '2015-03-24 20:48:40', 'images/listings/c113f17e93.jpg', 1),
(19, '2', 'Gaming PC', 'Super duper awesome master race pc', 'computers', 0, 1000, '2015-03-17 20:50:40', '2015-03-22 20:50:40', 'images/listings/5ee81fa23b.jpg', 0),
(20, '2', 'Razer Xbox Controller', 'This is a really cool controller', 'accessories', 20, 0, '2015-03-17 20:51:38', '2015-03-20 20:51:38', 'images/listings/9ad4cdf0f5.png', 0),
(21, '2', 'Best WiiU Controller Ever!', 'Title says it all.', 'accessories', 1, 0, '2015-03-17 20:52:30', '2015-03-24 20:52:30', 'images/listings/aa36cbc56e.jpg', 1),
(22, '2', 'PSVITA', 'Just the system as is.', 'handhelds', 100, 0, '2015-03-17 20:53:06', '2015-03-24 20:53:06', 'images/listings/4d2a2295f4.jpg', 1),
(23, '3', 'Mario Wallet', 'The sweetest wallet ever!', 'merchandise', 1, 10, '2015-03-17 20:54:08', '2015-03-24 20:54:08', 'images/listings/51285bd6e6.jpg', 1),
(24, '3', 'Awesome Gaming Rig', 'Selling my awesome gaming rig.', 'computers', 300, 900, '2015-03-17 20:55:15', '2015-03-24 20:55:15', 'images/listings/5daa4c4f26.jpg', 1),
(25, '3', 'Cool Gaming PC', 'Selling a pc I stole from my brother', 'computers', 12, 40, '2015-03-17 20:56:30', '2015-03-20 20:56:30', 'images/listings/afda04a322.jpg', 0),
(26, '3', 'Headset', 'For chatting duh!', 'accessories', 20, 0, '2015-03-17 20:57:10', '2015-03-20 20:57:10', 'images/listings/294e2556b6.jpg', 0),
(27, '3', 'Nintendo 3DS XL', 'Just the console.', 'handhelds', 50, 0, '2015-03-17 20:58:04', '2015-03-24 20:58:04', 'images/listings/89a9e9b2fe.jpg', 1),
(28, '3', 'Gameboy Advanced SP', 'Selling my GBASP', 'handhelds', 25, 30, '2015-03-17 20:58:55', '2015-03-20 20:58:55', 'images/listings/8c5821d62f.jpg', 0),
(29, '5', 'Cool Link Figure', 'Selling my cool link figure. Like new condition', 'merchandise', 12, 13, '2015-03-17 21:01:52', '2015-03-24 21:01:52', 'images/listings/e0c7c01cf7.jpg', 1),
(30, '5', 'Like New PS4', 'Selling my ps4 only had it for about a month', 'consoles', 350, 0, '2015-03-17 21:02:39', '2015-03-24 21:02:39', 'images/listings/6efdbab78f.jpg', 1),
(31, '5', 'PS4 Games', 'Selling some awesome ps4 games.', 'games', 70, 0, '2015-03-17 21:03:26', '2015-03-24 21:03:26', 'images/listings/e97989ad68.jpg', 1),
(32, '5', 'Selling yet another PS4', 'This one is brand spankin new', 'consoles', 370, 0, '2015-03-17 21:04:03', '2015-03-20 21:04:03', 'images/listings/2ab7e1a088.png', 0),
(33, '5', 'Legend of Zelda ALTTP', 'Selling my mint copy of ALTTP for SNES', 'games', 50, 70, '2015-03-17 21:04:44', '2015-03-22 21:04:44', 'images/listings/24a412425d.jpg', 0),
(34, '5', 'Almost New WiiU', 'Almost brand new', 'consoles', 30, 40, '2015-03-17 21:05:40', '2015-03-20 21:05:40', 'images/listings/2175eed3e8.jpg', 0),
(43, '2', 'sdfdff', 'sdfsdfsdfsdfsdfsd', 'accessories', 22, 22, '2015-03-22 23:10:10', '2015-03-25 23:10:10', 'images/listings/placeholder.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `name` varchar(50) NOT NULL,
  `joined` datetime NOT NULL,
  `groups` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `salt`, `name`, `joined`, `groups`) VALUES
(2, 'dylan', 'c1258de0fcaee93e35629535e7de8182c16dee066d3b8f64305a50dc8549c0a9', '_%¬<šÐ¦øZêþ™ÛyxÙÎ¼ë•]Žçü', 'Dylan Fontaine', '0000-00-00 00:00:00', 2),
(3, 'joey', '932f6156d5af54ee2ae000c59df3b63048f5317a35afa2583bfe926cfbe1a386', 'B1Aêì+ê6®V&|ó[¢²GH+È\ZdÏL·O ', 'Joe', '0000-00-00 00:00:00', 1),
(5, 'spiderman', '7eb171d35571f8d407da416957ae1cf91ff7042c29ee8ad5ed4886dfe68bb88d', 'q\0¼]2]äŸ³ÁÐÂç—Äuâõ©}ÏØ¯X*ëb¯', 'Peter Parker', '0000-00-00 00:00:00', 1),
(9, 'Nessa', '20f921183aa9bdb8edf410302444a2c408ff47314eacf88f1cfdaa77f897089e', 'F‰ñÕÆ¯Y_ÔW€  =·„á:V«æ›‹ÊaÕ', 'Jenessa rakovalis', '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_session`
--

CREATE TABLE IF NOT EXISTS `users_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
