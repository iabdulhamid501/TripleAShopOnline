-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2026 at 12:48 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopping`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `billingAddress` text DEFAULT NULL,
  `billingCity` varchar(255) DEFAULT NULL,
  `billingState` varchar(255) DEFAULT NULL,
  `billingPincode` varchar(20) DEFAULT NULL,
  `billingCountry` varchar(100) DEFAULT NULL,
  `shippingAddress` text DEFAULT NULL,
  `shippingCity` varchar(255) DEFAULT NULL,
  `shippingState` varchar(255) DEFAULT NULL,
  `shippingPincode` varchar(20) DEFAULT NULL,
  `shippingCountry` varchar(100) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updationDate` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `creationDate`, `updationDate`) VALUES
(2, 'Dansmall', 'e10adc3949ba59abbe56e057f20f883e', '2026-04-08 10:23:16', ''),
(3, 'Chuman', 'e10adc3949ba59abbe56e057f20f883e', '2026-04-08 10:30:56', ''),
(4, 'Aminu', 'e10adc3949ba59abbe56e057f20f883e', '2026-04-08 10:31:13', ''),
(5, 'Nuhu', 'e10adc3949ba59abbe56e057f20f883e', '2026-04-08 10:37:28', '09-04-2026 09:31:31 PM'),
(6, 'Makurundu', 'e10adc3949ba59abbe56e057f20f883e', '2026-04-08 10:37:28', '');

-- --------------------------------------------------------

--
-- Table structure for table `admin_agent_messages`
--

CREATE TABLE `admin_agent_messages` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `sender_type` enum('agent','admin') NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_path` varchar(500) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_agent_messages`
--

INSERT INTO `admin_agent_messages` (`id`, `agent_id`, `admin_id`, `sender_type`, `message`, `is_read`, `created_at`, `file_path`, `file_type`) VALUES
(23, 8, NULL, 'agent', 'hey', 1, '2026-04-22 23:21:17', NULL, NULL),
(24, 8, NULL, 'agent', '', 1, '2026-04-22 23:21:26', 'uploads/agent_chats/agent_8_1776900086_5197.png', 'image/png'),
(25, 8, NULL, 'agent', '', 1, '2026-04-22 23:21:55', 'uploads/agent_chats/agent_8_1776900115_3602.mp4', 'video/mp4'),
(26, 8, 0, 'admin', '', 0, '2026-04-22 23:22:26', 'uploads/agent_chats/admin_Aminu_agent_8_1776900146_6977.png', 'image/png'),
(27, 8, 0, 'admin', '', 0, '2026-04-22 23:22:40', 'uploads/agent_chats/admin_Aminu_agent_8_1776900160_3531.mp3', 'audio/mpeg'),
(28, 8, 0, 'admin', '', 0, '2026-04-22 23:22:54', 'uploads/agent_chats/admin_Aminu_agent_8_1776900174_9609.mp4', 'video/mp4');

-- --------------------------------------------------------

--
-- Table structure for table `admin_delivery_messages`
--

