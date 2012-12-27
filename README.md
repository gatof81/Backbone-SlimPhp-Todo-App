todoToptal
==========
-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 27, 2012 at 08:20 PM
-- Server version: 5.5.25
-- PHP Version: 5.3.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `todoToptal`
--

-- --------------------------------------------------------

--
-- Table structure for table `todosList`
--

CREATE TABLE `todosList` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `userID` int(50) NOT NULL,
  `content` varchar(255) NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `remaining` int(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `priority` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=171 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `username` varchar(15) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(9, 'Diego Ferreyra', '202cb962ac59075b964b07152d234b70', 'diegof18@gmail.com');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `todosList`
--
ALTER TABLE `todosList`
  ADD CONSTRAINT `todoslist_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`);
