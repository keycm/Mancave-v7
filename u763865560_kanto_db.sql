-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 30, 2025 at 05:33 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u763865560_kanto_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `is_active`, `created_at`) VALUES
(1, 'Our Schedule!', '<p><strong>December 20 to 23</strong><br>Open 8:00am - 7:00pm</p><p><strong>December 24</strong><br>Open 7:00am - 7:00pm</p><p><strong>December 25</strong><br>Closed. Merry Christmas!</p>', 1, '2025-11-14 03:03:51'),
(2, 'Our Schedule!', 'HIIIIII', 0, '2025-11-14 03:13:39'),
(3, 'Jeremiah', 'KALBO PO KAYO LAHAT', 0, '2025-11-14 03:14:28'),
(4, 'It\'s Ber Months!', 'Happy Ber Months!', 0, '2025-11-14 03:55:07'),
(5, 'New Announcement!', 'Libre pag kalbo ang gupit!!!', 0, '2025-11-14 03:57:24'),
(6, 'NewAnnouncement!', 'Libre pag kalbo ang gupit!!!', 0, '2025-11-14 04:03:21'),
(7, 'New Announcement!', 'Libre pag kalbo ang gupit!!!', 0, '2025-11-14 04:04:15'),
(8, 'Announcement!', 'Libre pag kalbo ang gupit!!!', 0, '2025-11-14 05:47:31'),
(9, 'Announcement!', 'libre', 0, '2025-11-14 07:42:42');

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `style` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `quote` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`id`, `name`, `style`, `bio`, `quote`, `image_path`, `created_at`) VALUES
(3, 'Vince', 'Abstract Expressionism', 'A fashionate painter.', 'life is a mess, but painting is a must.', '1764427501_artist_img-2.jpg', '2025-11-29 14:45:01');

-- --------------------------------------------------------

--
-- Table structure for table `artist_likes`
--

CREATE TABLE `artist_likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `artist_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artist_likes`
--

INSERT INTO `artist_likes` (`id`, `user_id`, `artist_id`, `created_at`) VALUES
(2, 1, 1, '2025-11-29 08:41:20'),
(3, 1, 2, '2025-11-29 11:35:24');

-- --------------------------------------------------------

--
-- Table structure for table `artworks`
--

CREATE TABLE `artworks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `artist` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('Available','Reserved','Sold') DEFAULT 'Available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artworks`
--

INSERT INTO `artworks` (`id`, `title`, `artist`, `description`, `price`, `image_path`, `status`, `created_at`) VALUES
(7, 'Starryyy', 'Vince', 'The best among the rest.', 10000.00, '1764427599_img-12.jpg', 'Available', '2025-11-29 14:46:39');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `artwork_id` int(11) DEFAULT NULL,
  `service` varchar(100) DEFAULT NULL,
  `vehicle_type` varchar(100) DEFAULT NULL,
  `vehicle_model` varchar(100) DEFAULT NULL,
  `preferred_date` date DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `artwork_id`, `service`, `vehicle_type`, `vehicle_model`, `preferred_date`, `full_name`, `phone_number`, `special_requests`, `status`, `created_at`, `deleted_at`) VALUES
