-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2017 at 10:07 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ichiapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(15) NOT NULL,
  `pincode` varchar(15) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `fname`, `lname`, `address`, `phone`, `pincode`, `status`, `created_on`) VALUES
(1, 2, 'sss', 'kkk', '480 C, b Ward, Nipani Ves, Kagal.', '123123', '414141', 1, '2017-03-07 22:09:00'),
(2, 2, 'sss', 'kkk', '480 C, b Ward, Nipani Ves, Kagal.', '123123', '414141', 1, '2017-03-07 22:09:00'),
(3, 1, 'abc', 'abc', 'pqr', '345', '123', 1, '2017-03-07 23:53:34'),
(4, 1, 'abc', 'abc', 'pqr', '345', '123', 1, '2017-03-07 23:53:45'),
(5, 1, 'abc', 'abc', 'pqr', '345', '123', 1, '2017-03-07 23:56:22'),
(6, 1, 'Hdhdh', 'Hdhdh', 'gdgdg', '878788', '8787', 1, '2017-03-07 23:58:39'),
(7, 2, 'Gshshhs', 'Gshshhs', 'sgshjsysjsdke un jdikdbdjsiaagys6s ibn jaast tu yg ssgzyszuz', '87878778', '87878787', 1, '2017-03-08 00:00:13'),
(8, 1, 'Gsgs', 'Gsgs', 'sggsg', '94979', '494997', 1, '2017-03-08 00:10:04'),
(9, 1, 'Gux8yf', 'Gux8yf', 'tsz6rs4', '6865737', '35735735', 1, '2017-03-08 00:15:26'),
(10, 2, 'Pufoyfou', 'Pufoyfou', 'ycoyxoyc', '868686', '858575', 1, '2017-03-08 00:16:11'),
(11, 1, 'Ogd', 'Ogd', 'iyd', '65', '68', 1, '2017-03-08 00:19:29');

-- --------------------------------------------------------

--
-- Table structure for table `adsliders`
--

CREATE TABLE `adsliders` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `adsliders`
--

