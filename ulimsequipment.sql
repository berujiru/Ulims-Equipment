-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2016 at 03:44 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ulimsequipment`
--

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE IF NOT EXISTS `equipment` (
`ID` int(11) NOT NULL,
  `equipmentID` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `lab` int(11) NOT NULL,
  `specification` varchar(500) NOT NULL,
  `date_received` date NOT NULL,
  `received_by` int(11) NOT NULL,
  `amount` double NOT NULL,
  `supplier` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `usagestatus` tinyint(1) NOT NULL,
  `lengthofuse` date NOT NULL,
  `remarks` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`ID`, `equipmentID`, `name`, `description`, `lab`, `specification`, `date_received`, `received_by`, `amount`, `supplier`, `status`, `usagestatus`, `lengthofuse`, `remarks`) VALUES
(1, '12345', 'Balance Beam', 'Use for balancing the world', 1, 'specs here', '2016-02-01', 1, 25000, 1, 1, 0, '2016-02-02', 'use with care'),
(2, '22222', 'graduated cylnder', 'measuring graduates haha', 2, 'nothing', '2016-01-01', 2, 2000, 3, 2, 0, '2016-01-01', 'haaha');

-- --------------------------------------------------------

--
-- Table structure for table `equipmentcalibration`
--

CREATE TABLE IF NOT EXISTS `equipmentcalibration` (
`ID` int(11) NOT NULL,
  `usageID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `calibrationdata` text NOT NULL,
  `remarks` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `equipmentcalibration`
--

INSERT INTO `equipmentcalibration` (`ID`, `usageID`, `user_id`, `calibrationdata`, `remarks`) VALUES
(1, 1, 0, 'sample calibration data', 'sample remarks');

-- --------------------------------------------------------

--
-- Table structure for table `equipmentstatus`
--

CREATE TABLE IF NOT EXISTS `equipmentstatus` (
`ID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `equipmentstatus`
--

INSERT INTO `equipmentstatus` (`ID`, `name`, `description`) VALUES
(1, 'Available', 'Sample description here'),
(2, 'Not Available', 'Equipment didnt use');

-- --------------------------------------------------------

--
-- Table structure for table `equipmentusage`
--

CREATE TABLE IF NOT EXISTS `equipmentusage` (
`ID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `equipmentID` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  `startdate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `remarks` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `equipmentusage`
--

INSERT INTO `equipmentusage` (`ID`, `user_id`, `equipmentID`, `status`, `startdate`, `enddate`, `remarks`) VALUES
(1, 0, '1', 1, '2016-03-07 00:00:00', '2016-03-07 00:00:00', 'sample remarks'),
(2, 0, '22222', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'passed'),
(3, 0, '22222', 1, '2016-03-14 00:00:00', '2016-03-14 07:24:03', '22222 latest test grrr'),
(4, 0, '12345', 1, '2016-03-13 00:00:00', '0000-00-00 00:00:00', 'Hahaha it works'),
(5, 0, '22222', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Wahahahahaha'),
(6, 0, '12345', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'passed'),
(8, 0, '12345', 1, '2016-03-14 08:30:08', '2016-03-14 09:01:57', 'testing 1'),
(9, 0, '12345', 1, '2016-03-14 10:02:32', '2016-03-14 10:03:05', 'Start Usage: i need you\n End Usage: its a joke'),
(10, 0, '12345', 1, '2016-03-14 10:03:44', '2016-03-14 10:04:01', 'Start Usage: wew\n End Usage: hahaha');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `equipmentcalibration`
--
ALTER TABLE `equipmentcalibration`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `equipmentstatus`
--
ALTER TABLE `equipmentstatus`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `equipmentusage`
--
ALTER TABLE `equipmentusage`
 ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `equipmentcalibration`
--
ALTER TABLE `equipmentcalibration`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `equipmentstatus`
--
ALTER TABLE `equipmentstatus`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `equipmentusage`
--
ALTER TABLE `equipmentusage`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