(52, 7, NULL, 'full paint job', 'sendan', '2010', '2025-11-08', 'jem', '09999999999', 'full', 'approved', '2025-10-01 15:28:00', '2025-11-17 22:03:47'),
(53, 7, NULL, 'retouch', 'sendan', '2', '2025-10-14', 'adasdasd', '1', 'wqe', 'approved', '2025-10-12 03:16:58', '2025-11-17 22:09:06'),
(54, 7, NULL, 'jjjj', 'sendan', '1', '2025-10-13', 'wqe', '09342423424', 'we', 'completed', '2025-10-12 03:21:57', NULL),
(56, 33, NULL, 'kalbo', '1:30 PM', '', '2025-11-14', 'Unknown User', '', 'thank you\r\n', 'rejected', '2025-11-14 01:52:37', '2025-11-17 22:09:14'),
(57, 34, NULL, 'SEMI kalbo', '2:00 PM', '', '2025-11-14', 'Kanto', '', 'Burst facde', '', '2025-11-14 03:30:29', NULL),
(58, 34, NULL, 'KAllllllbo', '10:30 AM', '', '2025-11-14', 'Kanto', '', 'Thank you', 'approved', '2025-11-14 03:31:54', '2025-11-17 22:03:49'),
(59, 34, NULL, '', '', '', '2025-11-14', 'Kanto', '', '', 'approved', '2025-11-14 03:42:51', '2025-11-17 22:09:13'),
(60, 34, NULL, '', '', '', '2025-11-14', 'Kanto', '', '', 'approved', '2025-11-14 03:43:06', '2025-11-17 22:09:11'),
(61, 34, NULL, '', '', '', '2025-11-14', 'Kanto', '', '', 'approved', '2025-11-14 03:43:12', '2025-11-17 22:09:09'),
(62, 34, NULL, '', '', '', '2025-11-14', 'Kanto', '', '', 'rejected', '2025-11-14 03:43:19', '2025-11-14 15:00:04'),
(63, 36, NULL, 'SEMI kalbo', '10:00 AM', '', '2025-11-19', 'Jem', '', 'aojsdasdsa', 'rejected', '2025-11-19 03:48:44', NULL),
(64, 1, NULL, 'Girl with a Pearl Earring', '', '', '2025-11-30', 'Vincent paul Pena', '09334257317', 'I want this \r\n', '', '2025-11-29 03:58:29', NULL),
(65, 1, NULL, '', '', '', '2025-12-05', '', '', '', 'pending', '2025-11-29 04:02:28', NULL),
(66, 1, NULL, 'Girl with a Pearl Earring', '', '', '2025-11-30', 'Vincent paul Pena', '09334257317', 'selkjtredfghjgggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg', '', '2025-11-29 04:03:11', NULL),
(67, 1, NULL, 'Girl with a Pearl Earring', '', '', '2025-12-01', 'Vincent paul Pena', '09334257317', 'thank you', '', '2025-11-29 05:02:55', NULL),
(68, 1, NULL, 'meee', '', '', '2025-11-30', 'Vincent paul Pena', '09334257317', 'geee', '', '2025-11-29 05:18:03', NULL),
(69, 1, NULL, '', '', '', '2025-12-01', '', '', '', '', '2025-11-29 05:23:30', NULL),
(70, 1, NULL, '', '', '', '2025-12-04', '', '', '', '', '2025-11-29 07:41:36', '2025-11-29 18:19:04'),
(71, 1, NULL, 'meee', '', '', '2025-11-30', 'Vincent paul Pena', '09334257317', 'thank you ', '', '2025-11-29 07:48:48', NULL),
(72, 1, NULL, 'meee', '', '', '2025-11-29', 'Vincent paul Pena', '09334257317', 'geee', 'approved', '2025-11-29 07:50:51', '2025-11-29 18:13:34'),
(73, 40, 7, 'Starryyy', '', '', '2025-12-06', '', '', '', 'approved', '2025-11-29 14:49:49', NULL),
(74, 1, 7, 'Starryyy', '', '', '2025-11-30', 'VIncent paul Pena', '09334257317', 'hjiopojh', 'approved', '2025-11-29 15:38:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` varchar(50) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `artwork_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `artwork_id`, `created_at`) VALUES
(17, 40, 7, '2025-11-29 14:50:02'),
(19, 1, 7, '2025-11-29 14:55:06');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `image_path`, `description`, `uploaded_at`) VALUES
(39, 'uploads/1753622886_Media (8).jpg', 'Door Trunk Refinishing', '2025-07-27 13:28:06'),
(40, 'uploads/1753622891_Media (9).jpg', 'Side Bumper Restoring', '2025-07-27 13:28:11'),
(41, 'uploads/1753622896_Media (10).jpg', 'Bumper  Restoring', '2025-07-27 13:28:16'),
(42, 'uploads/1753622901_Media (11).jpg', 'Full Paint Job', '2025-07-27 13:28:21'),
(43, 'uploads/1753622906_Media (12).jpg', 'Refinishing', '2025-07-27 13:28:26'),
(44, 'uploads/1753622912_Media (13).jpg', 'Touching up and Mags Refinishing', '2025-07-27 13:28:32'),
(45, 'uploads/1753622916_Media (14).jpg', 'Full Paint Job', '2025-07-27 13:28:37'),
(46, 'uploads/1753622922_Media (15).jpg', 'Fairings Refinishing', '2025-07-27 13:28:42'),
(47, 'uploads/1753622926_Media (16).jpg', 'Hood  Restoring', '2025-07-27 13:28:46'),
(48, 'uploads/1753622931_Media (17).jpg', 'Bumper Restoring', '2025-07-27 13:28:51'),
(65, 'uploads/1753622871_Media (7).jpg', 'Side Bumper Restoring', '2025-10-14 12:49:48'),
(66, 'uploads/1753622866_Media (6).jpg', 'Changing Color Refinishing', '2025-11-14 07:01:21');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` enum('unread','read','replied') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `username`, `email`, `mobile`, `subject`, `message`, `attachment`, `status`, `created_at`, `deleted_at`) VALUES
(68, 'jem', 'jem@gmail.com', '', '', 'dasdsadasd', NULL, 'read', '2025-10-14 12:32:40', NULL),
(70, 'Guest', 'example@gmail.com', '29343934234', '', 'cjeicijdckcdndi', NULL, 'read', '2025-11-27 19:11:30', NULL),
(71, 'Guest', 'example@gmail.com', '29343934234', '', 'cjeicijdckcdndi', NULL, 'read', '2025-11-27 19:11:35', NULL),
(76, 'Keycm', 'keycm109@gmail.com', '29343934234', '', 'Hello, I am interested in requesting a copy or similar commission of the artwork: &quot;Girl with a Pearl Earring&quot;. Please contact me with details.', NULL, 'unread', '2025-11-29 08:53:33', NULL),
(77, 'Keycm', 'keycm109@gmail.com', '29343934234', '', 'Hello, I am interested in requesting a copy or similar commission of the artwork: &quot;Girl with a Pearl Earring&quot;. Please contact me with details.', NULL, 'read', '2025-11-29 08:53:40', NULL),
(92, 'Isaac Jed Macaraeg', 'isaacjedm@gmail.com', '09942170085', '', 'I want to reserve a painting', NULL, 'read', '2025-11-29 14:52:38', NULL),
(93, 'Isaac Jed Macaraeg', 'isaacjedm@gmail.com', '09942170085', '', 'I want to reserve a painting', NULL, 'read', '2025-11-29 14:53:38', NULL),
(94, 'Guest', 'keycm109@gmail.com', '09334257317', '', 'sdfghjk', NULL, 'read', '2025-11-29 15:37:37', NULL),
(95, 'Isaac Jed Macaraeg', 'isaacjedm@gmail.com', '09942170085', '', 'I want to reserve a painting', NULL, 'read', '2025-11-29 16:21:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(39, 1, 'Your booking has been approved.', 1, '2025-11-29 04:01:59'),
(40, 1, 'Your booking has been approved.', 1, '2025-11-29 04:10:50'),
(41, 1, 'Your booking has been marked as completed. Thank you!', 1, '2025-11-29 04:10:55'),
(42, 1, 'Your booking has been approved.', 1, '2025-11-29 05:03:05'),
(43, 1, 'Your booking has been marked as completed. Thank you!', 1, '2025-11-29 05:03:07'),
(44, 1, 'Your booking has been approved.', 1, '2025-11-29 05:18:25'),
(45, 1, 'Your booking has been marked as completed. Thank you!', 1, '2025-11-29 05:18:53'),
(46, 1, 'Your booking has been approved.', 1, '2025-11-29 05:23:38'),
(47, 1, 'Your booking has been approved.', 1, '2025-11-29 07:41:42'),
(48, 1, 'Your booking has been marked as completed. Thank you!', 1, '2025-11-29 07:41:57'),
(52, 1, 'Your booking has been marked as completed. Thank you!', 1, '2025-11-29 10:26:11'),
(53, 1, 'Your booking has been marked as completed. Thank you!', 1, '2025-11-29 10:30:11'),
(55, 1, 'Your booking has been approved. Please check your email for collection details.', 0, '2025-11-29 15:58:54'),
(56, 40, 'Your booking has been approved. Please check your email for collection details.', 1, '2025-11-29 16:02:11');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trash_bin`
--

