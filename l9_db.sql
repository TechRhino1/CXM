-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2022 at 01:59 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `l9_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$5VOeE8r8K9WhRM.WAWsLv.7uxVblVCRrcasQHoYtUiWbwFzoxAlBO', '2022-07-28 01:41:56', '2022-07-28 01:41:56'),
(2, 'admin', 'admin1@gmail.com', '$2y$10$PjDwLIzT2Jp3zTyCnKdteOlljPb8stW5ZUpoFQO0UU6OjC7O352oe', '2022-07-28 03:59:41', '2022-07-28 03:59:41');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_07_28_040619_create_admins_table', 1),
(6, '2022_07_28_040716_create_subadmins_table', 1),
(7, '2022_07_29_051428_create_tasks_table', 2),
(8, '2022_07_29_103435_create_projects_table', 3),
(9, '2022_07_29_114806_create_user_leaves_table', 4),
(10, '2022_08_03_051401_create_sign_in_outs_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `Description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Billing` decimal(8,2) NOT NULL,
  `BillingType` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TotalHours` int(11) NOT NULL,
  `Status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `HourlyINR` int(11) NOT NULL,
  `Currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `StartDate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `EndDate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TotalClientHours` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Comments` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `InternalComments` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ClientID` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `Description`, `Billing`, `BillingType`, `TotalHours`, `Status`, `HourlyINR`, `Currency`, `StartDate`, `EndDate`, `TotalClientHours`, `UserID`, `Comments`, `InternalComments`, `ClientID`, `created_at`, `updated_at`) VALUES
(2, 'Testproject', '1.00', 'M', 16, '2021-01-06', 13, 'AED', '2021-01-06', '111', 100, 1, 'hggujguiuug', '2', 2, '2022-07-29 05:51:33', '2022-07-29 05:51:48'),
(3, 'Angular6', '1245.00', 'M', 106, '2021-01-06', 13, 'AED', '2021-01-06', '111', 100, 1, 'hggujguiuug', '2', 2, '2022-07-29 05:51:39', '2022-07-29 05:51:39');

-- --------------------------------------------------------

--
-- Table structure for table `sign_in_outs`
--

CREATE TABLE `sign_in_outs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `EVENTDATE` date NOT NULL,
  `SIGNIN_TIME` time NOT NULL DEFAULT '00:00:00',
  `CREATEDSIGNIN_DATE` date NOT NULL,
  `CREATEDSIGNIN_TIME` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '00:00',
  `SIGNOUT_TIME` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '00:00',
  `CREATEDSIGNOUT_DATE` date DEFAULT NULL,
  `CREATEDSIGNOUT_TIME` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '00:00',
  `TotalMins` int(11) NOT NULL DEFAULT 0,
  `TotalTaskMins` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sign_in_outs`
--

INSERT INTO `sign_in_outs` (`id`, `user_id`, `EVENTDATE`, `SIGNIN_TIME`, `CREATEDSIGNIN_DATE`, `CREATEDSIGNIN_TIME`, `SIGNOUT_TIME`, `CREATEDSIGNOUT_DATE`, `CREATEDSIGNOUT_TIME`, `TotalMins`, `TotalTaskMins`, `created_at`, `updated_at`) VALUES
(1, 1, '2022-08-03', '00:09:00', '2022-08-04', '00:00', '1600', '2022-08-01', '2021-01-03', 800, 800, '2022-08-03 07:06:18', '2022-08-03 07:06:18'),
(2, 2, '2021-01-06', '00:09:00', '2022-08-03', '2022-08-03', '1600', '2022-08-01', '2021-01-03', 800, 800, '2022-08-03 07:10:33', '2022-08-03 07:10:33'),
(3, 2, '2022-08-04', '00:09:00', '2022-08-03', '09:05', '1600', '2022-08-01', '2021-01-03', 800, 800, '2022-08-03 07:11:15', '2022-08-03 07:11:15'),
(4, 3, '2021-01-06', '00:09:00', '2022-08-03', '09:05', '1600', '2022-08-01', '2021-01-03', 800, 800, '2022-08-03 07:14:50', '2022-08-03 07:14:50');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `Title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ProjectID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CreaterID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `EstimatedDate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `EstimatedTime` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Priority` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CurrentStatus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `InitiallyAssignedToID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CurrentlyAssignedToID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CompletedDate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CompletedTime` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ParentID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `Title`, `Description`, `ProjectID`, `CreaterID`, `EstimatedDate`, `EstimatedTime`, `Priority`, `CurrentStatus`, `InitiallyAssignedToID`, `CurrentlyAssignedToID`, `CompletedDate`, `CompletedTime`, `ParentID`, `created_at`, `updated_at`) VALUES
(1, 'MaterialDesignAngular', 'AngularMaterial', '110', '1', '2021-01-06', '13:44', 'High', 'complected', '111', '100', '2021-01-06 13:44', '13:44', '1', '2022-07-29 02:15:03', '2022-07-29 02:15:03'),
(2, 'Angular1', 'Material', '120', '2', '2021-01-06', '13:44', 'High', 'complected', '111', '100', '2021-01-06 13:44', '13:44', '2', '2022-07-29 02:59:11', '2022-07-29 04:14:01'),
(3, 'Angular3', 'Material', '120', '3', '2021-01-06', '13:44', 'High', 'complected', '111', '100', '2021-01-06 13:44', '13:44', '2', '2022-07-29 03:19:04', '2022-07-29 04:14:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'user', 'user@gmail.com', '$2y$10$SxCizNC8Qn.Ja/tmEGjKauQsW7o.07txeIFI8strLJf8zvT/I7uM.', 1, '2022-08-04 00:46:47', '2022-08-04 00:46:47'),
(2, 'Admin', 'Admin2@gmail.com', '$2y$10$7/N9UU8S9DDyMhesutYqX.6t1z82sFE0iFuzwcXnCSz.cqBtwuzTG', 0, '2022-08-04 01:03:04', '2022-08-04 01:03:04'),
(3, 'testuser', 'testuser@gmail.com', '$2y$10$JTEymfTXkNnM14OOZFTs9uEQZWv9ftv7SBJy9oY2TrIViMTzKV2NK', 1, '2022-08-04 01:57:02', '2022-08-04 01:57:02');

-- --------------------------------------------------------

--
-- Table structure for table `user_leaves`
--

CREATE TABLE `user_leaves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `UserID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DateFrom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DateTo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ApprovalStatus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ApprovedUserID` int(11) NOT NULL,
  `ApprovedDate` date NOT NULL,
  `ApprovalComments` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_leaves`
--

INSERT INTO `user_leaves` (`id`, `UserID`, `DateFrom`, `DateTo`, `Reason`, `ApprovalStatus`, `ApprovedUserID`, `ApprovedDate`, `ApprovalComments`, `created_at`, `updated_at`) VALUES
(2, '2', '2021-01-06', '2021-01-08', 'fever', 'ok', 1, '2021-01-06', 'qwertyu', '2022-08-02 23:36:12', '2022-08-02 23:36:12'),
(3, '3', '2021-01-06', '2021-01-08', 'virus', 'ok', 1, '2021-01-06', 'qwertyu', '2022-08-02 23:37:00', '2022-08-02 23:37:00'),
(4, '3', '2021-01-06', '2021-01-08', 'virus', 'ok', 1, '2021-01-06', 'qwertyu', '2022-08-03 06:42:38', '2022-08-03 06:42:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sign_in_outs`
--
ALTER TABLE `sign_in_outs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_leaves`
--
ALTER TABLE `user_leaves`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sign_in_outs`
--
ALTER TABLE `sign_in_outs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_leaves`
--
ALTER TABLE `user_leaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
