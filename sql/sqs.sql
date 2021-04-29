-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 29, 2021 at 11:43 AM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project0315`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `AdminID` int(4) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Phone` int(11) NOT NULL,
  PRIMARY KEY (`AdminID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AdminID`, `Username`, `Password`, `Email`, `Phone`) VALUES
(1, 'admin1', 'admin1', 'admin1@gmail.com', 123456),
(2, 'admin2', 'admin2', 'admin2@gmail.com', 123456),
(3, 'admin3', 'admin3', 'admin3@gmail.com', 123456),
(4, 'admin4', 'admin4', 'admin4@gmail.com', 123456),
(5, 'admin5', 'admin5', 'admin5@gmail.com', 123456);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
CREATE TABLE IF NOT EXISTS `booking` (
  `BookingID` int(4) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(4) NOT NULL,
  `RoomID` int(4) NOT NULL,
  `DateStart` date NOT NULL,
  `DateFinish` date NOT NULL,
  `OrderStatus` varchar(10) NOT NULL,
  `TotalAmount` float NOT NULL,
  PRIMARY KEY (`BookingID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`BookingID`, `CustomerID`, `RoomID`, `DateStart`, `DateFinish`, `OrderStatus`, `TotalAmount`) VALUES
(1, 1, 1, '2021-03-01', '2021-03-23', 'yes', 1000),
(2, 2, 3, '2021-03-03', '2021-03-23', 'yes', 2000),
(3, 2, 2, '2021-03-22', '2021-03-26', '', 111),
(4, 1, 1, '2021-01-01', '2021-02-01', '', 100),
(5, 1, 1, '2021-03-23', '2021-03-25', '0', 11),
(6, 2, 2, '2021-03-16', '2021-03-28', '0', 11);

-- --------------------------------------------------------

--
-- Table structure for table `bookingitem`
--

DROP TABLE IF EXISTS `bookingitem`;
CREATE TABLE IF NOT EXISTS `bookingitem` (
  `BookingItemID` int(4) NOT NULL AUTO_INCREMENT,
  `RoomID` int(4) NOT NULL,
  `Availability` varchar(10) NOT NULL,
  `Number` int(2) NOT NULL,
  PRIMARY KEY (`BookingItemID`),
  KEY `Constraint3` (`RoomID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bookingitem`
--

INSERT INTO `bookingitem` (`BookingItemID`, `RoomID`, `Availability`, `Number`) VALUES
(1, 1, '1', 1),
(2, 2, '2', 1),
(3, 3, '3', 1),
(4, 4, '4', 1),
(5, 5, '5', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `CustomerID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Rol` enum('user','admin') NOT NULL DEFAULT 'user',
  `Pass` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Phone` int(11) NOT NULL,
  PRIMARY KEY (`CustomerID`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `Username`, `Rol`, `Pass`, `Email`, `Phone`) VALUES
(1, 'customer1', 'user', 'customer1', 'customer1@gmail.com', 111111),
(2, 'customer2', 'user', 'customer2', 'customer2@gmail.com', 111111),
(3, 'customer3', 'user', 'customer3', 'customer3@gmail.com', 111111),
(4, 'customer4', 'user', 'customer4', 'customer4@gmail.com', 111111),
(5, 'customer5', 'user', 'customer5', 'customer5@gmail.com', 111111),
(8, '0', 'admin', 'aaa', 'aa@gmail.com', 222222),
(9, '0', 'admin', 'bbb', 'bb@gmail.com', 222222),
(10, 'ccc', 'admin', 'ccc', 'cc@gmail.com', 222222),
(11, 'qqq', 'admin', 'qqq', 'qq@gmail.com', 111),
(12, 'www', 'admin', 'www', 'ww@gmail.com', 22222),
(13, 'wei', 'admin', 'wei', 'ei@gmail.com', 123456),
(14, 'customer', 'user', 'customer6', 'ustomer6@gmail.com', 121212),
(15, 'customer7', 'user', 'customer7', 'customer7@gmail.com', 11111),
(16, 'customer7', 'user', 'customer7', 'customer7@gmail.com', 11111),
(17, 'admin', 'admin', 'admin', 'admin@gmail.com', 11111),
(18, 'customer9', 'user', 'customer9', 'customer9@gmail.com', 999999),
(19, 'customer10', 'user', 'customer10', 'customer10@gmail.com', 111),
(20, 'customer11', 'user', 'customer11', 'customer11@gmail.com', 111111),
(21, 'customer11', 'user', 'customer11', 'customer11@gmail.com', 111111);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
CREATE TABLE IF NOT EXISTS `room` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `RoomPic` varchar(50) NOT NULL,
  `RoomDes` varchar(50) NOT NULL,
  `RoomPrice` int(10) NOT NULL,
  `RoomNumber` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `RoomPic`, `RoomDes`, `RoomPrice`, `RoomNumber`) VALUES
(1, 'images/picture1.jpg', 'Double-Size Room', 100, 3),
(2, 'images/picture2.jpg', 'Single Size Room', 22, 2),
(3, 'images/picture3.jpg', 'Family Room.', 3, 3),
(4, 'picture4', 'description4', 44, 44),
(5, 'picture5', 'description5', 5333, 5),
(8, '2222', '1111', 1111, 1111);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookingitem`
--
ALTER TABLE `bookingitem`
  ADD CONSTRAINT `Constraint3` FOREIGN KEY (`RoomID`) REFERENCES `room` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