CREATE TABLE `trash_bin` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `source` enum('bookings','services','gallery','inquiries') NOT NULL,
  `deleted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trash_bin`
--

INSERT INTO `trash_bin` (`id`, `item_id`, `item_name`, `source`, `deleted_at`) VALUES
(141, 62, 'Kanto|{\"service_name\":\"\",\"vehicle_type\":\"\",\"vehicle_model\":\"\",\"full_name\":\"Kanto\",\"phone\":\"\",\"special_request\":\"\",\"username\":\"Kanto\",\"email\":\"keycm109@gmail.com\"}', 'bookings', '2025-11-14 15:00:04'),
(142, 8, 'Spot Repair|{\"description\":\"Professional touch-up for scratches, dings, and small damaged areas to restore your car\'s finish.\",\"price\":\"3000.00\",\"duration\":\"1-2 Days\",\"image\":\"Media (7).jpg\"}', 'services', '2025-11-14 15:00:21'),
(143, 52, 'jem|{\"service_name\":\"full paint job\",\"vehicle_type\":\"sendan\",\"vehicle_model\":\"2010\",\"full_name\":\"jem\",\"phone\":\"09999999999\",\"special_request\":\"full\",\"username\":\"\",\"email\":\"\"}', 'bookings', '2025-11-17 22:03:46'),
(144, 58, 'Kanto|{\"service_name\":\"KAllllllbo\",\"vehicle_type\":\"10:30 AM\",\"vehicle_model\":\"\",\"full_name\":\"Kanto\",\"phone\":\"\",\"special_request\":\"Thank you\",\"username\":\"Kanto\",\"email\":\"keycm109@gmail.com\"}', 'bookings', '2025-11-17 22:03:49'),
(145, 53, 'adasdasd|{\"service_name\":\"retouch\",\"vehicle_type\":\"sendan\",\"vehicle_model\":\"2\",\"full_name\":\"adasdasd\",\"phone\":\"1\",\"special_request\":\"wqe\",\"username\":\"\",\"email\":\"\"}', 'bookings', '2025-11-17 22:09:06'),
(146, 61, 'Kanto|{\"service_name\":\"\",\"vehicle_type\":\"\",\"vehicle_model\":\"\",\"full_name\":\"Kanto\",\"phone\":\"\",\"special_request\":\"\",\"username\":\"Kanto\",\"email\":\"keycm109@gmail.com\"}', 'bookings', '2025-11-17 22:09:09'),
(147, 60, 'Kanto|{\"service_name\":\"\",\"vehicle_type\":\"\",\"vehicle_model\":\"\",\"full_name\":\"Kanto\",\"phone\":\"\",\"special_request\":\"\",\"username\":\"Kanto\",\"email\":\"keycm109@gmail.com\"}', 'bookings', '2025-11-17 22:09:11'),
(148, 59, 'Kanto|{\"service_name\":\"\",\"vehicle_type\":\"\",\"vehicle_model\":\"\",\"full_name\":\"Kanto\",\"phone\":\"\",\"special_request\":\"\",\"username\":\"Kanto\",\"email\":\"keycm109@gmail.com\"}', 'bookings', '2025-11-17 22:09:13'),
(149, 56, 'Unknown User|{\"service_name\":\"kalbo\",\"vehicle_type\":\"1:30 PM\",\"vehicle_model\":\"\",\"full_name\":\"Unknown User\",\"phone\":\"\",\"special_request\":\"thank you\\r\\n\",\"username\":\"\",\"email\":\"\"}', 'bookings', '2025-11-17 22:09:14'),
(152, 34, 'trizone|{\"description\":\"werty\",\"price\":\"12.00\",\"duration\":\"20\",\"image\":\"1764360366_img-10.jpg\"}', 'services', '2025-11-29 04:13:49'),
(153, 7, 'Full Face Job|{\"description\":\"Complete transformation of your vehicle\'s appearance with our premium paint solutions.\",\"price\":\"4000.00\",\"duration\":\"2-3 Days\",\"image\":\"Media (11).jpg\"}', 'services', '2025-11-29 04:16:40'),
(167, 72, 'Vincent paul Pena|{\"id\":\"72\",\"user_id\":\"1\",\"artwork_id\":null,\"service\":\"meee\",\"vehicle_type\":\"\",\"vehicle_model\":\"\",\"preferred_date\":\"2025-11-29\",\"full_name\":\"Vincent paul Pena\",\"phone_number\":\"09334257317\",\"special_requests\":\"geee\",\"status\":\"approved\",\"cr', 'bookings', '2025-11-29 18:13:34'),
(169, 70, 'Booking:  - |{\"id\":\"70\",\"user_id\":\"1\",\"artwork_id\":null,\"service\":\"\",\"vehicle_type\":\"\",\"vehicle_model\":\"\",\"preferred_date\":\"2025-12-04\",\"full_name\":\"\",\"phone_number\":\"\",\"special_requests\":\"\",\"status\":\"\",\"created_at\":\"2025-11-29 15:41:36\",\"deleted_at\":null', 'bookings', '2025-11-29 18:19:04'),
(172, 6, 'NIGHT NIHTT|{\"id\":\"6\",\"title\":\"NIGHT NIHTT\",\"artist\":\"trizone\",\"description\":\"dfghjk\",\"price\":\"160.00\",\"image_path\":\"1764404934_img-10.jpg\",\"status\":\"Available\",\"created_at\":\"2025-11-29 10:06:48\"}', '', '2025-11-29 12:53:13'),
(173, 4, 'meee|{\"id\":\"4\",\"title\":\"meee\",\"artist\":\"Johannes Vermeer\",\"description\":\"sdf\",\"price\":\"120.00\",\"image_path\":\"1764393448_img-21.jpg\",\"status\":\"Available\",\"created_at\":\"2025-11-29 05:17:28\"}', '', '2025-11-29 12:53:15'),
(174, 3, 'Girl with a Pearl Earring|{\"id\":\"3\",\"title\":\"Girl with a Pearl Earring\",\"artist\":\"Johannes Vermeer\",\"description\":\"Oil painting.\",\"price\":\"18000.00\",\"image_path\":\"1763888922_img-10.jpg\",\"status\":\"Available\",\"created_at\":\"2025-11-22 14:56:00\"}', '', '2025-11-29 12:53:17'),
(175, 2, 'The Scream|{\"id\":\"2\",\"title\":\"The Scream\",\"artist\":\"Edvard Munch\",\"description\":\"Expressionist masterpiece.\",\"price\":\"25000.00\",\"image_path\":\"1763888960_img-21.jpg\",\"status\":\"Reserved\",\"created_at\":\"2025-11-22 14:56:00\"}', '', '2025-11-29 12:53:18'),
(176, 1, 'Starry Night|{\"id\":\"1\",\"title\":\"Starry Night\",\"artist\":\"Vincent Van Gogh\",\"description\":\"Oil on canvas.\",\"price\":\"15000.00\",\"image_path\":\"1763888841_img-12.jpg\",\"status\":\"Available\",\"created_at\":\"2025-11-22 14:56:00\"}', '', '2025-11-29 12:53:20'),
(177, 1, 'NIGHT|{\"id\":\"1\",\"title\":\"NIGHT\",\"event_date\":\"2025-11-30\",\"event_time\":\"6;00\",\"location\":\"fghgf\",\"created_at\":\"2025-11-28 20:12:12\"}', '', '2025-11-29 12:53:24'),
(178, 2, 'trizone|{\"id\":\"2\",\"name\":\"trizone\",\"style\":\"paint\",\"bio\":\"gfds\",\"quote\":\"giii\",\"image_path\":\"1764360747_artist_img-21.jpg\",\"created_at\":\"2025-11-29 10:07:06\"}', '', '2025-11-29 12:53:26'),
(179, 39, 'jem123|{\"id\":\"39\",\"username\":\"jem123\",\"email\":\"keycm109@gmail.com\",\"password\":\"$2y$10$VGoRNP2XvrIMk6EX7bhJ9OEfX2Tq5f.hDTPND6CvEkP6px\\/YI.Py6\",\"role\":\"user\",\"reset_token_hash\":null,\"reset_token_expires_at\":null,\"account_activation_hash\":null,\"image_path\":n', '', '2025-11-29 12:53:31'),
(180, 91, 'Keycm|{\"username\":\"Keycm\",\"email\":\"keycm109@gmail.com\",\"mobile\":\"29343934234\",\"message\":\"dfghjkl\",\"attachment\":null,\"status\":\"unread\",\"created_at\":\"2025-11-29 10:55:33\"}', 'inquiries', '2025-11-29 12:53:35'),
(181, 90, 'Keycm|{\"username\":\"Keycm\",\"email\":\"keycm109@gmail.com\",\"mobile\":\"29343934234\",\"message\":\"dfghjkl\",\"attachment\":null,\"status\":\"unread\",\"created_at\":\"2025-11-29 10:55:30\"}', 'inquiries', '2025-11-29 12:53:36'),
(182, 89, 'Keycm|{\"username\":\"Keycm\",\"email\":\"keycm109@gmail.com\",\"mobile\":\"29343934234\",\"message\":\"dfghjkl\",\"attachment\":null,\"status\":\"unread\",\"created_at\":\"2025-11-29 10:55:26\"}', 'inquiries', '2025-11-29 12:53:38'),
(183, 88, 'Keycm|{\"username\":\"Keycm\",\"email\":\"keycm109@gmail.com\",\"mobile\":\"29343934234\",\"message\":\"dfghjkl\",\"attachment\":null,\"status\":\"unread\",\"created_at\":\"2025-11-29 10:55:22\"}', 'inquiries', '2025-11-29 12:53:40'),
(184, 87, 'Keycm|{\"username\":\"Keycm\",\"email\":\"keycm109@gmail.com\",\"mobile\":\"29343934234\",\"message\":\"dfghjkl\",\"attachment\":null,\"status\":\"unread\",\"created_at\":\"2025-11-29 10:55:19\"}', 'inquiries', '2025-11-29 12:53:42'),
(185, 86, 'Keycm|{\"username\":\"Keycm\",\"email\":\"keycm109@gmail.com\",\"mobile\":\"29343934234\",\"message\":\"dfghjkl\",\"attachment\":null,\"status\":\"unread\",\"created_at\":\"2025-11-29 10:55:15\"}', 'inquiries', '2025-11-29 12:53:44'),
(186, 85, 'Keycm|{\"username\":\"Keycm\",\"email\":\"keycm109@gmail.com\",\"mobile\":\"29343934234\",\"message\":\"dfghjkl\",\"attachment\":null,\"status\":\"unread\",\"created_at\":\"2025-11-29 10:55:11\"}', 'inquiries', '2025-11-29 12:53:45'),
(187, 84, 'Keycm|{\"username\":\"Keycm\",\"email\":\"keycm109@gmail.com\",\"mobile\":\"29343934234\",\"message\":\"dfghjkl\",\"attachment\":null,\"status\":\"unread\",\"created_at\":\"2025-11-29 10:55:08\"}', 'inquiries', '2025-11-29 12:53:47'),
(188, 83, 'Keycm|{\"username\":\"Keycm\",\"email\":\"keycm109@gmail.com\",\"mobile\":\"29343934234\",\"message\":\"dfghjkl\",\"attachment\":null,\"status\":\"unread\",\"created_at\":\"2025-11-29 10:55:04\"}', 'inquiries', '2025-11-29 12:53:49'),
(189, 82, 'Keycm|{\"username\":\"Keycm\",\"email\":\"keycm109@gmail.com\",\"mobile\":\"29343934234\",\"message\":\"dfghjkl\",\"attachment\":null,\"status\":\"unread\",\"created_at\":\"2025-11-29 10:55:01\"}', 'inquiries', '2025-11-29 12:53:51'),
(190, 81, 'Keycm|{\"username\":\"Keycm\",\"email\":\"keycm109@gmail.com\",\"mobile\":\"29343934234\",\"message\":\"dfghjkl\",\"attachment\":null,\"status\":\"unread\",\"created_at\":\"2025-11-29 10:54:57\"}', 'inquiries', '2025-11-29 12:53:53'),
(191, 79, 'Guest|{\"username\":\"Guest\",\"email\":\"example@gmail.com\",\"mobile\":\"29343934234\",\"message\":\"cjeicijdckcdndi\",\"attachment\":null,\"status\":\"unread\",\"created_at\":\"2025-11-27 19:11:42\"}', 'inquiries', '2025-11-29 12:53:55'),
(192, 80, 'Jermin|{\"username\":\"Jermin\",\"email\":\"jerminmercado1@gmail.com\",\"mobile\":\"\",\"message\":\"how about this \",\"attachment\":\"uploads\\/inquiries\\/1.jpg\",\"status\":\"replied\",\"created_at\":\"2025-10-12 05:43:31\"}', 'inquiries', '2025-11-29 12:53:59'),
(193, 69, 'Jermin|{\"username\":\"Jermin\",\"email\":\"jerminmercado1@gmail.com\",\"mobile\":\"\",\"message\":\"cabyou give nme the\",\"attachment\":null,\"status\":\"read\",\"created_at\":\"2025-10-12 05:43:08\"}', 'inquiries', '2025-11-29 12:54:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `account_activation_hash` varchar(64) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `reset_token_hash`, `reset_token_expires_at`, `account_activation_hash`, `image_path`) VALUES
(1, 'Keycm', 'penapaul858@gmail.com', '$2y$10$uLXGTKqqRQgVsXBwO89aHeI7L9NdNURnqiIt8NBbKUl8Z2IVOFi1.', 'admin', 'b9196683c40dd7cecb4dfc8ffc9ed64d4bc192f126d9d771706e791726582b26', '2025-11-29 16:05:41', NULL, '1764414221_img-17.jpg'),
(40, 'Isaac Jed Macaraeg', 'isaacjedm@gmail.com', '$2y$10$u36UOTtqy6kctr.lwCRNL.M06xvl6wCZcMvyq3FVxghDVSJODtDhu', 'user', NULL, NULL, NULL, '1764427752_makima.jpg'),
(41, 'Jinzo', 'valencia04jeremiah29@gmail.com', '$2y$10$LPqw/E1MrcScBQJGvmGadOlFuQx4QIPOcqcC1GXEk/y7zgLxcW2a.', 'user', NULL, '2025-11-29 16:33:43', '552925', NULL),
(42, 'note', 'crunchybox321@gmail.com', '$2y$10$.hRqiFZmPlNXghRibEYQqe/hrDXhlOs3VRQKXveJDjZMjsbIAkbnu', 'user', NULL, '2025-11-29 16:51:41', '579419', NULL),
(43, 'elle', 'valenciajeremiah29@gmail.com', '$2y$10$FCi2lBiFauIS3OLGS2ykne4jxQnk35rpYeCg.JpV4d06UdPIHHGZC', 'user', NULL, '2025-11-29 16:53:29', '746617', NULL),
(44, 'isaac jed', 'vibrancy0616@gmail.com', '$2y$10$Smd3GKg9FBkdz4Ko2cH6o.8IsE/Yxzv1DfMKnKOflYrEckmdEQHai', 'user', NULL, '2025-11-29 16:56:03', '553068', NULL),
(45, 'isaacjed', 'gnc.isaacjedm@gmail.com', '$2y$10$fVyq7VeGoVVKD/I1l6RiiuO70RXgqNoKjskMSC8gz/eHW28Z69Gpq', 'user', NULL, '2025-11-29 17:16:41', '150025', NULL),
(46, 'jann kyle', 'yuta.zzz06@gmail.com', '$2y$10$ZaaoBPdkVY2f2rtj7OC9m.Sa1nLiWk8E/IfeURjK33ySO2Wy7WCbW', 'user', NULL, '2025-11-29 17:21:56', '752455', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `artist_likes`
--
ALTER TABLE `artist_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_artist_like` (`user_id`,`artist_id`);

--
-- Indexes for table `artworks`
--
ALTER TABLE `artworks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `artwork_id` (`artwork_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trash_bin`
--
ALTER TABLE `trash_bin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `reset_token_hash` (`reset_token_hash`),
  ADD UNIQUE KEY `account_activation_hash` (`account_activation_hash`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `artist_likes`
--
ALTER TABLE `artist_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `artworks`
--
ALTER TABLE `artworks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `trash_bin`
--
ALTER TABLE `trash_bin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`artwork_id`) REFERENCES `artworks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
