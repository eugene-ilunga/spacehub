-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 15, 2025 at 11:14 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spacekoi_1.0_installer`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_contents`
--

CREATE TABLE `about_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `sub_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `about_contents`
--

INSERT INTO `about_contents` (`id`, `language_id`, `sub_title`, `sub_text`, `created_at`, `updated_at`) VALUES
(1, 51, 'Flexible Booking Options', 'Book spaces hourly, for multiple days, or in your preferred time slots — fully flexible to suit your schedule', '2025-08-24 13:57:51', '2025-08-24 13:57:51'),
(2, 53, 'خيارات الحجز المرنة', 'احجز الأماكن بالساعة، لأيام متعددة، أو حسب الفترات الزمنية المفضلة لديك — مرن بالكامل ليناسب جدولك.', '2025-08-24 13:58:38', '2025-08-24 13:58:38'),
(3, 51, '1k+ Customer Feedback', 'Trusted by over 1,000 happy customers who booked hourly, multi-day, and time slot spaces successfully.', '2025-08-24 13:59:02', '2025-08-24 13:59:02'),
(4, 53, 'أكثر من 1000 تعليق من العملاء', 'موثوق من قبل أكثر من 1000 عميل سعيد قاموا بالحجز بالساعة، لأيام متعددة، أو حسب الفترات الزمنية بنجاح.', '2025-08-24 13:59:19', '2025-08-24 13:59:19'),
(5, 51, 'Modern Spaces & Facility', 'Enjoy modern rooms and facilities always available for hourly, multi-day, or time slot bookings.', '2025-08-24 14:00:15', '2025-08-24 14:00:15'),
(6, 53, 'مساحات ومرافق حديثة', 'استمتع بالغرف الحديثة والمرافق المتوفرة دائمًا للحجز بالساعة، لأيام متعددة، أو حسب الفترات الزمنية.', '2025-08-24 14:00:34', '2025-08-24 14:00:34');

-- --------------------------------------------------------

--
-- Table structure for table `about_sections`
--

CREATE TABLE `about_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `text` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `button_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `about_sections`
--

INSERT INTO `about_sections` (`id`, `language_id`, `title`, `text`, `button_name`, `button_url`, `created_at`, `updated_at`) VALUES
(1, 51, 'Your Ultimate Space/Room/Hotel Booking Solution', '<p>Designing a website for booking hotel rooms hourly requires a user-friendly interface with specific sections facilitate easy browsing, booking, and information access</p>', NULL, NULL, '2025-08-24 13:54:41', '2025-08-24 14:01:16'),
(2, 53, 'الحل الأمثل لحجز المساحات/الغرف/الفنادق', '<p>يتطلب تصميم موقع ويب لحجز غرف الفنادق بالساعة واجهة سهلة الاستخدام مع أقسام محددة تسهل التصفح والحجز والوصول إلى المعلومات</p>', NULL, NULL, '2025-08-24 13:55:50', '2025-08-24 14:01:29');

-- --------------------------------------------------------

--
-- Table structure for table `additional_sections`
--

CREATE TABLE `additional_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `serial_number` int DEFAULT NULL,
  `page_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `additional_section_contents`
--

CREATE TABLE `additional_section_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `addition_section_id` bigint UNSIGNED NOT NULL,
  `section_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `address` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `lang_code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'admin_en',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `role_id`, `first_name`, `last_name`, `image`, `username`, `email`, `phone`, `address`, `password`, `status`, `lang_code`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Jonh', 'Doe', '68a0761159fd2.jpg', 'admin', 'demo@example.com', '111111', 'Enter address here', '$2y$10$7rcuMv8LG9adF09JnRjt.O35YL/3dkFWA7EBhBT.LOZvS07OaeDFm', 1, 'en', NULL, '2025-09-15 10:26:20');

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` bigint UNSIGNED NOT NULL,
  `ad_type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `resolution_type` smallint UNSIGNED NOT NULL COMMENT '1 => 300 x 250, 2 => 300 x 600, 3 => 728 x 90',
  `image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `slot` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `views` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `advertisements`
--

INSERT INTO `advertisements` (`id`, `ad_type`, `resolution_type`, `image`, `url`, `slot`, `views`, `created_at`, `updated_at`) VALUES
(1, 'banner', 3, '68abe3993b59b.png', 'https://example.com/user/profile', NULL, 0, '2025-08-25 04:16:25', '2025-08-25 04:16:25'),
(2, 'banner', 3, '68abe3c951f08.png', 'https://example.com/user/profile', NULL, 0, '2025-08-25 04:17:13', '2025-08-25 04:17:13'),
(3, 'banner', 4, '68abe41a24f1d.png', 'https://api.example.net/v1/products', NULL, 0, '2025-08-25 04:18:34', '2025-08-25 04:18:34'),
(4, 'banner', 4, '68abe451cc13d.png', 'https://blog.example.com/post/2023/10/05/dummy-title', NULL, 0, '2025-08-25 04:19:29', '2025-08-25 04:19:29'),
(5, 'banner', 4, '68abe4cf42895.png', 'https://support.example.com/help/ticket/67890', NULL, 0, '2025-08-25 04:21:35', '2025-08-25 04:21:35'),
(6, 'banner', 2, '68abe542c6805.png', 'https://events.example.com/register?event=webinar', NULL, 0, '2025-08-25 04:23:30', '2025-08-25 04:23:30'),
(7, 'banner', 2, '68abe5642b04a.png', 'https://events.example.com/register?event=webinar', NULL, 0, '2025-08-25 04:24:04', '2025-08-25 04:24:04'),
(8, 'banner', 2, '68abe592c9968.png', 'https://static.example.com/images/placeholder.jpg', NULL, 0, '2025-08-25 04:24:50', '2025-08-25 04:24:50'),
(9, 'banner', 1, '68bee21dba52a.png', 'https://login.example.com/auth', NULL, 0, '2025-08-25 04:41:07', '2025-09-08 14:03:09');

-- --------------------------------------------------------

--
-- Table structure for table `basic_settings`
--

CREATE TABLE `basic_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `uniqid` int UNSIGNED NOT NULL DEFAULT '12345',
  `favicon` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website_title` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `latitude` decimal(8,5) DEFAULT NULL,
  `longitude` decimal(8,5) DEFAULT NULL,
  `theme_version` smallint UNSIGNED NOT NULL,
  `base_currency_symbol` varchar(255) DEFAULT NULL,
  `base_currency_symbol_position` varchar(20) DEFAULT NULL,
  `base_currency_text` varchar(20) DEFAULT NULL,
  `base_currency_text_position` varchar(20) DEFAULT NULL,
  `base_currency_rate` decimal(8,2) DEFAULT NULL,
  `primary_color` varchar(30) DEFAULT NULL,
  `secondary_color` varchar(30) DEFAULT NULL,
  `breadcrumb_overlay_color` varchar(30) DEFAULT NULL,
  `breadcrumb_overlay_opacity` decimal(4,2) DEFAULT NULL,
  `smtp_status` tinyint DEFAULT NULL,
  `smtp_host` varchar(255) DEFAULT NULL,
  `smtp_port` int DEFAULT NULL,
  `encryption` varchar(50) DEFAULT NULL,
  `smtp_username` varchar(255) DEFAULT NULL,
  `smtp_password` varchar(255) DEFAULT NULL,
  `from_mail` varchar(255) DEFAULT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `to_mail` varchar(255) DEFAULT NULL,
  `breadcrumb` varchar(255) DEFAULT NULL,
  `disqus_status` tinyint UNSIGNED DEFAULT NULL,
  `disqus_short_name` varchar(255) DEFAULT NULL,
  `google_recaptcha_status` tinyint DEFAULT NULL,
  `google_recaptcha_site_key` varchar(255) DEFAULT NULL,
  `google_recaptcha_secret_key` varchar(255) DEFAULT NULL,
  `whatsapp_status` tinyint UNSIGNED DEFAULT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `whatsapp_header_title` varchar(255) DEFAULT NULL,
  `whatsapp_popup_status` tinyint UNSIGNED DEFAULT NULL,
  `whatsapp_popup_message` text,
  `maintenance_img` varchar(255) DEFAULT NULL,
  `maintenance_status` tinyint DEFAULT NULL,
  `maintenance_msg` text,
  `bypass_token` varchar(255) DEFAULT NULL,
  `footer_logo` varchar(255) DEFAULT NULL,
  `admin_theme_version` varchar(10) NOT NULL DEFAULT 'light',
  `notification_image` varchar(255) DEFAULT NULL,
  `google_adsense_publisher_id` varchar(255) DEFAULT NULL,
  `hero_section_background_img` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `about_section_image` varchar(255) DEFAULT NULL,
  `about_section_video_link` varchar(255) DEFAULT NULL,
  `work_process_background_img` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `testimonial_bg_img` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `facebook_login_status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 -> enable, 0 -> disable',
  `facebook_app_id` varchar(255) DEFAULT NULL,
  `facebook_app_secret` varchar(255) DEFAULT NULL,
  `google_login_status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 -> enable, 0 -> disable',
  `google_client_id` varchar(255) DEFAULT NULL,
  `google_client_secret` varchar(255) DEFAULT NULL,
  `hero_section_foreground_img` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `hero_section_foreground_img_theme_3` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `hero_section_foreground_img_theme_3_left` varchar(255) DEFAULT NULL,
  `hero_video_url` varchar(255) DEFAULT NULL,
  `newsletter_bg_img` varchar(255) DEFAULT NULL,
  `banner_section_bg_img` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `banner_section_foreground_img` varchar(255) DEFAULT NULL,
  `footer_section_bg_img` varchar(255) DEFAULT NULL,
  `seller_email_verification` int DEFAULT '0',
  `seller_admin_approval` int DEFAULT '0',
  `admin_approval_notice` text,
  `expiration_reminder` int DEFAULT '0',
  `tax` float(12,2) NOT NULL DEFAULT '0.00',
  `space_units` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `life_time_earning` double(15,2) NOT NULL DEFAULT '0.00',
  `total_profit` double(15,2) NOT NULL DEFAULT '0.00',
  `shop_status` int DEFAULT NULL,
  `is_shop_rating` tinyint DEFAULT NULL,
  `product_tax_amount` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `video_banner_section_image` varchar(255) DEFAULT NULL,
  `video_banner_video_link` varchar(255) DEFAULT NULL,
  `category_section_background` varchar(255) DEFAULT NULL,
  `fixed_time_slot_rental` tinyint DEFAULT NULL,
  `hourly_rental` tinyint DEFAULT NULL,
  `multi_day_rental` tinyint DEFAULT NULL,
  `guest_checkout_status` tinyint NOT NULL DEFAULT '1',
  `admin_profile` tinyint DEFAULT NULL,
  `google_map_api_key` varchar(255) DEFAULT NULL,
  `google_map_api_key_status` tinyint DEFAULT NULL,
  `google_map_radius` varchar(255) DEFAULT NULL,
  `time_format` varchar(255) DEFAULT NULL,
  `time_zone` varchar(255) DEFAULT NULL,
  `preloader_status` tinyint DEFAULT NULL,
  `preloader` varchar(255) DEFAULT NULL,
  `package_features` longtext,
  `is_language` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `basic_settings`
--

INSERT INTO `basic_settings` (`id`, `uniqid`, `favicon`, `logo`, `website_title`, `email_address`, `contact_number`, `address`, `latitude`, `longitude`, `theme_version`, `base_currency_symbol`, `base_currency_symbol_position`, `base_currency_text`, `base_currency_text_position`, `base_currency_rate`, `primary_color`, `secondary_color`, `breadcrumb_overlay_color`, `breadcrumb_overlay_opacity`, `smtp_status`, `smtp_host`, `smtp_port`, `encryption`, `smtp_username`, `smtp_password`, `from_mail`, `from_name`, `to_mail`, `breadcrumb`, `disqus_status`, `disqus_short_name`, `google_recaptcha_status`, `google_recaptcha_site_key`, `google_recaptcha_secret_key`, `whatsapp_status`, `whatsapp_number`, `whatsapp_header_title`, `whatsapp_popup_status`, `whatsapp_popup_message`, `maintenance_img`, `maintenance_status`, `maintenance_msg`, `bypass_token`, `footer_logo`, `admin_theme_version`, `notification_image`, `google_adsense_publisher_id`, `hero_section_background_img`, `about_section_image`, `about_section_video_link`, `work_process_background_img`, `testimonial_bg_img`, `facebook_login_status`, `facebook_app_id`, `facebook_app_secret`, `google_login_status`, `google_client_id`, `google_client_secret`, `hero_section_foreground_img`, `hero_section_foreground_img_theme_3`, `hero_section_foreground_img_theme_3_left`, `hero_video_url`, `newsletter_bg_img`, `banner_section_bg_img`, `banner_section_foreground_img`, `footer_section_bg_img`, `seller_email_verification`, `seller_admin_approval`, `admin_approval_notice`, `expiration_reminder`, `tax`, `space_units`, `life_time_earning`, `total_profit`, `shop_status`, `is_shop_rating`, `product_tax_amount`, `created_at`, `updated_at`, `video_banner_section_image`, `video_banner_video_link`, `category_section_background`, `fixed_time_slot_rental`, `hourly_rental`, `multi_day_rental`, `guest_checkout_status`, `admin_profile`, `google_map_api_key`, `google_map_api_key_status`, `google_map_radius`, `time_format`, `time_zone`, `preloader_status`, `preloader`, `package_features`, `is_language`) VALUES
(2, 12345, '68c7e9149ab9f.png', '68c7e9149b33b.png', 'SpaceKoi', 'demo@example.com', '1111111111', '450 Young Road, New York, USA', 34.05224, -118.24368, 1, '$', 'left', 'USD', 'right', 1.00, 'C50942', '160828', '000000', 0.60, 1, 'Enter smtp host here', 587, 'TLS', 'demo@example.com', 'demo@example.com', 'demo@example.com', 'spacekoi', 'demo@example.com', '68a741eb5cbdd.png', 0, 'demo', 0, '11111', '111111', 1, '11111', 'Hi, there!', 1, 'If you have any issues, let us know.', '68ab0165433f3.png', 0, 'We are upgrading our site. We will come back soon. \r\nPlease stay with us.\r\nThank you.', 'demo', '68c7e95ee08e9.png', 'dark', '619b7d5e5e9df.png', NULL, '68a185e4693dd.jpg', '68ab197a4dfee.png', NULL, '625bae6fd72f0.jpg', '68a18b4a3c30e.jpg', 0, '415655527803766', 'a0c446544eaaf35713de739be5dc22e8', 1, '1028456015138-2ig40jpn9gaj7bq6kefbmsjt149me75v.apps.googleusercontent.com', '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', '68a184caa4191.jpg', '68a17d9a843dd.png', '68a17d9a85520.png', NULL, '62f09aacaaa98.png', '68a0acec72584.jpg', '68a0acec740c4.png', '68a087f76e088.jpg', 1, 1, 'Unfortunately, your account is deactive now. please get in touch with admin.', 3, 10.00, 'Sqft', 0.00, 0.00, 1, 1, 10.00, '2023-12-03 06:27:43', '2023-12-03 06:27:43', '68a18cc6f05ef.jpg', 'https://www.youtube.com/watch?v=dBeWl6yGE3Y', NULL, 1, 1, 1, 1, 1, '11111', 0, '1500', '12h', '51', 1, '68a0847cbdf8e.gif', '[\"spaces\",\"slider_images_per_space\",\"services_per_space\",\"variants_per_service\",\"amenities_per_space\",\"support_tickets\",\"add_booking\",\"fixed_timeslot_rental\",\"hourly_rental\",\"multi_day_rental\"]', 1);

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL,
  `serial_number` mediumint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `language_id`, `name`, `slug`, `status`, `serial_number`, `created_at`, `updated_at`) VALUES
(1, 51, 'Event Planning Tips', 'event-planning-tips', 1, 1, '2025-08-24 14:26:07', '2025-08-24 14:26:07'),
(2, 53, 'نصائح تخطيط الحدث', 'نصائح-تخطيط-الحدث', 1, 1, '2025-08-24 14:26:32', '2025-08-24 14:26:32'),
(3, 51, 'Venue Highlights', 'venue-highlights', 1, 2, '2025-08-24 14:26:59', '2025-08-24 14:26:59'),
(4, 53, 'أبرز ما يميز المكان', 'أبرز-ما-يميز-المكان', 1, 2, '2025-08-24 14:27:10', '2025-08-25 14:32:42'),
(5, 51, 'Decoration & Themes', 'decoration--themes', 1, 3, '2025-08-24 14:27:23', '2025-08-24 14:27:23'),
(6, 53, 'الديكور والموضوعات', 'الديكور-والموضوعات', 1, 3, '2025-08-24 14:27:32', '2025-08-25 14:32:29'),
(7, 51, 'Catering & Food', 'catering--food', 1, 4, '2025-08-24 14:27:50', '2025-08-24 14:27:50'),
(8, 53, 'خدمات الطعام والتموين', 'خدمات-الطعام-والتموين', 1, 4, '2025-08-24 14:27:59', '2025-08-25 14:32:18'),
(9, 51, 'Digital & Technology', 'digital--technology', 1, 5, '2025-08-24 14:28:19', '2025-08-24 14:28:19'),
(10, 53, 'التكنولوجيا الرقمية', 'التكنولوجيا-الرقمية', 1, 5, '2025-08-24 14:28:28', '2025-08-25 14:32:06');

-- --------------------------------------------------------

--
-- Table structure for table `book_for_tours`
--

CREATE TABLE `book_for_tours` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_number` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `seller_id` bigint UNSIGNED DEFAULT NULL,
  `space_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` tinyint DEFAULT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `information` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `country_id` bigint UNSIGNED DEFAULT NULL,
  `state_id` bigint UNSIGNED DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `is_featured` tinyint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `language_id`, `country_id`, `state_id`, `image`, `name`, `slug`, `status`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 51, 1, 1, '68a1d5fc3615a.jpg', 'Los Angeles', 'los-angeles', 1, 1, '2025-08-17 10:03:23', '2025-08-17 13:50:40'),
(2, 53, 2, 2, '68a1d64e120c4.jpg', 'لوس أنجلوس', 'لوس-أنجلوس', 1, 1, '2025-08-17 10:04:05', '2025-08-24 13:01:40'),
(3, 51, 1, 3, '68a5b3da3ddfe.png', 'Houston', 'houston', 1, 0, '2025-08-20 11:39:06', '2025-08-24 11:54:30'),
(4, 53, 2, 4, '68a5b4049982c.png', 'هيوستن', 'هيوستن', 1, 0, '2025-08-20 11:39:48', '2025-08-20 11:39:48'),
(5, 51, 1, 5, '68a5b52e6100a.png', 'Miami', 'miami', 1, 0, '2025-08-20 11:44:46', '2025-08-24 11:54:31'),
(6, 53, 2, 6, '68a5b5664abb1.png', 'ميامي', 'ميامي', 1, 0, '2025-08-20 11:45:42', '2025-08-20 11:45:42'),
(7, 51, 5, NULL, '68a5deaec764d.png', 'Downtown Dubai', 'downtown-dubai', 1, 0, '2025-08-20 14:41:50', '2025-08-20 14:41:50'),
(8, 51, 5, NULL, '68a5e16be234b.png', 'Dubai Marina', 'dubai-marina', 1, 1, '2025-08-20 14:53:31', '2025-08-24 11:54:45'),
(9, 53, 6, NULL, '68a5e1aa1e0d9.png', 'وسط مدينة دبي', 'وسط-مدينة-دبي', 1, 0, '2025-08-20 14:54:34', '2025-08-24 10:18:17'),
(10, 53, 6, NULL, '68a5e1c738e35.png', 'مرسى دبي', 'مرسى-دبي', 1, 1, '2025-08-20 14:55:03', '2025-08-24 13:01:34'),
(11, 51, 5, NULL, '68a5e1e06cbbb.png', 'Jumeirah', 'jumeirah', 1, 0, '2025-08-20 14:55:28', '2025-08-20 14:55:28'),
(12, 53, 6, NULL, '68a5e1f339d34.png', 'جميرا', 'جميرا', 1, 0, '2025-08-20 14:55:47', '2025-08-24 10:17:47'),
(13, 51, 3, 11, '68a5e21e6e44d.png', 'Toronto', 'toronto', 1, 0, '2025-08-20 14:56:30', '2025-08-20 14:56:30'),
(14, 53, 4, 12, '68a5e24ee0e3d.png', 'تورنتو', 'تورنتو', 1, 0, '2025-08-20 14:57:18', '2025-08-24 10:17:33'),
(15, 51, 3, 13, '68a5e284b40ab.png', 'Victoria', 'victoria', 1, 1, '2025-08-20 14:58:12', '2025-08-24 11:54:42'),
(16, 53, 4, 14, '68a5e299e94ef.png', 'فيكتوريا', 'فيكتوريا', 1, 1, '2025-08-20 14:58:33', '2025-08-24 13:01:26'),
(17, 51, 3, 15, '68a5e2caa19f7.png', 'Montreal', 'montreal', 1, 0, '2025-08-20 14:59:22', '2025-08-20 14:59:22'),
(18, 53, 4, 16, '68a5e2dd96f48.png', 'مونتريال', 'مونتريال', 1, 0, '2025-08-20 14:59:41', '2025-08-24 10:17:00'),
(20, 53, 8, NULL, '68a5e333c685c.png', 'مومباي', 'مومباي', 1, 1, '2025-08-20 15:01:07', '2025-08-24 13:01:23'),
(21, 51, 7, NULL, '68a5e34e10d87.png', 'Delhi', 'delhi', 1, 1, '2025-08-20 15:01:34', '2025-08-24 11:54:51'),
(22, 53, 8, NULL, '68a5e3680ed0c.png', 'دلهي', 'دلهي', 1, 0, '2025-08-20 15:02:00', '2025-08-24 10:16:29'),
(23, 51, 7, NULL, '68a5e3d84f429.png', 'Mumbai', 'mumbai', 1, 0, '2025-08-20 15:03:52', '2025-08-20 15:03:52'),
(24, 51, 9, 17, '68a5e45337b73.png', 'Berlin-Mitte', 'berlin-mitte', 1, 0, '2025-08-20 15:05:55', '2025-08-20 15:05:55'),
(25, 53, 10, 18, '68a5e471a6621.png', 'برلين-ميتي', 'برلين-ميتي', 1, 0, '2025-08-20 15:06:25', '2025-08-24 10:16:14'),
(26, 51, 9, 19, '68a5e4a866ab6.png', 'Altona', 'altona', 1, 1, '2025-08-20 15:07:20', '2025-09-02 11:01:56'),
(27, 53, 10, 20, '68a5e4c27705f.png', 'ألتونا', 'ألتونا', 1, 1, '2025-08-20 15:07:46', '2025-08-24 13:01:12');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `mobile_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `mobile_number`, `email_address`, `created_at`, `updated_at`) VALUES
(1, '111111111,22222222', 'demo@example.com', '2025-08-18 14:05:47', '2025-09-11 15:09:06');

-- --------------------------------------------------------

--
-- Table structure for table `contact_contents`
--

CREATE TABLE `contact_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_contents`
--

INSERT INTO `contact_contents` (`id`, `language_id`, `title`, `text`, `location`, `created_at`, `updated_at`) VALUES
(1, 51, 'Get in Touch', '<p>We&rsquo;re here to help you with any questions about bookings, venues, or services. Feel free to reach out to our team &mdash; we&rsquo;ll respond as quickly as possible</p>', '123 Event Street, Downtown, New York, USA', '2025-08-18 14:06:11', '2025-08-18 14:08:26'),
(2, 53, 'تواصل معنا', '<p>نحن هنا لمساعدتك في أي استفسارات حول الحجوزات أو الأماكن أو الخدمات. لا تتردد في التواصل مع فريقنا &mdash; سنرد عليك في أسرع وقت ممكن</p>', '٣ شارع المناسبات، وسط المدينة، نيويورك، الولايات المتحدة', '2025-08-18 14:09:59', '2025-08-18 14:09:59');

-- --------------------------------------------------------

--
-- Table structure for table `cookie_alerts`
--

CREATE TABLE `cookie_alerts` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `cookie_alert_status` tinyint UNSIGNED NOT NULL,
  `cookie_alert_btn_text` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `cookie_alert_text` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `cookie_alerts`
--

INSERT INTO `cookie_alerts` (`id`, `language_id`, `cookie_alert_status`, `cookie_alert_btn_text`, `cookie_alert_text`, `created_at`, `updated_at`) VALUES
(1, 51, 1, 'Accept All', 'MultiSpace uses cookies to ensure you get the best experience on our digital galaxy', '2025-08-27 13:12:40', '2025-09-15 10:23:58'),
(2, 53, 1, 'قبول الكل', 'يستخدم MultiSpace ملفات تعريف الارتباط لضمان حصولك على أفضل تجربة على مجرتنا الرقمية', '2025-08-27 14:36:57', '2025-09-15 10:26:13');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `language_id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 51, 'United States', 'united-states', 1, '2025-08-17 08:50:37', '2025-08-17 08:50:37'),
(2, 53, 'الولايات المتحدة', 'الولايات-المتحدة', 1, '2025-08-17 08:54:09', '2025-08-17 08:54:09'),
(3, 51, 'Canada', 'canada', 1, '2025-08-20 11:48:29', '2025-08-20 11:48:29'),
(4, 53, 'كندا', 'كندا', 1, '2025-08-20 11:48:53', '2025-08-20 11:48:53'),
(5, 51, 'Dubai', 'dubai', 1, '2025-08-20 11:52:10', '2025-08-20 11:52:10'),
(6, 53, 'دبي', 'دبي', 1, '2025-08-20 11:52:34', '2025-08-20 11:52:34'),
(7, 51, 'India', 'india', 1, '2025-08-20 11:53:28', '2025-08-20 11:53:28'),
(8, 53, 'الهند', 'الهند', 1, '2025-08-20 11:54:00', '2025-08-20 11:54:00'),
(9, 51, 'Germany', 'germany', 1, '2025-08-20 12:03:06', '2025-08-20 12:03:06'),
(10, 53, 'ألمانيا', 'ألمانيا', 1, '2025-08-20 12:03:31', '2025-08-20 12:03:31');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usages`
--

CREATE TABLE `coupon_usages` (
  `id` bigint UNSIGNED NOT NULL,
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `coupon_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `host_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `question` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `answer` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `serial_number` mediumint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `language_id`, `question`, `answer`, `serial_number`, `created_at`, `updated_at`) VALUES
(1, 51, 'How do I book a space?', 'You can browse available spaces on our platform, select your preferred date and time, and complete the booking through our secure online system.', 10, '2025-08-24 13:47:59', '2025-08-24 13:48:15'),
(2, 53, 'كيف أقوم بحجز مكان؟', 'يمكنك تصفح الأماكن المتاحة على منصتنا، اختيار التاريخ والوقت المفضلين، وإتمام الحجز من خلال نظامنا الآمن عبر الإنترنت.', 10, '2025-08-24 13:48:38', '2025-08-24 13:48:38'),
(3, 51, 'Can I cancel or reschedule my booking?', 'Yes, cancellations and rescheduling are allowed according to our Cancellation Policy. Please review the policy before booking.', 9, '2025-08-24 13:48:58', '2025-08-24 13:48:58'),
(4, 53, 'هل يمكنني إلغاء أو إعادة جدولة حجزي؟', 'نعم، يُسمح بالإلغاء وإعادة الجدولة وفقًا لسياسة الإلغاء لدينا. يرجى مراجعة السياسة قبل الحجز.', 9, '2025-08-24 13:49:28', '2025-08-24 13:49:28'),
(5, 51, 'What payment methods are accepted?', 'We accept major credit/debit cards, PayPal, and local payment gateways. All transactions are secure', 8, '2025-08-24 13:49:52', '2025-08-24 13:49:52'),
(6, 53, 'ما هي طرق الدفع المقبولة؟', 'نقبل بطاقات الائتمان/الخصم الرئيسية، باي بال، وبوابات الدفع المحلية. جميع المعاملات آمنة.', 8, '2025-08-24 13:50:18', '2025-08-24 13:50:18'),
(7, 51, 'Are spaces available for hourly rental?', 'Yes, many of our spaces offer hourly, daily, or multi-day rental options depending on the venue.', 7, '2025-08-24 13:50:39', '2025-08-24 13:50:39'),
(8, 53, 'هل الأماكن متاحة للإيجار بالساعة؟', 'نعم، العديد من الأماكن لدينا تقدم خيارات إيجار بالساعة أو اليوم أو لعدة أيام حسب المكان.', 7, '2025-08-24 13:50:58', '2025-08-24 13:50:58'),
(9, 51, 'Can I request custom services for my event?', 'Absolutely! You can select add-ons such as catering, decoration, AV equipment, and more while booking your space.', 6, '2025-08-24 13:51:23', '2025-08-24 13:51:23'),
(10, 53, 'هل يمكنني طلب خدمات مخصصة لفعاليتي؟', 'بالتأكيد! يمكنك اختيار خدمات إضافية مثل الطعام، الديكور، معدات الصوت والصورة، والمزيد أثناء حجز المكان.', 6, '2025-08-24 13:52:02', '2025-08-24 13:52:02');

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `color` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `number` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `language_id`, `icon`, `color`, `title`, `number`, `created_at`, `updated_at`, `description`) VALUES
(1, 51, 'far fa-building', 'FFFFFF', 'Search Your Spaces', 1, '2025-08-17 14:51:14', '2025-09-08 08:36:14', 'Browse through a wide range of venues and pick the perfect space that matches your event'),
(2, 53, 'far fa-building', 'FFFFFF', 'اختر مساحتك', 1, '2025-08-17 14:52:32', '2025-08-17 14:58:35', 'تصفح مجموعة واسعة من الأماكن واختر المكان المثالي الذي يتناسب مع حدثك'),
(3, 51, 'far fa-calendar-alt', 'FFFFFF', 'Select Date & Time', 2, '2025-08-17 15:00:26', '2025-08-17 15:00:26', 'Pick your preferred event date and time slot to ensure availability'),
(4, 53, 'far fa-calendar-alt', 'FFFFFF', 'حدد التاريخ والوقت', 2, '2025-08-17 15:01:03', '2025-08-17 15:01:03', 'اختر تاريخ الحدث والفترة الزمنية المفضلة لديك لضمان التوافر'),
(7, 51, 'far fa-check-circle', 'FFFFFF', 'Confirm Your Booking', 3, '2025-08-17 15:08:54', '2025-09-08 08:37:26', 'Review your booking details, make payment securely, and receive instant confirmation'),
(8, 53, 'far fa-check-circle', 'FFFFFF', 'تأكيد وحجز', 3, '2025-08-17 15:10:05', '2025-09-08 08:38:16', 'قم بمراجعة تفاصيل الحجز الخاص بك، وقم بالدفع بشكل آمن، واحصل على تأكيد فوري');

-- --------------------------------------------------------

--
-- Table structure for table `feature_charges`
--

CREATE TABLE `feature_charges` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `day` int DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feature_charges`
--

INSERT INTO `feature_charges` (`id`, `language_id`, `day`, `price`, `created_at`, `updated_at`) VALUES
(1, 51, 7, 29.00, '2025-08-18 14:14:18', '2025-08-18 14:14:18');

-- --------------------------------------------------------

--
-- Table structure for table `footer_contents`
--

CREATE TABLE `footer_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `footer_background_color` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `about_company` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `copyright_text` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `newsletter_text` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `footer_contents`
--

INSERT INTO `footer_contents` (`id`, `language_id`, `footer_background_color`, `about_company`, `copyright_text`, `newsletter_text`, `created_at`, `updated_at`) VALUES
(1, 51, '0F0F0F', 'We are a trusted space booking company dedicated to connecting people with the perfect venues for their events. Whether it’s weddings, corporate meetings, conferences, workshops, or private gatherings, we make venue booking simple, fast, and reliable', '© 2025 MultiSpace Booking Company. All Rights Reserved', 'Stay updated with the latest event spaces, exclusive offers, and booking tips. Join our newsletter and never miss out on creating unforgettable moments with the perfect venue', '2025-08-16 13:30:31', '2025-09-15 10:24:30'),
(2, 53, '080808', 'نحن شركة موثوقة لحجز المساحات، ملتزمون بربط الناس بالأماكن المثالية لفعالياتهم. سواءً كانت حفلات زفاف، أو اجتماعات شركات، أو مؤتمرات، أو ورش عمل، أو تجمعات خاصة، نجعل حجز الأماكن سهلاً وسريعًا وموثوقًا.', '© ٢٠٢٥ شركة حجز المساحات. جميع الحقوق محفوظة.', 'ابقَ على اطلاع بأحدث أماكن الفعاليات والعروض الحصرية ونصائح الحجز. انضم إلى نشرتنا الإخبارية ولا تفوّت فرصة صنع لحظات لا تُنسى في المكان المثالي.', '2025-08-17 16:29:30', '2025-09-08 11:21:43');

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `seller_id` bigint DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `language_id`, `seller_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 51, NULL, 'Custom Booking Quote', 1, '2025-08-20 15:42:23', '2025-08-20 15:42:23'),
(2, 53, NULL, 'عرض حجز مخصص', 1, '2025-08-20 15:42:37', '2025-08-20 15:42:37'),
(3, 51, 67, 'Venue Tour Request', 1, '2025-08-24 06:48:22', '2025-08-24 06:48:22'),
(4, 53, 67, 'طلب جولة في المكان', 1, '2025-08-24 06:48:30', '2025-08-24 06:48:30');

-- --------------------------------------------------------

--
-- Table structure for table `form_inputs`
--

CREATE TABLE `form_inputs` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` bigint DEFAULT NULL,
  `type` tinyint UNSIGNED NOT NULL COMMENT '1 - Text Field, 2 - Number Field, 3 - Select, 4 - Checkbox, 5 - Textarea Field, 6 - Datepicker, 7 - Timepicker, 8 - File',
  `label` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `placeholder` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `is_required` tinyint UNSIGNED NOT NULL COMMENT '0 - not required, 1 - required',
  `options` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `file_size` decimal(11,2) UNSIGNED DEFAULT NULL,
  `order_no` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'default value 0 means, this input field has created just now and it has not sorted yet.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `form_inputs`
--

INSERT INTO `form_inputs` (`id`, `form_id`, `type`, `label`, `placeholder`, `name`, `is_required`, `options`, `file_size`, `order_no`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Phone Number', 'Enter phone number', 'phone_number', 1, NULL, NULL, 1, '2025-08-20 15:46:28', '2025-08-20 15:46:28'),
(2, 1, 3, 'Event Type', 'Select Event Type', 'event_type', 1, '[\"Wedding & Reception\",\"Corporate Events\",\"Birthday & Private Parties\",\"Conference & Seminars\"]', NULL, 2, '2025-08-20 15:49:05', '2025-08-21 06:08:44'),
(3, 1, 6, 'Event Date', 'Enter Date', 'event_date', 1, NULL, NULL, 3, '2025-08-20 15:49:37', '2025-08-20 15:49:37'),
(4, 1, 2, 'Number of Guests', 'Enter number of guest', 'number_of_guests', 1, NULL, NULL, 4, '2025-08-20 15:51:03', '2025-08-20 15:51:03'),
(5, 1, 5, 'Additional Notes', 'Special Requests', 'additional_notes', 0, NULL, NULL, 5, '2025-08-20 15:51:59', '2025-08-20 15:52:06'),
(6, 2, 2, 'رقم التليفون', 'أدخل رقم الهاتف', 'رقم_التليفون', 1, NULL, NULL, 1, '2025-08-20 15:57:06', '2025-08-20 15:57:06'),
(7, 2, 3, 'نوع المساحة', 'حدد نوع المساحة', 'نوع_المساحة', 1, '[\"\\u062d\\u0641\\u0644 \\u0627\\u0644\\u0632\\u0641\\u0627\\u0641 \\u0648\\u0627\\u0644\\u0627\\u0633\\u062a\\u0642\\u0628\\u0627\\u0644\",\"\\u0627\\u0644\\u0641\\u0639\\u0627\\u0644\\u064a\\u0627\\u062a \\u0627\\u0644\\u0645\\u0624\\u0633\\u0633\\u064a\\u0629\",\"\\u0623\\u0639\\u064a\\u0627\\u062f \\u0627\\u0644\\u0645\\u064a\\u0644\\u0627\\u062f \\u0648\\u0627\\u0644\\u062d\\u0641\\u0644\\u0627\\u062a \\u0627\\u0644\\u062e\\u0627\\u0635\\u0629\",\"\\u0627\\u0644\\u0645\\u0624\\u062a\\u0645\\u0631\\u0627\\u062a \\u0648\\u0627\\u0644\\u0646\\u062f\\u0648\\u0627\\u062a\"]', NULL, 2, '2025-08-20 15:59:37', '2025-08-20 15:59:37'),
(8, 2, 6, 'تاريخ الحدث', 'أدخل التاريخ', 'تاريخ_الحدث', 1, NULL, NULL, 3, '2025-08-21 05:21:29', '2025-08-21 05:21:29'),
(9, 2, 2, 'عدد الضيوف', 'أدخل عدد الضيوف', 'عدد_الضيوف', 1, NULL, NULL, 4, '2025-08-21 05:22:51', '2025-08-21 05:22:51'),
(10, 2, 5, 'ملاحظات إضافية', 'طلب خاص', 'ملاحظات_إضافية', 0, NULL, NULL, 5, '2025-08-21 05:23:43', '2025-08-21 05:23:50'),
(11, 3, 1, 'Phone Number', 'Enter phone number', 'phone_number', 1, NULL, NULL, 1, '2025-08-24 06:49:18', '2025-08-24 06:49:18'),
(12, 3, 6, 'Preferred Date', 'Enter date', 'preferred_date', 1, NULL, NULL, 2, '2025-08-24 06:49:43', '2025-08-24 06:49:43'),
(14, 3, 7, 'Preferred Time', 'Enter time', 'preferred_time', 1, NULL, NULL, 3, '2025-08-24 06:50:39', '2025-08-24 06:50:39'),
(15, 3, 2, 'Number of Guests Attending the Tour', 'Number of Guests Attending the Tour', 'number_of_guests_attending_the_tour', 1, NULL, NULL, 4, '2025-08-24 06:51:06', '2025-08-24 06:51:06'),
(16, 3, 3, 'Event Type', 'Select Event  Type', 'event_type', 1, '[\"Wedding & Reception\",\"Corporate Events\",\"Birthday & Private Parties\",\"Conference & Seminars\",\"Exhibitions & Trade Shows\",\"Concerts & Live Shows\",\"Workshops & Training\",\"Religious & Cultural Events\"]', NULL, 5, '2025-08-24 06:53:47', '2025-08-24 06:53:47'),
(17, 3, 5, 'Special Requests / Notes', 'Enter note', 'special_requests_/_notes', 0, NULL, NULL, 6, '2025-08-24 06:54:35', '2025-08-24 06:55:46'),
(18, 3, 3, 'How did you hear about us?', 'Select Option', 'how_did_you_hear_about_us?', 0, '[\"Website\",\"Social Media\",\"Friend\",\"Other\"]', NULL, 7, '2025-08-24 06:55:41', '2025-08-24 06:55:41'),
(19, 4, 1, 'رقم التليفون', 'أدخل رقم الهاتف', 'رقم_التليفون', 1, NULL, NULL, 1, '2025-08-24 06:58:07', '2025-08-24 06:58:07'),
(20, 4, 6, 'التاريخ المفضل', 'أدخل التاريخ', 'التاريخ_المفضل', 1, NULL, NULL, 2, '2025-08-24 07:00:06', '2025-08-24 07:00:06'),
(22, 4, 7, 'الوقت المفضل', 'أدخل الوقت', 'الوقت_المفضل', 1, NULL, NULL, 3, '2025-08-24 07:02:45', '2025-08-24 07:13:15'),
(23, 4, 2, 'عدد الضيوف الحاضرين للجولة', 'عدد الضيوف الحاضرين للجولة', 'عدد_الضيوف_الحاضرين_للجولة', 1, NULL, NULL, 4, '2025-08-24 07:05:17', '2025-08-24 07:13:47'),
(24, 4, 3, 'نوع الحدث', 'حدد نوع الحدث', 'نوع_الحدث', 1, '[\"\\u062d\\u0641\\u0644 \\u0627\\u0644\\u0632\\u0641\\u0627\\u0641 \\u0648\\u0627\\u0644\\u0627\\u0633\\u062a\\u0642\\u0628\\u0627\\u0644\",\"\\u0627\\u0644\\u0641\\u0639\\u0627\\u0644\\u064a\\u0627\\u062a \\u0627\\u0644\\u0645\\u0624\\u0633\\u0633\\u064a\\u0629\",\"\\u0627\\u0644\\u0645\\u0624\\u062a\\u0645\\u0631\\u0627\\u062a \\u0648\\u0627\\u0644\\u0646\\u062f\\u0648\\u0627\\u062a\",\"\\u0623\\u0639\\u064a\\u0627\\u062f \\u0627\\u0644\\u0645\\u064a\\u0644\\u0627\\u062f \\u0648\\u0627\\u0644\\u062d\\u0641\\u0644\\u0627\\u062a \\u0627\\u0644\\u062e\\u0627\\u0635\\u0629\"]', NULL, 5, '2025-08-24 07:07:57', '2025-08-24 07:09:30'),
(25, 4, 5, 'طلبات خاصة / ملاحظات', 'أدخل الملاحظات', 'طلبات_خاصة_/_ملاحظات', 0, NULL, NULL, 6, '2025-08-24 07:10:21', '2025-08-24 07:10:21'),
(26, 4, 3, 'كيف سمعت عنا؟', 'حدد الخيار', 'كيف_سمعت_عنا؟', 0, '[\"\\u0645\\u0648\\u0642\\u0639 \\u0625\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a\",\"\\u0648\\u0633\\u0627\\u0626\\u0644 \\u0627\\u0644\\u062a\\u0648\\u0627\\u0635\\u0644 \\u0627\\u0644\\u0627\\u062c\\u062a\\u0645\\u0627\\u0639\\u064a\",\"\\u0635\\u062f\\u064a\\u0642\",\"\\u0622\\u062e\\u0631\"]', NULL, 7, '2025-08-24 07:12:49', '2025-08-24 07:13:25'),
(27, 1, 8, 'upload file', NULL, 'upload_file', 1, NULL, 10.00, 6, '2025-08-30 08:15:53', '2025-08-30 08:15:53'),
(28, 3, 8, 'Upload File', NULL, 'upload_file', 1, NULL, 10.00, 8, '2025-08-30 08:19:38', '2025-08-30 08:19:38');

-- --------------------------------------------------------

--
-- Table structure for table `get_quotes`
--

CREATE TABLE `get_quotes` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_number` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `seller_id` bigint UNSIGNED DEFAULT NULL,
  `space_id` bigint UNSIGNED DEFAULT NULL,
  `language_id` tinyint DEFAULT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `information` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `global_days`
--

CREATE TABLE `global_days` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `space_id` bigint DEFAULT NULL,
  `seller_id` bigint DEFAULT NULL,
  `order` int DEFAULT NULL,
  `is_weekend` tinyint(1) NOT NULL DEFAULT '0',
  `is_holiday` tinyint NOT NULL DEFAULT '0',
  `start_of_week` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `global_days`
--

INSERT INTO `global_days` (`id`, `name`, `space_id`, `seller_id`, `order`, `is_weekend`, `is_holiday`, `start_of_week`, `created_at`, `updated_at`) VALUES
(1, 'Sunday', 1, 0, 0, 0, 0, 0, '2025-08-17 10:31:11', '2025-08-17 10:31:11'),
(2, 'Monday', 1, 0, 1, 0, 0, 1, '2025-08-17 10:31:11', '2025-08-17 10:31:11'),
(3, 'Tuesday', 1, 0, 2, 0, 0, 2, '2025-08-17 10:31:11', '2025-08-17 10:31:11'),
(4, 'Wednesday', 1, 0, 3, 0, 0, 3, '2025-08-17 10:31:11', '2025-08-17 10:31:11'),
(5, 'Thursday', 1, 0, 4, 0, 0, 4, '2025-08-17 10:31:11', '2025-08-17 10:31:11'),
(6, 'Friday', 1, 0, 5, 0, 0, 5, '2025-08-17 10:31:11', '2025-08-17 10:31:11'),
(7, 'Saturday', 1, 0, 6, 0, 0, 6, '2025-08-17 10:31:11', '2025-08-17 10:31:11'),
(8, 'Sunday', 2, 0, 0, 0, 0, 0, '2025-08-19 13:35:10', '2025-08-19 13:35:10'),
(9, 'Monday', 2, 0, 1, 0, 0, 1, '2025-08-19 13:35:10', '2025-08-19 13:35:10'),
(10, 'Tuesday', 2, 0, 2, 0, 0, 2, '2025-08-19 13:35:10', '2025-08-19 13:35:10'),
(11, 'Wednesday', 2, 0, 3, 0, 0, 3, '2025-08-19 13:35:10', '2025-08-19 13:35:10'),
(12, 'Thursday', 2, 0, 4, 0, 0, 4, '2025-08-19 13:35:10', '2025-08-19 13:35:10'),
(13, 'Friday', 2, 0, 5, 0, 0, 5, '2025-08-19 13:35:10', '2025-08-19 13:35:10'),
(14, 'Saturday', 2, 0, 6, 0, 0, 6, '2025-08-19 13:35:10', '2025-08-19 13:35:10'),
(15, 'Sunday', 3, 0, 0, 0, 0, 0, '2025-08-21 09:03:39', '2025-08-21 09:03:39'),
(16, 'Monday', 3, 0, 1, 0, 0, 1, '2025-08-21 09:03:39', '2025-08-21 09:03:39'),
(17, 'Tuesday', 3, 0, 2, 0, 0, 2, '2025-08-21 09:03:39', '2025-08-21 09:03:39'),
(18, 'Wednesday', 3, 0, 3, 0, 0, 3, '2025-08-21 09:03:39', '2025-08-21 09:03:39'),
(19, 'Thursday', 3, 0, 4, 0, 0, 4, '2025-08-21 09:03:39', '2025-08-21 09:03:39'),
(20, 'Friday', 3, 0, 5, 0, 0, 5, '2025-08-21 09:03:39', '2025-08-21 09:03:39'),
(21, 'Saturday', 3, 0, 6, 0, 0, 6, '2025-08-21 09:03:39', '2025-08-21 09:03:39'),
(22, 'Sunday', 4, 66, 0, 0, 0, 0, '2025-08-22 06:34:08', '2025-08-22 06:34:08'),
(23, 'Monday', 4, 66, 1, 0, 0, 1, '2025-08-22 06:34:08', '2025-08-22 06:34:08'),
(24, 'Tuesday', 4, 66, 2, 0, 0, 2, '2025-08-22 06:34:08', '2025-08-22 06:34:08'),
(25, 'Wednesday', 4, 66, 3, 0, 0, 3, '2025-08-22 06:34:08', '2025-08-22 06:34:08'),
(26, 'Thursday', 4, 66, 4, 0, 0, 4, '2025-08-22 06:34:08', '2025-08-22 06:34:08'),
(27, 'Friday', 4, 66, 5, 0, 0, 5, '2025-08-22 06:34:08', '2025-08-22 06:34:08'),
(28, 'Saturday', 4, 66, 6, 0, 0, 6, '2025-08-22 06:34:08', '2025-08-22 06:34:08'),
(29, 'Sunday', 5, 66, 0, 0, 0, 0, '2025-08-22 12:05:25', '2025-08-22 12:05:25'),
(30, 'Monday', 5, 66, 1, 0, 0, 1, '2025-08-22 12:05:25', '2025-08-22 12:05:25'),
(31, 'Tuesday', 5, 66, 2, 0, 0, 2, '2025-08-22 12:05:25', '2025-08-22 12:05:25'),
(32, 'Wednesday', 5, 66, 3, 0, 0, 3, '2025-08-22 12:05:25', '2025-08-22 12:05:25'),
(33, 'Thursday', 5, 66, 4, 0, 0, 4, '2025-08-22 12:05:25', '2025-08-22 12:05:25'),
(34, 'Friday', 5, 66, 5, 0, 0, 5, '2025-08-22 12:05:25', '2025-08-22 12:05:25'),
(35, 'Saturday', 5, 66, 6, 0, 0, 6, '2025-08-22 12:05:25', '2025-08-22 12:05:25'),
(36, 'Sunday', 6, 66, 0, 0, 0, 0, '2025-08-22 13:14:19', '2025-08-22 13:14:19'),
(37, 'Monday', 6, 66, 1, 0, 0, 1, '2025-08-22 13:14:19', '2025-08-22 13:14:19'),
(38, 'Tuesday', 6, 66, 2, 0, 0, 2, '2025-08-22 13:14:19', '2025-08-22 13:14:19'),
(39, 'Wednesday', 6, 66, 3, 0, 0, 3, '2025-08-22 13:14:19', '2025-08-22 13:14:19'),
(40, 'Thursday', 6, 66, 4, 0, 0, 4, '2025-08-22 13:14:19', '2025-08-22 13:14:19'),
(41, 'Friday', 6, 66, 5, 0, 0, 5, '2025-08-22 13:14:19', '2025-08-22 13:14:19'),
(42, 'Saturday', 6, 66, 6, 0, 0, 6, '2025-08-22 13:14:19', '2025-08-22 13:14:19'),
(43, 'Sunday', 7, 67, 0, 0, 0, 0, '2025-08-22 14:57:13', '2025-08-22 14:57:13'),
(44, 'Monday', 7, 67, 1, 0, 0, 1, '2025-08-22 14:57:13', '2025-08-22 14:57:13'),
(45, 'Tuesday', 7, 67, 2, 0, 0, 2, '2025-08-22 14:57:13', '2025-08-22 14:57:13'),
(46, 'Wednesday', 7, 67, 3, 0, 0, 3, '2025-08-22 14:57:13', '2025-08-22 14:57:13'),
(47, 'Thursday', 7, 67, 4, 0, 0, 4, '2025-08-22 14:57:13', '2025-08-22 14:57:13'),
(48, 'Friday', 7, 67, 5, 0, 0, 5, '2025-08-22 14:57:13', '2025-08-22 14:57:13'),
(49, 'Saturday', 7, 67, 6, 0, 0, 6, '2025-08-22 14:57:13', '2025-08-22 14:57:13'),
(50, 'Sunday', 8, 67, 0, 0, 0, 0, '2025-08-22 15:37:03', '2025-08-22 15:37:03'),
(51, 'Monday', 8, 67, 1, 0, 0, 1, '2025-08-22 15:37:03', '2025-08-22 15:37:03'),
(52, 'Tuesday', 8, 67, 2, 0, 0, 2, '2025-08-22 15:37:03', '2025-08-22 15:37:03'),
(53, 'Wednesday', 8, 67, 3, 0, 0, 3, '2025-08-22 15:37:03', '2025-08-22 15:37:03'),
(54, 'Thursday', 8, 67, 4, 0, 0, 4, '2025-08-22 15:37:03', '2025-08-22 15:37:03'),
(55, 'Friday', 8, 67, 5, 0, 0, 5, '2025-08-22 15:37:03', '2025-08-22 15:37:03'),
(56, 'Saturday', 8, 67, 6, 0, 0, 6, '2025-08-22 15:37:03', '2025-08-22 15:37:03'),
(57, 'Sunday', 9, 67, 0, 0, 0, 0, '2025-08-23 08:36:43', '2025-08-23 08:36:43'),
(58, 'Monday', 9, 67, 1, 0, 0, 1, '2025-08-23 08:36:43', '2025-08-23 08:36:43'),
(59, 'Tuesday', 9, 67, 2, 0, 0, 2, '2025-08-23 08:36:43', '2025-08-23 08:36:43'),
(60, 'Wednesday', 9, 67, 3, 0, 0, 3, '2025-08-23 08:36:43', '2025-08-23 08:36:43'),
(61, 'Thursday', 9, 67, 4, 0, 0, 4, '2025-08-23 08:36:43', '2025-08-23 08:36:43'),
(62, 'Friday', 9, 67, 5, 0, 0, 5, '2025-08-23 08:36:43', '2025-08-23 08:36:43'),
(63, 'Saturday', 9, 67, 6, 0, 0, 6, '2025-08-23 08:36:43', '2025-08-23 08:36:43'),
(64, 'Sunday', 10, 68, 0, 0, 0, 0, '2025-08-23 12:25:42', '2025-08-23 12:25:42'),
(65, 'Monday', 10, 68, 1, 0, 0, 1, '2025-08-23 12:25:42', '2025-08-23 12:25:42'),
(66, 'Tuesday', 10, 68, 2, 0, 0, 2, '2025-08-23 12:25:42', '2025-08-23 12:25:42'),
(67, 'Wednesday', 10, 68, 3, 0, 0, 3, '2025-08-23 12:25:42', '2025-08-23 12:25:42'),
(68, 'Thursday', 10, 68, 4, 0, 0, 4, '2025-08-23 12:25:42', '2025-08-23 12:25:42'),
(69, 'Friday', 10, 68, 5, 0, 0, 5, '2025-08-23 12:25:42', '2025-08-23 12:25:42'),
(70, 'Saturday', 10, 68, 6, 0, 0, 6, '2025-08-23 12:25:42', '2025-08-23 12:25:42'),
(71, 'Sunday', 11, 68, 0, 0, 0, 0, '2025-08-23 13:52:04', '2025-08-23 13:52:04'),
(72, 'Monday', 11, 68, 1, 0, 0, 1, '2025-08-23 13:52:04', '2025-08-23 13:52:04'),
(73, 'Tuesday', 11, 68, 2, 0, 0, 2, '2025-08-23 13:52:04', '2025-08-23 13:52:04'),
(74, 'Wednesday', 11, 68, 3, 0, 0, 3, '2025-08-23 13:52:04', '2025-08-23 13:52:04'),
(75, 'Thursday', 11, 68, 4, 0, 0, 4, '2025-08-23 13:52:04', '2025-08-23 13:52:04'),
(76, 'Friday', 11, 68, 5, 0, 0, 5, '2025-08-23 13:52:04', '2025-08-23 13:52:04'),
(77, 'Saturday', 11, 68, 6, 0, 0, 6, '2025-08-23 13:52:04', '2025-08-23 13:52:04'),
(78, 'Sunday', 12, 68, 0, 0, 0, 0, '2025-08-23 14:39:31', '2025-08-23 14:39:31'),
(79, 'Monday', 12, 68, 1, 0, 0, 1, '2025-08-23 14:39:31', '2025-08-23 14:39:31'),
(80, 'Tuesday', 12, 68, 2, 0, 0, 2, '2025-08-23 14:39:31', '2025-08-23 14:39:31'),
(81, 'Wednesday', 12, 68, 3, 0, 0, 3, '2025-08-23 14:39:31', '2025-08-23 14:39:31'),
(82, 'Thursday', 12, 68, 4, 0, 0, 4, '2025-08-23 14:39:31', '2025-08-23 14:39:31'),
(83, 'Friday', 12, 68, 5, 0, 0, 5, '2025-08-23 14:39:31', '2025-08-23 14:39:31'),
(84, 'Saturday', 12, 68, 6, 0, 0, 6, '2025-08-23 14:39:32', '2025-08-23 14:39:32'),
(85, 'Sunday', 13, 0, 0, 0, 0, 0, '2025-08-23 15:16:43', '2025-08-23 15:16:43'),
(86, 'Monday', 13, 0, 1, 0, 0, 1, '2025-08-23 15:16:43', '2025-08-23 15:16:43'),
(87, 'Tuesday', 13, 0, 2, 0, 0, 2, '2025-08-23 15:16:43', '2025-08-23 15:16:43'),
(88, 'Wednesday', 13, 0, 3, 0, 0, 3, '2025-08-23 15:16:43', '2025-08-23 15:16:43'),
(89, 'Thursday', 13, 0, 4, 0, 0, 4, '2025-08-23 15:16:43', '2025-08-23 15:16:43'),
(90, 'Friday', 13, 0, 5, 0, 0, 5, '2025-08-23 15:16:43', '2025-08-23 15:16:43'),
(91, 'Saturday', 13, 0, 6, 0, 0, 6, '2025-08-23 15:16:43', '2025-08-23 15:16:43'),
(92, 'Sunday', 14, 67, 0, 0, 0, 0, '2025-08-24 06:47:34', '2025-08-24 06:47:34'),
(93, 'Monday', 14, 67, 1, 0, 0, 1, '2025-08-24 06:47:34', '2025-08-24 06:47:34'),
(94, 'Tuesday', 14, 67, 2, 0, 0, 2, '2025-08-24 06:47:34', '2025-08-24 06:47:34'),
(95, 'Wednesday', 14, 67, 3, 0, 0, 3, '2025-08-24 06:47:34', '2025-08-24 06:47:34'),
(96, 'Thursday', 14, 67, 4, 0, 0, 4, '2025-08-24 06:47:34', '2025-08-24 06:47:34'),
(97, 'Friday', 14, 67, 5, 0, 0, 5, '2025-08-24 06:47:34', '2025-08-24 06:47:34'),
(98, 'Saturday', 14, 67, 6, 0, 0, 6, '2025-08-24 06:47:34', '2025-08-24 06:47:34');

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `id` bigint UNSIGNED NOT NULL,
  `endpoint` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hero_sliders`
--

CREATE TABLE `hero_sliders` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hero_statics`
--

CREATE TABLE `hero_statics` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `code` char(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `direction` tinyint NOT NULL,
  `is_default` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `direction`, `is_default`, `created_at`, `updated_at`) VALUES
(51, 'English', 'en', 0, 1, NULL, '2025-08-16 09:11:27'),
(53, 'Arabic', 'ar', 1, 0, '2025-08-17 08:11:26', '2025-08-17 08:11:26');

-- --------------------------------------------------------

--
-- Table structure for table `mail_templates`
--

CREATE TABLE `mail_templates` (
  `id` int NOT NULL,
  `mail_type` varchar(255) NOT NULL,
  `mail_subject` varchar(255) NOT NULL,
  `mail_body` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `mail_templates`
--

INSERT INTO `mail_templates` (`id`, `mail_type`, `mail_subject`, `mail_body`) VALUES
(4, 'verify_email', 'Verify Your Email Address', '<table class=\"m_2450577039782362685body\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td align=\"center\" valign=\"top\" bgcolor=\"#F6F6F6\"><center>\r\n<table class=\"m_2450577039782362685container\" style=\"width: 78.3088%;\" align=\"center\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 100%;\">\r\n<table class=\"m_2450577039782362685row\">\r\n<tbody>\r\n<tr>\r\n<th class=\"m_2450577039782362685small-12 m_2450577039782362685columns\">\r\n<table style=\"width: 100.096%; height: 191.953px;\">\r\n<tbody>\r\n<tr style=\"height:22.3906px;\">\r\n<th style=\"width:97.6447%;height:22.3906px;\"> </th>\r\n</tr>\r\n<tr style=\"height:169.562px;\">\r\n<td style=\"width: 97.6447%; height: 169.562px;\">\r\n<p style=\"text-align:left;\">Hi {username},</p>\r\n<p class=\"m_2450577039782362685force-overleaf-style\" style=\"text-align:left;\">We need to verify your email address before you can access your dashboard.</p>\r\n<p class=\"m_2450577039782362685force-overleaf-style\" style=\"text-align:left;\">Please verify your email address by visiting the link below:</p>\r\n<p class=\"m_2450577039782362685force-overleaf-style\" style=\"text-align:left;\">{verification_link}.</p>\r\n<p class=\"m_2450577039782362685force-overleaf-style\" style=\"text-align:left;\">Thank you.<br />{website_title}</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</th>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</center></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><img class=\"CToWUd\" src=\"https://ci5.googleusercontent.com/proxy/_L2S_yn8V9jLvAeR1rLPF3qmrQLBqWlB2DJfAQ4SBEhv-VqAJHg0FK6cmT99y8m9R1G1BC_i2FWCFmHGlcjnIExwE3rNqaUN1-ayp0bawEaxVCbLEGpJ7JQDR4jbczNq_1DXjqcVXXnTza_LEegpL2x792ZGjaA8Y594GJqeVxtjqM2LA5kDTgdYFWW8sGb8UQzAetE2hKnCmyIkYvcqSFceBQcSFT_B7jgjI_qLUCiOPLf8IAudBTPMNjeesYBhKmRLScTVpcAyb1ASUfoBwueWDC3I8AHTpsbotgLJks5ipgbiZSINWL1bG_qw0pI_JbMPhCaSek6I-f4QsLYRd6oAUcdol5y2dXTkzr3WmL1K1lZ8lr1i6eJ8FDsTtGwlLTwxv9-kUCCT2UfqHxbUGnGTPYOHH74ytkpK=s0-d-e1-ft#http://email-link.overleaf.com/wf/open?upn=CB7nsy4cUUrMEy00dVC7xtkixf1jGRQiRmv9ytghPG-2F9iMBvteO1eyfwjvE7n-2FPrXViQOvivqNnn9vNEH7KuOUPk6gWzhzmBjtlf6gat86vo9nJtlVPWo-2BQ6DCAkJV4JpOTwpu0-2FMAzexK9bw6PGBTnX5GD5nNe2ed6hROW6IDmeUd0gh2F5IV42PVhMQ-2B0gYOp39DeLXW7PovcBulw-2BrA8qlCawgAjpBtNzRd-2Bl3Hk-3D\" alt=\"\" width=\"1\" height=\"1\" border=\"0\" /></p>'),
(5, 'reset_password', 'Recover Password of Your Account', '<table class=\"m_2450577039782362685body\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td align=\"center\" valign=\"top\" bgcolor=\"#F6F6F6\"><center>\r\n<table class=\"m_2450577039782362685container\" style=\"width: 78.3088%;\" align=\"center\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 100%;\">\r\n<table class=\"m_2450577039782362685row\">\r\n<tbody>\r\n<tr>\r\n<th class=\"m_2450577039782362685small-12 m_2450577039782362685columns\">\r\n<table style=\"width: 100.096%; height: 191.953px;\">\r\n<tbody>\r\n<tr style=\"height:22.3906px;\">\r\n<th style=\"width:97.6447%;height:22.3906px;\"> </th>\r\n</tr>\r\n<tr style=\"height:169.562px;\">\r\n<td style=\"width: 97.6447%; height: 169.562px;\">\r\n<p style=\"text-align:left;\">Hi {customer_name},</p>\r\n<p class=\"m_2450577039782362685force-overleaf-style\" style=\"text-align:left;\">We have received a request to reset your password. If you did not make the request, just ignore this email. Otherwise, you can reset your password using the below link.</p>\r\n<p class=\"m_2450577039782362685force-overleaf-style\" style=\"text-align:left;\">{password_reset_link}</p>\r\n<p class=\"m_2450577039782362685force-overleaf-style\" style=\"text-align:left;\">Thanks,<br />{website_title}</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</th>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</center></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><img class=\"CToWUd\" src=\"https://ci5.googleusercontent.com/proxy/_L2S_yn8V9jLvAeR1rLPF3qmrQLBqWlB2DJfAQ4SBEhv-VqAJHg0FK6cmT99y8m9R1G1BC_i2FWCFmHGlcjnIExwE3rNqaUN1-ayp0bawEaxVCbLEGpJ7JQDR4jbczNq_1DXjqcVXXnTza_LEegpL2x792ZGjaA8Y594GJqeVxtjqM2LA5kDTgdYFWW8sGb8UQzAetE2hKnCmyIkYvcqSFceBQcSFT_B7jgjI_qLUCiOPLf8IAudBTPMNjeesYBhKmRLScTVpcAyb1ASUfoBwueWDC3I8AHTpsbotgLJks5ipgbiZSINWL1bG_qw0pI_JbMPhCaSek6I-f4QsLYRd6oAUcdol5y2dXTkzr3WmL1K1lZ8lr1i6eJ8FDsTtGwlLTwxv9-kUCCT2UfqHxbUGnGTPYOHH74ytkpK=s0-d-e1-ft#http://email-link.overleaf.com/wf/open?upn=CB7nsy4cUUrMEy00dVC7xtkixf1jGRQiRmv9ytghPG-2F9iMBvteO1eyfwjvE7n-2FPrXViQOvivqNnn9vNEH7KuOUPk6gWzhzmBjtlf6gat86vo9nJtlVPWo-2BQ6DCAkJV4JpOTwpu0-2FMAzexK9bw6PGBTnX5GD5nNe2ed6hROW6IDmeUd0gh2F5IV42PVhMQ-2B0gYOp39DeLXW7PovcBulw-2BrA8qlCawgAjpBtNzRd-2Bl3Hk-3D\" alt=\"\" width=\"1\" height=\"1\" border=\"0\" /></p>'),
(11, 'multiday_rental_space_booking', 'We’ve Confirmed Your Space Booking', '<p>Hi {customer_name},</p>\r\n\r\n<p>We are pleased to inform you that your space has been successfully booked. Please find the invoice attached to this email.</p>\r\n\r\n<p><strong>Booking Number:</strong> #{booking_number}</p>\r\n\r\n<p><strong>Start Date:</strong> {start_date}</p>\r\n\r\n<p><strong>End Date:</strong> {end_date}</p>\r\n\r\n<p><strong>Number of Days:</strong> {number_of_day}</p>\r\n\r\n<p>For more details, you can view your booking information by clicking on the link below:</p>\r\n\r\n{booking_link} \r\n\r\n<p>Thank you for choosing {website_title}. If you have any questions, feel free to contact us.</p>\r\n\r\n<p>Best regards,<br />\r\n\r\nThe {website_title} Team</p>'),
(18, 'membership_extend', 'Your membership is extended', '<p>Hi {username},<br><br>This is a confirmation mail from us.<br>You have extended your membership.<br><strong>Package Title:</strong> {package_title}<br><strong>Package Price:</strong> {package_price}<br><strong>Activation Date:</strong> {activation_date}<br><strong>Expire Date:</strong> {expire_date}</p>\r\n<p> </p>\r\n<p>We have attached an invoice with this mail.<br>Thank you for your purchase.</p>\r\n<p><br>Best Regards,<br>{website_title}.</p>'),
(19, 'registration_with_premium_package', 'You have registered successfully', '<p>Hi {username},<br /><br />This is a confirmation mail from us</p>\r\n<p><strong><span style=\"font-size:18px;\">Membership Information:</span></strong><br /><strong>Package Title:</strong> {package_title}<br /><strong>Package Price:</strong> {package_price}</p>\r\n<p><span style=\"font-weight:600;\">Total:</span> {total}<br /><strong>Activation Date:</strong> {activation_date}<br /><strong>Expire Date:</strong> {expire_date}</p>\r\n<p> </p>\r\n<p>We have attached an invoice with this mail.<br />Thank you for your purchase.</p>\r\n<p><br />Best Regards,<br />{website_title}.</p>'),
(22, 'membership_expiry_reminder', 'Your membership will be expired soon', 'Hi {username},<br /><br />\n\nYour membership will be expired soon.<br />\nYour membership is valid till <strong>{last_day_of_membership}</strong><br />\nPlease click here - {login_link} to log into the dashboard to purchase a new package / extend the current package to extend your membership.<br /><br />\n\nBest Regards,<br />\n{website_title}.'),
(23, 'membership_expired', 'Your membership is expired', '<p>Hi {username},<br /><br />Your membership is expired.<br />Please click here - {login_link} to log into the dashboard to purchase a new package / extend the current package to continue the membership.<br /><br />Best Regards,<br />{website_title}.</p>'),
(24, 'payment_accepted_for_membership_extension_offline_gateway', 'Your payment for membership extension is accepted', '<p>Hi {username},<br><br>This is a confirmation mail from us.<br>Your payment has been accepted &amp; your membership is extended.<br><strong>Package Title:</strong> {package_title}<br><strong>Package Price:</strong> {package_price}<br><strong>Activation Date:</strong> {activation_date}<br><strong>Expire Date:</strong> {expire_date}</p>\r\n<p>Best Regards,<br>{website_title}.</p>'),
(25, 'payment_accepted_for_registration_offline_gateway', 'Your payment for registration is approved', '<p>Hi {username},<br /><br />\r\n\r\nThis is a confirmation mail from us.<br />\r\nYour payment has been accepted & now you can login to your user dashboard to build your portfolio website.<br />\r\n\r\n<strong>Package Title:</strong> {package_title}<br />\r\n<strong>Package Price:</strong> {package_price}<br />\r\n<strong>Activation Date:</strong> {activation_date}<br />\r\n<strong>Expire Date:</strong> {expire_date}</p><p><br /></p><p>We have attached an invoice with this mail.<br />\r\nThank you for your purchase.</p><p><br />\r\n\r\nBest Regards,<br />\r\n{website_title}.<br /></p>'),
(26, 'payment_rejected_for_membership_extension_offline_gateway', 'Your payment for membership extension is rejected', '<p>Hi {username},<br /><br />\r\n\r\nWe are sorry to inform you that your payment has been rejected<br />\r\n\r\n<strong>Package Title:</strong> {package_title}<br />\r\n<strong>Package Price:</strong> {package_price}<br />\r\n\r\nBest Regards,<br />\r\n{website_title}.<br /></p>'),
(27, 'payment_rejected_for_registration_offline_gateway', 'Your payment for registration is rejected', '<p>Hi {username},<br><br>We are sorry to inform you that your payment has been rejected<br><strong>Package Title:</strong> {package_title}<br><strong>Package Price:</strong> {package_price}<br>Best Regards,<br>{website_title}.</p>'),
(28, 'admin_changed_current_package', 'Admin has changed your current package', '<p>Hi {username},<br /><br />\r\n\r\nAdmin has changed your current package <b>({replaced_package})</b></p>\r\n<p><b>New Package Information:</b></p>\r\n<p>\r\n<strong>Package:</strong> {package_title}<br />\r\n<strong>Package Price:</strong> {package_price}<br />\r\n<strong>Activation Date:</strong> {activation_date}<br />\r\n<strong>Expire Date:</strong> {expire_date}</p><p><br /></p><p>We have attached an invoice with this mail.<br />\r\nThank you for your purchase.</p><p><br />\r\n\r\nBest Regards,<br />\r\n{website_title}.<br /></p>'),
(29, 'admin_added_current_package', 'Admin has added current package for you', '<p>Hi {username},<br /><br />\r\n\r\nAdmin has added current package for you</p><p><b><span style=\"font-size:18px;\">Current Membership Information:</span></b><br />\r\n<strong>Package Title:</strong> {package_title}<br />\r\n<strong>Package Price:</strong> {package_price}<br />\r\n<strong>Activation Date:</strong> {activation_date}<br />\r\n<strong>Expire Date:</strong> {expire_date}</p><p><br /></p><p>We have attached an invoice with this mail.<br />\r\nThank you for your purchase.</p><p><br />\r\n\r\nBest Regards,<br />\r\n{website_title}.<br /></p>'),
(30, 'admin_changed_next_package', 'Admin has changed your next package', '<p>Hi {username},<br /><br />\r\n\r\nAdmin has changed your next package <b>({replaced_package})</b></p><p><b><span style=\"font-size:18px;\">Next Membership Information:</span></b><br />\r\n<strong>Package Title:</strong> {package_title}<br />\r\n<strong>Package Price:</strong> {package_price}<br />\r\n<strong>Activation Date:</strong> {activation_date}<br />\r\n<strong>Expire Date:</strong> {expire_date}</p><p><br /></p><p>We have attached an invoice with this mail.<br />\r\nThank you for your purchase.</p><p><br />\r\n\r\nBest Regards,<br />\r\n{website_title}.<br /></p>'),
(31, 'admin_added_next_package', 'Admin has added next package for you', '<p>Hi {username},<br /><br />\r\n\r\nAdmin has added next package for you</p><p><b><span style=\"font-size:18px;\">Next Membership Information:</span></b><br />\r\n<strong>Package Title:</strong> {package_title}<br />\r\n<strong>Package Price:</strong> {package_price}<br />\r\n<strong>Activation Date:</strong> {activation_date}<br />\r\n<strong>Expire Date:</strong> {expire_date}</p><p><br /></p><p>We have attached an invoice with this mail.<br />\r\nThank you for your purchase.</p><p><br />\r\n\r\nBest Regards,<br />\r\n{website_title}.<br /></p>'),
(32, 'admin_removed_current_package', 'Admin has removed current package for you', '<p>Hi {username},<br /><br />\r\n\r\nAdmin has removed current package - <strong>{removed_package_title}</strong><br>\r\n\r\nBest Regards,<br />\r\n{website_title}.<br />'),
(33, 'admin_removed_next_package', 'Admin has removed next package for you', '<p>Hi {username},<br /><br />Admin has removed next package - <strong>{removed_package_title}</strong><br />Best Regards,<br />{website_title}.</p>'),
(34, 'withdraw_approve', 'Confirmation of Withdraw Approve', '<p>Hi {vendor_username},</p>\r\n<p>This email is confirm that your withdraw request  {withdraw_id} is approved. </p>\r\n<p>Your current balance is {current_balance}, withdraw amount {withdraw_amount}, charge : {charge},payable amount {payable_amount}</p>\r\n<p>withdraw method : {withdraw_method},</p>\r\n<p> </p>\r\n<p>Best Regards.<br />{website_title}</p>'),
(35, 'withdraw_rejected', 'Withdraw Request Rejected', '<p>Hi {vendor_username},</p>\r\n<p>This email is to confirm that your withdrawal request  {withdraw_id} is rejected and the balance added to your account. </p>\r\n<p>Your current balance is {current_balance}</p>\r\n<p> </p>\r\n<p>Best Regards.<br />{website_title}</p>'),
(36, 'balance_add', 'Balance Add', '<p>Hi {username}</p><p>{amount} added to your account.</p><p>Your current balance is {current_balance}. </p></p><p><br></p><p>Best Regards.<br>{website_title}<br></p><br>'),
(37, 'balance_subtract', 'Balance Subtract', '<p>Hi {username}</p>\n<p>{amount} subtract from your account.</p>\n<p>Your current balance is {current_balance}.</p>\n<p>Best Regards.<br />{website_title}</p>\n<p> </p>\n<p> </p>'),
(38, 'add_user_by_admin', 'Admin has been added your account', '<p>Hi {username},</p>\r\n<p>Admin has been added to your account as a \'{user_type}\'.</p>\r\n<p>Your username: {username} and password: #{password}</p>\r\n<p>Best regards.<br />{website_title}</p>'),
(39, 'product_order', 'Product Order Has Been Placed', '<p>Hi {customer_name},</p>\r\n<p>Your order has been placed successfully. We have attached an invoice in this mail.<br />Order No: #{order_number}</p>\r\n<p>{order_link}</p>\r\n<p>Best regards.<br />{website_title}</p>\r\n<p> </p>\r\n<p> </p>'),
(41, 'space_booking_rejected', 'Rejection of Space Booking', '<p>Hi <span style=\"font-weight:600;\">{customer_name}</span>,</p>\r\n<p>Your payment is not completed, thus we have rejected your Booking for the following space.</p>\r\n<p>Booking Number: #{booking_number}</p>\r\n<p>Space Title : {space_title}</p>\r\n<p>For further information, please do not hesitate to contact us.<br />{website_title}</p>'),
(42, 'quote_request', 'Your Quote Request Confirmation', '<p>Dear <span style=\"font-weight:600;\">{customer_name}</span>,</p>\r\n<p>Thank you for reaching out to us!  We have received your request for a quote, and we appreciate your interest in our services.</p>\r\n<p>Request Id: #{request_number}</p>\r\n<p>Title : {title}</p>\r\n<p>We are currently reviewing your request and will get back to you shortly with a detailed quote. If you have any questions in the meantime, please feel free to reply to this email or contact us.<br />Thank you for choosing {website_title}. We look forward to assisting you!<br />Best regards,<br />{website_title}</p>'),
(43, 'tour_request', 'Your Tour Request Confirmation', '<p>Dear <span style=\"font-weight:600;\">{customer_name}</span>,</p>\r\n<p>Thank you for reaching out to us!  We have received your request for a tour, and we appreciate your interest in our services.</p>\r\n<p>Request Id: #{request_number}</p>\r\n<p>Title : {title}</p>\r\n<p>We are currently reviewing your request and will get back to you shortly with a detailed quote. If you have any questions in the meantime, please feel free to reply to this email or contact us.<br />Thank you for choosing {website_title}. We look forward to assisting you!<br />Best regards,<br />{website_title}</p>'),
(44, 'quote_request_status', 'Your quote request status', '<p>Dear <span style=\"font-weight:600;\">{customer_name}</span>,</p>\r\n<p>Thank you for reaching out to us!  We have received your request for a quote, and we appreciate your interest in our services.</p>\r\n<p>Unfortunately, we regret to inform you that your quote request has been cancelled.</p>\r\n<p>Request Id: #{request_number}</p>\r\n<p>Title : {title}</p>\r\n<p>If you have any questions or need further assistance, please feel free to reply to this email or contact us.<br />Thank you for considering {website_title}. We hope to assist you in the future!<br />Best regards,<br />{website_title}</p>'),
(45, 'tour_request_cancel_status', 'Your Tour Request Status', '<p>Dear <span style=\"font-weight:600;\">{customer_name}</span>,</p>\r\n<p>Thank you for reaching out to us!  We have received your request for a tour, and we appreciate your interest in our services.</p>\r\n<p>Unfortunately, we regret to inform you that your tour request has been cancelled.</p>\r\n<p>Request Id: #{request_number}</p>\r\n<p>Title : {title}</p>\r\n<p>If you have any questions or need further assistance, please feel free to reply to this email or contact us.<br />Thank you for considering {website_title}. We hope to assist you in the future!<br />Best regards,<br />{website_title}</p>'),
(46, 'tour_request_confirm_status', 'Your Tour Request Status', '<p>Dear <span style=\"font-weight:600;\">{customer_name}</span>,</p>\r\n<p>Thank you for reaching out to us! We appreciate your interest in our space and are glad to confirm your visit.</p>\r\n<p>Request Id: #{request_number}</p>\r\n<p>Title : {title}</p>\r\n<p>Visit Date : {visit_date}</p>\r\n<p>Time : {visit_time}</p>\r\n<p>If you have any questions or need further assistance, please feel free to reply to this email or contact us.<br />Thank you for considering {website_title}. We hope to assist you in the future!<br />Best regards,<br />{website_title}</p>'),
(47, 'hourly_rental_space_booking', 'We’ve Confirmed Your Space Booking', '<p>Hi {customer_name},</p>\r\n<p>We are pleased to inform you that your space has been successfully booked. Please find the invoice attached to this email.</p>\r\n<p><strong>Booking Number:</strong> #{booking_number}</p>\r\n<p><strong>Booking Date:</strong> {booking_date}</p>\r\n<p><strong>Start Time:</strong> {start_time}</p>\r\n<p><strong>End Time:</strong> {end_time}</p>\r\n<p>For more details, you can view your booking information by clicking on the link below:</p>\r\n<p>{booking_link} </p>\r\n<p>Thank you for choosing {website_title}. If you have any questions, feel free to contact us.</p>\r\n<p>Best regards,<br />The {website_title} Team</p>'),
(50, 'fixed_time_slot_rental_space_booking', 'We’ve Confirmed Your Space Booking', '<p>Hi {customer_name},</p>\r\n\r\n<p>We are pleased to inform you that your space has been successfully booked. Please find the invoice attached to this email.</p>\r\n\r\n<p><strong>Booking Number:</strong> #{booking_number}</p>\r\n\r\n<p><strong>Booking Date:</strong> #{booking_date}</p>\r\n\r\n<p><strong>Start Time:</strong> {start_time}</p>\r\n\r\n<p><strong>End Time:</strong> {end_time}</p>\r\n\r\n<p>For more details, you can view your booking information by clicking on the link below:</p>\r\n\r\n{booking_link}\r\n\r\n<p>Thank you for choosing {website_title}. If you have any questions, feel free to contact us.</p>\r\n\r\n<p>Best regards,<br />\r\n\r\nThe {website_title} Team</p>'),
(51, 'vendor_space_booking_notification', 'Congratulations! Your Space Has Been Reserved', '<p>Hi {vendor_name},</p>\r\n<p>We are happy to inform you that your space, <strong>{space_title}</strong>, has been successfully booked by a customer.</p>\r\n<p><strong>Booking Number:</strong> #{booking_number}</p>\r\n<p><strong>Booking Date:</strong> {booking_date}</p>\r\n<p><strong>Customer Name:</strong> {customer_name}</p>\r\n<p><strong>Customer Email:</strong> {customer_email}</p>\r\n<p>You can view more details of this booking by clicking the link below:</p>\r\n<p>{booking_link}</p>\r\n<p>If you have any questions, feel free to contact us.</p>\r\n<p>Best regards,<br />The {website_title} Team</p>'),
(52, 'quote_request_cancel_status', 'Your Quote Request Status', '<p>Dear <span style=\"font-weight:600;\">{customer_name}</span>,</p>\r\n<p>Thank you for reaching out to us!  We have received your request for a quote, and we appreciate your interest in our services.</p>\r\n<p>Unfortunately, we regret to inform you that your tour request has been cancelled.</p>\r\n<p>Request Id: #{request_number}</p>\r\n<p>Title : {title}</p>\r\n<p>If you have any questions or need further assistance, please feel free to reply to this email or contact us.<br />Thank you for considering {website_title}. We hope to assist you in the future!<br />Best regards,<br />{website_title}</p>'),
(53, 'featured_request_payment_rejected', 'Your Feature Request for Space Has Been Rejected', '<p>Hi <span style=\"font-weight:600;\">{vendor_name}</span>,</p>\r\n<p>Thank you for your recent feature request regarding the implementation of the space feature. We appreciate the time and effort you put into your proposal.</p>\r\n<p>Regrettably, we must inform you that your feature request has been rejected. This decision was made after careful consideration of our current priorities and resources.</p>\r\n<p>For further information, please do not hesitate to contact us.</p>\r\n<p>Request Id: #{request_id}</p>\r\n<p>Amount : {amount}</p>\r\n<p>Space Title : {space_title}</p>\r\n<p>Best Regards,</p>\r\n<p>{website_title}</p>\r\n<p> </p>'),
(54, 'featured_request_payment_approved', 'Your Feature Request for Space Has Been Approved', '<p>Hi <span style=\"font-weight:600;\">{vendor_name}</span>,</p>\r\n<p>Thank you for your recent feature request regarding the implementation of the space feature. We appreciate the time and effort you put into your proposal.</p>\r\n<p>We are pleased to inform you that your feature request has been approved! We believe this feature will add significant value and enhance our offerings.</p>\r\n<p>For further information, please do not hesitate to contact us.</p>\r\n<p>Request Id: #{request_id}</p>\r\n<p>Amount : {amount}</p>\r\n<p>Space Title : {space_title}</p>\r\n<p>Best Regards,</p>\r\n<p>{website_title}</p>\r\n<p> </p>');

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE `memberships` (
  `id` bigint UNSIGNED NOT NULL,
  `price` double DEFAULT NULL,
  `currency` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0' COMMENT '0 = pending, 1=completed, 2 = rejected',
  `is_trial` tinyint NOT NULL DEFAULT '0',
  `trial_days` int NOT NULL DEFAULT '0',
  `receipt` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `transaction_details` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `settings` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `package_id` bigint DEFAULT NULL,
  `seller_id` bigint DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `invoice` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `conversation_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `modified` tinyint DEFAULT NULL COMMENT '1 - modified by Admin, 0 - not modified by Admin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `memberships`
--

INSERT INTO `memberships` (`id`, `price`, `currency`, `currency_symbol`, `payment_method`, `transaction_id`, `status`, `is_trial`, `trial_days`, `receipt`, `transaction_details`, `settings`, `package_id`, `seller_id`, `start_date`, `expire_date`, `invoice`, `conversation_id`, `modified`, `created_at`, `updated_at`) VALUES
(6, 1499, 'USD', '$', 'Stripe', 'a916b6dd', 1, 0, 0, NULL, '{\"id\":\"ch_3S5RHwJlIV5dN9n70gxjdwPG\",\"object\":\"charge\",\"amount\":149900,\"amount_captured\":149900,\"amount_refunded\":0,\"application\":null,\"application_fee\":null,\"application_fee_amount\":null,\"balance_transaction\":\"txn_3S5RHwJlIV5dN9n70Z1Zi4ZW\",\"billing_details\":{\"address\":{\"city\":null,\"country\":null,\"line1\":null,\"line2\":null,\"postal_code\":\"12345\",\"state\":null},\"email\":null,\"name\":null,\"phone\":null,\"tax_id\":null},\"calculated_statement_descriptor\":\"Stripe\",\"captured\":true,\"created\":1757423328,\"currency\":\"usd\",\"customer\":null,\"description\":\"Start Your Membership Journey\",\"destination\":null,\"dispute\":null,\"disputed\":false,\"failure_balance_transaction\":null,\"failure_code\":null,\"failure_message\":null,\"fraud_details\":[],\"invoice\":null,\"livemode\":false,\"metadata\":{\"customer_name\":\"Marcus Chen\"},\"on_behalf_of\":null,\"order\":null,\"outcome\":{\"advice_code\":null,\"network_advice_code\":null,\"network_decline_code\":null,\"network_status\":\"approved_by_network\",\"reason\":null,\"risk_level\":\"normal\",\"risk_score\":63,\"seller_message\":\"Payment complete.\",\"type\":\"authorized\"},\"paid\":true,\"payment_intent\":null,\"payment_method\":\"card_1S5RHuJlIV5dN9n7bC2Vte9b\",\"payment_method_details\":{\"card\":{\"amount_authorized\":149900,\"authorization_code\":\"295365\",\"brand\":\"visa\",\"checks\":{\"address_line1_check\":null,\"address_postal_code_check\":\"pass\",\"cvc_check\":\"pass\"},\"country\":\"US\",\"exp_month\":12,\"exp_year\":2027,\"extended_authorization\":{\"status\":\"disabled\"},\"fingerprint\":\"WXDgVUSzrY61Nnm6\",\"funding\":\"credit\",\"incremental_authorization\":{\"status\":\"unavailable\"},\"installments\":null,\"last4\":\"4242\",\"mandate\":null,\"multicapture\":{\"status\":\"unavailable\"},\"network\":\"visa\",\"network_token\":{\"used\":false},\"network_transaction_id\":\"878868103868583\",\"overcapture\":{\"maximum_amount_capturable\":149900,\"status\":\"unavailable\"},\"regulated_status\":\"unregulated\",\"three_d_secure\":null,\"wallet\":null},\"type\":\"card\"},\"receipt_email\":\"rakibul01072019@gmail.com\",\"receipt_number\":null,\"receipt_url\":\"https:\\/\\/pay.stripe.com\\/receipts\\/payment\\/CAcaFwoVYWNjdF8xQXplbzNKbElWNWROOW43KODNgMYGMgamWpY3lEU6LBYsHj1wLSUIC7PIm5Lxt7pxxMbOIipZFO3xZOBzjCQHu17kkYU5xZJSapoV\",\"refunded\":false,\"refunds\":{\"object\":\"list\",\"data\":[],\"has_more\":false,\"total_count\":0,\"url\":\"\\/v1\\/charges\\/ch_3S5RHwJlIV5dN9n70gxjdwPG\\/refunds\"},\"review\":null,\"shipping\":null,\"source\":{\"id\":\"card_1S5RHuJlIV5dN9n7bC2Vte9b\",\"object\":\"card\",\"address_city\":null,\"address_country\":null,\"address_line1\":null,\"address_line1_check\":null,\"address_line2\":null,\"address_state\":null,\"address_zip\":\"12345\",\"address_zip_check\":\"pass\",\"allow_redisplay\":\"unspecified\",\"brand\":\"Visa\",\"country\":\"US\",\"customer\":null,\"cvc_check\":\"pass\",\"dynamic_last4\":null,\"exp_month\":12,\"exp_year\":2027,\"fingerprint\":\"WXDgVUSzrY61Nnm6\",\"funding\":\"credit\",\"last4\":\"4242\",\"metadata\":[],\"name\":null,\"regulated_status\":\"unregulated\",\"tokenization_method\":null,\"wallet\":null},\"source_transfer\":null,\"statement_descriptor\":null,\"statement_descriptor_suffix\":null,\"status\":\"succeeded\",\"transfer_data\":null,\"transfer_group\":null}', '{\"id\":2,\"uniqid\":12345,\"favicon\":\"68a07fae20f2e.png\",\"logo\":\"68a07fae212f0.png\",\"website_title\":\"SpaceKoi\",\"email_address\":\"demo@spacekoi.com\",\"contact_number\":\"+1-202-555-0109\",\"address\":\"450 Young Road, New York, USA\",\"latitude\":\"34.05224\",\"longitude\":\"-118.24368\",\"theme_version\":1,\"base_currency_symbol\":\"$\",\"base_currency_symbol_position\":\"left\",\"base_currency_text\":\"USD\",\"base_currency_text_position\":\"right\",\"base_currency_rate\":\"1.00\",\"primary_color\":\"C50942\",\"secondary_color\":\"160828\",\"breadcrumb_overlay_color\":\"000000\",\"breadcrumb_overlay_opacity\":\"0.60\",\"smtp_status\":1,\"smtp_host\":\"smtp.gmail.com\",\"smtp_port\":587,\"encryption\":\"TLS\",\"smtp_username\":\"ranaahmed269205@gmail.com\",\"smtp_password\":\"afiw ynhq tjuj vdwa\",\"from_mail\":\"ranaahmed269205@gmail.com\",\"from_name\":\"spacekoi\",\"to_mail\":\"rakibul01072019@gmail.com\",\"breadcrumb\":\"68a741eb5cbdd.png\",\"disqus_status\":1,\"disqus_short_name\":\"spacekoi\",\"google_recaptcha_status\":1,\"google_recaptcha_site_key\":\"6Lesf5gpAAAAAHZsex91ZKdNR8MX8FMQw46CGPs0\",\"google_recaptcha_secret_key\":\"6Lesf5gpAAAAAP8bh4vwnRTxdRiTcO81DhMS8UXm\",\"whatsapp_status\":1,\"whatsapp_number\":\"01931341253\",\"whatsapp_header_title\":\"Hi, there!\",\"whatsapp_popup_status\":1,\"whatsapp_popup_message\":\"If you have any issues, let us know.\",\"maintenance_img\":\"68ab0165433f3.png\",\"maintenance_status\":0,\"maintenance_msg\":\"We are upgrading our site. We will come back soon. \\r\\nPlease stay with us.\\r\\nThank you.\",\"bypass_token\":\"rakib\",\"footer_logo\":\"68a087f76e487.png\",\"admin_theme_version\":\"dark\",\"notification_image\":\"619b7d5e5e9df.png\",\"google_adsense_publisher_id\":null,\"hero_section_background_img\":\"68a185e4693dd.jpg\",\"about_section_image\":\"68ab197a4dfee.png\",\"about_section_video_link\":null,\"work_process_background_img\":\"625bae6fd72f0.jpg\",\"testimonial_bg_img\":\"68a18b4a3c30e.jpg\",\"facebook_login_status\":0,\"facebook_app_id\":\"415655527803766\",\"facebook_app_secret\":\"a0c446544eaaf35713de739be5dc22e8\",\"google_login_status\":1,\"google_client_id\":\"1028456015138-2ig40jpn9gaj7bq6kefbmsjt149me75v.apps.googleusercontent.com\",\"google_client_secret\":\"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\",\"hero_section_foreground_img\":\"68a184caa4191.jpg\",\"hero_section_foreground_img_theme_3\":\"68a17d9a843dd.png\",\"hero_section_foreground_img_theme_3_left\":\"68a17d9a85520.png\",\"hero_video_url\":null,\"newsletter_bg_img\":\"62f09aacaaa98.png\",\"banner_section_bg_img\":\"68a0acec72584.jpg\",\"banner_section_foreground_img\":\"68a0acec740c4.png\",\"footer_section_bg_img\":\"68a087f76e088.jpg\",\"seller_email_verification\":1,\"seller_admin_approval\":1,\"admin_approval_notice\":\"Unfortunately, your account is deactive now. please get in touch with admin.\",\"expiration_reminder\":3,\"tax\":10,\"space_units\":\"Sqft\",\"life_time_earning\":0,\"total_profit\":0,\"shop_status\":1,\"is_shop_rating\":1,\"product_tax_amount\":\"10.00\",\"created_at\":\"2023-12-03 12:27:43\",\"updated_at\":\"2023-12-03 12:27:43\",\"video_banner_section_image\":\"68a18cc6f05ef.jpg\",\"video_banner_video_link\":\"https:\\/\\/www.youtube.com\\/watch?v=dBeWl6yGE3Y\",\"category_section_background\":null,\"fixed_time_slot_rental\":1,\"hourly_rental\":1,\"multi_day_rental\":1,\"guest_checkout_status\":1,\"admin_profile\":1,\"google_map_api_key\":\"AIzaSyBh-Q9sZzK43b6UssN6vCDrdwgWv4NOL68\",\"google_map_api_key_status\":1,\"google_map_radius\":\"1500\",\"time_format\":\"12h\",\"time_zone\":\"51\",\"preloader_status\":1,\"preloader\":\"68a0847cbdf8e.gif\",\"package_features\":\"[\\\"spaces\\\",\\\"slider_images_per_space\\\",\\\"services_per_space\\\",\\\"variants_per_service\\\",\\\"amenities_per_space\\\",\\\"support_tickets\\\",\\\"add_booking\\\",\\\"fixed_timeslot_rental\\\",\\\"hourly_rental\\\",\\\"multi_day_rental\\\"]\",\"is_language\":1}', 1000008, 67, '2025-09-09', '9999-12-31', 'a916b6dd68c026e1001cd.pdf', NULL, NULL, '2025-09-09 13:08:48', '2025-09-09 13:08:49'),
(7, 1499, 'USD', '$', 'Stripe', 'd437af1e', 1, 0, 0, NULL, '{\"id\":\"ch_3S5RjfJlIV5dN9n70gUq4eFj\",\"object\":\"charge\",\"amount\":149900,\"amount_captured\":149900,\"amount_refunded\":0,\"application\":null,\"application_fee\":null,\"application_fee_amount\":null,\"balance_transaction\":\"txn_3S5RjfJlIV5dN9n70kujXI8D\",\"billing_details\":{\"address\":{\"city\":null,\"country\":null,\"line1\":null,\"line2\":null,\"postal_code\":\"12345\",\"state\":null},\"email\":null,\"name\":null,\"phone\":null,\"tax_id\":null},\"calculated_statement_descriptor\":\"Stripe\",\"captured\":true,\"created\":1757425047,\"currency\":\"usd\",\"customer\":null,\"description\":\"Start Your Membership Journey\",\"destination\":null,\"dispute\":null,\"disputed\":false,\"failure_balance_transaction\":null,\"failure_code\":null,\"failure_message\":null,\"fraud_details\":[],\"invoice\":null,\"livemode\":false,\"metadata\":{\"customer_name\":\"Priya  Sharma\"},\"on_behalf_of\":null,\"order\":null,\"outcome\":{\"advice_code\":null,\"network_advice_code\":null,\"network_decline_code\":null,\"network_status\":\"approved_by_network\",\"reason\":null,\"risk_level\":\"normal\",\"risk_score\":0,\"seller_message\":\"Payment complete.\",\"type\":\"authorized\"},\"paid\":true,\"payment_intent\":null,\"payment_method\":\"card_1S5RjeJlIV5dN9n7V5f07vNi\",\"payment_method_details\":{\"card\":{\"amount_authorized\":149900,\"authorization_code\":\"702057\",\"brand\":\"visa\",\"checks\":{\"address_line1_check\":null,\"address_postal_code_check\":\"pass\",\"cvc_check\":\"pass\"},\"country\":\"US\",\"exp_month\":12,\"exp_year\":2027,\"extended_authorization\":{\"status\":\"disabled\"},\"fingerprint\":\"WXDgVUSzrY61Nnm6\",\"funding\":\"credit\",\"incremental_authorization\":{\"status\":\"unavailable\"},\"installments\":null,\"last4\":\"4242\",\"mandate\":null,\"multicapture\":{\"status\":\"unavailable\"},\"network\":\"visa\",\"network_token\":{\"used\":false},\"network_transaction_id\":\"878868103868583\",\"overcapture\":{\"maximum_amount_capturable\":149900,\"status\":\"unavailable\"},\"regulated_status\":\"unregulated\",\"three_d_secure\":null,\"wallet\":null},\"type\":\"card\"},\"receipt_email\":\"priya@gmail.com\",\"receipt_number\":null,\"receipt_url\":\"https:\\/\\/pay.stripe.com\\/receipts\\/payment\\/CAcaFwoVYWNjdF8xQXplbzNKbElWNWROOW43KJjbgMYGMgZd8BQ6eJ86LBbULa-kx2IpMOAlorB62ML1Htchwy6T25oXi5McF80z5cBAuNGkddpJ3tNa\",\"refunded\":false,\"refunds\":{\"object\":\"list\",\"data\":[],\"has_more\":false,\"total_count\":0,\"url\":\"\\/v1\\/charges\\/ch_3S5RjfJlIV5dN9n70gUq4eFj\\/refunds\"},\"review\":null,\"shipping\":null,\"source\":{\"id\":\"card_1S5RjeJlIV5dN9n7V5f07vNi\",\"object\":\"card\",\"address_city\":null,\"address_country\":null,\"address_line1\":null,\"address_line1_check\":null,\"address_line2\":null,\"address_state\":null,\"address_zip\":\"12345\",\"address_zip_check\":\"pass\",\"allow_redisplay\":\"unspecified\",\"brand\":\"Visa\",\"country\":\"US\",\"customer\":null,\"cvc_check\":\"pass\",\"dynamic_last4\":null,\"exp_month\":12,\"exp_year\":2027,\"fingerprint\":\"WXDgVUSzrY61Nnm6\",\"funding\":\"credit\",\"last4\":\"4242\",\"metadata\":[],\"name\":null,\"regulated_status\":\"unregulated\",\"tokenization_method\":null,\"wallet\":null},\"source_transfer\":null,\"statement_descriptor\":null,\"statement_descriptor_suffix\":null,\"status\":\"succeeded\",\"transfer_data\":null,\"transfer_group\":null}', '{\"id\":2,\"uniqid\":12345,\"favicon\":\"68a07fae20f2e.png\",\"logo\":\"68a07fae212f0.png\",\"website_title\":\"SpaceKoi\",\"email_address\":\"demo@spacekoi.com\",\"contact_number\":\"+1-202-555-0109\",\"address\":\"450 Young Road, New York, USA\",\"latitude\":\"34.05224\",\"longitude\":\"-118.24368\",\"theme_version\":1,\"base_currency_symbol\":\"$\",\"base_currency_symbol_position\":\"left\",\"base_currency_text\":\"USD\",\"base_currency_text_position\":\"right\",\"base_currency_rate\":\"1.00\",\"primary_color\":\"C50942\",\"secondary_color\":\"160828\",\"breadcrumb_overlay_color\":\"000000\",\"breadcrumb_overlay_opacity\":\"0.60\",\"smtp_status\":1,\"smtp_host\":\"smtp.gmail.com\",\"smtp_port\":587,\"encryption\":\"TLS\",\"smtp_username\":\"ranaahmed269205@gmail.com\",\"smtp_password\":\"afiw ynhq tjuj vdwa\",\"from_mail\":\"ranaahmed269205@gmail.com\",\"from_name\":\"spacekoi\",\"to_mail\":\"rakibul01072019@gmail.com\",\"breadcrumb\":\"68a741eb5cbdd.png\",\"disqus_status\":1,\"disqus_short_name\":\"spacekoi\",\"google_recaptcha_status\":1,\"google_recaptcha_site_key\":\"6Lesf5gpAAAAAHZsex91ZKdNR8MX8FMQw46CGPs0\",\"google_recaptcha_secret_key\":\"6Lesf5gpAAAAAP8bh4vwnRTxdRiTcO81DhMS8UXm\",\"whatsapp_status\":1,\"whatsapp_number\":\"01931341253\",\"whatsapp_header_title\":\"Hi, there!\",\"whatsapp_popup_status\":1,\"whatsapp_popup_message\":\"If you have any issues, let us know.\",\"maintenance_img\":\"68ab0165433f3.png\",\"maintenance_status\":0,\"maintenance_msg\":\"We are upgrading our site. We will come back soon. \\r\\nPlease stay with us.\\r\\nThank you.\",\"bypass_token\":\"rakib\",\"footer_logo\":\"68a087f76e487.png\",\"admin_theme_version\":\"dark\",\"notification_image\":\"619b7d5e5e9df.png\",\"google_adsense_publisher_id\":null,\"hero_section_background_img\":\"68a185e4693dd.jpg\",\"about_section_image\":\"68ab197a4dfee.png\",\"about_section_video_link\":null,\"work_process_background_img\":\"625bae6fd72f0.jpg\",\"testimonial_bg_img\":\"68a18b4a3c30e.jpg\",\"facebook_login_status\":0,\"facebook_app_id\":\"415655527803766\",\"facebook_app_secret\":\"a0c446544eaaf35713de739be5dc22e8\",\"google_login_status\":1,\"google_client_id\":\"1028456015138-2ig40jpn9gaj7bq6kefbmsjt149me75v.apps.googleusercontent.com\",\"google_client_secret\":\"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\",\"hero_section_foreground_img\":\"68a184caa4191.jpg\",\"hero_section_foreground_img_theme_3\":\"68a17d9a843dd.png\",\"hero_section_foreground_img_theme_3_left\":\"68a17d9a85520.png\",\"hero_video_url\":null,\"newsletter_bg_img\":\"62f09aacaaa98.png\",\"banner_section_bg_img\":\"68a0acec72584.jpg\",\"banner_section_foreground_img\":\"68a0acec740c4.png\",\"footer_section_bg_img\":\"68a087f76e088.jpg\",\"seller_email_verification\":1,\"seller_admin_approval\":1,\"admin_approval_notice\":\"Unfortunately, your account is deactive now. please get in touch with admin.\",\"expiration_reminder\":3,\"tax\":10,\"space_units\":\"Sqft\",\"life_time_earning\":5390.77,\"total_profit\":2315.77,\"shop_status\":1,\"is_shop_rating\":1,\"product_tax_amount\":\"10.00\",\"created_at\":\"2023-12-03 12:27:43\",\"updated_at\":\"2023-12-03 12:27:43\",\"video_banner_section_image\":\"68a18cc6f05ef.jpg\",\"video_banner_video_link\":\"https:\\/\\/www.youtube.com\\/watch?v=dBeWl6yGE3Y\",\"category_section_background\":null,\"fixed_time_slot_rental\":1,\"hourly_rental\":1,\"multi_day_rental\":1,\"guest_checkout_status\":1,\"admin_profile\":1,\"google_map_api_key\":\"AIzaSyBh-Q9sZzK43b6UssN6vCDrdwgWv4NOL68\",\"google_map_api_key_status\":1,\"google_map_radius\":\"1500\",\"time_format\":\"12h\",\"time_zone\":\"51\",\"preloader_status\":1,\"preloader\":\"68a0847cbdf8e.gif\",\"package_features\":\"[\\\"spaces\\\",\\\"slider_images_per_space\\\",\\\"services_per_space\\\",\\\"variants_per_service\\\",\\\"amenities_per_space\\\",\\\"support_tickets\\\",\\\"add_booking\\\",\\\"fixed_timeslot_rental\\\",\\\"hourly_rental\\\",\\\"multi_day_rental\\\"]\",\"is_language\":1}', 1000008, 68, '2025-09-09', '9999-12-31', 'd437af1e68c02d98c9484.pdf', NULL, NULL, '2025-09-09 13:37:28', '2025-09-09 13:37:29'),
(8, 1499, 'USD', '$', 'Zelle', 'b248ae0d', 1, 0, 0, NULL, '\"offline\"', '{\"id\":2,\"uniqid\":12345,\"favicon\":\"68a07fae20f2e.png\",\"logo\":\"68a07fae212f0.png\",\"website_title\":\"SpaceKoi\",\"email_address\":\"demo@spacekoi.com\",\"contact_number\":\"+1-202-555-0109\",\"address\":\"450 Young Road, New York, USA\",\"latitude\":\"34.05224\",\"longitude\":\"-118.24368\",\"theme_version\":1,\"base_currency_symbol\":\"$\",\"base_currency_symbol_position\":\"left\",\"base_currency_text\":\"USD\",\"base_currency_text_position\":\"right\",\"base_currency_rate\":\"1.00\",\"primary_color\":\"C50942\",\"secondary_color\":\"160828\",\"breadcrumb_overlay_color\":\"000000\",\"breadcrumb_overlay_opacity\":\"0.60\",\"smtp_status\":1,\"smtp_host\":\"smtp.gmail.com\",\"smtp_port\":587,\"encryption\":\"TLS\",\"smtp_username\":\"ranaahmed269205@gmail.com\",\"smtp_password\":\"afiw ynhq tjuj vdwa\",\"from_mail\":\"ranaahmed269205@gmail.com\",\"from_name\":\"spacekoi\",\"to_mail\":\"rakibul01072019@gmail.com\",\"breadcrumb\":\"68a741eb5cbdd.png\",\"disqus_status\":1,\"disqus_short_name\":\"spacekoi\",\"google_recaptcha_status\":1,\"google_recaptcha_site_key\":\"6Lesf5gpAAAAAHZsex91ZKdNR8MX8FMQw46CGPs0\",\"google_recaptcha_secret_key\":\"6Lesf5gpAAAAAP8bh4vwnRTxdRiTcO81DhMS8UXm\",\"whatsapp_status\":1,\"whatsapp_number\":\"01931341253\",\"whatsapp_header_title\":\"Hi, there!\",\"whatsapp_popup_status\":1,\"whatsapp_popup_message\":\"If you have any issues, let us know.\",\"maintenance_img\":\"68ab0165433f3.png\",\"maintenance_status\":0,\"maintenance_msg\":\"We are upgrading our site. We will come back soon. \\r\\nPlease stay with us.\\r\\nThank you.\",\"bypass_token\":\"rakib\",\"footer_logo\":\"68a087f76e487.png\",\"admin_theme_version\":\"dark\",\"notification_image\":\"619b7d5e5e9df.png\",\"google_adsense_publisher_id\":null,\"hero_section_background_img\":\"68a185e4693dd.jpg\",\"about_section_image\":\"68ab197a4dfee.png\",\"about_section_video_link\":null,\"work_process_background_img\":\"625bae6fd72f0.jpg\",\"testimonial_bg_img\":\"68a18b4a3c30e.jpg\",\"facebook_login_status\":0,\"facebook_app_id\":\"415655527803766\",\"facebook_app_secret\":\"a0c446544eaaf35713de739be5dc22e8\",\"google_login_status\":1,\"google_client_id\":\"1028456015138-2ig40jpn9gaj7bq6kefbmsjt149me75v.apps.googleusercontent.com\",\"google_client_secret\":\"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\",\"hero_section_foreground_img\":\"68a184caa4191.jpg\",\"hero_section_foreground_img_theme_3\":\"68a17d9a843dd.png\",\"hero_section_foreground_img_theme_3_left\":\"68a17d9a85520.png\",\"hero_video_url\":null,\"newsletter_bg_img\":\"62f09aacaaa98.png\",\"banner_section_bg_img\":\"68a0acec72584.jpg\",\"banner_section_foreground_img\":\"68a0acec740c4.png\",\"footer_section_bg_img\":\"68a087f76e088.jpg\",\"seller_email_verification\":1,\"seller_admin_approval\":1,\"admin_approval_notice\":\"Unfortunately, your account is deactive now. please get in touch with admin.\",\"expiration_reminder\":3,\"tax\":10,\"space_units\":\"Sqft\",\"life_time_earning\":6949.77,\"total_profit\":3874.77,\"shop_status\":1,\"is_shop_rating\":1,\"product_tax_amount\":\"10.00\",\"created_at\":\"2023-12-03 12:27:43\",\"updated_at\":\"2023-12-03 12:27:43\",\"video_banner_section_image\":\"68a18cc6f05ef.jpg\",\"video_banner_video_link\":\"https:\\/\\/www.youtube.com\\/watch?v=dBeWl6yGE3Y\",\"category_section_background\":null,\"fixed_time_slot_rental\":1,\"hourly_rental\":1,\"multi_day_rental\":1,\"guest_checkout_status\":1,\"admin_profile\":1,\"google_map_api_key\":\"AIzaSyBh-Q9sZzK43b6UssN6vCDrdwgWv4NOL68\",\"google_map_api_key_status\":1,\"google_map_radius\":\"1500\",\"time_format\":\"12h\",\"time_zone\":\"51\",\"preloader_status\":1,\"preloader\":\"68a0847cbdf8e.gif\",\"package_features\":\"[\\\"spaces\\\",\\\"slider_images_per_space\\\",\\\"services_per_space\\\",\\\"variants_per_service\\\",\\\"amenities_per_space\\\",\\\"support_tickets\\\",\\\"add_booking\\\",\\\"fixed_timeslot_rental\\\",\\\"hourly_rental\\\",\\\"multi_day_rental\\\"]\",\"is_language\":1}', 1000008, 66, '2025-09-09', '9999-12-31', 'b248ae0d68c02e6a635bf.pdf', NULL, NULL, '2025-09-09 13:40:48', '2025-09-09 13:41:03');

-- --------------------------------------------------------

--
-- Table structure for table `menu_builders`
--

CREATE TABLE `menu_builders` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `menus` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `menu_builders`
--

INSERT INTO `menu_builders` (`id`, `language_id`, `menus`, `created_at`, `updated_at`) VALUES
(3, 51, '[{\"text\":\"Home\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"home\"},{\"text\":\"Spaces\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"spaces\"},{\"text\":\"Pricing\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"pricing\"},{\"text\":\"Vendors\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"vendors\"},{\"text\":\"Shop\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"shop\",\"children\":[{\"text\":\"Product\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"product\"},{\"text\":\"Cart\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"cart\"},{\"text\":\"Checkout\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"checkout\"}]},{\"text\":\"Blog\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"blog\"},{\"text\":\"Pages\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"custom\",\"children\":[{\"text\":\"About  Us\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"about-us\"},{\"text\":\"FAQ\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"faq\"},{\"text\":\"Terms & Conditions\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"terms--conditions\"}]},{\"text\":\"Contact\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"contact\"}]', '2022-05-11 03:26:11', '2025-08-28 07:55:06'),
(43, 53, '[{\"text\":\"بيت\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"home\"},{\"text\":\"المساحات\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"spaces\"},{\"text\":\"التسعير\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"pricing\"},{\"text\":\"البائعين\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"vendors\"},{\"text\":\"محل\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"shop\",\"children\":[{\"type\":\"product\",\"text\":\"منتج\",\"target\":\"_self\"},{\"text\":\"عربة\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"cart\"},{\"text\":\"الدفع\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"checkout\"}]},{\"text\":\"مدونة\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"blog\"},{\"text\":\"الصفحات\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"pages\",\"children\":[{\"text\":\"معلومات عنا\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"about-us\"},{\"text\":\"التعليمات\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"faq\"},{\"text\":\"الشروط والأحكام\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"الشروط-والأحكام\"}]},{\"text\":\"اتصال\",\"href\":\"\",\"icon\":\"empty\",\"target\":\"_self\",\"title\":\"\",\"type\":\"contact\"}]', '2025-08-17 08:11:26', '2025-08-28 08:42:31');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2023_12_30_110825_create_cta_section_infos_table', 1),
(3, '2024_03_06_020617_add_description_to_features_table', 2),
(4, '2024_03_06_030022_add_number_to_features_table', 3),
(7, '2024_03_09_003246_create_about_contents_table', 4),
(10, '2024_03_14_020708_create_contacts_table', 5),
(11, '2024_03_14_034738_create_contact_contents_table', 6),
(12, '2024_03_16_031928_create_feature_links_table', 7),
(14, '2024_03_25_034939_create_service_category_contents_table', 8),
(15, '2024_03_31_052859_create_sub_service_contents_table', 9),
(16, '2024_03_31_080456_create_subservices_table', 10),
(19, '2024_04_18_031036_create_space_bookings_table', 11),
(22, '2024_04_24_013810_create_space_amenities_table', 12),
(24, '2024_03_21_044934_create_spaces_table', 13),
(25, '2024_04_25_024223_create_space_contents_table', 14),
(26, '2024_04_25_062229_create_space_services_table', 15),
(28, '2024_04_25_072945_create_space_service_contents_table', 16),
(31, '2024_04_27_073615_create_time_slots_table', 17),
(32, '2024_04_27_054522_create_global_days_table', 18),
(33, '2024_05_11_040117_create_space_reviews_table', 19),
(36, '2024_05_18_075420_create_space_sub_categories_table', 20),
(37, '2024_05_18_072358_create_space_categories_table', 21),
(38, '2024_05_20_093923_create_countries_table', 22),
(39, '2024_05_20_104345_create_states_table', 23),
(41, '2024_05_20_104848_create_cities_table', 24),
(42, '2024_06_03_050049_create_space_wishlists_table', 25),
(43, '2024_06_04_075917_create_feature_charges_table', 26),
(44, '2024_06_04_090638_create_space_features_table', 27),
(46, '2024_07_16_061854_create_popular_city_sections_table', 28),
(47, '2021_02_01_030511_create_payment_invoices_table', 29),
(48, '2024_09_05_094248_create_space_settings_table', 30),
(49, '2024_09_08_022609_create_get_quotes_table', 31),
(50, '2024_09_09_022506_create_book_for_tours_table', 32),
(52, '2024_12_24_123650_create_space_coupons_table', 33),
(53, '2024_12_30_124708_create_coupon_usages_table', 34),
(54, '2025_02_28_001825_create_space_holidays_table', 35),
(55, '2025_07_14_004734_create_additional_sections_table', 36),
(56, '2025_07_14_005530_create_additional_section_contents_table', 36);

-- --------------------------------------------------------

--
-- Table structure for table `offline_gateways`
--

CREATE TABLE `offline_gateways` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `short_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `instructions` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 -> gateway is deactive, 1 -> gateway is active.',
  `has_attachment` tinyint(1) NOT NULL COMMENT '0 -> do not need attachment, 1 -> need attachment.',
  `serial_number` mediumint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_gateways`
--

CREATE TABLE `online_gateways` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `information` mediumtext,
  `status` tinyint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `online_gateways`
--

INSERT INTO `online_gateways` (`id`, `name`, `keyword`, `information`, `status`) VALUES
(1, 'PayPal', 'paypal', '{\"sandbox_status\":\"0\",\"client_id\":\"1111\",\"client_secret\":\"1111\"}', 0),
(2, 'Instamojo', 'instamojo', '{\"sandbox_status\":\"0\",\"key\":\"1111\",\"token\":\"11111\"}', 0),
(3, 'Paystack', 'paystack', '{\"key\":\"11111\"}', 0),
(4, 'Flutterwave', 'flutterwave', '{\"public_key\":\"1111\",\"secret_key\":\"1111\"}', 0),
(5, 'Razorpay', 'razorpay', '{\"key\":\"1111\",\"secret\":\"1111\"}', 0),
(6, 'MercadoPago', 'mercadopago', '{\"sandbox_status\":\"0\",\"token\":\"111111\"}', 0),
(7, 'Mollie', 'mollie', '{\"key\":\"1111\"}', 0),
(8, 'Stripe', 'stripe', '{\"key\":\"1111\",\"secret\":\"1111\"}', 0),
(9, 'Paytm', 'paytm', '{\"environment\":\"production\",\"merchant_key\":\"1111\",\"merchant_mid\":\"1111\",\"merchant_website\":\"1111\",\"industry_type\":\"demo\"}', 0),
(10, 'Authorize.Net', 'authorize.net', '{\"sandbox_status\":\"0\",\"api_login_id\":\"1111\",\"transaction_key\":\"1111\",\"public_client_key\":\"1111\"}', 0),
(11, 'Yoco', 'yoco', '{\"secret_key\":\"1111\"}', 0),
(12, 'Xendit', 'xendit', '{\"secret_key\":\"1111\"}', 0),
(13, 'Phone Pe', 'phonepe', '{\"merchant_id\":\"11111\",\"sandbox_status\":\"0\",\"salt_key\":\"demo\",\"salt_index\":\"1111\"}', 0),
(14, 'Toyyibpay', 'toyyibpay', '{\"sandbox_status\":\"0\",\"secret_key\":\"demo\",\"category_code\":\"1111\"}', 0),
(15, 'Iyzico', 'iyzico', '{\"api_key\":\"1111\",\"secret_key\":\"1111\",\"sandbox_status\":null,\"iyzico_mode\":\"0\"}', 0),
(16, 'Midtrans', 'midtrans', '{\"server_key\":\"11111\",\"midtrans_mode\":\"0\"}', 0),
(17, 'MyFatoorah', 'myfatoorah', '{\"token\":\"1111\",\"sandbox_status\":\"0\"}', 0),
(18, 'Paytabs', 'paytabs', '{\"server_key\":\"1111\",\"profile_id\":\"111\",\"country\":\"global\",\"api_endpoint\":\"demo url\"}', 0),
(19, 'Perfect Money', 'perfect_money', '{\"perfect_money_wallet_id\":\"1111\"}', 0),
(20, 'Freshpay', 'freshpay', '{\"merchant_id\":\"1111\",\"merchant_secrete\":\"1111\",\"firstname\":\"John\",\"lastname\":\"Doe\",\"email\":\"admin@example.com\"}', 0);

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `price` double NOT NULL DEFAULT '0',
  `package_feature` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `popular` int DEFAULT NULL,
  `number_of_space` int DEFAULT NULL,
  `number_of_service_per_space` int DEFAULT NULL,
  `number_of_option_per_service` int DEFAULT NULL,
  `number_of_slider_image_per_space` int DEFAULT NULL,
  `number_of_amenities_per_space` int DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `term` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `number_of_service_add` int DEFAULT '0',
  `number_of_service_featured` int DEFAULT '0',
  `number_of_form_add` int DEFAULT '0',
  `number_of_service_order` int NOT NULL DEFAULT '0',
  `live_chat_status` int DEFAULT '0',
  `qr_builder_status` int DEFAULT '0',
  `qr_code_save_limit` int DEFAULT '0',
  `custom_features` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `is_trial` int DEFAULT NULL,
  `recommended` int DEFAULT '0',
  `trial_days` int DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `title`, `price`, `package_feature`, `popular`, `number_of_space`, `number_of_service_per_space`, `number_of_option_per_service`, `number_of_slider_image_per_space`, `number_of_amenities_per_space`, `icon`, `term`, `number_of_service_add`, `number_of_service_featured`, `number_of_form_add`, `number_of_service_order`, `live_chat_status`, `qr_builder_status`, `qr_code_save_limit`, `custom_features`, `is_trial`, `recommended`, `trial_days`, `status`, `created_at`, `updated_at`) VALUES
(999999, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lifetime', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 1, '2025-08-17 11:54:31', '2025-08-17 11:54:31'),
(1000000, 'Basic', 19, '[\"Support Tickets\",\"Fixed Timeslot Rental\"]', NULL, 6, 4, 4, 4, 4, 'fas fa-seedling', 'monthly', 0, 0, 0, 0, 0, 0, 0, '', NULL, 0, NULL, 1, '2025-08-21 15:26:14', '2025-09-08 12:45:44'),
(1000001, 'Standard', 49, '[\"Support Tickets\",\"Fixed Timeslot Rental\",\"Multi Day Rental\"]', NULL, 10, 8, 8, 6, 7, 'fas fa-briefcase', 'monthly', 0, 0, 0, 0, 0, 0, 0, '', NULL, 1, NULL, 1, '2025-08-21 15:33:27', '2025-09-08 12:47:31'),
(1000002, 'Premium', 99, '[\"Support Tickets\",\"Add Booking\",\"Fixed Timeslot Rental\",\"Hourly Rental\",\"Multi Day Rental\"]', NULL, 999999, 999999, 999999, 999999, 999999, 'fas fa-building', 'monthly', 0, 0, 0, 0, 0, 0, 0, '', NULL, 0, NULL, 1, '2025-08-21 15:36:27', '2025-09-08 12:49:13'),
(1000003, 'Basic', 199, '[\"Support Tickets\",\"Fixed Timeslot Rental\"]', NULL, 6, 4, 4, 4, 4, 'fas fa-calendar-alt', 'yearly', 0, 0, 0, 0, 0, 0, 0, '', NULL, 0, NULL, 1, '2025-08-21 15:39:57', '2025-09-08 12:50:54'),
(1000004, 'Standard', 499, '[\"Support Tickets\",\"Fixed Timeslot Rental\",\"Multi Day Rental\"]', NULL, 10, 6, 8, 8, 7, 'fas fa-chart-line', 'yearly', 0, 0, 0, 0, 0, 0, 0, '', NULL, 1, NULL, 1, '2025-08-21 15:41:41', '2025-09-08 12:52:15'),
(1000005, 'Premium', 499, '[\"Support Tickets\",\"Add Booking\",\"Fixed Timeslot Rental\",\"Hourly Rental\",\"Multi Day Rental\"]', NULL, 999999, 999999, 999999, 999999, 999999, 'fas fa-crown', 'yearly', 0, 0, 0, 0, 0, 0, 0, '', NULL, 0, NULL, 1, '2025-08-21 15:44:04', '2025-09-08 12:52:49'),
(1000006, 'Basic', 0, '[\"Fixed Timeslot Rental\"]', NULL, 2, 2, 2, 2, 2, 'fas fa-infinity', 'lifetime', 0, 0, 0, 0, 0, 0, 0, '', NULL, 0, NULL, 1, '2025-08-21 15:48:19', '2025-09-08 12:57:29'),
(1000007, 'Golden', 999, '[\"Add Booking\",\"Fixed Timeslot Rental\",\"Hourly Rental\"]', NULL, 10, 15, 15, 8, 8, 'fas fa-gem', 'lifetime', 0, 0, 0, 0, 0, 0, 0, '', NULL, 1, NULL, 1, '2025-08-21 15:49:38', '2025-09-08 13:03:02'),
(1000008, 'Platinum', 1499, '[\"Support Tickets\",\"Add Booking\",\"Fixed Timeslot Rental\",\"Hourly Rental\",\"Multi Day Rental\"]', NULL, 999999, 999999, 999999, 999999, 999999, 'fas fa-star-and-crescent', 'lifetime', 0, 0, 0, 0, 0, 0, 0, '', NULL, 0, NULL, 1, '2025-08-21 15:50:39', '2025-09-08 13:03:33');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `status` tinyint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `status`, `created_at`, `updated_at`) VALUES
(26, 1, '2025-08-24 13:46:15', '2025-08-24 13:46:15');

-- --------------------------------------------------------

--
-- Table structure for table `page_contents`
--

CREATE TABLE `page_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `page_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keywords` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `page_contents`
--

INSERT INTO `page_contents` (`id`, `language_id`, `page_id`, `title`, `slug`, `content`, `meta_keywords`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 51, 26, 'Terms & Conditions', 'terms--conditions', '<p><strong>Welcome to SpaceKoi!</strong><br />By accessing or using our website and services, you agree to comply with and be bound by the following terms and conditions. Please read them carefully before using our platform.</p>\r\n<h4>1. <strong>Acceptance of Terms</strong></h4>\r\n<p>By registering or using our services, you confirm that you accept these Terms &amp; Conditions. If you do not agree, please do not use our platform. These terms may be updated periodically, and continued use implies acceptance of the updated terms.</p>\r\n<h4>2. <strong>Use of Services</strong></h4>\r\n<p>You must use our website only for lawful purposes. You are responsible for providing accurate information when creating an account, booking spaces, or requesting quotes. You agree not to misuse the website, hack accounts, or interfere with other users’ experience.</p>\r\n<h4>3. <strong>Account Registration</strong></h4>\r\n<p>Users may be required to create an account to access certain features. You must provide valid information and keep your login credentials secure. You are responsible for all activities under your account.</p>\r\n<h4>4. <strong>Booking &amp; Payments</strong></h4>\r\n<p>All bookings are subject to availability. Prices, fees, and terms may change without notice. Payments must be made through authorized gateways. Refunds, cancellations, and disputes are governed by our <strong>Cancellation &amp; Refund Policy</strong>, which is part of these terms.</p>\r\n<h4>5. <strong>Digital Content &amp; Services</strong></h4>\r\n<p>Some services or add-ons may include digital content (templates, guides, PDFs). You may download and use these for personal or business purposes only. Unauthorized distribution, resale, or reproduction is prohibited.</p>\r\n<h4>6. <strong>Vendor &amp; Space Responsibility</strong></h4>\r\n<p>Vendors and space providers are responsible for delivering the services as described. We act as a platform connecting users and vendors and are not liable for any service delivery issues, accidents, or damages occurring on-site.</p>\r\n<h4>7. <strong>User Conduct</strong></h4>\r\n<p>Users must respect other participants, staff, and vendors. Harassment, offensive content, or illegal activity is strictly prohibited. Violation may result in account suspension or termination.</p>\r\n<h4>8. <strong>Privacy &amp; Data Protection</strong></h4>\r\n<p>We collect personal information to provide services. Data usage is governed by our <strong>Privacy Policy</strong>. Users consent to the storage and processing of data in accordance with applicable laws.</p>\r\n<h4>9. <strong>Intellectual Property</strong></h4>\r\n<p>All content on the website, including text, images, logos, and software, is owned by [Your Company Name] or its partners. Copying, reproducing, or distributing without permission is prohibited.</p>\r\n<h4>10. <strong>Limitation of Liability</strong></h4>\r\n<p>We are not liable for indirect, incidental, or consequential damages arising from using our website or services. Users acknowledge that use of services is at their own risk.</p>\r\n<h4>11. <strong>Third-Party Links &amp; Services</strong></h4>\r\n<p>Our website may contain links to third-party services. We are not responsible for their content or services. Users must review third-party terms and conditions before use.</p>\r\n<h4>12. <strong>Cancellation &amp; Refunds</strong></h4>\r\n<p>Refunds and cancellations are processed according to our <strong>Cancellation Policy</strong>. Users are responsible for reviewing policies before booking.</p>\r\n<h4>13. <strong>Termination of Services</strong></h4>\r\n<p>We reserve the right to suspend or terminate accounts that violate our terms, misuse services, or engage in fraudulent activity.</p>\r\n<h4>14. <strong>Governing Law</strong></h4>\r\n<p>These terms are governed by the laws of [Your Country]. Any disputes will be resolved under the jurisdiction of [Your City/Court].</p>\r\n<h4>15. <strong>Amendments</strong></h4>\r\n<p>We may update these terms at any time. Users are encouraged to review this page regularly. Continued use implies acceptance of changes.</p>\r\n<h4>16. <strong>Contact Us</strong></h4>\r\n<p>For questions regarding terms, bookings, or services, contact us at:<br /><strong>Email:</strong> support@[yourwebsite].com<br /><strong>Phone:</strong> +123-456-7890</p>\r\n<p>By using our platform, you agree to all these terms and ensure compliance with rules, safety, and proper conduct while using the website or visiting any booked space.</p>', NULL, NULL, '2025-08-24 13:46:15', '2025-08-24 13:46:15'),
(2, 53, 26, 'الشروط والأحكام', 'الشروط-والأحكام', '<p>باستخدامك لموقعنا وخدماتنا، فإنك توافق على الامتثال لهذه الشروط والأحكام. يرجى قراءتها بعناية قبل استخدام المنصة.</p>\r\n<h4>1. <strong>قبول الشروط</strong></h4>\r\n<p>باستخدامك خدماتنا أو التسجيل فيها، فإنك تؤكد قبولك لهذه الشروط والأحكام. إذا لم توافق، يرجى عدم استخدام المنصة. قد يتم تحديث هذه الشروط دوريًا، والاستخدام المستمر يعني الموافقة على التحديثات.</p>\r\n<h4>2. <strong>استخدام الخدمات</strong></h4>\r\n<p>يجب استخدام الموقع لأغراض قانونية فقط. أنت مسؤول عن تقديم معلومات دقيقة عند إنشاء حساب، حجز الأماكن، أو طلب عروض الأسعار. لا يجوز إساءة استخدام الموقع أو محاولة اختراق الحسابات أو التأثير على تجربة المستخدمين الآخرين.</p>\r\n<h4>3. <strong>تسجيل الحساب</strong></h4>\r\n<p>قد يُطلب من المستخدمين إنشاء حساب للوصول إلى بعض الميزات. يجب تقديم معلومات صحيحة وحماية بيانات تسجيل الدخول. أنت مسؤول عن جميع الأنشطة تحت حسابك.</p>\r\n<h4>4. <strong>الحجز والدفع</strong></h4>\r\n<p>تخضع جميع الحجوزات للتوافر. قد تتغير الأسعار والرسوم والشروط دون إشعار. يجب إجراء الدفعات عبر بوابات الدفع المصرح بها. تخضع الاستردادات والإلغاءات والنزاعات لسياسة <strong>الإلغاء والاسترداد</strong> الخاصة بنا، والتي تعتبر جزءًا من هذه الشروط.</p>\r\n<h4>5. <strong>المحتوى الرقمي والخدمات</strong></h4>\r\n<p>قد تتضمن بعض الخدمات أو الإضافات محتوى رقمي (قوالب، أدلة، PDF). يمكنك تنزيلها واستخدامها للأغراض الشخصية أو التجارية فقط. يمنع التوزيع أو إعادة البيع أو النسخ بدون إذن.</p>\r\n<h4>6. <strong>مسؤولية البائع والمكان</strong></h4>\r\n<p>البائعون ومقدمو الخدمات مسؤولون عن تقديم الخدمات كما هو موضح. نحن نعمل كمنصة تربط بين المستخدمين والبائعين ولسنا مسؤولين عن أي مشاكل في تقديم الخدمة، حوادث أو أضرار تحدث في الموقع.</p>\r\n<h4>7. <strong>سلوك المستخدم</strong></h4>\r\n<p>يجب على المستخدمين احترام المشاركين الآخرين والموظفين والبائعين. يُمنع بشدة التحرش، المحتوى المسيء، أو النشاط غير القانوني. قد يؤدي الانتهاك إلى تعليق أو إيقاف الحساب.</p>\r\n<h4>8. <strong>الخصوصية وحماية البيانات</strong></h4>\r\n<p>نقوم بجمع المعلومات الشخصية لتقديم الخدمات. تخضع البيانات لسياسة <strong>الخصوصية</strong> الخاصة بنا. يوافق المستخدمون على تخزين ومعالجة البيانات وفقًا للقوانين المعمول بها.</p>\r\n<h4>9. <strong>حقوق الملكية الفكرية</strong></h4>\r\n<p>جميع محتويات الموقع، بما في ذلك النصوص والصور والشعارات والبرمجيات، مملوكة لـ [اسم شركتك] أو شركائها. يُحظر النسخ أو التوزيع بدون إذن.</p>\r\n<h4>10. <strong>حدود المسؤولية</strong></h4>\r\n<p>نحن غير مسؤولين عن أي أضرار غير مباشرة أو عرضية أو تبعية ناتجة عن استخدام الموقع أو الخدمات. يقر المستخدمون بأن استخدام الخدمات يكون على مسؤوليتهم الخاصة.</p>\r\n<h4>11. <strong>روابط وخدمات الطرف الثالث</strong></h4>\r\n<p>قد يحتوي الموقع على روابط لخدمات طرف ثالث. نحن غير مسؤولين عن محتواها أو خدماتها. يجب على المستخدمين مراجعة شروط الطرف الثالث قبل الاستخدام.</p>\r\n<h4>12. <strong>الإلغاء والاسترداد</strong></h4>\r\n<p>تتم معالجة الاستردادات والإلغاءات وفقًا لسياسة <strong>الإلغاء</strong> الخاصة بنا. يتحمل المستخدمون مسؤولية مراجعة السياسات قبل الحجز.</p>\r\n<h4>13. <strong>إنهاء الخدمات</strong></h4>\r\n<p>نحتفظ بالحق في تعليق أو إنهاء الحسابات التي تنتهك الشروط أو تستخدم الخدمات بطريقة غير مشروعة أو تشارك في أنشطة احتيالية.</p>\r\n<h4>14. <strong>القانون الواجب التطبيق</strong></h4>\r\n<p>تخضع هذه الشروط لقوانين [بلدك]. يتم حل أي نزاعات تحت ولاية [مدينتك/المحكمة].</p>\r\n<h4>15. <strong>التعديلات</strong></h4>\r\n<p>قد نقوم بتحديث هذه الشروط في أي وقت. يُنصح المستخدمون بمراجعة هذه الصفحة بانتظام. يشير الاستخدام المستمر إلى قبول التغييرات.</p>\r\n<h4>16. <strong>اتصل بنا</strong></h4>\r\n<p>لأي استفسارات حول الشروط، الحجز، أو الخدمات، تواصل معنا:<br /><strong>البريد الإلكتروني:</strong> support@[yourwebsite].com<br /><strong>الهاتف:</strong> +123-456-7890</p>\r\n<p>باستخدام المنصة، فإنك توافق على جميع هذه الشروط وتضمن الامتثال للقوانين والسلامة والسلوك السليم أثناء استخدام الموقع أو زيارة أي مكان تم حجزه.</p>', NULL, NULL, '2025-08-24 13:46:15', '2025-08-24 13:46:15');

-- --------------------------------------------------------

--
-- Table structure for table `page_headings`
--

CREATE TABLE `page_headings` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `blog_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `post_details_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `contact_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `error_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `faq_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `forget_password_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `login_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `signup_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `cart_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `checkout_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `spaces_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `space_details_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `about_us_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `vendor_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `space_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `seller_login_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `seller_signup_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `seller_forget_password_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pricing_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `customer_dashboard_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `customer_booking_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `customer_booking_details_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `customer_order_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `customer_order_details_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `customer_wishlist_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `customer_edit_profile_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `customer_change_password_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `shop_page_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `page_headings`
--

INSERT INTO `page_headings` (`id`, `language_id`, `blog_page_title`, `post_details_page_title`, `contact_page_title`, `error_page_title`, `faq_page_title`, `forget_password_page_title`, `login_page_title`, `signup_page_title`, `cart_page_title`, `checkout_page_title`, `spaces_page_title`, `space_details_page_title`, `created_at`, `updated_at`, `about_us_page_title`, `vendor_page_title`, `space_page_title`, `seller_login_page_title`, `seller_signup_page_title`, `seller_forget_password_page_title`, `pricing_page_title`, `customer_dashboard_page_title`, `customer_booking_page_title`, `customer_booking_details_page_title`, `customer_order_page_title`, `customer_order_details_page_title`, `customer_wishlist_page_title`, `customer_edit_profile_page_title`, `customer_change_password_page_title`, `shop_page_title`) VALUES
(8, 8, 'Blog', 'Post Details', 'Contact', '404', 'FAQ', 'Forget Password', 'Login', 'Signup', 'Cart', NULL, 'Spaces', 'Details', '2022-01-10 05:21:48', '2025-07-14 04:05:10', 'About', 'Vendors', 'Spaces', 'Vendor Login', 'Signup', 'Forget Password', 'Pricing', 'Dashboard', 'Space Bookings', 'Booking Details', 'My Orders', 'Order Details', 'Space Wishlists', 'Edit Profile', 'Change Password', 'Shop'),
(10, 41, 'تفاصيل المدونة', 'تفاصيل المدونة', 'الاتصال', 'خطأ', 'الأسئلة المتداولة', 'نسيت كلمة المرور', 'دخول العميل', 'دخول العميل', 'عَرَبَة نَقْل', NULL, 'خدمات', 'خدمات', '2024-07-27 20:44:19', '2025-01-15 07:23:53', 'معلومات عنا', 'بائع', 'المساحات', 'خدمات', 'دخول العميل', 'نسيت كلمة المرور', 'التسعير', 'لوحة المعلومات', 'حجوزاتي', 'تفاصيل الحجز', 'طلباتي', 'تفاصيل الطلب', 'قوائم الرغبات الخاصة بي', 'تحرير الملف الشخصي', 'تغيير كلمة المرور', 'دكان'),
(11, 51, 'Blog', 'Post Details', 'Contact', '404', 'FAQ', 'Forget Password', 'Login', 'Signup', 'Cart', 'Checkout', 'Spaces', 'Space Details', '2025-08-24 12:37:11', '2025-09-08 13:22:16', 'About Us', 'Vendors', 'Spaces', 'Vendor Login', 'Vendor Signup', 'Vendor Forgot Password', 'Pricing', 'Dashboard', 'Booking', 'Booking Details', 'Orders', 'Order Details', 'Wishlist', 'Edit Profile', 'Change Password', 'Shop');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` bigint NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_invoices`
--

CREATE TABLE `payment_invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `InvoiceId` bigint UNSIGNED NOT NULL,
  `InvoiceStatus` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `InvoiceValue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `InvoiceDisplayValue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `TransactionId` bigint UNSIGNED NOT NULL,
  `TransactionStatus` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `PaymentGateway` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `PaymentId` bigint UNSIGNED NOT NULL,
  `CardNumber` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `popular_city_sections`
--

CREATE TABLE `popular_city_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `popups`
--

CREATE TABLE `popups` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `type` smallint UNSIGNED NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `background_color` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `background_color_opacity` decimal(3,2) UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `button_text` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `button_color` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `delay` int UNSIGNED NOT NULL COMMENT 'value will be in milliseconds',
  `serial_number` mediumint UNSIGNED NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '0 => deactive, 1 => active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `popups`
--

INSERT INTO `popups` (`id`, `language_id`, `type`, `image`, `name`, `background_color`, `background_color_opacity`, `title`, `text`, `button_text`, `button_color`, `button_url`, `end_date`, `end_time`, `delay`, `serial_number`, `status`, `created_at`, `updated_at`) VALUES
(7, 51, 1, '1628593512.jpg', 'Black Friday', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1500, 1, 0, '2021-08-10 05:05:12', '2024-08-01 00:42:11'),
(8, 51, 2, '1628593631.jpg', 'Month End Sale', '451D53', 0.80, 'ENJOY 10% OFF', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 'Shop Now', '451D53', 'http://example.com/', NULL, NULL, 2000, 2, 0, '2021-08-10 05:07:11', '2024-01-06 09:50:57'),
(10, 51, 3, '1628682131.jpg', 'Summer Sale', 'DC143C', 0.70, 'Newsletter', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 'Subscribe', 'DC143C', NULL, NULL, NULL, 2000, 3, 0, '2021-08-11 05:42:11', '2024-01-06 19:05:23'),
(11, 51, 4, '1628685488.jpg', 'Winter Offer', NULL, NULL, 'Get 10% off your first order', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt', 'Shop Now', 'FF2865', 'http://example.com/', NULL, NULL, 1500, 4, 0, '2021-08-11 06:38:08', '2025-08-31 10:24:18'),
(12, 51, 5, '1628685866.jpg', 'Winter Sale', NULL, NULL, 'Get 10% off your first order', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt', 'Subscribe', 'F8960D', NULL, NULL, NULL, 2000, 5, 0, '2021-08-11 06:44:26', '2025-08-31 10:24:16'),
(13, 51, 6, '1628686132.jpg', 'New Arrivals Sale', NULL, NULL, 'Hurry, Sales Ends This Friday', 'This is your last chance to save 30%', 'Yes, I Want to Save 30%', '29A19C', 'http://example.com/', '2026-07-22', '10:00:00', 2000, 6, 0, '2021-08-11 06:48:52', '2025-08-31 10:19:31'),
(14, 51, 7, '1628687716.jpg', 'Flash Sale', '930077', NULL, 'Hurry, Sale Ends This Friday', 'This is your last chance to save 30%', 'Yes, I Want to Save 30%', 'FA00CA', 'http://example.com/', '2025-11-27', '12:00:00', 1500, 7, 0, '2021-08-11 07:15:16', '2025-08-31 10:24:14');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint UNSIGNED NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `serial_number` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `image`, `serial_number`, `status`, `created_at`, `updated_at`) VALUES
(1, '68ab22c8a5f03.png', 1, 1, '2025-08-24 14:33:44', '2025-08-24 14:33:44'),
(2, '68ab240c23164.png', 2, 1, '2025-08-24 14:39:08', '2025-08-24 14:39:08'),
(3, '68ab259b4fcf9.png', 3, 1, '2025-08-24 14:45:47', '2025-08-24 14:45:47'),
(4, '68ab27ef13094.png', 4, 1, '2025-08-24 14:55:43', '2025-08-24 14:55:43');

-- --------------------------------------------------------

--
-- Table structure for table `post_informations`
--

CREATE TABLE `post_informations` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `blog_category_id` bigint UNSIGNED DEFAULT NULL,
  `post_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `author` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `tags` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keywords` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `post_informations`
--

INSERT INTO `post_informations` (`id`, `language_id`, `blog_category_id`, `post_id`, `title`, `slug`, `author`, `content`, `tags`, `meta_keywords`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 51, 1, 1, '10 Tips for Planning a Perfect Wedding', '10-tips-for-planning-a-perfect-wedding', 'Rakib Hasan', '<p>Planning a wedding can be exciting yet overwhelming. To make your special day smooth and memorable, here are <strong>10 essential tips</strong>:</p>\r\n<p><strong>1. Set a Realistic Budget</strong><br />Start by deciding how much you can spend. Allocate funds for venue, catering, decor, photography, and other essentials to avoid overspending.</p>\r\n<p><strong>2. Choose the Right Venue</strong><br />Select a venue that fits your style, guest count, and budget. Consider both indoor and outdoor options for flexibility.</p>\r\n<p><strong>3. Plan Your Guest List</strong><br />Keep a clear guest list to manage invitations, seating arrangements, and catering efficiently. This also helps control costs.</p>\r\n<p><strong>4. Hire Reliable Vendors</strong><br />Research and book trusted vendors for catering, decoration, photography, and entertainment. Check reviews and past work to ensure quality.</p>\r\n<p><strong>5. Personalize Your Theme</strong><br />Incorporate personal touches into your wedding theme — colors, floral arrangements, and décor that reflect your style and personality.</p>\r\n<p><strong>6. Plan a Detailed Schedule</strong><br />Create a timeline for the ceremony, reception, and other activities. Share it with vendors and wedding party to keep everything organized.</p>\r\n<p><strong>7. Consider Guest Comfort</strong><br />Think about seating, weather, parking, and amenities to make your guests feel comfortable throughout the event.</p>\r\n<p><strong>8. Prepare for Contingencies</strong><br />Have a backup plan for weather, vendor cancellations, or last-minute emergencies. This ensures the day goes smoothly.</p>\r\n<p><strong>9. Don’t Forget Photography &amp; Videography</strong><br />Capture every moment by hiring professional photographers and videographers. Their work will be cherished for years.</p>\r\n<p><strong>10. Enjoy Your Day</strong><br />Finally, don’t stress over small details. Focus on celebrating love, family, and friends. Your happiness will make the day truly memorable.</p>', NULL, NULL, NULL, '2025-08-24 14:33:44', '2025-08-24 14:33:44'),
(2, 53, 2, 1, '10 نصائح لتخطيط زفاف مثالي', '10-نصائح-لتخطيط-زفاف-مثالي', 'رقيب حسن', '<p>يمكن أن يكون تخطيط الزفاف مثيرًا ولكنه قد يكون مرهقًا أيضًا. لجعل يومك الخاص سلسًا ولا يُنسى، إليك <strong>10 نصائح أساسية</strong>:</p>\r\n<p><strong>1. تحديد ميزانية واقعية</strong><br />ابدأ بتحديد المبلغ الذي يمكنك إنفاقه. خصص الأموال للمكان، الطعام، الديكور، التصوير، وغيرها لتجنب الإنفاق الزائد.</p>\r\n<p><strong>2. اختيار المكان المناسب</strong><br />اختر مكانًا يناسب أسلوبك وعدد الضيوف والميزانية. ضع في اعتبارك الخيارات الداخلية والخارجية للمرونة.</p>\r\n<p><strong>3. التخطيط لقائمة الضيوف</strong><br />احتفظ بقائمة واضحة للضيوف لإدارة الدعوات وترتيبات الجلوس والطعام بكفاءة. هذا يساعد أيضًا في التحكم بالتكاليف.</p>\r\n<p><strong>4. استئجار بائعين موثوقين</strong><br />ابحث واحجز بائعين موثوقين للطعام والديكور والتصوير والترفيه. تحقق من التقييمات وأعمالهم السابقة لضمان الجودة.</p>\r\n<p><strong>5. تخصيص ثيم الزفاف</strong><br />قم بإضافة لمسات شخصية لثيم الزفاف — الألوان، ترتيب الزهور، والديكور الذي يعكس أسلوبك وشخصيتك.</p>\r\n<p><strong>6. التخطيط لجدول مفصل</strong><br />قم بإنشاء جدول زمني للحفل، الاستقبال، والأنشطة الأخرى. شاركه مع البائعين وفريق الزفاف للحفاظ على التنظيم.</p>\r\n<p><strong>7. مراعاة راحة الضيوف</strong><br />فكر في الجلوس، الطقس، مواقف السيارات، والمرافق لجعل الضيوف يشعرون بالراحة طوال الحدث.</p>\r\n<p><strong>8. التحضير للطوارئ</strong><br />ضع خطة بديلة للطقس أو إلغاء البائعين أو الطوارئ في اللحظة الأخيرة. هذا يضمن سير اليوم بسلاسة.</p>\r\n<p><strong>9. لا تنس التصوير والفيديو</strong><br />قم بالتقاط كل اللحظات من خلال توظيف مصورين وفيديوغرافيين محترفين. ستكون أعمالهم ذكرى ثمينة لسنوات.</p>\r\n<p><strong>10. استمتع بيومك</strong><br />أخيرًا، لا تشغل بالك بالتفاصيل الصغيرة. ركز على الاحتفال بالحب والعائلة والأصدقاء. سعادتك ستجعل اليوم لا يُنسى حقًا.</p>', NULL, NULL, NULL, '2025-08-24 14:33:44', '2025-08-24 14:33:44'),
(3, 51, 1, 2, 'Budget-Friendly Event Planning Guide', 'budget-friendly-event-planning-guide', 'Rakib Hasan', '<p>Planning a wedding can be exciting yet overwhelming. To make your special day smooth and memorable, here are <strong>10 essential tips</strong>:</p>\r\n<p><strong>1. Set a Realistic Budget</strong><br />Start by deciding how much you can spend. Allocate funds for venue, catering, decor, photography, and other essentials to avoid overspending.</p>\r\n<p><strong>2. Choose the Right Venue</strong><br />Select a venue that fits your style, guest count, and budget. Consider both indoor and outdoor options for flexibility.</p>\r\n<p><strong>3. Plan Your Guest List</strong><br />Keep a clear guest list to manage invitations, seating arrangements, and catering efficiently. This also helps control costs.</p>\r\n<p><strong>4. Hire Reliable Vendors</strong><br />Research and book trusted vendors for catering, decoration, photography, and entertainment. Check reviews and past work to ensure quality.</p>\r\n<p><strong>5. Personalize Your Theme</strong><br />Incorporate personal touches into your wedding theme — colors, floral arrangements, and décor that reflect your style and personality.</p>\r\n<p><strong>6. Plan a Detailed Schedule</strong><br />Create a timeline for the ceremony, reception, and other activities. Share it with vendors and wedding party to keep everything organized.</p>\r\n<p><strong>7. Consider Guest Comfort</strong><br />Think about seating, weather, parking, and amenities to make your guests feel comfortable throughout the event.</p>\r\n<p><strong>8. Prepare for Contingencies</strong><br />Have a backup plan for weather, vendor cancellations, or last-minute emergencies. This ensures the day goes smoothly.</p>\r\n<p><strong>9. Don’t Forget Photography &amp; Videography</strong><br />Capture every moment by hiring professional photographers and videographers. Their work will be cherished for years.</p>\r\n<p><strong>10. Enjoy Your Day</strong><br />Finally, don’t stress over small details. Focus on celebrating love, family, and friends. Your happiness will make the day truly memorable.</p>', NULL, NULL, NULL, '2025-08-24 14:39:08', '2025-08-24 14:39:08'),
(4, 53, 2, 2, 'دليل تخطيط الفعاليات بأسلوب اقتصادي', 'دليل-تخطيط-الفعاليات-بأسلوب-اقتصادي', 'رقيب حسن', '<p>يمكن أن يكون تخطيط الزفاف مثيرًا ولكنه قد يكون مرهقًا أيضًا. لجعل يومك الخاص سلسًا ولا يُنسى، إليك <strong>10 نصائح أساسية</strong>:</p>\r\n<p><strong>1. تحديد ميزانية واقعية</strong><br />ابدأ بتحديد المبلغ الذي يمكنك إنفاقه. خصص الأموال للمكان، الطعام، الديكور، التصوير، وغيرها لتجنب الإنفاق الزائد.</p>\r\n<p><strong>2. اختيار المكان المناسب</strong><br />اختر مكانًا يناسب أسلوبك وعدد الضيوف والميزانية. ضع في اعتبارك الخيارات الداخلية والخارجية للمرونة.</p>\r\n<p><strong>3. التخطيط لقائمة الضيوف</strong><br />احتفظ بقائمة واضحة للضيوف لإدارة الدعوات وترتيبات الجلوس والطعام بكفاءة. هذا يساعد أيضًا في التحكم بالتكاليف.</p>\r\n<p><strong>4. استئجار بائعين موثوقين</strong><br />ابحث واحجز بائعين موثوقين للطعام والديكور والتصوير والترفيه. تحقق من التقييمات وأعمالهم السابقة لضمان الجودة.</p>\r\n<p><strong>5. تخصيص ثيم الزفاف</strong><br />قم بإضافة لمسات شخصية لثيم الزفاف — الألوان، ترتيب الزهور، والديكور الذي يعكس أسلوبك وشخصيتك.</p>\r\n<p><strong>6. التخطيط لجدول مفصل</strong><br />قم بإنشاء جدول زمني للحفل، الاستقبال، والأنشطة الأخرى. شاركه مع البائعين وفريق الزفاف للحفاظ على التنظيم.</p>\r\n<p><strong>7. مراعاة راحة الضيوف</strong><br />فكر في الجلوس، الطقس، مواقف السيارات، والمرافق لجعل الضيوف يشعرون بالراحة طوال الحدث.</p>\r\n<p><strong>8. التحضير للطوارئ</strong><br />ضع خطة بديلة للطقس أو إلغاء البائعين أو الطوارئ في اللحظة الأخيرة. هذا يضمن سير اليوم بسلاسة.</p>\r\n<p><strong>9. لا تنس التصوير والفيديو</strong><br />قم بالتقاط كل اللحظات من خلال توظيف مصورين وفيديوغرافيين محترفين. ستكون أعمالهم ذكرى ثمينة لسنوات.</p>\r\n<p><strong>10. استمتع بيومك</strong><br />أخيرًا، لا تشغل بالك بالتفاصيل الصغيرة. ركز على الاحتفال بالحب والعائلة والأصدقاء. سعادتك ستجعل اليوم لا يُنسى حقًا.</p>', NULL, NULL, NULL, '2025-08-24 14:39:08', '2025-08-24 14:39:08'),
(5, 51, 7, 3, 'Top Catering Ideas for Weddings', 'top-catering-ideas-for-weddings', 'Lina Karim', '<p>Food is one of the most memorable aspects of any wedding. A well-planned catering service not only delights guests but also reflects the couple’s style. Here are some top catering ideas to make your wedding extraordinary:</p>\r\n<p><strong>1. Personalized Menu</strong><br />Offer dishes that reflect your personalities or family traditions. This adds a personal touch and creates a unique dining experience.</p>\r\n<p><strong>2. Multi-Cuisine Options</strong><br />Include a variety of cuisines to cater to diverse tastes. Mixing local favorites with international dishes ensures everyone enjoys their meal.</p>\r\n<p><strong>3. Interactive Food Stations</strong><br />Live cooking stations or DIY food bars let guests customize their meals and enjoy an engaging experience.</p>\r\n<p><strong>4. Signature Drinks &amp; Cocktails</strong><br />Create a signature cocktail or mocktail representing your love story. It’s fun, memorable, and visually appealing.</p>\r\n<p><strong>5. Dietary-Friendly Choices</strong><br />Include vegetarian, vegan, gluten-free, and allergy-sensitive options to accommodate all guests.</p>\r\n<p><strong>6. Dessert Buffet</strong><br />Offer a variety of sweets such as cupcakes, macarons, and mini pastries. A visually stunning dessert table leaves lasting impressions.</p>\r\n<p><strong>7. Appetizer &amp; Welcome Drinks</strong><br />Serve welcome drinks and bite-sized appetizers as guests arrive. This sets the mood and makes them feel welcomed.</p>\r\n<p><strong>8. Themed Presentation</strong><br />Match food presentation with your wedding theme, such as rustic, modern, or elegant setups.</p>\r\n<p><strong>9. Seasonal &amp; Local Ingredients</strong><br />Using fresh seasonal produce enhances flavor and supports local suppliers. It’s eco-friendly and cost-effective.</p>\r\n<p><strong>10. Late-Night Snacks</strong><br />Keep guests energized and happy by offering small snacks or finger foods later in the evening.</p>\r\n<p>A thoughtfully planned catering strategy ensures that your wedding is delicious, memorable, and perfectly aligned with your style.</p>', NULL, NULL, NULL, '2025-08-24 14:45:47', '2025-08-24 14:45:47'),
(6, 53, 8, 3, 'أفضل أفكار تقديم الطعام لحفلات الزفاف', 'أفضل-أفكار-تقديم-الطعام-لحفلات-الزفاف', 'لينا كريم', '<p>يعتبر الطعام من أبرز جوانب أي حفل زفاف. خدمة تقديم الطعام المخططة جيدًا لا تبهج الضيوف فقط، بل تعكس أيضًا أسلوب الزوجين. إليك بعض أفضل الأفكار لجعل حفل زفافك استثنائيًا:</p>\r\n<p><strong>1. قائمة مخصصة</strong><br />قدّم أطباقًا تعكس شخصيتكما أو التقاليد العائلية. هذا يضيف لمسة شخصية ويخلق تجربة طعام فريدة.</p>\r\n<p><strong>2. خيارات متعددة من المأكولات</strong><br />اشمل مجموعة متنوعة من المأكولات لتلبية جميع الأذواق. المزج بين الأطباق المحلية والدولية يضمن استمتاع الجميع بوجباتهم.</p>\r\n<p><strong>3. محطات طعام تفاعلية</strong><br />محطات الطبخ الحي أو أركان الطعام القابلة للتخصيص تتيح للضيوف إعداد وجباتهم ويضيف تجربة ممتعة.</p>\r\n<p><strong>4. مشروبات وكوكتيلات مميزة</strong><br />اصنع كوكتيلًا أو مشروبًا يمثل قصة حبكما. إنه ممتع، ولا يُنسى، وجذاب بصريًا.</p>\r\n<p><strong>5. خيارات غذائية ملائمة للجميع</strong><br />اشمل خيارات نباتية، نباتية صرفة، خالية من الغلوتين، وحساسية الطعام لتلبية جميع الضيوف.</p>\r\n<p><strong>6. بوفيه الحلويات</strong><br />قدّم مجموعة متنوعة من الحلويات مثل الكب كيك، الماكرون، والمعجنات الصغيرة. طاولة حلوى مذهلة بصريًا تترك انطباعًا دائمًا.</p>\r\n<p><strong>7. المقبلات والمشروبات الترحيبية</strong><br />قدّم مشروبات ترحيبية ومقبلات صغيرة عند وصول الضيوف. هذا يهيئ الجو ويجعلهم يشعرون بالترحيب.</p>\r\n<p><strong>8. عرض الطعام المتناسق مع الثيم</strong><br />طابق عرض الطعام مع ثيم الزفاف، مثل الطابع الريفي، الحديث، أو الأنيق.</p>\r\n<p><strong>9. مكونات موسمية ومحلية</strong><br />استخدام منتجات موسمية طازجة يعزز النكهة ويدعم الموردين المحليين. إنه صديق للبيئة وفعال من حيث التكلفة.</p>\r\n<p><strong>10. وجبات خفيفة في وقت متأخر من الليل</strong><br />حافظ على طاقة الضيوف وسعادتهم من خلال تقديم وجبات صغيرة أو أطعمة خفيفة في وقت متأخر من الليل.</p>', NULL, NULL, NULL, '2025-08-24 14:45:47', '2025-08-24 14:45:47'),
(7, 51, 3, 4, 'How to Pick a Corporate Event Venue', 'how-to-pick-a-corporate-event-venue', 'Omar Khalid', '<p>Choosing the right corporate event venue is one of the most critical decisions when planning a successful business event. The venue sets the tone for your meeting, conference, workshop, or corporate celebration. From small team-building events to large conferences, every detail counts. Here’s a comprehensive guide to help you select the perfect corporate venue.</p>\r\n<h4>1. Define Your Event Objectives</h4>\r\n<p>Before considering any venue, clearly outline the purpose of your event. Are you hosting a workshop, a conference, a networking event, or a celebration? Knowing the goal helps determine the venue size, layout, and facilities needed.</p>\r\n<h4>2. Determine Your Budget</h4>\r\n<p>Budget plays a major role in venue selection. Consider venue rental fees, catering, AV equipment, staffing, and decoration costs. Always allocate a contingency for unexpected expenses. Being realistic about your budget helps narrow down suitable options.</p>\r\n<h4>3. Consider the Location</h4>\r\n<p>A venue’s location impacts convenience and attendance. Choose a place easily accessible by public transport or with ample parking. Central locations are preferable for corporate attendees, minimizing travel time.</p>\r\n<h4>4. Check Venue Capacity</h4>\r\n<p>Ensure the venue can comfortably accommodate all your guests. Consider seating arrangements, breakout areas, and space for networking. Overcrowded or too-large venues can negatively affect attendee experience.</p>\r\n<h4>5. Evaluate Amenities &amp; Services</h4>\r\n<p>Look for essential amenities such as high-speed internet, audio-visual equipment, lighting, and climate control. Some venues offer in-house catering, event staff, or decoration services, which can simplify planning.</p>\r\n<h4>6. Ambiance &amp; Style</h4>\r\n<p>The venue’s ambiance should match the tone of your event. Corporate meetings may require professional, modern settings, while company celebrations might benefit from more creative, stylish spaces. Ensure the design reflects your brand and event theme.</p>\r\n<h4>7. Accessibility &amp; Inclusivity</h4>\r\n<p>Ensure the venue is accessible for all attendees, including those with disabilities. Check for ramps, elevators, and restroom facilities. Accessibility ensures all guests feel welcome and included.</p>\r\n<h4>8. Availability &amp; Flexibility</h4>\r\n<p>Book the venue well in advance to secure your preferred date. Some venues offer flexible hours or allow setup the day before the event, which helps with smooth execution.</p>\r\n<h4>9. Catering Options</h4>\r\n<p>Food and beverages are an integral part of corporate events. Evaluate whether the venue provides in-house catering or allows external caterers. Consider dietary restrictions and diverse preferences for an inclusive experience.</p>\r\n<h4>10. Technology &amp; Connectivity</h4>\r\n<p>For corporate events, reliable Wi-Fi, projectors, microphones, and video conferencing facilities are essential. Ensure the venue can support your technological requirements.</p>\r\n<h4>11. Parking &amp; Transportation</h4>\r\n<p>Ample parking or nearby transport options enhance attendee convenience. Some venues provide valet services or shuttle buses for large events. Check the venue’s recommendations for transportation logistics.</p>\r\n<h4>12. Venue Policies &amp; Restrictions</h4>\r\n<p>Review policies on cancellations, deposits, insurance, alcohol, and noise restrictions. Understanding the rules helps avoid last-minute surprises or conflicts during the event.</p>\r\n<h4>13. Read Reviews &amp; Visit the Venue</h4>\r\n<p>Check online reviews and testimonials from previous clients. Schedule a site visit to evaluate space, amenities, and overall atmosphere firsthand. A personal visit often reveals details not evident online.</p>\r\n<h4>14. Plan for Contingencies</h4>\r\n<p>Always have a backup plan for unforeseen circumstances, such as technical failures or weather issues if the event is partially outdoor. Discuss contingency measures with the venue management.</p>\r\n<h4>15. Sustainability &amp; Green Practices</h4>\r\n<p>Increasingly, companies prefer eco-friendly venues. Look for venues that use sustainable materials, energy-efficient lighting, and recycling practices. Sustainability can enhance your corporate image.</p>\r\n<h4>16. Contract &amp; Legal Considerations</h4>\r\n<p>Once you finalize a venue, review the contract carefully. Pay attention to payment schedules, cancellation policies, liability clauses, and included services. Legal clarity prevents misunderstandings.</p>\r\n<p>By carefully considering these factors, you can choose a corporate event venue that ensures a smooth, professional, and memorable experience for all attendees. Proper planning, clear objectives, and attention to detail make all the difference in hosting a successful corporate event.</p>', NULL, NULL, NULL, '2025-08-24 14:55:43', '2025-08-24 14:55:43'),
(8, 53, 4, 4, 'كيفية اختيار مكان لإقامة حدث شركي', 'كيفية-اختيار-مكان-لإقامة-حدث-شركي', 'عمر خالد', '<p>اختيار المكان المناسب لإقامة حدث شركي هو أحد أهم القرارات عند تخطيط حدث تجاري ناجح. المكان يحدد أجواء الاجتماع، المؤتمر، ورشة العمل، أو الاحتفال المؤسسي. من الأحداث الصغيرة لبناء الفريق إلى المؤتمرات الكبيرة، كل التفاصيل مهمة. فيما يلي دليل شامل لمساعدتك في اختيار المكان المثالي.</p>\r\n<h4>1. تحديد أهداف الحدث</h4>\r\n<p>قبل النظر في أي مكان، حدد بوضوح هدف الحدث. هل تقيم ورشة عمل، مؤتمر، حدث للتواصل، أم احتفال شركي؟ معرفة الهدف يساعد على تحديد حجم المكان، ترتيب المقاعد، والخدمات المطلوبة.</p>\r\n<h4>2. تحديد الميزانية</h4>\r\n<p>تلعب الميزانية دورًا رئيسيًا في اختيار المكان. ضع في اعتبارك رسوم استئجار المكان، الطعام، المعدات السمعية والبصرية، الموظفين، والديكور. دائمًا خصص مبلغ للطوارئ لتغطية النفقات غير المتوقعة. الواقعية في الميزانية تساعد على تضييق الخيارات المناسبة.</p>\r\n<h4>3. مراعاة الموقع</h4>\r\n<p>يؤثر موقع المكان على الراحة وحضور الضيوف. اختر مكانًا يسهل الوصول إليه بواسطة وسائل النقل العامة أو به مواقف سيارات كافية. المواقع المركزية مفضلة للمشاركين لتقليل وقت السفر.</p>\r\n<h4>4. سعة المكان</h4>\r\n<p>تأكد من أن المكان يمكن أن يستوعب جميع الضيوف بشكل مريح. ضع في اعتبارك ترتيبات الجلوس، مناطق الاجتماعات الصغيرة، ومساحة للتواصل. الأماكن المكتظة أو الواسعة جدًا قد تؤثر سلبًا على تجربة الحاضرين.</p>\r\n<h4>5. تقييم المرافق والخدمات</h4>\r\n<p>ابحث عن المرافق الأساسية مثل الإنترنت عالي السرعة، المعدات السمعية والبصرية، الإضاءة، والتحكم في المناخ. بعض الأماكن تقدم خدمات الطعام، الموظفين، أو الديكور داخليًا مما يسهل التخطيط.</p>\r\n<h4>6. الجو والطراز</h4>\r\n<p>يجب أن يتناسب جو المكان مع طابع الحدث. الاجتماعات المهنية قد تتطلب أماكن حديثة واحترافية، بينما الاحتفالات يمكن أن تكون أكثر إبداعًا وأناقة. تأكد من أن التصميم يعكس علامتك التجارية وموضوع الحدث.</p>\r\n<h4>7. سهولة الوصول والشمولية</h4>\r\n<p>تأكد من أن المكان مناسب لجميع الحاضرين بما في ذلك ذوي الاحتياجات الخاصة. تحقق من وجود منحدرات، مصاعد، ودورات مياه مناسبة. الشمولية تجعل جميع الضيوف يشعرون بالراحة والترحيب.</p>\r\n<h4>8. التوفر والمرونة</h4>\r\n<p>احجز المكان مسبقًا لضمان الحصول على التاريخ المفضل. بعض الأماكن تقدم ساعات مرنة أو تسمح بالإعداد قبل يوم الحدث مما يسهل التنفيذ بسلاسة.</p>\r\n<h4>9. خيارات الطعام</h4>\r\n<p>الطعام والشراب جزء أساسي من الأحداث المؤسسية. قيم ما إذا كان المكان يقدم خدمة الطعام داخليًا أو يسمح بمقدمي طعام خارجيين. ضع في الاعتبار القيود الغذائية والتفضيلات المتنوعة لتجربة شاملة.</p>\r\n<h4>10. التكنولوجيا والاتصال</h4>\r\n<p>للأحداث المؤسسية، الإنترنت السريع، أجهزة العرض، الميكروفونات، وخدمات المؤتمرات عبر الفيديو ضرورية. تأكد من قدرة المكان على دعم احتياجاتك التقنية.</p>\r\n<h4>11. مواقف السيارات والنقل</h4>\r\n<p>توفر مواقف كافية أو وسائل نقل قريبة يعزز راحة الحاضرين. بعض الأماكن تقدم خدمة صف السيارات أو حافلات للنقل للمناسبات الكبيرة. تحقق من توصيات المكان للنقل واللوجستيات.</p>\r\n<h4>12. سياسات المكان والقيود</h4>\r\n<p>راجع السياسات المتعلقة بالإلغاء، الدفعات، التأمين، الكحول، والضوضاء. فهم القواعد يمنع المشاكل أو الصراعات أثناء الحدث.</p>\r\n<h4>13. قراءة المراجعات وزيارة المكان</h4>\r\n<p>تحقق من المراجعات عبر الإنترنت وتجارب العملاء السابقين. قم بزيارة الموقع لتقييم المكان والمرافق والجو بشكل مباشر. الزيارة الشخصية تكشف عن تفاصيل قد لا تظهر على الإنترنت.</p>\r\n<h4>14. التحضير للطوارئ</h4>\r\n<p>ضع دائمًا خطة بديلة للظروف غير المتوقعة مثل الأعطال التقنية أو الطقس إذا كان الحدث جزئيًا في الهواء الطلق. ناقش إجراءات الطوارئ مع إدارة المكان.</p>\r\n<h4>15. الاستدامة والممارسات البيئية</h4>\r\n<p>يفضل العديد من الشركات الأماكن الصديقة للبيئة. ابحث عن الأماكن التي تستخدم مواد مستدامة، إضاءة موفرة للطاقة، وممارسات إعادة التدوير. الاستدامة تعزز صورة شركتك.</p>\r\n<h4>16. العقد والجوانب القانونية</h4>\r\n<p>بعد تحديد المكان، راجع العقد بعناية. انتبه لجداول الدفع، سياسات الإلغاء، بنود المسؤولية، والخدمات المدرجة. الوضوح القانوني يمنع سوء الفهم.</p>\r\n<p>من خلال مراعاة هذه العوامل بعناية، يمكنك اختيار مكان حدث شركي يضمن تجربة سلسة، احترافية، ولا تُنسى لجميع الحاضرين. التخطيط الجيد، وضوح الأهداف، والانتباه للتفاصيل يصنع فرقًا كبيرًا في نجاح الحدث.</p>', NULL, NULL, NULL, '2025-08-24 14:55:43', '2025-08-24 14:55:43');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint DEFAULT NULL,
  `product_type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `featured_image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `slider_images` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `input_type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `file` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `stock` int UNSIGNED DEFAULT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `current_price` decimal(8,2) UNSIGNED NOT NULL,
  `previous_price` decimal(8,2) UNSIGNED DEFAULT NULL,
  `average_rating` decimal(4,2) UNSIGNED DEFAULT '0.00',
  `is_featured` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `vendor_id`, `product_type`, `featured_image`, `slider_images`, `status`, `input_type`, `file`, `link`, `stock`, `sku`, `current_price`, `previous_price`, `average_rating`, `is_featured`, `created_at`, `updated_at`) VALUES
(59, NULL, 'digital', '68ab2c7c0e054.png', '[\"68ab2b00c267e.png\",\"68ab2b00cafa6.png\",\"68ab2b00ef706.png\",\"68ab2b010cdf0.png\"]', 'show', 'link', NULL, 'https://surl.lt/zhnvue', NULL, NULL, 5.99, 9.99, 0.00, 'no', '2025-08-24 15:15:08', '2025-08-24 15:15:08'),
(60, NULL, 'digital', '68ab2d6deac45.png', '[\"68ab2d01b8f4c.png\",\"68ab2d0719a26.png\",\"68ab2d0723c05.png\"]', 'show', 'link', NULL, 'https://surl.li/ooaahq', NULL, NULL, 7.99, 12.98, 0.00, 'no', '2025-08-24 15:19:09', '2025-08-24 15:19:09'),
(61, NULL, 'digital', '68ab2e12c8107.png', '[\"68ab2daaad033.png\",\"68ab2db1b5366.png\",\"68ab2db1bd9df.png\"]', 'show', 'link', NULL, 'https://surl.li/eusqsb', NULL, NULL, 10.00, 15.00, 0.00, 'no', '2025-08-24 15:21:54', '2025-08-24 15:21:54'),
(62, NULL, 'digital', '68ab2eb639c77.png', '[\"68ab2e5a61460.png\",\"68ab2e5a621f1.png\",\"68ab2e5a8df6e.png\"]', 'show', 'link', NULL, 'https://surl.li/f', NULL, NULL, 8.99, 14.99, 0.00, 'no', '2025-08-24 15:24:38', '2025-08-24 15:24:38'),
(63, NULL, 'physical', '68ab2fb27cafa.png', '[\"68ab2f55c1e76.png\",\"68ab2f55c260d.png\",\"68ab2f55ef601.png\"]', 'show', NULL, NULL, NULL, 10, NULL, 12.99, 19.99, 0.00, 'no', '2025-08-24 15:28:50', '2025-08-24 15:28:50'),
(64, NULL, 'physical', '68ab314f5ff52.png', '[\"68ab30c77211d.png\",\"68ab30c7738b2.png\",\"68ab30c7a4d71.png\"]', 'show', NULL, NULL, NULL, 10, NULL, 9.99, 14.99, 0.00, 'no', '2025-08-24 15:35:43', '2025-08-24 15:35:43'),
(65, NULL, 'physical', '68ab3313a6645.png', '[\"68ab32bf93736.png\",\"68ab32bf943da.png\",\"68ab32bfc2514.png\"]', 'show', NULL, NULL, NULL, 6, NULL, 18.99, 27.99, 0.00, 'no', '2025-08-24 15:43:15', '2025-09-08 14:12:43'),
(66, NULL, 'physical', '68ab342b979e8.png', '[\"68ab337822074.png\",\"68ab337e1bdf9.png\"]', 'show', NULL, NULL, NULL, 10, NULL, 9.99, 15.00, 0.00, 'no', '2025-08-24 15:47:55', '2025-08-24 15:47:55'),
(67, NULL, 'physical', '68ab354134a76.png', '[\"68ab3483153ae.png\",\"68ab3483178d5.png\",\"68ab348342666.png\"]', 'show', NULL, NULL, NULL, 12, NULL, 19.99, 29.98, 0.00, 'no', '2025-08-24 15:52:33', '2025-08-24 15:52:33'),
(68, NULL, 'physical', '68ab35af87de6.png', '[\"68ab35531eb55.png\",\"68ab35532968f.png\",\"68ab355359058.png\",\"68ab355365271.png\"]', 'show', NULL, NULL, NULL, 9, NULL, 35.00, 50.00, 0.00, 'no', '2025-08-24 15:54:23', '2025-08-24 15:54:23'),
(69, NULL, 'physical', '68ab365ad173c.png', '[\"68ab36055d83b.png\",\"68ab360563f41.png\",\"68ab36059271e.png\"]', 'show', NULL, NULL, NULL, 8, NULL, 9.99, 14.99, 0.00, 'no', '2025-08-24 15:57:14', '2025-09-08 13:46:38'),
(70, NULL, 'physical', '68ab372111c11.png', '[\"68ab36c805aca.png\",\"68ab36c80ac87.png\"]', 'show', NULL, NULL, NULL, 7, NULL, 14.99, 24.99, 4.50, 'no', '2025-08-24 16:00:33', '2025-09-09 13:31:48');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL,
  `serial_number` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `language_id`, `name`, `slug`, `status`, `serial_number`, `created_at`, `updated_at`) VALUES
(1, 51, 'Beautiful Template and card', 'beautiful-template-and-card', 1, 1, '2025-08-24 15:07:10', '2025-08-24 15:07:10'),
(2, 53, 'قالب وبطاقة جميلة', 'قالب-وبطاقة-جميلة', 1, 1, '2025-08-24 15:07:59', '2025-08-24 15:07:59'),
(3, 51, 'Decor & Party Essentials', 'decor--party-essentials', 1, 2, '2025-08-24 15:26:18', '2025-08-24 15:26:18'),
(4, 53, 'ديكورات ومستلزمات الحفلات', 'ديكورات-ومستلزمات-الحفلات', 1, 2, '2025-08-24 15:26:42', '2025-08-24 15:26:42'),
(5, 51, 'Gift & Treats', 'gift--treats', 1, 3, '2025-08-24 15:40:20', '2025-08-24 15:40:20'),
(6, 53, 'الهدايا والحلويات', 'الهدايا-والحلويات', 1, 3, '2025-08-24 15:40:38', '2025-08-24 15:40:38'),
(7, 51, 'Wearables & Accessories', 'wearables--accessories', 1, 4, '2025-08-24 15:48:36', '2025-08-24 15:48:36'),
(8, 53, 'الأجهزة القابلة للارتداء والإكسسوارات', 'الأجهزة-القابلة-للارتداء-والإكسسوارات', 1, 4, '2025-08-24 15:48:45', '2025-08-24 15:48:45'),
(9, 51, 'Toys & Keepsakes', 'toys--keepsakes', 1, 6, '2025-08-24 15:58:22', '2025-08-24 15:58:22'),
(10, 53, 'الألعاب والتذكارات', 'الألعاب-والتذكارات', 1, 6, '2025-08-24 15:58:40', '2025-08-24 15:58:40');

-- --------------------------------------------------------

--
-- Table structure for table `product_contents`
--

CREATE TABLE `product_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `product_category_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `summary` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `content` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keywords` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `product_contents`
--

INSERT INTO `product_contents` (`id`, `language_id`, `product_category_id`, `product_id`, `title`, `slug`, `summary`, `content`, `meta_keywords`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 51, 1, 59, 'Printable Thank You Cards', 'printable-thank-you-cards', 'Express your gratitude beautifully with our Printable Thank You Cards. Perfect for personal and professional use, these cards are fully customizable and easy to print at home.', '<p>Saying “thank you” has never been easier! Our Printable Thank You Cards are designed to make expressing gratitude effortless and stylish. Whether you are sending a personal note to a friend, a client, or a colleague, these cards help convey your appreciation in a memorable way.</p>\r\n<p>Each card comes in a high-quality digital format, ready to be printed on any standard paper or card stock. You can personalize them with your own messages, names, or even images, making each card unique and thoughtful. The design is versatile, suitable for birthdays, weddings, business gifts, or any occasion where a thank you is needed.</p>\r\n<p>By choosing printable cards, you also save time and money compared to traditional pre-printed cards. Simply download the file, print at home or at a local print shop, and you are ready to share your gratitude. The elegant designs are suitable for all age groups and professional contexts, making them a flexible option for everyone.</p>\r\n<p>With instant download access, you can start customizing your cards immediately. No shipping fees, no waiting time, just instant gratification and creativity at your fingertips. Perfect for last-minute events or spontaneous acts of kindness.</p>\r\n<p>Make every “thank you” count with our beautifully crafted Printable Thank You Cards. Show your appreciation in style and make a lasting impression on those you care about.</p>', NULL, NULL, '2025-08-24 15:15:08', '2025-08-24 15:15:08'),
(2, 53, 2, 59, 'بطاقات شكر قابلة للطباعة', 'بطاقات-شكر-قابلة-للطباعة', 'عبّر عن امتنانك بطريقة جميلة مع بطاقات الشكر القابلة للطباعة لدينا. مثالية للاستخدام الشخصي والمهني، وقابلة للتخصيص بالكامل وسهلة الطباعة في المنزل.', '<p>قول \"شكراً\" لم يكن أسهل من الآن! بطاقات الشكر القابلة للطباعة لدينا مصممة لجعل التعبير عن الامتنان بسيط وأنيق. سواء كنت ترسل ملاحظة شخصية لصديق، أو عميل، أو زميل، تساعد هذه البطاقات في إيصال تقديرك بطريقة لا تُنسى.</p>\r\n<p>كل بطاقة تأتي بصيغة رقمية عالية الجودة، جاهزة للطباعة على أي ورق أو كرت قياسي. يمكنك تخصيصها برسائلك الخاصة، الأسماء، أو حتى الصور، مما يجعل كل بطاقة فريدة ومدروسة. التصميم متعدد الاستخدامات، مناسب لأعياد الميلاد، حفلات الزفاف، هدايا الأعمال، أو أي مناسبة تحتاج فيها إلى شكر.</p>\r\n<p>باختيار البطاقات القابلة للطباعة، توفر الوقت والمال مقارنة بالبطاقات المطبوعة مسبقاً. ببساطة قم بتحميل الملف، اطبع في المنزل أو في محل الطباعة المحلي، وستكون جاهزًا لمشاركة امتنانك. التصميمات الأنيقة مناسبة لجميع الفئات العمرية والسياقات المهنية، مما يجعلها خيارًا مرنًا للجميع.</p>\r\n<p>مع إمكانية التحميل الفوري، يمكنك البدء في تخصيص بطاقاتك على الفور. لا رسوم شحن، لا انتظار، فقط إبداع وسعادة في متناول يدك. مثالية للأحداث الطارئة أو الأعمال العفوية للطف.</p>\r\n<p>اجعل كل \"شكراً\" له قيمة مع بطاقات الشكر القابلة للطباعة المصممة بعناية. عبّر عن امتنانك بأناقة واترك انطباعًا دائمًا على من تهتم لأمرهم.</p>', NULL, NULL, '2025-08-24 15:15:08', '2025-08-24 15:15:08'),
(3, 51, 1, 60, 'Birthday & Wedding Invitation Templates', 'birthday--wedding-invitation-templates', 'Create stunning invitations for birthdays and weddings with ease using our fully customizable templates. Perfect for digital or print invitations that impress your guests.', '<p>Make every celebration memorable with our Birthday &amp; Wedding Invitation Templates. Designed for both elegance and convenience, these templates allow you to craft beautiful invitations without the hassle of designing from scratch.</p>\r\n<p>Whether you are planning a birthday party, engagement, or wedding ceremony, these templates provide a professional and stylish foundation. Each template comes in high-quality digital formats, compatible with popular design software or even simple editing tools. You can customize text, colors, fonts, and even images to match your theme perfectly.</p>\r\n<p>Digital templates save time and cost compared to traditional invitations. Simply download, personalize, and send via email or print at home or through a local print service. The designs are versatile, catering to formal weddings, casual birthday parties, and everything in between.</p>\r\n<p>Our templates also help you maintain a consistent theme across all your party elements. Matching RSVP cards, thank you notes, and other stationery can be created effortlessly with the same design style. With instant download access, you can start creating invitations immediately, ensuring no delays in your party planning.</p>\r\n<p>Elevate your celebration and impress your guests with professionally designed Birthday &amp; Wedding Invitation Templates. Celebrate in style and make every event unforgettable.</p>', NULL, NULL, '2025-08-24 15:19:09', '2025-08-24 15:19:09'),
(4, 53, 2, 60, 'قوالب دعوات أعياد الميلاد والزفاف', 'قوالب-دعوات-أعياد-الميلاد-والزفاف', 'أنشئ دعوات رائعة لأعياد الميلاد وحفلات الزفاف بسهولة باستخدام قوالبنا القابلة للتخصيص بالكامل. مثالية للدعوات الرقمية أو المطبوعة التي تُبهر ضيوفك.', '<p>اجعل كل احتفال ذكرى لا تُنسى مع قوالب دعوات أعياد الميلاد والزفاف لدينا. صُممت هذه القوالب لتجمع بين الأناقة والعملية، وتتيح لك تصميم دعوات رائعة دون عناء التصميم من الصفر.</p>\r\n<p>سواء كنت تخطط لحفل عيد ميلاد أو خطوبة أو حفل زفاف، توفر لك هذه القوالب أساسًا احترافيًا وأنيقًا. يأتي كل قالب بتنسيقات رقمية عالية الجودة، متوافقة مع برامج التصميم الشائعة أو حتى أدوات التحرير البسيطة. يمكنك تخصيص النصوص والألوان والخطوط وحتى الصور لتتناسب تمامًا مع موضوع حفلتك.</p>\r\n<p>توفر القوالب الرقمية الوقت والتكلفة مقارنةً بالدعوات التقليدية. ما عليك سوى تنزيلها وتخصيصها وإرسالها عبر البريد الإلكتروني أو طباعتها في المنزل أو من خلال خدمة طباعة محلية. تتميز هذه التصاميم بتعدد استخداماتها، وتناسب حفلات الزفاف الرسمية وحفلات أعياد الميلاد غير الرسمية، وغيرها من المناسبات.</p>\r\n<p>كما تساعدك قوالبنا على الحفاظ على طابع متناسق لجميع عناصر حفلتك. يمكنك إنشاء بطاقات تأكيد الحضور ورسائل الشكر وغيرها من القرطاسية بسهولة وبنفس أسلوب التصميم. مع إمكانية التنزيل الفوري، يمكنك البدء في إنشاء الدعوات فورًا، مما يضمن لك عدم حدوث أي تأخير في تخطيط حفلتك.</p>\r\n<p>ارتقِ باحتفالك وأبهر ضيوفك بنماذج دعوات أعياد الميلاد والزفاف المصممة باحترافية. احتفل بأناقة واجعل كل مناسبة لا تُنسى.</p>', NULL, NULL, '2025-08-24 15:19:09', '2025-08-24 15:19:09'),
(5, 51, 1, 61, 'E-gift Cards', 'e-gift-cards', 'Give the perfect gift instantly with our E-gift Cards. Flexible, convenient, and suitable for any occasion, these digital cards make gifting easy and stress-free.', '<p>Looking for a simple and thoughtful way to surprise someone? Our E-gift Cards are the perfect solution. Designed for flexibility and convenience, they allow the recipient to choose exactly what they want, ensuring your gift is always appreciated.</p>\r\n<p>Available in various denominations, these digital cards can be used for online purchases, services, or special events. You can send them instantly via email or messaging apps, making last-minute gifting a breeze. There’s no need for physical delivery or waiting for shipping—your thoughtful gesture reaches your loved ones immediately.</p>\r\n<p>Our E-gift Cards are perfect for birthdays, weddings, anniversaries, holidays, or corporate gifting. The sleek, modern design ensures that the gift looks stylish and professional, whether it’s for a friend, family member, or colleague.</p>\r\n<p>Using an E-gift Card is simple: the recipient receives a code that can be redeemed online instantly. It’s a versatile gift that removes the stress of choosing the “right” present, allowing the recipient full freedom to select what they truly desire.</p>\r\n<p>With instant access, no shipping fees, and universal usability, E-gift Cards are an ideal way to show appreciation, love, or gratitude. Make gifting effortless, meaningful, and memorable with our E-gift Cards.</p>', NULL, NULL, '2025-08-24 15:21:54', '2025-08-24 15:21:54'),
(6, 53, 2, 61, 'طاقات الهدايا الإلكترونية', 'طاقات-الهدايا-الإلكترونية', 'قدّم الهدية المثالية على الفور مع بطاقات الهدايا الإلكترونية لدينا. مرنة، سهلة الاستخدام، ومناسبة لأي مناسبة، تجعل هذه البطاقات الرقمية عملية الإهداء سهلة وخالية من القلق.', '<p>قدّم الهدية المثالية على الفور مع بطاقات الهدايا الإلكترونية لدينا. مرنة، سهلة الاستخدام، ومناسبة لأي مناسبة، تجعل هذه البطاقات الرقمية عملية الإهداء سهلة وخالية من القلق.</p>\r\n<p><strong>المحتوى:</strong><br />هل تبحث عن طريقة بسيطة ومدروسة لمفاجأة شخص ما؟ بطاقات الهدايا الإلكترونية لدينا هي الحل الأمثل. مصممة لتكون مرنة وسهلة الاستخدام، تتيح للمستلم اختيار ما يريده بالضبط، مما يضمن أن تكون هديتك محل تقدير دائمًا.</p>\r\n<p>متاحة بمبالغ مختلفة، يمكن استخدام هذه البطاقات الرقمية للمشتريات عبر الإنترنت، الخدمات، أو الأحداث الخاصة. يمكنك إرسالها فورًا عبر البريد الإلكتروني أو تطبيقات المراسلة، مما يجعل الهدايا في اللحظة الأخيرة سهلة وسريعة. لا حاجة للتوصيل الفعلي أو انتظار الشحن—تصل لمستلميها مباشرة وبشكل فوري.</p>\r\n<p>بطاقات الهدايا الإلكترونية مثالية لأعياد الميلاد، حفلات الزفاف، الذكرى السنوية، العطلات، أو الهدايا للشركات. التصميم العصري والأنيق يضمن أن تبدو الهدية جذابة ومهنية، سواء كانت لصديق، أحد أفراد العائلة، أو زميل عمل.</p>\r\n<p>استخدام بطاقة الهدايا الإلكترونية بسيط: يحصل المستلم على رمز يمكن استبداله عبر الإنترنت مباشرة. إنها هدية متعددة الاستخدامات تزيل ضغط اختيار الهدية “المثالية”، مما يمنح المستلم الحرية الكاملة لاختيار ما يريده حقًا.</p>\r\n<p>مع الوصول الفوري، وعدم وجود رسوم شحن، وإمكانية الاستخدام العام، تعتبر بطاقات الهدايا الإلكترونية طريقة مثالية لإظهار التقدير، الحب، أو الامتنان. اجعل الإهداء سهلاً، ذو معنى، ولا يُنسى مع بطاقات الهدايا الإلكترونية لدينا.</p>', NULL, NULL, '2025-08-24 15:21:54', '2025-08-24 15:21:54'),
(7, 51, 1, 62, 'Online Invitation Cards & RSVP', 'online-invitation-cards--rsvp', 'Create and manage invitations online effortlessly with our Online Invitation Cards & RSVP system. Perfect for events, weddings, birthdays, and parties, it makes inviting and tracking guests seamless.', '<p>Planning an event just got easier with our Online Invitation Cards &amp; RSVP templates. Designed to simplify invitations and guest management, these digital tools allow you to create beautiful, interactive invitations that your guests can respond to instantly.</p>\r\n<p>Whether it’s a wedding, birthday, anniversary, or corporate event, you can design invitations with customizable themes, colors, fonts, and images. Guests can RSVP online, making it simple to track attendance and plan your event efficiently. This digital approach removes the hassle of paper invites and manual tracking.</p>\r\n<p>The templates are compatible with most devices, ensuring guests can view and respond from their phones, tablets, or computers. You can personalize each invitation with your own message, event details, and even multimedia elements to make it more engaging.</p>\r\n<p>Online Invitation Cards &amp; RSVP save time, reduce costs, and enhance convenience for both hosts and guests. With instant download and ready-to-use templates, you can start inviting guests immediately without worrying about printing or mailing.</p>\r\n<p>Make every event organized and memorable with stylish, interactive invitations. Impress your guests, simplify event management, and create a seamless experience for everyone involved.</p>', NULL, NULL, '2025-08-24 15:24:38', '2025-08-24 15:24:38'),
(8, 53, 2, 62, 'طاقات الدعوات عبر الإنترنت ونظام', 'طاقات-الدعوات-عبر-الإنترنت-ونظام', 'اصنع وادِر الدعوات عبر الإنترنت بسهولة مع بطاقات الدعوات عبر الإنترنت ونظام RSVP لدينا. مثالية للأحداث، حفلات الزفاف، أعياد الميلاد، والحفلات، مما يجعل دعوة الضيوف وتتبع الحضور أمرًا سلسًا.', '<p>اصنع وادِر الدعوات عبر الإنترنت بسهولة مع بطاقات الدعوات عبر الإنترنت ونظام RSVP لدينا. مثالية للأحداث، حفلات الزفاف، أعياد الميلاد، والحفلات، مما يجعل دعوة الضيوف وتتبع الحضور أمرًا سلسًا.</p>\r\n<p><strong>المحتوى:</strong><br />أصبح التخطيط للحدث أسهل مع قوالب بطاقات الدعوات عبر الإنترنت ونظام RSVP لدينا. مصممة لتبسيط عملية الدعوات وإدارة الضيوف، تتيح لك هذه الأدوات الرقمية إنشاء دعوات جميلة وتفاعلية يمكن للضيوف الرد عليها على الفور.</p>\r\n<p>سواء كان حفل زفاف، عيد ميلاد، ذكرى سنوية، أو حدثًا للشركة، يمكنك تصميم الدعوات باستخدام سمات قابلة للتخصيص، ألوان، خطوط، وصور. يمكن للضيوف تأكيد حضورهم عبر الإنترنت، مما يجعل تتبع الحضور والتخطيط للحدث أمراً سهلاً وفعالاً. هذا الأسلوب الرقمي يزيل عناء الدعوات الورقية والمتابعة اليدوية.</p>\r\n<p>القوالب متوافقة مع معظم الأجهزة، مما يضمن قدرة الضيوف على عرض الدعوة والرد عليها من هواتفهم، أجهزتهم اللوحية، أو أجهزة الكمبيوتر. يمكنك تخصيص كل دعوة برسالتك الخاصة، تفاصيل الحدث، وحتى العناصر الإعلامية لجعلها أكثر تفاعلية وجاذبية.</p>\r\n<p>بطاقات الدعوات عبر الإنترنت ونظام RSVP توفر الوقت، وتقلل التكاليف، وتعزز الراحة لكل من المضيفين والضيوف. مع إمكانية التحميل الفوري والقوالب الجاهزة للاستخدام، يمكنك البدء في دعوة الضيوف فورًا دون القلق بشأن الطباعة أو البريد.</p>\r\n<p>اجعل كل حدث منظمًا ولا يُنسى مع دعوات أنيقة وتفاعلية. أبهر ضيوفك، وسهّل إدارة الحدث، وقدم تجربة سلسة للجميع.</p>', NULL, NULL, '2025-08-24 15:24:38', '2025-08-24 15:24:38'),
(9, 51, 3, 63, 'Party Balloons Packs', 'party-balloons-packs', 'Make every celebration colorful and fun with our Party Balloons Packs. Perfect for birthdays, weddings, and special events, these packs include a variety of vibrant balloons to enhance your décor.', '<p>Transform your party space instantly with our Party Balloons Packs. Designed to bring color, excitement, and a festive atmosphere, these packs are perfect for any celebration. Whether it’s a birthday party, wedding reception, anniversary, or corporate event, balloons add a joyful touch to your décor.</p>\r\n<p>Each pack contains a mix of high-quality balloons in assorted colors, sizes, and shapes, allowing you to create stunning arrangements. You can combine them into balloon bouquets, arches, or centerpieces to match your theme. The vibrant colors and durable material ensure your decorations last throughout the event.</p>\r\n<p>Party Balloons Packs are suitable for indoor or outdoor use and are easy to inflate, either with air or helium. These packs help you save time and money compared to buying individual balloons separately. They are also ideal for DIY party decorations, giving you flexibility to create custom designs that wow your guests.</p>\r\n<p>Add fun, style, and a touch of magic to your celebrations with our Party Balloons Packs. Perfect for families, friends, and professional event planners, these packs make every event memorable and visually stunning.</p>', NULL, NULL, '2025-08-24 15:28:50', '2025-08-24 15:28:50'),
(10, 53, 4, 63, 'مجموعات بالونات الحفلات', 'مجموعات-بالونات-الحفلات', 'اجعل كل احتفال ملونًا وممتعًا مع مجموعات بالونات الحفلات لدينا. مثالية لأعياد الميلاد، حفلات الزفاف، والمناسبات الخاصة، تشمل هذه المجموعات مجموعة متنوعة من البالونات الزاهية لتعزيز ديكورك.', '<p><br />حوّل مساحة حفلتك على الفور مع مجموعات بالونات الحفلات لدينا. مصممة لإضفاء اللون، الإثارة، والأجواء الاحتفالية، هذه المجموعات مثالية لأي احتفال. سواء كانت حفلة عيد ميلاد، حفل زفاف، ذكرى سنوية، أو حدثًا للشركة، تضيف البالونات لمسة فرحة إلى ديكورك.</p>\r\n<p>تحتوي كل مجموعة على مجموعة من البالونات عالية الجودة بألوان، أحجام، وأشكال متنوعة، مما يتيح لك إنشاء ترتيبات رائعة. يمكنك دمجها في باقات بالونات، أقواس، أو قطع مركزية لتتناسب مع موضوعك. الألوان الزاهية والمواد المتينة تضمن أن تدوم زخارفك طوال الحدث.</p>\r\n<p>مجموعات بالونات الحفلات مناسبة للاستخدام الداخلي أو الخارجي وسهلة النفخ، سواء بالهواء أو الهيليوم. تساعدك هذه المجموعات على توفير الوقت والمال مقارنة بشراء البالونات بشكل فردي. كما أنها مثالية لتزيين الحفلات بنفسك، مما يمنحك المرونة لإنشاء تصاميم مخصصة تبهج ضيوفك.</p>\r\n<p>أضف المرح، الأناقة، ولمسة من السحر إلى احتفالاتك مع مجموعات بالونات الحفلات لدينا. مثالية للعائلات، الأصدقاء، ومنظمي الأحداث المحترفين، تجعل هذه المجموعات كل حدث لا يُنسى وجذاب بصريًا.</p>', NULL, NULL, '2025-08-24 15:28:50', '2025-08-24 15:36:38'),
(11, 51, 3, 64, 'Mini Lanterns / Fairy Lights', 'mini-lanterns--fairy-lights', 'Add instant excitement to any celebration with our Confetti Cannons and Party Poppers. Perfect for birthdays, weddings, and festive events, they create a fun and colorful atmosphere.', '<p>Bring the party to life with our Confetti Cannons and Party Poppers. Designed to create a burst of color and excitement, these party essentials instantly elevate any celebration. Whether it’s a birthday, wedding, graduation, or holiday event, confetti adds a magical touch that guests will remember.</p>\r\n<p>Each pack contains high-quality, safe, and easy-to-use confetti cannons or poppers. With vibrant colors and lightweight materials, they ensure a fun and mess-free experience. Simply pull the string or twist the cannon to release a shower of colorful confetti that transforms any space into a festive wonderland.</p>\r\n<p>Confetti Cannons and Party Poppers are suitable for indoor and outdoor use, making them versatile for different types of events. They are easy to store, reusable in some cases, and a cost-effective way to add a “wow” factor to your celebration. Perfect for photographers and event planners, these products make every event more photogenic and memorable.</p>\r\n<p>Celebrate in style and create unforgettable moments with our Confetti Cannons and Party Poppers. Bring joy, laughter, and color to your events with a simple yet spectacular party accessory.</p>', NULL, NULL, '2025-08-24 15:35:43', '2025-08-24 15:35:43'),
(12, 53, 4, 64, 'فوانيس صغيرة / أضواء خرافية', 'فوانيس-صغيرة--أضواء-خرافية', 'أضف الإثارة الفورية لأي احتفال مع مدافع الكونفيتي وبوبّر الحفلات لدينا. مثالية لأعياد الميلاد، حفلات الزفاف، والمناسبات الاحتفالية، تخلق جوًا ممتعًا وملونًا.', '<p>أحيي حفلتك مع مدافع الكونفيتي وبوبّر الحفلات لدينا. مصممة لإحداث انفجار من اللون والإثارة، ترفع هذه الأدوات الأساسية أي احتفال على الفور. سواء كانت حفلة عيد ميلاد، زفاف، تخرج، أو حدث عطلة، يضيف الكونفيتي لمسة سحرية لا تُنسى للضيوف.</p>\r\n<p>تحتوي كل مجموعة على مدافع أو بوبّرات عالية الجودة، آمنة وسهلة الاستخدام. مع ألوان زاهية ومواد خفيفة الوزن، تضمن تجربة ممتعة وخالية من الفوضى. ببساطة اسحب الخيط أو قم بتدوير المدفع لإطلاق وابل من الكونفيتي الملون الذي يحوّل أي مكان إلى عالم احتفالي رائع.</p>\r\n<p>مدافع الكونفيتي وبوبّر الحفلات مناسبة للاستخدام الداخلي والخارجي، مما يجعلها متعددة الاستخدامات لمختلف أنواع الأحداث. سهلة التخزين، وبعضها قابل لإعادة الاستخدام، وطريقة فعالة من حيث التكلفة لإضافة عامل المفاجأة إلى احتفالك. مثالية للمصورين ومنظمي الأحداث، تجعل كل حدث أكثر جاذبية وذكريات لا تُنسى.</p>\r\n<p>احتفل بأناقة واصنع لحظات لا تُنسى مع مدافع الكونفيتي وبوبّر الحفلات لدينا. جلب الفرح، الضحك، واللون إلى أحداثك مع ملحق حفلات بسيط ولكنه مذهل.</p>', NULL, NULL, '2025-08-24 15:35:43', '2025-08-24 15:35:43'),
(13, 51, 5, 65, 'Candies & Chocolates Gift Packs', 'candies--chocolates-gift-packs', 'Sweeten every occasion with our Candies & Chocolates Gift Packs. Perfect for birthdays, weddings, and festive celebrations, these packs bring joy with delicious flavors and elegant presentation.', '<p>Make your celebrations sweeter with our Candies &amp; Chocolates Gift Packs. Carefully curated with premium chocolates and assorted candies, these packs are designed to delight both children and adults. Whether it’s a birthday, wedding, holiday, or corporate gift, they make every moment memorable.</p>\r\n<p>Each pack features a variety of high-quality chocolates and candies, beautifully arranged in gift-ready packaging. The combination of flavors — from rich, creamy chocolate to fruity, chewy treats — ensures that there’s something for everyone to enjoy.</p>\r\n<p>Candies &amp; Chocolates Gift Packs are not just tasty, but also visually stunning. The elegant packaging makes them a thoughtful present for friends, family, and colleagues. They’re also perfect as party favors, dessert table additions, or surprise gifts for special occasions.</p>\r\n<p>These packs are versatile and suitable for all ages. Whether you’re celebrating with kids at a birthday party or offering a luxurious treat at a wedding, our gift packs deliver the perfect balance of taste and presentation.</p>\r\n<p>Share sweetness, spread joy, and create unforgettable memories with our Candies &amp; Chocolates Gift Packs — a delightful treat for every celebration.</p>', NULL, NULL, '2025-08-24 15:43:15', '2025-08-24 15:43:15'),
(14, 53, 6, 65, 'علب هدايا الحلويات والشوكولاتة', 'علب-هدايا-الحلويات-والشوكولاتة', 'أضف الحلاوة لكل مناسبة مع علب هدايا الحلويات والشوكولاتة لدينا. مثالية لأعياد الميلاد، حفلات الزفاف، والاحتفالات الخاصة، تجلب هذه العلب الفرح بنكهات لذيذة وتغليف أنيق.', '<p>أضف الحلاوة لكل مناسبة مع علب هدايا الحلويات والشوكولاتة لدينا. مثالية لأعياد الميلاد، حفلات الزفاف، والاحتفالات الخاصة، تجلب هذه العلب الفرح بنكهات لذيذة وتغليف أنيق.</p>\r\n<p><strong>المحتوى:</strong><br />اجعل احتفالاتك أكثر حلاوة مع علب هدايا الحلويات والشوكولاتة لدينا. تم اختيارها بعناية لتشمل شوكولاتة فاخرة وحلويات متنوعة، صُممت هذه العلب لإسعاد الأطفال والكبار على حد سواء. سواء كانت عيد ميلاد، زفاف، عطلة، أو هدية للشركات، فإنها تجعل كل لحظة لا تُنسى.</p>\r\n<p>تحتوي كل علبة على مجموعة متنوعة من الشوكولاتة والحلويات عالية الجودة، مرتبة بشكل جميل في تغليف جاهز للهدايا. يجمع المزيج بين النكهات المختلفة — من الشوكولاتة الغنية والكريمية إلى الحلويات الفاكهية والمطاطية — ليضمن أن يستمتع الجميع.</p>\r\n<p>علب هدايا الحلويات والشوكولاتة ليست لذيذة فحسب، بل جذابة بصريًا أيضًا. يجعل التغليف الأنيق منها هدية مدروسة للأصدقاء، العائلة، والزملاء. كما أنها مثالية كهدايا صغيرة للحفلات، إضافات لطاولة الحلوى، أو مفاجآت للمناسبات الخاصة.</p>\r\n<p>هذه العلب متعددة الاستخدامات ومناسبة لجميع الأعمار. سواء كنت تحتفل مع الأطفال في حفلة عيد ميلاد أو تقدم لمسة فاخرة في حفل زفاف، فإن علبنا توفر التوازن المثالي بين الطعم والشكل.</p>\r\n<p>شارك الحلاوة، انشر الفرح، واصنع ذكريات لا تُنسى مع علب هدايا الحلويات والشوكولاتة لدينا — متعة لذيذة لكل احتفال.</p>', NULL, NULL, '2025-08-24 15:43:15', '2025-08-24 15:43:15'),
(15, 51, 5, 66, 'Premium Gift Wrapping Kits', 'premium-gift-wrapping-kits', 'Make every gift extra special with our Premium Gift Wrapping Kits. Designed with stylish wrapping papers, ribbons, bows, and tags, this kit ensures your presents stand out beautifully for any occasion.', '<p>Gift giving is an art, and the first impression comes from how beautifully it is wrapped. Our <strong>Premium Gift Wrapping Kits</strong> are designed to turn ordinary gifts into extraordinary surprises. Whether you are preparing a present for birthdays, weddings, anniversaries, or festive celebrations, this kit has everything you need to make your gifts look stunning.</p>\r\n<p>The kit includes a variety of <strong>wrapping papers</strong> in stylish colors and patterns, from classic tones to festive themes. Along with this, you get <strong>decorative ribbons and bows</strong> that add a touch of elegance. We have also included <strong>gift tags</strong> so you can personalize each gift with a heartfelt message.</p>\r\n<p>One of the best features of our gift wrapping kit is its <strong>versatility</strong>. It’s not limited to one occasion – you can use it for birthdays, baby showers, Christmas, Valentine’s Day, corporate gifting, and more. Each material is <strong>high-quality, durable, and easy to use</strong>, ensuring that wrapping gifts is a fun and stress-free experience.</p>\r\n<p>If you often struggle with gift presentation or run out of supplies last minute, this kit is the <strong>perfect all-in-one solution</strong>. Instead of buying wrapping paper, ribbons, and tags separately, you get everything you need in one convenient package – saving you time and money.</p>\r\n<p>Apart from being practical, our gift wrapping kits also add a personal touch to your presents. A beautifully wrapped gift shows thoughtfulness, care, and effort, making the recipient feel truly special even before they open the box.</p>\r\n<p>Whether you are wrapping small gifts, large boxes, or oddly shaped items, our versatile collection of papers and accessories makes it easy to create a neat and elegant finish. The variety ensures you’ll always have the right style for the right event.</p>\r\n<p>Give your gifts the finishing touch they deserve with our <strong>Premium Gift Wrapping Kits</strong>. Perfect for families, party hosts, and anyone who loves to add beauty to their presents.</p>', NULL, NULL, '2025-08-24 15:47:55', '2025-08-24 15:47:55'),
(16, 53, 6, 66, 'أطقم تغليف الهدايا الفاخرة – عرض أنيق وأسلوب مميز', 'أطقم-تغليف-الهدايا-الفاخرة-–-عرض-أنيق-وأسلوب-مميز', 'اجعل كل هدية مميزة مع أطقم تغليف الهدايا الفاخرة لدينا. مصممة بأوراق تغليف أنيقة، وأشرطة، وأقواس، وبطاقات لتجعل هداياك تتألق في أي مناسبة.', '<p>قديم الهدايا فن يبدأ من طريقة تغليفها. لذلك صممنا <strong>أطقم تغليف الهدايا الفاخرة</strong> لتجعل هداياك أكثر تميزاً وروعة. سواء كنت تحضر هدية لعيد ميلاد، زفاف، ذكرى سنوية أو أي مناسبة خاصة، فهذا الطقم يحتوي على كل ما تحتاجه لإبراز جمال هديتك.</p>\r\n<p>يحتوي الطقم على <strong>أوراق تغليف أنيقة</strong> بتصاميم وألوان متعددة تناسب جميع المناسبات، من الألوان الكلاسيكية الهادئة إلى التصاميم الاحتفالية المبهجة. كما يتضمن <strong>أشرطة وأقواس زخرفية</strong> تضيف لمسة فاخرة إلى كل هدية، بالإضافة إلى <strong>بطاقات معايدة</strong> لتكتب رسالتك الخاصة للشخص الذي تهديه.</p>\r\n<p>يتميز هذا الطقم بكونه <strong>متعدد الاستخدامات</strong>، حيث يمكنك استخدامه في مختلف المناسبات مثل أعياد الميلاد، حفلات المواليد، الكريسماس، عيد الحب، والهدايا الرسمية في العمل. جميع المواد عالية الجودة وسهلة الاستخدام، مما يجعل تغليف الهدايا ممتعاً وخالياً من التوتر.</p>\r\n<p>بدلاً من شراء أوراق التغليف والأشرطة والبطاقات بشكل منفصل، ستحصل في هذا الطقم على <strong>حل متكامل</strong> يوفر لك الوقت والمال.</p>\r\n<p>التغليف الجميل لا يضيف فقط مظهراً أنيقاً للهدية، بل يعكس أيضاً <strong>الاهتمام والحرص</strong> ويجعل متلقي الهدية يشعر بسعادة أكبر قبل حتى أن يفتح العلبة.</p>\r\n<p>مهما كان حجم أو شكل الهدية، يوفر لك هذا الطقم تشكيلة متنوعة لتغليفها بطريقة مرتبة وجذابة. إنه مثالي للأسر، منسقي الحفلات، ولكل شخص يحب إضافة لمسة جمالية إلى الهدايا.</p>\r\n<p>اجعل هداياك مميزة واستثنائية مع <strong>أطقم تغليف الهدايا الفاخرة</strong> التي تضيف لمسة من الأناقة لكل مناسبة.</p>', NULL, NULL, '2025-08-24 15:47:55', '2025-08-24 15:47:55'),
(17, 51, 7, 67, 'Event T-Shirts / Caps', 'event-t-shirts--caps', 'Make your events unforgettable with personalized T-Shirts and Caps designed for weddings, birthdays, corporate events, and parties. Comfortable, stylish, and customizable to match your theme.', '<p>Event T-Shirts and Caps are more than just clothing – they are a statement of unity, joy, and celebration. Whether you’re organizing a <strong>wedding party, birthday celebration, team-building event, or corporate gathering</strong>, having matching apparel instantly elevates the experience.</p>\r\n<p>Our collection offers <strong>soft cotton T-shirts</strong> and <strong>durable caps</strong>, available in multiple sizes and colors. Each item can be <strong>custom-printed</strong> with names, logos, slogans, or special messages, making them ideal for both personal and professional use.</p>\r\n<p>Imagine your guests entering the event, all wearing coordinated T-shirts and caps featuring your event theme – it creates an atmosphere of togetherness while also making photos and videos look more vibrant and memorable.</p>\r\n<p>These products are not only stylish but also practical. The <strong>breathable fabric</strong> ensures comfort during long events, while the caps provide sun protection for outdoor gatherings. Perfect for <strong>sports events, bachelor parties, family reunions, or promotional activities</strong>, they make every occasion special.</p>\r\n<p>Additionally, Event T-Shirts and Caps serve as <strong>wonderful souvenirs</strong>. Guests can take them home as a reminder of the event, ensuring your special day remains cherished long after it’s over.</p>\r\n<p>Upgrade your event with these personalized apparel items and let your guests feel truly part of the celebration.</p>', NULL, NULL, '2025-08-24 15:52:33', '2025-08-24 15:52:33'),
(18, 53, 8, 67, 'قمصان/قبعات للمناسبات', 'قمصانقبعات-للمناسبات', 'اجعل مناسباتك لا تُنسى مع القمصان والقبعات المخصصة لحفلات الزفاف وأعياد الميلاد والفعاليات التجارية والمناسبات الخاصة. مريحة، أنيقة، وقابلة للتخصيص لتناسب أجواء احتفالك.', '<p>تعتبر القمصان والقبعات الخاصة بالمناسبات أكثر من مجرد ملابس – فهي رمز للوحدة والفرح والاحتفال. سواء كنت تنظّم <strong>حفلة زفاف، عيد ميلاد، فعالية للشركة، أو لقاء عائلي</strong>، فإن ارتداء ملابس متطابقة يعزز الأجواء بشكل فوري.</p>\r\n<p>تتضمن مجموعتنا <strong>قمصان قطنية ناعمة</strong> و <strong>قبعات متينة</strong>، متوفرة بمقاسات وألوان متعددة. يمكن تخصيص كل قطعة بطباعة الأسماء أو الشعارات أو العبارات الخاصة، مما يجعلها مثالية للاستخدام الشخصي أو المهني.</p>\r\n<p>تخيّل ضيوفك وهم يدخلون إلى الحفل جميعهم يرتدون قمصان وقبعات متناسقة تحمل شعار أو فكرة المناسبة – سيخلق ذلك جواً من الانسجام ويجعل الصور ومقاطع الفيديو أكثر تميزاً وجمالاً.</p>\r\n<p>إلى جانب مظهرها الأنيق، تتميز هذه المنتجات بكونها عملية أيضاً. فالأقمشة القابلة للتنفس تمنح راحة طوال اليوم، بينما توفر القبعات حماية من أشعة الشمس خلال الفعاليات الخارجية. إنها مناسبة تماماً لـ <strong>المباريات الرياضية، حفلات توديع العزوبية، الاجتماعات العائلية، أو الحملات الترويجية</strong>، مما يجعل كل مناسبة مميزة.</p>\r\n<p>كما أن القمصان والقبعات تعتبر <strong>تذكارات رائعة</strong> يمكن للضيوف أخذها معهم بعد المناسبة، لتبقى ذكرى يومك الخاص خالدة في قلوبهم.</p>\r\n<p>اجعل مناسبتك أكثر تميزاً مع هذه الملابس المخصصة ودع ضيوفك يشعرون بأنهم جزء حقيقي من الاحتفال.</p>', NULL, NULL, '2025-08-24 15:52:33', '2025-08-24 15:52:33'),
(19, 51, 7, 68, 'Handmade Jewelry – Unique Artisan Craft for Every Occasion', 'handmade-jewelry-–-unique-artisan-craft-for-every-occasion', 'Discover the beauty of handmade jewelry crafted with passion and attention to detail. Each piece is carefully designed to reflect uniqueness, making it the perfect gift or personal accessory for weddings, birthdays, and special events.', '<p>Discover the beauty of <strong>handmade jewelry</strong> crafted with passion and attention to detail. Each piece is carefully designed to reflect uniqueness, making it the perfect gift or personal accessory for weddings, birthdays, and special events.</p>\r\n<p><strong>Content (Approx. 500 words):</strong><br />Handmade jewelry is more than just an accessory – it’s a piece of art that carries the story of craftsmanship, culture, and individuality. Unlike mass-produced pieces, every handmade jewelry item reflects the creativity and skill of the artisan who designed it. Whether you’re searching for a meaningful gift or a personal keepsake, handmade jewelry offers unmatched uniqueness and sentimental value.</p>\r\n<p>One of the greatest advantages of handmade jewelry is its individuality. No two pieces are ever exactly the same, ensuring that you own something truly special. From delicate necklaces and elegant bracelets to intricate earrings and bold rings, each item is carefully handcrafted with precision and love. This personal touch makes handmade jewelry a timeless accessory for weddings, anniversaries, birthdays, and even casual everyday wear.</p>\r\n<p>The materials used in handmade jewelry often include natural gemstones, beads, silver, gold, and eco-friendly elements. This not only ensures durability but also highlights sustainable practices, as many artisans prefer ethical sourcing. By purchasing handmade jewelry, you’re not only supporting small businesses and local craftsmen but also promoting environmentally conscious fashion.</p>\r\n<p>Another special aspect of handmade jewelry is its ability to reflect cultural traditions. Many artisans incorporate traditional patterns, symbols, and techniques that have been passed down through generations. This makes every piece more than just jewelry – it becomes a part of history and storytelling.</p>\r\n<p>Handmade jewelry is also the perfect personalized gift. Whether you want to surprise a loved one with a custom engraving, their birthstone, or a meaningful symbol, handmade artisans can tailor each piece to suit your needs. This thoughtful customization adds emotional value that no factory-made product can replicate.</p>\r\n<p>In today’s fast-paced world of trends, handmade jewelry stands out as a reminder of authenticity and timeless style. It allows you to express your personality while appreciating the beauty of craftsmanship. Investing in handmade jewelry means choosing quality, creativity, and emotional connection.</p>\r\n<p>If you’re looking for jewelry that tells a story, represents individuality, and brings elegance to your collection, handmade jewelry is the perfect choice. Whether as a gift or a personal treasure, these artisan-crafted pieces will remain a cherished part of your life for years to come.</p>', NULL, NULL, '2025-08-24 15:54:23', '2025-08-24 15:54:23'),
(20, 53, 8, 68, 'مجوهرات يدوية – إبداع حرفي فريد لكل مناسبة', 'مجوهرات-يدوية-–-إبداع-حرفي-فريد-لكل-مناسبة', 'اكتشف جمال المجوهرات اليدوية المصنوعة بشغف وعناية فائقة بالتفاصيل. كل قطعة مصممة لتعكس التفرد والتميز، مما يجعلها الهدية المثالية أو الإكسسوار الشخصي لحفلات الزفاف وأعياد الميلاد والمناسبات الخاصة.', '<p>اكتشف جمال <strong>المجوهرات اليدوية</strong> المصنوعة بشغف وعناية فائقة بالتفاصيل. كل قطعة مصممة لتعكس التفرد والتميز، مما يجعلها الهدية المثالية أو الإكسسوار الشخصي لحفلات الزفاف وأعياد الميلاد والمناسبات الخاصة.</p>\r\n<p><strong>المحتوى (حوالي 500 كلمة):</strong><br />المجوهرات اليدوية ليست مجرد إكسسوار، بل هي عمل فني يحمل قصة الحرفية والثقافة والتميز. على عكس المنتجات الجاهزة، كل قطعة من المجوهرات اليدوية تعكس إبداع ومهارة الصانع الذي صممها. سواء كنت تبحث عن هدية مميزة أو تذكار شخصي، فإن المجوهرات اليدوية تقدم قيمة فريدة لا مثيل لها.</p>\r\n<p>أحد أهم مميزات المجوهرات اليدوية هو التفرد. لا توجد قطعتان متطابقتان تمامًا، مما يضمن لك امتلاك شيء خاص وفريد. من العقود الأنيقة والأساور الرقيقة إلى الأقراط المميزة والخواتم الجريئة، تُصنع كل قطعة بعناية وحب. هذا الاهتمام يجعل المجوهرات اليدوية إضافة مثالية للأعراس والذكرى السنوية وأعياد الميلاد وحتى للاستخدام اليومي.</p>\r\n<p>المواد المستخدمة غالبًا ما تشمل الأحجار الكريمة الطبيعية، والخرز، والفضة، والذهب، وعناصر صديقة للبيئة. هذا يضمن المتانة ويعزز الممارسات المستدامة، حيث يفضل العديد من الحرفيين مصادر أخلاقية للمواد. شراء المجوهرات اليدوية يعني دعم الحرفيين المحليين وتشجيع الموضة المستدامة.</p>\r\n<p>جانب آخر مميز هو ارتباطها بالثقافات والتقاليد. العديد من الحرفيين يدمجون رموزًا وأنماطًا قديمة وتقنيات موروثة، مما يجعل كل قطعة أكثر من مجرد إكسسوار – إنها قطعة تحمل قصة وتاريخًا.</p>\r\n<p>المجوهرات اليدوية أيضًا خيار مثالي للهدايا الشخصية. يمكنك تخصيصها بنقش خاص أو حجر ميلاد أو رمز ذو معنى، مما يضيف قيمة عاطفية لا يمكن أن يقدمها المنتج الصناعي.</p>\r\n<p>في عالم سريع التغير مليء بالموضة السريعة، تبقى المجوهرات اليدوية رمزًا للأصالة والأناقة. إنها وسيلة للتعبير عن شخصيتك مع تقدير جمال الحرفية والإبداع.</p>\r\n<p>إذا كنت تبحث عن مجوهرات تحكي قصة وتمثل التفرد وتضيف لمسة من الأناقة لمجموعتك، فإن المجوهرات اليدوية هي الخيار المثالي. سواء كهدية أو ككنز شخصي، ستظل هذه القطع المصنوعة بإبداع جزءًا عزيزًا من حياتك لسنوات طويلة.</p>', NULL, NULL, '2025-08-24 15:54:23', '2025-08-24 15:54:23'),
(21, 51, 7, 69, 'Colorful Party Hats & Fun Masks for Celebrations', 'colorful-party-hats--fun-masks-for-celebrations', 'Add excitement and fun to your celebration with our vibrant Party Hats & Masks. Perfect for birthdays, weddings, and festive gatherings, these accessories bring smiles, laughter, and a festive vibe to any event.', '<p>Make your parties unforgettable with our <strong>Party Hats &amp; Masks</strong>, designed to add joy and creativity to every celebration. Whether it’s a child’s birthday, a wedding after-party, or a festive gathering with friends, these fun accessories are guaranteed to light up the occasion.</p>\r\n<p>Our collection includes a variety of colorful hats and masks that suit all themes and age groups. From sparkling cone hats for kids to elegant masks for adults, you’ll find options that perfectly match your event. Crafted with high-quality materials, the hats and masks are lightweight, comfortable, and durable enough to last throughout your celebration.</p>\r\n<p>Not only do they enhance the party atmosphere, but they also make for memorable photo opportunities. Guests can enjoy dressing up, taking group selfies, and sharing the fun on social media. Whether you’re planning a small gathering or a big event, these hats and masks are the perfect addition to your party supplies.</p>\r\n<p>Affordable and reusable, they are an excellent choice for anyone looking to add flair without spending a fortune. Give your guests a unique experience and let your event stand out with stylish, creative, and festive <strong>Party Hats &amp; Masks</strong>.</p>', NULL, NULL, '2025-08-24 15:57:14', '2025-08-24 15:57:14'),
(22, 53, 8, 69, 'قبعات وأقنعة الحفلات الملونة للاحتفالات', 'قبعات-وأقنعة-الحفلات-الملونة-للاحتفالات', 'أضف الحماس والمرح إلى احتفالك مع قبعات وأقنعة الحفلات الملونة. مثالية لأعياد الميلاد وحفلات الزفاف والمناسبات، حيث تضيف البهجة والمرح إلى أجواء أي احتفال.', '<p>اجعل حفلاتك لا تُنسى مع <strong>قبعات وأقنعة الحفلات</strong> التي صُممت لإضافة المرح والإبداع إلى كل مناسبة. سواء كانت حفلة عيد ميلاد للأطفال، أو حفلة زفاف، أو تجمع احتفالي مع الأصدقاء، فإن هذه الإكسسوارات الممتعة ستضيء أجواء المناسبة وتجعلها مليئة بالبهجة.</p>\r\n<p>تتضمن مجموعتنا مجموعة متنوعة من القبعات والأقنعة الملونة التي تناسب جميع الأذواق والأعمار. من القبعات المخروطية البراقة للأطفال إلى الأقنعة الأنيقة للكبار، ستجد خيارات مثالية لأي نوع من الاحتفالات. مصنوعة من مواد عالية الجودة، تتميز هذه القبعات والأقنعة بأنها خفيفة الوزن ومريحة ومتينة لتدوم طوال الحفل.</p>\r\n<p>لا تضيف فقط أجواءً احتفالية، بل تمنح ضيوفك فرصة رائعة لالتقاط صور تذكارية مميزة. يمكن للجميع ارتداؤها، والتقاط صور جماعية مرحة، ومشاركتها على وسائل التواصل الاجتماعي. سواء كنت تخطط لاحتفال صغير أو مناسبة كبيرة، فإن هذه القبعات والأقنعة ستكون إضافة مثالية لمستلزمات الحفلات الخاصة بك.</p>\r\n<p>وبفضل سعرها المناسب وقابليتها لإعادة الاستخدام، فهي خيار رائع لمن يريد إضافة لمسة جمالية إلى حفله دون إنفاق الكثير. امنح ضيوفك تجربة مختلفة، ودع مناسبتك تتميز بلمسة من المرح والإبداع مع <strong>قبعات وأقنعة الحفلات</strong>.</p>', NULL, NULL, '2025-08-24 15:57:14', '2025-08-24 15:57:14'),
(23, 51, 9, 70, 'Small Plush Toys & Dolls – Adorable Companions for Every Occasion', 'small-plush-toys--dolls-–-adorable-companions-for-every-occasion', 'Delight children and adults alike with our collection of small plush toys and dolls. These soft, huggable companions make the perfect gifts, party favors, or decorations for any special occasion.', '<p>Small plush toys and dolls hold a special place in our hearts. Whether you are planning a birthday, a wedding, or a festive celebration, these adorable companions add joy and warmth to every occasion. Their small size makes them perfect as party favors, giveaway gifts, or thoughtful tokens of appreciation.</p>\r\n<p>Crafted from premium-quality, hypoallergenic materials, our plush toys are safe for children and incredibly soft to the touch. They are available in a wide range of cute designs—from animals and fantasy creatures to classic dolls—ensuring there’s a perfect choice for everyone.</p>\r\n<p>For event planners, small plush toys and dolls can be used as table decorations, giveaway gifts, or themed accessories that add a personal touch to any celebration. They can also be customized with colors, ribbons, or tags to match the theme of your event.</p>\r\n<p>Beyond parties, these little companions also serve as comfort items for children, meaningful keepsakes for couples, or collectible items for enthusiasts. They combine affordability with high emotional value, making them a timeless choice for thoughtful gifting.</p>\r\n<p>Whether you want to surprise your guests, delight a child, or simply decorate your event with something unique, our small plush toys and dolls are the perfect solution.</p>', NULL, NULL, '2025-08-24 16:00:33', '2025-08-24 16:00:33'),
(24, 53, 10, 70, 'الدمى والعرائس المحشوة الصغيرة – رفيق رائع لكل مناسبة', 'الدمى-والعرائس-المحشوة-الصغيرة-–-رفيق-رائع-لكل-مناسبة', 'أدخل البهجة على قلوب الأطفال والكبار مع مجموعتنا من الدمى والعرائس المحشوة الصغيرة. إنها هدايا مثالية، أو تذكارات للحفلات، أو ديكورات لأي مناسبة خاصة.', '<p>تحتل الدمى والعرائس المحشوة الصغيرة مكانة خاصة في قلوبنا. سواء كنت تخطط لحفل عيد ميلاد، زفاف، أو أي احتفال مميز، فإن هذه الرفقاء الصغار يجلبون الفرح والدفء لكل مناسبة. حجمها الصغير يجعلها مثالية كتوزيعات للحفلات، أو هدايا تذكارية، أو تعبير بسيط عن التقدير.</p>\r\n<p>تم تصنيعها من مواد عالية الجودة ومضادة للحساسية، مما يجعلها آمنة للأطفال وناعمة للغاية عند اللمس. كما تتوفر بتصاميم متعددة رائعة، بدءًا من الحيوانات والكائنات الخيالية وصولاً إلى الدمى الكلاسيكية، مما يضمن وجود خيار يناسب كل الأذواق.</p>\r\n<p>لمنظمي الفعاليات، يمكن استخدام الدمى الصغيرة كزينة للطاولات، أو كهدايا للمدعوين، أو كإكسسوارات مخصصة تضيف لمسة شخصية لأي احتفال. كما يمكن تخصيصها بالألوان أو الشرائط أو البطاقات لتتناسب مع موضوع الحدث.</p>\r\n<p>بعيدًا عن الحفلات، يمكن أن تكون هذه الألعاب الصغيرة وسيلة للراحة للأطفال، أو تذكارات مميزة للأزواج، أو حتى قطعًا قابلة للجمع لعشاق الدمى. فهي تجمع بين السعر المناسب والقيمة العاطفية العالية، مما يجعلها خيارًا مثاليًا للهدايا.</p>\r\n<p>سواء كنت ترغب في مفاجأة ضيوفك، أو إسعاد طفل، أو تزيين احتفالك بشيء مميز، فإن الدمى والعرائس المحشوة الصغيرة هي الخيار المثالي.</p>', NULL, NULL, '2025-08-24 16:00:33', '2025-08-24 16:00:33');

-- --------------------------------------------------------

--
-- Table structure for table `product_coupons`
--

CREATE TABLE `product_coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `value` decimal(8,2) UNSIGNED DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `minimum_spend` decimal(8,2) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_orders`
--

CREATE TABLE `product_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `order_number` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `billing_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `billing_email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `billing_phone` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `billing_address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `billing_city` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `billing_state` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `billing_country` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `shipping_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `shipping_email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `shipping_phone` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `shipping_address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `shipping_city` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `shipping_state` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `shipping_country` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `total` decimal(12,2) UNSIGNED NOT NULL,
  `discount` decimal(10,2) UNSIGNED DEFAULT NULL,
  `product_shipping_charge_id` bigint UNSIGNED DEFAULT NULL,
  `shipping_cost` decimal(10,2) UNSIGNED DEFAULT NULL,
  `tax` decimal(12,2) UNSIGNED NOT NULL,
  `grand_total` decimal(12,2) UNSIGNED NOT NULL,
  `currency_text` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `currency_text_position` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `currency_symbol` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `currency_symbol_position` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `gateway_type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `order_status` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `invoice` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `conversation_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_purchase_items`
--

CREATE TABLE `product_purchase_items` (
  `id` bigint UNSIGNED NOT NULL,
  `product_order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `comment` longtext,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `product_shipping_charges`
--

CREATE TABLE `product_shipping_charges` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `short_text` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `shipping_charge` decimal(8,2) UNSIGNED NOT NULL,
  `serial_number` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `product_shipping_charges`
--

INSERT INTO `product_shipping_charges` (`id`, `language_id`, `title`, `short_text`, `shipping_charge`, `serial_number`, `created_at`, `updated_at`) VALUES
(1, 51, 'Standard Shipping', '5-7 Business Days', 5.99, 1, '2025-08-25 06:27:59', '2025-08-25 06:27:59'),
(2, 53, 'الشحن القياسي', '5-7 أيام عمل', 5.99, 1, '2025-08-25 06:28:27', '2025-08-25 06:28:46'),
(3, 51, 'Expedited Shipping', '2-3 Business Days', 12.99, 2, '2025-08-25 06:29:21', '2025-08-25 06:29:21'),
(4, 53, 'الشحن السريع', '2-3 أيام عمل', 12.99, 2, '2025-08-25 06:30:04', '2025-08-25 06:30:04'),
(5, 51, 'Overnight Shipping', 'Next Business Day', 24.99, 3, '2025-08-25 06:30:32', '2025-08-25 06:30:32'),
(6, 53, 'الشحن بين عشية وضحاها', 'يوم العمل التالي', 24.99, 3, '2025-08-25 06:31:07', '2025-08-25 06:31:07');

-- --------------------------------------------------------

--
-- Table structure for table `push_subscriptions`
--

CREATE TABLE `push_subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `subscribable_type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `subscribable_id` bigint UNSIGNED NOT NULL,
  `endpoint` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `public_key` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `auth_token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `content_encoding` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qr_codes`
--

CREATE TABLE `qr_codes` (
  `id` bigint UNSIGNED NOT NULL,
  `seller_id` bigint DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quick_links`
--

CREATE TABLE `quick_links` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `serial_number` smallint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `quick_links`
--

INSERT INTO `quick_links` (`id`, `language_id`, `title`, `url`, `serial_number`, `created_at`, `updated_at`) VALUES
(1, 51, 'Browse Venues / Spaces', 'https://spacekoi.test/spaces', 2, '2025-08-16 13:55:18', '2025-08-16 13:58:14'),
(2, 51, 'Home', 'https://spacekoi.test/', 1, '2025-08-16 13:57:55', '2025-08-16 13:58:10'),
(3, 51, 'About Us', 'https://spacekoi.test/about-us', 3, '2025-08-16 13:59:09', '2025-08-16 13:59:09'),
(4, 51, 'FAQs', 'https://spacekoi.test/faq', 4, '2025-08-16 14:00:20', '2025-08-16 14:00:20'),
(5, 51, 'Become a Vendor / List Your Space', 'https://spacekoi.test/vendor/signup', 5, '2025-08-16 14:01:10', '2025-08-16 14:01:10'),
(6, 51, 'Pricing / Packages', 'https://spacekoi.test/pricing', 6, '2025-08-16 14:01:50', '2025-08-16 14:01:50'),
(7, 51, 'Blog / Articles', 'https://spacekoi.test/blog', 7, '2025-08-16 14:02:43', '2025-08-16 14:02:43'),
(8, 53, 'المدونة / المقالات', 'https://spacekoi.test/blog', 7, '2025-08-17 16:30:56', '2025-08-17 16:35:39'),
(9, 53, 'التسعير / الباقات', 'https://spacekoi.test/pricing', 6, '2025-08-17 16:31:24', '2025-08-17 16:35:25'),
(10, 53, 'كن بائعًا', 'https://spacekoi.test/vendor/signup', 5, '2025-08-17 16:32:02', '2025-08-17 16:35:10'),
(11, 53, 'الأسئلة الشائعة', 'https://spacekoi.test/faq', 4, '2025-08-17 16:32:30', '2025-08-17 16:32:30'),
(12, 53, 'معلومات عنا', 'https://spacekoi.test/about-us', 3, '2025-08-17 16:32:59', '2025-08-17 16:34:47'),
(13, 53, 'بيت', 'https://spacekoi.test/', 1, '2025-08-17 16:33:41', '2025-08-17 16:33:41'),
(14, 53, 'تصفح الأماكن / المساحات', 'https://spacekoi.test/spaces', 2, '2025-08-17 16:34:12', '2025-08-17 16:34:12');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `permissions` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint UNSIGNED NOT NULL,
  `space_category_section_status` tinyint NOT NULL DEFAULT '1',
  `about_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `features_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `video_banner_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `testimonials_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `blog_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `work_process_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `popular_city_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `newsletter_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `footer_section_status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `space_banner_section_status` int DEFAULT '1',
  `additional_section_status` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `space_category_section_status`, `about_section_status`, `features_section_status`, `video_banner_section_status`, `testimonials_section_status`, `blog_section_status`, `work_process_section_status`, `popular_city_section_status`, `newsletter_section_status`, `footer_section_status`, `space_banner_section_status`, `additional_section_status`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '{\"2\":\"1\"}', NULL, '2025-07-13 20:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `section_contents`
--

CREATE TABLE `section_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint NOT NULL,
  `category_section_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured_section_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_section_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_section_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `workprocess_section_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_section_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_section_button_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimonial_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_banner_video_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `popular_city_section_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `popular_city_section_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `popular_city_section_button_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `section_contents`
--

INSERT INTO `section_contents` (`id`, `language_id`, `category_section_title`, `featured_section_title`, `hero_section_title`, `hero_section_text`, `workprocess_section_title`, `banner_section_title`, `banner_section_button_text`, `testimonial_title`, `video_banner_video_link`, `popular_city_section_title`, `popular_city_section_text`, `popular_city_section_button_name`, `created_at`, `updated_at`) VALUES
(1, 51, 'Our Popular Categories', 'Our Featured Spaces', 'Where Every Event Finds Its Perfect Space', 'Seamlessly discover event spaces that match your vision—luxury, comfort, and convenience all in one place.', 'Our Simple Booking Process', '100+ Spaces Waiting for Your Next Event', 'Start Space Booking', 'What Our Trusted Clients Say About Us', 'https://www.youtube.com/watch?v=MhhAox6Zei8', 'Explore 100+ Popular Cities for Spaces', '<p>Spacekoi website where planning your next conference is as easy as booking a hotel. Sleeking for yours.</p>', 'Explore More Cities', '2025-08-16 14:44:19', '2025-08-24 12:58:35'),
(2, 53, 'فئاتنا الشعبية', 'مساحاتنا المميزة', 'حيث يجد كل حدث مكانه المثالي', 'اكتشف بسلاسة أماكن الأحداث التي تتوافق مع رؤيتك - الفخامة والراحة والرفاهية، كل ذلك في مكان واحد.', 'عملية الحجز البسيطة لدينا', 'أكثر من 100 مكان في انتظار حدثك القادم', 'ابدأ حجز المساحة', 'ماذا يقول عملاؤنا الموثوق بهم عنا', 'https://www.youtube.com/watch?v=MhhAox6Zei8', 'استكشف أكثر من 100 مدينة مشهورة بالمساحات', '<p>موقع سبيس كوي الإلكتروني، حيث يمكنك التخطيط لمؤتمرك القادم بسهولة حجز فندق. تصميم أنيق يناسبك.</p>', 'استكشف المزيد من المدن', '2025-08-17 16:40:56', '2025-08-24 13:00:26');

-- --------------------------------------------------------

--
-- Table structure for table `section_titles`
--

CREATE TABLE `section_titles` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `category_section_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `space_banner_section_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `work_process_section_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `testimonials_section_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `popular_cities_section_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `blog_section_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `featured_products_section_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `newsletter_section_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `featured_space_section_title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sellers`
--

CREATE TABLE `sellers` (
  `id` bigint UNSIGNED NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_mail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int DEFAULT '0',
  `amount` double(15,2) DEFAULT '0.00',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `avg_rating` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show_email_addresss` tinyint DEFAULT '1',
  `show_phone_number` tinyint DEFAULT '1',
  `show_contact_form` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sellers`
--

INSERT INTO `sellers` (`id`, `photo`, `email`, `recipient_mail`, `phone`, `username`, `password`, `status`, `amount`, `email_verified_at`, `avg_rating`, `show_email_addresss`, `show_phone_number`, `show_contact_form`, `created_at`, `updated_at`) VALUES
(0, NULL, 'demo1@example.com', 'demo1@example.com', NULL, 'admin', NULL, 1, 0.00, NULL, NULL, 1, 1, 1, '2025-08-17 11:54:31', '2025-08-17 11:54:31'),
(66, '68ac37de83184.png', 'demo2@example.com', 'demo2@example.com', '111111', 'eleanor', '$2y$10$xtEcBm0cueOroOPRo4/ro.zgiLbCza00jrNBlG8mLWig655GRwUce', 1, 0.00, '2025-08-22 05:53:29', NULL, 1, 1, 1, '2025-08-22 05:53:04', '2025-08-25 10:15:58'),
(67, '68ac3f52a5b1d.jpg', 'demo3@example.com', 'demo3@example.com', '22222', 'marcus', '$2y$10$iVpSTRqMrvmVDHdE9wjKwe8MCfZ5DFKsSlfiZ3WlOh2iqlaYxwjS2', 1, 0.00, '2025-08-22 13:56:00', NULL, 1, 1, 1, '2025-08-22 13:56:00', '2025-09-09 13:25:53'),
(68, '68ac3b02375d3.jpg', 'demo4@example.com', 'demo4@example.com', '3333', 'priya', '$2y$10$nf4vSrzHPm6.LWepvoQgBOTuyDeFouvt4V6aOGKUBsdZR2ha/wcNe', 1, 0.00, '2025-08-23 11:41:16', NULL, 1, 1, 1, '2025-08-23 11:41:16', '2025-08-25 10:29:22');

-- --------------------------------------------------------

--
-- Table structure for table `seller_infos`
--

CREATE TABLE `seller_infos` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint DEFAULT NULL,
  `seller_id` bigint DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seller_infos`
--

INSERT INTO `seller_infos` (`id`, `language_id`, `seller_id`, `name`, `country`, `city`, `state`, `zip_code`, `address`, `details`, `created_at`, `updated_at`) VALUES
(1, 51, 66, 'Eleanor Vance', 'Unit', 'Maui', 'Hawaii', '96753', NULL, 'Seaside Luxury Resorts specializes in exclusive, all-inclusive beachfront getaways. They focus on high-end amenities, private villas, and curated experiences for couples and families seeking a premium vacation', '2025-08-22 05:53:04', '2025-08-25 10:56:00'),
(2, 53, 66, 'إليانور فانس', 'الولايات المتحدة', 'ماوي', 'هاواي', '96753', NULL, 'تتخصص منتجعات سيسايد الفاخرة في توفير عطلات شاطئية حصرية وشاملة. وتركز على توفير وسائل راحة فاخرة، وفيلات خاصة، وتجارب مصممة خصيصاً للأزواج والعائلات الباحثين عن عطلة مميزة.', '2025-08-22 05:53:04', '2025-08-25 10:56:00'),
(3, 51, 67, 'Marcus Chen', 'United States', 'New York', NULL, '10024', NULL, 'Urban Office Solutions provides flexible, turnkey office rentals for startups and established businesses. Their portfolio includes private suites, co-working memberships, and fully serviced executive offices in prime downtown locations', '2025-08-22 14:20:47', '2025-08-25 10:49:12'),
(4, 53, 67, 'ماركوس تشين', 'الولايات المتحدة', 'نيويورك', NULL, '10024', NULL, 'تقدم \"حلول المكاتب الحضرية\" استئجار مكاتب جاهزة ومرنة للشركات الناشئة والقائمة. تشمل خدماتها أجنحة خاصة، وعضويات في مساحات عمل مشتركة، ومكاتب تنفيذية متكاملة الخدمات في مواقع مميزة بوسط المدينة.', '2025-08-22 14:20:47', '2025-08-25 10:49:23'),
(5, 51, 68, 'Priya  Sharma', 'USA', 'Seattle', 'WA', '98101', '123 Pine Street', 'A luxury beachfront resort specializing in honeymoon packages and wellness retreats. Features private villas, a world-class spa, and three gourmet restaurants. Focuses on sustainable tourism and authentic Hawaiian cultural experiences', '2025-08-25 10:29:22', '2025-08-25 10:29:22'),
(6, 53, 68, 'بريا شارما', 'الولايات المتحدة الأمريكية', 'سياتل', 'واشنطن', '98101', '123 شارع باين', 'منتجع فاخر على شاطئ البحر، متخصص في باقات شهر العسل ومنتجعات العافية. يضم فيلات خاصة، ومنتجعًا صحيًا عالمي المستوى، وثلاثة مطاعم فاخرة. يركز على السياحة المستدامة والتجارب الثقافية الأصيلة في هاواي.', '2025-08-25 10:29:22', '2025-08-25 10:29:22');

-- --------------------------------------------------------

--
-- Table structure for table `seos`
--

CREATE TABLE `seos` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `meta_keyword_home` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description_home` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keyword_spaces` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description_spaces` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keyword_space_details` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description_space_details` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keyword_space_booking` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description_space_booking` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keyword_pricing` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description_pricing` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `vendor_page_meta_keywords` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `vendor_page_meta_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `shop_page_meta_keywords` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `shop_page_meta_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `cart_page_meta_keywords` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `cart_page_meta_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keyword_aboutus` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description_aboutus` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keyword_faq` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description_faq` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `meta_description_term_and_condition` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description_checkout` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keyword_contact` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description_contact` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keyword_customer_login` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description_customer_login` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keyword_customer_signup` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description_customer_signup` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keyword_customer_forget_password` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_description_customer_forget_password` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keyword_vendor_login` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_description_vendor_login` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keyword_vendor_signup` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_description_vendor_signup` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keyword_vendor_forget_password` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_description_vendor_forget_password` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `shop_checkout_page_meta_keywords` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `shop_checkout_page_meta_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keyword_blog` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description_blog` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_keyword_term_and_condition` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_keyword_blog_post_details` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `meta_description_blog_post_details` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `vendor_details_page_meta_keywords` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `vendor_details_page_meta_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `product_details_page_meta_keywords` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `product_details_page_meta_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `seos`
--

INSERT INTO `seos` (`id`, `language_id`, `meta_keyword_home`, `meta_description_home`, `meta_keyword_spaces`, `meta_description_spaces`, `meta_keyword_space_details`, `meta_description_space_details`, `meta_keyword_space_booking`, `meta_description_space_booking`, `meta_keyword_pricing`, `meta_description_pricing`, `vendor_page_meta_keywords`, `vendor_page_meta_description`, `shop_page_meta_keywords`, `shop_page_meta_description`, `cart_page_meta_keywords`, `cart_page_meta_description`, `meta_keyword_aboutus`, `meta_description_aboutus`, `meta_keyword_faq`, `meta_description_faq`, `created_at`, `updated_at`, `meta_description_term_and_condition`, `meta_description_checkout`, `meta_keyword_contact`, `meta_description_contact`, `meta_keyword_customer_login`, `meta_description_customer_login`, `meta_keyword_customer_signup`, `meta_description_customer_signup`, `meta_keyword_customer_forget_password`, `meta_description_customer_forget_password`, `meta_keyword_vendor_login`, `meta_description_vendor_login`, `meta_keyword_vendor_signup`, `meta_description_vendor_signup`, `meta_keyword_vendor_forget_password`, `meta_description_vendor_forget_password`, `shop_checkout_page_meta_keywords`, `shop_checkout_page_meta_description`, `meta_keyword_blog`, `meta_description_blog`, `meta_keyword_term_and_condition`, `meta_keyword_blog_post_details`, `meta_description_blog_post_details`, `vendor_details_page_meta_keywords`, `vendor_details_page_meta_description`, `product_details_page_meta_keywords`, `product_details_page_meta_description`) VALUES
(1, 51, 'event spaces, venue booking, party halls, wedding venues, corporate event locations, conference halls, rentable spaces, local venues', 'Discover the perfect event space for weddings, parties, corporate events, and more. Browse and book top venues effortlessly in your city', 'available spaces, event halls, conference rooms, party venues, wedding venues, rentable spaces, local event locations', 'Explore a wide range of event spaces for every occasion. Filter by type, location, and amenities to find the ideal venue.', NULL, NULL, 'book event space, venue reservation, hall booking, conference room booking, party hall reservation, wedding venue booking', 'Book your preferred event space quickly and easily. Select the date, time, and services for a seamless booking experience', 'event space pricing, venue rental rates, booking cost, hall rental fees, space hire charges', 'Check transparent pricing for event spaces. Compare rates and choose the venue that fits your budget.', 'event vendors, service providers, wedding vendors, party planners, venue suppliers, vendor listing', 'Connect with top event vendors and service providers. Explore profiles, services, and ratings to find the perfect match.', 'event products, party supplies, event essentials, decoration items, wedding accessories, booking add-ons', 'Browse a wide range of products and add-ons to enhance your event. Get high-quality items for weddings, parties, and corporate events', 'shopping cart, event product cart, checkout items, event booking cart, selected products', 'Review your selected products and services in your cart before proceeding to checkout. Ensure everything is correct.', 'about our company, event booking platform, venue services, company mission, event industr', 'Learn more about our mission to provide seamless event space booking and connect you with top vendors and venues.', 'frequently asked questions, booking help, venue questions, vendor queries, event booking suppor', 'Find answers to common questions about booking event spaces, vendors, payments, and other platform services.', '2025-08-24 13:28:01', '2025-08-24 13:28:01', 'Read the terms and conditions of using our platform, including booking policies, vendor agreements, and user responsibilities', NULL, 'contact us, customer support, event booking help, vendor support, get in touch', 'Reach out to our team for any questions or support related to event bookings, vendors, or platform services.', 'customer login, user account, event booking login, customer access, sign in', 'Log in to your account to manage your bookings, view vendors, and track your event reservations.', 'customer registration, create account, sign up, event platform account, new user', 'Sign up to create your account and start booking event spaces and connecting with vendors effortlessly.', 'customer password reset, recover account, forgotten password, account recovery', 'Reset your password to regain access to your account quickly and securely', 'vendor login, service provider access, vendor account sign in, manage profile', 'Log in to your vendor account to manage listings, view bookings, and update services.', 'vendor registration, create vendor account, event service sign up, vendor platform account', 'Register as a vendor to list your services, manage bookings, and connect with customers seeking event spaces.', 'vendor password reset, vendor account recovery, reset vendor login, forgotten vendor password', 'Reset your vendor account password quickly and securely to regain access to your account.', 'checkout, event booking payment, payment process, secure checkout, complete order', 'Complete your booking or product order securely. Enter your payment details and confirm your reservation.', 'event tips, party ideas, wedding planning, corporate events, venue advice, event inspiration', 'Read expert tips, inspiration, and guides on planning weddings, parties, and corporate events. Stay updated with the latest trends', 'terms and conditions, user agreement, booking policy, vendor terms, site rules', NULL, NULL, 'vendor profile, vendor services, vendor details, event service providers, vendor ratings, vendor contact', 'View detailed profiles of event vendors, including services, pricing, and customer reviews, to make an informed choice.', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `social_medias`
--

CREATE TABLE `social_medias` (
  `id` bigint UNSIGNED NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `serial_number` mediumint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `social_medias`
--

INSERT INTO `social_medias` (`id`, `icon`, `url`, `serial_number`, `created_at`, `updated_at`) VALUES
(1, 'fab fa-facebook-f', 'https://www.facebook.com/', 1, '2025-08-24 12:21:02', '2025-08-24 12:21:02'),
(2, 'fab fa-twitter', 'https://www.twitter.com/', 2, '2025-08-24 12:21:40', '2025-08-24 12:21:40'),
(3, 'fab fa-instagram', 'https://www.instagram.com/', 3, '2025-08-24 12:22:08', '2025-08-24 12:22:08'),
(4, 'fab fa-linkedin-in', 'https://www.linkedin.com/', 4, '2025-08-24 12:22:32', '2025-08-24 12:22:32'),
(5, 'fab fa-youtube', 'https://www.youtube.com/', 5, '2025-08-24 12:23:10', '2025-08-24 12:23:10'),
(6, 'fab fa-pinterest-p', 'https://www.pinterest.com/', 6, '2025-08-24 12:23:40', '2025-08-24 12:23:40');

-- --------------------------------------------------------

--
-- Table structure for table `spaces`
--

CREATE TABLE `spaces` (
  `id` bigint UNSIGNED NOT NULL,
  `seller_id` bigint UNSIGNED DEFAULT NULL,
  `thumbnail_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slider_images` json DEFAULT NULL,
  `space_size` int DEFAULT NULL,
  `max_guest` int DEFAULT NULL,
  `min_guest` int DEFAULT NULL,
  `space_rent` decimal(16,2) DEFAULT NULL,
  `is_featured` tinyint DEFAULT '0',
  `average_rating` decimal(8,2) DEFAULT '0.00',
  `latitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `space_status` tinyint DEFAULT NULL,
  `space_type` int DEFAULT NULL COMMENT '1=fixed timeslot rental; 2=hourly rental; 3 = multi day rental',
  `booking_status` tinyint DEFAULT NULL,
  `book_a_tour` tinyint DEFAULT NULL,
  `prepare_time` int DEFAULT NULL,
  `rent_per_hour` decimal(16,2) DEFAULT NULL,
  `price_per_day` decimal(16,2) DEFAULT NULL,
  `similar_space_quantity` int DEFAULT NULL,
  `opening_time` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `closing_time` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `use_slot_rent` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spaces`
--

INSERT INTO `spaces` (`id`, `seller_id`, `thumbnail_image`, `slider_images`, `space_size`, `max_guest`, `min_guest`, `space_rent`, `is_featured`, `average_rating`, `latitude`, `longitude`, `space_status`, `space_type`, `booking_status`, `book_a_tour`, `prepare_time`, `rent_per_hour`, `price_per_day`, `similar_space_quantity`, `opening_time`, `closing_time`, `address`, `use_slot_rent`, `created_at`, `updated_at`) VALUES
(1, 0, '68a5b030caacc.png', '[\"68a5b01314915.png\", \"68a5b01344198.png\", \"68a5b013477e1.png\", \"68a5b0137023b.png\", \"68a5b01373bda.png\"]', 1200, 500, 200, 1000.00, 0, 0.00, '34.0549', '-118.2426', 1, 1, 0, 0, NULL, NULL, NULL, 1, '20:24', '20:24', NULL, 0, '2025-08-17 10:31:11', '2025-08-28 14:24:02'),
(2, 0, '68a73690b20c3.png', '[\"68a5b1c063d9e.png\", \"68a5b1c066c44.png\", \"68a5b1c094a4f.png\", \"68a5b1c097271.png\"]', 200, 20, 10, NULL, 0, 0.00, '29.7601315', '-95.3693838', 1, 2, 0, 0, 30, 70.00, NULL, 2, '01:00', '00:00', NULL, 0, '2025-08-19 13:35:10', '2025-08-21 15:09:04'),
(3, 0, '68beec1198911.png', '[\"68beebe2c48ba.png\", \"68beebe2c593d.png\", \"68beebe2f08cf.png\"]', 200, 40, 30, NULL, 0, 0.00, '43.653226', '-79.3831843', 1, 3, 0, 0, NULL, NULL, 100.00, 2, '20:45', '20:45', NULL, 0, '2025-08-21 09:03:39', '2025-09-08 14:45:37'),
(4, 66, '68a80f60c6d73.png', '[\"68a80dc50f132.png\", \"68a80dc50fa08.png\"]', 1200, 200, 100, NULL, 1, 0.00, '48.4284207', '-123.3656444', 1, 1, 0, 0, NULL, NULL, NULL, 1, '12:34', '12:34', NULL, 1, '2025-08-22 06:34:08', '2025-09-09 13:42:25'),
(5, 66, '68a85d05c4671.png', '[\"68a85b8bbb1dc.png\", \"68a85b8bbb1d9.png\", \"68a85b8bf3a1a.png\", \"68a85b8bf3da6.png\"]', 200, 80, 40, NULL, 0, 0.00, '52.5364461', '13.3679805', 1, 2, 0, 0, 30, 100.00, NULL, 3, '06:00', '22:00', NULL, 0, '2025-08-22 12:05:25', '2025-08-22 12:05:25'),
(6, 66, '68a86d2b3173d.png', '[\"68a86df183084.png\", \"68a86e06cdf33.png\", \"68a86e74a80eb.png\", \"68a86e929e8d5.png\"]', 1400, 150, 100, NULL, 1, 0.00, '53.57915999999999', '9.8745558', 1, 3, 0, 0, NULL, NULL, 190.00, 3, '19:20', '19:20', NULL, 0, '2025-08-22 13:14:19', '2025-08-22 15:38:50'),
(7, 67, '68a885494f0b3.png', '[\"68a883e741cdf.png\", \"68a883e74aba8.png\", \"68a883e77f001.png\"]', 1800, 300, 200, NULL, 1, 0.00, '25.1949849', '55.2784141', 1, 2, 0, 0, 45, 200.00, NULL, 1, '06:00', '23:00', NULL, 0, '2025-08-22 14:57:13', '2025-09-09 13:21:03'),
(8, 67, '68a88e9f2ba56.png', '[\"68a88db98e9e3.png\", \"68a88db98ea3f.png\", \"68a88db9c9516.png\", \"68a88ddd87703.png\"]', 1000, 150, 100, 200.00, 1, 0.00, '25.0806744', '55.13979209999999', 1, 1, 0, 0, NULL, NULL, NULL, 1, '20:20', '20:20', NULL, 1, '2025-08-22 15:37:03', '2025-09-08 13:36:42'),
(9, 67, '68beed80e30f5.png', '[\"68beed5ab4cd1.png\", \"68beed5ab88a7.png\"]', 100, 48, 20, NULL, 0, 0.00, '25.2027912', '55.2413195', 1, 3, 0, 0, NULL, NULL, 130.00, 1, '20:51', '20:51', NULL, 0, '2025-08-23 08:36:43', '2025-09-08 14:51:49'),
(10, 68, '68beecfa91545.png', '[\"68beecd0b1fae.png\", \"68beecd4d6923.png\"]', 200, 20, 10, NULL, 1, 0.00, '28.7040592', '77.10249019999999', 1, 1, 0, 0, NULL, NULL, NULL, 1, '20:49', '20:49', NULL, 1, '2025-08-23 12:25:42', '2025-09-09 13:39:12'),
(11, 68, '68beec7ac74ee.png', '[\"68beec688f0b9.png\", \"68beec6891dc2.png\"]', 800, 19, 15, NULL, 1, 0.00, '18.9581934', '72.8320729', 1, 3, 0, 0, NULL, NULL, 250.00, 1, '20:47', '20:47', NULL, 0, '2025-08-23 13:52:04', '2025-09-09 13:39:28'),
(12, 68, '68a9d2a3eb8c7.png', '[\"68a9d163896c3.png\", \"68a9d1638af62.png\"]', 1000, 100, 50, NULL, 0, 0.00, '25.2027912', '55.2413195', 1, 2, 0, 0, 44, 70.00, NULL, 2, '06:00', '23:00', NULL, 0, '2025-08-23 14:39:31', '2025-08-29 10:11:23'),
(13, 0, '68a9db5bb2e81.png', '[\"68a9d9d666cb4.png\", \"68a9d9d66ffc0.png\", \"68a9d9d69a906.png\"]', 999, 150, 100, NULL, 0, 0.00, '48.4284207', '-123.3656444', 1, 2, 1, 0, 30, 100.00, NULL, 2, '06:00', '23:00', NULL, 0, '2025-08-23 15:16:43', '2025-08-28 11:17:22'),
(14, 67, '68aab58654f2f.png', '[\"68aab3ac4183c.png\", \"68aab3ac4a6dc.png\", \"68aab3ac6f7c9.png\"]', 1800, 200, 100, NULL, 1, 0.00, NULL, NULL, 1, 2, 0, 1, 48, 200.00, NULL, 1, '06:00', '23:00', NULL, 0, '2025-08-24 06:47:34', '2025-09-09 13:19:39');

-- --------------------------------------------------------

--
-- Table structure for table `space_amenities`
--

CREATE TABLE `space_amenities` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `space_amenities`
--

INSERT INTO `space_amenities` (`id`, `language_id`, `icon`, `name`, `serial_number`, `created_at`, `updated_at`) VALUES
(1, 51, 'fas fa-wifi', 'Free Wi-Fi', '1', '2025-08-17 08:45:07', '2025-08-20 15:10:19'),
(2, 53, 'fas fa-wifi', 'واي فاي مجاني', '1', '2025-08-17 10:28:52', '2025-08-20 15:11:00'),
(3, 51, 'fas fa-parking', 'Parking Available', '2', '2025-08-20 15:11:36', '2025-08-20 15:11:36'),
(4, 53, 'fas fa-parking', 'موقف سيارات متاح', '2', '2025-08-20 15:11:51', '2025-08-20 15:11:51'),
(5, 51, 'fas fa-snowflake', 'Air Conditioning', '3', '2025-08-20 15:12:29', '2025-08-20 15:12:29'),
(6, 53, 'fas fa-snowflake', 'تكييف', '3', '2025-08-20 15:12:43', '2025-08-20 15:12:43'),
(7, 51, 'fas fa-seedling', 'Outdoor Garden', '4', '2025-08-20 15:13:25', '2025-08-20 15:13:25'),
(8, 53, 'fas fa-seedling', 'حديقة خارجية', '4', '2025-08-20 15:13:42', '2025-08-20 15:13:42'),
(9, 51, 'fas fa-swimming-pool', 'Swimming Pool', '5', '2025-08-20 15:14:21', '2025-08-20 15:14:21'),
(10, 53, 'fas fa-swimming-pool', 'حمام السباحة', '5', '2025-08-20 15:14:52', '2025-08-20 15:14:52'),
(11, 51, 'fas fa-dumbbell', 'Fitness Center', '6', '2025-08-20 15:15:30', '2025-08-20 15:15:30'),
(12, 53, 'fas fa-dumbbell', 'مركز اللياقة البدنية', '6', '2025-08-20 15:16:00', '2025-08-20 15:16:41'),
(13, 51, 'fas fa-spa', 'Spa & Wellness', '7', '2025-08-20 15:17:06', '2025-08-20 15:17:06'),
(14, 53, 'fas fa-spa', 'المنتجع الصحي والعافية', '7', '2025-08-20 15:17:21', '2025-08-20 15:17:21'),
(15, 51, 'fab fa-playstation', 'Play Area', '8', '2025-08-20 15:18:31', '2025-08-20 15:18:31'),
(16, 53, 'fab fa-playstation', 'منطقة اللعب', '8', '2025-08-20 15:18:58', '2025-08-20 15:18:58'),
(17, 51, 'fas fa-door-closed', 'Private Dressing Room', '9', '2025-08-20 15:19:50', '2025-08-20 15:19:50'),
(18, 53, 'fas fa-door-closed', 'غرفة ملابس خاصة', '9', '2025-08-20 15:20:08', '2025-08-20 15:20:08'),
(19, 51, 'fas fa-music', 'Dance Floor', '10', '2025-08-20 15:20:47', '2025-08-20 15:20:47'),
(20, 53, 'fas fa-music', 'حلبة الرقص', '10', '2025-08-20 15:21:04', '2025-08-20 15:21:04');

-- --------------------------------------------------------

--
-- Table structure for table `space_bookings`
--

CREATE TABLE `space_bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `seller_id` bigint UNSIGNED DEFAULT NULL,
  `booking_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `space_id` bigint UNSIGNED DEFAULT NULL,
  `package_id` bigint UNSIGNED DEFAULT NULL,
  `seller_membership_id` bigint UNSIGNED DEFAULT NULL,
  `service_stage_info` json DEFAULT NULL,
  `other_service_info` json DEFAULT NULL,
  `sub_service_info` json DEFAULT NULL,
  `number_of_guest` int DEFAULT NULL,
  `service_total` decimal(16,2) DEFAULT NULL,
  `sub_total` decimal(16,2) DEFAULT NULL,
  `space_rent_price` decimal(16,2) DEFAULT NULL,
  `grand_total` decimal(16,2) DEFAULT NULL,
  `tax_percentage` decimal(12,2) DEFAULT NULL,
  `tax` decimal(12,2) DEFAULT NULL,
  `currency_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_text_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_type` tinyint DEFAULT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_slot_id` bigint DEFAULT NULL,
  `start_time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `end_time_without_interval` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'without prepare time ',
  `number_of_day` int DEFAULT NULL,
  `custom_hour` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_hour` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT ' included interval time',
  `booking_date` date DEFAULT NULL,
  `duration` int UNSIGNED DEFAULT NULL,
  `receipt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `invoice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `raise_status` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Invoice Raise Status (0: None, 1: Raised, 2: Completed, 3: Rejected)',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `booked_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conversation_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `space_categories`
--

CREATE TABLE `space_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `icon_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bg_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `category_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `serial_number` int DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `space_categories`
--

INSERT INTO `space_categories` (`id`, `language_id`, `icon_image`, `bg_image`, `icon`, `name`, `slug`, `status`, `category_description`, `serial_number`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 51, '68be8b40ac7c7.png', NULL, 'fas fa-ring', 'Wedding & Reception', 'wedding--reception', 1, 'Celebrate love and joy with beautifully planned weddings and memorable receptions', 1, 1, '2025-08-17 08:37:58', '2025-09-08 07:52:32'),
(2, 53, '68c2e88f2298e.png', NULL, 'fas fa-ring', 'حفل الزفاف والاستقبال', 'حفل-الزفاف-والاستقبال', 1, 'احتفل بالحب والفرح مع حفلات الزفاف المخططة بشكل جميل وحفلات الاستقبال التي لا تنسى', 1, 1, '2025-08-17 09:45:10', '2025-09-11 15:19:43'),
(3, 51, '68be8cb2d8311.png', NULL, 'fas fa-calendar-alt', 'Corporate Events', 'corporate-events', 1, 'Professional and polished gatherings for businesses, from meetings to galas', 2, 1, '2025-08-17 09:50:48', '2025-09-08 07:58:42'),
(4, 53, '68c2e8a003454.png', NULL, 'fas fa-calendar-alt', 'الفعاليات المؤسسية', 'الفعاليات-المؤسسية', 1, 'تجمعات احترافية ومصقولة للشركات، من الاجتماعات إلى الحفلات', 2, 1, '2025-08-17 09:52:24', '2025-09-11 15:20:00'),
(5, 51, '68be9019d4cfa.png', NULL, 'fas fa-birthday-cake', 'Birthday & Private Parties', 'birthday--private-parties', 1, 'Make every birthday or private party extraordinary. Our spaces and decoration services help create fun, memorable, and personalized celebrations for all ages', 3, 1, '2025-08-20 09:25:39', '2025-09-08 08:13:13'),
(6, 53, '68c2e8da75abf.png', NULL, 'fas fa-birthday-cake', 'أعياد الميلاد والحفلات الخاصة', 'أعياد-الميلاد-والحفلات-الخاصة', 1, 'اجعل كل عيد ميلاد أو حفلة خاصة استثنائية. تساعد أماكننا وخدمات الديكور على خلق احتفالات ممتعة، لا تُنسى، وشخصية لجميع الأعمار.', 3, 1, '2025-08-20 09:27:09', '2025-09-11 15:20:58'),
(7, 51, '68be9341d2bc7.png', NULL, 'fas fa-chalkboard-teacher', 'Conference & Seminars', 'conference--seminars', 1, 'Host impactful conferences and seminars with our modern venues and facilities, designed to provide a professional, comfortable, and engaging environment for participants', 4, 1, '2025-08-20 09:29:07', '2025-09-08 08:26:41'),
(8, 53, '68c2e8f8b0b0f.png', NULL, 'fas fa-chalkboard-teacher', 'المؤتمرات والندوات', 'المؤتمرات-والندوات', 1, 'استضف مؤتمرات وندوات مؤثرة باستخدام أماكننا الحديثة ومرافقنا المصممة لتوفير بيئة مهنية ومريحة وجاذبة للمشاركين.', 4, 1, '2025-08-20 09:30:31', '2025-09-11 15:21:28'),
(9, 51, '68be92baaedf4.png', NULL, 'fas fa-handshake', 'Exhibitions & Trade Shows', 'exhibitions--trade-shows', 1, 'Showcase products and services effectively. Our venues support exhibitions and trade shows with flexible layouts, ample space, and top-notch amenities', 5, 0, '2025-08-20 09:31:51', '2025-09-11 11:34:57'),
(10, 53, '68c2e92f4fa96.png', NULL, 'fas fa-handshake', 'المعارض والعروض التجارية', 'المعارض-والعروض-التجارية', 1, 'اعرض المنتجات والخدمات بشكل فعال. تدعم أماكننا المعارض والأسواق التجارية بتصاميم مرنة ومساحات واسعة ومرافق عالية الجودة', 5, 1, '2025-08-20 09:32:37', '2025-09-11 15:22:23'),
(11, 51, '68be91f4e1d41.png', NULL, 'fas fa-music', 'Concerts & Live Shows', 'concerts--live-shows', 1, 'Experience unforgettable live performances. Our venues provide excellent acoustics, stage setups, and audience-friendly spaces for concerts and entertainment events', 6, 1, '2025-08-20 09:33:47', '2025-09-08 08:21:08'),
(12, 53, '68c2e939498e7.png', NULL, 'fas fa-music', 'الحفلات الموسيقية والعروض الحية', 'الحفلات-الموسيقية-والعروض-الحية', 1, 'استمتع بعروض حية لا تُنسى. توفر أماكننا صوتيات ممتازة، وترتيبات مسرحية، ومساحات ملائمة للجمهور للحفلات والعروض الترفيهية', 6, 1, '2025-08-20 09:34:30', '2025-09-11 15:22:33'),
(13, 51, '68be8a2f9a4e5.png', NULL, 'fas fa-laptop-code', 'Workshops & Training', 'workshops--training', 1, 'Organize interactive workshops and training sessions in comfortable, well-equipped spaces that foster learning, collaboration, and professional development', 7, 1, '2025-08-20 09:36:07', '2025-09-08 07:47:59'),
(14, 53, '68c2e946e3882.png', NULL, 'fas fa-laptop-code', 'ورش العمل والتدريب', 'ورش-العمل-والتدريب', 1, 'احتفل بالتقاليد والإيمان باستخدام أماكن مناسبة للطقوس الدينية والفعاليات الثقافية، مع توفير بيئة محترمة ومرحبة لجميع الحضور', 7, 1, '2025-08-20 09:45:55', '2025-09-11 15:22:46'),
(15, 51, '68be916aeae90.png', NULL, 'fas fa-praying-hands', 'Religious & Cultural Events', 'religious--cultural-events', 1, 'Celebrate traditions and faith with appropriate spaces for religious ceremonies and cultural events, providing a respectful and welcoming environment for all attendees', 8, 0, '2025-08-20 09:47:22', '2025-09-11 11:34:45'),
(16, 53, '68c2e94ed4231.png', NULL, 'fas fa-praying-hands', 'الفعاليات الدينية والثقافية', 'الفعاليات-الدينية-والثقافية', 1, 'احتفل بالتقاليد والإيمان باستخدام أماكن مناسبة للطقوس الدينية والفعاليات الثقافية، مع توفير بيئة محترمة ومرحبة لجميع الحضور', 8, 1, '2025-08-20 09:48:31', '2025-09-11 15:22:54');

-- --------------------------------------------------------

--
-- Table structure for table `space_contents`
--

CREATE TABLE `space_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `space_id` bigint UNSIGNED DEFAULT NULL,
  `space_category_id` bigint DEFAULT NULL,
  `country_id` bigint DEFAULT NULL,
  `state_id` bigint DEFAULT NULL,
  `city_id` bigint DEFAULT NULL,
  `sub_category_id` bigint DEFAULT NULL,
  `get_quote_form_id` bigint UNSIGNED DEFAULT NULL,
  `tour_request_form_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `amenities` json DEFAULT NULL,
  `meta_keywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `space_contents`
--

INSERT INTO `space_contents` (`id`, `language_id`, `space_id`, `space_category_id`, `country_id`, `state_id`, `city_id`, `sub_category_id`, `get_quote_form_id`, `tour_request_form_id`, `title`, `slug`, `address`, `description`, `amenities`, `meta_keywords`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 51, 1, 1, 1, 1, 1, NULL, NULL, NULL, 'Royal Banquet Hall', 'royal-banquet-hall', '111 S Grand Ave, Los Angeles, CA 90012', '<p>Royal Banquet Hall is an exquisite venue designed to host unforgettable events, from grand weddings to elegant corporate gatherings. With its luxurious interiors, spacious layout, and state-of-the-art facilities, the hall offers a perfect blend of sophistication and comfort. Guests can enjoy beautifully decorated spaces, ambient lighting, and premium service, ensuring every occasion becomes a memorable experience. Whether you are planning a lavish celebration or an intimate gathering, Royal Banquet Hall provides a setting that exceeds expectations.</p>', '[\"1\"]', NULL, NULL, '2025-08-17 10:31:11', '2025-08-28 14:24:02'),
(2, 53, 1, 2, 2, 2, 2, NULL, NULL, NULL, 'قاعة الحفلات الملكية', 'قاعة-الحفلات-الملكية', '111 S Grand Ave, Los Angeles, CA 90012', '<p>قاعة الولائم الملكية هي مكان فاخر مصمم لاستضافة الأحداث التي لا تُنسى، بدءًا من حفلات الزفاف الكبرى إلى التجمعات الرسمية الأنيقة. بفضل الديكورات الفاخرة والمساحة الواسعة والمرافق الحديثة، توفر القاعة مزيجًا مثاليًا من الأناقة والراحة. يمكن للضيوف الاستمتاع بالمساحات المزينة بشكل جميل والإضاءة الهادئة والخدمة المتميزة، مما يضمن أن يصبح كل حدث تجربة لا تُنسى. سواء كنت تخطط لاحتفال فاخر أو لقاء حميم، توفر قاعة الولائم الملكية بيئة تتجاوز التوقعات.</p>', '[\"2\"]', NULL, NULL, '2025-08-17 10:31:11', '2025-08-28 14:24:02'),
(3, 51, 2, 1, 1, 3, 3, NULL, NULL, NULL, 'Garden View Resort', 'garden-view-resort', '901 Bagby St, Houston, TX 77002, USA', '<p>Garden View Resort is a hidden gem designed for those who seek the perfect combination of luxury, nature, and comfort. Nestled in a prime location surrounded by lush greenery and peaceful landscapes, it is an ideal choice for weddings, corporate events, family gatherings, and private celebrations. The resort offers not only a stunning view but also world-class facilities, ensuring that every event becomes a memorable experience.</p>\r\n<p>The resort is strategically located just a short drive away from the city center, making it convenient for both local and international guests. It is well-connected with highways, airports, and public transport facilities. Parking is available on-site, offering ease and comfort for visitors.</p>\r\n<p>Garden View Resort is surrounded by beautifully landscaped gardens, water fountains, and open-air seating areas that create a serene environment. Guests can enjoy breathtaking views of flowers, trees, and seasonal decorations that enhance the natural beauty of the venue.</p>\r\n<p>Guests who wish to stay overnight can enjoy beautifully decorated rooms and suites. Each room comes with modern facilities including air conditioning, free Wi-Fi, smart TVs, and private balconies overlooking the gardens. Premium suites include Jacuzzis and personalized services.</p>', '[\"1\", \"19\", \"3\"]', NULL, NULL, '2025-08-19 13:35:10', '2025-08-21 15:09:04'),
(4, 53, 2, 2, 2, 2, 2, NULL, NULL, NULL, 'منتجع جاردن فيو', 'منتجع-جاردن-فيو', '901 Bagby St, Houston, TX 77002, USA', '<p>منتجع جاردن فيو جوهرة خفية، مصممة لمن يبحثون عن مزيج مثالي من الفخامة والطبيعة والراحة. يتميز بموقعه المميز، محاطًا بمساحات خضراء غناء ومناظر طبيعية هادئة، مما يجعله خيارًا مثاليًا لحفلات الزفاف، وفعاليات الشركات، والتجمعات العائلية، والاحتفالات الخاصة. لا يوفر المنتجع إطلالة خلابة فحسب، بل يوفر أيضًا مرافق عالمية المستوى، مما يضمن أن تصبح كل مناسبة تجربة لا تُنسى.</p>\r\n<p>يتميز المنتجع بموقع استراتيجي على بُعد مسافة قصيرة بالسيارة من مركز المدينة، مما يجعله مناسبًا للضيوف المحليين والدوليين على حد سواء. وهو متصل جيدًا بالطرق السريعة والمطارات ووسائل النقل العام. تتوفر مواقف سيارات في الموقع، مما يوفر الراحة والسهولة للزوار.</p>\r\n<p>يحيط بمنتجع جاردن فيو حدائق خلابة، ونوافير مياه، ومناطق جلوس مفتوحة تخلق بيئة هادئة. يمكن للضيوف الاستمتاع بإطلالات خلابة على الزهور والأشجار والديكورات الموسمية التي تعزز الجمال الطبيعي للمكان.</p>\r\n<p>يمكن للضيوف الراغبين في المبيت الاستمتاع بغرف وأجنحة مزينة بشكل جميل. تحتوي كل غرفة على مرافق عصرية، بما في ذلك تكييف هواء، وخدمة واي فاي مجانية، وأجهزة تلفزيون ذكية، وشرفات خاصة مطلة على الحدائق. أما الأجنحة الفاخرة، فتشمل جاكوزي وخدمات شخصية.</p>', '[\"2\", \"20\", \"4\"]', NULL, NULL, '2025-08-19 13:35:10', '2025-08-21 15:09:04'),
(5, 51, 3, 5, 3, 11, 13, 1, NULL, NULL, 'Kids Fun Zone', 'kids-fun-zone', 'Toronto, ON, Canada', '<p class=\"ds-markdown-paragraph\">A World of Wonder and Laughter: Welcome to the Kids Fun Zone</p>\r\n<p class=\"ds-markdown-paragraph\">Step into a vibrant universe designed exclusively for children, where imagination knows no bounds and laughter fills the air. The Kids Fun Zone is more than just a play area; it is a meticulously crafted haven where adventure, creativity, and safe, active play converge to create unforgettable childhood memories.</p>\r\n<p class=\"ds-markdown-paragraph\">Upon entering, you are greeted by a burst of bright, cheerful colors and the infectious sound of joy. The zone is a multi-sensory paradise, featuring a diverse array of attractions to cater to every young explorer. For the energetic and adventurous, sprawling multi-level play structures await, complete with twisting slides, challenging climbing walls, ball pits that feel like oceans of soft spheres, and obstacle courses that test agility and spark friendly competition. Every surface is padded and every corner is supervised, ensuring that safety is paramount while fun remains the top priority.</p>\r\n<p class=\"ds-markdown-paragraph\">For the creative minds, dedicated corners offer a world of artistic exploration. Here, children can dive into arts and crafts, painting their masterpieces, building fantastical creations with blocks, or engaging in interactive digital learning games that educate while they entertain. This space is designed to nurture young talents and encourage cognitive development through play.</p>\r\n<p class=\"ds-markdown-paragraph\">Meanwhile, quieter nooks provide a perfect retreat for little ones who prefer calmer activities. They can curl up with a colorful book from the mini-library, engage in imaginative play in themed playhouses, or solve puzzles that challenge their growing minds. Parents can relax in comfortable seating areas with clear sightlines, enjoying a moment of peace while watching their children learn, socialize, and grow in confidence.</p>\r\n<p class=\"ds-markdown-paragraph\">The Kids Fun Zone is a community within itself—a place where friendships are forged over shared adventures and where children learn valuable social skills like cooperation, sharing, and turn-taking. It is a space where birthdays are celebrated with gusto, and every visit feels like a special occasion. More than just physical play, it is an environment that fosters holistic development, ensuring every child leaves with a smile, a sense of accomplishment, and a story to tell. Welcome to the ultimate destination for fun.</p>', '[\"3\", \"5\", \"7\", \"15\"]', NULL, NULL, '2025-08-21 09:03:39', '2025-09-08 14:45:37'),
(6, 53, 3, 6, 4, 12, 14, 2, NULL, NULL, 'منطقة المرح للأطفال', 'منطقة-المرح-للأطفال', 'Toronto, ON, Canada', '<p class=\"ds-markdown-paragraph\"><strong>عالم من المرح والعجائب: مرحباً بكم في منطقة مرح الأطفال</strong></p>\r\n<p class=\"ds-markdown-paragraph\">ادخلوا إلى عالم نابض بالحياة مُصمم خصيصاً للأطفال، حيث الخيال لا يعرف حدوداً ويملأ الضحك كل ركن. إن \"منطقة مرح الأطفال\" هي أكثر من مجرد مساحة للعب؛ فهي ملاذ مُصمم بدقة حيث تلتقي المغامرة والإبداع واللعب النشط الآمن لصنع ذكريات طفولة لا تُنسى.</p>\r\n<p class=\"ds-markdown-paragraph\">عند عبور الباب، تستقبلكم ألوان زاهية مبهجة وصوت فرحٍ معدٍ. المنطقة هي جنة متعددة الحواس، تضم مجموعة متنوعة من وسائل الجذب التي تلبي احتياجات كل مستكشف صغير. بالنسبة للصغار مفعمي الطاقة والمغامرين، تنتظرهم هياكل لعب متعددة المستويات تتزاحم بهازحليقات ملتوية، وجدران تسلق تتحدى المهارة، وبركات كرات ناعمة تشبه محيطات من البهجة، ومسارات عقبات تختبر الرشاقة وتشعل روح المنافسة الودية. كل سطح مغطى بمادة amortissement وكل زاوية تُراقب، مما يضمن أن السلامة هي الأولوية القصوى بينما يبقى المرح هو الهدف الأساسي.</p>\r\n<p class=\"ds-markdown-paragraph\">أما العقول المبدعة، فستجد أركاناً مخصصة لعالم من الاستكشاف الفني. هنا، يمكن للأطفال الغوص في عالم الفنون والحرف اليدوية، ورسم تحفهم الفنية، وبناء creations خيالية بالمكعبات، أو الانخراط في ألعاب رقمية تفاعلية تعلّمهم بينما تسليهم. هذا الفضاء مصمم لرعاية المواهب الصغيرة وتشجيع النمو المعرفي من خلال اللعب.</p>\r\n<p class=\"ds-markdown-paragraph\">في الوقت نفسه، توفر الزوايا الهادئة ملاذاً مثالياً للصغار الذين يفضلون الأنشطة الأكثر هدوءاً. يمكنهم الجلوس مع كتاب ملون من المكتبة المصغرة، أو الانخراط في لعب تخيلي في بيوت اللعب ذات الطابع الخاص، أو حل الألغاز التي تتحدى عقولهم النامية. يمكن للوالدين الاسترخاء في مناطق جلوس مريحة مع إطلالة واضحة، والاستمتاع بلحظة من الراحة أثناء مشاهدة أطفالهم وهم يتعلمون ويتواصلون socially وينمون ثقتهم بأنفسهم.</p>\r\n<p class=\"ds-markdown-paragraph\">إن منطقة مرح الأطفال هي مجتمع بحد ذاته—مكان تُبنى فيه الصداقات فوق مغامرات مشتركة ويتعلم الأطفال مهارات اجتماعية قيمة مثل التعاون والمشاركة وانتظار الدور. إنها مساحة حيث تُحتفل بأعياد الميلاد ببهجة، وتشعر كل زيارة وكأنها مناسبة خاصة. أكثر من مجرد لعب جسدي، فهي بيئة تعزز النمو الشامل، وتضمن أن كل طفل يغادر وهو مبتسم، وشاعر بالإنجاز، وعنده قصة ليحكيها. مرحباً بكم في الوجهة ultimate للمتعة.</p>', '[\"4\", \"6\", \"8\", \"16\"]', NULL, NULL, '2025-08-21 09:03:39', '2025-09-08 14:45:37'),
(7, 51, 4, 9, 3, 13, 15, NULL, NULL, NULL, 'Global Expo Hall', 'global-expo-hall', 'Victoria, BC, Canada', '<p class=\"ds-markdown-paragraph\"><strong>The Global Expo Hall: A Nexus of Innovation and Connection</strong></p>\r\n<p class=\"ds-markdown-paragraph\">A Global Expo Hall is more than just a vast, enclosed space; it is a dynamic crossroads where the future is shaped, connections are forged, and the world\'s innovations are put on dazzling display. It is a purpose-built architectural marvel designed to be the epicenter of commerce, culture, and technology on a global scale. Functioning as a neutral ground for nations and industries, it provides a powerful platform for exhibitors to launch groundbreaking products, for attendees to glimpse tomorrow\'s trends, and for delegates to negotiate deals that span continents.</p>\r\n<p class=\"ds-markdown-paragraph\">Stepping into a world-class Expo Hall is an experience in itself. The sheer scale is often the first thing that strikes you—a cavernous interior with soaring ceilings, often supported by graceful, weightless arches or dramatic tensile structures, designed to accommodate everything from life-sized machinery to multi-story pavilions. Natural light frequently floods the space through vast skylights or glass curtain walls, creating an open, airy atmosphere that belies the hall\'s immense size. This sense of grandeur is meticulously balanced with functional precision. The floor is a geometric grid of power outlets, data ports, and utility lines, hidden beneath a surface ready to be transformed into any configuration imaginable.</p>\r\n<p class=\"ds-markdown-paragraph\">The true magic of the Global Expo Hall lies in its chameleon-like ability to transform. One month, it might host a massive international auto show, with gleaming concept cars and electric vehicles under spotlights. The next, it could be reconfigured into a labyrinth of booths for a tech convention, buzzing with the energy of virtual reality demonstrations, robotics, and AI startups. It can become a temporary home for a world food festival, filled with aromatic spices and culinary delights, or a high-stakes environment for a medical conference where the latest surgical equipment is unveiled.</p>\r\n<p class=\"ds-markdown-paragraph\">This versatility is made possible by state-of-the-art infrastructure. Advanced lighting and sound systems can create any ambiance, from the quiet solemnity of an art exhibition to the pulsating excitement of a product launch concert. High-speed Wi-Fi blankets every corner, ensuring real-time social media updates and seamless digital interaction. Logistics are a masterpiece of planning, with massive loading docks, spacious storage areas, and sophisticated climate control systems that ensure perfect conditions for both sensitive electronics and human comfort.</p>\r\n<p class=\"ds-markdown-paragraph\">Beyond the brick and mortar, the Expo Hall is a potent economic engine. It acts as a catalyst for local businesses, filling hotels, restaurants, and transportation networks. It generates employment and positions the host city firmly on the international map as a hub of trade and innovation. For attendees, it is an unparalleled opportunity for professional development and networking, a place to meet mentors, competitors, and future partners face-to-face in an era increasingly dominated by digital communication.</p>\r\n<p class=\"ds-markdown-paragraph\">Ultimately, a Global Expo Hall is a theater of human achievement. It is a microcosm of the global marketplace, a physical manifestation of our collective drive to create, share, and progress. It is where a handshake between two entrepreneurs from different hemispheres can spark an idea that changes an industry, and where the public can touch, feel, and experience the future. It is not merely a building; it is a beacon of possibility, a permanent venue for temporary worlds that, together, chart the course of our shared tomorrow.</p>', '[\"3\", \"5\"]', NULL, NULL, '2025-08-22 06:34:08', '2025-08-22 06:34:08'),
(8, 53, 4, 10, 4, 14, 16, NULL, NULL, NULL, 'قاعة المعرض العالمي', 'قاعة-المعرض-العالمي', 'Victoria, BC, Canada', '<p class=\"ds-markdown-paragraph\"><strong>قاعة المعارض العالمية: ملتقى الابتكار والاتصال</strong></p>\r\n<p class=\"ds-markdown-paragraph\">ليست قاعة المعارض العالمية مجرد مساحة مغلقة شاسعة؛ بل هي ملتقى ديناميكي حيث يُصاغ المستقبل، وتُ forged العلاقات، وتعرض ابتكارات العالم في أبهى حُللها. إنها معجزة معمارية مُصممة خصيصاً لتكون بؤرةً مركزية للتجارة والثقافة والتكنولوجيا على نطاق عالمي. تؤدي هذه القاعة دور الأرض المحايدة للأم nations والصناعات، وتوفر منصة قوية للمعارضين لإطلاق منتجاتهم الثورية، وللزوار لالتقاط لمحات عن trends المستقبل، وللمندوبين للتفاوض على صفقات تمتد عبر القارات.</p>\r\n<p class=\"ds-markdown-paragraph\">الدخول إلى قاعة معارض عالمية المستوى هو تجربة بحد ذاتها. أول ما يلفت انتباهك هو الضخامة المطلقة للمساحة—داخلية شاسعة بأسقف مرتفعة، غالباً ما يدعمها أقواس رشيقة أو هياكل إنشائية دراماتيكية، مصممة لاستيعاب everything من الآلات بحجمها الطبيعي إلى الأجنبة متعددة الطوابق. كثيراً ما يغمر الضوء الطبيعي الفضاء من خلال نوافذ سقفية ضخمة أو جدران ستائرية زجاجية، مما يخلق أجواءً مفتوحة وواسعة تناقض الحجم الهائل للقاعة. هذا الإحساس بالضخامة متوازن بدقة مع الدقة الوظيفية. الأرضية هي شبكة هندسية من منافذ الكهرباء ومنافذ البيانات وخطوط المرافق، مخبأة beneath سطح جاهز للتحول إلى أي تكوين يمكن تخيله.</p>\r\n<p class=\"ds-markdown-paragraph\">يكمن السحر الحقيقي للقاعة العالمية في قدرتها التشبهية على التحول. في شهر، قد تستضيف معرضاً دولياً ضخماً للسيارات، حيث تلمع السيارات概念ية والمركبات الكهربائية under الأضواء الكاشفة. وفي الشهر التالي، يمكن إعادة تهيئتها إلى متاهة من الأكشاك ل convención تقنية، تغص بحيوية عروض الواقع الافتراضي والروبوتات وشركات التكنولوجيا الناشئة. يمكن أن تصبح موطناً مؤقتاً لمهرجان عالمي للطعام، مليء بالتوابل العطرية和大快朵颐， أو بيئة عالية المخاطر لمؤتمر طبي حيث يتم الكشف عن أحدث المعدات الجراحية.</p>\r\n<p class=\"ds-markdown-paragraph\">هذه المرونة أصبحت ممكنة بفضل بنية تحتية متطورة. يمكن لأنظمة الإضاءة والصوت المتقدمة خلق أي أجواء، من الهدوء الوقور لمعرض فني إلى الإثارة النابضة لحفل إطلاق منتج. تغطي شبكة Wi-Fi فائقة السرعة every ركن، ensuring تحديثات وسائل التواصل الاجتماعي في الوقت الفعلي والتفاعل الرقمي السلس. الخدمات اللوجستية هي تحفة من التخطيط، بأرصفة تحميل ضخمة، ومناطق تخزين واسعة، وأنظمة تحكم مناخي متطورة ensuring ظروف مثالية لكل من الإلكترونيات الحساسة وراحة الإنسان.</p>\r\n<p class=\"ds-markdown-paragraph\">ما وراء الطوب والملاط، تعد قاعة المعارض محركاً اقتصادياً قوياً. فهي تعمل كعامل حفاز للشركات المحلية، ممتلئةً الفنادق والمطاعم وشبكات النقل. إنها تولد employment وتضع المدينة المضيفة firmly على الخريطة الدولية كمركز للتجارة والابتكار. بالنسبة للحضور، فهي فرصة لا مثيل لها للتطوير المهني والتواصل، ومكان للقاء الموجهين والمنافسين والشركاء المستقبليين وجهاً لوجه في عصر يهيمن عليه التواصل الرقمي بشكل متزايد.</p>\r\n<p class=\"ds-markdown-paragraph\">في النهاية، فإن قاعة المعارض العالمية هي مسرح للإنجاز البشري. إنها صورة مصغرة للسوق العالمية، ومظهر مادي لدفعنا الجماعي للابتكار والمشاركة والتقدم. إنها المكان حيث يمكن لمصافحة entre رجلَي أعمال من نصفَي الكرة الأرضية المختلفين أن يشعلوا فكرة تغير صناعة بأكملها، وحيث يمكن للجمهور أن يلمس ويشعر ويختبر المستقبل. إنها ليست مجرد مبنى؛ بل هي منارة للإمكانيات، وموقع دائم لعوالم مؤقتة ترسم معاً مسار غدنا المشترك.</p>', '[\"4\", \"6\"]', NULL, NULL, '2025-08-22 06:34:08', '2025-08-22 06:34:08'),
(9, 51, 5, 15, 9, 17, 24, NULL, NULL, NULL, 'Spiritual Gathering Hall', 'spiritual-gathering-hall', 'Berlin Mitte, Berlin, Germany', '<p>A Spiritual Gathering Hall is a sanctuary designed for collective peace and inner reflection. It is more than just a physical space; it is a vessel for shared energy, contemplation, and connection with the divine. The architecture often emphasizes serenity, with high ceilings that inspire awe, soft, natural lighting that filters through stained glass or intricate lattices, and an open, uncluttered floor to encourage a sense of unity and equality among all attendees.</p>\r\n<p>The air is typically still and fragrant, perhaps with the subtle scent of sandalwood, frankincense, or fresh flowers, aiding in calming the mind. The acoustics are carefully considered, allowing for the resonant echo of sacred hymns, silent prayers, or words of wisdom to linger, touching every heart present. Here, individuals from all walks of life come together to transcend their daily concerns, meditate, chant, or simply sit in silent communion. The primary purpose is to facilitate a journey inward, to nurture the soul, and to strengthen the bonds of community through a shared pursuit of spiritual growth and inner peace. It is a haven where the noise of the outside world fades, allowing the gentle voice of the spirit to be heard.</p>', '[\"1\", \"3\", \"5\", \"17\"]', NULL, NULL, '2025-08-22 12:05:25', '2025-08-22 12:05:25'),
(10, 53, 5, 16, 10, 18, 25, NULL, NULL, NULL, 'قاعة التجمع الروحي', 'قاعة-التجمع-الروحي', 'Berlin Mitte, Berlin, Germany', '<p>قاعة التجمع الروحي ملاذٌ مُصممٌ للسلام الجماعي والتأمل الداخلي. إنها أكثر من مجرد مساحة مادية؛ إنها وعاءٌ للطاقة المشتركة والتأمل والتواصل مع الله. غالبًا ما يُبرز تصميمها المعماري السكينة، بأسقفها العالية التي تُثير الإعجاب، وإضاءة طبيعية ناعمة تتسلل عبر الزجاج الملون أو الشبكات المُعقدة، وأرضية مفتوحة ومرتبة تُعزز الشعور بالوحدة والمساواة بين جميع الحضور.</p>\r\n<p>عادةً ما يكون الهواء ساكنًا وعبقًا، ربما برائحة خفيفة من خشب الصندل أو اللبان أو الزهور النضرة، مما يُساعد على تهدئة العقل. صُممت الصوتيات بعناية فائقة، مما يسمح بصدى الترانيم المقدسة، والصلوات الصامتة، أو كلمات الحكمة، بأن يتردد صداه، مُلامسًا كل قلب حاضر. هنا، يجتمع أفراد من جميع مناحي الحياة لتجاوز همومهم اليومية، والتأمل، والترانيم، أو حتى الجلوس في صمت. الهدف الأساسي هو تسهيل رحلة نحو الذات، ورعاية الروح، وتقوية روابط المجتمع من خلال السعي المشترك نحو النمو الروحي والسلام الداخلي. إنه ملاذ يتلاشى فيه ضجيج العالم الخارجي، ليُسمع صوت الروح الرقيق.</p>', '[\"2\", \"4\", \"6\", \"18\"]', NULL, NULL, '2025-08-22 12:05:25', '2025-08-22 12:05:25'),
(11, 51, 6, 13, 9, 19, 26, NULL, NULL, NULL, 'Skill Development Center', 'skill-development-center', 'Altona, Germany', '<p class=\"ds-markdown-paragraph\">A Skill Development Center is a dynamic hub dedicated to empowering individuals by bridging the gap between academic knowledge and real-world professional requirements. It serves as a catalyst for personal and career growth, offering a wide array of targeted training programs, workshops, and certifications across diverse fields such as digital literacy, communication, technical trades, management, and entrepreneurship.</p>\r\n<p class=\"ds-markdown-paragraph\">The core mission of such a center is to foster a culture of continuous learning and adaptability. By utilizing a practical, hands-on approach often led by industry experts, it ensures learners acquire not just theoretical understanding but also the applicable competencies demanded by today\'s competitive job market. The curriculum is frequently tailored to meet evolving industry trends, making participants more employable and capable of driving innovation.</p>\r\n<p class=\"ds-markdown-paragraph\">Beyond hard skills, the center focuses on cultivating essential soft skills like critical thinking, problem-solving, teamwork, and leadership. This holistic development builds confidence and prepares individuals to excel in their chosen paths. Ultimately, a Skill Development Center is more than just a training facility; it is an investment in human capital, building a skilled, future-ready workforce that contributes to both individual prosperity and the broader economic development of the community.</p>', '[\"1\", \"3\", \"5\", \"11\", \"15\", \"17\"]', NULL, NULL, '2025-08-22 13:14:19', '2025-08-22 13:20:21'),
(12, 53, 6, 14, 10, 20, 27, NULL, NULL, NULL, 'مركز تنمية المهارات', 'مركز-تنمية-المهارات', 'Altona, Germany', '<p class=\"ds-markdown-paragraph\">مركز تطوير المهارات هو محور ديناميكي مخصص لتمكين الأفراد من خلال سد الفجوة بين المعرفة الأكاديمية ومتطلبات العالم المهني الحقيقي. يعمل المركز كحافز للنمو الشخصي والمهني، حيث يقدم مجموعة واسعة من البرامج التدريبية المستهدفة وورش العمل والشهادات في مجالات متنوعة مثل المحو الرقمي والاتصالات والحرف التقنية والإدارة وريادة الأعمال.</p>\r\n<p class=\"ds-markdown-paragraph\">المهمة الأساسية للمركز هي تعزيز ثقافة التعلم المستمر والتكيف. من خلال استخدام نهج عملي تطبيقي غالبًا ما يقوده خبراء في المجال، يضمن المركز أن يكتسب المتعلمون ليس فقط الفهم النظري ولكن أيضًا الكفاءات التطبيقية التي يتطلبها سوق العمل التنافسي اليوم. يتم تخصيص المناهج بشكل متكرر لتلبية الاتجاهات الصناعية المتطورة، مما يجعل المشاركين أكثر قابلية للتوظيف وقدرة على دفع عجلة الابتكار.</p>\r\n<p class=\"ds-markdown-paragraph\">إلى جانب المهارات التقنية، يركز المركز على تنمية المهارات الناعمة الأساسية مثل التفكير النقدي وحل المشكلات والعمل الجماعي والقيادة. هذا التطور الشامل يبني الثقة ويجهز الأفراد لتحقيق التميز في مساراتهم التي يختارونها. في النهاية، مركز تطوير المهارات هو أكثر من مجرد منشأة تدريبية؛ إنه استثمار في رأس المال البشري، يبني قوى عاملة ماهرة وجاهزة للمستقبل تساهم في ازدهار الفرد والتنمية الاقتصادية الأوسع للمجتمع.</p>', '[\"2\", \"4\", \"6\", \"12\", \"16\", \"18\"]', NULL, NULL, '2025-08-22 13:14:19', '2025-08-22 13:20:21'),
(13, 51, 7, 1, 5, NULL, 7, 3, NULL, NULL, 'Paradise Wedding Palace', 'paradise-wedding-palace', 'Downtown Dubai - Dubai - United Arab Emirates', '<p class=\"ds-markdown-paragraph\">Welcome to Paradise Wedding Palace, where dreams are woven into reality and every moment is a brushstroke on the canvas of forever. Nestled in a realm of breathtaking elegance, our palace is more than a venue; it is the embodiment of a fairy tale.</p>\r\n<p class=\"ds-markdown-paragraph\">Step into our grand ballroom, a majestic space adorned with crystal chandeliers that rain light upon polished marble floors. Soaring ceilings and floor-to-ceiling windows offer a stunning backdrop, whether overlooking manicured gardens or a glittering city skyline. Our expert design team works tirelessly to transform this blank canvas into your personal vision, be it a classic opulent affair or a modern minimalist celebration.</p>\r\n<p class=\"ds-markdown-paragraph\">Beyond the ballroom, discover serene bridal suites for preparation, lush gardens for intimate ceremonies, and state-of-the-art facilities to ensure flawless execution. Our dedicated wedding specialists are with you at every step, orchestrating impeccable service, gourmet catering, and meticulous attention to detail.</p>\r\n<p class=\"ds-markdown-paragraph\">At Paradise Wedding Palace, we believe your wedding day should be nothing short of perfect. We invite you to begin your journey of everlasting love in a place where magic is real, and paradise is found</p>', '[\"19\", \"3\", \"7\", \"9\", \"13\", \"15\", \"17\"]', NULL, NULL, '2025-08-22 14:57:13', '2025-08-22 15:28:55'),
(14, 53, 7, 2, 6, NULL, 9, 4, NULL, NULL, 'قصر الزفاف الجنة', 'قصر-الزفاف-الجنة', 'Downtown Dubai - Dubai - United Arab Emirates', '<p class=\"ds-markdown-paragraph\">مرحبًا بكم في قصر زفاف \"بارادايس\"، حيث تُحاك الأحلام لتصبح واقعًا ملموسًا، وكل لحظة هي لمسة على لوحة الخلود. يتجسد في قصرنا الفخم، ذي الأناقة التي تأخذ بالألباب، أكثر من مجرد مكان للاحتفال؛ فهو تجسيد للحكاية الخيالية.</p>\r\n<p class=\"ds-markdown-paragraph\">ستخطو إلى قاعة رحبة مهيبة، تزينها ثريات كريستالية تُمطِرُ الضوء على أرضيات رخامية مصقولة. بينما تمنحك الأسقف العالية والنوافذ الممتدة من الأرض إلى السقف إطلالات خلابة على حدائق غنّاء أو أفق المدينة المتلألئ. يعمل فريق التصميم الخبير لدينا بلا كلل لتحويل هذه المساحة إلى رؤيتك الشخصية، سواءً كانت حفلة تقليدية فاخرة أو احتفالاً عصريًا minimalistic.</p>\r\n<p class=\"ds-markdown-paragraph\">وراء القاعة الرئيسية، ستكتشف غرف عرائسية راقية للاستعداد، وحدائق غنّاء للطقوس الحميمة، وأحدث المرافق التي تضمن تنفيذًا لا تشوبه شائبة. كما يظلك مختصو الأفراح المتفانون بخطوة، لتنظيم خدمة لا تعرف الخطأ، وولائم طهاة عالميين، واهتمامًا دقيقًا بأدق التفاصيل.</p>\r\n<p class=\"ds-markdown-paragraph\">في قصر زفاف \"بارادايس\"، نؤمن بأن يوم زفافك يجب أن يكون لا شيء سوى الكمال. ندعوكم لتبدؤوا رحلة حبكم الأبدية في مكانٍ حيثُ السحر حقيقي، والجنة موجودة</p>', '[\"20\", \"4\", \"8\", \"10\", \"16\", \"18\"]', NULL, NULL, '2025-08-22 14:57:13', '2025-08-22 15:28:55'),
(15, 51, 8, 3, 5, NULL, 8, NULL, NULL, NULL, 'Skyline Conference Center', 'skyline-conference-center', 'Dubai Marina - Dubai - United Arab Emirates', '<p class=\"ds-markdown-paragraph\">Nestled in the heart of the city, the Skyline Conference Center stands as a premier destination for business and prestige events. Designed with a visionary architectural approach, its stunning glass façade reflects the dynamic energy of the urban landscape while offering breathtaking panoramic views.</p>\r\n<p class=\"ds-markdown-paragraph\">Inside, the center boasts a versatile array of state-of-the-art facilities. From expansive, column-free ballrooms capable of hosting grand galas for over a thousand guests to smaller, modular meeting rooms designed for focused executive seminars, every space is equipped with cutting-edge technology. High-speed internet, advanced audio-visual systems, and seamless video conferencing capabilities are standard, ensuring every presentation is delivered with impact.</p>\r\n<p class=\"ds-markdown-paragraph\">Beyond the meeting rooms, the Skyline offers meticulously curated catering services, featuring world-class cuisine prepared by expert chefs. Our dedicated event coordination team provides end-to-end support, meticulously planning every detail to ensure flawless execution from conception to completion.</p>\r\n<p class=\"ds-markdown-paragraph\">Whether you are organizing an international conference, a product launch, a formal wedding, or a corporate retreat, the Skyline Conference Center provides a sophisticated and professional environment. It is more than just a venue; it is a partner in your success, committed to creating exceptional and memorable experiences</p>', '[\"1\", \"3\", \"5\", \"17\"]', NULL, NULL, '2025-08-22 15:37:03', '2025-08-28 14:20:32'),
(16, 53, 8, 4, 6, NULL, 10, NULL, NULL, NULL, 'مركز مؤتمرات سكاي لاين', 'مركز-مؤتمرات-سكاي-لاين', 'Dubai Marina - Dubai - United Arab Emirates', '<p>يقع مركز سكاي لاين للمؤتمرات في قلب المدينة، ويُعد وجهةً رائدةً للأعمال والفعاليات المرموقة. صُمم بمنهج معماري ثاقب، حيث تعكس واجهته الزجاجية الخلابة حيوية المشهد الحضري، مع إطلالات بانورامية خلابة.</p>\r\n<p>يضم المركز في الداخل مجموعةً متنوعةً من المرافق الحديثة والمتطورة. بدءًا من قاعات الرقص الفسيحة الخالية من الأعمدة القادرة على استضافة حفلات عشاء فاخرة لأكثر من ألف ضيف، وصولًا إلى قاعات الاجتماعات الصغيرة والمتكاملة المصممة للندوات التنفيذية المتخصصة، كل مساحة مجهزة بأحدث التقنيات. الإنترنت عالي السرعة، والأنظمة السمعية والبصرية المتطورة، وإمكانيات مؤتمرات الفيديو السلسة هي معايير أساسية، مما يضمن تقديم كل عرض تقديمي بفعالية.</p>\r\n<p>إلى جانب قاعات الاجتماعات، يقدم سكاي لاين خدمات تموين مُعدّة بعناية فائقة، تضم مأكولات عالمية المستوى من إعداد طهاة خبراء. يقدم فريقنا المتخصص في تنسيق الفعاليات دعمًا شاملًا، ويخطط بدقة لكل تفصيل لضمان تنفيذ مثالي من الفكرة إلى الإنجاز.</p>\r\n<p>سواءً كنت تُنظّم مؤتمرًا دوليًا، أو حفل إطلاق منتج، أو حفل زفاف رسمي، أو ملتقىً للشركات، فإن مركز مؤتمرات سكاي لاين يُوفّر بيئةً راقيةً واحترافية. إنه أكثر من مجرد مكان؛ إنه شريكٌ في نجاحك، ملتزمٌ بتقديم تجارب استثنائية لا تُنسى.</p>', '[\"2\", \"4\", \"6\", \"18\"]', NULL, NULL, '2025-08-22 15:37:03', '2025-08-28 14:20:32'),
(17, 51, 9, 5, 5, NULL, 11, 5, NULL, NULL, 'Happy Moments Hall', 'happy-moments-hall', 'Jumeirah - Dubai - United Arab Emirates', '<p class=\"ds-markdown-paragraph\">A bachelor or bachelorette party is a celebratory rite of passage held for someone shortly before their wedding. Traditionally thrown by the maid of honor and best man, along with the wedding party and close friends, it’s a final celebration of the individual\'s single life and an opportunity to honor their upcoming marriage.</p>\r\n<p class=\"ds-markdown-paragraph\">These events have evolved far beyond a simple night out. While some parties are still known for wild nights filled with bar-hopping and humorous gifts, modern celebrations are incredibly diverse. They can range from a relaxed weekend getaway at a beach or cabin to an adventurous trip involving activities like hiking, wine tasting, or spa days. The core purpose remains the same: to create lasting memories with your closest friends, share stories, and offer support and well-wishes for the new chapter ahead. Ultimately, it’s a heartfelt and fun tribute to friendship and the journey into marriage</p>', '[\"1\", \"19\", \"3\", \"9\", \"15\", \"17\"]', NULL, NULL, '2025-08-23 08:36:43', '2025-09-08 14:51:44'),
(18, 53, 9, 6, 6, NULL, 12, 6, NULL, NULL, 'قاعة اللحظات السعيدة', 'قاعة-اللحظات-السعيدة', 'Jumeirah - Dubai - United Arab Emirates', '<p class=\"ds-markdown-paragraph\">حفلة العزابية (للعرسان) أو العزباية (للعروس) هي احتفالية تقليدية تُقام للشخص قبل زفافه مباشرة. تقام عادةً من قبل أفضل الأصدقاء وطاقم الزفاف، وهي احتفال أخير بمرحلة العزوبية وتقدير للصداقة قبل بدء فصل جديد من الحياة.</p>\r\n<p class=\"ds-markdown-paragraph\">لقد تطورت هذه الاحتفالات بشكل كبير لتتجاوز فكرة الخروج لليلة واحدة. بينما لا تزال بعض الحفلات تركز على الخروج للمطاعم والحانات، أصبحت الاحتفالات الحديثة متنوعة للغاية. فقد تكون رحلة استرخاء في منتجع ساحلي، أو عطلة نهاية أسبوع في مكان هادئ، أو حتى مغامرة تشمل أنشطة مثل التخييم أو تناول الطعام الفاخر أو يوم كامل في منتجع صحي. الهدف الأساسي يبقى هو نفسه: خلق ذكريات دائمة مع الأصدقاء المقربين، وتبادل القصص، وتقديم الدعم والتمنيات الطيبة للفصل القادم. في النهاية، هي احتفال مرح وخلاصة للصداقة والرحلة نحو الزواج</p>', '[\"2\", \"20\", \"4\", \"10\", \"16\", \"18\"]', NULL, NULL, '2025-08-23 08:36:43', '2025-09-08 14:51:44'),
(19, 51, 10, 13, 7, NULL, 21, NULL, NULL, NULL, 'Learning Lab', 'learning-lab', 'Delhi, India', '<p class=\"ds-markdown-paragraph\">A Learning Lab is a dynamic, technology-enhanced environment designed to move beyond traditional classroom instruction. It is a dedicated space where experimentation, collaboration, and hands-on learning take center stage. Equipped with cutting-edge tools like 3D printers, robotics kits, VR systems, and high-end computers, the lab provides students with the resources to turn theoretical concepts into tangible projects.</p>\r\n<p class=\"ds-markdown-paragraph\">The core philosophy of a Learning Lab is student-centered, inquiry-based learning. Instead of passively receiving information, learners become active creators, problem-solvers, and innovators. They work individually or in teams on real-world challenges, fostering critical 21st-century skills such as creativity, critical thinking, communication, and collaboration. The lab often functions as a hub for interdisciplinary projects, blending subjects like science, technology, engineering, arts, and mathematics (STEAM). Facilitated by educators who act as guides, the Learning Lab cultivates a culture of curiosity and resilience, empowering students to learn from failure and iterate on their ideas. It is more than a room; it is an incubator for future-ready skills and a mindset of lifelong learning</p>', '[\"1\", \"5\", \"17\"]', NULL, NULL, '2025-08-23 12:25:42', '2025-09-08 14:49:30'),
(20, 53, 10, 14, 8, NULL, 22, NULL, NULL, NULL, 'مختبر التعلم', 'مختبر-التعلم', 'Delhi, India', '<p class=\"ds-markdown-paragraph\"><strong>ختبر التعلّم</strong> هو بيئة ديناميكية معززة بالتكنولوجيا، مُصممة لتتجاوز أساليب التعليم التقليدية. إنه مساحة مخصصة تحتضن التجريب والتعاون والتعلّم العملي. ومجهز بأحدث الأدوات مثل الطابعات ثلاثية الأبعاد ومعدات الروبوتات وأنظمة الواقع الافتراضي وأجهزة الكمبيوتر المتطورة، ليوفّر للطلاب الموارد اللازمة لتحويل المفاهيم النظرية إلى مشاريع ملموسة.</p>\r\n<p class=\"ds-markdown-paragraph\">الفلسفة الأساسية لمختبر التعلّم تتمحور حول الطالب والقائمة على التعلّم الاستقصائي. بدلاً من تلقي المعلومات بشكل سلبي، يصبح المتعلمون مبدعين نشطين وحلالًا للمشاكل ومبتكرين. يعملون بشكل فردي أو ضمن فرق على مواجهة تحديات من العالم الحقيقي، مما يعزز مهارات القرن الحادي والعشرين الأساسية مثل الإبداع والتفكير النقدي والتواصل والعمل الجماعي. غالبًا ما يعمل المختبر كمركز للمشاريع متعددة التخصصات، دامجًا بين مواد مثل العلوم والتكنولوجيا والهندسة والفون والرياضيات (STEAM). بتيسير من educators يقومون بدور المرشدين، يغرس مختبر التعلّم ثقافة الفضول والمرونة، مما يمكن الطلاب من التعلّم من الفشل وتطوير أفكارهم بشكل متكرر. إنه أكثر من مجرد غرفة؛ إنه حاضنة لمهارات المستقبل وعقلية التعلّم مدى الحياة</p>', '[\"2\", \"6\", \"18\"]', NULL, NULL, '2025-08-23 12:25:42', '2025-09-08 14:49:30'),
(21, 51, 11, 13, 7, NULL, 23, NULL, NULL, NULL, 'Coworking Space', 'coworking-space', 'Mumbai, Maharashtra, India', '<p class=\"ds-markdown-paragraph\">A coworking space is a modern, shared work environment designed for professionals from diverse backgrounds. It is the ideal solution for freelancers, remote employees, entrepreneurs, and small startups seeking an alternative to the isolation of home offices or the high cost of traditional leases.</p>\r\n<p class=\"ds-markdown-paragraph\">More than just a desk, these spaces offer flexible membership options, from hot desks available by the day to dedicated private offices. They are equipped with high-speed internet, meeting rooms, printing facilities, and comfortable common areas. The true value of a coworking space, however, lies in its vibrant community. It provides a dynamic atmosphere for networking, collaboration, and sparking new ideas through casual interactions and organized events.</p>\r\n<p class=\"ds-markdown-paragraph\">By blending the structure of an office with the freedom and flexibility of remote work, coworking spaces foster productivity, creativity, and professional growth, making them the future of work</p>', '[\"1\", \"5\", \"11\", \"15\", \"17\"]', NULL, NULL, '2025-08-23 13:52:04', '2025-09-08 14:47:22'),
(22, 53, 11, 14, 8, NULL, 20, NULL, NULL, NULL, 'مساحة العمل المشتركة', 'مساحة-العمل-المشتركة', 'Mumbai, Maharashtra, India', '<p class=\"ds-markdown-paragraph\">مساحة العمل المشتركة هي بيئة عمل عصرية ومشتركة مصممة للمحترفين من مختلف المجالات. إنها الحل الأمثل للعاملين لحسابهم الخاص، والموظفين عن بُعد، ورجال الأعمال، والشركات الناشئة الصغيرة الذين يبحثون عن بديل عن عزلة المكاتب المنزلية أو التكلفة المرتفعة للمساحات التقليدية.</p>\r\n<p class=\"ds-markdown-paragraph\">أكثر من مجرد مكتب، تقدم هذه المساحات خيارات عضوية مرنة، بدءًا من المكاتب الساخنة المتاحة يوميًا وصولاً إلى المكاتب الخاصة المخصصة. وهي مجهزة بإنترنت عالي السرعة، وغرف اجتماعات، ومرافق طباعة، ومناطق مشتركة مريحة. ومع ذلك، فإن القيمة الحقيقية لمساحة العمل المشتركة تكمن في مجتمعها النابض بالحياة. فهي توفر أجواء ديناميكية للتواصل والتعاون وإطلاق أفكار جديدة من خلال التفاعلات العفوية والفعاليات المنظمة.</p>\r\n<p class=\"ds-markdown-paragraph\">من خلال الجمع بين هيكل المكتب التقليدي وحرية ومرونة العمل عن بُعد، تعمل مساحات العمل المشتركة على تعزيز الإنتاجية والإبداع والنمو المهني، مما يجعلها مستقبل العمل</p>', '[\"2\", \"6\", \"12\", \"16\", \"18\"]', NULL, NULL, '2025-08-23 13:52:04', '2025-09-08 14:47:22'),
(23, 51, 12, 3, 5, NULL, 11, NULL, NULL, NULL, 'Cityview Convention Hall', 'cityview-convention-hall', 'Jumeirah - Dubai - United Arab Emirates', '<p class=\"ds-markdown-paragraph\">Cityview Convention Hall stands as a premier destination for events of distinction and scale. Strategically located in the heart of the city, it offers breathtaking panoramic views of the urban skyline, creating a dynamic and inspiring backdrop for any occasion. This state-of-the-art facility is designed to impress, combining architectural elegance with cutting-edge technology.</p>\r\n<p class=\"ds-markdown-paragraph\">The hall features a vast, flexible main space that can be configured to host grand international conferences, sophisticated corporate galas, large-scale exhibitions, and dream weddings. Beyond the main hall, a variety of well-appointed meeting rooms and breakout spaces cater to more intimate gatherings and focused sessions.</p>\r\n<p class=\"ds-markdown-paragraph\">Every detail is curated for excellence. We offer advanced audio-visual systems, high-speed connectivity, and a dedicated, professional events team to ensure flawless execution from conception to completion. Coupled with exceptional catering services that can customize menus to suit every palate, Cityview Convention Hall is more than just a venue; it is a partner in creating unforgettable, seamless, and impactful events that leave a lasting impression on all who attend</p>', '[\"1\", \"3\", \"17\"]', NULL, NULL, '2025-08-23 14:39:31', '2025-08-29 10:11:23'),
(24, 53, 12, 4, 6, NULL, 12, NULL, NULL, NULL, 'قاعة مؤتمرات سيتي فيو', 'قاعة-مؤتمرات-سيتي-فيو', 'Jumeirah - Dubai - United Arab Emirates', '<p class=\"ds-markdown-paragraph\">تعتبر قاعة سيتي فيو للمؤتمرات الوجهة المتميزة والفاخرة للفعاليات والمناسبات الكبرى. تقع القاعة في موقع استراتيجي متميز في قلب المدينة، وتوفر إطلالات بانورامية خلابة على الأفق الحضري، مما يخلق خلفية ملهمة وديناميكية لأي مناسبة. تم تصميم المنشأة الحديثة هذه لتثير الإعجاب، حيث تجمع بين الأناقة المعمارية وأحدث التقنيات.</p>\r\n<p class=\"ds-markdown-paragraph\">تتميز القاعة بمساحة رئيسية شاسعة ومرنة يمكن تخصيصها لاستضافة المؤتمرات الدولية الكبرى، والحفلات الشركاتية الراقية، والمعارض الضخمة، وحفلات الزفاف التي تخطف الأنفاس. إلى جانب القاعة الرئيسية، توفر مجموعة من غرف الاجتماعات وغرف الجلسات المنفصلة المصممة بأعلى مستوى، مكاناً مثالياً للجلسات الأصغر حجماً والأكثر تركيزاً.</p>\r\n<p class=\"ds-markdown-paragraph\">كل التفاصيل معدة للتميز. نقدم أنظمة سمعية وبصرية متطورة، واتصال عالي السرعة بالإنترنت، وفريقًا احترافيًا مخصصًا لتنظيم الفعاليات لضمان تنفيذ لا تشوبه شائبة من التخطيط حتى الانتهاء. إلى جانب خدمات التموين الاستثنائية التي يمكنها تخصيص القوائم الغذائية لتتناسب مع كل الأذواق، فإن قاعة سيتي فيو للمؤتمرات هي أكثر من مجرد مكان؛ إنها شريك في خلق فعاليات لا تُنسى وسلسة وذات تأثير كبير تترك انطباعاً دائماً لدى جميع الحضور</p>', '[\"2\", \"4\", \"18\"]', NULL, NULL, '2025-08-23 14:39:31', '2025-08-29 10:11:23'),
(25, 51, 13, 1, 3, 13, 15, 3, 1, NULL, 'Diamond Grand Hall', 'diamond-grand-hall', 'Victoria, BC, Canada', '<p class=\"ds-markdown-paragraph\">The Diamond Grand Hall is the epitome of luxury and grandeur, designed to transform your most significant occasions into timeless, legendary events. As you step inside, you are immediately enveloped in an atmosphere of sophisticated elegance. Soaring ceilings adorned with magnificent crystal chandeliers cast a warm, sparkling light across the vast, meticulously finished space, creating an ambiance of pure opulence.</p>\r\n<p class=\"ds-markdown-paragraph\">Versatility is a cornerstone of the hall\'s design. With a spacious, column-free layout and state-of-the-art modular partitioning, it can be seamlessly configured to host an intimate gathering of hundreds or a lavish gala for over a thousand guests. Every detail is curated for excellence, from the advanced, integrated lighting and sound systems that cater to both serene ceremonies and dynamic celebrations, to the dedicated VIP suites and spacious preparation areas.</p>\r\n<p class=\"ds-markdown-paragraph\">Ideal for dream weddings, prestigious corporate conferences, high-society galas, and cultural exhibitions, the Diamond Grand Hall is more than just a venue; it is a blank canvas. Our dedicated team of event specialists works tirelessly to bring your unique vision to life, ensuring every detail is executed with flawless precision. It is the premier destination where unforgettable memories are crafted, and moments are transformed into diamonds that last forever</p>', '[\"19\", \"7\", \"11\", \"15\", \"17\"]', NULL, NULL, '2025-08-23 15:16:43', '2025-08-28 11:17:22'),
(26, 53, 13, 2, 4, 14, 16, 4, 2, NULL, 'قاعة الماس الكبرى', 'قاعة-الماس-الكبرى', 'Victoria, BC, Canada', '<p class=\"ds-markdown-paragraph\">تتجسد في \"القاعة الكبرى الماسية\" أسمى معاني الفخامة والعظمة، حيث صُممت لتحول مناسباتكم الأكثر أهمية إلى أحداث خالدة وأسطورية. عند تجوُّلكم في أرجائها، ستجدون أنفسكم محاطين بأجواء من الأناقة الراقية. فأسقفها الشامخة المُزينة بثريات كريستالية مهيبة تُلقي ضوءها الدافء المتلألئ على مساحتها الشاسعة ذات التشطيب المتقن، لتصنع إحساساً لا مثيل له بالروعة والبذخ.</p>\r\n<p class=\"ds-markdown-paragraph\">تتميز القاعة بمرونة تصميمها الاستثنائي. فبفضل مساحتها المفتوحة الخالية من الأعمدة وأنظمة التقسيم المتطورة، يمكن تكييفها بسلاسة لاستقبال حفل حميم يضم مئات المدعوين أو حفلاً ضخماً يليق بأكثر من ألف ضيف. كل التفاصيل هنا مُختارة بعناية فائقة، بدءاً من أنظمة الإضاءة والصوت المتكاملة والمتطورة التي تخدم كلًّا من المراسم الهادئة والاحتفالات الصاخبة، ووصولاً إلى صالات الاستقبال الخاصة وغرف التحضير الواسعة.</p>\r\n<p class=\"ds-markdown-paragraph\">إنها المكان المثالي لحفلات الزفاف الأحلام، والمؤتمرات cooperate الراقية، والحفلات الاجتماعية الرسمية، والمعارض الثقافية. \"القاعة الكبرى الماسية\" هي أكثر من مجرد مساحة؛ إنها لوحة بيضاء جاهزة لرسم أي رؤية. فريقنا المختص من منسقي الفعاليات يعمل بدأب لتحقيق رؤيتكم الفريدة، وضمان تنفيذ كل التفاصيل بدقة متناهية. إنها الوجهة الأولى حيث تُصنع الذكريات التي لا تُنسى، وتتحول اللحظات الثمينة إلى ماسات تبقى خالدة إلى الأبد.</p>', '[\"20\", \"8\", \"16\", \"18\"]', NULL, NULL, '2025-08-23 15:16:43', '2025-08-28 11:17:22'),
(27, 51, 14, 9, 5, NULL, 7, NULL, NULL, 3, 'Creative Exhibition Plaza', 'creative-exhibition-plaza', 'Downtown Dubai - Dubai - United Arab Emirates', '<p class=\"ds-markdown-paragraph\">Welcome to the Creative Exhibition Plaza, a dynamic and ever-evolving hub where imagination takes physical form. This is not merely a space to observe art; it is an immersive environment designed to ignite dialogue, inspire innovation, and celebrate the boundless potential of human creativity.</p>\r\n<p class=\"ds-markdown-paragraph\">The Plaza is a architectural marvel in itself, featuring sleek, modern design with flexible, open-plan spaces that adapt to a multitude of visions. Natural light floods the main atrium, highlighting installations that range from cutting-edge digital art and interactive media to profound sculptures and avant-garde fashion. Walls transform into canvases for projection mapping, and soundscapes curated by audio artists fill the air, engaging all senses.</p>\r\n<p class=\"ds-markdown-paragraph\">Beyond exhibition, the Plaza is a vibrant community center. It hosts workshops led by renowned creators, talks that dissect future trends, and live performances that blur the line between artist and audience. It features curated retail spaces offering unique, handcrafted goods and a café designed as a networking spot for thinkers and dreamers.</p>\r\n<p class=\"ds-markdown-paragraph\">The Creative Exhibition Plaza is more than a destination—it\'s a living ecosystem. It is a crossroads for established masters and emerging talent, a playground for ideas, and a testament to the power of creative expression to connect us all. Step inside and become part of the story</p>', '[\"1\", \"3\", \"5\", \"17\"]', NULL, NULL, '2025-08-24 06:47:34', '2025-09-01 10:30:40'),
(28, 53, 14, 10, 6, NULL, 9, NULL, NULL, 4, 'ساحة المعرض الإبداعي', 'ساحة-المعرض-الإبداعي', 'Downtown Dubai - Dubai - United Arab Emirates', '<p class=\"ds-markdown-paragraph\">مرحبًا بكم في \"ساحة المعارض الإبداعية\"، مركز ديناميكي ومتطور دائمًا حيث تتجسد الخيالة في شكل مادي. هذه ليست مجرد مساحة لمشاهدة الفن؛ إنها بيئة غامسة مصممة لإشعال الحوار، وإلهام الابتكار، والاحتفاء بالإمكانات اللامحدودة للإبداع البشري.</p>\r\n<p class=\"ds-markdown-paragraph\">تمثل الساحة تحفة معمارية بحد ذاتها، حيث تتميز بتصميم حديث أنيق ومسحات مفتوحة مرنة تتكيف مع مجموعة لا حصر لها من الرؤى. يغمر الضوء الطبيعي البهو الرئيسي، مما يسلط الضوء على منشآت تتراوح بين الفن الرقمي المتقن والوسائط التفاعلية إلى المنحوتات العميقة والأزياء الطليعية. تتحول الجدران إلى لوحات للعروض الضوئية، وتملأ الأصوات التي صممها فنانون صوتيون الأجواء، engaging جميع الحواس.</p>\r\n<p class=\"ds-markdown-paragraph\">ما وراء المعارض، تعد الساحة مركزًا نابضًا للحياة المجتمعية. تستضيف ورش عمل يقودها مبدعون مشهورون، ومحاضرات تناقش اتجاهات المستقبل، وعروضًا حية تزيل الحدود بين الفنان والجمهور. وتضم مساحات بيعية تقدم سلعًا فريدة مصنوعة يدويًا ومقهى مصمم كمنصة للتواصل بين المفكرين والحالمين.</p>\r\n<p class=\"ds-markdown-paragraph\">\"ساحة المعارض الإبداعية\" هي أكثر من مجرد وجهة—إنها نظام بيئي حي. هي ملتقى للأساتذة المرموقين والمواهب الناشئة، وملعب للأفكار، وشهادة على قوة التعبير الإبداعي لربطنا جميعًا. ادخلوا وكونوا جزءًا من القصة</p>', '[\"2\", \"4\", \"6\", \"18\"]', NULL, NULL, '2025-08-24 06:47:34', '2025-09-01 10:30:40');

-- --------------------------------------------------------

--
-- Table structure for table `space_coupons`
--

CREATE TABLE `space_coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `seller_id` bigint UNSIGNED DEFAULT NULL,
  `space_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_type` enum('fixed','percentage') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` decimal(8,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `serial_number` int DEFAULT NULL,
  `spaces` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `space_features`
--

CREATE TABLE `space_features` (
  `id` bigint UNSIGNED NOT NULL,
  `seller_id` bigint UNSIGNED DEFAULT NULL,
  `feature_charge_id` bigint DEFAULT NULL,
  `booking_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `space_id` bigint UNSIGNED DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `currency_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_text_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `days` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `conversation_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `space_features`
--

INSERT INTO `space_features` (`id`, `seller_id`, `feature_charge_id`, `booking_number`, `seller_email`, `space_id`, `total`, `currency_text`, `currency_text_position`, `currency_symbol`, `currency_symbol_position`, `payment_method`, `gateway_type`, `payment_status`, `booking_status`, `attachment`, `invoice`, `days`, `start_date`, `end_date`, `conversation_id`, `created_at`, `updated_at`) VALUES
(17, 0, 12, 'BK-jbs7zxNT', 'dchen88@example.com', 13, 30.00, 'USD', 'left', '$', 'left', 'MercadoPago', 'MercadoPago', 'completed', 'approved', NULL, NULL, '1000', '2025-09-09', '2028-06-05', NULL, '2025-09-09 13:43:35', '2025-09-09 13:43:35'),
(18, 0, 12, 'BK-WOS3eONK', 'dchen88@example.com', 2, 30.00, 'USD', 'left', '$', 'left', 'PayPal', 'PayPal', 'completed', 'approved', NULL, NULL, '1000', '2025-09-11', '2028-06-07', NULL, '2025-09-11 11:36:26', '2025-09-11 11:36:26'),
(20, 0, 12, 'BK-frR1E9ne', 'dchen88@example.com', 1, 30.00, 'USD', 'left', '$', 'left', 'Authorize.Net', 'Authorize.Net', 'completed', 'approved', NULL, NULL, '1000', '2025-09-11', '2028-06-07', NULL, '2025-09-11 11:36:40', '2025-09-11 11:36:40'),
(21, 67, 12, 'BK-frR1E9de', 'dchen88@example.com', 7, 30.00, 'USD', 'left', '$', 'left', 'Authorize.Net', 'Authorize.Net', 'completed', 'approved', NULL, NULL, '1000', '2025-09-11', '2028-06-07', NULL, '2025-09-11 11:36:40', '2025-09-11 11:36:40');

-- --------------------------------------------------------

--
-- Table structure for table `space_holidays`
--

CREATE TABLE `space_holidays` (
  `id` bigint UNSIGNED NOT NULL,
  `seller_id` bigint UNSIGNED DEFAULT NULL,
  `date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `space_holidays`
--

INSERT INTO `space_holidays` (`id`, `seller_id`, `date`, `created_at`, `updated_at`) VALUES
(1, 66, '2025-08-27', '2025-08-22 06:18:16', '2025-08-22 06:18:16'),
(2, 67, '2025-08-29', '2025-08-22 15:13:14', '2025-08-22 15:13:14');

-- --------------------------------------------------------

--
-- Table structure for table `space_reviews`
--

CREATE TABLE `space_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `space_id` bigint UNSIGNED DEFAULT NULL,
  `rating` tinyint DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `space_services`
--

CREATE TABLE `space_services` (
  `id` bigint UNSIGNED NOT NULL,
  `space_id` tinyint DEFAULT NULL,
  `seller_id` bigint DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `serial_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_sub_services` tinyint(1) DEFAULT NULL,
  `is_custom_day` tinyint UNSIGNED DEFAULT NULL,
  `subservice_selection_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `space_services`
--

INSERT INTO `space_services` (`id`, `space_id`, `seller_id`, `image`, `status`, `serial_number`, `has_sub_services`, `is_custom_day`, `subservice_selection_type`, `price_type`, `price`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 1, 0, NULL, '1', '3', 1, NULL, 'single', 'fixed', NULL, 0, '2025-08-19 05:33:07', '2025-08-22 11:42:34'),
(2, 1, 0, NULL, '1', '2', 1, NULL, 'multiple', 'per person', NULL, 0, '2025-08-19 06:52:43', '2025-08-19 06:52:43'),
(4, 1, 0, NULL, '1', '1', 1, NULL, 'multiple', 'fixed', NULL, 0, '2025-08-19 07:18:53', '2025-08-19 07:20:04'),
(5, 1, 0, NULL, '1', '4', 0, NULL, NULL, 'fixed', 50.00, 0, '2025-08-19 07:21:47', '2025-08-19 07:21:47'),
(6, 1, 0, NULL, '1', '5', 0, NULL, NULL, 'fixed', 80.00, 0, '2025-08-19 07:23:46', '2025-08-19 07:23:46'),
(7, 1, 0, NULL, '1', '6', 0, NULL, NULL, 'fixed', 100.00, 0, '2025-08-19 07:24:24', '2025-08-19 07:24:49'),
(8, 1, 0, NULL, '1', '7', 0, NULL, NULL, 'fixed', 120.00, 0, '2025-08-19 07:25:35', '2025-08-19 07:25:35'),
(9, 2, 0, NULL, '1', '10', 1, NULL, 'single', 'fixed', NULL, 0, '2025-08-19 14:12:41', '2025-08-22 11:47:01'),
(11, 2, 0, NULL, '1', '2', 1, NULL, 'multiple', 'per person', NULL, 0, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(12, 2, 0, NULL, '1', '3', 1, NULL, 'multiple', 'fixed', NULL, 0, '2025-08-19 14:29:33', '2025-08-22 11:46:25'),
(13, 2, 0, NULL, '1', '4', 0, NULL, NULL, 'fixed', 50.00, 0, '2025-08-19 15:19:28', '2025-08-19 15:19:28'),
(14, 2, 0, NULL, '1', '5', 0, NULL, NULL, 'fixed', 100.00, 0, '2025-08-19 15:20:19', '2025-08-19 15:20:19'),
(15, 3, 0, NULL, '1', '1', 0, 1, NULL, 'fixed', 10.00, 0, '2025-08-21 12:30:14', '2025-08-21 13:05:42'),
(16, 3, 0, NULL, '1', '2', 1, 1, 'multiple', 'per person', NULL, 0, '2025-08-21 13:34:56', '2025-08-21 13:34:56'),
(17, 3, 0, NULL, '1', '3', 0, 0, NULL, 'fixed', 20.00, 0, '2025-08-21 13:48:50', '2025-08-21 14:45:52'),
(18, 3, 0, NULL, '1', '4', 1, 0, 'single', 'per person', NULL, 0, '2025-08-21 14:52:16', '2025-08-21 14:52:16'),
(19, 4, 66, NULL, '1', '2', 1, NULL, 'single', 'fixed', NULL, 0, '2025-08-22 08:29:37', '2025-08-22 11:29:19'),
(20, 4, 66, NULL, '1', '1', 1, NULL, 'multiple', 'per person', NULL, 0, '2025-08-22 09:18:26', '2025-08-22 11:29:03'),
(21, 4, 66, NULL, '1', '3', 0, NULL, NULL, 'fixed', 20.00, 0, '2025-08-22 11:30:19', '2025-08-22 11:30:19'),
(22, 4, 66, NULL, '1', '4', 0, NULL, NULL, 'fixed', 20.00, 0, '2025-08-22 11:35:11', '2025-08-22 11:35:11'),
(23, 4, 66, NULL, '1', '5', 0, NULL, NULL, 'fixed', 30.00, 0, '2025-08-22 11:35:56', '2025-08-22 11:35:56'),
(24, 4, 66, NULL, '1', '6', 0, NULL, NULL, 'fixed', 20.00, 0, '2025-08-22 11:36:30', '2025-08-22 11:36:30'),
(25, 5, 66, NULL, '1', '10', 1, NULL, 'multiple', 'fixed', 20.00, 0, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(26, 5, 66, NULL, '1', '9', 1, NULL, 'single', 'per person', 8.00, 0, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(27, 5, 66, NULL, '1', '8', 0, NULL, NULL, 'per person', 3.00, 0, '2025-08-22 12:28:12', '2025-08-22 12:28:12'),
(28, 5, 66, NULL, '1', '7', 0, NULL, NULL, 'fixed', 20.00, 0, '2025-08-22 12:28:54', '2025-08-22 12:28:54'),
(29, 5, 66, NULL, '1', '7', 0, NULL, NULL, 'fixed', 40.00, 0, '2025-08-22 12:51:19', '2025-08-22 12:51:19'),
(30, 6, 66, NULL, '1', '1', 1, 1, 'single', 'fixed', NULL, 0, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(31, 6, 66, NULL, '1', '2', 1, 0, 'multiple', 'per person', NULL, 0, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(32, 6, 66, NULL, '1', '3', 0, 1, NULL, 'fixed', 30.00, 0, '2025-08-22 13:37:04', '2025-08-22 13:37:04'),
(33, 6, 66, NULL, '1', '4', 0, 0, NULL, 'fixed', 30.00, 0, '2025-08-22 13:37:44', '2025-08-22 13:37:44'),
(34, 6, 66, NULL, '1', '5', 0, 1, NULL, 'per person', 2.00, 0, '2025-08-22 13:38:41', '2025-08-22 13:38:41'),
(35, 7, 67, NULL, '1', '1', 0, NULL, NULL, 'fixed', 50.00, 0, '2025-08-22 15:01:42', '2025-08-22 15:01:42'),
(36, 7, 67, NULL, '1', '2', 0, NULL, NULL, 'per person', 5.00, 0, '2025-08-22 15:02:56', '2025-08-22 15:02:56'),
(37, 7, 67, NULL, '1', '3', 0, NULL, NULL, 'fixed', 50.00, 0, '2025-08-22 15:04:16', '2025-08-22 15:04:16'),
(38, 7, 67, NULL, '1', '4', 1, NULL, 'multiple', 'per person', NULL, 0, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(39, 7, 67, NULL, '1', '5', 1, NULL, 'single', 'fixed', NULL, 0, '2025-08-22 15:12:12', '2025-08-22 15:12:12'),
(40, 8, 67, NULL, '1', '1', 1, NULL, 'single', 'fixed', NULL, 0, '2025-08-22 15:49:15', '2025-08-22 15:49:15'),
(41, 8, 67, NULL, '1', '2', 1, NULL, 'multiple', 'per person', NULL, 0, '2025-08-22 15:53:00', '2025-08-22 15:54:35'),
(42, 8, 67, NULL, '1', '3', 0, NULL, 'multiple', 'fixed', 70.00, 0, '2025-08-22 15:54:00', '2025-08-22 15:54:52'),
(43, 8, 67, NULL, '1', '4', 0, NULL, NULL, 'fixed', 50.00, 0, '2025-08-22 15:55:19', '2025-08-22 15:55:19'),
(44, 8, 67, NULL, '1', '5', 0, NULL, NULL, 'per person', 20.00, 0, '2025-08-22 15:56:33', '2025-08-22 15:56:33'),
(45, 9, 67, NULL, '1', '1', 0, 1, NULL, 'fixed', 10.00, 0, '2025-08-23 08:41:28', '2025-08-23 08:41:28'),
(46, 9, 67, NULL, '1', '2', 0, 0, NULL, 'fixed', 20.00, 0, '2025-08-23 08:42:20', '2025-08-23 08:42:20'),
(47, 9, 67, NULL, '1', '3', 0, 0, NULL, 'per person', 5.00, 0, '2025-08-23 08:43:13', '2025-08-23 08:43:13'),
(48, 9, 67, NULL, '1', '4', 1, 1, 'multiple', 'per person', NULL, 0, '2025-08-23 08:51:22', '2025-08-23 08:56:55'),
(51, 9, 67, NULL, '1', '5', 1, 0, 'single', 'fixed', NULL, 0, '2025-08-23 09:19:11', '2025-08-23 09:19:11'),
(52, 10, 68, NULL, '1', '1', 0, NULL, NULL, 'per person', 5.00, 0, '2025-08-23 12:33:36', '2025-08-23 12:36:16'),
(53, 10, 68, NULL, '1', '2', 0, NULL, NULL, 'fixed', 10.00, 0, '2025-08-23 12:35:44', '2025-08-23 12:35:44'),
(54, 10, 68, NULL, '1', '3', 1, NULL, 'multiple', 'per person', NULL, 0, '2025-08-23 12:57:15', '2025-08-23 12:57:15'),
(55, 10, 68, NULL, '1', '5', 1, NULL, 'single', 'fixed', NULL, 0, '2025-08-23 13:10:32', '2025-08-23 13:10:32'),
(56, 11, 68, NULL, '1', '1', 0, 0, NULL, 'fixed', 20.00, 0, '2025-08-23 13:55:48', '2025-08-23 13:55:48'),
(57, 11, 68, NULL, '1', '2', 0, 1, NULL, 'per person', 5.00, 0, '2025-08-23 13:56:34', '2025-08-23 13:56:34'),
(58, 11, 68, NULL, '1', '3', 0, 1, NULL, 'fixed', 20.00, 0, '2025-08-23 13:57:14', '2025-08-23 13:57:14'),
(59, 11, 68, NULL, '1', '4', 1, 1, 'multiple', 'per person', NULL, 0, '2025-08-23 13:59:42', '2025-08-23 13:59:42'),
(60, 11, 68, NULL, '1', '5', 1, 1, 'single', 'fixed', NULL, 0, '2025-08-23 14:02:48', '2025-08-23 14:02:48'),
(61, 12, 68, NULL, '1', '1', 0, NULL, NULL, 'per person', 10.00, 0, '2025-08-23 14:42:55', '2025-08-23 14:42:55'),
(62, 12, 68, NULL, '1', '2', 0, NULL, NULL, 'fixed', 40.00, 0, '2025-08-23 14:43:30', '2025-08-23 14:43:30'),
(63, 12, 68, NULL, '1', '3', 0, NULL, NULL, 'fixed', 35.00, 0, '2025-08-23 14:44:08', '2025-08-23 14:44:08'),
(64, 12, 68, NULL, '1', '4', 1, NULL, 'multiple', 'per person', NULL, 0, '2025-08-23 14:47:29', '2025-08-23 14:47:29'),
(65, 12, 68, NULL, '1', '5', 1, NULL, 'single', 'fixed', NULL, 0, '2025-08-23 14:53:09', '2025-08-23 14:53:09'),
(66, 13, 0, NULL, '1', '1', 0, NULL, NULL, 'fixed', 10.00, 0, '2025-08-23 15:27:17', '2025-08-23 15:27:17'),
(67, 13, 0, NULL, '1', '2', 0, NULL, NULL, 'fixed', 20.00, 0, '2025-08-23 15:28:14', '2025-08-23 15:28:14'),
(68, 13, 0, NULL, '1', '3', 0, NULL, NULL, 'fixed', 100.00, 0, '2025-08-23 15:28:59', '2025-08-23 15:28:59'),
(69, 13, 0, NULL, '1', '4', 1, NULL, 'multiple', 'per person', NULL, 0, '2025-08-23 15:31:19', '2025-08-23 15:31:19'),
(70, 13, 0, NULL, '1', '6', 1, NULL, 'multiple', 'fixed', NULL, 0, '2025-08-23 15:34:06', '2025-08-23 15:34:06'),
(71, 14, 67, NULL, '1', '1', 0, NULL, NULL, 'fixed', 20.00, 0, '2025-08-24 07:35:38', '2025-08-24 07:35:38'),
(72, 14, 67, NULL, '1', '2', 0, NULL, NULL, 'fixed', 30.00, 0, '2025-08-24 07:36:14', '2025-08-24 07:36:14'),
(73, 14, 67, NULL, '1', '3', 0, NULL, NULL, 'fixed', 20.00, 0, '2025-08-24 07:36:44', '2025-08-24 07:36:44'),
(81, 14, 67, NULL, '1', '4', 1, NULL, 'single', 'fixed', NULL, 0, '2025-08-24 08:34:30', '2025-08-24 08:34:30'),
(82, 14, 67, NULL, '1', '5', 1, NULL, 'multiple', 'per person', NULL, 0, '2025-08-24 08:37:07', '2025-08-24 08:37:07');

-- --------------------------------------------------------

--
-- Table structure for table `space_service_contents`
--

CREATE TABLE `space_service_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `space_service_id` bigint DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `space_service_contents`
--

INSERT INTO `space_service_contents` (`id`, `language_id`, `space_service_id`, `title`, `slug`, `description`, `meta_keywords`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 51, 1, 'Venue Decoration', 'venue-decoration', NULL, NULL, NULL, '2025-08-19 05:33:07', '2025-08-19 05:33:07'),
(2, 53, 1, 'ديكور المكان', 'ديكور-المكان', NULL, NULL, NULL, '2025-08-19 05:33:07', '2025-08-19 05:33:07'),
(3, 51, 2, 'Catering Service', 'catering-service', NULL, NULL, NULL, '2025-08-19 06:52:43', '2025-08-19 06:52:43'),
(4, 53, 2, 'خدمة تقديم الطعام', 'خدمة-تقديم-الطعام', NULL, NULL, NULL, '2025-08-19 06:52:43', '2025-08-19 06:52:43'),
(7, 51, 4, 'Photography & Videography', 'photography--videography', NULL, NULL, NULL, '2025-08-19 07:18:53', '2025-08-19 07:18:53'),
(8, 53, 4, 'التصوير الفوتوغرافي وتصوير الفيديو', 'التصوير-الفوتوغرافي-وتصوير-الفيديو', NULL, NULL, NULL, '2025-08-19 07:18:53', '2025-08-19 07:18:53'),
(9, 51, 5, 'DJ Service', 'dj-service', NULL, NULL, NULL, '2025-08-19 07:21:47', '2025-08-19 07:22:46'),
(10, 53, 5, 'خدمة الدي جي', 'خدمة-الدي-جي', NULL, NULL, NULL, '2025-08-19 07:21:47', '2025-08-19 07:22:46'),
(11, 51, 6, 'Live Band', 'live-band', NULL, NULL, NULL, '2025-08-19 07:23:46', '2025-08-19 07:23:46'),
(12, 53, 6, 'فرقة موسيقية حية', 'فرقة-موسيقية-حية', NULL, NULL, NULL, '2025-08-19 07:23:46', '2025-08-19 07:23:46'),
(13, 51, 7, 'Luxury Car Service', 'luxury-car-service', NULL, NULL, NULL, '2025-08-19 07:24:24', '2025-08-19 07:24:24'),
(14, 53, 7, 'خدمة السيارات الفاخرة', 'خدمة-السيارات-الفاخرة', NULL, NULL, NULL, '2025-08-19 07:24:24', '2025-08-19 07:24:24'),
(15, 51, 8, 'Guest Shuttle Bus', 'guest-shuttle-bus', NULL, NULL, NULL, '2025-08-19 07:25:35', '2025-08-19 07:25:35'),
(16, 53, 8, 'حافلة نقل الضيوف', 'حافلة-نقل-الضيوف', NULL, NULL, NULL, '2025-08-19 07:25:35', '2025-08-19 07:25:35'),
(17, 51, 9, 'Venue Decoration', 'venue-decoration', NULL, NULL, NULL, '2025-08-19 14:12:41', '2025-08-19 14:12:41'),
(18, 53, 9, 'ديكور المكان', 'ديكور-المكان', NULL, NULL, NULL, '2025-08-19 14:12:41', '2025-08-19 14:12:41'),
(21, 51, 11, 'Catering Service', 'catering-service', NULL, NULL, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(22, 53, 11, 'خدمة تقديم الطعام', 'خدمة-تقديم-الطعام', NULL, NULL, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(23, 51, 12, 'Bridal & Groom Styling', 'bridal--groom-styling', NULL, NULL, NULL, '2025-08-19 14:29:33', '2025-08-19 14:29:33'),
(24, 53, 12, 'تنسيق ملابس العروس والعريس', 'تنسيق-ملابس-العروس-والعريس', NULL, NULL, NULL, '2025-08-19 14:29:33', '2025-08-19 14:29:33'),
(25, 51, 13, 'DJ Service', 'dj-service', NULL, NULL, NULL, '2025-08-19 15:19:28', '2025-08-19 15:19:28'),
(26, 53, 13, 'خدمة الدي جي', 'خدمة-الدي-جي', NULL, NULL, NULL, '2025-08-19 15:19:28', '2025-08-19 15:19:28'),
(27, 51, 14, 'Luxury Car Service', 'luxury-car-service', NULL, NULL, NULL, '2025-08-19 15:20:19', '2025-08-19 15:20:19'),
(28, 53, 14, 'خدمة السيارات الفاخرة', 'خدمة-السيارات-الفاخرة', NULL, NULL, NULL, '2025-08-19 15:20:19', '2025-08-19 15:20:19'),
(29, 51, 15, 'Entertainment', 'entertainment', NULL, NULL, NULL, '2025-08-21 12:30:14', '2025-08-21 12:30:14'),
(30, 53, 15, 'ترفيه', 'ترفيه', NULL, NULL, NULL, '2025-08-21 12:30:14', '2025-08-21 13:05:42'),
(31, 51, 16, 'Catering Service', 'catering-service', NULL, NULL, NULL, '2025-08-21 13:34:56', '2025-08-21 13:34:56'),
(32, 53, 16, 'خدمة تقديم الطعام', 'خدمة-تقديم-الطعام', NULL, NULL, NULL, '2025-08-21 13:34:56', '2025-08-21 13:34:56'),
(33, 51, 17, 'Photography', 'photography', NULL, NULL, NULL, '2025-08-21 13:48:50', '2025-08-21 13:48:50'),
(34, 53, 17, 'التصوير الفوتوغرافي', 'التصوير-الفوتوغرافي', NULL, NULL, NULL, '2025-08-21 13:48:50', '2025-08-21 13:48:50'),
(35, 51, 18, 'Gift & Return Gifts', 'gift--return-gifts', NULL, NULL, NULL, '2025-08-21 14:52:16', '2025-08-21 14:52:16'),
(36, 53, 18, 'الهدايا والإرجاع', 'الهدايا-والإرجاع', NULL, NULL, NULL, '2025-08-21 14:52:16', '2025-08-21 14:52:16'),
(37, 51, 19, 'Booth Design & Setup', 'booth-design--setup', NULL, NULL, NULL, '2025-08-22 08:29:37', '2025-08-22 08:29:37'),
(38, 53, 19, 'تصميم وتجهيز الكشك', 'تصميم-وتجهيز-الكشك', NULL, NULL, NULL, '2025-08-22 08:29:37', '2025-08-22 08:29:37'),
(39, 51, 20, 'Catering Service', 'catering-service', NULL, NULL, NULL, '2025-08-22 09:18:26', '2025-08-22 09:18:26'),
(40, 53, 20, 'خدمة تقديم الطعام', 'خدمة-تقديم-الطعام', NULL, NULL, NULL, '2025-08-22 09:18:26', '2025-08-22 09:18:26'),
(41, 51, 21, 'Registration Desk', 'registration-desk', NULL, NULL, NULL, '2025-08-22 11:30:19', '2025-08-22 11:30:19'),
(42, 53, 21, 'مكتب التسجيل', 'مكتب-التسجيل', NULL, NULL, NULL, '2025-08-22 11:30:19', '2025-08-22 11:30:19'),
(43, 51, 22, 'Visitor Tracking', 'visitor-tracking', NULL, NULL, NULL, '2025-08-22 11:35:11', '2025-08-22 11:35:11'),
(44, 53, 22, 'تتبع الزوار', 'تتبع-الزوار', NULL, NULL, NULL, '2025-08-22 11:35:11', '2025-08-22 11:35:11'),
(45, 51, 23, 'Security Service', 'security-service', NULL, NULL, NULL, '2025-08-22 11:35:56', '2025-08-22 11:35:56'),
(46, 53, 23, 'خدمات الأمن', 'خدمات-الأمن', NULL, NULL, NULL, '2025-08-22 11:35:56', '2025-08-22 11:35:56'),
(47, 51, 24, 'VIP Management', 'vip-management', NULL, NULL, NULL, '2025-08-22 11:36:30', '2025-08-22 11:36:30'),
(48, 53, 24, 'إدارة كبار الشخصيات', 'إدارة-كبار-الشخصيات', NULL, NULL, NULL, '2025-08-22 11:36:30', '2025-08-22 11:36:30'),
(49, 51, 25, 'Venue Decoration', 'venue-decoration', NULL, NULL, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(50, 53, 25, 'ديكور المكان', 'ديكور-المكان', NULL, NULL, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(51, 51, 26, 'Catering Service', 'catering-service', NULL, NULL, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(52, 53, 26, 'خدمة تقديم الطعام', 'خدمة-تقديم-الطعام', NULL, NULL, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(53, 51, 27, 'Prayer Items', 'prayer-items', NULL, NULL, NULL, '2025-08-22 12:28:12', '2025-08-22 12:28:12'),
(54, 53, 27, 'أدوات الصلاة', 'أدوات-الصلاة', NULL, NULL, NULL, '2025-08-22 12:28:12', '2025-08-22 12:28:12'),
(55, 51, 28, 'Priest/Pandit/Imam', 'priestpanditimam', NULL, NULL, NULL, '2025-08-22 12:28:54', '2025-08-22 12:28:54'),
(56, 53, 28, 'كاهن/بانديت/إمام', 'كاهنبانديتإمام', NULL, NULL, NULL, '2025-08-22 12:28:54', '2025-08-22 12:28:54'),
(57, 51, 29, 'Volunteer Team', 'volunteer-team', NULL, NULL, NULL, '2025-08-22 12:51:19', '2025-08-22 12:51:19'),
(58, 53, 29, 'فريق المتطوعين', 'فريق-المتطوعين', NULL, NULL, NULL, '2025-08-22 12:51:19', '2025-08-22 12:51:19'),
(59, 51, 30, 'Venue Setup', 'venue-setup', NULL, NULL, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(60, 53, 30, 'إعداد المكان', 'إعداد-المكان', NULL, NULL, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(61, 51, 31, 'Catering Service', 'catering-service', NULL, NULL, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(62, 53, 31, 'خدمة تقديم الطعام', 'خدمة-تقديم-الطعام', NULL, NULL, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(63, 51, 32, 'Training Assistant', 'training-assistant', NULL, NULL, NULL, '2025-08-22 13:37:04', '2025-08-22 13:37:04'),
(64, 53, 32, 'مساعد التدريب', 'مساعد-التدريب', NULL, NULL, NULL, '2025-08-22 13:37:04', '2025-08-22 13:37:04'),
(65, 51, 33, 'IT Support', 'it-support', NULL, NULL, NULL, '2025-08-22 13:37:44', '2025-08-22 13:37:44'),
(66, 53, 33, 'دعم تكنولوجيا المعلومات', 'دعم-تكنولوجيا-المعلومات', NULL, NULL, NULL, '2025-08-22 13:37:44', '2025-08-22 13:37:44'),
(67, 51, 34, 'Welcome Kit', 'welcome-kit', NULL, NULL, NULL, '2025-08-22 13:38:41', '2025-08-22 13:38:41'),
(68, 53, 34, 'مجموعة الترحيب', 'مجموعة-الترحيب', NULL, NULL, NULL, '2025-08-22 13:38:41', '2025-08-22 13:38:41'),
(69, 51, 35, 'DJ Service', 'dj-service', NULL, NULL, NULL, '2025-08-22 15:01:42', '2025-08-22 15:01:42'),
(70, 53, 35, 'خدمة الدي جي', 'خدمة-الدي-جي', NULL, NULL, NULL, '2025-08-22 15:01:42', '2025-08-22 15:01:42'),
(71, 51, 36, 'Luxury Car Service', 'luxury-car-service', NULL, NULL, NULL, '2025-08-22 15:02:56', '2025-08-22 15:02:56'),
(72, 53, 36, 'خدمة السيارات الفاخرة', 'خدمة-السيارات-الفاخرة', NULL, NULL, NULL, '2025-08-22 15:02:56', '2025-08-22 15:02:56'),
(73, 51, 37, 'Guest Shuttle Bus', 'guest-shuttle-bus', NULL, NULL, NULL, '2025-08-22 15:04:16', '2025-08-22 15:04:16'),
(74, 53, 37, 'حافلة نقل الضيوف', 'حافلة-نقل-الضيوف', NULL, NULL, NULL, '2025-08-22 15:04:16', '2025-08-22 15:04:16'),
(75, 51, 38, 'Catering Service', 'catering-service', NULL, NULL, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(76, 53, 38, 'خدمة تقديم الطعام', 'خدمة-تقديم-الطعام', NULL, NULL, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(77, 51, 39, 'Venue Decoration', 'venue-decoration', NULL, NULL, NULL, '2025-08-22 15:12:12', '2025-08-22 15:12:12'),
(78, 53, 39, 'ديكور المكان', 'ديكور-المكان', NULL, NULL, NULL, '2025-08-22 15:12:12', '2025-08-22 15:12:12'),
(79, 51, 40, 'Venue Setup', 'venue-setup', NULL, NULL, NULL, '2025-08-22 15:49:15', '2025-08-22 15:49:15'),
(80, 53, 40, 'إعداد المكان', 'إعداد-المكان', NULL, NULL, NULL, '2025-08-22 15:49:15', '2025-08-22 15:49:15'),
(81, 51, 41, 'Catering Service', 'catering-service', NULL, NULL, NULL, '2025-08-22 15:53:00', '2025-08-22 15:53:00'),
(82, 53, 41, 'خدمة تقديم الطعام', 'خدمة-تقديم-الطعام', NULL, NULL, NULL, '2025-08-22 15:53:00', '2025-08-22 15:53:00'),
(83, 51, 42, 'Event Management', 'event-management', NULL, NULL, NULL, '2025-08-22 15:54:00', '2025-08-22 15:54:00'),
(84, 53, 42, 'إدارة الفعاليات', 'إدارة-الفعاليات', NULL, NULL, NULL, '2025-08-22 15:54:00', '2025-08-22 15:54:00'),
(85, 51, 43, 'Motivational Speaker', 'motivational-speaker', NULL, NULL, NULL, '2025-08-22 15:55:19', '2025-08-22 15:55:19'),
(86, 53, 43, 'متحدث تحفيزي', 'متحدث-تحفيزي', NULL, NULL, NULL, '2025-08-22 15:55:19', '2025-08-22 15:55:19'),
(87, 51, 44, 'Award Ceremony', 'award-ceremony', NULL, NULL, NULL, '2025-08-22 15:56:33', '2025-08-22 15:56:33'),
(88, 53, 44, 'Award Ceremony', 'award-ceremony', NULL, NULL, NULL, '2025-08-22 15:56:33', '2025-08-22 15:56:33'),
(89, 51, 45, 'Entertainment', 'entertainment', NULL, NULL, NULL, '2025-08-23 08:41:28', '2025-08-23 08:41:28'),
(90, 53, 45, 'ترفيه', 'ترفيه', NULL, NULL, NULL, '2025-08-23 08:41:28', '2025-08-23 08:41:28'),
(91, 51, 46, 'Photography', 'photography', NULL, NULL, NULL, '2025-08-23 08:42:20', '2025-08-23 08:42:20'),
(92, 53, 46, 'التصوير الفوتوغرافي', 'التصوير-الفوتوغرافي', NULL, NULL, NULL, '2025-08-23 08:42:20', '2025-08-23 08:42:20'),
(93, 51, 47, 'Gift & Return Gifts', 'gift--return-gifts', NULL, NULL, NULL, '2025-08-23 08:43:13', '2025-08-23 08:43:13'),
(94, 53, 47, 'الهدايا والإرجاع', 'الهدايا-والإرجاع', NULL, NULL, NULL, '2025-08-23 08:43:13', '2025-08-23 08:43:13'),
(95, 51, 48, 'Catering Service', 'catering-service', NULL, NULL, NULL, '2025-08-23 08:51:22', '2025-08-23 08:51:22'),
(96, 53, 48, 'خدمة تقديم الطعام', 'خدمة-تقديم-الطعام', NULL, NULL, NULL, '2025-08-23 08:51:22', '2025-08-23 08:51:22'),
(101, 51, 51, 'Venue Decoration', 'venue-decoration', NULL, NULL, NULL, '2025-08-23 09:19:11', '2025-08-23 09:19:11'),
(102, 53, 51, 'ديكور المكان', 'ديكور-المكان', NULL, NULL, NULL, '2025-08-23 09:19:11', '2025-08-23 09:19:11'),
(103, 51, 52, 'Hospitality', 'hospitality', NULL, NULL, NULL, '2025-08-23 12:33:36', '2025-08-23 12:33:36'),
(104, 53, 52, 'ضيافة', 'ضيافة', NULL, NULL, NULL, '2025-08-23 12:33:36', '2025-08-23 12:33:36'),
(105, 51, 53, 'IT Support', 'it-support', NULL, NULL, NULL, '2025-08-23 12:35:44', '2025-08-23 12:35:44'),
(106, 53, 53, 'دعم تكنولوجيا المعلومات', 'دعم-تكنولوجيا-المعلومات', NULL, NULL, NULL, '2025-08-23 12:35:44', '2025-08-23 12:35:44'),
(107, 51, 54, 'Catering Service', 'catering-service', NULL, NULL, NULL, '2025-08-23 12:57:15', '2025-08-23 12:57:15'),
(108, 53, 54, 'خدمة تقديم الطعام', 'خدمة-تقديم-الطعام', NULL, NULL, NULL, '2025-08-23 12:57:15', '2025-08-23 12:57:15'),
(109, 51, 55, 'Venue Setup', 'venue-setup', NULL, NULL, NULL, '2025-08-23 13:10:32', '2025-08-23 13:10:32'),
(110, 53, 55, 'إعداد المكان', 'إعداد-المكان', NULL, NULL, NULL, '2025-08-23 13:10:32', '2025-08-23 13:10:32'),
(111, 51, 56, 'Desk Registration', 'desk-registration', NULL, NULL, NULL, '2025-08-23 13:55:48', '2025-08-23 13:55:48'),
(112, 53, 56, 'تسجيل المكتب', 'تسجيل-المكتب', NULL, NULL, NULL, '2025-08-23 13:55:48', '2025-08-23 13:55:48'),
(113, 51, 57, 'Hospitality', 'hospitality', NULL, NULL, NULL, '2025-08-23 13:56:34', '2025-08-23 13:56:34'),
(114, 53, 57, 'ضيافة', 'ضيافة', NULL, NULL, NULL, '2025-08-23 13:56:34', '2025-08-23 13:56:34'),
(115, 51, 58, 'IT Support', 'it-support', NULL, NULL, NULL, '2025-08-23 13:57:14', '2025-08-23 13:57:14'),
(116, 53, 58, 'دعم تكنولوجيا المعلومات', 'دعم-تكنولوجيا-المعلومات', NULL, NULL, NULL, '2025-08-23 13:57:14', '2025-08-23 13:57:14'),
(117, 51, 59, 'Catering Service', 'catering-service', NULL, NULL, NULL, '2025-08-23 13:59:42', '2025-08-23 13:59:42'),
(118, 53, 59, 'خدمة تقديم الطعام', 'خدمة-تقديم-الطعام', NULL, NULL, NULL, '2025-08-23 13:59:42', '2025-08-23 13:59:42'),
(119, 51, 60, 'Venue Setup', 'venue-setup', NULL, NULL, NULL, '2025-08-23 14:02:48', '2025-08-23 14:02:48'),
(120, 53, 60, 'إعداد المكان', 'إعداد-المكان', NULL, NULL, NULL, '2025-08-23 14:02:48', '2025-08-23 14:02:48'),
(121, 51, 61, 'Award Ceremony', 'award-ceremony', NULL, NULL, NULL, '2025-08-23 14:42:55', '2025-08-23 14:42:55'),
(122, 53, 61, 'Award Ceremony', 'award-ceremony', NULL, NULL, NULL, '2025-08-23 14:42:55', '2025-08-23 14:42:55'),
(123, 51, 62, 'Motivational Speaker', 'motivational-speaker', NULL, NULL, NULL, '2025-08-23 14:43:30', '2025-08-23 14:43:30'),
(124, 53, 62, 'متحدث تحفيزي', 'متحدث-تحفيزي', NULL, NULL, NULL, '2025-08-23 14:43:30', '2025-08-23 14:43:30'),
(125, 51, 63, 'Event Management', 'event-management', NULL, NULL, NULL, '2025-08-23 14:44:08', '2025-08-23 14:44:08'),
(126, 53, 63, 'إدارة الفعاليات', 'إدارة-الفعاليات', NULL, NULL, NULL, '2025-08-23 14:44:08', '2025-08-23 14:44:08'),
(127, 51, 64, 'Catering Service', 'catering-service', NULL, NULL, NULL, '2025-08-23 14:47:29', '2025-08-23 14:47:29'),
(128, 53, 64, 'خدمة تقديم الطعام', 'خدمة-تقديم-الطعام', NULL, NULL, NULL, '2025-08-23 14:47:29', '2025-08-23 14:47:29'),
(129, 51, 65, 'Venue Setup', 'venue-setup', NULL, NULL, NULL, '2025-08-23 14:53:09', '2025-08-23 14:53:09'),
(130, 53, 65, 'إعداد المكان', 'إعداد-المكان', NULL, NULL, NULL, '2025-08-23 14:53:09', '2025-08-23 14:53:09'),
(131, 51, 66, 'DJ Service', 'dj-service', NULL, NULL, NULL, '2025-08-23 15:27:17', '2025-08-23 15:27:17'),
(132, 53, 66, 'خدمة الدي جي', 'خدمة-الدي-جي', NULL, NULL, NULL, '2025-08-23 15:27:17', '2025-08-23 15:27:17'),
(133, 51, 67, 'Photography & Videography', 'photography--videography', NULL, NULL, NULL, '2025-08-23 15:28:14', '2025-08-23 15:28:14'),
(134, 53, 67, 'التصوير الفوتوغرافي وتصوير الفيديو', 'التصوير-الفوتوغرافي-وتصوير-الفيديو', NULL, NULL, NULL, '2025-08-23 15:28:14', '2025-08-23 15:28:14'),
(135, 51, 68, 'Luxury Car Service', 'luxury-car-service', NULL, NULL, NULL, '2025-08-23 15:28:59', '2025-08-23 15:28:59'),
(136, 53, 68, 'خدمة السيارات الفاخرة', 'خدمة-السيارات-الفاخرة', NULL, NULL, NULL, '2025-08-23 15:28:59', '2025-08-23 15:28:59'),
(137, 51, 69, 'Catering Service', 'catering-service', NULL, NULL, NULL, '2025-08-23 15:31:19', '2025-08-23 15:31:19'),
(138, 53, 69, 'خدمة تقديم الطعام', 'خدمة-تقديم-الطعام', NULL, NULL, NULL, '2025-08-23 15:31:19', '2025-08-23 15:31:19'),
(139, 51, 70, 'Venue Decoration', 'venue-decoration', NULL, NULL, NULL, '2025-08-23 15:34:06', '2025-08-23 15:34:06'),
(140, 53, 70, NULL, '', NULL, NULL, NULL, '2025-08-23 15:34:06', '2025-08-23 15:34:06'),
(141, 51, 71, 'VIP Management', 'vip-management', NULL, NULL, NULL, '2025-08-24 07:35:38', '2025-08-24 07:35:38'),
(142, 53, 71, 'إدارة كبار الشخصيات', 'إدارة-كبار-الشخصيات', NULL, NULL, NULL, '2025-08-24 07:35:38', '2025-08-24 07:35:38'),
(143, 51, 72, 'Security Service', 'security-service', NULL, NULL, NULL, '2025-08-24 07:36:14', '2025-08-24 07:36:14'),
(144, 53, 72, 'خدمات الأمن', 'خدمات-الأمن', NULL, NULL, NULL, '2025-08-24 07:36:15', '2025-08-24 07:36:15'),
(145, 51, 73, 'Registration Desk', 'registration-desk', NULL, NULL, NULL, '2025-08-24 07:36:44', '2025-08-24 07:36:44'),
(146, 53, 73, 'مكتب التسجيل', 'مكتب-التسجيل', NULL, NULL, NULL, '2025-08-24 07:36:44', '2025-08-24 07:36:44'),
(161, 51, 81, 'Booth Design & Setup', 'booth-design--setup', NULL, NULL, NULL, '2025-08-24 08:34:30', '2025-08-24 08:34:30'),
(162, 53, 81, 'تصميم وتجهيز الكشك', 'تصميم-وتجهيز-الكشك', NULL, NULL, NULL, '2025-08-24 08:34:30', '2025-08-24 08:34:30'),
(163, 51, 82, 'Catering Service', 'catering-service', NULL, NULL, NULL, '2025-08-24 08:37:07', '2025-08-24 08:37:07'),
(164, 53, 82, 'خدمة تقديم الطعام', 'خدمة-تقديم-الطعام', NULL, NULL, NULL, '2025-08-24 08:37:07', '2025-08-24 08:37:07');

-- --------------------------------------------------------

--
-- Table structure for table `space_settings`
--

CREATE TABLE `space_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `seller_id` bigint UNSIGNED DEFAULT NULL,
  `fixed_time_slot_rental` tinyint DEFAULT NULL,
  `hourly_rental` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `space_sub_categories`
--

CREATE TABLE `space_sub_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `space_category_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `serial_number` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `space_sub_categories`
--

INSERT INTO `space_sub_categories` (`id`, `language_id`, `space_category_id`, `name`, `slug`, `status`, `serial_number`, `created_at`, `updated_at`) VALUES
(1, 51, 5, 'Kids Birthday Party', 'kids-birthday-party', 1, 1, '2025-08-20 15:34:35', '2025-08-20 15:34:35'),
(2, 53, 6, 'حفلة عيد ميلاد الاطفال', 'حفلة-عيد-ميلاد-الاطفال', 1, 1, '2025-08-20 15:35:29', '2025-08-25 13:51:22'),
(3, 51, 1, 'Engagement Party', 'engagement-party', 1, 2, '2025-08-20 15:36:41', '2025-08-20 15:36:41'),
(4, 53, 2, 'حفل خطوبة', 'حفل-خطوبة', 1, 2, '2025-08-20 15:37:20', '2025-08-25 13:51:04'),
(5, 51, 5, 'Bachelor Parties', 'bachelor-parties', 1, 3, '2025-08-23 08:30:40', '2025-08-23 08:30:40'),
(6, 53, 6, 'حفلات توديع العزوبية', 'حفلات-توديع-العزوبية', 1, 3, '2025-08-23 08:31:01', '2025-08-25 13:50:51');

-- --------------------------------------------------------

--
-- Table structure for table `space_wishlists`
--

CREATE TABLE `space_wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `space_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `country_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `language_id`, `country_id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 51, 1, 'California (CA)', 'california-(ca)', 1, '2025-08-17 08:51:13', '2025-08-17 08:51:13'),
(2, 53, 2, 'كاليفورنيا (CA)', 'كاليفورنيا-(ca)', 1, '2025-08-17 08:53:35', '2025-08-17 08:58:46'),
(3, 51, 1, 'Texas (TX)', 'texas-(tx)', 1, '2025-08-17 08:55:18', '2025-08-17 08:55:18'),
(4, 53, 2, 'تكساس (TX)', 'تكساس-(tx)', 1, '2025-08-17 08:56:10', '2025-08-17 08:56:10'),
(5, 51, 1, 'Florida (FL)', 'florida-(fl)', 1, '2025-08-17 08:56:59', '2025-08-17 08:56:59'),
(6, 53, 2, 'فلوريدا (FL)', 'فلوريدا-(fl)', 1, '2025-08-17 08:57:27', '2025-08-17 08:57:27'),
(7, 51, 1, 'New York (NY)', 'new-york-(ny)', 1, '2025-08-17 08:57:57', '2025-08-17 08:57:57'),
(8, 53, 2, 'نيويورك (نيويورك)', 'نيويورك-(نيويورك)', 1, '2025-08-17 08:58:22', '2025-08-17 08:58:22'),
(9, 51, 1, 'Illinois (IL)', 'illinois-(il)', 1, '2025-08-17 08:59:28', '2025-08-17 08:59:28'),
(10, 53, 2, 'إلينوي (IL)', 'إلينوي-(il)', 1, '2025-08-17 08:59:54', '2025-08-17 08:59:54'),
(11, 51, 3, 'Ontario (ON)', 'ontario-(on)', 1, '2025-08-20 14:44:15', '2025-08-20 14:44:15'),
(12, 53, 4, 'أونتاريو (ON)', 'أونتاريو-(on)', 1, '2025-08-20 14:45:01', '2025-08-24 10:12:44'),
(13, 51, 3, 'British Columbia (BC)', 'british-columbia-(bc)', 1, '2025-08-20 14:46:15', '2025-08-20 14:46:15'),
(14, 53, 4, 'كولومبيا البريطانية (BC)', 'كولومبيا-البريطانية-(bc)', 1, '2025-08-20 14:46:34', '2025-08-24 10:12:58'),
(15, 51, 3, 'Quebec (QC)', 'quebec-(qc)', 1, '2025-08-20 14:48:23', '2025-08-20 14:48:23'),
(16, 53, 4, 'كيبيك (كيبيك)', 'كيبيك-(كيبيك)', 1, '2025-08-20 14:48:32', '2025-08-24 10:13:13'),
(17, 51, 9, 'Berlin', 'berlin', 1, '2025-08-20 14:51:23', '2025-08-20 14:51:23'),
(18, 53, 10, 'برلين', 'برلين', 1, '2025-08-20 14:51:48', '2025-08-24 10:13:30'),
(19, 51, 9, 'Hamburg', 'hamburg', 1, '2025-08-20 14:52:19', '2025-08-20 14:52:19'),
(20, 53, 10, 'هامبورغ', 'هامبورغ', 1, '2025-08-20 14:52:28', '2025-08-24 10:13:56');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` bigint UNSIGNED NOT NULL,
  `email_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_services`
--

CREATE TABLE `sub_services` (
  `id` bigint UNSIGNED NOT NULL,
  `space_id` bigint DEFAULT NULL,
  `service_id` bigint DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `price_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `variations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_services`
--

INSERT INTO `sub_services` (`id`, `space_id`, `service_id`, `image`, `price`, `price_type`, `status`, `variations`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, '68a40c93a55ed.png', 20.00, 'fixed', 1, NULL, '2025-08-19 05:33:07', '2025-08-19 05:35:05'),
(2, NULL, 1, '68a40c93a7522.png', 30.00, 'fixed', 1, NULL, '2025-08-19 05:33:07', '2025-08-19 05:35:05'),
(3, NULL, 1, '68a40d735ce1f.png', 25.00, NULL, 1, NULL, '2025-08-19 05:35:05', '2025-08-19 05:36:51'),
(4, NULL, 1, '68a40dbedbfb9.png', 15.00, NULL, 1, NULL, '2025-08-19 05:38:06', '2025-08-19 06:04:02'),
(5, NULL, 1, '68a41471bdda8.png', 50.00, NULL, 1, NULL, '2025-08-19 06:06:41', '2025-08-19 06:06:41'),
(6, NULL, 1, '68a414e10645d.png', 5.00, NULL, 1, NULL, '2025-08-19 06:08:33', '2025-08-19 06:08:33'),
(7, NULL, 2, '68a41f3b9dcda.png', 10.00, 'per person', 1, NULL, '2025-08-19 06:52:43', '2025-08-19 06:55:08'),
(9, NULL, 2, '68a41fcc26d02.png', 30.00, NULL, 1, NULL, '2025-08-19 06:55:08', '2025-08-19 06:55:08'),
(10, NULL, 2, '68a42009a1a82.png', 10.00, NULL, 1, NULL, '2025-08-19 06:56:09', '2025-08-19 06:56:09'),
(11, NULL, 2, '68a4205154162.png', 12.00, NULL, 1, NULL, '2025-08-19 06:57:21', '2025-08-19 06:57:21'),
(12, NULL, 2, '68a4209e0cf2c.png', 20.00, NULL, 1, NULL, '2025-08-19 06:58:38', '2025-08-19 06:58:38'),
(13, NULL, 2, '68a420e2d7e5b.png', 30.00, NULL, 1, NULL, '2025-08-19 06:59:46', '2025-08-19 06:59:46'),
(14, NULL, 4, '68a4255dec8e9.png', 20.00, 'fixed', 1, NULL, '2025-08-19 07:18:53', '2025-08-19 07:20:04'),
(15, NULL, 4, '68a4255def2da.png', 25.00, 'fixed', 1, NULL, '2025-08-19 07:18:53', '2025-08-19 07:20:04'),
(16, NULL, 4, '68a4255df0bb8.png', 40.00, 'fixed', 1, NULL, '2025-08-19 07:18:53', '2025-08-19 07:20:04'),
(17, NULL, 9, '68a48659975e3.png', 20.00, 'fixed', 1, NULL, '2025-08-19 14:12:41', '2025-08-22 11:46:38'),
(18, NULL, 9, '68a4865999c37.png', 30.00, 'fixed', 1, NULL, '2025-08-19 14:12:41', '2025-08-22 11:46:38'),
(19, NULL, 9, '68a486599b289.png', 25.00, 'fixed', 1, NULL, '2025-08-19 14:12:41', '2025-08-22 11:46:38'),
(21, NULL, 9, '68a486599e58f.png', 50.00, 'fixed', 1, NULL, '2025-08-19 14:12:41', '2025-08-22 11:46:38'),
(22, NULL, 9, '68a486599fe64.png', 5.00, 'fixed', 1, NULL, '2025-08-19 14:12:41', '2025-08-22 11:46:38'),
(28, 2, 11, '68a4883d9736d.png', 10.00, 'per person', 1, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(29, 2, 11, '68a4883d98c40.png', 30.00, 'per person', 1, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(30, 2, 11, '68a4883d9a235.png', 10.00, 'per person', 1, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(31, 2, 11, '68a4883d9bd34.png', 12.00, 'per person', 1, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(32, 2, 11, '68a4883d9d644.png', 20.00, 'per person', 1, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(33, 2, 11, '68a4883d9f0cd.png', 30.00, 'per person', 1, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(34, NULL, 12, '68a48a4d0a6d8.png', 15.00, 'fixed', 1, NULL, '2025-08-19 14:29:33', '2025-08-19 15:17:45'),
(35, NULL, 12, '68a49599f3bf0.png', 5.00, NULL, 1, NULL, '2025-08-19 15:17:46', '2025-08-19 15:17:46'),
(36, NULL, 12, '68a4959a01b08.png', 15.00, NULL, 1, NULL, '2025-08-19 15:17:46', '2025-08-19 15:17:46'),
(37, NULL, 16, '68a7208039188.png', 10.00, 'per person', 1, NULL, '2025-08-21 13:34:56', '2025-08-21 13:35:37'),
(38, NULL, 16, '68a7235e7afda.jpg', 8.00, 'per person', 1, NULL, '2025-08-21 13:34:56', '2025-08-21 13:47:10'),
(39, NULL, 16, '68a7235e7c834.jpeg', 3.00, 'per person', 1, NULL, '2025-08-21 13:34:56', '2025-08-21 13:47:10'),
(40, NULL, 18, '68a732a0a64de.png', 5.00, 'per person', 1, NULL, '2025-08-21 14:52:16', '2025-08-21 14:55:26'),
(41, NULL, 18, '68a732a0a8503.png', 15.00, 'per person', 1, NULL, '2025-08-21 14:52:16', '2025-08-21 14:55:26'),
(42, NULL, 18, '68a732a0aa098.png', 10.00, 'per person', 1, NULL, '2025-08-21 14:52:16', '2025-08-21 14:55:26'),
(43, NULL, 18, '68a7335e66a2e.png', 30.00, NULL, 1, NULL, '2025-08-21 14:55:26', '2025-08-21 14:55:26'),
(44, NULL, 18, '68a7358a2c0de.png', 35.00, NULL, 1, NULL, '2025-08-21 15:04:42', '2025-08-21 15:04:42'),
(45, NULL, 18, '68a7358a2f1dc.webp', 25.00, NULL, 1, NULL, '2025-08-21 15:04:42', '2025-08-21 15:04:42'),
(46, NULL, 19, '68a82a7117a50.png', 30.00, NULL, 1, NULL, '2025-08-22 08:29:37', '2025-08-22 08:37:43'),
(47, NULL, 19, '68a82a711980d.png', 35.00, NULL, 1, NULL, '2025-08-22 08:29:37', '2025-08-22 08:37:43'),
(48, NULL, 19, '68a82c576e09c.png', 40.00, NULL, 1, NULL, '2025-08-22 08:37:43', '2025-08-22 08:37:43'),
(49, NULL, 20, '68a835e24e6a7.png', 10.00, NULL, 1, NULL, '2025-08-22 09:18:26', '2025-08-22 11:29:03'),
(50, NULL, 20, '68a835e251d03.png', 30.00, NULL, 1, NULL, '2025-08-22 09:18:26', '2025-08-22 11:29:03'),
(51, NULL, 20, '68a835e25398b.png', 10.00, NULL, 1, NULL, '2025-08-22 09:18:26', '2025-08-22 11:29:03'),
(52, NULL, 20, '68a835e255192.png', 12.00, NULL, 1, NULL, '2025-08-22 09:18:26', '2025-08-22 11:29:03'),
(53, NULL, 20, '68a835e256866.png', 20.00, NULL, 1, NULL, '2025-08-22 09:18:26', '2025-08-22 11:29:03'),
(54, NULL, 20, '68a835e25800e.png', 30.00, NULL, 1, NULL, '2025-08-22 09:18:26', '2025-08-22 11:29:03'),
(55, 5, 25, '68a860dd2ca85.png', 20.00, 'fixed', 1, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(56, 5, 25, '68a860dd2e82a.png', 30.00, 'fixed', 1, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(57, 5, 25, '68a860dd2fef1.png', 15.00, 'fixed', 1, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(58, 5, 25, '68a860dd31b7f.png', 35.00, 'fixed', 1, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(59, 5, 25, '68a860dd33477.png', 12.00, 'fixed', 1, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(60, 5, 25, '68a860dd34a88.png', 10.00, 'fixed', 1, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(61, 5, 26, '68a8620194b36.png', 10.00, 'per person', 1, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(62, 5, 26, '68a862019707b.png', 20.00, 'per person', 1, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(63, 5, 26, '68a86201987bf.png', 10.00, 'per person', 1, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(64, 5, 26, '68a8620199e83.png', 12.00, 'per person', 1, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(65, 5, 26, '68a862019bac5.png', 20.00, 'per person', 1, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(66, 5, 26, '68a862019d4bc.png', 20.00, 'per person', 1, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(67, 6, 30, '68a8706b0f7c3.png', 35.00, 'fixed', 1, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(68, 6, 30, '68a8706b11454.png', 30.00, 'fixed', 1, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(69, 6, 30, '68a8706b12a6d.png', 25.00, 'fixed', 1, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(70, 6, 30, '68a8706b14036.png', 20.00, 'fixed', 1, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(71, 6, 30, '68a8706b15bd1.png', 15.00, 'fixed', 1, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(72, 6, 30, '68a8706b172d4.png', 10.00, 'fixed', 1, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(73, 6, 31, '68a8722bc0ea9.png', 15.00, 'per person', 1, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(74, 6, 31, '68a8722bc32a7.png', 10.00, 'per person', 1, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(75, 6, 31, '68a8722bc4e14.png', 20.00, 'per person', 1, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(76, 6, 31, '68a8722bc64b5.png', 12.00, 'per person', 1, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(77, 6, 31, '68a8722bc7b7e.png', 15.00, 'per person', 1, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(78, 6, 31, '68a8722bc91f5.png', 8.00, 'per person', 1, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(79, 7, 38, '68a887c365ed6.png', 10.00, 'per person', 1, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(80, 7, 38, '68a887c3681eb.png', 30.00, 'per person', 1, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(81, 7, 38, '68a887c36997e.png', 10.00, 'per person', 1, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(82, 7, 38, '68a887c36b184.png', 12.00, 'per person', 1, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(83, 7, 38, '68a887c36c9de.png', 20.00, 'per person', 1, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(84, 7, 38, '68a887c36e0d2.png', 30.00, 'per person', 1, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(85, 7, 39, '68a888cc02732.png', 20.00, 'fixed', 1, NULL, '2025-08-22 15:12:12', '2025-08-22 15:12:12'),
(86, 7, 39, '68a888cc04cf1.png', 5.00, 'fixed', 1, NULL, '2025-08-22 15:12:12', '2025-08-22 15:12:12'),
(87, 7, 39, '68a888cc06809.png', 25.00, 'fixed', 1, NULL, '2025-08-22 15:12:12', '2025-08-22 15:12:12'),
(88, 8, 40, '68a8917b1ab5b.png', 20.00, 'fixed', 1, NULL, '2025-08-22 15:49:15', '2025-08-22 15:49:15'),
(89, 8, 40, '68a8917b1dd3b.png', 23.00, 'fixed', 1, NULL, '2025-08-22 15:49:15', '2025-08-22 15:49:15'),
(90, 8, 40, '68a8917b1f90d.png', 50.00, 'fixed', 1, NULL, '2025-08-22 15:49:15', '2025-08-22 15:49:15'),
(91, NULL, 41, '68a8925c0dc28.png', 20.00, 'per person', 1, NULL, '2025-08-22 15:53:00', '2025-08-22 15:54:35'),
(92, NULL, 41, '68a8925c1011b.png', 14.00, 'per person', 1, NULL, '2025-08-22 15:53:00', '2025-08-22 15:54:35'),
(93, NULL, 41, '68a8925c12733.png', 15.00, 'per person', 1, NULL, '2025-08-22 15:53:00', '2025-08-22 15:54:35'),
(94, NULL, 48, '68a9810ab51aa.png', 10.00, NULL, 1, NULL, '2025-08-23 08:51:22', '2025-08-23 08:56:55'),
(95, NULL, 48, '68a9810ab70b2.png', 10.00, NULL, 1, NULL, '2025-08-23 08:51:22', '2025-08-23 08:56:55'),
(96, NULL, 48, '68a9810ab876a.png', 30.00, NULL, 1, NULL, '2025-08-23 08:51:22', '2025-08-23 08:56:55'),
(97, NULL, 48, '68a9810ab9e55.png', 10.00, NULL, 1, NULL, '2025-08-23 08:51:22', '2025-08-23 08:56:55'),
(98, NULL, 48, '68a9810abb71c.png', 12.00, NULL, 1, NULL, '2025-08-23 08:51:22', '2025-08-23 08:56:55'),
(110, NULL, 48, '68a98291d66bd.png', 20.00, NULL, 1, NULL, '2025-08-23 08:57:53', '2025-08-23 08:57:53'),
(111, NULL, 51, '68beedc32ed36.png', 30.00, NULL, 1, NULL, '2025-08-23 09:19:11', '2025-09-08 14:52:51'),
(112, NULL, 51, '68beedc33083b.png', 25.00, NULL, 1, NULL, '2025-08-23 09:23:30', '2025-09-08 14:52:51'),
(113, NULL, 51, '68beedc333588.png', 40.00, NULL, 1, NULL, '2025-08-23 09:23:30', '2025-09-08 14:52:51'),
(114, NULL, 54, '68a9baabf1753.png', 5.00, NULL, 1, NULL, '2025-08-23 12:57:15', '2025-08-23 12:57:58'),
(115, NULL, 54, '68a9bad6a5f8c.png', 7.00, NULL, 1, NULL, '2025-08-23 12:57:58', '2025-08-23 12:57:58'),
(116, NULL, 54, '68a9bb1143e7c.png', 10.00, NULL, 1, NULL, '2025-08-23 12:58:57', '2025-08-23 12:58:57'),
(117, NULL, 55, '68beed2e88aae.png', 7.00, NULL, 1, NULL, '2025-08-23 13:10:32', '2025-09-08 14:50:22'),
(118, NULL, 55, '68beed2e8a6e2.png', 3.00, NULL, 1, NULL, '2025-08-23 13:10:32', '2025-09-08 14:50:22'),
(119, NULL, 55, '68beed2e8be7d.png', 10.00, NULL, 1, NULL, '2025-08-23 13:10:32', '2025-09-08 14:50:22'),
(120, 11, 59, '68a9c94e0cfaf.png', 5.00, NULL, 1, NULL, '2025-08-23 13:59:42', '2025-08-23 13:59:42'),
(121, 11, 59, '68a9c94e0ea81.png', 7.00, NULL, 1, NULL, '2025-08-23 13:59:42', '2025-08-23 13:59:42'),
(122, 11, 59, '68a9c94e102b3.png', 10.00, NULL, 1, NULL, '2025-08-23 13:59:42', '2025-08-23 13:59:42'),
(123, NULL, 60, '68beecad1e6a4.png', 50.00, NULL, 1, NULL, '2025-08-23 14:02:48', '2025-09-08 14:48:13'),
(124, NULL, 60, '68beecad200dd.png', 40.00, NULL, 1, NULL, '2025-08-23 14:02:48', '2025-09-08 14:48:13'),
(125, NULL, 60, '68beecad215ed.png', 45.00, NULL, 1, NULL, '2025-08-23 14:02:48', '2025-09-08 14:48:13'),
(126, 12, 64, '68a9d481ccbf3.png', 15.00, NULL, 1, NULL, '2025-08-23 14:47:29', '2025-08-23 14:47:29'),
(127, 12, 64, '68a9d481ce7b0.png', 10.00, NULL, 1, NULL, '2025-08-23 14:47:29', '2025-08-23 14:47:29'),
(128, 12, 64, '68a9d481cff84.png', 15.00, NULL, 1, NULL, '2025-08-23 14:47:29', '2025-08-23 14:47:29'),
(129, 12, 65, '68a9d5d5d2027.png', 90.00, NULL, 1, NULL, '2025-08-23 14:53:09', '2025-08-23 14:53:09'),
(130, 12, 65, '68a9d5d5d3aa6.png', 70.00, NULL, 1, NULL, '2025-08-23 14:53:09', '2025-08-23 14:53:09'),
(131, 12, 65, '68a9d5d5d5287.png', 20.00, NULL, 1, NULL, '2025-08-23 14:53:09', '2025-08-23 14:53:09'),
(132, 13, 69, '68a9dec7e8900.png', 10.00, 'per person', 1, NULL, '2025-08-23 15:31:19', '2025-08-23 15:31:19'),
(133, 13, 69, '68a9dec7eadcc.png', 12.00, 'per person', 1, NULL, '2025-08-23 15:31:19', '2025-08-23 15:31:19'),
(134, 13, 69, '68a9dec7ec673.png', 20.00, 'per person', 1, NULL, '2025-08-23 15:31:19', '2025-08-23 15:31:19'),
(135, 13, 70, '68a9df6e89501.png', 20.00, 'fixed', 1, NULL, '2025-08-23 15:34:06', '2025-08-23 15:34:06'),
(136, 13, 70, '68a9df6e8b911.png', 50.00, 'fixed', 1, NULL, '2025-08-23 15:34:06', '2025-08-23 15:34:06'),
(137, 13, 70, '68a9df6e8d385.png', 25.00, 'fixed', 1, NULL, '2025-08-23 15:34:06', '2025-08-23 15:34:06'),
(143, 14, 81, '68aace964fde7.png', 30.00, NULL, 1, NULL, '2025-08-24 08:34:30', '2025-08-24 08:34:30'),
(144, 14, 81, '68aace9651b99.png', 35.00, NULL, 1, NULL, '2025-08-24 08:34:30', '2025-08-24 08:34:30'),
(145, 14, 81, '68aace965368c.png', 40.00, NULL, 1, NULL, '2025-08-24 08:34:30', '2025-08-24 08:34:30'),
(146, 14, 82, '68aacf339d129.png', 10.00, NULL, 1, NULL, '2025-08-24 08:37:07', '2025-08-24 08:37:07'),
(147, 14, 82, '68aacf339f011.png', 10.00, NULL, 1, NULL, '2025-08-24 08:37:07', '2025-08-24 08:37:07'),
(148, 14, 82, '68aacf33a09aa.png', 12.00, NULL, 1, NULL, '2025-08-24 08:37:07', '2025-08-24 08:37:07');

-- --------------------------------------------------------

--
-- Table structure for table `sub_service_contents`
--

CREATE TABLE `sub_service_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `sub_service_id` bigint DEFAULT NULL,
  `language_id` tinyint DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `indx` int DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_service_contents`
--

INSERT INTO `sub_service_contents` (`id`, `sub_service_id`, `language_id`, `title`, `slug`, `indx`, `description`, `meta_keywords`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 1, 51, 'Stage Decoration', 'stage-decoration', NULL, NULL, NULL, NULL, '2025-08-19 05:33:07', '2025-08-19 05:33:07'),
(2, 1, 53, 'ديكور المسرح', 'ديكور-المسرح', NULL, NULL, NULL, NULL, '2025-08-19 05:33:07', '2025-08-19 05:33:07'),
(3, 2, 51, 'Floral Arrangement', 'floral-arrangement', NULL, NULL, NULL, NULL, '2025-08-19 05:33:07', '2025-08-19 05:33:07'),
(4, 2, 53, 'تنسيق الزهور', 'تنسيق-الزهور', NULL, NULL, NULL, NULL, '2025-08-19 05:33:07', '2025-08-19 05:33:07'),
(5, 3, 51, 'Lighting Setup', 'lighting-setup', NULL, NULL, NULL, NULL, '2025-08-19 05:35:05', '2025-08-19 05:35:05'),
(6, 3, 53, 'إعداد الإضاءة', 'إعداد-الإضاءة', NULL, NULL, NULL, NULL, '2025-08-19 05:35:05', '2025-08-19 05:35:05'),
(7, 4, 51, 'Entry Gate Design', 'entry-gate-design', NULL, NULL, NULL, NULL, '2025-08-19 05:38:06', '2025-08-19 05:38:06'),
(8, 4, 53, 'تصميم بوابة الدخول', 'تصميم-بوابة-الدخول', NULL, NULL, NULL, NULL, '2025-08-19 05:38:06', '2025-08-19 05:38:06'),
(9, 5, 51, 'Table Centerpieces', 'table-centerpieces', NULL, NULL, NULL, NULL, '2025-08-19 06:06:41', '2025-08-19 06:06:41'),
(10, 5, 53, 'قطع مركزية للطاولة', 'قطع-مركزية-للطاولة', NULL, NULL, NULL, NULL, '2025-08-19 06:06:41', '2025-08-19 06:06:41'),
(11, 6, 51, 'Backdrop Design', 'backdrop-design', NULL, NULL, NULL, NULL, '2025-08-19 06:08:33', '2025-08-19 06:08:33'),
(12, 6, 53, 'تصميم الخلفية', 'تصميم-الخلفية', NULL, NULL, NULL, NULL, '2025-08-19 06:08:33', '2025-08-19 06:08:33'),
(13, 7, 51, 'Buffet Dinner', 'buffet-dinner', NULL, NULL, NULL, NULL, '2025-08-19 06:52:43', '2025-08-19 06:52:43'),
(14, 7, 53, 'بوفيه عشاء', 'بوفيه-عشاء', NULL, NULL, NULL, NULL, '2025-08-19 06:52:43', '2025-08-19 06:52:43'),
(17, 9, 51, 'Live Food Station', 'live-food-station', NULL, NULL, NULL, NULL, '2025-08-19 06:55:08', '2025-08-19 06:55:08'),
(18, 9, 53, 'محطة الطعام الحي', 'محطة-الطعام-الحي', NULL, NULL, NULL, NULL, '2025-08-19 06:55:08', '2025-08-19 06:55:08'),
(19, 10, 51, 'Dessert Corner', 'dessert-corner', NULL, NULL, NULL, NULL, '2025-08-19 06:56:09', '2025-08-19 06:56:09'),
(20, 10, 53, 'ركن الحلويات', 'ركن-الحلويات', NULL, NULL, NULL, NULL, '2025-08-19 06:56:09', '2025-08-19 06:56:09'),
(21, 11, 51, 'Beverage Service', 'beverage-service', NULL, NULL, NULL, NULL, '2025-08-19 06:57:21', '2025-08-19 06:57:21'),
(22, 11, 53, 'خدمة المشروبات', 'خدمة-المشروبات', NULL, NULL, NULL, NULL, '2025-08-19 06:57:21', '2025-08-19 06:57:21'),
(23, 12, 51, 'Traditional Menu', 'traditional-menu', NULL, NULL, NULL, NULL, '2025-08-19 06:58:38', '2025-08-19 06:58:38'),
(24, 12, 53, 'القائمة التقليدية', 'القائمة-التقليدية', NULL, NULL, NULL, NULL, '2025-08-19 06:58:38', '2025-08-19 06:58:38'),
(25, 13, 51, 'Customized Menu', 'customized-menu', NULL, NULL, NULL, NULL, '2025-08-19 06:59:46', '2025-08-19 06:59:46'),
(26, 13, 53, 'قائمة مخصصة', 'قائمة-مخصصة', NULL, NULL, NULL, NULL, '2025-08-19 06:59:46', '2025-08-19 06:59:46'),
(27, 14, 51, 'Pre-wedding Shoot', 'pre-wedding-shoot', NULL, NULL, NULL, NULL, '2025-08-19 07:18:53', '2025-08-19 07:18:53'),
(28, 14, 53, 'جلسة تصوير ما قبل الزفاف', 'جلسة-تصوير-ما-قبل-الزفاف', NULL, NULL, NULL, NULL, '2025-08-19 07:18:53', '2025-08-19 07:18:53'),
(29, 15, 51, 'Wedding Highlights Video', 'wedding-highlights-video', NULL, NULL, NULL, NULL, '2025-08-19 07:18:53', '2025-08-19 07:18:53'),
(30, 15, 53, 'فيديو لأبرز أحداث حفل الزفاف', 'فيديو-لأبرز-أحداث-حفل-الزفاف', NULL, NULL, NULL, NULL, '2025-08-19 07:18:53', '2025-08-19 07:18:53'),
(31, 16, 51, 'Drone Coverage', 'drone-coverage', NULL, NULL, NULL, NULL, '2025-08-19 07:18:53', '2025-08-19 07:18:53'),
(32, 16, 53, 'تغطية الطائرات بدون طيار', 'تغطية-الطائرات-بدون-طيار', NULL, NULL, NULL, NULL, '2025-08-19 07:18:53', '2025-08-19 07:18:53'),
(33, 17, 51, 'Stage Decoration', 'stage-decoration', NULL, NULL, NULL, NULL, '2025-08-19 14:12:41', '2025-08-19 14:12:41'),
(34, 17, 53, 'ديكور المسرح', 'ديكور-المسرح', NULL, NULL, NULL, NULL, '2025-08-19 14:12:41', '2025-08-19 14:12:41'),
(35, 18, 51, 'Floral Arrangement', 'floral-arrangement', NULL, NULL, NULL, NULL, '2025-08-19 14:12:41', '2025-08-19 14:12:41'),
(36, 18, 53, 'تنسيق الزهور', 'تنسيق-الزهور', NULL, NULL, NULL, NULL, '2025-08-19 14:12:41', '2025-08-19 14:12:41'),
(37, 19, 51, 'Lighting Setup', 'lighting-setup', NULL, NULL, NULL, NULL, '2025-08-19 14:12:41', '2025-08-19 14:12:41'),
(38, 19, 53, 'إعداد الإضاءة', 'إعداد-الإضاءة', NULL, NULL, NULL, NULL, '2025-08-19 14:12:41', '2025-08-19 14:12:41'),
(41, 21, 51, 'Table Centerpieces', 'table-centerpieces', NULL, NULL, NULL, NULL, '2025-08-19 14:12:41', '2025-08-19 14:12:41'),
(42, 21, 53, 'قطع مركزية للطاولة', 'قطع-مركزية-للطاولة', NULL, NULL, NULL, NULL, '2025-08-19 14:12:41', '2025-08-19 14:12:41'),
(43, 22, 51, 'Backdrop Design', 'backdrop-design', NULL, NULL, NULL, NULL, '2025-08-19 14:12:41', '2025-08-19 14:12:41'),
(44, 22, 53, 'تصميم الخلفية', 'تصميم-الخلفية', NULL, NULL, NULL, NULL, '2025-08-19 14:12:41', '2025-08-19 14:12:41'),
(55, 28, 51, 'Buffet Dinner', 'buffet-dinner', NULL, NULL, NULL, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(56, 28, 53, 'بوفيه عشاء', 'بوفيه-عشاء', NULL, NULL, NULL, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(57, 29, 51, 'Live Food Station', 'live-food-station', NULL, NULL, NULL, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(58, 29, 53, 'محطة الطعام الحي', 'محطة-الطعام-الحي', NULL, NULL, NULL, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(59, 30, 51, 'Dessert Corner', 'dessert-corner', NULL, NULL, NULL, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(60, 30, 53, 'ركن الحلويات', 'ركن-الحلويات', NULL, NULL, NULL, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(61, 31, 51, 'Beverage Service', 'beverage-service', NULL, NULL, NULL, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(62, 31, 53, 'خدمة المشروبات', 'خدمة-المشروبات', NULL, NULL, NULL, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(63, 32, 51, 'Traditional Menu', 'traditional-menu', NULL, NULL, NULL, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(64, 32, 53, 'القائمة التقليدية', 'القائمة-التقليدية', NULL, NULL, NULL, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(65, 33, 51, 'Customized Menu', 'customized-menu', NULL, NULL, NULL, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(66, 33, 53, 'قائمة مخصصة', 'قائمة-مخصصة', NULL, NULL, NULL, NULL, '2025-08-19 14:20:45', '2025-08-19 14:20:45'),
(67, 34, 51, 'Makeup Artist', 'makeup-artist', NULL, NULL, NULL, NULL, '2025-08-19 14:29:33', '2025-08-19 14:29:33'),
(68, 34, 53, 'فنانة مكياج', 'فنانة-مكياج', NULL, NULL, NULL, NULL, '2025-08-19 14:29:33', '2025-08-19 14:29:33'),
(69, 35, 51, 'Mehendi Artist', 'mehendi-artist', NULL, NULL, NULL, NULL, '2025-08-19 15:17:46', '2025-08-19 15:17:46'),
(70, 35, 53, 'فنان الحناء', 'فنان-الحناء', NULL, NULL, NULL, NULL, '2025-08-19 15:17:46', '2025-08-19 15:17:46'),
(71, 36, 51, 'Hairstyling', 'hairstyling', NULL, NULL, NULL, NULL, '2025-08-19 15:17:46', '2025-08-19 15:17:46'),
(72, 36, 53, 'تصفيف الشعر', 'تصفيف-الشعر', NULL, NULL, NULL, NULL, '2025-08-19 15:17:46', '2025-08-19 15:17:46'),
(73, 37, 51, 'Birthday Cake', 'birthday-cake', NULL, NULL, NULL, NULL, '2025-08-21 13:34:56', '2025-08-21 13:34:56'),
(74, 37, 53, 'كعكة عيد ميلاد', 'كعكة-عيد-ميلاد', NULL, NULL, NULL, NULL, '2025-08-21 13:34:56', '2025-08-21 13:34:56'),
(75, 38, 51, 'Snacks Corner', 'snacks-corner', NULL, NULL, NULL, NULL, '2025-08-21 13:34:56', '2025-08-21 13:34:56'),
(76, 38, 53, 'ركن الوجبات الخفيفة', 'ركن-الوجبات-الخفيفة', NULL, NULL, NULL, NULL, '2025-08-21 13:34:56', '2025-08-21 13:34:56'),
(77, 39, 51, 'Cheeseburgers', 'cheeseburgers', NULL, NULL, NULL, NULL, '2025-08-21 13:34:56', '2025-08-21 13:44:31'),
(78, 39, 53, 'برجر بالجبن', 'برجر-بالجبن', NULL, NULL, NULL, NULL, '2025-08-21 13:34:56', '2025-08-21 13:44:31'),
(79, 40, 51, 'Goodie Bags', 'goodie-bags', NULL, NULL, NULL, NULL, '2025-08-21 14:52:16', '2025-08-21 14:52:16'),
(80, 40, 53, 'حقائب الهدايا', 'حقائب-الهدايا', NULL, NULL, NULL, NULL, '2025-08-21 14:52:16', '2025-08-21 14:52:16'),
(81, 41, 51, 'Chocolate Box', 'chocolate-box', NULL, NULL, NULL, NULL, '2025-08-21 14:52:16', '2025-08-21 14:52:16'),
(82, 41, 53, 'صندوق الشوكولاتة', 'صندوق-الشوكولاتة', NULL, NULL, NULL, NULL, '2025-08-21 14:52:16', '2025-08-21 14:52:16'),
(83, 42, 51, 'Customized Mugs', 'customized-mugs', NULL, NULL, NULL, NULL, '2025-08-21 14:52:16', '2025-08-21 14:52:16'),
(84, 42, 53, 'أكواب مخصصة', 'أكواب-مخصصة', NULL, NULL, NULL, NULL, '2025-08-21 14:52:16', '2025-08-21 14:52:16'),
(85, 43, 51, 'Personalized Gifts', 'personalized-gifts', NULL, NULL, NULL, NULL, '2025-08-21 14:55:26', '2025-08-21 14:55:26'),
(86, 43, 53, 'هدايا شخصية', 'هدايا-شخصية', NULL, NULL, NULL, NULL, '2025-08-21 14:55:26', '2025-08-21 14:55:26'),
(87, 44, 51, 'Toys & Games', 'toys--games', NULL, NULL, NULL, NULL, '2025-08-21 15:04:42', '2025-08-21 15:04:42'),
(88, 44, 53, 'الألعاب والألعاب', 'الألعاب-والألعاب', NULL, NULL, NULL, NULL, '2025-08-21 15:04:42', '2025-08-21 15:04:42'),
(89, 45, 51, 'Gift Basket', 'gift-basket', NULL, NULL, NULL, NULL, '2025-08-21 15:04:42', '2025-08-21 15:04:42'),
(90, 45, 53, 'سلة الهدايا', 'سلة-الهدايا', NULL, NULL, NULL, NULL, '2025-08-21 15:04:42', '2025-08-21 15:04:42'),
(91, 46, 51, 'Modular Booth', 'modular-booth', NULL, NULL, NULL, NULL, '2025-08-22 08:29:37', '2025-08-22 08:29:37'),
(92, 46, 53, 'كشك معياري', 'كشك-معياري', NULL, NULL, NULL, NULL, '2025-08-22 08:29:37', '2025-08-22 08:29:37'),
(93, 47, 51, 'Display Counters', 'display-counters', NULL, NULL, NULL, NULL, '2025-08-22 08:29:37', '2025-08-22 08:29:37'),
(94, 47, 53, 'عدادات العرض', 'عدادات-العرض', NULL, NULL, NULL, NULL, '2025-08-22 08:29:37', '2025-08-22 08:29:37'),
(95, 48, 51, 'Pop-up Display', 'pop-up-display', NULL, NULL, NULL, NULL, '2025-08-22 08:37:43', '2025-08-22 08:37:43'),
(96, 48, 53, 'شاشة العرض المنبثقة', 'شاشة-العرض-المنبثقة', NULL, NULL, NULL, NULL, '2025-08-22 08:37:43', '2025-08-22 08:37:43'),
(97, 49, 51, 'Buffet Dinner', 'buffet-dinner', NULL, NULL, NULL, NULL, '2025-08-22 09:18:26', '2025-08-22 09:18:26'),
(98, 49, 53, 'بوفيه عشاء', 'بوفيه-عشاء', NULL, NULL, NULL, NULL, '2025-08-22 09:18:26', '2025-08-22 09:18:26'),
(99, 50, 51, 'Live Food Station', 'live-food-station', NULL, NULL, NULL, NULL, '2025-08-22 09:18:26', '2025-08-22 09:18:26'),
(100, 50, 53, 'محطة الطعام الحي', 'محطة-الطعام-الحي', NULL, NULL, NULL, NULL, '2025-08-22 09:18:26', '2025-08-22 09:18:26'),
(101, 51, 51, 'Dessert Corner', 'dessert-corner', NULL, NULL, NULL, NULL, '2025-08-22 09:18:26', '2025-08-22 09:18:26'),
(102, 51, 53, 'ركن الحلويات', 'ركن-الحلويات', NULL, NULL, NULL, NULL, '2025-08-22 09:18:26', '2025-08-22 09:18:26'),
(103, 52, 51, 'Beverage Service', 'beverage-service', NULL, NULL, NULL, NULL, '2025-08-22 09:18:26', '2025-08-22 09:18:26'),
(104, 52, 53, 'خدمة المشروبات', 'خدمة-المشروبات', NULL, NULL, NULL, NULL, '2025-08-22 09:18:26', '2025-08-22 09:18:26'),
(105, 53, 51, 'Traditional Menu', 'traditional-menu', NULL, NULL, NULL, NULL, '2025-08-22 09:18:26', '2025-08-22 09:18:26'),
(106, 53, 53, 'القائمة التقليدية', 'القائمة-التقليدية', NULL, NULL, NULL, NULL, '2025-08-22 09:18:26', '2025-08-22 09:18:26'),
(107, 54, 51, 'Customized Menu', 'customized-menu', NULL, NULL, NULL, NULL, '2025-08-22 09:18:26', '2025-08-22 09:18:26'),
(108, 54, 53, 'قائمة مخصصة', 'قائمة-مخصصة', NULL, NULL, NULL, NULL, '2025-08-22 09:18:26', '2025-08-22 09:18:26'),
(109, 55, 51, 'Floral Setup', 'floral-setup', NULL, NULL, NULL, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(110, 55, 53, 'إعداد الأزهار', 'إعداد-الأزهار', NULL, NULL, NULL, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(111, 56, 51, 'Stage Decoration', 'stage-decoration', NULL, NULL, NULL, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(112, 56, 53, 'ديكور المسرح', 'ديكور-المسرح', NULL, NULL, NULL, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(113, 57, 51, 'Gate Decoration', 'gate-decoration', NULL, NULL, NULL, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(114, 57, 53, 'ديكور البوابة', 'ديكور-البوابة', NULL, NULL, NULL, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(115, 58, 51, 'Lighting Setup', 'lighting-setup', NULL, NULL, NULL, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(116, 58, 53, 'إعداد الإضاءة', 'إعداد-الإضاءة', NULL, NULL, NULL, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(117, 59, 51, 'Traditional Backdrop', 'traditional-backdrop', NULL, NULL, NULL, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(118, 59, 53, 'خلفية تقليدية', 'خلفية-تقليدية', NULL, NULL, NULL, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(119, 60, 51, 'Seating Arrangement', 'seating-arrangement', NULL, NULL, NULL, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(120, 60, 53, 'ترتيب الجلوس', 'ترتيب-الجلوس', NULL, NULL, NULL, NULL, '2025-08-22 12:21:49', '2025-08-22 12:21:49'),
(121, 61, 51, 'Buffet Dinner', 'buffet-dinner', NULL, NULL, NULL, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(122, 61, 53, 'بوفيه عشاء', 'بوفيه-عشاء', NULL, NULL, NULL, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(123, 62, 51, 'Live Food Station', 'live-food-station', NULL, NULL, NULL, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(124, 62, 53, 'محطة الطعام الحي', 'محطة-الطعام-الحي', NULL, NULL, NULL, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(125, 63, 51, 'Dessert Corner', 'dessert-corner', NULL, NULL, NULL, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(126, 63, 53, 'ركن الحلويات', 'ركن-الحلويات', NULL, NULL, NULL, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(127, 64, 51, 'Beverage Service', 'beverage-service', NULL, NULL, NULL, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(128, 64, 53, 'خدمة المشروبات', 'خدمة-المشروبات', NULL, NULL, NULL, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(129, 65, 51, 'Traditional Menu', 'traditional-menu', NULL, NULL, NULL, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(130, 65, 53, 'القائمة التقليدية', 'القائمة-التقليدية', NULL, NULL, NULL, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(131, 66, 51, 'Customized Menu', 'customized-menu', NULL, NULL, NULL, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(132, 66, 53, 'قائمة مخصصة', 'قائمة-مخصصة', NULL, NULL, NULL, NULL, '2025-08-22 12:26:41', '2025-08-22 12:26:41'),
(133, 67, 51, 'Classroom Style Setup', 'classroom-style-setup', NULL, NULL, NULL, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(134, 67, 53, 'إعداد نمط الفصل الدراسي', 'إعداد-نمط-الفصل-الدراسي', NULL, NULL, NULL, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(135, 68, 51, 'Theater Style Setup', 'theater-style-setup', NULL, NULL, NULL, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(136, 68, 53, 'إعداد على طراز المسرح', 'إعداد-على-طراز-المسرح', NULL, NULL, NULL, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(137, 69, 51, 'Round Table Setup', 'round-table-setup', NULL, NULL, NULL, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(138, 69, 53, 'إعداد الطاولة المستديرة', 'إعداد-الطاولة-المستديرة', NULL, NULL, NULL, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(139, 70, 51, 'Stage with Screen', 'stage-with-screen', NULL, NULL, NULL, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(140, 70, 53, 'المسرح مع الشاشة', 'المسرح-مع-الشاشة', NULL, NULL, NULL, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(141, 71, 51, 'Lighting Setup', 'lighting-setup', NULL, NULL, NULL, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(142, 71, 53, 'إعداد الإضاءة', 'إعداد-الإضاءة', NULL, NULL, NULL, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(143, 72, 51, 'Whiteboard', 'whiteboard', NULL, NULL, NULL, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(144, 72, 53, 'السبورة البيضاء', 'السبورة-البيضاء', NULL, NULL, NULL, NULL, '2025-08-22 13:28:11', '2025-08-22 13:28:11'),
(145, 73, 51, 'Coffee & Snacks', 'coffee--snacks', NULL, NULL, NULL, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(146, 73, 53, 'القهوة والوجبات الخفيفة', 'القهوة-والوجبات-الخفيفة', NULL, NULL, NULL, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(147, 74, 51, 'Breakfast Box', 'breakfast-box', NULL, NULL, NULL, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(148, 74, 53, 'صندوق الإفطار', 'صندوق-الإفطار', NULL, NULL, NULL, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(149, 75, 51, 'Lunch Buffet', 'lunch-buffet', NULL, NULL, NULL, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(150, 75, 53, 'بوفيه غداء', 'بوفيه-غداء', NULL, NULL, NULL, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(151, 76, 51, 'Tea Counter', 'tea-counter', NULL, NULL, NULL, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(152, 76, 53, 'منضدة الشاي', 'منضدة-الشاي', NULL, NULL, NULL, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(153, 77, 51, 'Juice Station', 'juice-station', NULL, NULL, NULL, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(154, 77, 53, 'محطة العصير', 'محطة-العصير', NULL, NULL, NULL, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(155, 78, 51, 'Dessert Corner', 'dessert-corner', NULL, NULL, NULL, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(156, 78, 53, 'ركن الحلويات', 'ركن-الحلويات', NULL, NULL, NULL, NULL, '2025-08-22 13:35:39', '2025-08-22 13:35:39'),
(157, 79, 51, 'Buffet Dinner', 'buffet-dinner', NULL, NULL, NULL, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(158, 79, 53, 'بوفيه عشاء', 'بوفيه-عشاء', NULL, NULL, NULL, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(159, 80, 51, 'Live Food Station', 'live-food-station', NULL, NULL, NULL, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(160, 80, 53, 'محطة الطعام الحي', 'محطة-الطعام-الحي', NULL, NULL, NULL, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(161, 81, 51, 'Dessert Corner', 'dessert-corner', NULL, NULL, NULL, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(162, 81, 53, 'ركن الحلويات', 'ركن-الحلويات', NULL, NULL, NULL, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(163, 82, 51, 'Beverage Service', 'beverage-service', NULL, NULL, NULL, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(164, 82, 53, 'خدمة المشروبات', 'خدمة-المشروبات', NULL, NULL, NULL, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(165, 83, 51, 'Traditional Menu', 'traditional-menu', NULL, NULL, NULL, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(166, 83, 53, 'القائمة التقليدية', 'القائمة-التقليدية', NULL, NULL, NULL, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(167, 84, 51, 'Customized Menu', 'customized-menu', NULL, NULL, NULL, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(168, 84, 53, 'قائمة مخصصة', 'قائمة-مخصصة', NULL, NULL, NULL, NULL, '2025-08-22 15:07:47', '2025-08-22 15:07:47'),
(169, 85, 51, 'Stage Decoration', 'stage-decoration', NULL, NULL, NULL, NULL, '2025-08-22 15:12:12', '2025-08-22 15:12:12'),
(170, 85, 53, 'ديكور المسرح', 'ديكور-المسرح', NULL, NULL, NULL, NULL, '2025-08-22 15:12:12', '2025-08-22 15:12:12'),
(171, 86, 51, 'Table Centerpieces', 'table-centerpieces', NULL, NULL, NULL, NULL, '2025-08-22 15:12:12', '2025-08-22 15:12:12'),
(172, 86, 53, 'قطع مركزية للطاولة', 'قطع-مركزية-للطاولة', NULL, NULL, NULL, NULL, '2025-08-22 15:12:12', '2025-08-22 15:12:12'),
(173, 87, 51, 'Lighting Setup', 'lighting-setup', NULL, NULL, NULL, NULL, '2025-08-22 15:12:12', '2025-08-22 15:12:12'),
(174, 87, 53, 'إعداد الإضاءة', 'إعداد-الإضاءة', NULL, NULL, NULL, NULL, '2025-08-22 15:12:12', '2025-08-22 15:12:12'),
(175, 88, 51, 'Stage Setup', 'stage-setup', NULL, NULL, NULL, NULL, '2025-08-22 15:49:15', '2025-08-22 15:49:15'),
(176, 88, 53, 'إعداد المسرح', 'إعداد-المسرح', NULL, NULL, NULL, NULL, '2025-08-22 15:49:15', '2025-08-22 15:49:15'),
(177, 89, 51, 'Seating Arrangement', 'seating-arrangement', NULL, NULL, NULL, NULL, '2025-08-22 15:49:15', '2025-08-22 15:49:15'),
(178, 89, 53, 'ترتيب الجلوس', 'ترتيب-الجلوس', NULL, NULL, NULL, NULL, '2025-08-22 15:49:15', '2025-08-22 15:49:15'),
(179, 90, 51, 'VIP Lounge Setup', 'vip-lounge-setup', NULL, NULL, NULL, NULL, '2025-08-22 15:49:15', '2025-08-22 15:49:15'),
(180, 90, 53, 'إعداد صالة كبار الشخصيات', 'إعداد-صالة-كبار-الشخصيات', NULL, NULL, NULL, NULL, '2025-08-22 15:49:15', '2025-08-22 15:49:15'),
(181, 91, 51, 'Breakfast Package', 'breakfast-package', NULL, NULL, NULL, NULL, '2025-08-22 15:53:00', '2025-08-22 15:53:00'),
(182, 91, 53, 'باقة الإفطار', 'باقة-الإفطار', NULL, NULL, NULL, NULL, '2025-08-22 15:53:00', '2025-08-22 15:53:00'),
(183, 92, 51, 'Lunch Buffet', 'lunch-buffet', NULL, NULL, NULL, NULL, '2025-08-22 15:53:00', '2025-08-22 15:53:00'),
(184, 92, 53, 'بوفيه غداء', 'بوفيه-غداء', NULL, NULL, NULL, NULL, '2025-08-22 15:53:00', '2025-08-22 15:53:00'),
(185, 93, 51, 'Cocktail Service', 'cocktail-service', NULL, NULL, NULL, NULL, '2025-08-22 15:53:00', '2025-08-22 15:53:00'),
(186, 93, 53, 'خدمة الكوكتيل', 'خدمة-الكوكتيل', NULL, NULL, NULL, NULL, '2025-08-22 15:53:00', '2025-08-22 15:53:00'),
(187, 94, 51, 'Party Cake', 'party-cake', NULL, NULL, NULL, NULL, '2025-08-23 08:51:22', '2025-08-23 08:51:22'),
(188, 94, 53, 'كعكة الحفلة', 'كعكة-الحفلة', NULL, NULL, NULL, NULL, '2025-08-23 08:51:22', '2025-08-23 08:51:22'),
(189, 95, 51, 'Buffet Dinner', 'buffet-dinner', NULL, NULL, NULL, NULL, '2025-08-23 08:51:22', '2025-08-23 08:51:22'),
(190, 95, 53, 'بوفيه عشاء', 'بوفيه-عشاء', NULL, NULL, NULL, NULL, '2025-08-23 08:51:22', '2025-08-23 08:51:22'),
(191, 96, 51, 'Live Food Station', 'live-food-station', NULL, NULL, NULL, NULL, '2025-08-23 08:51:22', '2025-08-23 08:51:22'),
(192, 96, 53, 'محطة الطعام الحي', 'محطة-الطعام-الحي', NULL, NULL, NULL, NULL, '2025-08-23 08:51:22', '2025-08-23 08:51:22'),
(193, 97, 51, 'Dessert Corner', 'dessert-corner', NULL, NULL, NULL, NULL, '2025-08-23 08:51:22', '2025-08-23 08:51:22'),
(194, 97, 53, 'ركن الحلويات', 'ركن-الحلويات', NULL, NULL, NULL, NULL, '2025-08-23 08:51:22', '2025-08-23 08:51:22'),
(195, 98, 51, 'Beverage Service', 'beverage-service', NULL, NULL, NULL, NULL, '2025-08-23 08:51:22', '2025-08-23 08:51:22'),
(196, 98, 53, 'خدمة المشروبات', 'خدمة-المشروبات', NULL, NULL, NULL, NULL, '2025-08-23 08:51:22', '2025-08-23 08:51:22'),
(219, 110, 51, 'Traditional Menu', 'traditional-menu', NULL, NULL, NULL, NULL, '2025-08-23 08:57:53', '2025-08-23 08:57:53'),
(220, 110, 53, 'القائمة التقليدية', 'القائمة-التقليدية', NULL, NULL, NULL, NULL, '2025-08-23 08:57:53', '2025-08-23 08:57:53'),
(221, 111, 51, 'Rooftop Lounge', 'rooftop-lounge', NULL, NULL, NULL, NULL, '2025-08-23 09:19:11', '2025-08-23 09:19:11'),
(222, 111, 53, 'صالة السطح', 'صالة-السطح', NULL, NULL, NULL, NULL, '2025-08-23 09:19:11', '2025-08-23 09:19:11'),
(223, 112, 51, 'Lighthouse', 'lighthouse', NULL, NULL, NULL, NULL, '2025-08-23 09:23:30', '2025-08-23 09:23:30'),
(224, 112, 53, 'منارة', 'منارة', NULL, NULL, NULL, NULL, '2025-08-23 09:23:30', '2025-08-23 09:23:30'),
(225, 113, 51, 'Private Dining', 'private-dining', NULL, NULL, NULL, NULL, '2025-08-23 09:23:30', '2025-08-23 10:59:37'),
(226, 113, 53, 'غرفة طعام خاصة', 'غرفة-طعام-خاصة', NULL, NULL, NULL, NULL, '2025-08-23 09:23:30', '2025-08-23 09:23:30'),
(227, 114, 51, 'Coffee & Snacks', 'coffee--snacks', NULL, NULL, NULL, NULL, '2025-08-23 12:57:15', '2025-08-23 12:57:15'),
(228, 114, 53, 'القهوة والوجبات الخفيفة', 'القهوة-والوجبات-الخفيفة', NULL, NULL, NULL, NULL, '2025-08-23 12:57:15', '2025-08-23 12:57:15'),
(229, 115, 51, 'Breakfast Box', 'breakfast-box', NULL, NULL, NULL, NULL, '2025-08-23 12:57:58', '2025-08-23 12:57:58'),
(230, 115, 53, 'صندوق الإفطار', 'صندوق-الإفطار', NULL, NULL, NULL, NULL, '2025-08-23 12:57:58', '2025-08-23 12:57:58'),
(231, 116, 51, 'Juice Station', 'juice-station', NULL, NULL, NULL, NULL, '2025-08-23 12:58:57', '2025-08-23 12:58:57'),
(232, 116, 53, 'محطة العصير', 'محطة-العصير', NULL, NULL, NULL, NULL, '2025-08-23 12:58:57', '2025-08-23 12:58:57'),
(233, 117, 51, 'Classroom Style', 'classroom-style', NULL, NULL, NULL, NULL, '2025-08-23 13:10:32', '2025-08-23 13:10:32'),
(234, 117, 53, 'أسلوب الفصل الدراسي', 'أسلوب-الفصل-الدراسي', NULL, NULL, NULL, NULL, '2025-08-23 13:10:32', '2025-08-23 13:10:32'),
(235, 118, 51, 'Library Style', 'library-style', NULL, NULL, NULL, NULL, '2025-08-23 13:10:32', '2025-08-23 13:10:32'),
(236, 118, 53, 'نمط المكتبة', 'نمط-المكتبة', NULL, NULL, NULL, NULL, '2025-08-23 13:10:32', '2025-08-23 13:10:32'),
(237, 119, 51, 'Modern library', 'modern-library', NULL, NULL, NULL, NULL, '2025-08-23 13:10:32', '2025-08-23 13:10:32'),
(238, 119, 53, 'المكتبة الحديثة', 'المكتبة-الحديثة', NULL, NULL, NULL, NULL, '2025-08-23 13:10:32', '2025-08-23 13:10:32'),
(239, 120, 51, 'Coffee & Snacks', 'coffee--snacks', NULL, NULL, NULL, NULL, '2025-08-23 13:59:42', '2025-08-23 13:59:42'),
(240, 120, 53, 'القهوة والوجبات الخفيفة', 'القهوة-والوجبات-الخفيفة', NULL, NULL, NULL, NULL, '2025-08-23 13:59:42', '2025-08-23 13:59:42'),
(241, 121, 51, 'Breakfast Box', 'breakfast-box', NULL, NULL, NULL, NULL, '2025-08-23 13:59:42', '2025-08-23 13:59:42'),
(242, 121, 53, 'صندوق الإفطار', 'صندوق-الإفطار', NULL, NULL, NULL, NULL, '2025-08-23 13:59:42', '2025-08-23 13:59:42'),
(243, 122, 51, 'Juice Station', 'juice-station', NULL, NULL, NULL, NULL, '2025-08-23 13:59:42', '2025-08-23 13:59:42'),
(244, 122, 53, 'محطة العصير', 'محطة-العصير', NULL, NULL, NULL, NULL, '2025-08-23 13:59:42', '2025-08-23 13:59:42'),
(245, 123, 51, 'The Office Group', 'the-office-group', NULL, NULL, NULL, NULL, '2025-08-23 14:02:48', '2025-08-23 14:02:48'),
(246, 123, 53, 'مجموعة المكتب', 'مجموعة-المكتب', NULL, NULL, NULL, NULL, '2025-08-23 14:02:48', '2025-08-23 14:02:48'),
(247, 124, 51, 'Second Home', 'second-home', NULL, NULL, NULL, NULL, '2025-08-23 14:02:48', '2025-08-23 14:02:48'),
(248, 124, 53, 'المنزل الثاني', 'المنزل-الثاني', NULL, NULL, NULL, NULL, '2025-08-23 14:02:48', '2025-08-23 14:02:48'),
(249, 125, 51, 'BetaWorks', 'betaworks', NULL, NULL, NULL, NULL, '2025-08-23 14:02:48', '2025-08-23 14:02:48'),
(250, 125, 53, 'بيتا ووركس', 'بيتا-ووركس', NULL, NULL, NULL, NULL, '2025-08-23 14:02:48', '2025-08-23 14:02:48'),
(251, 126, 51, 'Breakfast Package', 'breakfast-package', NULL, NULL, NULL, NULL, '2025-08-23 14:47:29', '2025-08-23 14:47:29'),
(252, 126, 53, 'باقة الإفطار', 'باقة-الإفطار', NULL, NULL, NULL, NULL, '2025-08-23 14:47:29', '2025-08-23 14:47:29'),
(253, 127, 51, 'Lunch Buffet', 'lunch-buffet', NULL, NULL, NULL, NULL, '2025-08-23 14:47:29', '2025-08-23 14:47:29'),
(254, 127, 53, 'بوفيه غداء', 'بوفيه-غداء', NULL, NULL, NULL, NULL, '2025-08-23 14:47:29', '2025-08-23 14:47:29'),
(255, 128, 51, 'Cocktail Service', 'cocktail-service', NULL, NULL, NULL, NULL, '2025-08-23 14:47:29', '2025-08-23 14:47:29'),
(256, 128, 53, 'خدمة الكوكتيل', 'خدمة-الكوكتيل', NULL, NULL, NULL, NULL, '2025-08-23 14:47:29', '2025-08-23 14:47:29'),
(257, 129, 51, 'Alumni House', 'alumni-house', NULL, NULL, NULL, NULL, '2025-08-23 14:53:09', '2025-08-23 14:53:09'),
(258, 129, 53, 'بيت الخريجين', 'بيت-الخريجين', NULL, NULL, NULL, NULL, '2025-08-23 14:53:09', '2025-08-23 14:53:09'),
(259, 130, 51, 'Museum Atrium', 'museum-atrium', NULL, NULL, NULL, NULL, '2025-08-23 14:53:09', '2025-08-23 14:53:09'),
(260, 130, 53, 'ردهة المتحف', 'ردهة-المتحف', NULL, NULL, NULL, NULL, '2025-08-23 14:53:09', '2025-08-23 14:53:09'),
(261, 131, 51, 'Business Center', 'business-center', NULL, NULL, NULL, NULL, '2025-08-23 14:53:09', '2025-08-23 14:53:09'),
(262, 131, 53, 'مركز الأعمال', 'مركز-الأعمال', NULL, NULL, NULL, NULL, '2025-08-23 14:53:09', '2025-08-23 14:53:09'),
(263, 132, 51, 'Buffet Dinner', 'buffet-dinner', NULL, NULL, NULL, NULL, '2025-08-23 15:31:19', '2025-08-23 15:31:19'),
(264, 132, 53, 'بوفيه عشاء', 'بوفيه-عشاء', NULL, NULL, NULL, NULL, '2025-08-23 15:31:19', '2025-08-23 15:31:19'),
(265, 133, 51, 'Beverage Service', 'beverage-service', NULL, NULL, NULL, NULL, '2025-08-23 15:31:19', '2025-08-23 15:31:19'),
(266, 133, 53, 'خدمة المشروبات', 'خدمة-المشروبات', NULL, NULL, NULL, NULL, '2025-08-23 15:31:19', '2025-08-23 15:31:19'),
(267, 134, 51, 'Traditional Menu', 'traditional-menu', NULL, NULL, NULL, NULL, '2025-08-23 15:31:19', '2025-08-23 15:31:19'),
(268, 134, 53, 'القائمة التقليدية', 'القائمة-التقليدية', NULL, NULL, NULL, NULL, '2025-08-23 15:31:19', '2025-08-23 15:31:19'),
(269, 135, 51, 'Stage Decoration', 'stage-decoration', NULL, NULL, NULL, NULL, '2025-08-23 15:34:06', '2025-08-23 15:34:06'),
(270, 135, 53, 'ديكور المسرح', 'ديكور-المسرح', NULL, NULL, NULL, NULL, '2025-08-23 15:34:06', '2025-08-23 15:34:06'),
(271, 136, 51, 'Table Centerpieces', 'table-centerpieces', NULL, NULL, NULL, NULL, '2025-08-23 15:34:06', '2025-08-23 15:34:06'),
(272, 136, 53, 'قطع مركزية للطاولة', 'قطع-مركزية-للطاولة', NULL, NULL, NULL, NULL, '2025-08-23 15:34:06', '2025-08-23 15:34:06'),
(273, 137, 51, 'Lighting Setup', 'lighting-setup', NULL, NULL, NULL, NULL, '2025-08-23 15:34:06', '2025-08-23 15:34:06'),
(274, 137, 53, 'إعداد الإضاءة', 'إعداد-الإضاءة', NULL, NULL, NULL, NULL, '2025-08-23 15:34:06', '2025-08-23 15:34:06'),
(285, 143, 51, 'Modular Booth', 'modular-booth', NULL, NULL, NULL, NULL, '2025-08-24 08:34:30', '2025-08-24 08:34:30'),
(286, 143, 53, 'كشك معياري', 'كشك-معياري', NULL, NULL, NULL, NULL, '2025-08-24 08:34:30', '2025-08-24 08:34:30'),
(287, 144, 51, 'Display Counters', 'display-counters', NULL, NULL, NULL, NULL, '2025-08-24 08:34:30', '2025-08-24 08:34:30'),
(288, 144, 53, 'عدادات العرض', 'عدادات-العرض', NULL, NULL, NULL, NULL, '2025-08-24 08:34:30', '2025-08-24 08:34:30'),
(289, 145, 51, 'Pop-up Display', 'pop-up-display', NULL, NULL, NULL, NULL, '2025-08-24 08:34:30', '2025-08-24 08:34:30'),
(290, 145, 53, 'شاشة العرض المنبثقة', 'شاشة-العرض-المنبثقة', NULL, NULL, NULL, NULL, '2025-08-24 08:34:30', '2025-08-24 08:34:30'),
(291, 146, 51, 'Buffet Dinner', 'buffet-dinner', NULL, NULL, NULL, NULL, '2025-08-24 08:37:07', '2025-08-24 08:37:07'),
(292, 146, 53, 'بوفيه عشاء', 'بوفيه-عشاء', NULL, NULL, NULL, NULL, '2025-08-24 08:37:07', '2025-08-24 08:37:07'),
(293, 147, 51, 'Dessert Corner', 'dessert-corner', NULL, NULL, NULL, NULL, '2025-08-24 08:37:07', '2025-08-24 08:37:07'),
(294, 147, 53, 'ركن الحلويات', 'ركن-الحلويات', NULL, NULL, NULL, NULL, '2025-08-24 08:37:07', '2025-08-24 08:37:07'),
(295, 148, 51, 'Beverage Service', 'beverage-service', NULL, NULL, NULL, NULL, '2025-08-24 08:37:07', '2025-08-24 08:37:07'),
(296, 148, 53, 'خدمة المشروبات', 'خدمة-المشروبات', NULL, NULL, NULL, NULL, '2025-08-24 08:37:07', '2025-08-24 08:37:07');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `user_type` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `admin_id` bigint UNSIGNED NOT NULL,
  `ticket_number` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `message` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `attachment` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` tinyint DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1=active,0=deactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `occupation` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `language_id`, `image`, `name`, `occupation`, `comment`, `created_at`, `updated_at`) VALUES
(1, 51, '68a1fe72eb799.jpg', 'Sarah Johnson', 'Event Planner', 'Booking a venue through this platform was an absolute breeze. The process is user-friendly, fast, and very reliable. I felt confident throughout and would definitely recommend it to anyone planning an event', '2025-08-17 15:43:56', '2025-08-17 16:08:18'),
(2, 53, '68a200431d337.jpg', 'سارة جونسون', 'منظم الفعاليات', 'كان حجز المكان من خلال هذه المنصة سهلاً للغاية. العملية سهلة الاستخدام وسريعة وموثوقة جدًا. شعرت بالثقة طوال الوقت وسأوصي بها بالتأكيد لأي شخص يخطط لحدث', '2025-08-17 16:01:53', '2025-08-17 16:16:03'),
(3, 51, '68a1fe4f0fa7f.jpg', 'Ahmed Al-Mansouri', 'Corporate Manager', 'I am impressed with the wide range of spaces available. The booking steps are clear and simple, making it very convenient for corporate events. The support team was also responsive and helpful throughout the process', '2025-08-17 16:07:43', '2025-08-17 16:07:43'),
(4, 53, '68a1fed2d66f8.jpg', 'أحمد المنصوري', 'مدير الشركة', 'أنا معجب بتنوع الأماكن المتاحة. خطوات الحجز واضحة وبسيطة، مما يجعلها مناسبة جدًا للفعاليات الشركاتية. فريق الدعم كان أيضًا متجاوبًا ومساعدًا طوال العملية', '2025-08-17 16:09:54', '2025-08-17 16:09:54'),
(5, 51, '68a2008484e44.jpg', 'Emily Davis', 'Wedding Coordinator', 'This platform made event planning so stress-free. The interface is intuitive, and I could easily select the perfect venue for my clients. The confirmation and payment process were seamless and reassuring every step of the way', '2025-08-17 16:17:08', '2025-08-17 16:17:08'),
(6, 53, '68a200c1d0278.jpg', 'إميلي ديفيس', 'منسق حفلات الزفاف', 'جعلت هذه المنصة تخطيط الحدث خاليًا من التوتر. الواجهة سهلة الاستخدام، وتمكنت من اختيار المكان المثالي لعملائي بسهولة. كانت عملية التأكيد والدفع سلسة وموثوقة في كل خطوة', '2025-08-17 16:18:09', '2025-08-17 16:18:09'),
(7, 51, '68b2e86ad47ed.jpg', 'Khalid Bin Saleh', 'Party Organizer', 'Everything about the booking process was smooth and convenient. From searching for venues to final confirmation, the platform ensured a hassle-free experience. I highly recommend it to anyone organizing events or parties of any size', '2025-08-17 16:19:08', '2025-08-30 12:02:50'),
(8, 53, '68a2013735c46.jpg', 'خالد بن صالح', 'منظم الحفلات', 'كل شيء في عملية الحجز كان سلسًا ومريحًا. من البحث عن الأماكن حتى التأكيد النهائي، ضمنت المنصة تجربة خالية من المتاعب. أوصي بها بشدة لأي شخص ينظم فعاليات أو حفلات من أي حجم', '2025-08-17 16:20:07', '2025-08-17 16:20:07');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_conversations`
--

CREATE TABLE `ticket_conversations` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `person_id` bigint UNSIGNED NOT NULL,
  `person_type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `reply` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `attachment` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timezones`
--

CREATE TABLE `timezones` (
  `id` bigint UNSIGNED NOT NULL,
  `country_code` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `timezone` varchar(125) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `gmt_offset` decimal(10,2) NOT NULL,
  `dst_offset` decimal(10,2) NOT NULL,
  `raw_offset` decimal(10,2) NOT NULL,
  `is_set` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `timezones`
--

INSERT INTO `timezones` (`id`, `country_code`, `timezone`, `gmt_offset`, `dst_offset`, `raw_offset`, `is_set`) VALUES
(1, 'AD', 'Europe/Andorra', 1.00, 2.00, 1.00, 'no'),
(2, 'AE', 'Asia/Dubai', 4.00, 4.00, 4.00, 'no'),
(3, 'AF', 'Asia/Kabul', 4.50, 4.50, 4.50, 'no'),
(4, 'AG', 'America/Antigua', -4.00, -4.00, -4.00, 'no'),
(5, 'AI', 'America/Anguilla', -4.00, -4.00, -4.00, 'no'),
(6, 'AL', 'Europe/Tirane', 1.00, 2.00, 1.00, 'no'),
(7, 'AM', 'Asia/Yerevan', 4.00, 4.00, 4.00, 'no'),
(8, 'AO', 'Africa/Luanda', 1.00, 1.00, 1.00, 'no'),
(9, 'AQ', 'Antarctica/Casey', 8.00, 8.00, 8.00, 'no'),
(10, 'AQ', 'Antarctica/Davis', 7.00, 7.00, 7.00, 'no'),
(11, 'AQ', 'Antarctica/DumontDUrville', 10.00, 10.00, 10.00, 'no'),
(12, 'AQ', 'Antarctica/Mawson', 5.00, 5.00, 5.00, 'no'),
(13, 'AQ', 'Antarctica/McMurdo', 13.00, 12.00, 12.00, 'no'),
(14, 'AQ', 'Antarctica/Palmer', -3.00, -4.00, -4.00, 'no'),
(15, 'AQ', 'Antarctica/Rothera', -3.00, -3.00, -3.00, 'no'),
(16, 'AQ', 'Antarctica/South_Pole', 13.00, 12.00, 12.00, 'no'),
(17, 'AQ', 'Antarctica/Syowa', 3.00, 3.00, 3.00, 'no'),
(18, 'AQ', 'Antarctica/Vostok', 6.00, 6.00, 6.00, 'no'),
(19, 'AR', 'America/Argentina/Buenos_Aires', -3.00, -3.00, -3.00, 'no'),
(20, 'AR', 'America/Argentina/Catamarca', -3.00, -3.00, -3.00, 'no'),
(21, 'AR', 'America/Argentina/Cordoba', -3.00, -3.00, -3.00, 'no'),
(22, 'AR', 'America/Argentina/Jujuy', -3.00, -3.00, -3.00, 'no'),
(23, 'AR', 'America/Argentina/La_Rioja', -3.00, -3.00, -3.00, 'no'),
(24, 'AR', 'America/Argentina/Mendoza', -3.00, -3.00, -3.00, 'no'),
(25, 'AR', 'America/Argentina/Rio_Gallegos', -3.00, -3.00, -3.00, 'no'),
(26, 'AR', 'America/Argentina/Salta', -3.00, -3.00, -3.00, 'no'),
(27, 'AR', 'America/Argentina/San_Juan', -3.00, -3.00, -3.00, 'no'),
(28, 'AR', 'America/Argentina/San_Luis', -3.00, -3.00, -3.00, 'no'),
(29, 'AR', 'America/Argentina/Tucuman', -3.00, -3.00, -3.00, 'no'),
(30, 'AR', 'America/Argentina/Ushuaia', -3.00, -3.00, -3.00, 'no'),
(31, 'AS', 'Pacific/Pago_Pago', -11.00, -11.00, -11.00, 'no'),
(32, 'AT', 'Europe/Vienna', 1.00, 2.00, 1.00, 'no'),
(33, 'AU', 'Antarctica/Macquarie', 11.00, 11.00, 11.00, 'no'),
(34, 'AU', 'Australia/Adelaide', 10.50, 9.50, 9.50, 'no'),
(35, 'AU', 'Australia/Brisbane', 10.00, 10.00, 10.00, 'no'),
(36, 'AU', 'Australia/Broken_Hill', 10.50, 9.50, 9.50, 'no'),
(37, 'AU', 'Australia/Currie', 11.00, 10.00, 10.00, 'no'),
(38, 'AU', 'Australia/Darwin', 9.50, 9.50, 9.50, 'no'),
(39, 'AU', 'Australia/Eucla', 8.75, 8.75, 8.75, 'no'),
(40, 'AU', 'Australia/Hobart', 11.00, 10.00, 10.00, 'no'),
(41, 'AU', 'Australia/Lindeman', 10.00, 10.00, 10.00, 'no'),
(42, 'AU', 'Australia/Lord_Howe', 11.00, 10.50, 10.50, 'no'),
(43, 'AU', 'Australia/Melbourne', 11.00, 10.00, 10.00, 'no'),
(44, 'AU', 'Australia/Perth', 8.00, 8.00, 8.00, 'no'),
(45, 'AU', 'Australia/Sydney', 11.00, 10.00, 10.00, 'no'),
(46, 'AW', 'America/Aruba', -4.00, -4.00, -4.00, 'no'),
(47, 'AX', 'Europe/Mariehamn', 2.00, 3.00, 2.00, 'no'),
(48, 'AZ', 'Asia/Baku', 4.00, 5.00, 4.00, 'no'),
(49, 'BA', 'Europe/Sarajevo', 1.00, 2.00, 1.00, 'no'),
(50, 'BB', 'America/Barbados', -4.00, -4.00, -4.00, 'no'),
(51, 'BD', 'Asia/Dhaka', 6.00, 6.00, 6.00, 'yes'),
(52, 'BE', 'Europe/Brussels', 1.00, 2.00, 1.00, 'no'),
(53, 'BF', 'Africa/Ouagadougou', 0.00, 0.00, 0.00, 'no'),
(54, 'BG', 'Europe/Sofia', 2.00, 3.00, 2.00, 'no'),
(55, 'BH', 'Asia/Bahrain', 3.00, 3.00, 3.00, 'no'),
(56, 'BI', 'Africa/Bujumbura', 2.00, 2.00, 2.00, 'no'),
(57, 'BJ', 'Africa/Porto-Novo', 1.00, 1.00, 1.00, 'no'),
(58, 'BL', 'America/St_Barthelemy', -4.00, -4.00, -4.00, 'no'),
(59, 'BM', 'Atlantic/Bermuda', -4.00, -3.00, -4.00, 'no'),
(60, 'BN', 'Asia/Brunei', 8.00, 8.00, 8.00, 'no'),
(61, 'BO', 'America/La_Paz', -4.00, -4.00, -4.00, 'no'),
(62, 'BQ', 'America/Kralendijk', -4.00, -4.00, -4.00, 'no'),
(63, 'BR', 'America/Araguaina', -3.00, -3.00, -3.00, 'no'),
(64, 'BR', 'America/Bahia', -3.00, -3.00, -3.00, 'no'),
(65, 'BR', 'America/Belem', -3.00, -3.00, -3.00, 'no'),
(66, 'BR', 'America/Boa_Vista', -4.00, -4.00, -4.00, 'no'),
(67, 'BR', 'America/Campo_Grande', -3.00, -4.00, -4.00, 'no'),
(68, 'BR', 'America/Cuiaba', -3.00, -4.00, -4.00, 'no'),
(69, 'BR', 'America/Eirunepe', -5.00, -5.00, -5.00, 'no'),
(70, 'BR', 'America/Fortaleza', -3.00, -3.00, -3.00, 'no'),
(71, 'BR', 'America/Maceio', -3.00, -3.00, -3.00, 'no'),
(72, 'BR', 'America/Manaus', -4.00, -4.00, -4.00, 'no'),
(73, 'BR', 'America/Noronha', -2.00, -2.00, -2.00, 'no'),
(74, 'BR', 'America/Porto_Velho', -4.00, -4.00, -4.00, 'no'),
(75, 'BR', 'America/Recife', -3.00, -3.00, -3.00, 'no'),
(76, 'BR', 'America/Rio_Branco', -5.00, -5.00, -5.00, 'no'),
(77, 'BR', 'America/Santarem', -3.00, -3.00, -3.00, 'no'),
(78, 'BR', 'America/Sao_Paulo', -2.00, -3.00, -3.00, 'no'),
(79, 'BS', 'America/Nassau', -5.00, -4.00, -5.00, 'no'),
(80, 'BT', 'Asia/Thimphu', 6.00, 6.00, 6.00, 'no'),
(81, 'BW', 'Africa/Gaborone', 2.00, 2.00, 2.00, 'no'),
(82, 'BY', 'Europe/Minsk', 3.00, 3.00, 3.00, 'no'),
(83, 'BZ', 'America/Belize', -6.00, -6.00, -6.00, 'no'),
(84, 'CA', 'America/Atikokan', -5.00, -5.00, -5.00, 'no'),
(85, 'CA', 'America/Blanc-Sablon', -4.00, -4.00, -4.00, 'no'),
(86, 'CA', 'America/Cambridge_Bay', -7.00, -6.00, -7.00, 'no'),
(87, 'CA', 'America/Creston', -7.00, -7.00, -7.00, 'no'),
(88, 'CA', 'America/Dawson', -8.00, -7.00, -8.00, 'no'),
(89, 'CA', 'America/Dawson_Creek', -7.00, -7.00, -7.00, 'no'),
(90, 'CA', 'America/Edmonton', -7.00, -6.00, -7.00, 'no'),
(91, 'CA', 'America/Glace_Bay', -4.00, -3.00, -4.00, 'no'),
(92, 'CA', 'America/Goose_Bay', -4.00, -3.00, -4.00, 'no'),
(93, 'CA', 'America/Halifax', -4.00, -3.00, -4.00, 'no'),
(94, 'CA', 'America/Inuvik', -7.00, -6.00, -7.00, 'no'),
(95, 'CA', 'America/Iqaluit', -5.00, -4.00, -5.00, 'no'),
(96, 'CA', 'America/Moncton', -4.00, -3.00, -4.00, 'no'),
(97, 'CA', 'America/Montreal', -5.00, -4.00, -5.00, 'no'),
(98, 'CA', 'America/Nipigon', -5.00, -4.00, -5.00, 'no'),
(99, 'CA', 'America/Pangnirtung', -5.00, -4.00, -5.00, 'no'),
(100, 'CA', 'America/Rainy_River', -6.00, -5.00, -6.00, 'no'),
(101, 'CA', 'America/Rankin_Inlet', -6.00, -5.00, -6.00, 'no'),
(102, 'CA', 'America/Regina', -6.00, -6.00, -6.00, 'no'),
(103, 'CA', 'America/Resolute', -6.00, -5.00, -6.00, 'no'),
(104, 'CA', 'America/St_Johns', -3.50, -2.50, -3.50, 'no'),
(105, 'CA', 'America/Swift_Current', -6.00, -6.00, -6.00, 'no'),
(106, 'CA', 'America/Thunder_Bay', -5.00, -4.00, -5.00, 'no'),
(107, 'CA', 'America/Toronto', -5.00, -4.00, -5.00, 'no'),
(108, 'CA', 'America/Vancouver', -8.00, -7.00, -8.00, 'no'),
(109, 'CA', 'America/Whitehorse', -8.00, -7.00, -8.00, 'no'),
(110, 'CA', 'America/Winnipeg', -6.00, -5.00, -6.00, 'no'),
(111, 'CA', 'America/Yellowknife', -7.00, -6.00, -7.00, 'no'),
(112, 'CC', 'Indian/Cocos', 6.50, 6.50, 6.50, 'no'),
(113, 'CD', 'Africa/Kinshasa', 1.00, 1.00, 1.00, 'no'),
(114, 'CD', 'Africa/Lubumbashi', 2.00, 2.00, 2.00, 'no'),
(115, 'CF', 'Africa/Bangui', 1.00, 1.00, 1.00, 'no'),
(116, 'CG', 'Africa/Brazzaville', 1.00, 1.00, 1.00, 'no'),
(117, 'CH', 'Europe/Zurich', 1.00, 2.00, 1.00, 'no'),
(118, 'CI', 'Africa/Abidjan', 0.00, 0.00, 0.00, 'no'),
(119, 'CK', 'Pacific/Rarotonga', -10.00, -10.00, -10.00, 'no'),
(120, 'CL', 'America/Santiago', -3.00, -4.00, -4.00, 'no'),
(121, 'CL', 'Pacific/Easter', -5.00, -6.00, -6.00, 'no'),
(122, 'CM', 'Africa/Douala', 1.00, 1.00, 1.00, 'no'),
(123, 'CN', 'Asia/Chongqing', 8.00, 8.00, 8.00, 'no'),
(124, 'CN', 'Asia/Harbin', 8.00, 8.00, 8.00, 'no'),
(125, 'CN', 'Asia/Kashgar', 8.00, 8.00, 8.00, 'no'),
(126, 'CN', 'Asia/Shanghai', 8.00, 8.00, 8.00, 'no'),
(127, 'CN', 'Asia/Urumqi', 8.00, 8.00, 8.00, 'no'),
(128, 'CO', 'America/Bogota', -5.00, -5.00, -5.00, 'no'),
(129, 'CR', 'America/Costa_Rica', -6.00, -6.00, -6.00, 'no'),
(130, 'CU', 'America/Havana', -5.00, -4.00, -5.00, 'no'),
(131, 'CV', 'Atlantic/Cape_Verde', -1.00, -1.00, -1.00, 'no'),
(132, 'CW', 'America/Curacao', -4.00, -4.00, -4.00, 'no'),
(133, 'CX', 'Indian/Christmas', 7.00, 7.00, 7.00, 'no'),
(134, 'CY', 'Asia/Nicosia', 2.00, 3.00, 2.00, 'no'),
(135, 'CZ', 'Europe/Prague', 1.00, 2.00, 1.00, 'no'),
(136, 'DE', 'Europe/Berlin', 1.00, 2.00, 1.00, 'no'),
(137, 'DE', 'Europe/Busingen', 1.00, 2.00, 1.00, 'no'),
(138, 'DJ', 'Africa/Djibouti', 3.00, 3.00, 3.00, 'no'),
(139, 'DK', 'Europe/Copenhagen', 1.00, 2.00, 1.00, 'no'),
(140, 'DM', 'America/Dominica', -4.00, -4.00, -4.00, 'no'),
(141, 'DO', 'America/Santo_Domingo', -4.00, -4.00, -4.00, 'no'),
(142, 'DZ', 'Africa/Algiers', 1.00, 1.00, 1.00, 'no'),
(143, 'EC', 'America/Guayaquil', -5.00, -5.00, -5.00, 'no'),
(144, 'EC', 'Pacific/Galapagos', -6.00, -6.00, -6.00, 'no'),
(145, 'EE', 'Europe/Tallinn', 2.00, 3.00, 2.00, 'no'),
(146, 'EG', 'Africa/Cairo', 2.00, 2.00, 2.00, 'no'),
(147, 'EH', 'Africa/El_Aaiun', 0.00, 0.00, 0.00, 'no'),
(148, 'ER', 'Africa/Asmara', 3.00, 3.00, 3.00, 'no'),
(149, 'ES', 'Africa/Ceuta', 1.00, 2.00, 1.00, 'no'),
(150, 'ES', 'Atlantic/Canary', 0.00, 1.00, 0.00, 'no'),
(151, 'ES', 'Europe/Madrid', 1.00, 2.00, 1.00, 'no'),
(152, 'ET', 'Africa/Addis_Ababa', 3.00, 3.00, 3.00, 'no'),
(153, 'FI', 'Europe/Helsinki', 2.00, 3.00, 2.00, 'no'),
(154, 'FJ', 'Pacific/Fiji', 13.00, 12.00, 12.00, 'no'),
(155, 'FK', 'Atlantic/Stanley', -3.00, -3.00, -3.00, 'no'),
(156, 'FM', 'Pacific/Chuuk', 10.00, 10.00, 10.00, 'no'),
(157, 'FM', 'Pacific/Kosrae', 11.00, 11.00, 11.00, 'no'),
(158, 'FM', 'Pacific/Pohnpei', 11.00, 11.00, 11.00, 'no'),
(159, 'FO', 'Atlantic/Faroe', 0.00, 1.00, 0.00, 'no'),
(160, 'FR', 'Europe/Paris', 1.00, 2.00, 1.00, 'no'),
(161, 'GA', 'Africa/Libreville', 1.00, 1.00, 1.00, 'no'),
(162, 'GB', 'Europe/London', 0.00, 1.00, 0.00, 'no'),
(163, 'GD', 'America/Grenada', -4.00, -4.00, -4.00, 'no'),
(164, 'GE', 'Asia/Tbilisi', 4.00, 4.00, 4.00, 'no'),
(165, 'GF', 'America/Cayenne', -3.00, -3.00, -3.00, 'no'),
(166, 'GG', 'Europe/Guernsey', 0.00, 1.00, 0.00, 'no'),
(167, 'GH', 'Africa/Accra', 0.00, 0.00, 0.00, 'no'),
(168, 'GI', 'Europe/Gibraltar', 1.00, 2.00, 1.00, 'no'),
(169, 'GL', 'America/Danmarkshavn', 0.00, 0.00, 0.00, 'no'),
(170, 'GL', 'America/Godthab', -3.00, -2.00, -3.00, 'no'),
(171, 'GL', 'America/Scoresbysund', -1.00, 0.00, -1.00, 'no'),
(172, 'GL', 'America/Thule', -4.00, -3.00, -4.00, 'no'),
(173, 'GM', 'Africa/Banjul', 0.00, 0.00, 0.00, 'no'),
(174, 'GN', 'Africa/Conakry', 0.00, 0.00, 0.00, 'no'),
(175, 'GP', 'America/Guadeloupe', -4.00, -4.00, -4.00, 'no'),
(176, 'GQ', 'Africa/Malabo', 1.00, 1.00, 1.00, 'no'),
(177, 'GR', 'Europe/Athens', 2.00, 3.00, 2.00, 'no'),
(178, 'GS', 'Atlantic/South_Georgia', -2.00, -2.00, -2.00, 'no'),
(179, 'GT', 'America/Guatemala', -6.00, -6.00, -6.00, 'no'),
(180, 'GU', 'Pacific/Guam', 10.00, 10.00, 10.00, 'no'),
(181, 'GW', 'Africa/Bissau', 0.00, 0.00, 0.00, 'no'),
(182, 'GY', 'America/Guyana', -4.00, -4.00, -4.00, 'no'),
(183, 'HK', 'Asia/Hong_Kong', 8.00, 8.00, 8.00, 'no'),
(184, 'HN', 'America/Tegucigalpa', -6.00, -6.00, -6.00, 'no'),
(185, 'HR', 'Europe/Zagreb', 1.00, 2.00, 1.00, 'no'),
(186, 'HT', 'America/Port-au-Prince', -5.00, -4.00, -5.00, 'no'),
(187, 'HU', 'Europe/Budapest', 1.00, 2.00, 1.00, 'no'),
(188, 'ID', 'Asia/Jakarta', 7.00, 7.00, 7.00, 'no'),
(189, 'ID', 'Asia/Jayapura', 9.00, 9.00, 9.00, 'no'),
(190, 'ID', 'Asia/Makassar', 8.00, 8.00, 8.00, 'no'),
(191, 'ID', 'Asia/Pontianak', 7.00, 7.00, 7.00, 'no'),
(192, 'IE', 'Europe/Dublin', 0.00, 1.00, 0.00, 'no'),
(193, 'IL', 'Asia/Jerusalem', 2.00, 3.00, 2.00, 'no'),
(194, 'IM', 'Europe/Isle_of_Man', 0.00, 1.00, 0.00, 'no'),
(195, 'IN', 'Asia/Kolkata', 5.50, 5.50, 5.50, 'no'),
(196, 'IO', 'Indian/Chagos', 6.00, 6.00, 6.00, 'no'),
(197, 'IQ', 'Asia/Baghdad', 3.00, 3.00, 3.00, 'no'),
(198, 'IR', 'Asia/Tehran', 3.50, 4.50, 3.50, 'no'),
(199, 'IS', 'Atlantic/Reykjavik', 0.00, 0.00, 0.00, 'no'),
(200, 'IT', 'Europe/Rome', 1.00, 2.00, 1.00, 'no'),
(201, 'JE', 'Europe/Jersey', 0.00, 1.00, 0.00, 'no'),
(202, 'JM', 'America/Jamaica', -5.00, -5.00, -5.00, 'no'),
(203, 'JO', 'Asia/Amman', 2.00, 3.00, 2.00, 'no'),
(204, 'JP', 'Asia/Tokyo', 9.00, 9.00, 9.00, 'no'),
(205, 'KE', 'Africa/Nairobi', 3.00, 3.00, 3.00, 'no'),
(206, 'KG', 'Asia/Bishkek', 6.00, 6.00, 6.00, 'no'),
(207, 'KH', 'Asia/Phnom_Penh', 7.00, 7.00, 7.00, 'no'),
(208, 'KI', 'Pacific/Enderbury', 13.00, 13.00, 13.00, 'no'),
(209, 'KI', 'Pacific/Kiritimati', 14.00, 14.00, 14.00, 'no'),
(210, 'KI', 'Pacific/Tarawa', 12.00, 12.00, 12.00, 'no'),
(211, 'KM', 'Indian/Comoro', 3.00, 3.00, 3.00, 'no'),
(212, 'KN', 'America/St_Kitts', -4.00, -4.00, -4.00, 'no'),
(213, 'KP', 'Asia/Pyongyang', 9.00, 9.00, 9.00, 'no'),
(214, 'KR', 'Asia/Seoul', 9.00, 9.00, 9.00, 'no'),
(215, 'KW', 'Asia/Kuwait', 3.00, 3.00, 3.00, 'no'),
(216, 'KY', 'America/Cayman', -5.00, -5.00, -5.00, 'no'),
(217, 'KZ', 'Asia/Almaty', 6.00, 6.00, 6.00, 'no'),
(218, 'KZ', 'Asia/Aqtau', 5.00, 5.00, 5.00, 'no'),
(219, 'KZ', 'Asia/Aqtobe', 5.00, 5.00, 5.00, 'no'),
(220, 'KZ', 'Asia/Oral', 5.00, 5.00, 5.00, 'no'),
(221, 'KZ', 'Asia/Qyzylorda', 6.00, 6.00, 6.00, 'no'),
(222, 'LA', 'Asia/Vientiane', 7.00, 7.00, 7.00, 'no'),
(223, 'LB', 'Asia/Beirut', 2.00, 3.00, 2.00, 'no'),
(224, 'LC', 'America/St_Lucia', -4.00, -4.00, -4.00, 'no'),
(225, 'LI', 'Europe/Vaduz', 1.00, 2.00, 1.00, 'no'),
(226, 'LK', 'Asia/Colombo', 5.50, 5.50, 5.50, 'no'),
(227, 'LR', 'Africa/Monrovia', 0.00, 0.00, 0.00, 'no'),
(228, 'LS', 'Africa/Maseru', 2.00, 2.00, 2.00, 'no'),
(229, 'LT', 'Europe/Vilnius', 2.00, 3.00, 2.00, 'no'),
(230, 'LU', 'Europe/Luxembourg', 1.00, 2.00, 1.00, 'no'),
(231, 'LV', 'Europe/Riga', 2.00, 3.00, 2.00, 'no'),
(232, 'LY', 'Africa/Tripoli', 2.00, 2.00, 2.00, 'no'),
(233, 'MA', 'Africa/Casablanca', 0.00, 0.00, 0.00, 'no'),
(234, 'MC', 'Europe/Monaco', 1.00, 2.00, 1.00, 'no'),
(235, 'MD', 'Europe/Chisinau', 2.00, 3.00, 2.00, 'no'),
(236, 'ME', 'Europe/Podgorica', 1.00, 2.00, 1.00, 'no'),
(237, 'MF', 'America/Marigot', -4.00, -4.00, -4.00, 'no'),
(238, 'MG', 'Indian/Antananarivo', 3.00, 3.00, 3.00, 'no'),
(239, 'MH', 'Pacific/Kwajalein', 12.00, 12.00, 12.00, 'no'),
(240, 'MH', 'Pacific/Majuro', 12.00, 12.00, 12.00, 'no'),
(241, 'MK', 'Europe/Skopje', 1.00, 2.00, 1.00, 'no'),
(242, 'ML', 'Africa/Bamako', 0.00, 0.00, 0.00, 'no'),
(243, 'MM', 'Asia/Rangoon', 6.50, 6.50, 6.50, 'no'),
(244, 'MN', 'Asia/Choibalsan', 8.00, 8.00, 8.00, 'no'),
(245, 'MN', 'Asia/Hovd', 7.00, 7.00, 7.00, 'no'),
(246, 'MN', 'Asia/Ulaanbaatar', 8.00, 8.00, 8.00, 'no'),
(247, 'MO', 'Asia/Macau', 8.00, 8.00, 8.00, 'no'),
(248, 'MP', 'Pacific/Saipan', 10.00, 10.00, 10.00, 'no'),
(249, 'MQ', 'America/Martinique', -4.00, -4.00, -4.00, 'no'),
(250, 'MR', 'Africa/Nouakchott', 0.00, 0.00, 0.00, 'no'),
(251, 'MS', 'America/Montserrat', -4.00, -4.00, -4.00, 'no'),
(252, 'MT', 'Europe/Malta', 1.00, 2.00, 1.00, 'no'),
(253, 'MU', 'Indian/Mauritius', 4.00, 4.00, 4.00, 'no'),
(254, 'MV', 'Indian/Maldives', 5.00, 5.00, 5.00, 'no'),
(255, 'MW', 'Africa/Blantyre', 2.00, 2.00, 2.00, 'no'),
(256, 'MX', 'America/Bahia_Banderas', -6.00, -5.00, -6.00, 'no'),
(257, 'MX', 'America/Cancun', -6.00, -5.00, -6.00, 'no'),
(258, 'MX', 'America/Chihuahua', -7.00, -6.00, -7.00, 'no'),
(259, 'MX', 'America/Hermosillo', -7.00, -7.00, -7.00, 'no'),
(260, 'MX', 'America/Matamoros', -6.00, -5.00, -6.00, 'no'),
(261, 'MX', 'America/Mazatlan', -7.00, -6.00, -7.00, 'no'),
(262, 'MX', 'America/Merida', -6.00, -5.00, -6.00, 'no'),
(263, 'MX', 'America/Mexico_City', -6.00, -5.00, -6.00, 'no'),
(264, 'MX', 'America/Monterrey', -6.00, -5.00, -6.00, 'no'),
(265, 'MX', 'America/Ojinaga', -7.00, -6.00, -7.00, 'no'),
(266, 'MX', 'America/Santa_Isabel', -8.00, -7.00, -8.00, 'no'),
(267, 'MX', 'America/Tijuana', -8.00, -7.00, -8.00, 'no'),
(268, 'MY', 'Asia/Kuala_Lumpur', 8.00, 8.00, 8.00, 'no'),
(269, 'MY', 'Asia/Kuching', 8.00, 8.00, 8.00, 'no'),
(270, 'MZ', 'Africa/Maputo', 2.00, 2.00, 2.00, 'no'),
(271, 'NA', 'Africa/Windhoek', 2.00, 1.00, 1.00, 'no'),
(272, 'NC', 'Pacific/Noumea', 11.00, 11.00, 11.00, 'no'),
(273, 'NE', 'Africa/Niamey', 1.00, 1.00, 1.00, 'no'),
(274, 'NF', 'Pacific/Norfolk', 11.50, 11.50, 11.50, 'no'),
(275, 'NG', 'Africa/Lagos', 1.00, 1.00, 1.00, 'no'),
(276, 'NI', 'America/Managua', -6.00, -6.00, -6.00, 'no'),
(277, 'NL', 'Europe/Amsterdam', 1.00, 2.00, 1.00, 'no'),
(278, 'NO', 'Europe/Oslo', 1.00, 2.00, 1.00, 'no'),
(279, 'NP', 'Asia/Kathmandu', 5.75, 5.75, 5.75, 'no'),
(280, 'NR', 'Pacific/Nauru', 12.00, 12.00, 12.00, 'no'),
(281, 'NU', 'Pacific/Niue', -11.00, -11.00, -11.00, 'no'),
(282, 'NZ', 'Pacific/Auckland', 13.00, 12.00, 12.00, 'no'),
(283, 'NZ', 'Pacific/Chatham', 13.75, 12.75, 12.75, 'no'),
(284, 'OM', 'Asia/Muscat', 4.00, 4.00, 4.00, 'no'),
(285, 'PA', 'America/Panama', -5.00, -5.00, -5.00, 'no'),
(286, 'PE', 'America/Lima', -5.00, -5.00, -5.00, 'no'),
(287, 'PF', 'Pacific/Gambier', -9.00, -9.00, -9.00, 'no'),
(288, 'PF', 'Pacific/Marquesas', -9.50, -9.50, -9.50, 'no'),
(289, 'PF', 'Pacific/Tahiti', -10.00, -10.00, -10.00, 'no'),
(290, 'PG', 'Pacific/Port_Moresby', 10.00, 10.00, 10.00, 'no'),
(291, 'PH', 'Asia/Manila', 8.00, 8.00, 8.00, 'no'),
(292, 'PK', 'Asia/Karachi', 5.00, 5.00, 5.00, 'no'),
(293, 'PL', 'Europe/Warsaw', 1.00, 2.00, 1.00, 'no'),
(294, 'PM', 'America/Miquelon', -3.00, -2.00, -3.00, 'no'),
(295, 'PN', 'Pacific/Pitcairn', -8.00, -8.00, -8.00, 'no'),
(296, 'PR', 'America/Puerto_Rico', -4.00, -4.00, -4.00, 'no'),
(297, 'PS', 'Asia/Gaza', 2.00, 3.00, 2.00, 'no'),
(298, 'PS', 'Asia/Hebron', 2.00, 3.00, 2.00, 'no'),
(299, 'PT', 'Atlantic/Azores', -1.00, 0.00, -1.00, 'no'),
(300, 'PT', 'Atlantic/Madeira', 0.00, 1.00, 0.00, 'no'),
(301, 'PT', 'Europe/Lisbon', 0.00, 1.00, 0.00, 'no'),
(302, 'PW', 'Pacific/Palau', 9.00, 9.00, 9.00, 'no'),
(303, 'PY', 'America/Asuncion', -3.00, -4.00, -4.00, 'no'),
(304, 'QA', 'Asia/Qatar', 3.00, 3.00, 3.00, 'no'),
(305, 'RE', 'Indian/Reunion', 4.00, 4.00, 4.00, 'no'),
(306, 'RO', 'Europe/Bucharest', 2.00, 3.00, 2.00, 'no'),
(307, 'RS', 'Europe/Belgrade', 1.00, 2.00, 1.00, 'no'),
(308, 'RU', 'Asia/Anadyr', 12.00, 12.00, 12.00, 'no'),
(309, 'RU', 'Asia/Irkutsk', 9.00, 9.00, 9.00, 'no'),
(310, 'RU', 'Asia/Kamchatka', 12.00, 12.00, 12.00, 'no'),
(311, 'RU', 'Asia/Khandyga', 10.00, 10.00, 10.00, 'no'),
(312, 'RU', 'Asia/Krasnoyarsk', 8.00, 8.00, 8.00, 'no'),
(313, 'RU', 'Asia/Magadan', 12.00, 12.00, 12.00, 'no'),
(314, 'RU', 'Asia/Novokuznetsk', 7.00, 7.00, 7.00, 'no'),
(315, 'RU', 'Asia/Novosibirsk', 7.00, 7.00, 7.00, 'no'),
(316, 'RU', 'Asia/Omsk', 7.00, 7.00, 7.00, 'no'),
(317, 'RU', 'Asia/Sakhalin', 11.00, 11.00, 11.00, 'no'),
(318, 'RU', 'Asia/Ust-Nera', 11.00, 11.00, 11.00, 'no'),
(319, 'RU', 'Asia/Vladivostok', 11.00, 11.00, 11.00, 'no'),
(320, 'RU', 'Asia/Yakutsk', 10.00, 10.00, 10.00, 'no'),
(321, 'RU', 'Asia/Yekaterinburg', 6.00, 6.00, 6.00, 'no'),
(322, 'RU', 'Europe/Kaliningrad', 3.00, 3.00, 3.00, 'no'),
(323, 'RU', 'Europe/Moscow', 4.00, 4.00, 4.00, 'no'),
(324, 'RU', 'Europe/Samara', 4.00, 4.00, 4.00, 'no'),
(325, 'RU', 'Europe/Volgograd', 4.00, 4.00, 4.00, 'no'),
(326, 'RW', 'Africa/Kigali', 2.00, 2.00, 2.00, 'no'),
(327, 'SA', 'Asia/Riyadh', 3.00, 3.00, 3.00, 'no'),
(328, 'SB', 'Pacific/Guadalcanal', 11.00, 11.00, 11.00, 'no'),
(329, 'SC', 'Indian/Mahe', 4.00, 4.00, 4.00, 'no'),
(330, 'SD', 'Africa/Khartoum', 3.00, 3.00, 3.00, 'no'),
(331, 'SE', 'Europe/Stockholm', 1.00, 2.00, 1.00, 'no'),
(332, 'SG', 'Asia/Singapore', 8.00, 8.00, 8.00, 'no'),
(333, 'SH', 'Atlantic/St_Helena', 0.00, 0.00, 0.00, 'no'),
(334, 'SI', 'Europe/Ljubljana', 1.00, 2.00, 1.00, 'no'),
(335, 'SJ', 'Arctic/Longyearbyen', 1.00, 2.00, 1.00, 'no'),
(336, 'SK', 'Europe/Bratislava', 1.00, 2.00, 1.00, 'no'),
(337, 'SL', 'Africa/Freetown', 0.00, 0.00, 0.00, 'no'),
(338, 'SM', 'Europe/San_Marino', 1.00, 2.00, 1.00, 'no'),
(339, 'SN', 'Africa/Dakar', 0.00, 0.00, 0.00, 'no'),
(340, 'SO', 'Africa/Mogadishu', 3.00, 3.00, 3.00, 'no'),
(341, 'SR', 'America/Paramaribo', -3.00, -3.00, -3.00, 'no'),
(342, 'SS', 'Africa/Juba', 3.00, 3.00, 3.00, 'no'),
(343, 'ST', 'Africa/Sao_Tome', 0.00, 0.00, 0.00, 'no'),
(344, 'SV', 'America/El_Salvador', -6.00, -6.00, -6.00, 'no'),
(345, 'SX', 'America/Lower_Princes', -4.00, -4.00, -4.00, 'no'),
(346, 'SY', 'Asia/Damascus', 2.00, 3.00, 2.00, 'no'),
(347, 'SZ', 'Africa/Mbabane', 2.00, 2.00, 2.00, 'no'),
(348, 'TC', 'America/Grand_Turk', -5.00, -4.00, -5.00, 'no'),
(349, 'TD', 'Africa/Ndjamena', 1.00, 1.00, 1.00, 'no'),
(350, 'TF', 'Indian/Kerguelen', 5.00, 5.00, 5.00, 'no'),
(351, 'TG', 'Africa/Lome', 0.00, 0.00, 0.00, 'no'),
(352, 'TH', 'Asia/Bangkok', 7.00, 7.00, 7.00, 'no'),
(353, 'TJ', 'Asia/Dushanbe', 5.00, 5.00, 5.00, 'no'),
(354, 'TK', 'Pacific/Fakaofo', 13.00, 13.00, 13.00, 'no'),
(355, 'TL', 'Asia/Dili', 9.00, 9.00, 9.00, 'no'),
(356, 'TM', 'Asia/Ashgabat', 5.00, 5.00, 5.00, 'no'),
(357, 'TN', 'Africa/Tunis', 1.00, 1.00, 1.00, 'no'),
(358, 'TO', 'Pacific/Tongatapu', 13.00, 13.00, 13.00, 'no'),
(359, 'TR', 'Europe/Istanbul', 2.00, 3.00, 2.00, 'no'),
(360, 'TT', 'America/Port_of_Spain', -4.00, -4.00, -4.00, 'no'),
(361, 'TV', 'Pacific/Funafuti', 12.00, 12.00, 12.00, 'no'),
(362, 'TW', 'Asia/Taipei', 8.00, 8.00, 8.00, 'no'),
(363, 'TZ', 'Africa/Dar_es_Salaam', 3.00, 3.00, 3.00, 'no'),
(364, 'UA', 'Europe/Kiev', 2.00, 3.00, 2.00, 'no'),
(365, 'UA', 'Europe/Simferopol', 2.00, 4.00, 4.00, 'no'),
(366, 'UA', 'Europe/Uzhgorod', 2.00, 3.00, 2.00, 'no'),
(367, 'UA', 'Europe/Zaporozhye', 2.00, 3.00, 2.00, 'no'),
(368, 'UG', 'Africa/Kampala', 3.00, 3.00, 3.00, 'no'),
(369, 'UM', 'Pacific/Johnston', -10.00, -10.00, -10.00, 'no'),
(370, 'UM', 'Pacific/Midway', -11.00, -11.00, -11.00, 'no'),
(371, 'UM', 'Pacific/Wake', 12.00, 12.00, 12.00, 'no'),
(372, 'US', 'America/Adak', -10.00, -9.00, -10.00, 'no'),
(373, 'US', 'America/Anchorage', -9.00, -8.00, -9.00, 'no'),
(374, 'US', 'America/Boise', -7.00, -6.00, -7.00, 'no'),
(375, 'US', 'America/Chicago', -6.00, -5.00, -6.00, 'no'),
(376, 'US', 'America/Denver', -7.00, -6.00, -7.00, 'no'),
(377, 'US', 'America/Detroit', -5.00, -4.00, -5.00, 'no'),
(378, 'US', 'America/Indiana/Indianapolis', -5.00, -4.00, -5.00, 'no'),
(379, 'US', 'America/Indiana/Knox', -6.00, -5.00, -6.00, 'no'),
(380, 'US', 'America/Indiana/Marengo', -5.00, -4.00, -5.00, 'no'),
(381, 'US', 'America/Indiana/Petersburg', -5.00, -4.00, -5.00, 'no'),
(382, 'US', 'America/Indiana/Tell_City', -6.00, -5.00, -6.00, 'no'),
(383, 'US', 'America/Indiana/Vevay', -5.00, -4.00, -5.00, 'no'),
(384, 'US', 'America/Indiana/Vincennes', -5.00, -4.00, -5.00, 'no'),
(385, 'US', 'America/Indiana/Winamac', -5.00, -4.00, -5.00, 'no'),
(386, 'US', 'America/Juneau', -9.00, -8.00, -9.00, 'no'),
(387, 'US', 'America/Kentucky/Louisville', -5.00, -4.00, -5.00, 'no'),
(388, 'US', 'America/Kentucky/Monticello', -5.00, -4.00, -5.00, 'no'),
(389, 'US', 'America/Los_Angeles', -8.00, -7.00, -8.00, 'no'),
(390, 'US', 'America/Menominee', -6.00, -5.00, -6.00, 'no'),
(391, 'US', 'America/Metlakatla', -8.00, -8.00, -8.00, 'no'),
(392, 'US', 'America/New_York', -5.00, -4.00, -5.00, 'no'),
(393, 'US', 'America/Nome', -9.00, -8.00, -9.00, 'no'),
(394, 'US', 'America/North_Dakota/Beulah', -6.00, -5.00, -6.00, 'no'),
(395, 'US', 'America/North_Dakota/Center', -6.00, -5.00, -6.00, 'no'),
(396, 'US', 'America/North_Dakota/New_Salem', -6.00, -5.00, -6.00, 'no'),
(397, 'US', 'America/Phoenix', -7.00, -7.00, -7.00, 'no'),
(398, 'US', 'America/Shiprock', -7.00, -6.00, -7.00, 'no'),
(399, 'US', 'America/Sitka', -9.00, -8.00, -9.00, 'no'),
(400, 'US', 'America/Yakutat', -9.00, -8.00, -9.00, 'no'),
(401, 'US', 'Pacific/Honolulu', -10.00, -10.00, -10.00, 'no'),
(402, 'UY', 'America/Montevideo', -2.00, -3.00, -3.00, 'no'),
(403, 'UZ', 'Asia/Samarkand', 5.00, 5.00, 5.00, 'no'),
(404, 'UZ', 'Asia/Tashkent', 5.00, 5.00, 5.00, 'no'),
(405, 'VA', 'Europe/Vatican', 1.00, 2.00, 1.00, 'no'),
(406, 'VC', 'America/St_Vincent', -4.00, -4.00, -4.00, 'no'),
(407, 'VE', 'America/Caracas', -4.50, -4.50, -4.50, 'no'),
(408, 'VG', 'America/Tortola', -4.00, -4.00, -4.00, 'no'),
(409, 'VI', 'America/St_Thomas', -4.00, -4.00, -4.00, 'no'),
(410, 'VN', 'Asia/Ho_Chi_Minh', 7.00, 7.00, 7.00, 'no'),
(411, 'VU', 'Pacific/Efate', 11.00, 11.00, 11.00, 'no'),
(412, 'WF', 'Pacific/Wallis', 12.00, 12.00, 12.00, 'no'),
(413, 'WS', 'Pacific/Apia', 14.00, 13.00, 13.00, 'no'),
(414, 'YE', 'Asia/Aden', 3.00, 3.00, 3.00, 'no'),
(415, 'YT', 'Indian/Mayotte', 3.00, 3.00, 3.00, 'no'),
(416, 'ZA', 'Africa/Johannesburg', 2.00, 2.00, 2.00, 'no'),
(417, 'ZM', 'Africa/Lusaka', 2.00, 2.00, 2.00, 'no'),
(418, 'ZW', 'Africa/Harare', 2.00, 2.00, 2.00, 'no');

-- --------------------------------------------------------

--
-- Table structure for table `time_slots`
--

CREATE TABLE `time_slots` (
  `id` bigint UNSIGNED NOT NULL,
  `seller_id` bigint DEFAULT NULL,
  `space_id` bigint DEFAULT NULL,
  `global_day_id` bigint DEFAULT NULL,
  `start_time` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `end_time` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `time_range` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_duration` int UNSIGNED DEFAULT NULL,
  `number_of_booking` int UNSIGNED DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `time_slot_rent` decimal(16,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_slots`
--

INSERT INTO `time_slots` (`id`, `seller_id`, `space_id`, `global_day_id`, `start_time`, `end_time`, `time_range`, `booking_duration`, `number_of_booking`, `is_available`, `time_slot_rent`, `created_at`, `updated_at`) VALUES
(1, 0, 1, 1, '02:00:00', '05:00:00', NULL, NULL, 2, 1, NULL, '2025-08-19 07:39:48', '2025-08-19 07:39:48'),
(2, 66, 4, 22, '01:00:00', '05:00:00', NULL, NULL, 1, 1, 200.00, '2025-08-22 09:20:38', '2025-08-22 09:20:38'),
(3, 66, 4, 22, '08:00:00', '11:00:00', NULL, NULL, 2, 1, 300.00, '2025-08-22 11:38:41', '2025-08-22 11:38:41'),
(4, 66, 4, 26, '03:00:00', '05:00:00', NULL, NULL, 2, 1, 100.00, '2025-08-22 11:39:36', '2025-08-22 11:39:36'),
(5, 66, 4, 26, '07:00:00', '09:00:00', NULL, NULL, 2, 1, 400.00, '2025-08-22 11:40:11', '2025-08-22 11:40:11'),
(6, 68, 10, 64, '02:00:00', '03:00:00', NULL, NULL, 2, 1, 45.00, '2025-08-23 12:27:43', '2025-08-29 10:37:54'),
(7, 68, 10, 65, '02:00:00', '04:00:00', NULL, NULL, 2, 1, 10.00, '2025-08-23 13:15:25', '2025-08-29 10:38:02'),
(8, 68, 10, 66, '03:00:00', '04:00:00', NULL, NULL, 2, 1, 5.00, '2025-08-23 13:16:18', '2025-08-29 10:38:11'),
(9, 0, 1, 2, '01:00:00', '02:00:00', NULL, NULL, 3, 1, NULL, '2025-08-26 07:29:24', '2025-08-26 07:29:24'),
(10, 67, 8, 50, '00:00:00', '02:00:00', NULL, NULL, 3, 1, 30.00, '2025-08-26 15:38:56', '2025-08-29 10:36:58');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `transcation_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_id` bigint DEFAULT NULL,
  `booking_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transcation_type` int DEFAULT NULL COMMENT '1= space booking, 2=Withdraw, 3= balance add, 4 = balance subtract, 5 = package purchase, 6 = space feature, 7 = product purchase,',
  `user_id` bigint DEFAULT NULL,
  `seller_id` bigint DEFAULT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grand_total` double(16,2) DEFAULT NULL,
  `tax` float(12,2) DEFAULT '0.00',
  `pre_balance` double(16,2) DEFAULT NULL,
  `after_balance` double(16,2) DEFAULT NULL,
  `gateway_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email_address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `zip_code` int DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 -> banned or deactive, 1 -> active',
  `verification_token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `provider` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_products`
--

CREATE TABLE `wishlist_products` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraws`
--

CREATE TABLE `withdraws` (
  `id` bigint UNSIGNED NOT NULL,
  `seller_id` bigint DEFAULT NULL,
  `withdraw_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method_id` int DEFAULT NULL,
  `amount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payable_amount` float(8,2) NOT NULL DEFAULT '0.00',
  `total_charge` float(8,2) NOT NULL DEFAULT '0.00',
  `additional_reference` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `feilds` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_method_inputs`
--

CREATE TABLE `withdraw_method_inputs` (
  `id` bigint UNSIGNED NOT NULL,
  `withdraw_payment_method_id` bigint DEFAULT NULL,
  `type` tinyint DEFAULT NULL COMMENT '1-text, 2-select, 3-checkbox, 4-textarea, 5-datepicker, 6-timepicker, 7-number',
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `placeholder` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `required` tinyint NOT NULL DEFAULT '0' COMMENT '1-required, 0- optional',
  `order_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_method_options`
--

CREATE TABLE `withdraw_method_options` (
  `id` bigint UNSIGNED NOT NULL,
  `withdraw_method_input_id` bigint DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_payment_methods`
--

CREATE TABLE `withdraw_payment_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `min_limit` double(8,2) DEFAULT NULL,
  `max_limit` double(8,2) DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `fixed_charge` float(8,2) DEFAULT '0.00',
  `percentage_charge` float(8,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdraw_payment_methods`
--

INSERT INTO `withdraw_payment_methods` (`id`, `min_limit`, `max_limit`, `name`, `status`, `fixed_charge`, `percentage_charge`, `created_at`, `updated_at`) VALUES
(1, 50.00, 200.00, 'Cash App', 1, 20.00, 10.00, '2025-09-08 12:32:12', '2025-09-08 12:32:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_contents`
--
ALTER TABLE `about_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `about_sections`
--
ALTER TABLE `about_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `additional_sections`
--
ALTER TABLE `additional_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `additional_section_contents`
--
ALTER TABLE `additional_section_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_username_unique` (`username`),
  ADD UNIQUE KEY `admins_email_unique` (`email`),
  ADD KEY `admins_role_id_foreign` (`role_id`);

--
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `basic_settings`
--
ALTER TABLE `basic_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_categories_language_id_foreign` (`language_id`);

--
-- Indexes for table `book_for_tours`
--
ALTER TABLE `book_for_tours`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_contents`
--
ALTER TABLE `contact_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cookie_alerts`
--
ALTER TABLE `cookie_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cookie_alerts_language_id_foreign` (`language_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faqs_language_id_foreign` (`language_id`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feature_charges`
--
ALTER TABLE `feature_charges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_contents`
--
ALTER TABLE `footer_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `footer_texts_language_id_foreign` (`language_id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `form_inputs`
--
ALTER TABLE `form_inputs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `get_quotes`
--
ALTER TABLE `get_quotes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `global_days`
--
ALTER TABLE `global_days`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_sliders`
--
ALTER TABLE `hero_sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_statics`
--
ALTER TABLE `hero_statics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mail_templates`
--
ALTER TABLE `mail_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `memberships`
--
ALTER TABLE `memberships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_builders`
--
ALTER TABLE `menu_builders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offline_gateways`
--
ALTER TABLE `offline_gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `online_gateways`
--
ALTER TABLE `online_gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_contents`
--
ALTER TABLE `page_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_contents_language_id_foreign` (`language_id`),
  ADD KEY `page_contents_page_id_foreign` (`page_id`);

--
-- Indexes for table `page_headings`
--
ALTER TABLE `page_headings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_headings_language_id_foreign` (`language_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment_invoices`
--
ALTER TABLE `payment_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `popular_city_sections`
--
ALTER TABLE `popular_city_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `popups`
--
ALTER TABLE `popups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `popups_language_id_foreign` (`language_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_informations`
--
ALTER TABLE `post_informations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_categories_language_id_foreign` (`language_id`);

--
-- Indexes for table `product_contents`
--
ALTER TABLE `product_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_contents_language_id_foreign` (`language_id`),
  ADD KEY `product_contents_product_category_id_foreign` (`product_category_id`),
  ADD KEY `product_contents_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_coupons`
--
ALTER TABLE `product_coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_orders`
--
ALTER TABLE `product_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `product_purchase_items`
--
ALTER TABLE `product_purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_purchase_items_product_order_id_foreign` (`product_order_id`),
  ADD KEY `product_purchase_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_shipping_charges`
--
ALTER TABLE `product_shipping_charges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_charges_language_id_foreign` (`language_id`);

--
-- Indexes for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `push_subscriptions_endpoint_unique` (`endpoint`),
  ADD KEY `push_subscriptions_subscribable_type_subscribable_id_index` (`subscribable_type`,`subscribable_id`);

--
-- Indexes for table `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quick_links`
--
ALTER TABLE `quick_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quick_links_language_id_foreign` (`language_id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section_contents`
--
ALTER TABLE `section_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section_titles`
--
ALTER TABLE `section_titles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sellers`
--
ALTER TABLE `sellers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller_infos`
--
ALTER TABLE `seller_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seos`
--
ALTER TABLE `seos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seos_language_id_foreign` (`language_id`);

--
-- Indexes for table `social_medias`
--
ALTER TABLE `social_medias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `spaces`
--
ALTER TABLE `spaces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `space_amenities`
--
ALTER TABLE `space_amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `space_bookings`
--
ALTER TABLE `space_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `space_categories`
--
ALTER TABLE `space_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `space_contents`
--
ALTER TABLE `space_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `space_coupons`
--
ALTER TABLE `space_coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `space_features`
--
ALTER TABLE `space_features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `space_holidays`
--
ALTER TABLE `space_holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `space_reviews`
--
ALTER TABLE `space_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `space_services`
--
ALTER TABLE `space_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `space_service_contents`
--
ALTER TABLE `space_service_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `space_settings`
--
ALTER TABLE `space_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `space_sub_categories`
--
ALTER TABLE `space_sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `space_wishlists`
--
ALTER TABLE `space_wishlists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscribers_email_id_unique` (`email_id`);

--
-- Indexes for table `sub_services`
--
ALTER TABLE `sub_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_service_contents`
--
ALTER TABLE `sub_service_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_conversations`
--
ALTER TABLE `ticket_conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timezones`
--
ALTER TABLE `timezones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_address_unique` (`email_address`) USING BTREE,
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indexes for table `wishlist_products`
--
ALTER TABLE `wishlist_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraws`
--
ALTER TABLE `withdraws`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_method_inputs`
--
ALTER TABLE `withdraw_method_inputs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_method_options`
--
ALTER TABLE `withdraw_method_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_payment_methods`
--
ALTER TABLE `withdraw_payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_contents`
--
ALTER TABLE `about_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `about_sections`
--
ALTER TABLE `about_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `additional_sections`
--
ALTER TABLE `additional_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `additional_section_contents`
--
ALTER TABLE `additional_section_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `basic_settings`
--
ALTER TABLE `basic_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `book_for_tours`
--
ALTER TABLE `book_for_tours`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_contents`
--
ALTER TABLE `contact_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cookie_alerts`
--
ALTER TABLE `cookie_alerts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=423;

--
-- AUTO_INCREMENT for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `feature_charges`
--
ALTER TABLE `feature_charges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `footer_contents`
--
ALTER TABLE `footer_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `form_inputs`
--
ALTER TABLE `form_inputs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `get_quotes`
--
ALTER TABLE `get_quotes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `global_days`
--
ALTER TABLE `global_days`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hero_sliders`
--
ALTER TABLE `hero_sliders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hero_statics`
--
ALTER TABLE `hero_statics`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `mail_templates`
--
ALTER TABLE `mail_templates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `memberships`
--
ALTER TABLE `memberships`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu_builders`
--
ALTER TABLE `menu_builders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `offline_gateways`
--
ALTER TABLE `offline_gateways`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `online_gateways`
--
ALTER TABLE `online_gateways`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000009;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `page_contents`
--
ALTER TABLE `page_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `page_headings`
--
ALTER TABLE `page_headings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_invoices`
--
ALTER TABLE `payment_invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `popular_city_sections`
--
ALTER TABLE `popular_city_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `popups`
--
ALTER TABLE `popups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `post_informations`
--
ALTER TABLE `post_informations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product_contents`
--
ALTER TABLE `product_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `product_coupons`
--
ALTER TABLE `product_coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_orders`
--
ALTER TABLE `product_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `product_purchase_items`
--
ALTER TABLE `product_purchase_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_shipping_charges`
--
ALTER TABLE `product_shipping_charges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qr_codes`
--
ALTER TABLE `qr_codes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quick_links`
--
ALTER TABLE `quick_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `section_contents`
--
ALTER TABLE `section_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `section_titles`
--
ALTER TABLE `section_titles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sellers`
--
ALTER TABLE `sellers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `seller_infos`
--
ALTER TABLE `seller_infos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `seos`
--
ALTER TABLE `seos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `social_medias`
--
ALTER TABLE `social_medias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `spaces`
--
ALTER TABLE `spaces`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `space_amenities`
--
ALTER TABLE `space_amenities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `space_bookings`
--
ALTER TABLE `space_bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `space_categories`
--
ALTER TABLE `space_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `space_contents`
--
ALTER TABLE `space_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `space_coupons`
--
ALTER TABLE `space_coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `space_features`
--
ALTER TABLE `space_features`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `space_holidays`
--
ALTER TABLE `space_holidays`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `space_reviews`
--
ALTER TABLE `space_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `space_services`
--
ALTER TABLE `space_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `space_service_contents`
--
ALTER TABLE `space_service_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `space_settings`
--
ALTER TABLE `space_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `space_sub_categories`
--
ALTER TABLE `space_sub_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `space_wishlists`
--
ALTER TABLE `space_wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sub_services`
--
ALTER TABLE `sub_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `sub_service_contents`
--
ALTER TABLE `sub_service_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ticket_conversations`
--
ALTER TABLE `ticket_conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timezones`
--
ALTER TABLE `timezones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=419;

--
-- AUTO_INCREMENT for table `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wishlist_products`
--
ALTER TABLE `wishlist_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraws`
--
ALTER TABLE `withdraws`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `withdraw_method_inputs`
--
ALTER TABLE `withdraw_method_inputs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw_method_options`
--
ALTER TABLE `withdraw_method_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw_payment_methods`
--
ALTER TABLE `withdraw_payment_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `role_permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD CONSTRAINT `blog_categories_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cookie_alerts`
--
ALTER TABLE `cookie_alerts`
  ADD CONSTRAINT `cookie_alerts_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faqs`
--
ALTER TABLE `faqs`
  ADD CONSTRAINT `faqs_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `footer_contents`
--
ALTER TABLE `footer_contents`
  ADD CONSTRAINT `footer_texts_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `page_contents`
--
ALTER TABLE `page_contents`
  ADD CONSTRAINT `page_contents_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `page_contents_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_purchase_items`
--
ALTER TABLE `product_purchase_items`
  ADD CONSTRAINT `product_purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_purchase_items_product_order_id_foreign` FOREIGN KEY (`product_order_id`) REFERENCES `product_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quick_links`
--
ALTER TABLE `quick_links`
  ADD CONSTRAINT `quick_links_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seos`
--
ALTER TABLE `seos`
  ADD CONSTRAINT `seos_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
