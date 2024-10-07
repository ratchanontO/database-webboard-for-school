-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 07, 2024 at 09:47 PM
-- Server version: 11.5.2-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webboardd`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commentid` int(11) NOT NULL,
  `postid` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `comment_text` text DEFAULT NULL,
  `comment_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`commentid`, `postid`, `userid`, `comment_text`, `comment_date`) VALUES
(111, 129, 40, 'เริ่มต้นเเล้วสินะครับว้าว.....\r\n', '2024-10-03 13:25:45'),
(113, 129, 41, 'ก็มาดิค้าบบบ', '2024-10-03 14:00:59'),
(119, 129, 42, 'เบียวนะเราอะ', '2024-10-03 14:10:18');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `postid` int(11) NOT NULL,
  `text` text NOT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`postid`, `text`, `date`, `userid`) VALUES
(114, '123\r\n', '2024-10-03 01:30:38', 31),
(129, 'สวัสดีครับ  วันนี้ผมไม่ได้จะมาสร้างกราม่าอะไรทั้งนั้นแต่ผมมาเริ่มต้นสงคราม!!!', '2024-10-03 13:24:08', 39),
(134, 'โย่วๆ', '2024-10-04 00:23:00', 32);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `privilege` int(1) NOT NULL,
  `username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `firstname`, `lastname`, `email`, `password`, `privilege`, `username`) VALUES
(32, 'รัตชานนท์', 'โอโสแวน', 'ratchanont2547s@gmail.com', '$2y$10$aVIoAYyxiIvBs/6geYNwKul86mSxKMKYgwbWjAJO2.Mvl65FB8MJq', 2, 'nong3'),
(39, 'นิคิกร', 'นามสกุล', 'nitikorn@gmail.com', '$2y$10$KLV6/kJq7fqA8Mf.4FpmreOcfYKa55EuP/y80gjQDs0OhusBAfegi', 1, 'nitikorn'),
(40, 'ชัยตะรูด', 'มาซอก', 'chaiwat@gmail.com', '$2y$10$726zkPlfScZZz9CxTmgX.OtCQKGTNkeE2IxIPv2X/mjJ3dnr/.fBe', 1, 'chaiwat'),
(41, 'Karamail', 'Kana', 'teennoiias@gmail.com', '$2y$10$cA/S3VrpKvhfFES0Bl50s.E8LMg95RL358ScDNeMcqZwC29meAXaC', 1, 'mark'),
(42, 'ahhha', 'haahh', 'dsss@gmail.com', '$2y$10$NuJ2vK5GAO7mCYp0nae0Ge1daWxk6pi6BIRuVKrD/a/LYz6rh2ixG', 1, 'sixmyduck'),
(43, 'nn', 'nn', '123@gmail.com', '$2y$10$dASlbLuPZowpCNj/vPrvT.fYe81qR7urZD6PGHuouZRXRhJeeb4Ii', 1, 'na'),
(44, 'ืma', 'ma', 'mark123@gmail.com', '$2y$10$tKgB4q2v5i5.yJuP0DaG2uwGV.ZmOc4KNTa76FlqzUTipOFKXhSEy', 1, 'karama'),
(45, 'รัตชานนท์ท', 'โอโสแวนน', 'rat@gmail.com', '$2y$10$8at1kcOtsFBVqmuWXcD83u4Fq.t8TT.aV.FqVqBfJGX/dz/jMbG4.', 1, 'ahhlooo');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentid`),
  ADD KEY `postid` (`postid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`postid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `postid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`postid`) REFERENCES `posts` (`postid`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