INSERT INTO `adsliders` (`id`, `name`, `image`, `status`) VALUES
(1, 'a', 'https://cdn.manfrotto.com/media/catalog/product/cache/3/image/9df78eab33525d08d6e5fb8d27136e95/u/u/uuid-1800px-inriverimage_383611.jpg', 0),
(2, 'b', 'http://wallpaper-gallery.net/images/image/image-13.jpg', 0),
(3, 'c', 'https://www.metaslider.com/wp-content/uploads/2014/11/mountains1.jpg', 1),
(4, 'd', 'http://cdn.jssor.com/demos/img/landscape/01.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `city_name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `city_name`, `status`, `created_on`) VALUES
(1, 'Ichalkaranji', 1, '2017-02-24 11:03:01');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_requests`
--

CREATE TABLE `coupon_requests` (
  `id` int(11) NOT NULL,
  `id_offer` int(11) NOT NULL,
  `id_retailer` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `coupon_code` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `coupon_requests`
--

INSERT INTO `coupon_requests` (`id`, `id_offer`, `id_retailer`, `id_user`, `coupon_code`, `status`, `created_on`) VALUES
(14, 1, 1, 1, 4627, 0, '2017-02-27 19:51:19'),
(15, 1, 1, 1, 3919, 0, '2017-02-27 19:51:32'),
(16, 1, 1, 1, 8770, 0, '2017-02-27 19:52:26'),
(17, 1, 1, 1, 5841, 0, '2017-02-27 20:09:21'),
(18, 1, 1, 1, 8667, 0, '2017-02-27 20:11:50'),
(19, 1, 1, 1, 9430, 0, '2017-02-27 20:12:27'),
(20, 1, 1, 1, 8734, 0, '2017-02-27 20:12:31'),
(21, 1, 1, 1, 6951, 0, '2017-02-27 20:13:51'),
(22, 1, 1, 1, 6547, 0, '2017-02-27 20:13:59'),
(23, 2, 2, 1, 2490, 1, '2017-02-28 16:50:24'),
(24, 1, 1, 1, 4412, 1, '2017-02-28 16:49:50'),
(25, 1, 1, 1, 6478, 0, '2017-02-28 17:05:46'),
(26, 1, 1, 1, 7358, 0, '2017-02-28 17:10:42'),
(27, 1, 1, 1, 4730, 0, '2017-02-28 17:11:38'),
(28, 1, 1, 1, 7300, 0, '2017-02-28 17:12:04'),
(29, 1, 1, 1, 7725, 0, '2017-02-28 17:13:08'),
(30, 1, 1, 1, 4338, 0, '2017-02-28 17:13:13'),
(31, 1, 1, 1, 2347, 0, '2017-02-28 17:13:15'),
(32, 1, 1, 1, 9123, 0, '2017-02-28 17:13:16'),
(33, 1, 1, 1, 6704, 0, '2017-02-28 17:13:17'),
(34, 1, 1, 1, 6152, 0, '2017-02-28 17:20:03'),
(35, 1, 1, 1, 4869, 0, '2017-02-28 17:20:42'),
(36, 1, 1, 1, 5514, 0, '2017-02-28 17:21:51'),
(37, 1, 1, 1, 7634, 1, '2017-02-28 17:29:00'),
(38, 1, 1, 1, 9171, 0, '2017-02-28 17:29:11'),
(39, 1, 1, 1, 5223, 0, '2017-02-28 17:30:33'),
(40, 1, 1, 1, 8977, 0, '2017-02-28 17:30:40'),
(41, 1, 1, 1, 9118, 0, '2017-02-28 17:30:45'),
(42, 1, 1, 1, 2961, 0, '2017-02-28 17:33:28'),
(43, 1, 1, 1, 1583, 0, '2017-02-28 17:38:49'),
(44, 1, 1, 1, 3799, 1, '2017-02-28 17:41:07'),
(45, 1, 1, 1, 6711, 1, '2017-02-28 17:44:23'),
(46, 1, 1, 1, 9551, 0, '2017-03-06 22:54:23');

-- --------------------------------------------------------

--
-- Table structure for table `offer_categories`
--

CREATE TABLE `offer_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `position` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `offer_categories`
--

INSERT INTO `offer_categories` (`id`, `name`, `image`, `position`, `status`, `created_on`) VALUES
(1, 'Buy 1 Get 1 Free', 'http://www.freecart.co.in/wp-content/uploads/2016/02/Buy1Get1.jpg', 1, 1, '2017-02-26 12:14:30'),
(2, '30% OFF', 'http://www.olivemeco.com/wp-content/uploads/2015/03/30-percent.jpg', 2, 1, '2017-02-26 12:14:38');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `discount` double NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `retailer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `mrp` double NOT NULL,
  `price` double NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `retailer_id`, `name`, `image`, `description`, `quantity`, `mrp`, `price`, `status`, `created_on`) VALUES
(1, 3, 1, 'Patanjali PowerVita', 'http://i.ebayimg.com/00/s/MzAwWDMwMA==/z/OmQAAOSwhRxXLLWG/$_35.JPG', ' Patanjali PowerVita Patanjali PowerVita\r\n Patanjali PowerVita\r\nPatanjali PowerVita\r\n\r\n', '500 Grams', 190, 180, 1, '2017-03-07 20:48:26'),
(2, 4, 1, 'ALOEVERA JUICE (L)', 'https://s-media-cache-ak0.pinimg.com/originals/c1/ab/21/c1ab218ca5b693d1a489c9748afcb27a.jpg', 'Aloevera juice 1 liter. Useful in acidity, gas, digestion problem, joint pain. It is useful in many more diseases. Composition: Each 10 ml contains Aloevera Juice - 9.96 ml. Dosage : 15 - 25 ml twice daily or mixing with equal quantity of water.', '1050 ml', 200, 150, 1, '2017-03-07 20:48:38');

-- --------------------------------------------------------

--
-- Table structure for table `retailers`
--

CREATE TABLE `retailers` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `description` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `image` text NOT NULL,
  `banner` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `retailers`
--

INSERT INTO `retailers` (`id`, `category_id`, `city_id`, `name`, `address`, `description`, `phone`, `image`, `banner`, `status`, `created_at`) VALUES
(1, 1, 1, 'Patanjali Store', 'Kagal', 'ALTER TABLE `retailers` ADD `description` TEXT NOT NULL AFTER `address`;', '8793198421', 'http://www.goasearch.com/wp-content/uploads/2016/07/electricals.jpg', 'http://sequoiasigns.com/wp-content/uploads/2014/12/banner-sign.jpg', 1, '2017-03-06 15:10:46'),
(2, 2, 1, 'Master Dosa', 'Bidri', 'ALTER TABLE `retailers` ADD `description` TEXT NOT NULL AFTER `address`;', '9527632043', 'https://scontent.fbom1-2.fna.fbcdn.net/v/t1.0-9/13938623_1829011127332825_8086071188496803901_n.jpg?oh=2f8f4b57424b8635bc1757c1780ef10a&oe=592C9CC8', 'http://www.goasearch.com/wp-content/uploads/2016/07/electricals.jpg', 1, '2017-02-26 12:21:46');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `name`, `image`, `status`) VALUES
(1, 'Automobile', 'http://www.theartcareerproject.com/wp-content/uploads/2014/11/Automobile-Design-Intro.jpg', 1),
(2, 'Industry', 'ServiceCategoriesData', 1),
(3, 'XYZ', 'ServiceCategoriesData', 1),
(4, 'aaa', 'aaa', 1),
(5, 'abc', 'aaa', 1),
(6, 'pqr', 'aaa', 1),
(7, 'xyz', 'aaa', 1),
(8, 'lmn', 'aaa', 1);

-- --------------------------------------------------------

--
-- Table structure for table `service_providers`
--

CREATE TABLE `service_providers` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `image` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `service_providers`
--

INSERT INTO `service_providers` (`id`, `category_id`, `city_id`, `name`, `address`, `phone`, `image`, `status`, `created_at`) VALUES
(1, 1, 1, 'Sourabh Electricals', 'Kagal', '8793198421', 'http://www.goasearch.com/wp-content/uploads/2016/07/electricals.jpg', 1, '2017-02-24 20:26:31'),
(2, 1, 1, 'Master Dosa', 'Bidri', '9527632043', 'https://scontent.fbom1-2.fna.fbcdn.net/v/t1.0-9/13938623_1829011127332825_8086071188496803901_n.jpg?oh=2f8f4b57424b8635bc1757c1780ef10a&oe=592C9CC8', 1, '2017-02-24 18:42:00');

-- --------------------------------------------------------

--
-- Table structure for table `shopping_categories`
--

CREATE TABLE `shopping_categories` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `position` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shopping_categories`
--

INSERT INTO `shopping_categories` (`id`, `parent_id`, `name`, `image`, `position`, `status`, `created_on`) VALUES
(1, 0, 'Patanjali Products', 'https://upload.wikimedia.org/wikipedia/en/thumb/1/19/Patanjali_logo.jpeg/220px-Patanjali_logo.jpeg', 1, 1, '2017-02-28 19:57:59'),
(2, 0, 'Health Care', 'https://static1.squarespace.com/static/56cb3afbf055e927c42f4954/56cb57a5d518adf88b4d8e48/5792230f893fc0c275035696/1469196043301/healthcare.jpg', 2, 1, '2017-02-28 19:57:08'),
(3, 1, 'Ayurvedic Medicines', 'http://akornnaturecare.com/wp-content/uploads/2016/09/ayurvedic-medicine-types.png', 0, 1, '2017-03-05 18:37:13'),
(4, 1, 'Health drinks', 'http://assets.menshealth.co.uk/main/thumbs/30500/green-KZBgpw__square.jpg', 0, 1, '2017-03-05 18:44:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(250) DEFAULT NULL,
  `lname` varchar(250) DEFAULT NULL,
  `phone` varchar(250) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` text NOT NULL,
  `api_key` varchar(32) NOT NULL,
  `gcm_regid` text NOT NULL,
  `imei` text NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `membership` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `phone`, `email`, `password_hash`, `api_key`, `gcm_regid`, `imei`, `status`, `membership`, `created_at`) VALUES
(1, 'guest', 's', '5', 's@s.com', '$2a$10$d5fd19ae1b18103c4e404eC/2QeJD7tOBMDlMkY0o2yyvsF3iPTCe', 'guest', 'as', '', 1, 0, '2017-02-20 18:50:13'),
(2, 's', 's', '555', 'sss@s.com', '$2a$10$4c92413f56c4936e109d9uvs02Tq.NdawDwjGQhPtExv57MrL5C2W', '55dc81cd1c8029d7818e8b1a33a90ba8', 'as', '123', 1, 0, '2017-02-20 18:56:20'),
(5, 'S', 'S', '8888888888', 's@s.s', '$2a$10$2ea2c85a75a33d731bf34uq8DBvYQNylqwjjDQy7oc7Ji2g156ur2', '75fc41686c627585e81d40ad0de964ad', 'gcm_regid', '861645032359721', 1, 0, '2017-02-21 16:53:34'),
(12, 's', 's', '555645', 'sss@s.com', '$2a$10$6dfa69f818397e65d5e2cu0a3.DNyzgHBzakhwjD/cl8k4uNywNJG', 'c3bcd48bd6596db805d16ff30e6d68d1', 'as', '123', 1, 0, '2017-02-21 17:25:17'),
(13, 's', 's', '5556453', 'sss@s.com', '$2a$10$768972e2379799be558b6evJB5unat08wJptaIuBRkW3UcJ5IKDam', 'e213110cbfc2eafa8f92a567b741bfc1', 'as', '123', 1, 0, '2017-02-21 17:28:28'),
(14, 'S', 'S', '8585858585', 't@t.d', '$2a$10$fff8e9281d46d0967f0dfu2aewe0JEfExR2LWbkJd/LGLX/ZztIA6', '2c6ac21c991b270df78bf20c158380fd', 'gcm_regid', '861645032359721', 1, 0, '2017-02-21 17:43:25'),
(15, 'H', 'H', '9999999999', 'f@f.com', '$2a$10$ee351da21e01282f6ceccu1W1Non/S2XofApx3MGRIN7RcENBQPdu', 'ff8b4476c9636645043f4ed7743a3f45', 'gcm_regid', '861645032359721', 1, 0, '2017-02-21 17:44:11'),
(16, 'G', 'G', '66556565656565', 'gh@gh.fh', '$2a$10$44fd240ad046642d6780cOL8M/IEvncjMRu0CCTwW50Qt3pe/P12a', '4c784dfc73c5ee3ca2d8ba2fb4426ca2', 'gcm_regid', '861645032359721', 1, 0, '2017-02-21 17:46:49'),
(17, 'V', 'V', '7878787878', 'v@v.v', '$2a$10$0f541e65e4b1b942741a6ubs8jH9G8l9EwEKKs.vI.mfJIvCx08vO', '0fe910cb081440f4a5edef5dd0a8428d', 'gcm_regid', '861645032359721', 1, 0, '2017-02-21 17:49:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `adsliders`
--
ALTER TABLE `adsliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon_requests`
--
ALTER TABLE `coupon_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offer_categories`
--
ALTER TABLE `offer_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `retailers`
--
ALTER TABLE `retailers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_providers`
--
ALTER TABLE `service_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shopping_categories`
--
ALTER TABLE `shopping_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `adsliders`
--
ALTER TABLE `adsliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `coupon_requests`
--
ALTER TABLE `coupon_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `offer_categories`
--
ALTER TABLE `offer_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `retailers`
--
ALTER TABLE `retailers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `service_providers`
--
ALTER TABLE `service_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `shopping_categories`
--
ALTER TABLE `shopping_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `hourly_inactive_cleanup` ON SCHEDULE EVERY '5:0' MINUTE_SECOND STARTS '2017-02-28 01:55:52' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM coupon_requests
      WHERE created_on <= DATE_SUB(NOW(), INTERVAL '5:00' MINUTE_SECOND)
        AND status = 0$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