CREATE TABLE `admin_delivery_messages` (
  `id` int(11) NOT NULL,
  `delivery_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `sender_type` enum('admin','delivery') NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`id`, `username`, `password`, `fullname`, `email`, `created_at`) VALUES
(8, 'Abdulhamdi', 'e10adc3949ba59abbe56e057f20f883e', 'Abdulhamid Ibrahim', 'abdulhamid@triplea.com', '2026-04-22 20:35:34');

-- --------------------------------------------------------

--
-- Table structure for table `agent_delivery_messages`
--

CREATE TABLE `agent_delivery_messages` (
  `id` int(11) NOT NULL,
  `delivery_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `sender_type` enum('agent','delivery') NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT 'Admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

CREATE TABLE `blog_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `status` enum('pending','approved','spam') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `categoryName` varchar(255) DEFAULT NULL,
  `categoryDescription` longtext DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `creationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updationDate` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `categoryName`, `categoryDescription`, `createdBy`, `creationDate`, `updationDate`) VALUES
(18, 'Electronics', 'Discover cutting?edge gadgets, devices, and gear for work, home, and entertainment.', 0, '2026-04-16 14:04:21', NULL),
(19, 'Fashion & Apparel', 'Elevate your style with trendy, comfortable, and season?ready clothing for all ages.', 0, '2026-04-16 14:04:58', NULL),
(20, 'Home & Living', 'Transform your space with functional, stylish furniture and décor for every room.', 0, '2026-04-16 14:05:30', NULL),
(21, 'Sports & Outdoors', 'Gear up for adventure, fitness, and outdoor recreation with high?performance equipment.', 0, '2026-04-16 14:06:02', NULL),
(22, 'Books & Media', 'Feed your curiosity with bestsellers, audiobooks, and collectible editions for all readers.', 0, '2026-04-16 14:06:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `sender_type` enum('customer','agent') NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_sessions`
--

CREATE TABLE `chat_sessions` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_size` varchar(50) DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internet_banking_payments`
--

CREATE TABLE `internet_banking_payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `productId` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `orderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `paymentMethod` varchar(50) DEFAULT NULL,
  `orderStatus` varchar(55) DEFAULT NULL,
  `delivery_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordertrackhistory`
--

CREATE TABLE `ordertrackhistory` (
  `id` int(11) NOT NULL,
  `orderId` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `remark` mediumtext DEFAULT NULL,
  `postingDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productreviews`
--

CREATE TABLE `productreviews` (
  `id` int(11) NOT NULL,
  `productId` int(11) DEFAULT NULL,
  `quality` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `review` longtext DEFAULT NULL,
  `reviewDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `subCategory` int(11) DEFAULT NULL,
  `productName` varchar(255) DEFAULT NULL,
  `productCompany` varchar(255) DEFAULT NULL,
  `productPrice` int(11) DEFAULT NULL,
  `productPriceBeforeDiscount` int(11) DEFAULT NULL,
  `productDescription` longtext DEFAULT NULL,
  `productImage1` varchar(255) DEFAULT NULL,
  `productImage2` varchar(255) DEFAULT NULL,
  `productImage3` varchar(255) DEFAULT NULL,
  `shippingCharge` int(11) DEFAULT NULL,
  `productAvailability` varchar(255) DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category`, `subCategory`, `productName`, `productCompany`, `productPrice`, `productPriceBeforeDiscount`, `productDescription`, `productImage1`, `productImage2`, `productImage3`, `shippingCharge`, `productAvailability`, `postingDate`, `updationDate`) VALUES
(29, 18, 21, 'SAMSUNG FOLD 7', 'SAMSUNG', 4290000, 4500000, '1. Benefit?Driven (Focus on User Experience)\r\nSamsung Galaxy Z Fold 7 – The Ultimate Foldable Powerhouse\r\nExperience the future of mobile with the Z Fold 7. Unfold a stunning 8.0?inch cinema?like screen for immersive gaming and multitasking, then fold it into a compact, pocket?friendly device. Capture pro?grade 200MP photos, work seamlessly with Galaxy AI, and enjoy all?day battery life – all in a design thinner and lighter than ever before.\r\n\r\n2. Bullet?Point Heavy (Ideal for Product Listings)\r\nSamsung Galaxy Z Fold 7 – Key Features\r\n\r\n8.0\" Dynamic AMOLED 2X main display – 120Hz adaptive refresh rate\r\n\r\n200MP pro camera – stunning low?light and 3x optical zoom\r\n\r\nSnapdragon 8 Elite for Galaxy – 41% faster AI performance\r\n\r\nThinnest Fold yet – 8.9mm folded, weighs only 215g\r\n\r\nIP48 dust/water resistance – durable hinge + Gorilla Glass Ceramic 2\r\n\r\nOne UI 8.0 + Android 16 – Gemini Live, Photo Assist, Audio Eraser\r\n\r\n4,400mAh battery – 25W wired & wireless charging\r\n\r\n3. Specs + Short Description (Best for Comparison Tables)\r\nSamsung Galaxy Z Fold 7 (2025)\r\nA perfect blend of tablet and phone. The Z Fold 7 features an 8.0?inch foldable main display, 200MP main camera, and the latest Snapdragon 8 Elite processor. With 12GB RAM, 256GB storage, and IP48 rating, it’s built for power users who demand portability without compromise. Available in Blue Shadow, Silver Shadow, and Jet Black.\r\n\r\n4. Lifestyle / Storytelling (For Product Story Section)\r\nWork. Play. Create. All on one screen.\r\nThe Samsung Galaxy Z Fold 7 isn’t just a phone – it’s your mobile studio, office, and cinema. Fold it open to compare spreadsheets side?by?side, then fold it shut to slip into your jeans. Take 200MP photos that rival dedicated cameras, or let Galaxy AI erase background noise from your videos. The thinnest, lightest Fold ever – because power shouldn’t weigh you down.\r\n\r\n5. SEO?Optimized Short Description (Meta / Quick Blurb)\r\nBuy Samsung Galaxy Z Fold 7 – 8.0\" Foldable 5G Smartphone\r\nThe 2025 Galaxy Z Fold 7 features a 200MP camera, Snapdragon 8 Elite chip, 120Hz main display, IP48 rating, and Android 16. Ultra?thin design at 8.9mm. 256GB/512GB/1TB options. Order now for free shipping.', '48d591840d392241e4bf901883708b98.jpg', '3ee5e17bd5c665281fb4467f9a9781a7.jpg', 'c8c84e1abad39bb0250fa847304d4c14.jpg', 170000, 'In Stock', '2026-04-16 14:36:45', NULL),
(30, 18, 21, 'Samsung Galaxy Z Flip6 – 512GB | Foldable, Stylish, Powerful', 'SAMSUNG', 1800000, 2000000, 'Folding Glass Display\r\nThe 6.7-inch Dynamic AMOLED 2X display folds in half seamlessly. Unfold it for a tablet-like experience, or fold it to palm-size perfection. With a buttery-smooth 120Hz refresh rate, scrolling and gaming feel effortless.\r\n512GB Internal Storage\r\nNever delete a memory again. Store thousands of photos, 4K videos, apps, and files without worrying about space. Perfect for creators and digital hoarders alike.\r\nPower & Performance\r\nPowered by the latest Snapdragon® 8 Gen 3 processor and 8GB RAM, the Flip6 handles heavy multitasking, gaming, and streaming like a breeze.\r\nCamera That Flips Perspective\r\nDual rear camera system (50MP main + 12MP ultra-wide) delivers crisp, vibrant shots day or night. Use the FlexCam mode – set the phone half-folded on any surface and capture hands-free vlogs, group photos, or time-lapses.\r\nAll?Day Battery + Fast Charging\r\nA larger 4000mAh battery keeps you going. And when you need a boost, Super Fast Charging gets you back up quickly\r\nBuilt to Last\r\nArmor Aluminum frame + Gorilla® Glass Victus® 2. Plus, it\'s IP48 dust and water resistant – one of the most durable foldables yet.\r\n? 6.7\" Foldable Dynamic AMOLED 2X, 120Hz\r\n\r\n? 512GB internal storage (no expandable)\r\n\r\n? Snapdragon 8 Gen 3 processor\r\n\r\n? 50MP main camera + 12MP ultra-wide\r\n\r\n? 4000mAh battery + 25W fast charging\r\n\r\n? IP48 water & dust resistance\r\n\r\n? 3.4\" cover screen (Flex Window)\r\n\r\n? Android 14 + 4 major OS upgrades promised', '10ad865712b960ce2dbaf14d7e3b0138.jpg', 'f08df500ebf9722727b28cbe28ca8f7b.jpg', 'fbae90ee7948648c3bca05dc2a5ac820.jpg', 100000, 'In Stock', '2026-04-16 15:22:07', NULL),
(31, 18, 21, 'Infinix Note 50pro+ 6.78\" AMOLED, 12GB RAM, 256GB, 50MP+32MP, 5G, 5200mAh, Titanium Grey', 'INFINIX', 999000, 1100000, 'Pro performance. Premium design. Non-stop power.\r\nMeet the Infinix Note 50 Pro+ – a smartphone that refuses to compromise. Whether you\'re a mobile gamer, photography lover, or productivity power user, this device delivers flagship-level features without the flagship price. Finished in sophisticated Titanium Grey, it looks as sharp as it performs.\r\nImmersive Display – 6.78\" AMOLED\r\nExperience true-to-life colors, deep blacks, and incredible contrast on the massive 6.78-inch AMOLED screen. With a buttery-smooth 120Hz refresh rate (assumed for Pro+), scrolling, gaming, and video streaming feel effortlessly fluid. Perfect for binge-watching or editing on the go.\r\nMassive Memory & Storage\r\n12GB RAM + 256GB ROM means no lag, no limits. Run multiple apps side by side, switch between games and social media instantly, and store thousands of photos, 4K videos, and files. Need more? Expandable storage via microSD (up to 1TB) gives you peace of mind.\r\nNext-Level Camera System\r\n50MP Primary Camera – Crisp, detailed shots in any light. AI scene optimization and night mode ensure your memories look stunning.\r\n32MP Secondary Camera – Ultra-wide or telephoto? Either way, you get professional-level framing and portrait shots with natural bokeh.\r\n32MP Front Camera – Flawless selfies and crystal-clear video calls. AI beauty mode keeps you looking your best.\r\nShoot 4K video, slow-motion, or time-lapse – the Note 50 Pro+ handles it all.\r\nBlazing Fast 5G Connectivity\r\nStream, download, and game without buffering. With dual 5G SIM support, you\'re ready for the future of mobile internet. Lag-free video calls and instant cloud access – wherever you are.\r\n5200mAh Battery – All-Day Power\r\nA massive 5200mAh battery easily gets you through a full day of heavy use. And when it\'s time to recharge, 68W fast charging (assumed, based on Infinix Note series trends) takes you from 0 to 80% in under 30 minutes. No more battery anxiety.\r\nSleek Titanium Grey Finish\r\nThe Titanium Grey colorway exudes elegance and durability. With a matte, smudge-resistant back and slim profile, this phone feels premium in hand and looks professional on any desk.\r\nSoftware & Extras\r\nAndroid 14 with XOS 14 (customizable, feature-rich)\r\nIn-display fingerprint sensor\r\nDual stereo speakers with DTS audio\r\nNFC for contactless payments\r\nIP53 splash resistance (light rain/dust)\r\nBullet Points for Quick Scan\r\n? 6.78\" AMOLED, 120Hz refresh rate\r\n? 12GB RAM + 256GB storage (expandable)\r\n? 50MP main + 32MP secondary (ultra-wide/telephoto) + 32MP selfie\r\n? 5G dual SIM support\r\n? 5200mAh battery + 68W fast charging\r\n? Titanium Grey premium matte finish\r\n? In-display fingerprint, NFC, stereo speakers\r\n? Android 14 with XOS 14\r\n\r\nSEO-Friendly Short Description (for product cards)\r\nInfinix Note 50 Pro+ 5G – 6.78\" AMOLED display, 12GB RAM, 256GB storage, 50MP+32MP dual camera, 5200mAh battery with fast charging, and Titanium Grey design. Flagship performance at a smart price. Order now.', 'ba6beffad52dfa6b486f4f70c8cb7dd6.jpg', 'b686e3ce69cb37cef94004c49dd2c405.jpg', '480e39c19ee8887494f138d527881363.jpg', 98900, 'In Stock', '2026-04-16 15:31:02', NULL),
(32, 20, 35, 'Ultra-Soft Living Room Chairs – Plush, Cozy & Supportive', 'BAJAJ', 650000, 0, 'Sink into Softness – Every Single Time\r\nTired of stiff, uncomfortable chairs? Meet our Ultra-Soft Living Room Chairs – designed for people who value relaxation above all else. Whether you\'re watching TV, reading a book, or just unwinding after a long day, these chairs hug you in plush comfort.\r\nWhy \"Mai Laushi Sosai\"?\r\nHigh-density foam cushioning – thick, resilient, and cloud-soft\r\nVelvety fabric or premium microfiber – gentle on skin, warm in winter\r\nPadded armrests & backrest – no hard edges, just pure coziness\r\nDeep seat design – curl up, stretch out, or sit upright – your choice\r\nBuilt for Everyday Living\r\n? Sturdy wooden or metal frame – supports up to 150kg\r\n? Removable, washable covers – easy to keep clean\r\n? Non-slip foot pads – protect your floors\r\n? Lightweight & easy to move – rearrange your space anytime\r\n? Available in multiple colors: Beige, Grey, Navy Blue, Dark Green, Wine Red\r\nPerfect for Any Living Room\r\nSmall apartments? Choose the compact size.\r\nFamily movie nights? Get the extra-wide lounger.\r\nElegant decor? Pick velvet or linen finishes.\r\nBullet Points for Quick Scan\r\n? Ultra-soft foam + fiber fill – mai laushi sosai\r\n? Skin-friendly fabric (no itching)\r\n? Deep seat + high back for full support\r\n? Removable, washable covers\r\n? Sturdy frame + non-slip base\r\n? Colors: Beige, Grey, Navy, Green, Wine\r\n? Weight capacity: 150kg', '80d0136628d8f99877cae0b6c4dd241b.jpg', '5fb9a1eb54285858e162b17cf40077bc.jpg', 'f8070a66a60a4bf63f20691b1d080188.jpg', 67890, 'In Stock', '2026-04-16 15:38:09', NULL),
(33, 19, 29, 'Sports And Leisure Shoes, Men\'S, Comfortable, Outdoor Commuting Casual Wear, Lightweight,', 'Nike', 36935, 67000, 'Step Into All-Day Comfort\r\nWhether you\'re commuting to work, running errands, or enjoying a weekend stroll, these Men\'s Sports & Leisure Shoes are designed to keep your feet happy from morning to night. Light as a feather, soft as a cloud – they\'re the perfect blend of sporty performance and casual style.\r\n\r\nWhy You\'ll Love Them\r\n? Ultra-Lightweight Design\r\nEach shoe weighs less than a bottle of water. You\'ll barely feel them on your feet – perfect for long walks, travel, or standing all day.\r\n\r\n? Breathable Mesh Upper\r\nNo more sweaty, stuffy feet. The airy mesh material allows constant airflow, keeping you cool and dry even under the sun.\r\n\r\n?? Cushioned Insole + Soft Midsole\r\nA thick, shock-absorbing insole molds to your foot shape, while the EVA midsole provides bounce-back support. It\'s like walking on cushions.\r\n\r\n? Built for Outdoor Commuting\r\n\r\nSlip-resistant rubber outsole – grips pavement, gravel, and light trails\r\n\r\nFlexible design – bends naturally with your foot\r\n\r\nPadded collar and tongue – no rubbing or blisters\r\n\r\n? Casual Style, Endless Pairings\r\nWear them with jeans, joggers, shorts, or chinos. Available in neutral colors (Black, Grey, Navy, White) that match everything.', '9855764b50d3b90f2f529df691705309.jpg', '7b809a914026e2defdf5cc9afce17184.jpg', '81af3cc6506abf6a24a52a5f2e68d6db.jpg', 2500, 'In Stock', '2026-04-16 15:47:49', NULL),
(34, 18, 22, 'Razer Blade 18 (2026 Model)', 'HP', 9900000, 10570000, 'Razer Blade 18 (2026 Model): Positioned as the premier 18-inch gaming laptop, it is the world\'s first with a dual-mode display, allowing users to switch between UHD+ 240 Hz for visual detail or FHD+ 440 Hz for high-speed gaming. It is packed with an Intel Core Ultra 9 275HX and NVIDIA GeForce RTX 5090 GPU.', '5367c329775ddc4b6e4df5caf232ba6a.webp', '1c0d9ecc8c1bcf154c4f214e1ed4e387.jpg', 'fa4ed29f2f4dac18c178f26b510fe749.jpg', 79000, 'In Stock', '2026-04-21 12:55:24', NULL),
(35, 18, 22, 'MSI Titan 18 HX AI', 'DELL', 10589000, 10900000, 'MSI Titan 18 HX AI: Described as a \"monster\" desktop replacement, it features a 17-18 inch 4K+ display, Intel Core Ultra 9 24-Core processor, and up to 64GB of RAM and 6TB SSD.', 'dff064d5228bacf5f88caf872a5cc4fb.webp', '6657143c8930670ccb048daf865986a8.webp', '2d5b619fdea3600e6de61f8b2e900e3b.webp', 0, 'In Stock', '2026-04-21 13:01:02', NULL),
(36, 18, 22, 'ASUS ROG Strix Scar 18', 'DELL', 10000000, 10268000, 'ASUS ROG Strix Scar 18: A leading contender in the 18-inch category featuring an Intel Core i9-14900HX processor and NVIDIA GeForce RTX 4090 GPU, known for its massive power and 240Hz refresh rate.', '5555d5cb4304d70438cacbccb730c400.jpg', 'a2d0ccfa7ae6bb39da59badaf5ad2947.jpg', '6cbde68d1b4ec24092aaf39aca07f09b.jpg', 16700, 'In Stock', '2026-04-21 13:04:53', NULL),
(37, 18, 22, 'Alienware m18 R2', 'DELL', 8890000, 9200000, 'Alienware m18 R2: Another powerful 18-inch option with a 14th Gen Intel Core i9-14900HX, RTX 4090 or 5080, and a 165Hz QHD+ display.', '5ea2a6c6bfd1e96b915dc552214ce95d.jpg', '3fd14e62b532415d0aa71853e3888c0e.jpg', 'bbad1985a2c491664ac72ed2b498068d.jpg', 26000, 'In Stock', '2026-04-21 13:09:22', NULL),
(38, 18, 22, 'MSI Raider GE78 HX', 'Micro-Star International', 10980000, 11450000, 'MSI Raider GE78 HX: A 17-inch powerhouse designed for high-frame-rate 1440p gaming, equipped with an Intel Core i9-14900HX and RTX 4080. \r\n PCMag +4', 'dfeac26748009a61e30965e3a44a3673.webp', '1dccb4a1a3f13791c84fd5fc4d343410.webp', 'aeb5d9078b0af21588f34f0a7aaa3a95.jpg', 0, 'In Stock', '2026-04-21 13:14:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `id` int(11) NOT NULL,
  `categoryid` int(11) DEFAULT NULL,
  `subcategoryName` varchar(255) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subcategory`
--

INSERT INTO `subcategory` (`id`, `categoryid`, `subcategoryName`, `createdBy`, `creationDate`, `updationDate`) VALUES
(21, 18, 'Smartphones', 0, '2026-04-16 14:07:17', NULL),
(22, 18, 'Laptops & Tablets', 0, '2026-04-16 14:07:42', NULL),
(23, 18, 'Audio & Headphones', 0, '2026-04-16 14:08:06', NULL),
(24, 18, 'Wearables', 0, '2026-04-16 14:08:29', NULL),
(25, 18, 'Home Office', 0, '2026-04-16 14:08:53', NULL),
(26, 19, 'Men’s Casual', 0, '2026-04-16 14:09:21', NULL),
(27, 19, 'Women’s Formal', 0, '2026-04-16 14:09:48', NULL),
(28, 19, 'Athleisure', 0, '2026-04-16 14:10:12', NULL),
(29, 19, 'Footwear', 0, '2026-04-16 14:10:40', NULL),
(30, 19, 'Accessories', 0, '2026-04-16 14:11:18', NULL),
(31, 20, 'Living Room', 0, '2026-04-16 14:11:48', NULL),
(32, 20, 'Kitchen & Dining', 0, '2026-04-16 14:12:36', NULL),
(33, 20, 'Bedroom', 0, '2026-04-16 14:12:59', NULL),
(34, 20, 'Bathroom', 0, '2026-04-16 14:13:27', NULL),
(35, 20, 'Decor', 0, '2026-04-16 14:13:53', NULL),
(36, 21, 'Camping', 0, '2026-04-16 14:14:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE `userlog` (
  `id` int(11) NOT NULL,
  `userEmail` varchar(255) DEFAULT NULL,
  `userip` binary(16) DEFAULT NULL,
  `loginTime` timestamp NULL DEFAULT current_timestamp(),
  `logout` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contactno` bigint(11) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `shippingAddress` longtext DEFAULT NULL,
  `shippingState` varchar(255) DEFAULT NULL,
  `shippingCity` varchar(255) DEFAULT NULL,
  `shippingPincode` int(11) DEFAULT NULL,
  `billingAddress` longtext DEFAULT NULL,
  `billingState` varchar(255) DEFAULT NULL,
  `billingCity` varchar(255) DEFAULT NULL,
  `billingPincode` int(11) DEFAULT NULL,
  `regDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `updationDate` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `productId` int(11) DEFAULT NULL,
  `postingDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_agent_messages`
--
ALTER TABLE `admin_agent_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agent_id` (`agent_id`);

--
-- Indexes for table `admin_delivery_messages`
--
ALTER TABLE `admin_delivery_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_id` (`delivery_id`);

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `agent_delivery_messages`
--
ALTER TABLE `agent_delivery_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_id` (`delivery_id`),
  ADD KEY `agent_id` (`agent_id`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `chat_sessions`
--
ALTER TABLE `chat_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `agent_id` (`agent_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `internet_banking_payments`
--
ALTER TABLE `internet_banking_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ordertrackhistory`
--
ALTER TABLE `ordertrackhistory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productreviews`
--
ALTER TABLE `productreviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `admin_agent_messages`
--
ALTER TABLE `admin_agent_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `admin_delivery_messages`
--
ALTER TABLE `admin_delivery_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `agent_delivery_messages`
--
ALTER TABLE `agent_delivery_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `blog_comments`
--
ALTER TABLE `blog_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `chat_sessions`
--
ALTER TABLE `chat_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `internet_banking_payments`
--
ALTER TABLE `internet_banking_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `ordertrackhistory`
--
ALTER TABLE `ordertrackhistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `productreviews`
--
ALTER TABLE `productreviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
