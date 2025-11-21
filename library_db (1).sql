-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 01, 2025 at 05:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `email`, `password`) VALUES
(1, 'dinuki', 'dinuki@gmail.com', '$2y$10$TKVSuo.Qaq4Kolnp2bYl/eDAwDTVbztSXqYdIQiWFRhAkHgghpuW6');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `book_name` varchar(255) NOT NULL,
  `book_category` varchar(100) NOT NULL,
  `book_number` varchar(50) NOT NULL,
  `books_available` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `book_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `book_name`, `book_category`, `book_number`, `books_available`, `book_image`) VALUES
(1, 'ඒ දවස් හරි වෙනස්', 'Novel', '1000', 'Yes', '1755969593_e dawas hari wenas.jpg'),
(2, 'forget me not', 'Novel', '1001', 'Yes', '1755969653_froget me not.jpeg'),
(3, 'ඉන්ද්‍රනීල මාණික්‍යය', 'Novel', '1002', 'Yes', '1755969719_ingranila manikya.jpg'),
(4, 'Software engineering ', 'Software engineering ', '1003', 'Yes', '1755969797_software engineering.jpeg'),
(5, 'Software engineering ', 'Software engineering ', '1004', 'No', '1755969843_software engineering 2.jpeg'),
(6, 'Harry Potter', 'Novel', '1005', 'Yes', '1755969899_harry potter.jpeg'),
(7, 'mechanical engineering design', 'mechanical engineering design', '1006', 'Yes', '1755969949_mechanical engineering desing.jpeg'),
(8, 'Ruskin BOND', 'Novel', '1007', 'Yes', '1755970017_ruskin bond.png'),
(9, 'Other Names for Love', 'Novel', '1008', 'Yes', '1755970069_Other names for love.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `book_orders`
--

CREATE TABLE `book_orders` (
  `id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `book_name` varchar(100) NOT NULL,
  `book_category` varchar(100) NOT NULL,
  `book_number` varchar(50) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `book_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_orders`
--

INSERT INTO `book_orders` (`id`, `student_name`, `book_name`, `book_category`, `book_number`, `contact_number`, `email`, `book_date`) VALUES
(1, 'dinuki', 'Html', 'software', '1000', '1234567890', 'dinuki@gmail.com', '2025-08-13'),
(3, 'dilki', 'php', 'software', '1001', '3423543634', 'ayodyani@gmail.com', '2025-06-04');

-- --------------------------------------------------------

--
-- Table structure for table `popular_books`
--

CREATE TABLE `popular_books` (
  `id` int(11) NOT NULL,
  `book_name` varchar(255) NOT NULL,
  `book_category` varchar(255) NOT NULL,
  `book_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `popular_books`
--

INSERT INTO `popular_books` (`id`, `book_name`, `book_category`, `book_image`) VALUES
(1, 'forget me not', 'Novel', '1755970198_1755969653_froget me not.jpeg'),
(2, 'ඒ දවස් හරි වෙනස්', 'Novel', '1755970223_e dawas hari wenas.jpg'),
(3, 'Harry Potter', 'Novel', '1755970246_1755969899_harry potter.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `students_say`
--

CREATE TABLE `students_say` (
  `id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `student_comment` text NOT NULL,
  `student_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students_say`
--

INSERT INTO `students_say` (`id`, `student_name`, `student_comment`, `student_image`) VALUES
(4, 'ayodyani', 'I like It', '1755099177_WhatsApp_Image_2025-06-09_at_22.06.14-removebg-preview.png'),
(6, 'ann', 'helpful', '1755970468_unnamed.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'dinuki', 'dinuki@gmail.com', '$2y$10$TprSkqPj/UtC3G4sZ4t2reZQHJlm.KsQJyoTNrVbaUrgt0Y9ogqCG', '2025-08-13 16:30:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_orders`
--
ALTER TABLE `book_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `popular_books`
--
ALTER TABLE `popular_books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students_say`
--
ALTER TABLE `students_say`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `book_orders`
--
ALTER TABLE `book_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `popular_books`
--
ALTER TABLE `popular_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students_say`
--
ALTER TABLE `students_say`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
